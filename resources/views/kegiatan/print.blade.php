<!DOCTYPE html>
<html>
<head>
    <title>Cetak Kegiatan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .content {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border: none; /* Hilangkan border pada tabel */
        }
        th, td {
            padding: 10px;
        }
        .photo-cell {
            width: 25%;
        }
        img {
            max-width: 100%;
            width: auto;
            height: auto;
            max-height: 400px; /* Tinggi maksimum untuk setiap foto */
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $kegiatan->nama_kegiatan }}</h1>
        <p>{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->locale('id')->isoFormat('D MMMM YYYY') }}</p>
    </div>
    <div class="content">
        <p>{{ $kegiatan->rincian_kegiatan }}</p>
        @foreach ($kegiatan->fotos->chunk(4) as $page)
            @if(!$loop->first)
                <div class="page-break"></div>
            @endif
            <table>
                @if($page->count() == 3)
                    <tr>
                        <td class="photo-cell">
                            <img src="{{ asset('storage/' . $page[0]->nama_file) }}" alt="Foto Kegiatan">
                        </td>
                        <td class="photo-cell">
                            <img src="{{ asset('storage/' . $page[1]->nama_file) }}" alt="Foto Kegiatan">
                        </td>
                    </tr>
                    <tr>
                        <td class="photo-cell" colspan="2" style="text-align: center;">
                            <img src="{{ asset('storage/' . $page[2]->nama_file) }}" alt="Foto Kegiatan">
                        </td>
                    </tr>
                @else
                    @foreach ($page->chunk(2) as $row)
                        <tr>
                            @foreach ($row as $foto)
                                <td class="photo-cell">
                                    <img src="{{ asset('storage/' . $foto->nama_file) }}" alt="Foto Kegiatan">
                                </td>
                            @endforeach
                            @if($row->count() < 2)
                                <td class="photo-cell"></td>
                            @endif
                        </tr>
                    @endforeach
                @endif
            </table>
        @endforeach
    </div>
</body>
</html>
