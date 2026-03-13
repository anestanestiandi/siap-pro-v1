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
            @if(in_array('nama_persidangan', $columns))
                <th>Nama Kegiatan</th>
            @endif
            @if(in_array('tempat', $columns))
                <th>Tempat</th>
            @endif
            @if(in_array('jenis_persidangan', $columns))
                <th>Jenis Persidangan</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($kegiatan as $index => $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                @if(in_array('tanggal', $columns))
                    <td style="vnd.ms-excel.numberformat:@">{{ \Carbon\Carbon::parse($item->tanggal_persidangan)->isoFormat('D MMMM Y') }}</td>
                @endif
                @if(in_array('waktu', $columns))
                    <td style="vnd.ms-excel.numberformat:@">{{ $item->waktu ? \Carbon\Carbon::parse($item->waktu)->format('H:i') . ' WIB' : '-' }}</td>
                @endif
                @if(in_array('anggota_dewan', $columns))
                    <td>{{ $item->anggotaDewan->pluck('nama')->join(', ') ?: '-' }}</td>
                @endif
                @if(in_array('nama_persidangan', $columns))
                    <td>{{ $item->nama_persidangan }}</td>
                @endif
                @if(in_array('tempat', $columns))
                    <td>{{ $item->tempat ?? '-' }}</td>
                @endif
                @if(in_array('jenis_persidangan', $columns))
                    <td>{{ $item->jenisPersidangan->nama_jenis ?? '-' }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
