@extends('layouts.sidebar')
@section('content')
<div style="background:#ECF1F6;">
  <div class="calender1">
    <div class="border calender2">
      <div class="calender3">
        <p class="text-center">{{ $calendar->getTitle() }}</p>
        <div class="calendar">
          {!! $calendar->render() !!}
        </div>
      </div>
      <div class="text-right w-75 m-auto">
        <input type="submit" class="btn btn-primary" value="登録" form="reserveSetting" onclick="return confirm('登録してよろしいですか？')">
      </div>
    </div>
  </div>
</div>
@endsection
