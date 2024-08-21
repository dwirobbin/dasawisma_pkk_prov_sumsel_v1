<?php

namespace App\Livewire\App\Backend\DataInput\Members\FamilyMembers;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\FamilyMember;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\Paginator;

class Table extends Component
{
    use WithPagination;

    public int $perPage = 5;

    #[Url]
    public string $search = '', $sortDirection = 'desc';

    public string $sortColumn = 'family_members.created_at';

    public Collection|array $provinces = [], $regencies = [], $districts = [], $villages = [];

    public function placeholder(): View
    {
        return view('placeholder');
    }

    #[Computed()]
    public function familyMembers(): Paginator
    {
        $familyMembers = FamilyMember::query()
            ->leftJoin('family_heads', 'family_members.family_head_id', '=', 'family_heads.id')
            ->leftJoin('dasawismas', 'family_heads.dasawisma_id', '=', 'dasawismas.id')
            ->leftJoin('provinces', 'dasawismas.province_id', '=', 'provinces.id')
            ->leftJoin('regencies', 'dasawismas.regency_id', '=', 'regencies.id')
            ->leftJoin('districts', 'dasawismas.district_id', '=', 'districts.id')
            ->leftJoin('villages', 'dasawismas.village_id', '=', 'villages.id')
            ->selectRaw("
                family_members.id,
                IF(dasawismas.name IS NULL, 'Belum di set', dasawismas.name) AS dasawisma_name,
                IF(family_heads.dasawisma_id IS NULL, 'Belum di set', CONCAT_WS(', ', provinces.name, regencies.name, districts.name, villages.name)) AS area,
                family_heads.kk_number,
                family_members.family_head_id,
                family_members.name, family_members.nik_number, family_members.birth_date, family_members.status,
                family_members.marital_status, family_members.gender, family_members.last_education, family_members.profession, family_members.created_at
            ")
            ->when($this->search != '', fn(Builder $query) => $query->search(trim($this->search)))
            ->when(
                auth()->user()->role_id == 2 && auth()->user()->admin->district_id != NULL
                    && (auth()->user()->admin->village_id == NULL || auth()->user()->admin->village_id != NULL),
                function (Builder $query) {
                    $query->where('districts.id', '=', auth()->user()->admin->district_id)
                        ->orWhere('villages.id', '=', auth()->user()->admin->village_id);
                }
            )
            ->when(
                auth()->user()->role_id == 2 && auth()->user()->admin->regency_id != NULL,
                function (Builder $query) {
                    $query->where('regencies.id', '=', auth()->user()->admin->regency_id);
                }
            )
            ->orderBy($this->sortColumn,  $this->sortDirection)
            ->orderBy('family_members.family_head_id', 'DESC')
            ->orderBy('family_members.status',  'ASC')
            ->simplePaginate($this->perPage);

        return $familyMembers->setCollection($familyMembers->groupBy(fn($column) => $column->kk_number));
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    #[On('refresh-data')]
    public function render()
    {
        return view('livewire.app.backend.data-input.members.family-members.table');
    }
}
