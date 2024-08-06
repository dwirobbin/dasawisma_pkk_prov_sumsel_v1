<?php

namespace App\Livewire\App\Backend\DataInput\Members\FamilyActivities;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\FamilyActivity;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class Table extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public int $perPage = 5;

    #[Url()]
    public string $search = '', $sortDirection = 'desc';

    public string $sortColumn = 'fa.created_at';

    public bool $bulkSelectedDisabled = false, $bulkSelectAll = false;
    public $bulkSelected = [];

    public EloquentCollection|array $provinces = [], $regencies = [], $districts = [], $villages = [];

    public function placeholder(): View
    {
        return view('placeholder');
    }

    #[Computed()]
    public function familyActivities(): LengthAwarePaginator|Collection
    {
        return FamilyActivity::from('family_activities AS fa')
            ->selectRaw("
                fa.id,
                IF(dsw.name IS NULL, 'Belum di set', dsw.name) AS dasawisma_name,
                IF(fh.dasawisma_id IS NULL, 'Belum di set', CONCAT_WS(', ', p.name, r.name, d.name, v.name)) AS area,
                fh.family_head, fh.kk_number,
                IF(fa.up2k_activity IS NULL, 'Tidak Ada', fa.up2k_activity) as up2k_activity,
                IF(fa.env_health_activity IS NULL, 'Tidak Ada', fa.env_health_activity) as env_health_activity
            ")
            ->leftJoin('family_heads AS fh', 'fa.family_head_id', '=', 'fh.id')
            ->leftJoin('dasawismas AS dsw', 'fh.dasawisma_id', '=', 'dsw.id')
            ->leftJoin('provinces AS p', 'dsw.province_id', '=', 'p.id')
            ->leftJoin('regencies AS r', 'dsw.regency_id', '=', 'r.id')
            ->leftJoin('districts AS d', 'dsw.district_id', '=', 'd.id')
            ->leftJoin('villages AS v', 'dsw.village_id', '=', 'v.id')
            ->when($this->search != '',  function (Builder $query) {
                $query->where('dsw.name', 'LIKE', "%{$this->search}%")
                    ->orWhere('p.name', 'LIKE', "%{$this->search}%")
                    ->orWhere('r.name', 'LIKE', "%{$this->search}%")
                    ->orWhere('d.name', 'LIKE', "%{$this->search}%")
                    ->orWhere('v.name', 'LIKE', "%{$this->search}%")
                    ->orWhere('fh.family_head', 'LIKE', "%{$this->search}%")
                    ->orWhere('fa.up2k_activity', 'LIKE', "%{$this->search}%")
                    ->orWhere('fa.env_health_activity', 'LIKE', "%{$this->search}%");
            })
            ->when(
                auth()->user()->role_id == 2 && auth()->user()->admin->district_id != NULL
                    && (auth()->user()->admin->village_id == NULL || auth()->user()->admin->village_id != NULL),
                function (Builder $query) {
                    $query->where('d.id', '=', auth()->user()->admin->district_id)
                        ->orWhere('v.id', '=', auth()->user()->admin->village_id);
                }
            )
            ->when(
                auth()->user()->role_id == 2 && auth()->user()->admin->regency_id != NULL,
                function (Builder $query) {
                    $query->where('r.id', '=', auth()->user()->admin->regency_id);
                }
            )
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->perPage)
            ->onEachSide(1);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function paginationView(): string
    {
        return 'paginations.custom-pagination-links';
    }

    #[On('refresh-data')]
    public function render()
    {
        $this->bulkSelectedDisabled = count($this->bulkSelected) < 2;

        if (method_exists($this->familyActivities(), 'currentPage')) {
            ($this->familyActivities()->currentPage() <= $this->familyActivities()->lastPage())
                ? $this->setPage($this->familyActivities()->currentPage())
                : $this->setPage($this->familyActivities()->lastPage());
        }

        return view('livewire.app.backend.data-input.members.family-activities.table');
    }

    #[On('sort-by')]
    public function sortBy(string $columnName): void
    {
        if ($this->sortColumn == $columnName) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortColumn = $columnName;
    }
}
