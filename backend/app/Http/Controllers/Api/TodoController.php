<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * @OA.Get(
     *     path="/api/todos",
     *     summary="Mengambil semua daftar tugas milik user",
     *     security={{"bearerAuth":{}}},
     *     tags={"Todos"},
     *     @OA.Response(response=200, description="Sukses"),
     *     @OA.Response(response=401, description="Unauthorized")
     * )
     */
    public function index(Request $request)
    {
        $todos = $request->user()->todos;
        return response()->json($todos, 200);
    }

    /**
     * @OA.Post(
     *     path="/api/todos",
     *     summary="Membuat tugas baru",
     *     security={{"bearerAuth":{}}},
     *     tags={"Todos"},
     *     @OA.RequestBody(
     *         required=true,
     *         @OA.JsonContent(
     *             required={"title","deadline"},
     *             @OA.Property(property="title", type="string", example="Belajar Mengintegrasikan API"),
     *             @OA.Property(property="deadline", type="string", format="date", example="2026-11-05"),
     *             @OA.Property(property="status", type="string", enum={"todo", "done"}, example="todo")
     *         )
     *     ),
     *     @OA.Response(response=201, description="Berhasil Dibuat"),
     *     @OA.Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'deadline' => 'required|date',
            'status' => 'in:todo,done',
        ]);

        $todo = $request->user()->todos()->create($request->all());
        return response()->json(['message' => 'Tugas berhasil dibuat!', 'data' => $todo], 201);
    }

    /**
     * @OA.Get(
     *     path="/api/todos/{id}",
     *     summary="Melihat detail satu tugas",
     *     security={{"bearerAuth":{}}},
     *     tags={"Todos"},
     *     @OA.Parameter(name="id", in="path", required=true, @OA.Schema(type="integer")),
     *     @OA.Response(response=200, description="Sukses"),
     *     @OA.Response(response=403, description="Akses Ditolak"),
     *     @OA\Response(response=404, description="Tidak Ditemukan")
     * )
     */
    public function show(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak!'], 403);
        }
        return response()->json($todo, 200);
    }

    /**
     * @OA.Put(
     *     path="/api/todos/{id}",
     *     summary="Mengubah tugas",
     *     security={{"bearerAuth":{}}},
     *     tags={"Todos"},
     *     @OA.Parameter(name="id", in="path", required=true, @OA.Schema(type="integer")),
     *     @OA.RequestBody(
     *         required=true,
     *         @OA.JsonContent(
     *             @OA.Property(property="title", type="string"),
     *             @OA.Property(property="deadline", type="string", format="date"),
     *             @OA.Property(property="status", type="string", enum={"todo", "done"})
     *         )
     *     ),
     *     @OA.Response(response=200, description="Berhasil Diperbarui"),
     *     @OA.Response(response=403, description="Akses Ditolak")
     * )
     */
    public function update(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak!'], 403);
        }

        $request->validate([
            'title' => 'string|max:255',
            'deadline' => 'date',
            'status' => 'in:todo,done',
        ]);

        $todo->update($request->all());
        return response()->json(['message' => 'Tugas berhasil diperbarui!', 'data' => $todo], 200);
    }

    /**
     * @OA.Delete(
     *     path="/api/todos/{id}",
     *     summary="Menghapus tugas",
     *     security={{"bearerAuth":{}}},
     *     tags={"Todos"},
     *     @OA.Parameter(name="id", in="path", required=true, @OA.Schema(type="integer")),
     *     @OA.Response(response=200, description="Berhasil Dihapus"),
     *     @OA.Response(response=403, description="Akses Ditolak")
     * )
     */
    public function destroy(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak!'], 403);
        }

        $todo->delete();
        return response()->json(['message' => 'Tugas berhasil dihapus!'], 200);
    }
}
