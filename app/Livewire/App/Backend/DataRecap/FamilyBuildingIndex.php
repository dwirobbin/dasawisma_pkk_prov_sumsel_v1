<?php

namespace App\Livewire\App\Backend\DataRecap;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\FamilyBuilding;
use Illuminate\Database\Eloquent\Builder;
use RalphJSmit\Livewire\Urls\Facades\Url as LivewireUrl;

class FamilyBuildingIndex extends Component
{
    use WithPagination;

    public $param = '';

    public int $perPage = 5;

    public array $familyBuildings = [];
    public bool $readyToLoad = false;

    #[Url()]
    public string $search = '';

    public ?string $currentUrl = NULL;

    public function mount()
    {
        $this->currentUrl = LivewireUrl::current();
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->getData();
    }

    public function getData($currentPage = null)
    {
        $param = match (true) {
            str_contains($this->currentUrl, '/index') => 'index',
            str_contains($this->currentUrl, '/area-code') && strlen($this->param) == 4 => $this->param,
            str_contains($this->currentUrl, '/area-code') && strlen($this->param) == 7 => $this->param,
            str_contains($this->currentUrl, '/area-code') && strlen($this->param) == 10 => $this->param,
            default => 'dasawisma'
        };

        $user = auth()->user();

        $this->familyBuildings = FamilyBuilding::query()
            ->selectRaw("
                COUNT(CASE WHEN family_buildings.staple_food = 'Beras' THEN 1 END) AS rice_foods_count,
                COUNT(CASE WHEN family_buildings.staple_food = 'Non Beras' THEN 1 END) AS etc_rice_foods_count,
                COUNT(family_buildings.have_toilet) AS have_toilets_count,
                COUNT(CASE WHEN family_buildings.water_src LIKE '%PDAM%' THEN 1 END) AS pdam_waters_count,
                COUNT(CASE WHEN family_buildings.water_src LIKE '%Sumur%' THEN 1 END) AS well_waters_count,
                COUNT(CASE WHEN family_buildings.water_src LIKE '%Sungai%' THEN 1 END) AS river_waters_count,
                COUNT(CASE WHEN family_buildings.water_src LIKE '%Lainnya%' THEN 1 END) AS etc_waters_count,
                COUNT(family_buildings.have_landfill) AS have_landfills_count,
                COUNT(family_buildings.have_sewerage) AS have_sewerages_count,
                COUNT(family_buildings.pasting_p4k_sticker) AS pasting_p4k_stickers_count,
                COUNT(CASE WHEN family_buildings.house_criteria = 'Sehat' THEN 1 END) AS healthy_criterias_count,
                COUNT(CASE WHEN family_buildings.house_criteria = 'Kurang Sehat' THEN 1 END) AS no_healthy_criterias_count
            ")
            ->join('family_heads', 'family_buildings.family_head_id', '=', 'family_heads.id')
            ->join('dasawismas', 'family_heads.dasawisma_id', '=', 'dasawismas.id')
            ->when($param == 'index', function (Builder $query) {
                $query->addSelect('regencies.id', 'regencies.name')
                    ->join('regencies', 'dasawismas.regency_id', '=', 'regencies.id')
                    ->where('dasawismas.province_id', '=', 16)
                    ->when($this->search != '', function (Builder $query) {
                        $query->where('regencies.name', 'LIKE', '%' . trim($this->search) . '%');
                    })
                    ->groupBy('regencies.id')
                    ->orderBy('dasawismas.regency_id', 'ASC');
            })
            ->when(strlen((string) $param) == 4, function (Builder $query) {
                $query->addSelect('districts.id', 'districts.name')
                    ->join('districts', 'dasawismas.district_id', '=', 'districts.id')
                    ->where('dasawismas.regency_id', '=', $this->param)
                    ->when($this->search != '', function (Builder $query) {
                        $query->where('districts.name', 'LIKE', '%' . trim($this->search) . '%');
                    })
                    ->groupBy('districts.id')
                    ->orderBy('dasawismas.regency_id', 'ASC');
            })
            ->when(strlen((string) $param) == 7, function (Builder $query) {
                $query->addSelect('villages.id', 'villages.name')
                    ->join('villages', 'dasawismas.village_id', '=', 'villages.id')
                    ->where('dasawismas.district_id', '=', $this->param)
                    ->when($this->search != '', function (Builder $query) {
                        $query->where('villages.name', 'LIKE', '%' . trim($this->search) . '%');
                    })
                    ->groupBy('villages.id')
                    ->orderBy('dasawismas.district_id', 'ASC');
            })
            ->when(strlen((string) $param) == 10, function (Builder $query) {
                $query->addSelect('dasawismas.id', 'dasawismas.name', 'dasawismas.slug')
                    ->where('dasawismas.village_id', '=', $this->param)
                    ->when($this->search != '', function (Builder $query) {
                        $query->where('dasawismas.name', 'LIKE', '%' . trim($this->search) . '%');
                    })
                    ->groupBy('dasawismas.id')
                    ->orderBy('dasawismas.village_id', 'ASC');
            })
            ->when($param == 'dasawisma', function (Builder $query) {
                $query->addSelect('family_heads.id', 'family_heads.family_head AS name', 'family_heads.kk_number')
                    ->where('dasawismas.slug', '=', $this->param)
                    ->when($this->search != '', function (Builder $query) {
                        $query->where('family_heads.family_head', 'LIKE', '%' . trim($this->search) . '%');
                    })
                    ->groupBy('family_heads.id')
                    ->orderBy('family_buildings.family_head_id', 'ASC');
            })
            ->when($user->role_id == 2 && $user->admin->village_id != NULL, function (Builder $query) use ($user) {
                $query->where('dasawismas.village_id', '=', $user->admin->village_id);
            })
            ->when($user->role_id == 2 && $user->admin->district_id != NULL, function (Builder $query) use ($user) {
                $query->where('dasawismas.district_id', '=', $user->admin->district_id);
            })
            ->when($user->role_id == 2 && $user->admin->regency_id != NULL, function (Builder $query) use ($user) {
                $query->where('dasawismas.regency_id', '=', $user->admin->regency_id);
            })
            ->when($user->role_id == 2 && $user->admin->province_id != NULL, function (Builder $query) use ($user) {
                $query->where('dasawismas.province_id', '=', $user->admin->province_id);
            })
            ->simplePaginate($this->perPage, ['*'], 'page', $currentPage ?? $this->getPage())
            ->toArray();

        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.app.backend.data-recap.family-building-index');
    }

    public function goToPrevPage()
    {
        $this->getData($this->previousPage());
    }

    public function goToNextPage()
    {
        $this->getData($this->nextPage());
    }
}
