<?php
namespace App\Searchs;

use App\Models\Users\User;

class SearchResultFactories{

  // 改修課題：選択科目の検索機能
  // public function initializeUsers($keyword, $category, $updown, $gender, $role, $subjects){
  //   if($category == 'name'){
  //     if(is_null($subjects)){
  //       $searchResults = new SelectNames();
  //     }else{
  //       $searchResults = new SelectNameDetails();
  //     }
  //     return $searchResults->resultUsers($keyword, $category, $updown, $gender, $role, $subjects);
  //   }else if($category == 'id'){
  //     if(is_null($subjects)){
  //       $searchResults = new SelectIds();
  //     }else{
  //       $searchResults = new SelectIdDetails();
  //     }
  //     return $searchResults->resultUsers($keyword, $category, $updown, $gender, $role, $subjects);
  //   }else{
  //     $allUsers = new AllUsers();
  //   return $allUsers->resultUsers($keyword, $category, $updown, $gender, $role, $subjects);
  //   }
  // }

  public function initializeUsers($keyword, $category, $updown, $gender, $role, $subjects)
{
    // 選択された科目が一つでもある場合
    if (!empty($subjects)) {
        // 選択された科目に該当するユーザーIDを取得
        $userIds = User::whereHas('subjects', function ($query) use ($subjects) {
            $query->whereIn('subjects.id', $subjects); // 'subjects' テーブルの 'id' カラムを指定
        })->pluck('users.id')->toArray(); // 'users' テーブルの 'id' カラムを指定


        // ユーザーIDに基づいてユーザーを取得
        $users = User::whereIn('id', $userIds);

        // その他の条件があれば適用
        // 例: $users->where('name', 'like', '%'.$keyword.'%')->where('category', $category)->orderBy('created_at', $updown)->where('gender', $gender)->where('role', $role);

        // ユーザーを取得し、結果を返す
        return $users->get();
    }

    // 選択科目がない場合は通常の検索処理を実行
    if ($category == 'name') {
        if (is_null($subjects)) {
            $searchResults = new SelectNames();
        } else {
            $searchResults = new SelectNameDetails();
        }
        return $searchResults->resultUsers($keyword, $category, $updown, $gender, $role, $subjects);
    } elseif ($category == 'id') {
        if (is_null($subjects)) {
            $searchResults = new SelectIds();
        } else {
            $searchResults = new SelectIdDetails();
        }
        return $searchResults->resultUsers($keyword, $category, $updown, $gender, $role, $subjects);
    } else {
        $allUsers = new AllUsers();
        return $allUsers->resultUsers($keyword, $category, $updown, $gender, $role, $subjects);
    }
}

}
