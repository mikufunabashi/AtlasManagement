<?php
namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView{

  private $carbon;
  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  function render(){
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th>土</th>';
    $html[] = '<th>日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();
    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';

      $days = $week->getDays();
      foreach($days as $day){
        $startDay = $this->carbon->copy()->startOfMonth();
        $toDay = $this->carbon->copy()->endOfMonth();

        // 日付が過去の場合は、"past-day" クラスを適用する
        $isPast = Carbon::parse($day->everyDay())->lt(Carbon::today());

        // 日付のHTML要素を生成する部分
        $html[] = '<td class="calendar-td ' . ($isPast ? 'past-day' : '') . '">';

        // 日付のレンダリング
        $html[] = $day->render();

          // 日付が過去の場合
          if ($isPast) {
              // 予約があるかチェック
              if (in_array($day->everyDay(), $day->authReserveDay())) {
                  // 予約がある場合の処理
                  $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
                  if ($reservePart == 1) {
                      $reservePart = "リモ1部";
                  } else if ($reservePart == 2) {
                      $reservePart = "リモ2部";
                  } else if ($reservePart == 3) {
                      $reservePart = "リモ3部";
                  }
                  // 予約した部を表示
                  $html[] = '<span class="status">' . $reservePart . '</span>';
                  $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
              } else {
                  // 予約がない場合は受付終了を表示
                  $html[] = '<span class="status">受付終了</span>';
                  $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
              }
          } else {
              // 過去でない場合の処理,🌟次の日また確認
              if (in_array($day->everyDay(), $day->authReserveDay())) {
                  // 予約がある場合の処理
                  $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
                  if ($reservePart == 1) {
                      $reservePart = "リモ1部";
                  } else if ($reservePart == 2) {
                      $reservePart = "リモ2部";
                  } else if ($reservePart == 3) {
                      $reservePart = "リモ3部";
                  }
                  // 予約完了ボタンを表示
                  $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
                  $html[] = '<button type="button" class="btn btn-danger p-0 w-75" onclick="showModal(\'' . $day->everyDay() . '\', \'' . $reservePart . '\')" style="font-size:12px">' . $reservePart . '</button>';
              } else {
                  // 予約がない場合の処理
                  $html[] = $day->selectPart($day->everyDay());
                  // $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
              }
          }

        // 日付の表示
        $html[] = $day->getDate();
        $html[] = '</td>';
      }


      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">'.csrf_field().'</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'.csrf_field().'</form>';

    return implode('', $html);
  }

  public function getWeeks(){
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while($tmpDay->lte($lastDay)){
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
?>
