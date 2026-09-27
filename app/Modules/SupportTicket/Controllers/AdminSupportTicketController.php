<?php

namespace App\Modules\SupportTicket\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\SupportTicket\Models\SupportTicket;
use App\Modules\SupportTicket\Services\SupportTicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSupportTicketController extends Controller
{
    public function __construct(
        protected SupportTicketService $ticketService
    ) {}

    /**
     * Display all tickets in admin helpdesk with filtering.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'category', 'priority', 'search']);
        $tickets = $this->ticketService->getAllTicketsFiltered($filters);

        return view('admin.support.index', [
            'tickets' => $tickets,
            'filters' => $filters,
        ]);
    }

    /**
     * View full ticket thread including internal staff notes.
     */
    public function show(SupportTicket $ticket): View
    {
        $ticket->load(['user', 'course', 'assignedStaff', 'messages.attachments', 'messages.user']);

        $staffMembers = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin', 'super_admin', 'instructor']);
        })->get(['id', 'name', 'email']);

        return view('admin.support.show', [
            'ticket' => $ticket,
            'staffMembers' => $staffMembers,
        ]);
    }

    /**
     * Staff post reply or internal note.
     */
    public function reply(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $request->validate([
            'message' => 'required|string|min:2',
            'is_internal_note' => 'nullable|boolean',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $isInternal = (bool) $request->input('is_internal_note', false);

        $this->ticketService->addReply(
            user: $request->user(),
            ticket: $ticket,
            messageText: $request->input('message'),
            isInternalNote: $isInternal,
            attachment: $request->file('attachment')
        );

        $msg = $isInternal ? 'تم تسجيل الملاحظة الداخلية بنجاح.' : 'تم إرسال الرد للطالب وتحديث حالة التذكرة.';

        return back()->with('status', $msg);
    }

    /**
     * Assign ticket to a staff member.
     */
    public function assign(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'assigned_to_user_id' => 'required|exists:users,id',
        ]);

        $staff = User::findOrFail($validated['assigned_to_user_id']);
        $this->ticketService->assignStaff($ticket, $staff, $request->user());

        return back()->with('status', 'تم تعيين التذكرة للمشرف '.$staff->name);
    }

    /**
     * Update ticket lifecycle status.
     */
    public function updateStatus(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:OPEN,IN_PROGRESS,WAITING_FOR_STUDENT,RESOLVED,CLOSED',
        ]);

        $this->ticketService->updateStatus($ticket, $validated['status'], $request->user());

        return back()->with('status', 'تم تحديث حالة التذكرة إلى: '.$validated['status']);
    }
}
