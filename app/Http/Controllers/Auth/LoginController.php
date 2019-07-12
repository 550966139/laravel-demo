<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use EasyWeChat\Factory;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function init_weChat()
    {
        dd(1111);
    }

    public function connect_weChat_servsr()
    {
        $config = [
            'app_id' => 'wx098ce30da094992a',
            'secret' => '636e257304a8c4fadb5d4c109dc82f58',
            'token' => 'wenshikun1992',
            'aes_key' => 'Yb04uU35BWfov2lIkhryngo5DLfg6MaFXxIugCroUO3',   
            'response_type' => 'array',
            //...
        ];
        
        $app = Factory::officialAccount($config);
        
        $app->server->push(function ($message) {
            return "您好！欢迎使用EasyWeChat!";
        });
        
        $response = $app->server->serve();
        
        // 将响应输出
        return $response;
    }
}
