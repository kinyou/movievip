<?php

namespace App\Http\Controllers;

use App\Movie;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    const GOU_DI_DIAO_URL = 'http://goudidiao.com?url=http:';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movies = Movie::where(['status'=>1])->paginate(12);
        return view('home',['page'=>$movies,'movies'=>array_chunk($movies->items(),4),'vipUrl'=>self::GOU_DI_DIAO_URL]);
    }
}
