@extends('layouts.sidebar')

@section('content')
<div class="vh-100 pt-5" style="background:#ECF1F6; margin-bottom:5px">
  <div class="w-75 m-auto pt-5 pb-5 border" style="border-radius:5px; background:#FFF;">
    <div class="w-75 m-auto " style="border-radius:5px;">
      <p class="text-center">{{ $calendar->getTitle() }}</p>
      <p>{!! $calendar->render() !!}</p>
    </div>
  </div>
</div>
@endsection
