<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    private $user;
    public function __construct()
    {
        $this->user = new User();
    }

    public function login(LoginRequest $request)
    {
        try {
            DB::beginTransaction();
            // If credentials in valid
            if (!Auth::attempt($request->only('email', 'password'))) {
                DB::rollback();
                return error("You entered invalid credentials", ERROR_400);
            }
            // if credentials are valid
            $user = $this->user->newQuery()->where('email', $request->email)->first();
            $user->token = $user->createToken('bearer_token')->plainTextToken;
            $user->token_type = 'Bearer';
            DB::commit();
            return successWithData(__('auth.loggedIn'), $user);

        } catch (QueryException $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        }
    }

    public function logout(Request $request)
    {
        try {
            DB::beginTransaction();
            Auth::user()->currentAccessToken()->delete();
            DB::commit();
            return success('Logged Out Succesfully');

        } catch (QueryException $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        }

    }
}
