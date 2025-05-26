<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticketing extends Model
{
    use HasFactory;

    protected $table = 'ticketings';
    protected $fillable = [
        'ticket_number',
        'requested_by',
        'department_id',
        'request_for',
        'category_id',
        'requested_date',
        'required_date',
        'notes',
        'status',
    ];

    // public static function generateTicketNumber()
    // {
    //     return 'TN-' . str_pad(self::max('id') + 1, 5, '0', STR_PAD_LEFT);
    // }

    public static function generateTicketNumber($departmentId, $requestDate = null)
    {
        // Fetch the department code (assuming Department model has a 'code' or 'name' field)
        $department = Department::findOrFail($departmentId);
        $deptCode = strtoupper(substr($department->code ?? $department->name, 0, 2)); // Use first 2 letters, e.g., 'IT'

        // Use provided request date or current date
        $date = $requestDate ? \Carbon\Carbon::parse($requestDate) : now();
        $monthYear = $date->format('my'); // e.g., '0525' for May 2025

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

        // Format the running number with 3 digits
        $runningNumber = str_pad($counter->counter, 3, '0', STR_PAD_LEFT);

        // Return the formatted ticket number
        return "TN{$deptCode}-{$runningNumber}-{$monthYear}";
    }

    // Relasi ke User (requested_by)
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    // Relasi ke Department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Relasi ke User (request_for)
    public function requestFor()
    {
        return $this->belongsTo(User::class, 'request_for');
    }

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}