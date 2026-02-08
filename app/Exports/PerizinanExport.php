<?php

namespace App\Exports;

use App\Models\PerizinanModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PerizinanExport implements FromCollection, WithHeadings, WithMapping
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
        return PerizinanModel::with('userModel')
            ->whereBetween('keluar', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
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
            'Waktu Keluar',
            'Waktu Masuk',
            'Tujuan',
            'Jenis Kendaraan',
        ];
    }

    public function map($perizinan): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $perizinan->userModel->name ?? '-',
            $perizinan->keluar ? \Carbon\Carbon::make($perizinan->keluar)->format('d/m/Y H:i:s') : '-',
            $perizinan->masuk ? \Carbon\Carbon::make($perizinan->masuk)->format('d/m/Y H:i:s') : '-',
            $perizinan->tujuan,
            $perizinan->jenis_kendaraan,
        ];
    }
}
