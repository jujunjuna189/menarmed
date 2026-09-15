<?php

namespace App\Imports;

use App\Models\AbsensiModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AbsensiImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new AbsensiModel([
            'user_id'   => $row['user_id'],
            'ket'       => $row['keterangan'], // Mapped from 'keterangan' column
            'latitude'  => $row['latitude'] ?? null,
            'longitude' => $row['longitude'] ?? null,
            'created_at'=> isset($row['tanggal']) ? Date::excelToDateTimeObject($row['tanggal'])->format('Y-m-d H:i:s') : now(),
        ]);
    }
}
