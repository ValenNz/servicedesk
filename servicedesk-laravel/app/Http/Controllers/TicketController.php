<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\Category;
use App\Models\User;
use App\Models\MongoDB\TicketHistory;
use App\Models\MongoDB\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Ticket::with(['category', 'assignee', 'user']);
        
        if ($user->role !== 'admin') {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }
        
        $filters = $request->only(['search', 'status', 'category', 'priority', 'start_date', 'end_date']);
        $tickets = $query->filter($filters)->latest()->paginate(10)->withQueryString();
        
        $categories = Category::all();
        
        return view('tickets.index', compact('tickets', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:100',
            'description' => 'required|string|min:10',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:Low,Medium,High',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:5120',
        ]);

        $filePath = null;
        $fileName = null;
        $fileSize = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = round($file->getSize() / 1024, 1) . ' KB';
            $filePath = $file->store('tickets/attachments', 'public');
        }

        $ticket = Ticket::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'priority' => $validated['priority'],
            'status' => 'Open',
            'user_id' => Auth::id(),
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
        ]);

        ActivityLog::log(
            Auth::id(),
            'ticket_created',
            "User created ticket: {$ticket->ticket_id}",
            ['ticket_id' => $ticket->id, 'title' => $ticket->title]
        );

        TicketHistory::logChange(
            $ticket->id,
            'created',
            null,
            'Open',
            Auth::id()
        );

        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Your ticket has been submitted successfully!');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['category', 'assignee', 'user', 'comments.user']);
        
        $user = Auth::user();
        if ($user->role !== 'admin' && $ticket->user_id !== $user->id && $ticket->assigned_to !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $employees = User::whereIn('role', ['admin', 'employee'])->get();

        return view('tickets.show', compact('ticket', 'employees'));
    }

    public function addComment(Request $request, Ticket $ticket)
    {

            if (in_array($ticket->status, ['Resolved', 'Closed'])) {
            return redirect()->back()->with('error', 'Cannot add comment to a resolved or closed ticket.');
        }

        $validated = $request->validate([
            'comment' => 'required|string|min:1',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:5120',
        ]);
        
        $validated = $request->validate([
            'comment' => 'required|string|min:1',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:5120',
        ]);

        $filePath = null;
        $fileName = null;
        $fileSize = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = round($file->getSize() / 1024, 1) . ' KB';
            $filePath = $file->store('tickets/comments', 'public');
        }

        $comment = TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
        ]);

        ActivityLog::log(
            Auth::id(),
            'comment_added',
            "User added comment to ticket: {$ticket->ticket_id}",
            ['ticket_id' => $ticket->id, 'comment_id' => $comment->id]
        );

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:Open,In Progress,Resolved,Closed',
        ]);

        $oldStatus = $ticket->status;
        $ticket->update(['status' => $validated['status']]);

        TicketHistory::logChange(
            $ticket->id,
            'status_changed',
            $oldStatus,
            $validated['status'],
            Auth::id()
        );

        ActivityLog::log(
            Auth::id(),
            'status_updated',
            "Ticket {$ticket->ticket_id} status changed from {$oldStatus} to {$validated['status']}",
            ['ticket_id' => $ticket->id, 'old_status' => $oldStatus, 'new_status' => $validated['status']]
        );

        return redirect()->back()->with('success', 'Ticket status updated!');
    }

     public function assign(Request $request, Ticket $ticket)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $oldAssignee = $ticket->assigned_to;
        $ticket->update(['assigned_to' => $validated['assigned_to']]);

        $assigneeName = $validated['assigned_to'] 
            ? User::find($validated['assigned_to'])->name 
            : 'Unassigned';

        ActivityLog::log(
            Auth::id(),
            'ticket_assigned',
            "Ticket {$ticket->ticket_id} assigned to {$assigneeName}",
            ['ticket_id' => $ticket->id, 'old_assignee' => $oldAssignee, 'new_assignee' => $validated['assigned_to']]
        );

        TicketHistory::logChange(
            $ticket->id,
            'assigned',
            $oldAssignee ? User::find($oldAssignee)->name : 'Unassigned',
            $assigneeName,
            Auth::id()
        );

        return redirect()->back()->with('success', 'Ticket assigned successfully!');
    }
    

    public function destroy(Ticket $ticket)
    {
        if ($ticket->file_path) {
            Storage::disk('public')->delete($ticket->file_path);
        }

        $ticket->delete();

        ActivityLog::log(
            Auth::id(),
            'ticket_deleted',
            "Ticket {$ticket->ticket_id} has been deleted",
            ['ticket_id' => $ticket->id]
        );

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully!');
    }
}