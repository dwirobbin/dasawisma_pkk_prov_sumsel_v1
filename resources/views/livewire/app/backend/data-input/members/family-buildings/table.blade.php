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
                    @if (count($this->familyBuildings))
                        <option value="{{ $this->familyBuildings->total() }}">Semua</option>
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
                            <button
                                wire:click="$dispatchTo('app.backend.data-input.members.family-buildings.bulk-delete', 'confirm-bulk-delete', { ids: {{ json_encode($bulkSelected) }} })"
                                class="dropdown-item">
                                Hapus
                            </button>
                        </li>
                        <div class="dropdown-divider my-1"></div>
                        <h6 class="dropdown-header">Export ke :</h6>
                        <li>
                            <button x-on:click="$wire.exportToExcel()" class="dropdown-item">
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
                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                        <path d="M21 21l-6 -6" />
                    </svg>
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
        <table class="table table-vcenter card-table table-hover text-nowrap">
            <thead>
                <tr>
                    {{-- @if (auth()->user()->role_id != 3)
                        <th rowspan="2" class="w-1">
                            <input type="checkbox" wire:model.live='bulkSelectAll' class="form-check-input m-0 align-middle">
                        </th>
                    @endif --}}
                    <th rowspan="2">No.</th>
                    <th rowspan="2">
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            Dasawisma
                            {{-- <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-buildings.table', 'sort-by', { columnName: 'dsw.name' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'dsw.name' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'dsw.name' && $sortDirection == 'asc')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-up"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-down"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
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
                        </div>
                    </th>
                    <th rowspan="2">
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            Wilayah
                            {{-- <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-buildings.table', 'sort-by', { columnName: 'area' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'area' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'area' && $sortDirection == 'asc')
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-down"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                        </div>
                    </th>
                    <th rowspan="2">
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            Kepala Keluarga
                            {{-- <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-buildings.table', 'sort-by', { columnName: 'fh.family_head' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'fh.family_head' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'fh.family_head' && $sortDirection == 'asc')
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-down"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                        </div>
                    </th>
                    <th rowspan="2">
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            Makanan Pokok
                            {{-- <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-buildings.table', 'sort-by', { columnName: 'fb.staple_food' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'fb.staple_food' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'fb.staple_food' && $sortDirection == 'asc')
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-down"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                        </div>
                    </th>
                    <th rowspan="2">
                        <span class="d-flex flex-nowrap justify-content-between align-items-center">
                            Sumber Air Keluarga
                            {{-- <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-buildings.table', 'sort-by', { columnName: 'fb.water_src' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'fb.water_src' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'fb.water_src' && $sortDirection == 'asc')
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-down"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                        </span>
                    </th>
                    <th colspan="3" class="text-center">Mempunyai</th>
                    <th rowspan="2">
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            Menempel Stiker P4K
                            {{-- <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-buildings.table', 'sort-by', { columnName: 'pasting_p4k_sticker' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'pasting_p4k_sticker' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'pasting_p4k_sticker' && $sortDirection == 'asc')
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-down"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                        </div>
                    </th>
                    <th rowspan="2">
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            Kriteria Rumah
                            {{-- <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-buildings.table', 'sort-by', { columnName: 'fb.house_criteria' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'fb.house_criteria' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'fb.house_criteria' && $sortDirection == 'asc')
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-down"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                        </div>
                    </th>
                    @if (auth()->user()->role_id != 3)
                        <th rowspan="2" class="w-1">Aksi</th>
                    @endif
                </tr>
                <tr>
                    <th>
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            Jamban
                            {{-- <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-buildings.table', 'sort-by', { columnName: 'have_toilet' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'have_toilet' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'have_toilet' && $sortDirection == 'asc')
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-down"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                        </div>
                    </th>
                    <th>
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            TPS
                            {{-- <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-buildings.table', 'sort-by', { columnName: 'have_landfill' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'have_landfill' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'have_landfill' && $sortDirection == 'asc')
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-down"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                        </div>
                    </th>
                    <th>
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            SPAL
                            {{-- <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-buildings.table', 'sort-by', { columnName: 'have_sewerage' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'have_sewerage' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'have_sewerage' && $sortDirection == 'asc')
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-transfer-down"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->familyBuildings as $familyBuilding)
                    <tr wire:key='{{ $familyBuilding->id }}'>
                        {{-- @if (auth()->user()->role_id != 3)
                            <td>
                                <input type="checkbox" wire:model.live='bulkSelected' class="form-check-input m-0 align-middle"
                                    value="{{ $familyBuilding->id }}">
                            </td>
                        @endif --}}
                        <th class="text-muted">
                            {{ ($this->familyBuildings->currentPage() - 1) * $this->familyBuildings->perPage() + $loop->iteration }}
                        </th>
                        <td class="text-muted">{{ $familyBuilding->dasawisma_name }}</td>
                        <td class="text-muted">{{ $familyBuilding->area }}</td>
                        <td>{{ $familyBuilding->family_head }}</td>
                        <td>{{ $familyBuilding->staple_food }}</td>
                        <td>{{ $familyBuilding->water_src }}</td>
                        <td>{{ $familyBuilding->have_toilet }}</td>
                        <td>{{ $familyBuilding->have_landfill }}</td>
                        <td>{{ $familyBuilding->have_sewerage }}</td>
                        <td>{{ $familyBuilding->pasting_p4k_sticker }}</td>
                        <td>{{ $familyBuilding->house_criteria }}</td>
                        @if (auth()->user()->role_id != 3)
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a wire:navigate
                                        href="{{ route('area.data-input.member.family-building.edit', $familyBuilding->kk_number) }}"
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
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center text-info">Data tidak tersedia</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div wire:loading.class='invisible'>
        @if (method_exists($this->familyBuildings, 'hasPages'))
            @if ($this->familyBuildings->hasPages())
                <div class="card-footer py-2">
                    {{ $this->familyBuildings->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
