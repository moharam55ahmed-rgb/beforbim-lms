<?php

namespace App\Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BeforbimGeneralNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public ?string $actionUrl = null,
        public ?string $actionText = null,
        public array $extraData = [],
        public array $channels = ['database']
    ) {}

    public function via(object $notifiable): array
    {
        return $this->channels;
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'action_url' => $this->actionUrl,
            'action_text' => $this->actionText,
            'extra_data' => $this->extraData,
            'created_at' => now()->toIso8601String(),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->title)
            ->greeting('مرحباً '.$notifiable->name)
            ->line($this->message);

        if ($this->actionUrl) {
            $mail->action($this->actionText ?: 'عرض التفاصيل في المنصة', $this->actionUrl);
        }

        return $mail->salutation('مع تحيات فريق منصة Beforbim الهندسية');
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
