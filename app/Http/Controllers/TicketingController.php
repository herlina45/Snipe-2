<?php

namespace App\Http\Controllers;

use App\Models\Ticketing;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
        'ticket_number' => 'nullable|unique:ticketings',
            'requested_date' => 'required|date',
            'required_date' => 'required|date',
            'requested_by' => 'required|exists:users,id', // FIXED
            'department_id' => 'required|exists:departments,id',
            'request_for' => 'nullable|exists:users,id',  // FIXED
            'category_id' => 'required|exists:categories,id',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:waiting_for_approval,approved,rejected',
                ]);
        $validated['ticket_number'] = Ticketing::generateTicketNumber();
        Ticketing::create($validated);
        return redirect()->route('ticketing.index')->with('success', 'Ticketing created');
    }

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