@extends('layouts.sidebar')

@section('content')
<div class="search_content w-100 d-flex">
  <div class="reserve_users_area">
    @foreach($users as $user)
    <div class="border one_person">
        <div class="one_person_1">
          <span>ID : </span><span>{{ $user->id }}</span>
        </div>
        <div><span>名前 : </span>
          <a href="{{ route('user.profile', ['id' => $user->id]) }}">
            <span>{{ $user->over_name }}</span>
            <span>{{ $user->under_name }}</span>
          </a>
        </div>
        <div class="one_person_1">
          <span>カナ : </span>
          <span>({{ $user->over_name_kana }}</span>
          <span>{{ $user->under_name_kana }})</span>
        </div>
        <div class="one_person_1">
          @if($user->sex == 1)
          <span>性別 : </span><span>男</span>
          @elseif($user->sex == 2)
          <span>性別 : </span><span>女</span>
          @else
          <span>性別 : </span><span>その他</span>
          @endif
        </div>
        <div class="one_person_1">
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
  <div class="w-25">
    <form action="{{ route('user.show') }}" method="get" id="userSearchRequest">
        <div class="search_area">
          <label>検索</label>
            <div>
                <input type="text" class="free_word" name="keyword" placeholder="キーワードを検索" form="userSearchRequest">
            </div>
            <div class="category-container">
                <label>カテゴリ</label>
                <select name="category" class="category-select">
                    <option value="name">名前</option>
                    <option value="id">社員ID</option>
                </select>
            </div>
            <div class="category-container">
                <label>並び替え</label>
                <select name="updown" class="category-select">
                    <option value="ASC">昇順</option>
                    <option value="DESC">降順</option>
                </select>
            </div>
            <div class="user_search_conditions">
                <p class="m-0 search_conditions" onclick="toggleAccordion()">
                  <span>検索条件の追加<span class="arrow"></span></span>
                </p>
                <div class="search_conditions_inner">
                    <div class="search_sex">
                        <label>性別</label>
                        <div class="search_sex1">
                          <span>男</span><input type="radio" name="sex" value="1">
                          <span>女</span><input type="radio" name="sex" value="2">
                          <span>その他</span><input type="radio" name="sex" value="3">
                        </div>
                    </div>
                    <div class="category-container search_sex">
                        <label>権限</label>
                        <select name="role" class="category-select">
                            <option selected disabled>----</option>
                            <option value="1">教師(国語)</option>
                            <option value="2">教師(数学)</option>
                            <option value="3">教師(英語)</option>
                            <option value="4">生徒</option>
                        </select>
                    </div>
                    <div class="selected_engineer search_sex">
                        <label>選択科目</label>
                        <div class="checkbox-item">
                        @if($allSubjects)
                            @foreach($allSubjects as $subject)
                            <div class="checkbox-item1">
                              <label>{{ $subject->subject }}</label>
                              <input type="checkbox" name="selected_subjects[]" value="{{ $subject->id }}">
                            </div>
                            @endforeach
                        @endif
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <input type="submit" class="search_btn" name="search_btn" value="検索">
            </div>
            <div>
              <!-- 🌟リセットボタン機能できてない -->
                <input type="reset" class="reset_btn" value="リセット">
            </div>
        </div>
    </form>
  </div>
</div>
<script>
    function toggleAccordion() {
        const accordionContent = document.querySelector('.search_conditions');
        const arrow = document.querySelector('.arrow');

        // アコーディオンの表示・非表示を切り替える
        accordionContent.classList.toggle('open');

        // クリックで回転する矢印のクラスを切り替える
        arrow.classList.toggle('open');
    }
</script>
@endsection
