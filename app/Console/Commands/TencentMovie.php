<?php

namespace App\Console\Commands;

use App\Movie;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class TencentMovie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tencent:movie';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '扒取腾讯VIP视频';

    const SUCCESS_CODE = 200;

    const PAGE = 17;

    const TENCENT_URL = 'http://list.video.qq.com/fcgi-bin/list_common_cgi?otype=json&novalue=1&platform=1&version=10000&intfname=web_vip_movie_new&tid=687&appkey=c8094537f5337021&appid=200010596&type=1&sourcetype=1&itype=-1&iyear=-1&iarea=-1&iawards=0&sort=17&pagesize=30&offset=%d&callback=jQuery1910009415596887985433_%d258&_=%d269';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Client $client)
    {

        for ($start=0;$start<=self::PAGE;$start++){
            $time = time();
            $url = sprintf(self::TENCENT_URL,$start*30,$time,$time);
            $res = $client->get($url);

            if(self::SUCCESS_CODE == $res->getStatusCode()){
                $content = $res->getBody()->getContents();
                preg_match_all('/{[\s\S]*}/',$content,$matches);

                if (is_array($matches) && count($matches[0][0]) > 0) {
                    $movieData = json_decode($matches[0][0],true);
                    if (!isset($movieData['jsonvalue']['results']) || !is_array($movieData['jsonvalue']['results'])) break;
                    foreach ($movieData['jsonvalue']['results'] as $key=>$value) {
                        //电影名称
                        $movie['name'] = $value['fields']['title'];
                        //电影缩略图
                        $movie['thumb_url'] = $value['fields']['vertical_pic_url'];
                        //电影地址
                        $movie['movie_url'] = sprintf('https://v.qq.com/x/cover/%s.html',$value['fields']['cover_id']);
                        //电影主演
                        $movie['actor'] = $value['fields']['second_title'];
                        //电影评分
                        $movie['view'] = $value['fields']['score']['score'];
                        //电影来源
                        $movie['source'] = 2;

                        Movie::create($movie);
                    }
                }

                echo $url . PHP_EOL.PHP_EOL;
                echo 'SUCCESS!!!!' . PHP_EOL.PHP_EOL;
                //sleep(5);
            }
        }

    }
}
