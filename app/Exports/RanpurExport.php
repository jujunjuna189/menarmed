<?php

namespace App\Exports;

use App\Models\PerizinanRanpurModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RanpurExport implements FromCollection, WithHeadings, WithMapping
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
        return PerizinanRanpurModel::with('userModel')
            ->whereBetween('keluar', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
            ->whereHas('userModel', function($q) {
                if (isset($this->filters['name'])) {
                    $q->where('name', 'like', '%' . $this->filters['name'] . '%');
                }
            })
            ->orderBy('keluar', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Waktu Keluar',
            'Waktu Masuk',
            'Tujuan',
            'Jenis Kendaraan',
        ];
    }

    public function map($ranpur): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $ranpur->userModel->name ?? '-',
            $ranpur->keluar,
            $ranpur->masuk,
            $ranpur->tujuan,
            $ranpur->jenis_kendaraan,
        ];
    }
}
