<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Xin_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = Xin_user::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }


        return $user->createToken("user login")->plainTextToken;
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'company_name' => 'required',
            // 'company_logo' => 'required',
            'user_type' => 'required',
            'email' => 'required|email|unique:xin_users',
            'username' => 'required|unique:xin_users',
            'password' => 'required',
            'nik' => 'required',
            // 'profile_photo' => 'required',
            'profile_background' => 'required',
            'contact_number' => 'required',
            'gender' => 'required',
            'address_1' => 'required',
            'address_2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
            'country' => 'required',
        ]);

        $request["password"] = Hash::make($request->password);

        $uploadLogo = 'Tidak ada foto';
        $uploadFoto = 'Tidak ada foto';

        if ($request->file_logo) {
            $fileLogo = $this->generateRandomString();
            $extension = $request->file_logo->extension();
            $uploadLogo = $fileLogo.'.'.$extension;

            Storage::putFileAs('company_logo', $request->file_logo, $uploadLogo );
        }

        if ($request->file_foto) {
            $fileFoto = $this->generateRandomString();
            $extension = $request->file_foto->extension();
            $uploadFoto = $fileFoto.'.'.$extension;

            Storage::putFileAs('foto_profile', $request->file_foto, $uploadFoto );
        }

        $request["company_logo"] = $uploadLogo;
        $request["profile_photo"] = $uploadFoto;
        $register = Xin_user::create($request->all());
        return response()->json($register);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(["anda sudah logout"]);
    }

    public function me(Request $request) {
        return response()->json(Auth::user());
    }

    function generateRandomString($length = 30) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
