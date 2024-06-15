@extends('layouts.sidebar')

@section('content')
    <span class="top-title">{{ $user->over_name }}</span><span class="top-title">{{ $user->under_name }}さんのプロフィール</span>
    <div class="user_status p-3 top-wight border">
      <p>名前 : <span>{{ $user->over_name }}</span><span class="ml-1">{{ $user->under_name }}</span></p>
      <p>カナ : <span>{{ $user->over_name_kana }}</span><span class="ml-1">{{ $user->under_name_kana }}</span></p>
      <p>性別 : @if($user->sex == 1)<span>男</span>@else<span>女</span>@endif</p>
      <p>生年月日 : <span>{{ $user->birth_day }}</span></p>
      @if($user->role == 4)
        <div>選択科目 :
          @foreach($user->subjects as $subject)
          <span>{{ $subject->subject }}</span>
          @endforeach
        </div>
        <div>
          @can('admin')
          <div class="subject_edit" onclick="toggleAccordion()">
            <span class="subject_edit_btn">選択科目の登録<span class="arrow"></span></span>
          </div>
          <div>
            <form class="subject_inner" action="{{ route('user.edit') }}" method="post" style="display: none;">
              @foreach($subject_lists as $subject_list)
              <div class="subject_item">
                <label>{{ $subject_list->subject }}</label>
                <input type="checkbox" name="subjects[]" value="{{ $subject_list->id }}">
              </div>
              @endforeach
              <input type="submit" value="編集" class="btn btn-primary">
              <input type="hidden" name="user_id" value="{{ $user->id }}">
              {{ csrf_field() }}
            </form>
          </div>
          @endcan
        </div>
      @endif
    </div>
    <script>
    function toggleAccordion() {
        const accordionContent = document.querySelector('.subject_edit');
        const arrow = document.querySelector('.arrow');

        // アコーディオンの表示・非表示を切り替える
        accordionContent.classList.toggle('open');

        // クリックで回転する矢印のクラスを切り替える
        arrow.classList.toggle('open');
    }
    </script>

@endsection
