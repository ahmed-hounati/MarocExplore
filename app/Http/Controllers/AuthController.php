<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Dotenv\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Info(
 *      title="Auth API",
 *      version="1.0.0",
 *      description="APIs for user authentication",
 *      @OA\Contact(
 *          email="admin@example.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 * @OA\Post(
 *      path="/api/register",
 *      operationId="registerUser",
 *      tags={"Auth"},
 *      summary="Register a new user",
 *      description="Registers a new user with the provided name, email, and password.",
 *      @OA\RequestBody(
 *          required=true,
 *          description="User data",
 *          @OA\JsonContent(
 *              required={"name", "email", "password"},
 *              @OA\Property(property="name", type="string", example="John Doe"),
 *              @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *              @OA\Property(property="password", type="string", example="password123")
 *          )
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="User registered successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="User registered successfully"),
 *              @OA\Property(property="user", type="object",
 *                  @OA\Property(property="name", type="string"),
 *                  @OA\Property(property="email", type="string")
 *              )
 *          )
 *      )
 * )
 *
 * @OA\Post(
 *      path="/api/login",
 *      operationId="loginUser",
 *      tags={"Auth"},
 *      summary="Log in as a user",
 *      description="Logs in a user with the provided email and password.",
 *      @OA\RequestBody(
 *          required=true,
 *          description="User credentials",
 *          @OA\JsonContent(
 *              required={"email", "password"},
 *              @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *              @OA\Property(property="password", type="string", example="password123")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Login successful",
 *          @OA\JsonContent(
 *              @OA\Property(property="access_token", type="string"),
 *              @OA\Property(property="token_type", type="string"),
 *              @OA\Property(property="expires_in", type="integer")
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthorized"
 *      )
 * )
 *
 * @OA\Post(
 *      path="/api/logout",
 *      operationId="logoutUser",
 *      tags={"Auth"},
 *      summary="Log out user",
 *      description="Logs out the authenticated user.",
 *      @OA\Response(
 *          response=200,
 *          description="Successfully logged out"
 *      ),
 *      security={{"BearerAuth": {}}}
 * )
 */
class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
