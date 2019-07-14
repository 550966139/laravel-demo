<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use EasyWeChat\Factory;
use Illuminate\Http\Request;

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

    public function git_pull(Request $request)
    {
        //git webhook 自动部署脚本
        //项目存放物理路径
        $path = "/data/wwwroot/laravel-demo";
        $requestBody = file_get_contents("php://input");
        app('log')->info($requestBody.'++++++++++');
        if (empty($requestBody)) {
            return 'send fail';
        }
        $content = json_decode($requestBody, true);
        //若是主分支且提交数大于0
        if ($content['ref']=='refs/heads/master' && $content['commits']>0) {
            $res = shell_exec("cd {$path} && git pull origin master 2>&1");//以www用户运行
            $res_log = '-------------------------'.PHP_EOL;
            $res_log .= $content['user_name'] . ' 在' . date('Y-m-d H:i:s') . '向' . $content['repository']['name'] . '项目的' . $content['ref'] . '分支push了' . $content['commits'][0]['message'] . PHP_EOL;
            $res_log .= $res.PHP_EOL;
            file_put_contents("git-webhook.txt", $res_log, FILE_APPEND);//追加写入
        }
        echo '很棒:'.date('y-m-d H:i:s');
    }

    public function action_sehll(Request $request)
    {
        $cmd = "sudo mkdir testdir &&  echo 'success'";
        $shellExec= shell_exec($cmd);
	var_dump($shellExec);
    }
}
