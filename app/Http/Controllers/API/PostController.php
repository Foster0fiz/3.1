<?php

namespace App\Http\Controllers;


use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    // GET /api/posts
    public function index()
    {
        $posts = Post::all();
        return response()->json([
            'status'  => 200,
            'data'    => $posts,
            'message' => 'Все посты получены успешно'
        ], 200);
    }

    // POST /api/posts
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            // Проверка на существование user_id в таблице users
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
        ]);

        $post = Post::create($validated);

        return response()->json([
            'status'  => 201,
            'data'    => $post,
            'message' => 'Пост создан успешно'
        ], 201);
    }

    // GET /api/posts/{id}
    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status'  => 404,
                'data'    => null,
                'message' => 'Пост не найден'
            ], 404);
        }

        return response()->json([
            'status'  => 200,
            'data'    => $post,
            'message' => 'Пост получен успешно'
        ], 200);
    }

    // PUT /api/posts/{id}
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status'  => 404,
                'data'    => null,
                'message' => 'Пост не найден'
            ], 404);
        }

        $validated = $request->validate([
            'title'   => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'user_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('users', 'id'),
            ],
        ]);

        $post->update($validated);

        return response()->json([
            'status'  => 200,
            'data'    => $post,
            'message' => 'Пост обновлён успешно'
        ], 200);
    }

    // DELETE /api/posts/{id}
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status'  => 404,
                'data'    => null,
                'message' => 'Пост не найден'
            ], 404);
        }

        $post->delete();

        return response()->json([
            'status'  => 200,
            'data'    => null,
            'message' => 'Пост удалён успешно'
        ], 200);
    }
}
