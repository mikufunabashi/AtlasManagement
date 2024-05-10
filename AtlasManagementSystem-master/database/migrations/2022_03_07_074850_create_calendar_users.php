<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 予約とユーザーのつながりを表してる、こっちは枠数とは関係なくただ予約してるかどうかのテーブル？
        Schema::create('calendar_users', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('id');
            $table->integer('user_id')->index()->comment('ユーザーid');
            $table->integer('calendar_id')->index()->comment('カレンダーid');
            $table->timestamp('created_at')->nullable()->comment('登録日時');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_users');
    }
}
