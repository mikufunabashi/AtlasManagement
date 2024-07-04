<?php
namespace App\Calendars\Admin;
use Carbon\Carbon;
use App\Models\Users\User;

class CalendarView {
  private $carbon;

  function __construct($date) {
    $this->carbon = new Carbon($date);
  }

  public function getTitle() {
    return $this->carbon->format('Y年n月');
  }

  public function render() {
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="calendar-td">月</th>';
    $html[] = '<th class="calendar-td">火</th>';
    $html[] = '<th class="calendar-td">水</th>';
    $html[] = '<th class="calendar-td">木</th>';
    $html[] = '<th class="calendar-td">金</th>';
    $html[] = '<th class="saturday calendar-td">土</th>';
    $html[] = '<th class="sunday calendar-td">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody class="tbody">';

    $weeks = $this->getWeeks();

    foreach($weeks as $week) {
      $html[] = '<tr class="'.$week->getClassName().'">';
      $days = $week->getDays();
      foreach($days as $day) {
        $startDay = $this->carbon->format("Y-m-01");
        $toDay = $this->carbon->format("Y-m-d");
        $isPast = $startDay <= $day->everyDay() && $toDay >= $day->everyDay();

        // 過去の日付の場合に適用するクラス
        $dayClass = $isPast ? 'past-day' : '';

        // 土曜日または日曜日の場合のクラスを追加
        if ($day->isSaturday()) {
          $dayClass .= ' saturday';
        } elseif ($day->isSunday()) {
          $dayClass .= ' sunday';
        }

        $html[] = '<td class="calendar-td1 ' . $dayClass . '">';
        $html[] = $day->render();
        $html[] = $day->dayPartCounts($day->everyDay(), $day->everyDay());
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';

    return implode("", $html);
  }

  protected function getWeeks() {
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while($tmpDay->lte($lastDay)) {
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
