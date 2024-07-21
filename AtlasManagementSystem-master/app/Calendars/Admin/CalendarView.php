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
    $html[] = '<div class="calendar-week">';
    $html[] = '<div class="calendar-header">月</div>';
    $html[] = '<div class="calendar-header">火</div>';
    $html[] = '<div class="calendar-header">水</div>';
    $html[] = '<div class="calendar-header">木</div>';
    $html[] = '<div class="calendar-header">金</div>';
    $html[] = '<div class="calendar-header saturday">土</div>';
    $html[] = '<div class="calendar-header sunday">日</div>';
    $html[] = '</div>';
    $html[] = '<div class="calendar-grid">';

    $weeks = $this->getWeeks();
    $currentMonth = $this->carbon->format("m");

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

        if (!$day->isCurrentMonth($currentMonth)) {
          $dayClass .= ' not-current-month';
        }

        $html[] = '<div class="calendar-cell ' . $dayClass . '">';
        $html[] = $day->renderDayWithReservations(); // 新しいメソッドを呼び出す
        $html[] = '</div>';
      }
      $html[] = '</tr>';
    }
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
?>
