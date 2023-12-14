<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Traits\JsonResponseTrait;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use JsonResponseTrait;
    public function register(StoreUserRequest $request) {
        try {
                $user = User::create([
                    'name'=> $request->name,
                    'email'=>$request->email,
                    'password'=>Hash::make($request->password),
                ]);
           
            $token=$user->createToken('authToken'.$user->name)->plainTextToken;//token generated from user object and this method return Laravel\Sanctum\NewAccessToken instance  API tokens are hashed using SHA-256 hashing before being stored in database but you may access the plain-text value of the token using the plainTextToken property of the NewAccessToken instance.
            $user->assignRole('editor');
            return $this->jsonSuccessResponse('Registytration successful',['token' => $token], 201);

        }catch(ValidationException $e){
            //msg, status
            return $this->jsonErrorResponse('Validation error',402,['errors' => $e->errors()]);
        }
    }

    public function login(LoginUserRequest $request){
        if(!Auth::attempt($request->only(['email', 'password']))){
            return $this->jsonErrorResponse('credintial doesnt match',401);
        }
        $user= User::where('email',$request->email)->first();
        $token = $user->createToken('authToken'.$user->name)->plainTextToken;
        return $this->jsonSuccessResponse('login was successful',['token' => $token], 201);
    }

}
