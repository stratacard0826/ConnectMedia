<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    //Redirect to Root
    protected $redirectPath = '/';


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }



    /**
    *   
    *   Modifies the loginUsername to accept the generic "login" field
    *
    *   @return string
    *   @source https://laracasts.com/discuss/channels/general-discussion/log-in-with-username-or-email-in-laravel-5/replies/105088
    *
    **/
    public function loginUsername()
    {
        return 'login';
    }



    /**
    *
    *   Overrides the default getCredentials and allows people to login via email OR name
    *
    *   @return Illuminate\Foundation\Auth\AuthenticateUsers@postLogin
    *   @source https://laracasts.com/discuss/channels/general-discussion/log-in-with-username-or-email-in-laravel-5/replies/105088
    *
    **/
    protected function getCredentials($request)
    {
        $login = $request->get('login');
        $field = filter_var( $login , FILTER_VALIDATE_EMAIL ) ? 'email' : 'username' ;

        return [
            $field      => $login,
            'password'  => $request->get('password')
        ];

    }



    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }



    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
    
}
