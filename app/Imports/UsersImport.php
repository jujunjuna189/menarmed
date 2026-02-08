<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'name'          => $row['nama'],
            'email'         => $row['email'],
            'password'      => Hash::make($row['password']),
            'role'          => $row['role_id'] ?? 3, // Default to Personil if not specified
            'pangkat'       => $row['pangkat'] ?? null,
            'korp'          => $row['korp'] ?? null,
            'satuan'        => $row['satuan'] ?? null,
            'jabatan'       => $row['jabatan'] ?? null,
            'tempat_lahir'  => $row['tempat_lahir'] ?? null,
            'tgl_lahir'     => isset($row['tgl_lahir']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_lahir'])->format('Y-m-d') : null,
            'agama'         => $row['agama'] ?? null,
            'gol_darah'     => $row['gol_darah'] ?? null,
            'sumber_pa'     => $row['sumber_pa'] ?? null,
            'senjata'       => $row['senjata'] ?? null,
        ]);
    }
}
