@extends('layouts.sidebar')

@section('content')
<div class="vh-100 pt-5" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
    <div class="w-75 m-auto border" style="border-radius:5px;">

      <p class="text-center">{{ $calendar->getTitle() }}</p>
        <div class="">
         {!! $calendar->render() !!}
        </div>

    </div>
    <div class="text-right w-75 m-auto">
      <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
    </div>
  </div>
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
            </div>
            <div class="modal-body">
                <!-- 予約情報 -->
                <p>予約日: <span id="reserveDate"></span></p>
                <p>予約時間: <span id="reserveTime"></span></p>
            </div>
            <div class="modal-footer">
              <!-- 🌟予約のIDの取り方が話kらない、時間と部数は別のテーブルで消したいIDはreserve_setting_usersだからどうするんだ？ -->
                <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                <button type="button" class="btn btn-danger" id="cancelButton">キャンセル</button>
            </div>
        </div>
    </div>
  </div>
</div>
<script>
  // モーダルを表示する関数
  function showModal(reserveDate, reserveTime, id) {
    // モーダルのタイトルを設定
    document.getElementById('modalTitle').innerText = '予約情報';

    // 予約日と予約時間を表示する要素を更新
    document.getElementById('reserveDate').innerText = reserveDate;
    document.getElementById('reserveTime').innerText = reserveTime;
    reservationId = id;
    document.getElementById('cancelButton').setAttribute('onclick', `cancelReservation(${id})`);
    // モーダルを表示
    $('#exampleModal').modal('show');
  }

  // 予約キャンセル関数
  function cancelReservation(id) {
    $.ajax({
      url: '{{ url('/delete/calendar') }}/' + id,
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
        alert('予約がキャンセルされました。');
        location.reload(); // ページをリロードして更新
      },
      error: function(error) {
        console.error('Error:', error);
        alert('予約のキャンセルに失敗しました。');
      }
    });
  }

</script>
@endsection
