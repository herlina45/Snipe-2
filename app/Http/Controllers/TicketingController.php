<?php

namespace App\Http\Controllers;

use App\Models\Ticketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TicketCounter;

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
        'ticket_number' => 'nullable',
            'requested_date' => 'required|date',
            'required_date' => 'required|date',
            'requested_by' => 'required|exists:users,id', // FIXED
            'department_id' => 'required|exists:departments,id',
            'request_for' => 'required|exists:users,id',  // FIXED
            'category_id' => 'required|exists:categories,id',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:Waiting for approval,Approved,Rejected',
                ]);

$ticketing->update($request->except('ticket_number'));        return redirect()->route('ticketing.index')->with('success', 'Ticketing updated');
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

public function destroy(Ticketing $ticketing)
    {
        DB::transaction(function () use ($ticketing) {
            // Ambil department_id dan month_year dari tiket
            $departmentId = $ticketing->department_id;
            $monthYear = $ticketing->created_at ? $ticketing->created_at->format('my') : now()->format('my');

            // Hapus tiket
            $ticketing->delete();

            // Cek apakah masih ada tiket lain untuk department_id dan month_year ini
            $ticketCount = Ticketing::where('department_id', $departmentId)
                ->whereYear('created_at', $ticketing->created_at->year)
                ->whereMonth('created_at', $ticketing->created_at->month)
                ->count();

            // Kalo ga ada tiket lagi, reset counter
            if ($ticketCount === 0) {
                TicketCounter::where('department_id', $departmentId)
                    ->where('month_year', $monthYear)
                    ->delete();
            }
        });
        return redirect()->route('ticketing.index')->with('success', 'Ticketing deleted');
    }

    // public function customReport()
    // {
    //     return response()->json(['message' => 'Custom report not implemented']);
    // }

    public function export()
    {
        return view('reports.ticketing', [
            'users' => \App\Models\User::all(), // Tambahkan ini
            'departments' => \App\Models\Department::all(),
            'categories' => \App\Models\Category::all()
        ]);
    }

    public function postExport(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'ticket_number' => 'nullable|boolean',
            'requested_date' => 'nullable|boolean',
            'required_date' => 'nullable|boolean',
            'requested_by' => 'nullable|boolean',
            'request_for' => 'nullable|boolean',
            'department' => 'nullable|boolean',
            'category' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'notes' => 'nullable|boolean',
            'requested_start' => 'nullable|date',
            'requested_end' => 'nullable|date',
            'required_start' => 'nullable|date',
            'required_end' => 'nullable|date',
            'by_dept_id' => 'nullable|array',
            'by_status_id' => 'nullable|array',
            'by_category_id' => 'nullable|array',
            'by_requested_by_id' => 'nullable|array',
            'by_request_for_id' => 'nullable|array',
        ]);

        // Query ticketing dengan relasi
        $query = Ticketing::with(['requester', 'requestFor', 'department', 'category', 'status']);

        // Filter
        if ($request->filled('by_dept_id')) {
            $query->whereIn('department_id', $request->by_dept_id);
        }
        if ($request->filled('by_status_id')) {
            $query->whereIn('status', $request->by_status_id); // Sesuaikan dengan kolom status
        }
        if ($request->filled('by_category_id')) {
            $query->whereIn('category_id', $request->by_category_id);
        }
        if ($request->filled('by_requester_id')) {
            $query->whereIn('requested_by', $request->by_requester_id); // Perbaiki dari 'requested_by_id'
        }
        if ($request->filled('by_request_for_id')) {
            $query->whereIn('request_for', $request->by_request_for_id); // Perbaiki dari 'request_for_id'
        }
        if ($request->filled('requested_start') && $request->filled('requested_end')) {
            $query->whereBetween('requested_date', [$request->requested_start, $request->requested_end]);
        }
        if ($request->filled('required_start') && $request->filled('required_end')) {
            $query->whereBetween('required_date', [$request->required_start, $request->required_end]);
        }

        // Ambil data
        $tickets = $query->get();

        // Buat CSV
        $csv = \League\Csv\Writer::createFromString();
        $headers = [];
        if ($request->ticket_number) $headers[] = 'Ticket Number';
        if ($request->requested_date) $headers[] = 'Requested Date';
        if ($request->required_date) $headers[] = 'Required Date';
        if ($request->requested_by) $headers[] = 'Requested By';
        if ($request->request_for) $headers[] = 'Request For';
        if ($request->department) $headers[] = 'Department';
        if ($request->category) $headers[] = 'Category';
        if ($request->status) $headers[] = 'Status';
        if ($request->notes) $headers[] = 'Notes';

        $csv->insertOne($headers);

        foreach ($tickets as $ticket) {
            $row = [];
            if ($request->ticket_number) $row[] = $ticket->ticket_number;
            if ($request->requested_date) $row[] = $ticket->requested_date;
            if ($request->required_date) $row[] = $ticket->required_date;
            if ($request->requested_by) $row[] = $ticket->requester ? ($ticket->requester->first_name . ' ' . $ticket->requester->last_name) : 'N/A';
            if ($request->request_for) $row[] = $ticket->requestFor ? ($ticket->requestFor->first_name . ' ' . $ticket->requestFor->last_name) : 'N/A';
            if ($request->department) $row[] = $ticket->department?->name ?? 'N/A';
            if ($request->category) $row[] = $ticket->category?->name ?? 'N/A';
            if ($request->status) $row[] = $ticket->status ?? 'N/A'; // Status di sini string, bukan relasi
            if ($request->notes) $row[] = $ticket->notes;
            $csv->insertOne($row);
        }

        return response($csv->getContent(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="ticketing_export.csv"',
        ]);
    }
}