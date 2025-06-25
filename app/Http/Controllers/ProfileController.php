<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function stats(Request $request)
    {
        $user = $request->user();

        return [
            'tasks_created' => $user->cardsCreated()->count(),
            'tasks_completed' => $user->cardsAssigned()->where('status', 'done')->count(),
            'tasks_in_progress' => $user->cardsAssigned()->where('status', 'in_progress')->count()
        ];
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:6',
            'avatar' => 'sometimes|string' // для URL аватара
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
