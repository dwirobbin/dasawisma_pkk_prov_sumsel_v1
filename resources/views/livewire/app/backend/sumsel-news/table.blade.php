<div>
    <div class="card-header py-2 d-flex flex-wrap justify-content-center justify-content-md-between gap-2">
        <div class="text-muted">
            <label>
                Lihat
                <select class="d-inline-block form-select w-auto" wire:model.live='perPage'>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="35">35</option>
                    <option value="50">50</option>
                    <option value="65">65</option>
                    <option value="85">85</option>
                    <option value="100">100</option>
                    @if (count($this->sumselNews))
                        <option value="{{ $this->sumselNews->total() }}">Semua</option>
                    @endif
                </select>
                data
            </label>
        </div>
        {{-- @if (auth()->user()->role_id != 3)
            <div class="text-muted">
                <div class="dropdown-center btn-group" x-data="{ open: false }" x-on:click.outside="open = false">
                    <button x-on:click="open = !open" :class="{ 'show': open == true }" class="btn btn-info dropdown-toggle"
                        @disabled($bulkSelectedDisabled) wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="bulkSelected, bulkSelectAll">
                            Terpilih ({{ count($bulkSelected) }}) Data
                        </span>

                        <span wire:loading wire:target="bulkSelected, bulkSelectAll" role="status"
                            class="spinner-border spinner-border-sm"></span>&ensp;
                        <span wire:loading wire:target="bulkSelected, bulkSelectAll" role="status">Loading..</span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" x-show="open" :class="{ 'show': open == true }" x-transition
                        :data-bs-popper="{ 'static': open == true }">
                        <li>
                            <button x-on:click="$dispatch('confirm-delete-selected', { ids: {{ json_encode($bulkSelected) }} })"
                                class="dropdown-item">
                                Hapus
                            </button>
                        </li>
                        <div class="dropdown-divider my-1"></div>
                        <h6 class="dropdown-header">Export ke :</h6>
                        <li>
                            <button x-on:click="$dispatch('go-on-export-to-excel-selected')" class="dropdown-item">
                                Excel
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        @endif --}}
        <div class="text-muted">
            <div class="input-icon">
                <input type="text" wire:model.live.debounce.300ms='search' class="form-control" placeholder="Cari...">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" wire:loading.remove
                        wire:target='search'>
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                        <path d="M21 21l-6 -6" />
                    </svg>

                    <span class="spinner-border spinner-border-sm" wire:loading wire:target='search' role="status"></span>
                </span>
            </div>
        </div>
    </div>

    <div wire:loading.delay class="container">
        <div class="text-center mt-2">
            <span wire:loading role="status" class="spinner-border spinner-border-sm"></span>&ensp;
            <span wire:loading role="status">Memuat..</span>
        </div>
    </div>

    <div wire:loading.class='invisible' class="table-responsive">
        <table class="table table-vcenter card-table table-striped table-hover">
            <thead class="text-nowrap">
                <tr>
                    {{-- @if (auth()->user()->role_id != 3)
                        <th class="w-1">
                            <input type="checkbox" wire:model.live='bulkSelectAll' class="form-check-input m-0 align-middle">
                        </th>
                    @endif --}}
                    <th class="w-1">No.</th>
                    <th>Thumbnail</th>
                    <th class="w-25">
                        <span class="d-inline-block py-1">Judul</span>
                        {{-- <span x-on:click="$dispatch('sort-by', { columnName: 'title' })" class="float-end" style="padding-top: 1.5px"
                            role="button">
                            @if ($sortColumn == 'title' && $sortDirection == 'desc')
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-down" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M17 3v6" />
                                    <path d="M10 18l-3 3l-3 -3" />
                                    <path d="M7 21v-18" />
                                    <path d="M20 6l-3 -3l-3 3" />
                                    <path d="M17 21v-2" />
                                    <path d="M17 15v-2" />
                                </svg>
                            @elseif ($sortColumn == 'title' && $sortDirection == 'asc')
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-up" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 21v-6" />
                                    <path d="M20 6l-3 -3l-3 3" />
                                    <path d="M17 3v18" />
                                    <path d="M10 18l-3 3l-3 -3" />
                                    <path d="M7 3v2" />
                                    <path d="M7 9v2" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-down" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 18l-3 3l-3 -3" />
                                    <path d="M7 5v-2" />
                                    <path d="M7 11v-2" />
                                    <path d="M7 15v6" />

                                    <path d="M20 6l-3 -3l-3 3" />
                                    <path d="M17 3v6" />
                                    <path d="M17 21v-2" />
                                    <path d="M17 15v-2" />
                                </svg>
                            @endif
                        </span> --}}
                    </th>
                    <th>Durasi Baca</th>
                    <th>
                        <span class="d-inline-block py-1">Author</span>
                        {{-- <span x-on:click="$dispatch('sort-by', { columnName: 'name' })" class="float-end" style="padding-top: 1.5px"
                            role="button">
                            @if ($sortColumn == 'name' && $sortDirection == 'desc')
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-down"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M17 3v6" />
                                    <path d="M10 18l-3 3l-3 -3" />
                                    <path d="M7 21v-18" />
                                    <path d="M20 6l-3 -3l-3 3" />
                                    <path d="M17 21v-2" />
                                    <path d="M17 15v-2" />
                                </svg>
                            @elseif ($sortColumn == 'name' && $sortDirection == 'asc')
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-up" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 21v-6" />
                                    <path d="M20 6l-3 -3l-3 3" />
                                    <path d="M17 3v18" />
                                    <path d="M10 18l-3 3l-3 -3" />
                                    <path d="M7 3v2" />
                                    <path d="M7 9v2" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                                    <path d="M10 18l-3 3l-3 -3" />
                                    <path d="M7 5v-2" />
                                    <path d="M7 11v-2" />
                                    <path d="M7 15v6" />

                                    <path d="M20 6l-3 -3l-3 3" />
                                    <path d="M17 3v6" />
                                    <path d="M17 21v-2" />
                                    <path d="M17 15v-2" />
                                </svg>
                            @endif
                        </span> --}}
                    </th>
                    <th>
                        <span class="d-inline-block py-1">Waktu dibuat</span>
                        {{-- <span x-on:click="$dispatch('sort-by', { columnName: 'created_at' })" class="float-end" style="padding-top: 1.5px"
                            role="button">
                            @if ($sortColumn == 'created_at' && $sortDirection == 'desc')
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-down"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M17 3v6" />
                                    <path d="M10 18l-3 3l-3 -3" />
                                    <path d="M7 21v-18" />
                                    <path d="M20 6l-3 -3l-3 3" />
                                    <path d="M17 21v-2" />
                                    <path d="M17 15v-2" />
                                </svg>
                            @elseif ($sortColumn == 'created_at' && $sortDirection == 'asc')
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-up" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 21v-6" />
                                    <path d="M20 6l-3 -3l-3 3" />
                                    <path d="M17 3v18" />
                                    <path d="M10 18l-3 3l-3 -3" />
                                    <path d="M7 3v2" />
                                    <path d="M7 9v2" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                                    <path d="M10 18l-3 3l-3 -3" />
                                    <path d="M7 5v-2" />
                                    <path d="M7 11v-2" />
                                    <path d="M7 15v6" />

                                    <path d="M20 6l-3 -3l-3 3" />
                                    <path d="M17 3v6" />
                                    <path d="M17 21v-2" />
                                    <path d="M17 15v-2" />
                                </svg>
                            @endif
                        </span> --}}
                    </th>
                    @if (auth()->user()->role_id != 3)
                        <th>
                            <span class="d-inline-block py-1">DiPublish ?</span>
                            {{-- <span x-on:click="$dispatch('sort-by', { columnName: 'is_published' })" class="float-end"
                                style="padding-top: 1.5px" role="button">
                                @if ($sortColumn == 'is_published' && $sortDirection == 'desc')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-down"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M17 3v6" />
                                        <path d="M10 18l-3 3l-3 -3" />
                                        <path d="M7 21v-18" />
                                        <path d="M20 6l-3 -3l-3 3" />
                                        <path d="M17 21v-2" />
                                        <path d="M17 15v-2" />
                                    </svg>
                                @elseif ($sortColumn == 'is_published' && $sortDirection == 'asc')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-up"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M7 21v-6" />
                                        <path d="M20 6l-3 -3l-3 3" />
                                        <path d="M17 3v18" />
                                        <path d="M10 18l-3 3l-3 -3" />
                                        <path d="M7 3v2" />
                                        <path d="M7 9v2" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                                        <path d="M10 18l-3 3l-3 -3" />
                                        <path d="M7 5v-2" />
                                        <path d="M7 11v-2" />
                                        <path d="M7 15v6" />

                                        <path d="M20 6l-3 -3l-3 3" />
                                        <path d="M17 3v6" />
                                        <path d="M17 21v-2" />
                                        <path d="M17 15v-2" />
                                    </svg>
                                @endif
                            </span> --}}
                        </th>
                    @endif
                    @if (auth()->user()->role_id != 3)
                        <th class="w-1">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($this->sumselNews as $sumselNews)
                    <tr wire:key='{{ $sumselNews->id }}'>
                        {{-- @if (auth()->user()->role_id != 3)
                            <td>
                                <input type="checkbox" wire:model.live='bulkSelected' class="form-check-input m-0 align-middle"
                                    value="{{ $sumselNews->id }}">
                            </td>
                        @endif --}}
                        <th class="text-muted">
                            {{ ($this->sumselNews->currentPage() - 1) * $this->sumselNews->perPage() + $loop->iteration }}
                        </th>
                        <td>
                            <div class="avatar avatar-md" style="background-image: url({{ $sumselNews->image }}); width: 6.5rem"></div>
                        </td>
                        <td>{{ $sumselNews->title }}</td>
                        <td class="text-muted">{{ $sumselNews->getReadingTime() }}</td>
                        <td class="text-muted">{{ $sumselNews->author }}</td>
                        <td class="text-muted">
                            {{ $sumselNews->created_at->format('d M Y') . ', ' . $sumselNews->created_at->format('H:i') }}
                        </td>
                        @if (auth()->user()->role_id != 3)
                            <td class="text-muted">
                                <livewire:app.bootstraps.toggle-button :model="$sumselNews" field="is_published" key="{{ $sumselNews->id }}" />
                            </td>
                        @endif
                        @if (auth()->user()->role_id != 3)
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a wire:navigate href="{{ route('area.sumsel_news.edit', $sumselNews->slug) }}"
                                        class="form-selectgroup-label bg-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit text-white"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                            <path d="M16 5l3 3"></path>
                                        </svg>
                                    </a>
                                    <a x-on:click="$dispatch('delete-confirm', { id: {{ $sumselNews->id }}, title: '{{ $sumselNews->title }}' })"
                                        class="form-selectgroup-label bg-danger" role="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash text-white"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M4 7l16 0"></path>
                                            <path d="M10 11l0 6"></path>
                                            <path d="M14 11l0 6"></path>
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-info">Data tidak tersedia!!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div wire:loading.class='invisible'>
        @if (method_exists($this->sumselNews, 'hasPages'))
            @if ($this->sumselNews->hasPages())
                <div class="card-footer py-2">
                    {{ $this->sumselNews->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
