<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ResponseAPI;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            return $this->success("bad request, error validation", $validator->errors(), 400, count($validator->errors()));
        }
        $id = $request->_id > 0 ? $request->_id : 0;
        $request->request->add(['password' => Hash::make($request->password)]);


        $user = $this->userRepository->CreateOrUpdate($request->input(), $id);

        return $user;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rulesLogin());
        if ($validator->fails()) {
            return $this->success("bad request, error validation", $validator->errors(), 400, count($validator->errors()));
        }


        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return $this->error("email atau password salah", 400, 1);
        }
        return $this->success("berhasil", $token, 200, 1);
    }

    private function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ];
    }

    private function rulesLogin()
    {
        return [
            'email' => 'required',
            'password' => 'required',
        ];
    }
}
