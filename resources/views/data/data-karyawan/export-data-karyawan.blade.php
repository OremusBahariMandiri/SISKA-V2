<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal Masuk</th>
            <th>NRK</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Jenis Kelamin</th>
            <th>Agama</th>
            <th>Kewarganegaraan</th>
            <th>Status Nikah</th>
            <th>Status Keluarga</th>
            <th>Jumlah Anak</th>
            <th>Telepon 1</th>
            <th>Telepon 2</th>
            <th>Email 1</th>
            <th>Email 2</th>
            <th>Instagram</th>
            <th>Facebook</th>
            {{-- Alamat KTP --}}
            <th>Provinsi KTP</th>
            <th>Kota KTP</th>
            <th>Kecamatan KTP</th>
            <th>Kelurahan KTP</th>
            <th>RT/RW KTP</th>
            <th>Kode Pos KTP</th>
            <th>Alamat KTP</th>
            {{-- Alamat DOM --}}
            <th>Provinsi DOM</th>
            <th>Kota DOM</th>
            <th>Kecamatan DOM</th>
            <th>Kelurahan DOM</th>
            <th>RT/RW DOM</th>
            <th>Kode Pos DOM</th>
            <th>Alamat DOM</th>
            {{-- Pendidikan --}}
            <th>Jenjang</th>
            <th>Institusi</th>
            <th>Kota</th>
            <th>Fakultas</th>
            <th>Jurusan</th>
            <th>Gelar</th>
            <th>Tanggal Lulus</th>
            {{-- Kontrak Kerja --}}
            <th>Perusahaan</th>
            <th>Status Kontrak</th>
            <th>Tanggal Awal Kontrak</th>
            <th>Tanggal Akhir Kontrak</th>
            <th>Durasi Kontrak Kontrak</th>
            {{-- Jenjang Karir --}}
            <th>Departemen</th>
            <th>Jabatan</th>
            <th>Wilayah Kerja</th>
            <th>Unit Kerja</th>
            <th>Tugas</th>
            {{-- Jenjang Karir --}}
            <th>Status Karyawan</th>
            <th>Tanggal PHK</th>
            <th>Ket PHK</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($dataKaryawans as $index => $karyawan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $karyawan->tgl_masuk ?: '-' }}</td>
                <td>{{ $karyawan->nrk ?: '-' }}</td>
                <td>{{ $karyawan->nik }}</td>
                <td>{{ $karyawan->nama }}</td>
                <td>{{ $karyawan->tpt_lahir }}</td>
                <td>
                    @if ($karyawan->tgl_lahir)
                        {{ \Carbon\Carbon::parse($karyawan->tgl_lahir)->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $karyawan->sex }}</td>
                <td>{{ $karyawan->agama }}</td>
                <td>{{ $karyawan->kewarganegaraan ?: 'INDONESIA' }}</td>
                <td>{{ $karyawan->sts_nikah }}</td>
                <td>{{ $karyawan->sts_keluarga ?: '-' }}</td>
                <td>{{ $karyawan->jml_anak ?: '0' }}</td>
                <td>{{ $karyawan->tlp1 }}</td>
                <td>{{ $karyawan->tlp2 ?: '-' }}</td>
                <td>{{ $karyawan->email1 ?: '-' }}</td>
                <td>{{ $karyawan->email2 ?: '-' }}</td>
                <td>{{ $karyawan->instagram ?: '-' }}</td>
                <td>{{ $karyawan->facebook ?: '-' }}</td>
                {{-- Alamat KTP --}}
                <td>{{ $karyawan->prov_ktp }}</td>
                <td>{{ $karyawan->kota_ktp }}</td>
                <td>{{ $karyawan->kec_ktp }}</td>
                <td>{{ $karyawan->kel_ktp }}</td>
                <td>{{ $karyawan->rt_rw_ktp }}</td>
                <td>{{ $karyawan->kd_pos_ktp }}</td>
                <td>{{ $karyawan->alamat_ktp }}</td>
                {{-- Alamat DOM --}}
                <td>{{ $karyawan->prov_dom }}</td>
                <td>{{ $karyawan->kota_dom }}</td>
                <td>{{ $karyawan->kec_dom }}</td>
                <td>{{ $karyawan->kel_dom }}</td>
                <td>{{ $karyawan->rt_rw_dom }}</td>
                <td>{{ $karyawan->kd_pos_dom }}</td>
                <td>{{ $karyawan->alamat_dom }}</td>
                {{-- Pendidikan --}}
                <td>{{ $karyawan->jenjang_skl ?: '-' }}</td>
                <td>{{ $karyawan->institusi_skl ?: '-' }}</td>
                <td>{{ $karyawan->kota_skl ?: '-' }}</td>
                <td>{{ $karyawan->fakultas_skl ?: '-' }}</td>
                <td>{{ $karyawan->jurusan_skl ?: '-' }}</td>
                <td>{{ $karyawan->gelar_skl ?: '-' }}</td>
                <td>{{ $karyawan->tgl_lulus_skl ?: '-' }}</td>
                {{-- Kontrak Kerja --}}
                <td>{{ $karyawan->perusahaan ? $karyawan->perusahaanRelation->nama_prs1 : '-' }}</td>
                <td>{{ $karyawan->sts_ktr ?: '-' }}</td>
                <td>
                    @if ($karyawan->tgl_awal_ktr)
                        {{ \Carbon\Carbon::parse($karyawan->tgl_awal_ktr)->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if ($karyawan->tgl_akhir_ktr)
                        {{ \Carbon\Carbon::parse($karyawan->tgl_akhir_ktr)->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $karyawan->durasi_ktr }}</td>
                {{-- Jenjang karir --}}
                <td>{{ $karyawan->departemen ? $karyawan->departemenRelation->nama_dep : '-' }}</td>
                <td>{{ $karyawan->jabatan ?: '-' }}</td>
                <td>{{ $karyawan->wilayahKerja ? $karyawan->wilayahKerjaRelation->wilayah_krj : '-' }}</td>
                <td>{{ $karyawan->unit_krj }}</td>
                <td>{{ $karyawan->tugas }}</td>
                {{-- hub in --}}
                <td>{{ $karyawan->sts_kry }}</td>
                <td>{{ $karyawan->tgl_phk }}</td>
                <td>{{ $karyawan->ket_phk }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@if ($filter)
    <table style="margin-top: 20px;">
        <tr>
            <td colspan="2"><strong>Informasi Filter yang Diterapkan:</strong></td>
        </tr>
        @if (isset($filter['nrk']) && $filter['nrk'])
            <tr>
                <td>NRK</td>
                <td>{{ $filter['nrk'] }}</td>
            </tr>
        @endif
        @if (isset($filter['nama']) && $filter['nama'])
            <tr>
                <td>Nama</td>
                <td>{{ $filter['nama'] }}</td>
            </tr>
        @endif
        @if (isset($filter['nik']) && $filter['nik'])
            <tr>
                <td>NIK</td>
                <td>{{ $filter['nik'] }}</td>
            </tr>
        @endif
        @if (isset($filter['sex']) && $filter['sex'])
            <tr>
                <td>Jenis Kelamin</td>
                <td>{{ $filter['sex'] }}</td>
            </tr>
        @endif
        @if (isset($filter['agama']) && $filter['agama'])
            <tr>
                <td>Agama</td>
                <td>{{ $filter['agama'] }}</td>
            </tr>
        @endif
        @if (isset($filter['sts_nikah']) && $filter['sts_nikah'])
            <tr>
                <td>Status Nikah</td>
                <td>{{ $filter['sts_nikah'] }}</td>
            </tr>
        @endif
        @if (isset($filter['perusahaan']) && $filter['perusahaan'])
            <tr>
                <td>Perusahaan</td>
                <td>{{ $filter['perusahaan'] }}</td>
            </tr>
        @endif
        @if (isset($filter['departemen']) && $filter['departemen'])
            <tr>
                <td>Departemen</td>
                <td>{{ $filter['departemen'] }}</td>
            </tr>
        @endif
        @if (isset($filter['jabatan']) && $filter['jabatan'])
            <tr>
                <td>Jabatan</td>
                <td>{{ $filter['jabatan'] }}</td>
            </tr>
        @endif
        @if (isset($filter['wilker']) && $filter['wilker'])
            <tr>
                <td>Wilayah Kerja</td>
                <td>{{ $filter['wilker'] }}</td>
            </tr>
        @endif
        @if (isset($filter['sts_kry']) && $filter['sts_kry'])
            <tr>
                <td>Status Karyawan</td>
                <td>{{ $filter['sts_kry'] }}</td>
            </tr>
        @endif
        @if (isset($filter['tgl_masuk_from']) && $filter['tgl_masuk_from'])
            <tr>
                <td>Tanggal Masuk (Dari)</td>
                <td>{{ $filter['tgl_masuk_from'] }}</td>
            </tr>
        @endif
        @if (isset($filter['tgl_masuk_to']) && $filter['tgl_masuk_to'])
            <tr>
                <td>Tanggal Masuk (Sampai)</td>
                <td>{{ $filter['tgl_masuk_to'] }}</td>
            </tr>
        @endif
        <tr>
            <td>Tanggal Export</td>
            <td>{{ now()->format('d/m/Y H:i:s') }}</td>
        </tr>
    </table>
@endif
