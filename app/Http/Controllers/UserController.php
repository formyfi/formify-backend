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
use App\Http\Models\{
    Users, Task
  };
  use Illuminate\Support\Facades\Mail;
  use Illuminate\Mail\Message as MailMessage;
  
class UserController extends Controller
{
   /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function create_user(Request $request){

            $user_details = $request->input('user_details');
            $org_id = $request->input('org_id');

            if(empty($user_details['user_name'] || empty($org_id) || empty($user_details['password']) || $user_details['first_name'])) return response()->json(['success' => false]);

            $user_details['password'] = Hash::make($user_details['password']);
            $user_details['org_id'] = $org_id;
            $id = UserServices::insert_user($user_details);
            $list = UserServices::get_users_list((int)$org_id);
    
            if(!empty($list)){
                return response()->json(['success' => true, 'user_list' => $list]);
            } else return response()->json(['success' => true]);

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
            $org_detail = Users::get_org_details((int)$user-> org_id);
            $stations = Users::get_stations_by_user_id((int)$user-> id, (int)$user-> org_id, $user->super_user);
            $areas = Users::get_areas_by_user_id((int)$user->id);
            if(!empty($stations)) $stations = array_values($stations);
            return response()->json([
                'success' => true,
                'message' => 'User Logged In Successfully',
                'user_id' => $user['id'],
                'super_user_ind' => $user['super_user'],
                'org_id' => $user['org_id'],
                'user_type_id' => $user['user_type'],
                'user_first_name'=>$user['first_name'],
                'user_last_name'=>$user['last_name'],
                'user_details' => $user,
                'stations' => $stations,
                'areas' => $areas,
                'org_name' =>  $org_detail?$org_detail->org_name:'-',
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
        $org_id = $request->input('org_id');

        if(empty($user_details['id'])) return response()->json(['success' => false]);

        if(!empty($user_details['password'])) $user_details['password'] = Hash::make($user_details['password']);
        else unset($user_details['password']);

        UserServices::update_user_details_by_id($user_details);

        $list = UserServices::get_users_list((int)$org_id);
        
        if(!empty($list)){
            return response()->json(['success' => true, 'user_list' => $list]);
        } else   return response()->json(['success' => true]);

      
        
    }


    public function update_password(Request $request){
        $user_id = $request->input('user_id');
        $old_password = $request->input('old_password');
        $new_password = $request->input('new_password');

        $user = User::where('user_name', $user_id)->first();
        // Verify if the old password matches the user's current password
        if (!Hash::check($old_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Incorrect old password']);
        }

        // Update the user's password with the new password
        $user->password = Hash::make($new_password);
        Users::upsert_user(['password' => $user->password], ['user_name' => $user_id]);

        return response()->json(['success' => true, 'message' => 'Password updated successfully']);
    }


public function sendEmail(Request $request)
{
    $body = $request->input('body');
    $subject = 'New Inquiry';

    $message_body = '<html>
    <head>
    </head>
    <body>
      <p><strong>The new inquiry made by:</strong> ' . $body['name'] . '</p>
      <p><strong>Company Name:</strong> ' . $body['company_name'] . '</p>
      <p><strong>Message:</strong> ' . $body['message'] . '</p>
      <p><strong>Phone:</strong> ' . $body['phone'] . '</p>
      <p><strong>Email:</strong> ' . $body['email'] . '</p>
    </body>
    </html>';

    Mail::send([], [], function (MailMessage $message) use ($subject, $message_body) {
        $message->to('info@digicheck.ca')
            ->subject($subject)
            ->from('akshay_patel26@hotmail.com')
            ->html($message_body);
    });

    return response()->json(['message' => 'Email sent successfully']);
}

    


    public function social_login(Request $request){
        $user_name = $request->input('user_name');
        $client_id = $request->input('client_id');

        if(empty($user_name) || empty($client_id)) return response()->json(['success' => false]);

        $user = User::where('user_name', $request->user_name)->first();

        if(!empty($user)){
            Users::upsert_user(['client_id' => $client_id], ['user_name' => $user_name]);
            $org_detail = Users::get_org_details((int)$user-> org_id);
            $stations = Users::get_stations_by_user_id((int)$user-> id, (int)$user-> org_id, $user->super_user);
            $areas = Users::get_areas_by_user_id((int)$user->id);
            if(!empty($stations)) $stations = array_values($stations);
                return response()->json([
                'success' => true,
                'message' => 'User Logged In Successfully',
                'user_id' => $user['id'],
                'super_user_ind' => $user['super_user'],
                'org_id' => $user['org_id'],
                'user_type_id' => $user['user_type'],
                'user_first_name'=>$user['first_name'],
                'user_last_name'=>$user['last_name'],
                'user_details' => $user,
                'stations' => $stations,
                'areas' => $areas,
                'org_name' =>  $org_detail?$org_detail->org_name:'-',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } else{
            return response()->json(['success' => false]);
        }
        

      
        
    }

    public function delete_user(Request $request){
        $id = $request->input('id');
        $org_id = $request->input('org_id');

        if(empty($id)) return response()->json(['success' => false]);

        UserServices::delete_user(['id' => $id]);
        
        $list = UserServices::get_users_list((int)$org_id);
        Task::unlock_form_by_user_id($id);
        if(!empty($list)){
            return response()->json(['success' => true, 'user_list' => $list]);
        } else return response()->json(['success' => true]);
        
    }

    public static function logout(){

        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        Task::unlock_form_by_user_id($user_id);
        $user->tokens()->delete();
        
        return response()->json(['success' => true]);
    }
}
