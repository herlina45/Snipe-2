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
            'ticket_number' => 'required|unique:ticketing',
            'requested_date' => 'required|date',
            'required_date' => 'required|date',
            'requester_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'request_for_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'notes' => 'nullable|string',
            'status' => 'required|in:waiting_for_approval,approved,rejected',
        ]);

        Ticketing::create($validated);
        return redirect()->route('ticketing.index')->with('success', 'Ticketing created');
    }

    public function edit(Ticketing $ticketing)
    {
        
        return view('ticketing.edit', compact('ticketing, users, departments, categories'));
    }

    public function update(Request $request, Ticketing $ticketing)
    {
        $validated = $request->validate([
            'ticket_number' => 'required|unique:ticketing,ticket_number,' . $ticketing->id,
            'requested_date' => 'required|date',
            'required_date' => 'required|date',
            'requester_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'request_for_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'notes' => 'nullable|string',
            'status' => 'required|in:waiting_for_approval,approved,rejected',
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
            'ids.*' => 'exists:ticketing,id',
        ]);

        Ticketing::whereIn('id', $request->ids)->update(['status' => $request->status]);
        return redirect()->route('ticketing.index')->with('success', 'Ticketing updated');
    }

    public function customReport()
    {
        return response()->json(['message' => 'Custom report not implemented']);
    }
}