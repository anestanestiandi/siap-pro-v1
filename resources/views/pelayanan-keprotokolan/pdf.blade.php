<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pelayanan Keprotokolan</title>
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
        <h1>DAFTAR KEGIATAN PELAYANAN KEPROTOKOLAN</h1>
        @if(request('start_date') && request('end_date'))
            <p>Periode: {{ \Carbon\Carbon::parse(request('start_date'))->isoFormat('D MMMM Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->isoFormat('D MMMM Y') }}</p>
        @elseif(request('date'))
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
                
                @if(in_array('nama_kegiatan', $columns))
                    <th>Nama Kegiatan</th>
                @endif
                
                @if(in_array('tempat', $columns))
                    <th style="width: 15%">Tempat</th>
                @endif

                @if(in_array('jenis_pelayanan', $columns))
                    <th style="width: 15%">Jenis</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($kegiatan as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>

                    @if(in_array('tanggal', $columns))
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_kegiatan)->isoFormat('D MMMM Y') }}</td>
                    @endif

                    @if(in_array('waktu', $columns))
                        <td>{{ $item->waktu ? \Carbon\Carbon::parse($item->waktu)->format('H:i') . ' WIB' : '-' }}</td>
                    @endif

                    @if(in_array('anggota_dewan', $columns))
                        <td>{{ $item->anggotaDewan->count() > 0 ? $item->anggotaDewan->pluck('nama')->implode(', ') : '-' }}</td>
                    @endif

                    @if(in_array('nama_kegiatan', $columns))
                        <td>{{ $item->nama_kegiatan }}</td>
                    @endif

                    @if(in_array('tempat', $columns))
                        <td>{{ $item->tempat ?? '-' }}</td>
                    @endif

                    @if(in_array('jenis_pelayanan', $columns))
                        <td>{{ $item->jenisPelayanan?->nama_jenis ?? '-' }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>