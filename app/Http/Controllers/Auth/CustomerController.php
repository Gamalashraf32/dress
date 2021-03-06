<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    use ResponseTrait;
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->getMessages() as $message) {
                $error = implode($message);
                $errors[] = $error;
            }
            return $this->returnError(implode(' , ', $errors), 400);
        }
        if (!$token = auth('api')->attempt($validator->validated())) {
            return $this->returnError(__('auth.failed'), 400);
        }

        return $this->createNewToken($token);
    }
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:3|max:255',
            'second_name' => 'required|string|min:3|max:255',
            'email' =>  'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'location' => 'required|string',
            'phone_number' => 'required|unique:users|min:11',
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->getMessages() as $message) {
                $error = implode($message);
                $errors[] = $error;
            }

            return $this->returnError(implode(' , ', $errors), 400);
        }

        User::create([
            'first_name' => $request->first_name,
            'second_name' => $request->second_name,
            'email' => $request->email,
            'password' => Hash::make($request['password']),
            'location'=>$request->location,
            'phone_number' => $request->phone_number,
        ]);

        $token = auth('api')->attempt(['email' => $request['email'], 'password' => $request['password']]);
        return $this->createNewToken($token);

    }
    public function profile()
    {
        if (auth('api')->user()) {
            return $this->returnData('customer-profile', auth('api')->user(), '200');
        } else {
            return $this->returnError('you are not authorized to show this data', 401, false);
        }
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:3|max:255',
            'second_name' => 'required|string|min:3|max:255',
            'location'=>'required|string',
            'email' => [
                'required',
                Rule::unique('users','email')->ignore(auth('api')->user()->id),
            ],
            'phone_number' => [
                'required',
                Rule::unique('users','phone_number')->ignore(auth('api')->user()->id),
            ],

        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->getMessages() as $message) {
                $error = implode($message);
                $errors[] = $error;
            }
            return $this->returnError(implode(' , ', $errors), 400);
        }
        $user=auth('api')->user();
        $user->first_name = $request->first_name;
        $user->second_name = $request->second_name;
        $user->email = $request->email;
        $user->location =$request->location;
        $user->phone_number = $request->phone_number;
        $user->save();
        return $this->returnSuccess('your data updated successfully', 200);
    }
    public function logout(): JsonResponse
    {
        auth('api')->logout(true);
        return $this->returnSuccess(__('logged_out'), 200);

    }
    protected function createNewToken(string $token): JsonResponse
    {

        return $this->returnData("Here is a valid token",
            [
                'token' => $token,
                'token_type' => 'bearer',
            ],
            200);
    }
}
