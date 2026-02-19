<table>
    <thead>
        <tr>
            {{-- Main Employee and Contract Information Columns --}}
            <th>No</th>
            <th>NRK</th>
            <th>NIK</th>
            <th>Nama Karyawan</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Jenis Kelamin</th>
            <th>Status Karyawan</th>
            <th>Tanggal Masuk</th>

            {{-- Contract Specific Columns --}}
            <th>Nomor Kontrak</th>
            <th>Tanggal Kontrak</th>
            <th>Jenis Kontrak</th>
            <th>Kategori Kontrak</th>
            <th>Tanggal Mulai Kontrak</th>
            <th>Tanggal Akhir Kontrak</th>
            <th>Durasi Kontrak (Bulan)</th>
            <th>Tanggal Pengingat</th>
            <th>Status Kontrak</th>

            {{-- Company Details --}}
            <th>Perusahaan</th>

            {{-- Education Details --}}
            <th>Jenjang Pendidikan</th>
            <th>Institusi</th>
            <th>Jurusan</th>
            <th>Tanggal Lulus</th>

            {{-- Career Details --}}
            <th>Departemen</th>
            <th>Jabatan</th>
            <th>Wilayah Kerja</th>
            <th>Unit Kerja</th>

            {{-- Additional Contract Info --}}
            <th>Keterangan Non-Aktif</th>
            <th>Tanggal Non-Aktif</th>

            {{-- Audit Columns --}}
            <th>Dibuat Oleh</th>
            <th>Tanggal Dibuat</th>
            <th>Diupdate Oleh</th>
            <th>Tanggal Diupdate</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dataKontraks as $index => $kontrak)
            @php
                $karyawan = $kontrak->karyawan;
                $perusahaan = $kontrak->perusahaan;
                $departemen = $kontrak->departemen;
                $kontrakKerja = $kontrak->kontrakKerja;
                $wilayah = $kontrak->wilayahKerja;
            @endphp
            <tr>
                {{-- Main Employee Info --}}
                <td>{{ $index + 1 }}</td>
                <td>{{ $karyawan->nrk ?? '-' }}</td>
                <td>{{ $karyawan->nik ?? '-' }}</td>
                <td>{{ $karyawan->nama ?? '-' }}</td>
                <td>{{ $karyawan->tpt_lahir ?? '-' }}</td>
                <td>
                    {{ $karyawan->tgl_lahir ? \Carbon\Carbon::parse($karyawan->tgl_lahir)->format('d/m/Y') : '-' }}
                </td>
                <td>{{ $karyawan->sex ?? '-' }}</td>
                <td>{{ $karyawan->sts_kry ?? '-' }}</td>
                <td>
                    {{ $karyawan->tgl_masuk ? \Carbon\Carbon::parse($karyawan->tgl_masuk)->format('d/m/Y') : '-' }}
                </td>

                {{-- Contract Details --}}
                <td>{{ $kontrak->no_srt_ktr ?? '-' }}</td>
                <td>
                    {{ $kontrak->tgl_srt_ktr ? \Carbon\Carbon::parse($kontrak->tgl_srt_ktr)->format('d/m/Y') : '-' }}
                </td>
                <td>{{ $kontrakKerja->nama_ktr ?? '-' }}</td>
                <td>{{ $kontrak->ktg_ktk ?? '-' }}</td>
                <td>
                    {{ $kontrak->tgl_awl_ktr ? \Carbon\Carbon::parse($kontrak->tgl_awl_ktr)->format('d/m/Y') : '-' }}
                </td>
                <td>
                    {{ $kontrak->tgl_akhir_ktr ? \Carbon\Carbon::parse($kontrak->tgl_akhir_ktr)->format('d/m/Y') : '-' }}
                </td>
                <td>{{ $kontrak->durasi_ktr ?? '-' }}</td>
                <td>
                    {{ $kontrak->tgl_pgt_ktr ? \Carbon\Carbon::parse($kontrak->tgl_pgt_ktr)->format('d/m/Y') : '-' }}
                </td>
                <td>{{ $kontrak->sts_srt_ktr ?? '-' }}</td>

                {{-- Company Details --}}
                <td>{{ $perusahaan->nama_prs1 . ' - ' . $perusahaan->nama_prs2 ?? '-' }}</td>

                {{-- Education Details --}}
                <td>{{ $kontrak->jenjang_skl ?? '-' }}</td>
                <td>{{ $kontrak->institusi_skl ?? '-' }}</td>
                <td>{{ $kontrak->jurusan_skl ?? '-' }}</td>
                <td>
                    {{ $kontrak->tgl_lulus_skl ? \Carbon\Carbon::parse($kontrak->tgl_lulus_skl)->format('d/m/Y') : '-' }}
                </td>

                {{-- Career Details --}}
                <td>{{ $departemen->nama_dep ?? '-' }}</td>
                <td>{{ $departemen->nama_jbt ?? '-' }}</td>
                <td>{{ $wilayah->wilayah_krj ?? '-' }}</td>
                <td>{{ $kontrak->unit_krj ?? '-' }}</td>

                {{-- Additional Contract Info --}}
                <td>{{ $kontrak->ket_sr_na ?? '-' }}</td>
                <td>
                    {{ $kontrak->tgl_sr_na ? \Carbon\Carbon::parse($kontrak->tgl_sr_na)->format('d/m/Y') : '-' }}
                </td>

                {{-- Audit Columns --}}
                <td>{{ $kontrak->creator->nama_kry ?? '-' }}</td>
                <td>
                    {{ $kontrak->created_at ? \Carbon\Carbon::parse($kontrak->created_at)->format('d/m/Y H:i:s') : '-' }}
                </td>
                <td>{{ $kontrak->updater->nama_kry ?? '-' }}</td>
                <td>
                    {{ $kontrak->updated_at ? \Carbon\Carbon::parse($kontrak->updated_at)->format('d/m/Y H:i:s') : '-' }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@if ($filter)
    <table style="margin-top: 20px;">
        <tr>
            <td colspan="2"><strong>Informasi Filter yang Diterapkan:</strong></td>
        </tr>
        @foreach ([
            'nama' => 'Nama Karyawan',
            'jenis_kelamin' => 'Jenis Kelamin',
            'status' => 'Status Karyawan',
            'perusahaan' => 'Perusahaan',
            'departemen' => 'Departemen',
            'jabatan' => 'Jabatan',
            'wilker' => 'Wilayah Kerja',
            'kontrak' => 'Jenis Kontrak'
        ] as $key => $label)
            @if (!empty($filter[$key]))
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $filter[$key] }}</td>
                </tr>
            @endif
        @endforeach

        <tr>
            <td>Tanggal Export</td>
            <td>{{ now()->format('d/m/Y H:i:s') }}</td>
        </tr>
    </table>
@endif