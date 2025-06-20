<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeadlineNotification extends Notification
{
    use Queueable;

    protected $project;

    public function __construct($project)
    {
        $this->project = $project;
    }

    public function via($notifiable)
    {
        return ['mail']; // Можно добавить другие каналы, например, 'database'
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Напоминание о дедлайне')
            ->line('Дедлайн для проекта "' . $this->project->name . '" приближается.')
            ->action('Посмотреть проект', url('/projects/' . $this->project->id))
            ->line('Спасибо, что используете наше приложение!');
    }
}
