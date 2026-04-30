<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BugReport;
use Illuminate\Http\Request;

class BugReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:open,in_review,resolved',
        ]);

        $bugReports = BugReport::query()
            ->with('user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('page_url', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.index', compact('bugReports'));
    }

    public function show(BugReport $bugReport)
    {
        $bugReport->load('user');

        return view('admin.reports.show', compact('bugReport'));
    }

    public function update(Request $request, BugReport $bugReport)
    {
        $data = $request->validate([
            'status' => 'required|in:open,in_review,resolved',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $bugReport->update($data);

        return back()->with('success', 'Bug report updated successfully.');
    }
}
