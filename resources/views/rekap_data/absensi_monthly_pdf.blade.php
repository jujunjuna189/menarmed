<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><title>Rekap Absensi Bulanan</title>
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 9px; }
    table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    th, td { border: 1px solid #ccc; padding: 4px 2px; text-align: center; overflow-wrap: break-word; }
    th { background: #f1f3f5; }
    .name { width: 120px; text-align: left; }
    .rank { width: 55px; text-align: left; }
    thead { display: table-header-group; }
    tr { page-break-inside: avoid; }
</style></head>
<body>
<h2>Rekap Absensi · {{ $month->locale('id')->translatedFormat('F Y') }}</h2>
<p>H: Hadir | I: Ijin | S: Sakit | CUTI | BP | DD | DIK | DK | DL | -: Tidak ada data</p>
<table><thead><tr>
@foreach($headings as $heading)<th class="{{ $loop->index === 0 ? 'name' : ($loop->index === 1 ? 'rank' : '') }}">{{ $heading }}</th>@endforeach
</tr></thead><tbody>
@foreach($rows as $row)<tr>@foreach($row as $cell)<td class="{{ $loop->index === 0 ? 'name' : ($loop->index === 1 ? 'rank' : '') }}">{{ $cell }}</td>@endforeach</tr>@endforeach
</tbody></table>
</body></html>
