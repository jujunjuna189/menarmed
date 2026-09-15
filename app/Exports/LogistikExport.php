<?php

namespace App\Exports;

use App\Models\LogistikModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LogistikExport implements FromCollection, WithHeadings, WithMapping
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
        return LogistikModel::with('userModel')
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
        ];
    }

    public function map($logistik): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $logistik->userModel->name ?? '-',
            $logistik->keluar,
            $logistik->masuk,
        ];
    }
}
