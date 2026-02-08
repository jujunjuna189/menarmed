<?php

namespace App\Exports;

use App\Models\AbsensiModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $filters;

    public function __construct($startDate, $endDate, $filters = [])
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->filters = $filters;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AbsensiModel::with('userModel')
            ->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
            ->whereHas('userModel', function($q) {
                if (isset($this->filters['name'])) {
                    $q->where('name', 'like', '%' . $this->filters['name'] . '%');
                }
            })
            ->orderBy('id', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Keterangan',
            'Latitude',
            'Longitude',
            'Tanggal dan Waktu',
        ];
    }

    public function map($absensi): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $absensi->userModel->name ?? '-',
            $absensi->ket,
            $absensi->latitude,
            $absensi->longitude,
            $absensi->created_at,
        ];
    }
}
