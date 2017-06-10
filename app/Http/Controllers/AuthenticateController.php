<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authenticate;
use JWTAuth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;


class AuthenticateController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.refresh')->only('refresh');
    }
    public function auth(Authenticate $request)
    {
        $credentials = $request->only('email', 'password');


        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }

    /**
     * Refresh Token
     *
     * * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
     * Check Authorization header for new token.
     * Call this API to exchange expired (not invalid!) JWT token with a fresh one.
     *
     */
    public function refresh()
    {
        return response()->json(['status' => 'Ok', 'message' => 'Check Authorization Header for new token']);
    }

    /**
     * Get Authenticated User
     *
     * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
     *
     * Retrieves the user associated with the JWT token.
     *
     */
    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }
        $food_saved = count($user->comments);
        $food_offered = count($user->foods);

        $user['saved']      = $food_saved;
        $user['offered']    = $food_offered;
        return response()->json(compact('user'), 200);
    }

}
