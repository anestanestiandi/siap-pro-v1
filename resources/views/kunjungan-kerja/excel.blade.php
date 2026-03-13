<table>
    <thead>
        <tr>
            <th>No</th>
            @if(in_array('tanggal', $columns))
                <th>Tanggal</th>
            @endif
            @if(in_array('waktu', $columns))
                <th>Pukul</th>
            @endif
            @if(in_array('anggota_dewan', $columns))
                <th>Anggota Dewan</th>
            @endif
            @if(in_array('rombongan', $columns))
                <th>Rombongan</th>
            @endif
            @if(in_array('nama_kegiatan', $columns))
                <th>Nama Kegiatan</th>
            @endif
            @if(in_array('tujuan', $columns))
                <th>Tujuan</th>
            @endif
            @if(in_array('jenis_kunjungan', $columns))
                <th>Jenis Kunjungan</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($kunjungan as $index => $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                @if(in_array('tanggal', $columns))

                @endif
                @if(in_array('waktu', $columns))
                    <td style="vnd.ms-excel.numberformat:@">{{ $item->waktu ? \Carbon\Carbon::parse($item->waktu)->format('H:i') . ' WIB' : '-' }}</td>
                @endif
                @if(in_array('anggota_dewan', $columns))
                    <td>{{ $item->anggotaDewan->count() > 0 ? $item->anggotaDewan->pluck('nama')->implode(', ') : '-' }}</td>
                @endif
                @if(in_array('rombongan', $columns))
                    <td>{{ !empty($item->rombongan) ? implode(', ', $item->rombongan) : '-' }}</td>
                @endif
                @if(in_array('nama_kegiatan', $columns))
                    <td>{{ $item->nama_kegiatan }}</td>
                @endif
                @if(in_array('tujuan', $columns))
                    <td>
                        @if($item->tipe_tujuan == 'dalam_negeri')
                            {{ $item->provinsi->nama_provinsi ?? '-' }}
                        @else
                            Luar Negeri
                        @endif
                    </td>
                @endif
                @if(in_array('jenis_kunjungan', $columns))
                    <td>{{ $item->jenisKunjungan?->nama_jenis ?? '-' }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
