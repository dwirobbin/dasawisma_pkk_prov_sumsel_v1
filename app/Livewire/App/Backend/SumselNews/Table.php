<?php

namespace App\Livewire\App\Backend\SumselNews;

use Livewire\Component;
use App\Models\SumselNews;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class Table extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public int $perPage = 5;

    #[Url]
    public string $search = '', $sortDirection = 'desc';

    public string $sortColumn = 'created_at';

    public bool $bulkSelectedDisabled = false, $bulkSelectAll = false;
    public $bulkSelected = [];

    public function placeholder(): View
    {
        return view('placeholder');
    }

    #[Computed()]
    public function sumselNews(): LengthAwarePaginator|Collection
    {
        return SumselNews::query()
            ->select([
                'sumsel_news.id',
                'sumsel_news.title',
                'sumsel_news.slug',
                'sumsel_news.image',
                'sumsel_news.is_published',
                'sumsel_news.created_at',
                'users.name AS author',
            ])
            ->join('users', 'sumsel_news.author_id', '=', 'users.id')
            ->when(
                // prov
                auth()->user()->role_id == 2 && auth()->user()->admin->province_id != NULL && auth()->user()->admin->regency_id == NULL,
                function (Builder $query) {
                    $query
                        ->whereRelation('author', 'role_id', '=', 2)
                        ->whereRelation('author.admin', 'province_id', '!=', NULL);
                }
            )
            ->when(
                // kota
                auth()->user()->role_id == 2 && auth()->user()->admin->regency_id != NULL,
                function (Builder $query) {
                    $query
                        ->whereRelation('author', 'role_id', '=', 2)
                        ->whereRelation('author.admin', 'regency_id', '=', auth()->user()->admin->regency_id);
                }
            )
            ->when(
                // kecamatan
                auth()->user()->role_id == 2 && auth()->user()->admin->district_id != NULL,
                function (Builder $query) {
                    $query
                        ->whereRelation('author', 'role_id', '=', 2)
                        ->whereRelation('author.admin', 'district_id', '=', auth()->user()->admin->district_id);
                }
            )
            ->when(
                // kelurahan
                auth()->user()->role_id == 2 && auth()->user()->admin->village_id != NULL,
                function (Builder $query) {
                    $query
                        ->whereRelation('author', 'role_id', '=', 2)
                        ->whereRelation('author.admin', 'village_id', '=', auth()->user()->admin->village_id);
                }
            )
            ->search(trim($this->search))
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->perPage)
            ->onEachSide(1);
    }

    public function updatedPage(): void
    {
        $this->bulkSelectAll = $this->determineBulkSelectAll($this->getDataOnCurrentPage(), $this->bulkSelected);
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();

        $this->bulkSelectAll = $this->determineBulkSelectAll($this->getDataOnCurrentPage(), $this->bulkSelected);

        if ($this->determineBulkSelectAll($this->getDataOnCurrentPage(), $this->bulkSelected)) {
            $this->bulkSelected = array_keys(array_flip(array_merge($this->bulkSelected, $this->getDataOnCurrentPage())));
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedBulkSelectAll(): void
    {
        $this->bulkSelected = $this->determineBulkSelectAll($this->getDataOnCurrentPage(), $this->bulkSelected)
            ? array_keys(array_flip(array_diff($this->bulkSelected, $this->getDataOnCurrentPage())))
            : array_keys(array_flip(array_merge($this->bulkSelected, $this->getDataOnCurrentPage())));
    }

    public function updatedBulkSelected(): void
    {
        $this->bulkSelectAll = $this->determineBulkSelectAll($this->getDataOnCurrentPage(), $this->bulkSelected);
    }

    public function paginationView(): string
    {
        return 'paginations.custom-pagination-links';
    }

    #[On('refresh-data')]
    public function render(): View
    {
        $this->bulkSelectedDisabled = count($this->bulkSelected) < 2;

        ($this->sumselNews()->currentPage() <= $this->sumselNews()->lastPage())
            ? $this->setPage($this->sumselNews()->currentPage())
            : $this->setPage($this->sumselNews()->lastPage());

        return view('livewire.app.backend.sumsel-news.table');
    }

    private function getDataOnCurrentPage(): array
    {
        return $this->sumselNews()->pluck('id')->toArray();
    }

    private function determineBulkSelectAll(array $dataOnCurrentPage, array $bulkSelected): bool
    {
        return empty(array_diff($dataOnCurrentPage, $bulkSelected));
    }

    #[On('sort-by')]
    public function sortBy(string $columnName): void
    {
        if ($this->sortColumn == $columnName) {
            $this->sortDirection = ($this->sortDirection == 'asc') ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortColumn = $columnName;
    }

    #[On('clear-selected')]
    public function clearSelected(): void
    {
        $this->bulkSelected = [];
        $this->bulkSelectAll = false;
    }
}
