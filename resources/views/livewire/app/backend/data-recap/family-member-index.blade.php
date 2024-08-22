<div wire:init='getFamilyMembers'>
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
                        <th colspan="9" class="text-center">Jumlah</th>
                        <th colspan="8" class="text-center">Jumlah Pendidikan Terakhir</th>
                    </tr>
                    <tr>
                        <th>Angg. Keluarga</th>
                        <th>Laki-laki</th>
                        <th>Perempuan</th>
                        <th>Kawin</th>
                        <th>Blm Kawin</th>
                        <th>Janda</th>
                        <th>Duda</th>
                        <th>Sdh Bekerja</th>
                        <th>Blm/Tdk Bekerja</th>
                        <th>TK</th>
                        <th>SD</th>
                        <th>SLTP</th>
                        <th>SLTA</th>
                        <th>Diploma</th>
                        <th>S1</th>
                        <th>S2</th>
                        <th>S3</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr wire:key='{{ $item['id'] }}' class="text-nowrap">
                            <th class="text-muted">
                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                            </th>
                            <td>
                                @if (str_contains($currentUrl, '/index') || (str_contains($currentUrl, '/area-code') && strlen(substr(strrchr($currentUrl, '/'), 1)) != 10))
                                    <a href="{{ route('area.data-recap.family-members.show-area', $item['id']) }}">
                                        {{ $item['name'] }}
                                    </a>
                                @elseif(isset($item['slug']))
                                    <a href="{{ route('area.data-recap.family-members.show-dasawisma', $item['slug']) }}">
                                        {{ $item['name'] }}
                                    </a>
                                @else
                                    <a>{{ $item['name'] }}</a>
                                @endif
                            </td>
                            <td>{{ format_number($item['family_members_count']) }}</td>
                            <td>{{ format_number($item['gender_males_count']) }}</td>
                            <td>{{ format_number($item['gender_females_count']) }}</td>
                            <td>{{ format_number($item['marries_count']) }}</td>
                            <td>{{ format_number($item['singles_count']) }}</td>
                            <td>{{ format_number($item['widows_count']) }}</td>
                            <td>{{ format_number($item['widowers_count']) }}</td>
                            <td>{{ format_number($item['workings_count']) }}</td>
                            <td>{{ format_number($item['not_workings_count']) }}</td>
                            <td>{{ format_number($item['kindergartens_count']) }}</td>
                            <td>{{ format_number($item['elementary_schools_count']) }}</td>
                            <td>{{ format_number($item['middle_schools_count']) }}</td>
                            <td>{{ format_number($item['high_schools_count']) }}</td>
                            <td>{{ format_number($item['associate_degrees_count']) }}</td>
                            <td>{{ format_number($item['bachelor_degrees_count']) }}</td>
                            <td>{{ format_number($item['master_degrees_count']) }}</td>
                            <td>{{ format_number($item['post_degrees_count']) }}</td>
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
            @if (method_exists($data, 'hasPages'))
                @if ($data->hasPages())
                    <div class="card-footer py-2 d-flex justify-content-center align-items-center">
                        {{ $data->links('vendor.livewire.simple-bootstrap') }}
                    </div>
                @endif
            @endif
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
