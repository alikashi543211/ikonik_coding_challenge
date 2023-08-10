<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    private $user;
    public function __construct()
    {
        $this->user = new User();
    }

    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $user = $this->user->newInstance();
            $user->fill($inputs);
            $user->password = Hash::make($inputs['password']);
            if(!$user->save())
            {
                DB::rollBack();
                return error(GENERAL_ERROR_MESSAGE, ERROR_400);
            }
            Auth::login($user);
            $user->token = $user->createToken('bearer_token')->plainTextToken;
            $user->token_type = 'Bearer';
            DB::commit();
            return successWithData('User registered successfully', $user);

        } catch (QueryException $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage(), ERROR_500);
        }
    }
}
