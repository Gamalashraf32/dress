<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    use ResponseTrait;

    public function login(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
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
        if (!$token = auth('admin')->attempt($validator->validated())) {
            return $this->returnError(__('auth.failed'), 400);
        }

        return $this->createNewToken($token);
    }


    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:3|max:255',
            'second_name' => 'required|string|min:3|max:255',
            'email' =>  'required|email|unique:admins,email',
            'password' => 'required|confirmed|min:8',
            'location' => 'required|string',
            'phone_number' => 'required|unique:admins|min:11',
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->getMessages() as $message) {
                $error = implode($message);
                $errors[] = $error;
            }
            return $this->returnError(implode(' , ', $errors), 400);
        }

        Admin::create([
            'first_name' => $request->first_name,
            'second_name' => $request->second_name,
            'email' => $request->email,
            'password' => Hash::make($request['password']),
            'location'=>$request->location,
            'phone_number' => $request->phone_number,
        ]);

        $token = auth('admin')->attempt(['email' => $request['email'], 'password' => $request['password']]);
        return $this->createNewToken($token);
    }

    public function profile()
    {

        if (auth('admin')->user()) {
            return $this->returnData('info', auth('admin')->user(), '200');
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
                Rule::unique('admins','email')->ignore(auth('admin')->user()->id),
            ],
            'phone_number' => [
                'required',
                Rule::unique('admins','phone_number')->ignore(auth('admin')->user()->id),
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
        $user=auth('admin')->user();
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
        auth('admin')->logout(true);
        return $this->returnSuccess(__('logged_out'), 200);
    }


    protected function createNewToken(string $token)
    {

        return $this->returnData("Here is a valid token",
            [
                'token' => $token,
                'token_type' => 'bearer',
            ],
            200);
    }


}
