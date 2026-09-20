<?php

namespace App\Http\Controllers;

use App\Models\MongoDB\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = ActivityLog::query();
        
        $query->where('user_id', $user->id);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('user_name', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('type')) {
            $query->where('action', $request->type);
        }
        
        if ($request->filled('date')) {
            $dateFilter = $request->date;
            if ($dateFilter === 'today') {
                $query->whereDate('created_at', '>=', now()->startOfDay());
            } elseif ($dateFilter === 'week') {
                $query->whereDate('created_at', '>=', now()->startOfWeek());
            } elseif ($dateFilter === 'month') {
                $query->whereDate('created_at', '>=', now()->startOfMonth());
            }
        }
        
        $activities = $query->latest('created_at')->paginate(20);
        
        return view('user.activity', compact('activities'));
    }
    
    public function export(Request $request)
    {
        return back()->with('success', 'Export functionality coming soon!');
    }
}