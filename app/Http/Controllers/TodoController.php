<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

/**
 * @OA\Schema(
 * schema="Todo",
 * title="Todo Model",
 * description="Todo data structure",
 * @OA\Property(property="id", type="integer", example=1),
 * @OA\Property(property="user_id", type="integer", example=2),
 * @OA\Property(property="title", type="string", example="Makan"),
 * @OA\Property(property="description", type="string", format="text", example="pake telur"),
 * @OA\Property(property="is_done", type="string", format="int", example=0)
 * )
 */
class TodoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @OA\Post(
     * path="/todo/add",
     * summary="Membuat todo baru",
     * tags={"Todos"},
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"title","description"},
     * @OA\Property(property="title", type="string", format="text", example="Mengerjakan task CRUD API"),
     * @OA\Property(property="description", type="string", format="text", example="waktu 24 jam")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Data sukses dibuat",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Todo created successfully"),
     * @OA\Property(property="data", type="object"),
     * )
     * )
     * )
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $todo = Todo::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'is_done' => '0',
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Todo created successfully',
            'data' => [
                'id' => $todo->id,
                'title' => $todo->title,
                'description' => $todo->description,
            ]
        ], 201);
    }

    /**
     * @OA\Get(
     * path="/todo/list",
     * summary="Mendapatkan list data todos user",
     * tags={"Todos"},
     * security={{"bearerAuth": {}}},
     * @OA\Response(
     * response=200,
     * description="Data sukses diambil",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Data retrieved successfully"),
     * @OA\Property(
     * property="data",
     * type="array",
     * description="Daftar objek todo",
     * @OA\Items(ref="#/components/schemas/Todo")
     * ),
     * )
     * )
     * )
     */
    public function list(Request $request)
    {
        $todos = Todo::where('user_id', auth()->id())->get();
        return response()->json([
            'message' => 'Data retrieved successfully',
            'data' => $todos
        ], 200);
    }

    /**
     * @OA\Get(
     * path="/todo/{id}",
     * summary="Mendapatkan satu data todos user",
     * tags={"Todos"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID todo yang ingin diambil",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Data sukses diambil",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Data retrieved successfully"),
     * @OA\Property(
     * property="data",
     * type="object",
     * description="Object data todo",
     * ),
     * )
     * )
     * )
     */
    public function getOne(Request $request, $id)
    {
        $todo = Todo::where('user_id', auth()->id())->where('id', $id)->first();
        if ($todo) {
            return response()->json([
                'message' => 'Data retrieved successfully',
                'data' => $todo
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found',
            ], status: 404);
        }
    }

    /**
     * @OA\Put(
     * path="/todo/update/{id}",
     * summary="Mengubah data todos user",
     * tags={"Todos"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID todo yang ingin diubah",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Data sukses diubah",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Todo updated successfully"),
     * @OA\Property(
     * property="data",
     * type="object",
     * description="Object data todo",
     * ),
     * )
     * )
     * )
     */
    public function update(Request $request, $id)
    {
        $todo = Todo::where('user_id', auth()->id())->where('id', $id)->first();
        if ($todo) {
            $this->validate($request, [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'is_done' => 'required|boolean',
            ]);

            $updated = Todo::updateOrCreate(
                ['id' => $id],
                [
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'is_done' => $request->input('is_done'),
                ]
            );

            return response()->json([
                'message' => 'Todo updated successfully',
                'data' => $updated
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found',
            ], status: 404);
        }
    }

    /**
     * @OA\Delete(
     * path="/todo/delete/{id}",
     * summary="Menghapus data todos user",
     * tags={"Todos"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID todo yang ingin dihapus",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Data sukses dihapus",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Todo deleted successfully"),
     * )
     * )
     * )
     */
    public function delete($id)
    {
        $todo = Todo::where('user_id', auth()->id())->where('id', $id)->first();
        if ($todo && $todo->delete()) {
            return response()->json([
                'message' => 'Todo deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found',
            ], status: 404);
        }
    }
}
