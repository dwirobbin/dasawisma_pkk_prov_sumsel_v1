<?php

namespace App\Livewire\App\Backend\Roles;

use App\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

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
    public function roles(): LengthAwarePaginator|Collection
    {
        return Role::query()
            ->select(['id', 'name', 'slug', 'created_at'])
            ->withCount('permissions')
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

        ($this->roles()->currentPage() <= $this->roles()->lastPage())
            ? $this->setPage($this->roles()->currentPage())
            : $this->setPage($this->roles()->lastPage());

        return view('livewire.app.backend.roles.table');
    }

    private function getDataOnCurrentPage(): array
    {
        return $this->roles()->pluck('id')->toArray();
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
