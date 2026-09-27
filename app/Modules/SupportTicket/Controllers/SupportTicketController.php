<?php

namespace App\Modules\SupportTicket\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Models\Course;
use App\Modules\SupportTicket\Models\SupportTicket;
use App\Modules\SupportTicket\Services\SupportTicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function __construct(
        protected SupportTicketService $ticketService
    ) {}

    /**
     * Display student tickets list.
     */
    public function index(Request $request): View
    {
        $tickets = $this->ticketService->getStudentTickets($request->user());

        return view('support.index', [
            'tickets' => $tickets,
        ]);
    }

    /**
     * Show ticket creation form.
     */
    public function create(Request $request): View
    {
        $user = $request->user();
        $enrolledCourses = Course::whereHas('enrollments', function ($q) use ($user) {
            $q->where('user_id', $user->id)->where('status', 'ACTIVE');
        })->get(['id', 'title_ar', 'title_en']);

        return view('support.create', [
            'enrolledCourses' => $enrolledCourses,
        ]);
    }

    /**
     * Store new support ticket.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string|min:4|max:255',
            'category' => 'required|string|in:TECHNICAL,ACADEMIC_CONTENT,BILLING,CERTIFICATE,OTHER',
            'priority' => 'nullable|string|in:LOW,NORMAL,HIGH,URGENT',
            'course_id' => 'nullable|exists:courses,id',
            'message' => 'required|string|min:10',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        $attachment = $request->file('attachment');

        $ticket = $this->ticketService->createTicket(
            student: $request->user(),
            data: $validated,
            attachment: $attachment
        );

        return redirect()->route('support.show', $ticket)
            ->with('status', 'تم إنشاء تذكرة الدعم بنجاح! رقم التذكرة: '.$ticket->ticket_number);
    }

    /**
     * View ticket details and messages (excluding staff internal notes).
     */
    public function show(Request $request, SupportTicket $ticket): View
    {
        // Gate check: only ticket owner or staff
        if ($ticket->user_id !== $request->user()->id && ! $request->user()->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, 'غير مصرح لك باستعراض هذه التذكرة.');
        }

        $ticket->load([
            'course',
            'assignedStaff',
            'messages' => function ($q) {
                $q->where('is_internal_note', false)->with('attachments', 'user');
            },
        ]);

        return view('support.show', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Add student reply.
     */
    public function reply(Request $request, SupportTicket $ticket): RedirectResponse
    {
        if ($ticket->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|min:2',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $this->ticketService->addReply(
            user: $request->user(),
            ticket: $ticket,
            messageText: $request->input('message'),
            isInternalNote: false,
            attachment: $request->file('attachment')
        );

        return back()->with('status', 'تم إرسال ردك بنجاح.');
    }
}
