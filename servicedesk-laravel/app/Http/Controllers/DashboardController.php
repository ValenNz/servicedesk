<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isEmployee = $user->role === 'employee';

        if ($isAdmin) {
            $tickets = Ticket::with(['assignee'])->latest()->get();
            return view('admin.dashboard', [
                'stats' => [
                    'total' => $tickets->count(),
                    'open' => $tickets->where('status', 'Open')->count(),
                    'in_progress' => $tickets->where('status', 'In Progress')->count(),
                    'resolved' => $tickets->where('status', 'Resolved')->count(),
                ],
                'recentTickets' => $tickets->take(5),
                'isAdmin' => true,
            ]);
        } elseif ($isEmployee) {
            $tickets = Ticket::with(['assignee'])
                ->where('assigned_to', $user->id)
                ->latest()
                ->get();
            
            return view('employee.dashboard', [
                'stats' => [
                    'total' => $tickets->count(),
                    'open' => $tickets->where('status', 'Open')->count(),
                    'in_progress' => $tickets->where('status', 'In Progress')->count(),
                    'resolved' => $tickets->where('status', 'Resolved')->count(),
                ],
                'recentTickets' => $tickets->take(5),
            ]);
        } else {
            $tickets = Ticket::with(['assignee'])
                ->where('user_id', $user->id)
                ->latest()
                ->get();
            
            return view('user.dashboard', [
                'stats' => [
                    'total' => $tickets->count(),
                    'open' => $tickets->where('status', 'Open')->count(),
                    'in_progress' => $tickets->where('status', 'In Progress')->count(),
                    'resolved' => $tickets->where('status', 'Resolved')->count(),
                ],
                'recentTickets' => $tickets->take(5),
            ]);
        }
    }
}