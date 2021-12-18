<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//panggil model User
use App\Models\User;
//untuk validasi
use Illuminate\Support\Facades\Validator;
//untuk hash
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
      //validasi 
      $data = Validator::make($request->all(), [
        'email' => 'required|email',
        'name' => 'required',
        'password' => 'required|min:8',
        'confirmPassword' => 'required|same:password'
      ]);
      
      //jika validasi gagal 
      if($data->fails()) {
        return response()->json($data->errors(), 422);
      }
      
      //save 
      $user = User::create([
        'email' => $request->email,
        'name' => $request->name,
        'password' => Hash::make($request->password)
      ]);
      
      return response()->json([
        'message' => 'Register successfuly'
        ], 201);
    }
    
    public function login(Request $request)
    {
        //validasi
        $data = Validator::make($request->all(), [
          'email' => 'required|email',
          'password' => 'required|min:8'
        ]);
        
        //jika Validasi gagal
        if($data->fails()) {
          return response()->json($data->errors(), 422);
        }
        
        //cari user 
        $user = User::where('email', $request->email)->first();
        
        //jika user tidak ditemukan
        if(!$user || !Hash::check($request->password, $user->password)) {
          return response()->json([
            'userNotFound' => 'Wrong email or password'
            ], 410);
        }
        
        //hapus token sebelumnya
        $user->tokens()->delete();

        //cek type user 
        if($user->type === 0) {
          $token = $user->createToken('userToken', ['post:free'])->plainTextToken;
        }
        else {
          $token = $user->createToken('userToken', ['post:free', 'post:premium'])->plainTextToken;
        }
        
        return response()->json([
          'message' => 'Login successfuly',
          'userToken' => $token
        ], 200);
    }
    
    public function premiumUser(Request $request)
    {
      //update type user
      $user = User::find($request->id);
      $user->type = 1;
      $user->update();
      
      //hapus token user sebelumnya, ganti dengan ability post:premium 
      $user->tokens()->delete();
      
      return response()->json([
        'message' => 'Register as premium user successfuly',
        'userToken' => $user->createToken('userToken', ['post:free', 'post:premium'])->plainTextToken
      ], 200);
    }
    
    public function logout(Request $request)
    {
      //hapus semua token
      $request->user()->tokens()->delete();
      
      return response()->json('Logout successfuly', 200);
    }
}
