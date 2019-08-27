<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Tools\Wechat;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    public $redis;
    public $app ;

    public function __construct(Application $app, Dispatcher $events)
    {
        parent::__construct($app, $events);
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1','6379');
        $this->app = app('wechat.official_account');
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */




    protected function schedule(Schedule $schedule)
    {

        $schedule->call(function () {
            \Log::Info('22222222222222');
            //业务逻辑
            $price_info = file_get_contents('http://shopdemo.18022480300.com/price/api');
            $price_arr = json_decode($price_info,1);
            foreach($price_arr['result'] as $v){
                if($this->redis->exists($v['city'].'信息')){
                    $redis_info = json_decode($this->redis->get($v['city'].'信息'),1);
                    foreach ($v as $k=>$vv){
                        if($vv != $redis_info[$k]){
                            //推送模板消息
                            $openid_info = $this->app->user->list($nextOpenId = null);
                            $openid_list = $openid_info['data'];
                            foreach ($openid_list['openid'] as $vo){
                                $this->app->template_message->send([
                                    'touser' => $vo,
                                    'template_id' => 'hy-ju5jnMvV0PWVvJ4LMlg1ky_WQ91DtOrNYRQpfoq0',
                                    'url' => 'http://shopdemo.18022480300.com',
                                    'data' => [
                                        'first' => '你好22222',
                                        'keyword1' => '你好',
                                    ],
                                ]);
                            }
                        }
                    }
                }
            }
       // })->daily();
        })->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
