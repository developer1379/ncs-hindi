<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use App\Models\BugReport;
use Illuminate\Http\Request;

class BugReportController extends Controller
{
    public function create()
    {
        return view('webapp.bug-report');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'page_url' => 'nullable|url|max:2048',
            'description' => 'required|string|max:20000',
        ]);

        $data['user_id'] = $request->user()->id;
        $data['status'] = 'open';

        BugReport::create($data);

        return redirect()
            ->route('webapp.bug-reports.create')
            ->with('success', 'Bug report submitted successfully.');
    }
}
