<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::guard('web')->attempt(['usuario' => $request->usuario, 'password' => $request->password, 'user_state_id' => 1])) {
            $user = User::select('uuid', 'name', 'type_user')->where('id', Auth::user()->id)->first();
            return response()->json([
                'user' => $user,
                'token' => $request->user()->createToken("token")->plainTextToken,
                'message' => 'Success',
            ]);
        }
        return response([
            'message' => ['Estas credenciales no coinciden con nuestros registros.'],
        ], 404);
    }

    public function register(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $uuid = Str::uuid()->toString();

        $user = User::create([
            'uuid' => $uuid,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'usuario' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'type_user' => 1,
            'user_state_id' => 1,
        ]);

        $response = [
            "user" => $user,
            "token" => $user->createToken('API_Token')->plainTextToken,
        ];

        return response()->json($response, 201);
    }

    public function resetPassword(Request $request)
    {
        // try {
        //     DB::beginTransaction();

            $new_pass = bcrypt($request->password);
            User::where('uuid', $request->uuid)->update(['password' => $new_pass]);
            $user = User::select('uuid', 'name', 'type_user')->where('id', Auth::user()->id)->first();
            $response = [
                "user" => $user,
                'token' => $request->user()->createToken("token")->plainTextToken,
                'message' => 'Success',
            ];
            return response()->json($response, 200);
        //     DB::commit();
        // } catch (\Exception$e) {
        //     DB::rollback();
        //     throw $e;
        // }
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        //return response(null, 200);
        //return response()->noContent();
        return response([
            'status' => true,
            'message' => ['You have successfully logoud out and the token has been deleted.'],
        ], 200);
    }

}
