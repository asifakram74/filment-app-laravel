<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\DeleteUser;
use App\Mail\ForgetPassword;
use App\Mail\NewUserEmail;
use App\Mail\VerifyEmail;
use App\Mail\WelcomeMail;
//use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401); // 401 Unauthorized
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            // 'token_type' => 'Bearer',
        ], 200); // 200 OK
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]);

            $data = $request->all();
            // dd("register: ", $data);
            $user = User::create($data);

            // Email for user
            $toEmail = $request->input('email'); 
            $name = $request->input('name');
            $email = $request->input('email');
            $message = "Congratulations! You've successfully created an account on Sidanah Travel. We are thrilled to have you as a part of our community.";
            $subject = "Welcome to Sidanah Travel";
            Mail::to($toEmail)->send(new WelcomeMail($message, $subject, $name));

                   // Email for Admin
            $toEmail = 'asifakram74@gmail.com'; 
            $name = $request->input('name');
            $message = "Dear Admin! We are excited to inform you that a new user has just registered on Sidanah Travel. ";
            $subject = "New User Registration Notification";
            Mail::to($toEmail)->send(new NewUserEmail($message, $subject, $name, $email));

            $userInfo = [
                'userInfo' => $user,
                'status' => 200,
                'message' => 'User created successfully'
            ];

            return response()->json($userInfo, 200); // 201 Created

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request'
            ], 500); // 500 Internal Server Error
        }
    }
    // Admin See All Users
    public function users(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $orderBy = $request->input('order_by', 'asc');
        $columnName = $request->input('column_name', 'id');

        $users = User::orderBy($columnName, $orderBy)->paginate($limit, '*', 'page', $page);

        return ['users' => $users];
    }

    // For Delete User
    public function deleteUsers($id)
    {

        $currentUser =  auth()->user(); 
           $request_user = User::find($id);
        if ($currentUser->id === $request_user->id) {
        $request_user->delete();
        // email code starts from here
        $toEmail = $request_user['email'];
        $name = $request_user['name'];
        $subject = "Account Deletion Confirmation - Sidanah Travel";
        Mail::to($toEmail)->send(new DeleteUser($subject, $name));
        return  [
            'userInfo' => $request_user,
            'status' => 200,
            'message' => 'User Deleted successfully'
        ];
    }
    else{
        return  [
                      'status' => 500,
            'message' => 'you can not deleted'
        ];
    }
    }
    // For SHOW USER Data
    public function userData($id)
    {
        // $user = auth()->user();
        $user = USer::find($id);
        if ($user) {
            return $user;
        }
    }


    // For SHOW USER Data
    public function profileData()
    {
        $user =  auth()->user();
        // dd($user);
        if ($user) {
            return $user;
        }
    }

