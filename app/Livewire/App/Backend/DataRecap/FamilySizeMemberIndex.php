<?php

namespace App\Livewire\App\Backend\DataRecap;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\FamilySizeMember;
use Illuminate\Database\Eloquent\Builder;
use RalphJSmit\Livewire\Urls\Facades\Url as LivewireUrl;

class FamilySizeMemberIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $param = '';

    public int $perPage = 5;

    #[Url()]
    public string $search = '';

    public ?string $currentUrl = NULL;

    public function mount()
    {
        $this->currentUrl = LivewireUrl::current();
    }

    public function placeholder()
    {
        return view('placeholder');
    }

    public function paginationView(): string
    {
        return 'paginations.custom-pagination-links';
    }

    public function render()
    {
        $param = match (true) {
            str_contains($this->currentUrl, '/index') => 'index',
            str_contains($this->currentUrl, '/area-code') && strlen($this->param) == 4 => $this->param,
            str_contains($this->currentUrl, '/area-code') && strlen($this->param) == 7 => $this->param,
            str_contains($this->currentUrl, '/area-code') && strlen($this->param) == 10 => $this->param,
            default => 'dasawisma'
        };

        $familySizeMembers = FamilySizeMember::query()
            ->selectRaw("
                SUM(toddlers_number) AS toddlers_sum,
                SUM(pus_number) AS pus_sum,
                SUM(wus_number) AS wus_sum,
                SUM(blind_people_number) AS blind_peoples_sum,
                SUM(pregnant_women_number) AS pregnant_womens_sum,
                SUM(breastfeeding_mother_number) AS breastfeeding_mothers_sum,
                SUM(elderly_number) AS elderlies_sum
            ")
            ->join('family_heads', 'family_size_members.family_head_id', '=', 'family_heads.id')
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
            ->when(strlen($param) == 4, function (Builder $query) {
                $query->addSelect('districts.id', 'districts.name')
                    ->join('districts', 'dasawismas.district_id', '=', 'districts.id')
                    ->where('dasawismas.regency_id', '=', $this->param)
                    ->when($this->search != '', function (Builder $query) {
                        $query->where('districts.name', 'LIKE', '%' . trim($this->search) . '%');
                    })
                    ->groupBy('districts.id')
                    ->orderBy('dasawismas.regency_id', 'ASC');
            })
            ->when(strlen($param) == 7, function (Builder $query) {
                $query->addSelect('villages.id', 'villages.name')
                    ->join('villages', 'dasawismas.village_id', '=', 'villages.id')
                    ->where('dasawismas.district_id', '=', $this->param)
                    ->when($this->search != '', function (Builder $query) {
                        $query->where('villages.name', 'LIKE', '%' . trim($this->search) . '%');
                    })
                    ->groupBy('villages.id')
                    ->orderBy('dasawismas.district_id', 'ASC');
            })
            ->when(strlen($param) == 10, function (Builder $query) {
                $query->addSelect('dasawismas.id', 'dasawismas.name', 'dasawismas.slug')
                    ->where('dasawismas.village_id', '=', $this->param)
                    ->when($this->search != '', function (Builder $query) {
                        $query->where('dasawismas.name', 'LIKE', '%' . trim($this->search) . '%');
                    })
                    ->groupBy('dasawismas.id')
                    ->orderBy('dasawismas.village_id', 'ASC');
            })
            ->when($param == 'dasawisma', function (Builder $query) {
                $query->addSelect('family_heads.id', 'family_heads.family_head AS name')
                    ->where('dasawismas.slug', '=', $this->param)
                    ->when($this->search != '', function (Builder $query) {
                        $query->where('family_heads.family_head', 'LIKE', '%' . trim($this->search) . '%');
                    })
                    ->groupBy('family_heads.id')
                    ->orderBy('family_size_members.family_head_id', 'ASC');
            })
            ->when(
                auth()->user()->role_id == 2 && auth()->user()->admin->regency_id != NULL,
                function (Builder $query) {
                    $query->where('dasawismas.regency_id', '=', auth()->user()->admin->regency_id);
                }
            )
            ->paginate($this->perPage);

        return view('livewire.app.backend.data-recap.family-size-member-index', [
            'data' => $familySizeMembers,
        ]);
    }
}
