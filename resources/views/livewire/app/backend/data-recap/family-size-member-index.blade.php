<div wire:init='getData'>
    @if ($readyToLoad)
        <div class="card-header py-2 d-flex flex-wrap justify-content-center justify-content-lg-between">
            <h3 class="my-auto">
                @if (str_contains($currentUrl, '/index'))
                    Kabupaten/Kota
                @elseif (str_contains($currentUrl, '/area-code') && strlen(substr(strrchr($currentUrl, '/'), 1)) == 4)
                    Kecamatan
                @elseif (str_contains($currentUrl, '/area-code') && strlen(substr(strrchr($currentUrl, '/'), 1)) == 7)
                    Kelurahan/Desa
                @elseif (str_contains($currentUrl, '/area-code') && strlen(substr(strrchr($currentUrl, '/'), 1)) == 10)
                    Dasawisma
                @else
                    Keluarga
                @endif
            </h3>
            <div class="text-muted ms-auto my-1 my-lg-0">
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
            <table class="table table-vcenter card-table table-striped table-hover">
                <thead>
                    <tr>
                        <th rowspan="2">No.</th>
                        <th rowspan="2">
                            @if (str_contains($currentUrl, '/index') || (str_contains($currentUrl, '/area-code') && strlen(substr(strrchr($currentUrl, '/'), 1)) != 10))
                                Wilayah
                            @elseif (str_contains($currentUrl, '/area-code') && strlen(substr(strrchr($currentUrl, '/'), 1)) == 10)
                                Dasawisma
                            @else
                                Nama Kepala Keluarga
                            @endif
                        </th>
                        <th colspan="7" class="text-center">Jumlah</th>
                    </tr>
                    <tr>
                        <th>Balita</th>
                        <th>Psngan Usia Subur</th>
                        <th>Wan. Usia Subur</th>
                        <th>Org Buta</th>
                        <th>Ibu Hamil</th>
                        <th>Ibu Menyusui</th>
                        <th>Lansia</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($familySizeMembers['data'] as $item)
                        <tr wire:key='{{ $item['id'] }}' class="text-nowrap">
                            <th class="text-muted">
                                {{ ($familySizeMembers['current_page'] - 1) * $familySizeMembers['per_page'] + $loop->iteration }}
                            </th>
                            <td>
                                @if (str_contains($currentUrl, '/index') || (str_contains($currentUrl, '/area-code') && strlen(substr(strrchr($currentUrl, '/'), 1)) != 10))
                                    <a href="{{ route('area.data-recap.family-size-members.show-area', $item['id']) }}">
                                        {{ $item['name'] }}
                                    </a>
                                @elseif(isset($item['slug']))
                                    <a href="{{ route('area.data-recap.family-size-members.show-dasawisma', $item['slug']) }}">
                                        {{ $item['name'] }}
                                    </a>
                                @else
                                    <a>{{ $item['name'] }}</a>
                                @endif
                            </td>
                            <td>{{ format_number($item['toddlers_sum']) }}</td>
                            <td>{{ format_number($item['pus_sum']) }}</td>
                            <td>{{ format_number($item['wus_sum']) }}</td>
                            <td>{{ format_number($item['blind_peoples_sum']) }}</td>
                            <td>{{ format_number($item['pregnant_womens_sum']) }}</td>
                            <td>{{ format_number($item['breastfeeding_mothers_sum']) }}</td>
                            <td>{{ format_number($item['elderlies_sum']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="text-center text-info">
                                {{ 'Data tidak tersedia!' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div wire:loading.class='invisible'>
            <div class="card-footer py-2 d-flex justify-content-center align-items-center">
                <nav>
                    <ul class="pagination mb-0 space-x-2">
                        {{-- Previous Page Link --}}
                        @if ($familySizeMembers['prev_page_url'] == null)
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="btn btn-secondary disabled">&lsaquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <button type="button"class="btn btn-primary" wire:click="goToPrevPage"
                                    wire:loading.attr="disabled">&lsaquo;</button>
                            </li>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($familySizeMembers['next_page_url'] != null)
                            <li class="page-item">
                                <a class="btn btn-primary" wire:click="goToNextPage" wire:loading.attr="disabled">&rsaquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="btn btn-secondary disabled">&rsaquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    @else
        <div class="container container-slim py-3" style="max-width: 16rem">
            <div class="text-center">
                <div class="mb-3">
                    <img src="{{ asset('src/img/logo-favicon/prov-sumsel.png') }}" height="36" class="avatar avatar-md shadow-none"
                        alt="">
                    <img src="{{ asset('src/img/logo-favicon/logo-pkk.png') }}" height="36" class="avatar avatar-md shadow-none"
                        alt="">
                </div>
                <div class="text-muted mb-3">Sedang memuat...</div>
                <div class="progress progress-sm">
                    <div class="progress-bar progress-bar-indeterminate"></div>
                </div>
            </div>
        </div>
    @endif
</div>
