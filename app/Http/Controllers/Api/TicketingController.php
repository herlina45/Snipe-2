<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticketing;
use Illuminate\Http\Request;

class TicketingController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticketing::with(['requester', 'department', 'requestFor', 'category'])
            ->when($request->status, fn($query) => $query->where('status', $request->status))
            ->when($request->department_id, fn($query) => $query->where('department_id', $request->department_id));

        $ticketing = $query->paginate(50);
        

        return response()->json([
            'total' => $ticketing->total(),
            'rows' => $ticketing->map(fn($item) => [
                'id' => $item->id,
                'ticket_number' => $item->ticket_number,
                    'requested_date' => $item->requested_date ? \Carbon\Carbon::parse($item->requested_date)->format('d-m-Y') : '-',
                    'required_date' => $item->required_date ? \Carbon\Carbon::parse($item->required_date)->format('d-m-Y') : '-',
                'requested_by' => $item->requester ? ($item->requester->first_name . ' ' . $item->requester->last_name) : 'N/A',
                'request_for' => $item->requestFor ? ($item->requestFor->first_name . ' ' . $item->requestFor->last_name) : 'N/A',
                'department' => $item->department->name ?? 'N/A',
                'category' => $item->category->name ?? 'N/A',
                'notes' => $item->notes ?? 'N/A',
                'status' => $item->status,
            ])
        ]);
    }
}