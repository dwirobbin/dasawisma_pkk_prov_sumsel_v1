<?php

namespace App\Livewire\App\Backend\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class Card extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public int $perPage = 4;

    #[Url]
    public string $search = '', $sortDirection = 'desc', $author = '';

    public string $sortColumn = 'created_at';

    public function placeholder(): View
    {
        return view('placeholder');
    }

    #[Computed()]
    public function users(): LengthAwarePaginator|Collection
    {
        return User::query()
            ->select('id', 'name', 'username', 'email', 'role_id', 'photo', 'is_active')
            ->with(['role:id,name', 'admin'])
            ->search(trim($this->search))
            ->when(
                // prov
                auth()->user()->role_id == 2 && auth()->user()->admin->province_id != NULL && auth()->user()->admin->regency_id == NULL,
                function (Builder $query) {
                    $query
                        ->where('role_id', '=', 2)
                        ->whereRelation('admin', 'province_id', '!=', NULL);
                }
            )
            ->when(
                // kota
                auth()->user()->role_id == 2 && auth()->user()->admin->regency_id != NULL,
                function (Builder $query) {
                    $query
                        ->where('role_id', '=', 2)
                        ->whereRelation('admin', 'regency_id', '=', auth()->user()->admin->regency_id);
                }
            )
            ->when(
                // kecamatan
                auth()->user()->role_id == 2 && auth()->user()->admin->district_id != NULL,
                function (Builder $query) {
                    $query
                        ->where('role_id', '=', 2)
                        ->whereRelation('admin', 'district_id', '=', auth()->user()->admin->district_id);
                }
            )
            ->when(
                // kelurahan
                auth()->user()->role_id == 2 && auth()->user()->admin->village_id != NULL,
                function (Builder $query) {
                    $query
                        ->where('role_id', '=', 2)
                        ->whereRelation('admin', 'village_id', '=', auth()->user()->admin->village_id);
                }
            )
            ->when(
                // kelurahan
                auth()->user()->role_id == 3,
                function (Builder $query) {
                    $query->where('role_id', '=', 3);
                }
            )
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->perPage)
            ->onEachSide(1);
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[On('refresh-data')]
    public function render(): View
    {
        return view('livewire.app.backend.users.card');
    }

    public function paginationView(): string
    {
        return 'paginations.custom-pagination-links';
    }

    public function setSort(string $sortDirection)
    {
        $this->sortDirection = ($sortDirection === 'desc') ? 'desc' : 'asc';
    }
}
