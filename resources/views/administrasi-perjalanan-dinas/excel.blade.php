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
            @if(in_array('nama_kegiatan', $columns))
                <th>Nama Kegiatan</th>
            @endif
            @if(in_array('pelaksana', $columns))
                <th>Pelaksana</th>
            @endif
            @if(in_array('tujuan', $columns))
                <th>Tujuan</th>
            @endif
            @if(in_array('jenis_perjalanan', $columns))
                <th>Jenis Perjalanan</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($kegiatan as $index => $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                @if(in_array('tanggal', $columns))
                    <td>
                        @if($item->tanggal_mulai == $item->tanggal_selesai)
                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->isoFormat('D MMMM Y') }}
                        @else
                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->isoFormat('D MMMM') }} - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->isoFormat('D MMMM Y') }}
                        @endif
                    </td>
                @endif
                @if(in_array('waktu', $columns))
                    <td>{{ $item->waktu ? \Carbon\Carbon::parse($item->waktu)->format('H:i') . ' WIB' : '-' }}</td>
                @endif
                @if(in_array('nama_kegiatan', $columns))
                    <td>{{ $item->nama_kegiatan }}</td>
                @endif
                @if(in_array('pelaksana', $columns))
                    <td>{{ $item->pelaksana ?? '-' }}</td>
                @endif
                @if(in_array('tujuan', $columns))
                    <td>{{ $item->tujuan ?? '-' }}</td>
                @endif
                @if(in_array('jenis_perjalanan', $columns))
                    <td>{{ $item->jenisPerjalananDinas?->nama_jenis ?? '-' }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
