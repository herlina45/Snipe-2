<?php

namespace App\Helpers;

use App\Models\TicketCounter;
use App\Models\Department;
use Carbon\Carbon;

class TicketCounterHelper
{
    public static function generateTicketNumber($departmentId, $requestDate = null)
    {
        // Ambil kode departemen
        $department = Department::findOrFail($departmentId);
        $deptCode = strtoupper(substr($department->code ?? $department->name, 0, 2)); // Ambil 2 huruf pertama, misal 'IT'

        // Pake tanggal request atau tanggal sekarang
        $date = $requestDate ? Carbon::parse($requestDate) : now();
        $monthYear = $date->format('my'); // Misal '0525' buat Mei 2025

        // Cari atau bikin counter
        $counter = TicketCounter::firstOrCreate(
            [
                'department_id' => $departmentId,
                'department_code' => $deptCode,
                'month_year' => $monthYear,
            ],
            ['counter' => 0]
        );

        // Increment counter
        $counter->increment('counter');
        $counter->refresh(); // Ambil data terbaru dari DB

        // Format nomor urut jadi 3 digit
        $runningNumber = str_pad($counter->counter, 3, '0', STR_PAD_LEFT);

        // Gabungin formatnya
        return "TN{$deptCode}-{$runningNumber}-{$monthYear}";
    }
}
?>