<?php

namespace App\Http\Controllers\API23MAR2023;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class PassportController extends Controller
{
	public $successStatus = 200;
	
	public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
		if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }
		$input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['first_name'] =  $user->first_name;
		return response()->json(['success'=>$success], $this->successStatus);
    }
    
}
