<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\UserServices;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
   /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function create_user(Request $request){

            $user_details = $request->input('user_details');

            if(empty($user_details['user_name'] || empty($user_details['user_type']) || empty($user_details['org_id']) || empty($user_details['password']) || $user_details['first_name'])) return response()->json(['success' => false]);

                $user_details['password'] = Hash::make($user_details['password']);
                $id = UserServices::insert_user($user_details);

                if(!empty($id)){
                    return response()->json([
                        'success' => true,
                        'user_id' => $id,
                        'message' => 'User created successfully',
                    ]);
                } else return response()->json(['success' => false]);
               
            
    }

    public function create_admin_user(Request $request){

        $user_details = $request->input('user_details');

        if(empty($user_details['user_name'] || empty($user_details['user_type']) || empty($user_details['org_id']) || empty($user_details['password']) || $user_details['first_name'])) return response()->json(['success' => false]);

            $user_details['password'] = Hash::make($user_details['password']);
            $id = UserServices::insert_user($user_details);

            if(!empty($id)){
                return response()->json([
                    'success' => true,
                    'user_id' => $id,
                    'message' => 'User created successfully',
                ]);
            } else return response()->json(['success' => false]);
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function login_user(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'user_name' => 'required',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['user_name', 'password']))){
                return response()->json([
                    'success' => false,
                    'message' => 'User_name & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('user_name', $request->user_name)->first();
         
            return response()->json([
                'success' => true,
                'message' => 'User Logged In Successfully',
                'user_id' => $user['id'],
                'org_id' => $user['org_id'],
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }

    }

    public function get_users(Request $request){
        $org_id = $request->input('org_id');

        if(empty($org_id)) return response()->json(['success' => false]);

        $list = UserServices::get_users_list((int)$org_id);

        if(!empty($list)){
            return response()->json(['success' => true, 'user_list' => $list]);
        } else return response()->json(['success' => false]);
    }

    public function update_user(Request $request){
        $user_details = $request->input('user_details');

        if(empty($user_details['id'])) return response()->json(['success' => false]);

        if(!empty($user_details['password'])) $user_details['password'] = Hash::make($user_details['password']);
        else unset($user_details['password']);

        UserServices::update_user_details_by_id($user_details);

        return response()->json(['success' => true]);
        
    }
}
