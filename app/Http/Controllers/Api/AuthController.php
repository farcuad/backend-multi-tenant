<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'store_id' => null,
            ]);
            $store = Store::create([
                'name' => $request->store_name,
                'slug' => Str::slug($request->store_name),
                'address' => $request->address ?? 'Dirección por Defecto',
                'user_id' => $user->id,
            ]);

            $user->update(['store_id' => $store->id]);
            DB::commit();
            return response()->json([
                'message' => 'Usuario Creado Correctamente',
                'user' => $user,
                'message_store' => 'Tienda Creada Correctamente',
                'store' => $store
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al crear el usuario', 'error' => $e->getMessage()], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales Incorrectas'], 401);
        }

        $user->load('store');
        $token = $user->createToken('auth_token')->plainTextToken;

        $user->makeHidden(['password', 'email_verified_at']);
        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'store_id' => $user->store_id,
                'role' => $user->role,
            ],
            'token' => $token,
        ], 200);
    }
}
