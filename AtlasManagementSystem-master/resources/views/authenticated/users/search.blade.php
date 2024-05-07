@extends('layouts.sidebar')

@section('content')
<p>ユーザー検索</p>
<div class="search_content w-100 border d-flex">
  <div class="reserve_users_area">
    @foreach($users as $user)
    <div class="border one_person">
      <div>
        <span>ID : </span><span>{{ $user->id }}</span>
      </div>
      <div><span>名前 : </span>
        <a href="{{ route('user.profile', ['id' => $user->id]) }}">
          <span>{{ $user->over_name }}</span>
          <span>{{ $user->under_name }}</span>
        </a>
      </div>
      <div>
        <span>カナ : </span>
        <span>({{ $user->over_name_kana }}</span>
        <span>{{ $user->under_name_kana }})</span>
      </div>
      <div>
        @if($user->sex == 1)
        <span>性別 : </span><span>男</span>
        @elseif($user->sex == 2)
        <span>性別 : </span><span>女</span>
        @else
        <span>性別 : </span><span>その他</span>
        @endif
      </div>
      <div>
        <span>生年月日 : </span><span>{{ $user->birth_day }}</span>
      </div>
      <div>
        @if($user->role == 1)
        <span>権限 : </span><span>教師(国語)</span>
        @elseif($user->role == 2)
        <span>権限 : </span><span>教師(数学)</span>
        @elseif($user->role == 3)
        <span>権限 : </span><span>講師(英語)</span>
        @else
        <span>権限 : </span><span>生徒</span>
        @endif
      </div>
      <div>
        @if($user->role == 4)
        <span>選択科目 :</span>
          @foreach($user->subjects as $subject)
              <span>{{ $subject->subject }}</span> <!-- ここで選択された科目を表示 -->
          @endforeach
        @endif
      </div>
    </div>
    @endforeach
  </div>
  <div class="search_area w-25 border">
    <form action="{{ route('user.show') }}" method="get" id="userSearchRequest">
        <div class="">
            <div>
                <input type="text" class="free_word" name="keyword" placeholder="キーワードを検索" form="userSearchRequest">
            </div>
            <div>
                <label>カテゴリ</label>
                <select name="category">
                    <option value="name">名前</option>
                    <option value="id">社員ID</option>
                </select>
            </div>
            <div>
                <label>並び替え</label>
                <select name="updown">
                    <option value="ASC">昇順</option>
                    <option value="DESC">降順</option>
                </select>
            </div>
            <div class="">
                <p class="m-0 search_conditions"><span>検索条件の追加</span></p>
                <div class="search_conditions_inner">
                    <div>
                        <label>性別</label>
                        <span>男</span><input type="radio" name="sex" value="1">
                        <span>女</span><input type="radio" name="sex" value="2">
                        <span>その他</span><input type="radio" name="sex" value="3">
                    </div>
                    <div>
                        <label>権限</label>
                        <select name="role" class="engineer">
                            <option selected disabled>----</option>
                            <option value="1">教師(国語)</option>
                            <option value="2">教師(数学)</option>
                            <option value="3">教師(英語)</option>
                            <option value="4">生徒</option>
                        </select>
                    </div>
                    <div class="selected_engineer">
                        <label>選択科目</label>
                        @if($allSubjects)
                            @foreach($allSubjects as $subject)
                            <div>
                                <input type="checkbox" name="selected_subjects[]" value="{{ $subject->id }}">
                                <label>{{ $subject->subject }}</label>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div>
              <!-- 🌟リセットボタン機能できてない -->
                <input type="reset" value="リセット">
            </div>
            <div>
                <input type="submit" name="search_btn" value="検索">
            </div>
        </div>
    </form>
  </div>
</div>
@endsection