// Change Password
// Change Password
public function changePassword(Request $request)
{
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required',
    ]);

    $user = auth()->user();
    // Validate the request
    
    if (Hash::check($request->old_password, $user->password)) {
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        return response()->json([
            'message' => 'Password updated successfully!'
        ], 200); // 200 OK status code
    }
    
    return response()->json([
        'message' => 'Your old password is incorrect.'
    ], 422); // 422 Unprocessable Entity status code
}
    // Udpate User Profile
    public function updateUser(Request $request, $id)
    {

        $user = USer::find($id);
        // Update the user profile
        $currentStatus = $user->status;
        // Update the user profile
        $data = $request->all();
        $user->update($data);
        // Check if the status changed from 'pending' to 'active'
        if ($currentStatus == 'Pending' && $user->status == 'Active') {
            // Send verification email
            // email code starts from here
            $toEmail = $user['email'];
            $name = $user['name'];
            $subject = "Account Activated - Sidanah Travel";

            Mail::to($toEmail)->send(new VerifyEmail($subject, $name));
        }
        return  [
            'userInfo' => $user,
            'status' => 200,
            'message' => 'Profile updated successfully'
        ];
    }
    // Udpate Profile
    public function updateProfile(Request $request)
    {
        $user =  auth()->user();


        $data = $request->all();

        $user->update($data);


        return  [
            'userInfo' => $user,
            'status' => 200,
            'message' => 'Profile updated successfully'
        ];
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout()
    {

        Session::flush();
        // Auth::logout();
        return  [
            'status' => 200,
            'message' => 'User Logout successfully'
        ];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        if (Auth::check()) {
            return view('dashboard');
        }

        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {

        return User::create($data);
    }




    // controller methods
    public function verifyEmail(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        // if (isset($user->otp_expiry) && ($user->otp_expiry > date('Y-m-d H:i:s')) && $user->otp_count >= 3) {
        //     return  [
        //         'status' => 500,
        //         'message' => 'Token is already sent. Please wait for 1 hour.'
        //     ];
        // }

        if (is_null($user)) {
            return  [
                'status' => 500,
                'message' => 'User not found'
            ];
        }
        $user->otp          = str_pad(random_int(0, 9999), 6, '0', STR_PAD_LEFT);
        $user->otp_expiry   = date("Y-m-d H:i:s", strtotime('+1 hours'));
        $user->otp_count +=  1;
        $user->save();

     
        //Email Request data create
        $toEmail = $user['email'];
        $name = $user['name'];
        $otp= $user->otp;
        $subject = "Your Password Reset OTP - Sidanah Travel";

 
        Mail::to($toEmail)->send(new ForgetPassword($subject, $name, $otp));

        return  [
            'status' => 200,
            'message' => 'Password Reset Email sent successfully'
        ];
    }

    public function verifyOtp(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|email',
            'otp' => 'required', // added otp validation
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if (is_null($user)) {
            return response()->json([
                'status' => 500,
                'message' => 'User not found'
            ]);
        }

        if (now() > $user->otp_expiry) {
            return response()->json([
                'status' => 500,
                'message' => 'OTP is expired'
            ]);
        }

        if ($user->otp != $request->input('otp')) {
            return response()->json([
                'status' => 500,
                'message' => 'Incorrect OTP'
            ]);
        }

        // assuming you want to return a success response
        return response()->json([
            'status' => 200,
            'message' => 'OTP verified successfully'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|email',
            'otp' => 'required', // added otp validation
            'password' => 'required|min:8',
        ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Validation Error',
        //         'errors' => $validator->errors(),
        //     ], 400);
        // }

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if (is_null($user)) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
            ], 404);
        }

        if (now() > $user->otp_expiry) {
            return response()->json([
                'status' => 400,
                'message' => 'OTP is expired',
            ], 400);
        }

        if ($user->otp != $request->input('otp')) {
            return response()->json([
                'status' => 400,
                'message' => 'Incorrect OTP',
            ], 400);
        }

        $user->password = bcrypt($request->input('password'));
        $user->otp_expiry = NULL;
        $user->otp = NULL;
        $user->otp_count = 0;
        $user->save();

        //Email Request data create
        // $email_data = [
        //     "name"          =>  $user->name,
        //     "email"         =>  $email,
        //     "subject"       =>  "Password Updated",
        //     "body"          =>  "<span>This is to inform you that your Chex account password has been updated successfully. Below are your new login credentials: <br /><br /> <b> Username: </b>" . $user->name . " <br > <b>Password:</b> " . $request->input('password') . "<br /><br />Please ensure to keep this information confidential. If you did not request this change or have any concerns, please contact our support team immediately.
        // Thank you for using Chex. We appreciate your commitment to keeping your account secure. </span>",
        //     "button_url"    =>  "",
        //     "button_text"   =>  "",
        // ];

        // //send user email
        // dispatch(new SendEmailJob($email_data));

        return response()->json([
            'status' => 200,
            'message' => 'Password Updated successfully'
        ]);
    }
}
