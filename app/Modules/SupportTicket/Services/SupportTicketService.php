<?php

namespace App\Modules\SupportTicket\Services;

use App\Models\User;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\SupportTicket\Models\SupportTicket;
use App\Modules\SupportTicket\Models\TicketAttachment;
use App\Modules\SupportTicket\Models\TicketMessage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class SupportTicketService
{
    /**
     * Create a new support ticket initiated by a student.
     *
     * @param array{
     *     subject: string,
     *     category: string,
     *     priority?: string,
     *     course_id?: int|null,
     *     message: string
     * } $data
     */
    public function createTicket(User $student, array $data, ?UploadedFile $attachment = null): SupportTicket
    {
        return DB::transaction(function () use ($student, $data, $attachment) {
            $ticket = SupportTicket::create([
                'user_id' => $student->id,
                'course_id' => $data['course_id'] ?? null,
                'category' => $data['category'] ?? 'TECHNICAL',
                'priority' => $data['priority'] ?? 'NORMAL',
                'status' => 'OPEN',
                'subject' => $data['subject'],
            ]);

            $message = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $student->id,
                'is_staff_reply' => false,
                'is_internal_note' => false,
                'message' => $data['message'],
            ]);

            if ($attachment) {
                $this->saveAttachment($message, $attachment);
            }

            AuditLog::log(
                module: 'Support',
                action: 'TICKET_CREATED',
                actor: $student,
                target: $ticket,
                newValues: ['ticket_number' => $ticket->ticket_number, 'subject' => $ticket->subject],
                reason: 'Student opened new academic/technical support ticket'
            );

            return $ticket->load(['messages.attachments', 'user', 'course']);
        });
    }

    /**
     * Post a reply to a support ticket.
     */
    public function addReply(
        User $user,
        SupportTicket $ticket,
        string $messageText,
        bool $isInternalNote = false,
        ?UploadedFile $attachment = null
    ): TicketMessage {
        return DB::transaction(function () use ($user, $ticket, $messageText, $isInternalNote, $attachment) {
            $isStaff = $user->hasAnyRole(['admin', 'super_admin', 'instructor']);

            $ticketMessage = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'is_staff_reply' => $isStaff,
                'is_internal_note' => $isInternalNote,
                'message' => $messageText,
            ]);

            if ($attachment) {
                $this->saveAttachment($ticketMessage, $attachment);
            }

            // Update ticket status based on reply context
            if (! $isInternalNote) {
                if ($isStaff) {
                    $ticket->status = 'WAITING_FOR_STUDENT';
                } else {
                    $ticket->status = 'IN_PROGRESS';
                }
                $ticket->save();
            }

            return $ticketMessage->load('attachments');
        });
    }

    /**
     * Assign ticket to a specific staff member.
     */
    public function assignStaff(SupportTicket $ticket, User $staff, User $assigner): SupportTicket
    {
        $oldAssigned = $ticket->assigned_to_user_id;
        $ticket->assigned_to_user_id = $staff->id;

        if ($ticket->status === 'OPEN') {
            $ticket->status = 'IN_PROGRESS';
        }

        $ticket->save();

        AuditLog::log(
            module: 'Support',
            action: 'TICKET_ASSIGNED',
            actor: $assigner,
            target: $ticket,
            oldValues: ['assigned_to' => $oldAssigned],
            newValues: ['assigned_to' => $staff->id],
            reason: 'Ticket reassigned to specialist staff member'
        );

        return $ticket;
    }

    /**
     * Update ticket status (OPEN, IN_PROGRESS, WAITING_FOR_STUDENT, RESOLVED, CLOSED).
     */
    public function updateStatus(SupportTicket $ticket, string $newStatus, User $actor): SupportTicket
    {
        $oldStatus = $ticket->status;
        $ticket->status = $newStatus;

        if (in_array($newStatus, ['RESOLVED', 'CLOSED'], true)) {
            $ticket->resolved_at = now();
        } else {
            $ticket->resolved_at = null;
        }

        $ticket->save();

        AuditLog::log(
            module: 'Support',
            action: 'TICKET_STATUS_UPDATED',
            actor: $actor,
            target: $ticket,
            oldValues: ['status' => $oldStatus],
            newValues: ['status' => $newStatus],
            reason: 'Ticket status workflow transition'
        );

        return $ticket;
    }

    /**
     * Get paginated tickets for a student.
     */
    public function getStudentTickets(User $student, int $perPage = 15): LengthAwarePaginator
    {
        return SupportTicket::where('user_id', $student->id)
            ->with(['course', 'assignedStaff'])
            ->withCount('messages')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get filtered tickets for administration & staff helpdesk.
     *
     * @param  array<string, mixed>  $filters
     */
    public function getAllTicketsFiltered(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = SupportTicket::with(['user', 'course', 'assignedStaff'])
            ->withCount('messages')
            ->latest();

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (! empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (! empty($filters['search'])) {
            $term = '%'.$filters['search'].'%';
            $query->where(function ($q) use ($term) {
                $q->where('ticket_number', 'like', $term)
                    ->orWhere('subject', 'like', $term);
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Save an uploaded file attachment for a ticket message.
     */
    protected function saveAttachment(TicketMessage $message, UploadedFile $file): TicketAttachment
    {
        $path = $file->store('support-attachments', 'public');

        return TicketAttachment::create([
            'ticket_message_id' => $message->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size_bytes' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
        ]);
    }
}
