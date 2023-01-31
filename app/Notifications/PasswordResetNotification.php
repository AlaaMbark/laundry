<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $resetPasswordToken;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $resetPasswordToken)
    {
        $this->user = $user->name;
        $this->resetPasswordToken = $resetPasswordToken;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(__('message.message-subject-eml') . $this->user)
                    ->line(__('message.message-line1-eml'))
                    ->line(__('message.message-line2-eml'))
                    ->line($this->resetPasswordToken)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
