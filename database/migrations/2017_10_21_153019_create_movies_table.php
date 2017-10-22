<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id')->comment('电影自增主键');
            $table->string('name',50)->comment('电影名称');
            $table->string('thumb_url',100)->comment('电影缩略图地址');
            $table->string('movie_url',100)->comment('电影地址');
            $table->string('actor',50)->comment('电影主演');
            $table->string('view',50)->comment('电影点击量');
            $table->unsignedSmallInteger('status')->default(1)->comment('电影状态 1:上线 2:下架');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
