

@extends('layouts.sidebar')

@section('content')
<div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
  <div class="h-75 custom-table2">
    <p><span>{{ $formattedDate }}</span><span class="ml-3">{{ $part }}部</span></p>
    <div class="border custom-table1">
      <table class="custom-table">
        <thead>
          <tr class="text-center header-row">
            <th class="w-25">ID</th>
            <th class="w-25">名前</th>
            <th class="w-25">場所</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
            <tr class="text-center data-row">
              <td class="w-25">{{ $user->id }}</td>
              <td class="w-25">{{ $user->name }}</td>
              <td class="w-25">リモート</td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center">予約がありません</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
