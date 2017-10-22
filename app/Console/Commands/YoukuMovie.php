<?php

namespace App\Console\Commands;

use App\Movie;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class YoukuMovie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youku:movie';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '扒取优酷的vip视频';

    const SUCCESS_CODE = 200;
    const YOUKU_MOVIE_URL = 'http://list.youku.com/category/show/c_96_u_1_pt_1_s_1_d_1_p_%d.html';
    const PAGE = 30;

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
        for ($start=1;$start<=self::PAGE;$start++) {
            //组装对应的url
            $url = sprintf(self::YOUKU_MOVIE_URL,$start);


            //请求对应的内容
            $res = $client->get($url);

            //2.如果请求成功的话获取请求的内容
            if (self::SUCCESS_CODE == $res->getStatusCode()) {
                //2.1获取到请求到的内容
                $html = $res->getBody()->getContents();

                //3.实例化html的解析器
                $crawler = new Crawler($html);

                //4.通过过滤获取自己想要的元素
                $res = $crawler->filter('.box-series .panel')->each(function (Crawler $node,$i){
                    //4.0 获取电影名称
                    $movie[$i]['name'] = $node->filter('.info-list .title')->filterXPath('//a')->extract(['title']);

                    //4.1获取影视的缩略图
                    $movie[$i]['thumb_url'] = $node->filter('.p-thumb')->filterXPath('//img')->extract(['src']);

                    //4.2获取电影的名称
                    $movie[$i]['movie_url'] = $node->filter('.info-list .title')->filterXPath('//a')->extract(['href']);

                    //4.3获取电影的主演
                    $movie[$i]['actor'] = $node->filter('.info-list .actor')->filterXPath('//a')->extract(['title']);

                    //4.4获取电影的观看数量
                    $movie[$i]['view'] = $node->filter('.info-list')->each(function (Crawler $node){
                        return $node->filterXPath('//li')->last()->text();
                    });

                    return $movie;
                });

                //5.如果请求到的数据不为空则格式化数据
                $movies = $this->formatMovieData($res);

                //6.把趴到电影信息入库
                if (is_array($movies) && count($movies) > 0) {
                    foreach ($movies as $movie){
                        Movie::create($movie);
                    }
                }

                unset($crawler,$res);
            }
        }

    }

    /**
     * 格式化电影数据
     * @param array $data
     * @return array
     */
    private function formatMovieData(array $data){

        $movie = [];
        foreach ( $data[0][0]['name'] as $key=>$name) {
            $tmp['name'] = $name;
            $tmp['thumb_url'] = $data[0][0]['thumb_url'][$key];
            $tmp['movie_url'] = $data[0][0]['movie_url'][$key];
            $tmp['actor'] = $data[0][0]['actor'][$key];
            $tmp['view'] = $data[0][0]['view'][$key];
            $movie[] = $tmp;
        }

        return $movie;
    }
}
