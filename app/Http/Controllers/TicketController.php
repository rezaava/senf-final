<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $user = User::find(auth()->id());
        $tickets = $user->hasRole('admin')
            ? Ticket::latest()->paginate(10)
            : $user->tickets()->latest()->paginate(10);
        return view('dashboard.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('dashboard.tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return redirect()->route('tickets.index')->with('success', 'تیکت ثبت شد');
    }

    public function show(Ticket $ticket)
    {
        // $this->authorize('view', $ticket); // برای امنیت، اطمینان از اینکه کاربر فقط تیکت خودش رو می‌بینه
        $messages = $ticket->messages()->latest()->get();

        return view('dashboard.tickets.show', compact('ticket', 'messages'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        // $this->authorize('reply', $ticket);

        $request->validate([
            'message' => 'required|string',
        ]);

        $user = User::find(auth()->id());

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_operator' => $user->hasRole('admin') ? true : false,
        ]);
        $user->hasRole('admin') ?? $ticket->update(['status' => 'answered']);

        return back()->with('success', 'پاسخ ارسال شد');
    }
}
