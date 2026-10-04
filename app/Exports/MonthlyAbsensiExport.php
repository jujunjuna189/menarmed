<?php

namespace App\Exports;

use App\Support\MonthlyAttendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlyAbsensiExport implements FromCollection, WithHeadings
{
    private $month;
    private $search;

    public function __construct(Carbon $month, string $search)
    {
        $this->month = $month;
        $this->search = $search;
    }

    public function headings(): array
    {
        return array_merge(['Nama', 'Pangkat'], range(1, $this->month->daysInMonth));
    }

    public function collection()
    {
        $people = MonthlyAttendance::people($this->search)->get();
        $records = MonthlyAttendance::records($this->month, $people->pluck('id'));
        return $people->map(function ($person) use ($records) {
            $row = [$person->name, $person->pangkat ?: '-'];
            for ($day = 1; $day <= $this->month->daysInMonth; $day++) {
                $items = $records->get($person->id . ':' . $day, collect());
                $row[] = $items->map(function ($record) { return MonthlyAttendance::code($record->ket); })->unique()->implode('/') ?: '-';
            }
            return $row;
        });
    }
}
