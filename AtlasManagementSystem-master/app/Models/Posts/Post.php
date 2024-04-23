<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    public function user(){
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments(){
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    public function subCategories(){
        // リレーションの定義
    }

    // コメント数
    public function commentCounts($post_id){
        return PostComment::where('post_id', $post_id)->count();
    }

    public function likes()
    {
        return $this->belongsToMany('App\Models\Users\User', 'likes', 'like_post_id', 'like_user_id');
    }


    // 🌟likesの中間テーブルが既に存在していたから、リレーションは一つでも問題ない？
    // public function users()
    // {
    //     return $this->belongsToMany('App\Models\Users\User', 'likes', 'like_post_id', 'like_user_id');
    // }
}
