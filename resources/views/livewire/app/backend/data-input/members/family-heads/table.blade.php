<div>
    <div class="card-header py-2 d-flex flex-wrap justify-content-center justify-content-md-between gap-2">
        <div class="text-muted">
            <label>
                Lihat
                <select class="d-inline-block form-select w-auto" wire:model.live='perPage'>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="80">80</option>
                    <option value="100">100</option>
                </select>
                data
            </label>
        </div>
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
        <table class="table table-vcenter card-table table-striped table-hover table-nowrap">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>
                        <span class="d-inline-block py-1">Dasawisma</span>
                    </th>
                    <th>
                        <span class="d-inline-block py-1">Wilayah</span>
                    </th>
                    <th>
                        <span class="d-inline-block py-1">No. KK</span>
                    </th>
                    <th>
                        <span class="d-inline-block py-1">Kepala Keluarga</span>
                    </th>
                    @if (auth()->user()->role_id != 3)
                        <th class="w-1">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($this->familyHeads as $familyHead)
                    <tr wire:key='{{ $familyHead->id }}'>
                        <th class="text-muted">
                            {{ ($this->familyHeads->currentPage() - 1) * $this->familyHeads->perPage() + $loop->iteration }}
                        </th>
                        <td class="text-muted">{{ $familyHead->dasawisma_name }}</td>
                        <td class="text-muted">{{ $familyHead->area }}</td>
                        <td>{{ $familyHead->kk_number }}</td>
                        <td>{{ $familyHead->family_head }}</td>
                        @if (auth()->user()->role_id != 3)
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a wire:click="$dispatchTo('app.backend.data-input.members.family-heads.edit', 'set-data', { id: {{ $familyHead->id }} })"
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
                                    <livewire:app.backend.data-input.members.family-heads.delete :$familyHead :key="'delete-' . $familyHead->id" />
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-info">Data tidak tersedia</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div wire:loading.class='invisible'>
        @if (method_exists($this->familyHeads, 'hasPages'))
            @if ($this->familyHeads->hasPages())
                <div class="card-footer py-2 d-flex justify-content-center align-items-center">
                    {{ $this->familyHeads->links('vendor.livewire.simple-bootstrap') }}
                </div>
            @endif
        @endif
    </div>

    <livewire:app.backend.data-input.members.family-heads.edit />
</div>
