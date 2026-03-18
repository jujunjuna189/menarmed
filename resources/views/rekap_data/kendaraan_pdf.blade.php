<!DOCTYPE html>
<html>
<head>
    <title>Rekap Angkutan</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; table-layout: fixed; word-wrap: break-word; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .col-no { width: 30px; }
        .col-nama { width: 120px; }
        .col-tujuan { width: auto; }
        .col-tgl { width: 110px; }
        .col-kd { width: 80px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rekap Data Angkutan (Perizinan Kendaraan)</h2>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">No.</th>
                <th class="col-nama">Nama</th>
                <th class="col-tgl">Waktu Keluar</th>
                <th class="col-tgl">Waktu Masuk</th>
                <th class="col-tujuan">Tujuan</th>
                <th class="col-kd">Kendaraan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($report as $val)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $val->userModel->name ?? '-' }}</td>
                <td>{{ $val->keluar != null ? \Carbon\Carbon::make($val->keluar)->format('d/m/Y H:i:s') : '-' }}</td>
                <td>{{ $val->masuk != null ? \Carbon\Carbon::make($val->masuk)->format('d/m/Y H:i:s') : '-' }}</td>
                <td>{{ $val->tujuan }}</td>
                <td>{{ $val->jenis_kendaraan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
