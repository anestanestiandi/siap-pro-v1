<!DOCTYPE html>
<html>
<head>
    <title>Laporan Persidangan</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 5px 0; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daftar Kegiatan Persidangan</h1>
        @if(request('date'))
            <p>Tanggal: {{ \Carbon\Carbon::parse(request('date'))->isoFormat('D MMMM Y') }}</p>
        @elseif(request('month'))
            <p>Bulan: {{ \Carbon\Carbon::parse(request('month'))->isoFormat('MMMM Y') }}</p>
        @elseif(request('year'))
            <p>Tahun: {{ request('year') }}</p>
        @else
            <p>Semua Data</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                
                @if(in_array('tanggal', $columns))
                    <th style="width: 12%">Tanggal</th>
                @endif

                @if(in_array('waktu', $columns))
                    <th style="width: 10%">Pukul</th>
                @endif
                
                @if(in_array('anggota_dewan', $columns))
                    <th style="width: 20%">Anggota Dewan</th>
                @endif
                
                @if(in_array('nama_persidangan', $columns))
                    <th>Nama Kegiatan</th>
                @endif
                
                @if(in_array('tempat', $columns))
                    <th style="width: 15%">Tempat</th>
                @endif

                @if(in_array('jenis_persidangan', $columns))
                    <th style="width: 15%">Jenis</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($kegiatan as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>

                    @if(in_array('tanggal', $columns))
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_persidangan)->isoFormat('D MMMM Y') }}</td>
                    @endif

                    @if(in_array('waktu', $columns))
                        <td>{{ $item->waktu ? \Carbon\Carbon::parse($item->waktu)->format('H:i') . ' WIB' : '-' }}</td>
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
</body>
</html>
