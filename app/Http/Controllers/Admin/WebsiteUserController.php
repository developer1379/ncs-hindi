<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class WebsiteUserController extends Controller
{
    public function index(Request $request)
    {
        // 1. Validate inputs
        $request->validate([
            'search' => 'nullable|string|max:100',
            'status' => 'nullable|in:0,1',
        ]);

        // 2. Build Query for Website Users (user_type = 3)
        $query = User::where('user_type', 3)
            ->withCount(['threads', 'replies', 'interactions']);

        // Filter by Search (Name or Email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // Filter by Account Status
        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        // 3. Execute with Pagination
        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.website_users.index', compact('users'));
    }

    public function show($id)
    {
        // Retrieve website user with their complete activity relations
        $user = User::where('user_type', 3)
            ->with([
                'media', 
                'threads.category', 
                'replies.thread', 
                'interactions.music'
            ])
            ->findOrFail($id);

        return view('admin.website_users.show', compact('user'));
    }

    public function destroy($id)
    {
        $user = User::where('user_type', 3)->findOrFail($id);
        
        $timestamp = time();
        $user->update([
            'name'  => $user->name . '@deleted_' . $timestamp,
            'email' => $user->email . '@deleted_' . $timestamp,
            'phone' => $user->phone ? $user->phone . '@deleted_' . $timestamp : null,
        ]);

        $user->delete();

        return redirect()->route('admin.website-users.index')->with('success', 'Website user deleted successfully!');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'status' => 'required|boolean',
        ]);

        $user = User::where('user_type', 3)->findOrFail($request->id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['message' => 'Website user status updated successfully.']);
    }
}
