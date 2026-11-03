<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: "/api/register",
        summary: "Mendaftarkan user baru (Register)",
        tags: ["Auth"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["name", "email", "password", "password_confirmation"],
            properties: [
                new OA\Property(property: "name", type: "string", example: "Koleantara"),
                new OA\Property(property: "email", type: "string", format: "email", example: "user@email.com"),
                new OA\Property(property: "password", type: "string", format: "password", example: "password123"),
                new OA\Property(property: "password_confirmation", type: "string", format: "password", example: "password123")
            ]
        )
    )]
    #[OA\Response(response: 201, description: "Registrasi sukses!")]
    #[OA\Response(response: 422, description: "Validasi Gagal")]
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Registrasi sukses!'], 201);
    }

    #[OA\Post(
        path: "/api/login",
        summary: "Masuk ke sistem untuk mendapatkan token (Login)",
        tags: ["Auth"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["email", "password"],
            properties: [
                new OA\Property(property: "email", type: "string", format: "email", example: "user@email.com"),
                new OA\Property(property: "password", type: "string", format: "password", example: "password123")
            ]
        )
    )]
    #[OA\Response(response: 200, description: "Login Sukses")]
    #[OA\Response(response: 401, description: "Email atau password salah")]
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login sukses!',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    #[OA\Post(
        path: "/api/logout",
        summary: "Keluar dari sistem dan menghapus token (Logout)",
        security: [["bearerAuth" => []]],
        tags: ["Auth"]
    )]
    #[OA\Response(response: 200, description: "Logout sukses!")]
    #[OA\Response(response: 401, description: "Unauthorized")]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout sukses!'], 200);
    }
        #[OA\Get(
        path: "/api/profile",
        summary: "Mengambil data profil user yang sedang login (Profil)",
        security: [["bearerAuth" => []]],
        tags: ["Auth"]
    )]
    #[OA\Response(response: 200, description: "Sukses mengambil data profil")]
    #[OA\Response(response: 401, description: "Unauthorized")]
    public function profile(Request $request)
    {
        // Mengembalikan data user yang sedang login saat ini
        return response()->json($request->user(), 200);
    }

}
