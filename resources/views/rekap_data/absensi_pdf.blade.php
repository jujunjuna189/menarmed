<!DOCTYPE html>
<html>
<head>
    <title>Rekap Absensi</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; table-layout: fixed; word-wrap: break-word; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .col-no { width: 30px; }
        .col-nama { width: 150px; }
        .col-ket { width: auto; }
        .col-tgl { width: 120px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rekap Data Absensi</h2>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">No.</th>
                <th class="col-nama">Nama</th>
                <th class="col-ket">Keterangan</th>
                <th class="col-tgl">Tanggal dan Waktu</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($report as $val)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $val->userModel->name ?? '-' }}</td>
                <td>{{ $val->ket }}</td>
                <td>{{ \Carbon\Carbon::make($val->created_at)->format('d/m/Y H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
