<?php

use Illuminate\Database\Seeder;
use App\Models\Users\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'over_name' => '小栗',
            'under_name' => '旬',
            'over_name_kana' => 'オグリ',
            'under_name_kana' => 'シュン',
            'mail_address' => '39@39',
            'sex' => 1, // 男性の場合
            'birth_day' => '1999-02-22',
            'role' => 1, // 権限の設定に応じて変更する必要があります
            'password' => Hash::make('mikumiku'), // パスワードはハッシュ化して保存する必要があります
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
