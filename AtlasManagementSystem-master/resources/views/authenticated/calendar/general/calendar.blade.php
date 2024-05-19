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
                <p>予約パート: <span id="reservePart"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                <form id="cancelForm" action="{{ route('deleteParts') }}" method="POST">
                    @csrf
                    <input type="hidden" name="reserveDate" id="formReserveDate">
                    <input type="hidden" name="reservePartNumber" id="formReservePart">
                    <button type="submit" class="btn btn-danger" id="cancelButton">キャンセル</button>
                </form>

            </div>
        </div>
    </div>
  </div>
</div>
<script>
  // モーダルを表示する関数
  function showModal(reserveDate, reservePart, part) {
     console.log('reserveDate:', reserveDate); // デバッグ用
    console.log('reservePart:', reservePart); // デバッグ用

    // 予約パートを数値に変換
    let reservePartNumber;
    if (reservePart === "リモ1部") {
        reservePartNumber = 1;
    } else if (reservePart === "リモ2部") {
        reservePartNumber = 2;
    } else if (reservePart === "リモ3部") {
        reservePartNumber = 3;
    }
      // モーダルのタイトルを設定
      document.getElementById('modalTitle').innerText = '予約情報';

      // 予約日と予約パートを表示する要素を更新
      document.getElementById('reserveDate').innerText = reserveDate;
      document.getElementById('reservePart').innerText = reservePart;

      // フォームのhiddenフィールドにデータを設定
      document.getElementById('formReserveDate').value = reserveDate;
      document.getElementById('formReservePart').value = reservePartNumber;

      // モーダルを表示
      $('#exampleModal').modal('show');
  }

</script>
@endsection
