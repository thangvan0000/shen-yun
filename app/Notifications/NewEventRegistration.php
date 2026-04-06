<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Ntfy\Message;
use Wijourdil\NtfyNotificationChannel\Channels\NtfyChannel;

class NewEventRegistration extends Notification
{
    public function __construct(private $registration) {}

    public function via($notifiable): array
    {
        return [NtfyChannel::class];
    }

    public function toNtfy(mixed $notifiable): Message
    {
        $session = $this->registration->eventSession;
        $venue = $session->venue ?? null;

        $message = new Message();
        $message->topic('tiec_tra_shenyun_alerts');
        $message->title('Đăng ký mới!');
        $message->body("{$this->registration->full_name} - {$this->registration->total_count} khách");
        $message->tags(['bell', 'tada']);
        $message->priority(4);

        return $message;
    }
}
