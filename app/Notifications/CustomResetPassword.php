<?php

namespace App\Notifications;

use FontLib\Table\Type\name;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{
    use Queueable;
    protected $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $user = $notifiable;
        $url = url('https://' . $user->portal->subdomain . '.' .'wizardylab.com'.route('password.reset', $this->token, false));

        return (new MailMessage)
            ->subject('Password reset '.config('app.name'))
            ->view('vendor.notifications.email', [
                'user' => $user,
                'actionUrl' => $url,
                'actionText' => 'Password reset',
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
