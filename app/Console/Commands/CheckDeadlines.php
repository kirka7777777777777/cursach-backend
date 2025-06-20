<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckDeadlines extends Command
{
    protected $signature = 'check:deadlines';
    protected $description = 'Проверка проектов с приближающимися дедлайнами и отправка уведомлений';

    public function handle()
    {
        // Получаем проекты с дедлайном, который истекает в течение 1 дня
        $projects = Project::where('deadline', '<=', now()->addDays(1))->get();

        foreach ($projects as $project) {
            // Получаем всех пользователей, связанных с проектом
            $users = User::all(); // Или выберите конкретных пользователей, связанных с проектом
            Notification::send($users, new \App\Notifications\DeadlineNotification($project));
        }

        $this->info('Уведомления об окончании дедлайнов отправлены.');
    }
}
