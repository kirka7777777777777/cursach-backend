<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Card;     // <-- ДОБАВЬТЕ ЭТУ СТРОКУ
use App\Policies\CardPolicy; // <-- ДОБАВЬТЕ ЭТУ СТРОКУ
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        Card::class => CardPolicy::class, // <-- ДОБАВЬТЕ ЭТУ СТРОКУ
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
