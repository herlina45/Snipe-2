<?php

namespace App\Http\Controllers;

use App\Models\Ticketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketingController extends Controller
{
    public function index(Request $request)
    {

        return view('ticketing.index');
    }
    public function create()
    {
        return view('ticketing.create', [
            'users' => \App\Models\User::all(),
            'departments' => \App\Models\Department::all(),
            'categories' => \App\Models\Category::all(),
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'requested_date' => 'nullable|date',
            'category_id' => 'required|exists:categories,id',
            'requested_by' => 'required|exists:users,id',
            'request_for' => 'nullable|exists:users,id',
            'required_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $ticket = DB::transaction(function () use ($request) {
            $ticket = new Ticketing();
            $ticket->department_id = $request->input('department_id');
            $ticket->ticket_number = Ticketing::generateTicketNumber(
                $request->input('department_id'),
                $request->input('requested_date')
            );
            $ticket->requested_date = $request->input('requested_date') ?? now();
            $ticket->required_date = $request->input('required_date');
            $ticket->requested_by = $request->input('requested_by');
            $ticket->request_for = $request->input('request_for');
            $ticket->category_id = $request->input('category_id');
            $ticket->status = $request->input('status', 'Waiting for approval');
            $ticket->notes = $request->input('notes');
            $ticket->save();

            return $ticket;
        });

        return redirect()->route('ticketing.index')->with('success', 'Tiket berhasil dibuat.');
    }

        // $validated = $request->validate([
        // 'ticket_number' => 'nullable|unique:ticketings',
        //     'requested_date' => 'required|date',
        //     'required_date' => 'required|date',
        //     'requested_by' => 'required|exists:users,id', // FIXED
        //     'department_id' => 'required|exists:departments,id',
        //     'request_for' => 'nullable|exists:users,id',  // FIXED
        //     'category_id' => 'required|exists:categories,id',
        //     'notes' => 'nullable|string',
        //     'status' => 'nullable|in:waiting_for_approval,approved,rejected',
        //         ]);
        // $departmentId = $validated['department_id'];
        // $requestDate = $validated['requested_date'] ?? null;
        // $validated['ticket_number'] = Ticketing::generateTicketNumber($departmentId, $requestDate);
        // Ticketing::create($validated);
        // return redirect()->route('ticketing.index')->with('success', 'Ticketing created');
    
    

    public function edit(Ticketing $ticketing)
    {
        // return view('ticketing.edit', compact('ticketing, users, departments, categories'));

        return view('ticketing.edit', [
            'item' => $ticketing,
            'users' => \App\Models\User::all(),
            'departments' => \App\Models\Department::all(),
            'categories' => \App\Models\Category::all(),
        ]);
    }

    public function update(Request $request, Ticketing $ticketing)
    {
        $validated = $request->validate([
        'ticket_number' => 'nullable|unique:ticketings',
            'requested_date' => 'required|date',
            'required_date' => 'required|date',
            'requested_by' => 'required|exists:users,id', // FIXED
            'department_id' => 'required|exists:departments,id',
            'request_for' => 'required|exists:users,id',  // FIXED
            'category_id' => 'required|exists:categories,id',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:waiting_for_approval,approved,rejected',
                ]);

        $ticketing->update($validated);
        return redirect()->route('ticketing.index')->with('success', 'Ticketing updated');
    }

    public function updateStatus(Request $request, Ticketing $ticketing)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        $ticketing->update(['status' => $request->status]);
        return redirect()->route('ticketing.index')->with('success', 'Status updated');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'ids' => 'required|array',
            'ids.*' => 'exists:ticketings,id',
        ]);

        Ticketing::whereIn('id', $request->ids)->update(['status' => $request->status]);
        return redirect()->route('ticketing.index')->with('success', 'Ticketing updated');
    }

    public function customReport()
    {
        return response()->json(['message' => 'Custom report not implemented']);
    }
}