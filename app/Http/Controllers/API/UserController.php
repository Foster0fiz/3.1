<?php

namespace App\Http\Controllers\API;   //

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'status'  => 200,
            'data'    => $users,
            'message' => 'Все пользователи получены успешно'
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        $user = User::create($validated);

        return response()->json([
            'status'  => 201,
            'data'    => $user,
            'message' => 'Пользователь создан успешно'
        ], 201);
    }

    public function show($id)
    {
        $user = User::with('posts')->find($id);

        if (!$user) {
            return response()->json([
                'status'  => 404,
                'data'    => null,
                'message' => 'Пользователь не найден'
            ], 404);
        }

        return response()->json([
            'status'  => 200,
            'data'    => $user,
            'message' => 'Пользователь получен успешно'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status'  => 404,
                'data'    => null,
                'message' => 'Пользователь не найден'
            ], 404);
        }

        $validated = $request->validate([
            'name'  => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
        ]);

        $user->update($validated);

        return response()->json([
            'status'  => 200,
            'data'    => $user,
            'message' => 'Пользователь обновлён успешно'
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status'  => 404,
                'data'    => null,
                'message' => 'Пользователь не найден'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status'  => 200,
            'data'    => null,
            'message' => 'Пользователь удалён успешно'
        ], 200);
    }
}
