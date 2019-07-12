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
    protected $app;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = [
            'app_id' => config('easywechat.app_id'),
            'secret' => config('easywechat.secret'),
            'token' => config('easywechat.token'),
            'aes_key' => config('easywechat.aes_key'),
            'response_type' => config('easywechat.response_type'),
        ];
        $this->middleware('guest')->except('logout');
    }

    public function init_weChat()
    {
        dd($this->app);
    }

    public function connect_weChat_servsr()
    {
        $app = Factory::officialAccount($this->config);
        $app->server->push(function ($message) {
            return "您好！欢迎使用EasyWeChat!";
        });
        
        $response = $app->server->serve();
        
        // 将响应输出
        return $response;
    }

    public function get_menu()
    {
        $app = Factory::officialAccount($this->config);
        dd($app->broadcasting->sendText("大家好！欢迎使用 EasyWeChat。"));
    }
}
