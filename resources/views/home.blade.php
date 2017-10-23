@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @foreach($movies as $movie)
                    <div class="row">
                        @foreach($movie as $value)
                        <div class="col-sm-3 col-md-3">
                            <div class="thumbnail">
                                <a href="{{$vipUrl}}{{$value['movie_url']}}" target="_blank">
                                    <img src="{{$value['thumb_url']}}" alt="{{$value['name']}}">
                                </a>

                                <div class="caption">
                                    <h3>{{$value['name']}}</h3>
                                    <p><a href="#" class="btn btn-primary" role="button">{{$value['actor']}}</a> <a href="#" class="btn btn-default" role="button">{{$value['view']}}</a></p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach

                    {{ $page->links('vendor.pagination.default') }}

                </div>
                <div class="panel-footer">Panel footer</div>
            </div>
        </div>
    </div>
</div>
@endsection
