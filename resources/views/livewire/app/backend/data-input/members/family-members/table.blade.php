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
                    @if (count($this->familyMembers))
                        <option value="{{ $this->familyMembers->total() }}">Semua</option>
                    @endif
                </select>
                data
            </label>
        </div>
        @if (auth()->user()->role_id != 3)
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
                                wire:click="$dispatchTo('app.backend.data-input.members.family-members.bulk-delete', 'confirm-bulk-delete', { ids: {{ json_encode($bulkSelected) }} })"
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
        @endif
        <div class="text-muted">
            <div class="input-icon">
                <input type="text" wire:model.live='search' class="form-control" placeholder="Cari...">
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

    <div class="table-responsive">
        <table class="table table-vcenter card-table table-striped table-hover">
            <thead>
                <tr>
                    @if (auth()->user()->role_id != 3)
                        <th rowspan="2" class="w-1">
                            <input type="checkbox" wire:model.live='bulkSelectAll' class="form-check-input m-0 align-middle">
                        </th>
                    @endif
                    <th rowspan="2">No.</th>
                    <th rowspan="2">
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            Dasawisma
                            <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-members.table', 'sort-by', { columnName: 'dsw.name' })"
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
                            </span>
                        </div>
                    </th>
                    <th rowspan="2">
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            Wilayah
                            <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-members.table', 'sort-by', { columnName: 'area' })"
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
                            </span>
                        </div>
                    </th>
                    <th rowspan="2">
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            No. KK
                            <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-members.table', 'sort-by', { columnName: 'fh.kk_number' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'fh.kk_number' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'fh.kk_number' && $sortDirection == 'asc')
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
                            </span>
                        </div>
                    </th>
                    <th rowspan="2">
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            Nama
                            <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-members.table', 'sort-by', { columnName: 'fm.name' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'fm.name' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'fm.name' && $sortDirection == 'asc')
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
                            </span>
                        </div>
                    </th>
                    <th rowspan="2">
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            NIK
                            <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-members.table', 'sort-by', { columnName: 'fm.nik_number' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'fm.nik_number' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'fm.nik_number' && $sortDirection == 'asc')
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
                            </span>
                        </div>
                    </th>
                    <th rowspan="2">
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            Tgl Lahir
                            <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-members.table', 'sort-by', { columnName: 'fm.birth_date' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'fm.birth_date' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'fm.birth_date' && $sortDirection == 'asc')
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
                            </span>
                        </div>
                    </th>
                    <th colspan="2" class="text-center">Status</th>
                    <th rowspan="2">
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            Jenis Kelamin
                            <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-members.table', 'sort-by', { columnName: 'fm.gender' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'fm.gender' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'fm.gender' && $sortDirection == 'asc')
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
                            </span>
                        </div>
                    </th>
                    <th rowspan="2">
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            Pendidikan Terakhir
                            <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-members.table', 'sort-by', { columnName: 'fm.last_education' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'fm.last_education' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'fm.last_education' && $sortDirection == 'asc')
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
                            </span>
                        </div>
                    </th>
                    <th rowspan="2">
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            Pekerjaan
                            <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-members.table', 'sort-by', { columnName: 'fm.profession' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'fm.profession' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'fm.profession' && $sortDirection == 'asc')
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
                            </span>
                        </div>
                    </th>
                    @if (auth()->user()->role_id != 3)
                        <th rowspan="2" class="w-1">Aksi</th>
                    @endif
                </tr>
                <tr>
                    <th>
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            di Keluarga
                            <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-members.table', 'sort-by', { columnName: 'fm.status' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'fm.status' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'fm.status' && $sortDirection == 'asc')
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
                            </span>
                        </div>
                    </th>
                    <th>
                        <div class="d-flex flex-nowrap justify-content-between align-items-center">
                            Kawin
                            <span
                                wire:click="$dispatchTo('app.backend.data-input.members.family-members.table', 'sort-by', { columnName: 'fm.marital_status' })"
                                class="float-end ms-2" role="button">
                                @if ($sortColumn == 'fm.marital_status' && $sortDirection == 'desc')
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
                                @elseif ($sortColumn == 'fm.marital_status' && $sortDirection == 'asc')
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
                            </span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @php($i = $this->familyMembers->perPage() * $this->familyMembers->currentPage() - ($this->familyMembers->perPage() - 1))

                @forelse ($this->familyMembers as $kkNumber => $familyMembers)
                    @foreach ($familyMembers as $familyMember)
                        <tr wire:key='family-member-{{ $familyMember->id }}' class="text-nowrap">
                            @if (auth()->user()->role_id != 3)
                                <td>
                                    <input type="checkbox" wire:model.live='bulkSelected' class="form-check-input m-0 align-middle"
                                        value="{{ $familyMember->id }}">
                                </td>
                            @endif
                            <th class="text-muted">{{ $i }}</th>
                            <td class="text-muted">{{ $familyMember->dasawisma_name }}</td>
                            <td class="text-muted">{{ $familyMember->area }}</td>
                            <td>{{ $familyMember->kk_number }}</td>
                            <td>{{ $familyMember->name }}</td>
                            <td>{{ $familyMember->nik_number }}</td>
                            <td>{{ $familyMember->birth_date }}</td>
                            <td>{{ $familyMember->status }}</td>
                            <td>{{ $familyMember->marital_status }}</td>
                            <td>{{ $familyMember->gender }}</td>
                            <td>{{ $familyMember->last_education }}</td>
                            <td>{{ $familyMember->profession }}</td>
                            @if (auth()->user()->role_id != 3)
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a wire:navigate
                                            href="{{ route('area.data-input.member.family-member.edit', $familyMember->kk_number) }}"
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
                                        @if (!$loop->first || $familyMember->status != 'Kepala Keluarga')
                                            <livewire:app.backend.data-input.members.family-members.delete :$familyMember :key="'delete-' . $familyMember->id" />
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                        @php($i++)
                    @endforeach
                @empty
                    <tr>
                        <td colspan="14" class="text-center text-info">Data tidak tersedia</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if (method_exists($this->familyMembers, 'hasPages'))
        @if ($this->familyMembers->hasPages())
            <div class="card-footer py-2">
                {{ $this->familyMembers->links() }}
            </div>
        @endif
    @endif
</div>
