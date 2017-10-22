<?php

namespace App\Http\Controllers;

use App\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(){
        $movies = Movie::where(['status'=>1])->paginate(30);
        dump($movies);
    }
}
