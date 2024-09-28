<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    public function __construct($url)                      //この行を修正
    {
        $this->url = $url;                                 //この行を修正
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
    //ここから修正
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(config('app.name'). ' パスワードリセットURLの送付')
            ->greeting('いつもご利用頂きありがとうございます')
            ->action('パスワードリセット', $this->url)
            ->line('こちらからパスワードリセットを行ってください');
    }
    //ここまで修正

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
