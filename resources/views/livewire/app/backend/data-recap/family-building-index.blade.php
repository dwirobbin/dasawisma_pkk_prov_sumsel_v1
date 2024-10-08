<div wire:init='getData'>
    @if ($readyToLoad)
        <div class="card-header py-2 d-flex flex-wrap justify-content-center justify-content-md-between">
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
                        <th colspan="2" class="text-center">Makanan Pokok</th>
                        <th colspan="4" class="text-center">Sumber Air Keluarga</th>
                        <th colspan="3" class="text-center">Mempunyai</th>
                        <th rowspan="2">Menempel Stiker P4K</th>
                        <th colspan="2" class="text-center">Kriteria Rumah</th>
                    </tr>
                    <tr>
                        <th class="ps-2">Beras</th>
                        <th>Non Beras</th>
                        <th>PDAM</th>
                        <th>Sumur</th>
                        <th>Sungai</th>
                        <th>Lainnya</th>
                        <th>Jamban</th>
                        <th>TPS</th>
                        <th>SPAL</th>
                        <th>Sehat</th>
                        <th>Kurang Sehat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($familyBuildings['data'] as $item)
                        <tr wire:key='{{ $item['id'] }}' class="text-nowrap">
                            <th class="text-muted">
                                {{ ($familyBuildings['current_page'] - 1) * $familyBuildings['per_page'] + $loop->iteration }}
                            </th>
                            <td>
                                @if (str_contains($currentUrl, '/index') || (str_contains($currentUrl, '/area-code') && strlen(substr(strrchr($currentUrl, '/'), 1)) != 10))
                                    <a href="{{ route('area.data-recap.family-buildings.show-area', $item['id']) }}">
                                        {{ $item['name'] }}
                                    </a>
                                @elseif(isset($item['slug']))
                                    <a href="{{ route('area.data-recap.family-buildings.show-dasawisma', $item['slug']) }}">
                                        {{ $item['name'] }}
                                    </a>
                                @else
                                    <a>{{ $item['name'] }}</a>
                                @endif
                            </td>
                            <td>
                                @if (str_contains($currentUrl, '/index') || str_contains($currentUrl, '/area-code'))
                                    {{ format_number($item['rice_foods_count']) }}
                                @else
                                    {{ $item['rice_foods_count'] == 1 ? 'Ya' : 'Tidak' }}
                                @endif
                            </td>
                            <td>
                                @if (str_contains($currentUrl, '/index') || str_contains($currentUrl, '/area-code'))
                                    {{ format_number($item['etc_rice_foods_count']) }}
                                @else
                                    {{ $item['etc_rice_foods_count'] == 1 ? 'Ya' : 'Tidak' }}
                                @endif
                            </td>
                            <td>
                                @if (str_contains($currentUrl, '/index') || str_contains($currentUrl, '/area-code'))
                                    {{ format_number($item['pdam_waters_count']) }}
                                @else
                                    {{ $item['pdam_waters_count'] == 1 ? 'Ya' : 'Tidak' }}
                                @endif
                            </td>
                            <td>
                                @if (str_contains($currentUrl, '/index') || str_contains($currentUrl, '/area-code'))
                                    {{ format_number($item['well_waters_count']) }}
                                @else
                                    {{ $item['well_waters_count'] == 1 ? 'Ya' : 'Tidak' }}
                                @endif
                            </td>
                            <td>
                                @if (str_contains($currentUrl, '/index') || str_contains($currentUrl, '/area-code'))
                                    {{ format_number($item['river_waters_count']) }}
                                @else
                                    {{ $item['river_waters_count'] == 1 ? 'Ya' : 'Tidak' }}
                                @endif
                            </td>
                            <td>
                                @if (str_contains($currentUrl, '/index') || str_contains($currentUrl, '/area-code'))
                                    {{ format_number($item['etc_waters_count']) }}
                                @else
                                    {{ $item['etc_waters_count'] == 1 ? 'Ya' : 'Tidak' }}
                                @endif
                            </td>
                            <td>
                                @if (str_contains($currentUrl, '/index') || str_contains($currentUrl, '/area-code'))
                                    {{ format_number($item['have_toilets_count']) }}
                                @else
                                    {{ $item['have_toilets_count'] == 1 ? 'Ya' : 'Tidak' }}
                                @endif
                            </td>
                            <td>
                                @if (str_contains($currentUrl, '/index') || str_contains($currentUrl, '/area-code'))
                                    {{ format_number($item['have_landfills_count']) }}
                                @else
                                    {{ $item['have_landfills_count'] == 1 ? 'Ya' : 'Tidak' }}
                                @endif
                            </td>
                            <td>
                                @if (str_contains($currentUrl, '/index') || str_contains($currentUrl, '/area-code'))
                                    {{ format_number($item['have_sewerages_count']) }}
                                @else
                                    {{ $item['have_sewerages_count'] == 1 ? 'Ya' : 'Tidak' }}
                                @endif
                            </td>
                            <td>
                                @if (str_contains($currentUrl, '/index') || str_contains($currentUrl, '/area-code'))
                                    {{ format_number($item['pasting_p4k_stickers_count']) }}
                                @else
                                    {{ $item['pasting_p4k_stickers_count'] == 1 ? 'Ya' : 'Tidak' }}
                                @endif
                            </td>
                            <td>
                                @if (str_contains($currentUrl, '/index') || str_contains($currentUrl, '/area-code'))
                                    {{ format_number($item['healthy_criterias_count']) }}
                                @else
                                    {{ $item['healthy_criterias_count'] == 1 ? 'Ya' : 'Tidak' }}
                                @endif
                            </td>
                            <td>
                                @if (str_contains($currentUrl, '/index') || str_contains($currentUrl, '/area-code'))
                                    {{ format_number($item['no_healthy_criterias_count']) }}
                                @else
                                    {{ $item['no_healthy_criterias_count'] == 1 ? 'Ya' : 'Tidak' }}
                                @endif
                            </td>
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
                        @if ($familyBuildings['prev_page_url'] == null)
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
                        @if ($familyBuildings['next_page_url'] != null)
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
