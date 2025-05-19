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

    public static function generateTicketNumber()
{
    return 'TN-' . str_pad(self::max('id') + 1, 5, '0', STR_PAD_LEFT);
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