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
            text-align: center;
        }
        .photo-cell {
            width: 100%;
            text-align: center;
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
        @foreach ($kegiatan->fotos->chunk(2) as $page)
            @if(!$loop->first)
                <div class="page-break"></div>
            @endif
            <table>
                @foreach ($page as $foto)
                    <tr>
                        <td class="photo-cell">
                            <img src="{{ url('storage/' . $foto->nama_file) }}" alt="Foto Kegiatan">
                        </td>
                    </tr>
                @endforeach
            </table>
        @endforeach
    </div>
</body>
</html>
