<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTubeCrush extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Post $post)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('A new TubeCrush has been submitted')
            ->line('Hello,')
            ->line('Someone has sent us a TubeCrush and a draft post has been created.')
            ->line('Click on the button below to edit the post and publish it.')
            ->action('Edit Post', url(route('posts.update', [$this->post])))
            ->line('Thank you!');
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
