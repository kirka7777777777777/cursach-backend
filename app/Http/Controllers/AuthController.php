<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role; // <-- ДОБАВИТЬ ЭТУ СТРОКУ!
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Можно добавить для отладки

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Присвоение роли пользователю
            $userRole = Role::where('name', 'user')->first();
            if ($userRole) {
                $user->roles()->attach($userRole);
            } else {
                // Если роли 'user' нет, можно залогировать ошибку или создать роль
                Log::warning('Role "user" not found, user registered without default role.', ['user_id' => $user->id]);
                // Или, если это приемлемо, создать роль:
                // $userRole = Role::create(['name' => 'user']);
                // $user->roles()->attach($userRole);
            }

            return response()->json(['message' => 'User registered successfully'], 201);

        } catch (\Exception $e) {
            // Отладочная информация:
            Log::error('Registration error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Registration failed', 'error' => $e->getMessage()], 500);
        }
    }
    public function registerManager(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'secret_key' => 'required|string' // Секретный ключ для регистрации менеджеров
        ]);

        if ($request->secret_key !== config('app.manager_secret_key')) {
            return response()->json(['message' => 'Invalid secret key'], 403);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $managerRole = Role::where('name', 'manager')->firstOrFail();
        $user->roles()->attach($managerRole);

        return response()->json(['message' => 'Manager registered successfully'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Неверные учетные данные'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;

        $user = $request->user();

        return response()->json([
            'token' => $user->createToken('API Token')->plainTextToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->toArray() // Добавляем роли
            ]
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        $user->delete();
        return response()->json(['message' => 'Account deleted successfully']);
    }
}
