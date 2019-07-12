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
    //与微信服务器建立连接
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
    //群发微信消息
    public function group_sending()
    {
        $app = Factory::officialAccount($this->config);
        dd($app->broadcasting->sendText("大家好！欢迎使用 EasyWeChat。".time()));
    }

    //获取微信菜单
    public function get_menu()
    {
        $app = Factory::officialAccount($this->config);
        $current = $app->menu->current();
        dd($current);
    }

    public function create_menu()
    {
        $app = Factory::officialAccount($this->config);
        $buttons = [
            [
                "type" => "click",
                "name" => "今日歌曲",
                "key"  => "V1001_TODAY_MUSIC"
            ],
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "搜索",
                        "url"  => "http://www.soso.com/"
                    ],
                    [
                        "type" => "view",
                        "name" => "视频",
                        "url"  => "http://v.qq.com/"
                    ],
                    [
                        "type" => "click",
                        "name" => "赞一下我们",
                        "key" => "V1001_GOOD"
                    ],
                ],
            ],
        ];
        $res=$app->menu->create($buttons);
        dd($res);
    }
}
