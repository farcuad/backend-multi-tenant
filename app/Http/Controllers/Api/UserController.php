<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
class UserController extends Controller
{
    public function index()
    {
        

        $storeId = auth()->user()->store_id;

        $user = User::where('store_id', $storeId)
            ->where('role', 'employee')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function store(UserRequest $request)
    {
        $admin = auth()->user();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employee',
            'store_id' => $admin->store_id,
        ]);

        return response()->json([
            'message' => 'Empleado creado exitosamente',
            'data' => $user
        ], 201);
    }

    public function destroy(User $user,)
    {
        if ($user->store_id !== auth()->user()->store_id) {
            return response()->json([
                'message' => 'No autorizado para eliminar este empleado'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'Empleado eliminado exitosamente'
        ], 200);
    }
}
