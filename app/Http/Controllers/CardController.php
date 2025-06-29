<?php


namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function index()
    {
        return Card::with('userAssigned:id,name')->get();
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        // Проверка прав
        if (!auth()->user()->hasRole(['admin', 'manager'])) {
            return response()->json(['message' => 'Only admins and managers can create cards'], 403);
        }

        $card = Card::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'status' => $request->status,
            'assigned_to' => $request->assigned_to,
            'created_by' => auth()->id(),
        ]);

        return response()->json($card, 201);
    }

    public function update(Request $request, Card $card)
    {
        $this->authorize('update', [$card, $request->all()]);

        $validated = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'sometimes|in:todo,in_progress,review,done',
            'assigned_to' => 'sometimes|nullable|exists:users,id',
        ]);

        $card->update($validated);

        return $card->load('userAssigned:id,name');
    }

    public function destroy(Card $card)
    {
        $this->authorize('delete', $card);
        $card->delete();
        return response()->json(['message' => 'Card deleted successfully']);
    }
}
