<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BulkTicketingController extends Controller
{
    public function edit(Request $request)
    {
        $ticketIds = $request->input('ids', []);
        if (empty($ticketIds)) {
            return redirect()->back()->with('error', 'No tickets selected.');
        }
        // Logika buat edit bulk
        return redirect()->back()->with('success', 'Bulk edit initiated.');
    }

    public function destroy(Request $request)
    {
        $ticketIds = $request->input('ids', []);
        if (empty($ticketIds)) {
            return redirect()->back()->with('error', 'No tickets selected.');
        }
        // Logika buat delete bulk
        return redirect()->back()->with('success', 'Tickets deleted.');
    }

    public function restore(Request $request)
    {
        $ticketIds = $request->input('ids', []);
        if (empty($ticketIds)) {
            return redirect()->back()->with('error', 'No tickets selected.');
        }
        // Logika buat restore bulk
        return redirect()->back()->with('success', 'Tickets restored.');
    }

    public function update(Request $request)
    {
        $ticketIds = $request->input('ids', []);
        if (empty($ticketIds)) {
            return redirect()->back()->with('error', 'No tickets selected.');
        }
        // Logika buat update bulk
        return redirect()->back()->with('success', 'Tickets updated.');
    }
}