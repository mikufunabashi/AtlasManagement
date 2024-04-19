<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

use App\Models\Users\User;

class Subjects extends Model
{
    const UPDATED_AT = null;


    protected $fillable = [
        'subject'
    ];

    public function users(){
        return $this->belongsToMany(Users::class, 'subject_user', 'subject_id', 'user_id');// リレーションの定義
        // Subjects::class→'App\Models\Users\Users'でも可
    }
}
