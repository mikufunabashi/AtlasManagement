<?php
namespace App\Calendars\Admin;
use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

class CalendarSettingView {
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
      $html[] = '<div class="calendar-row '.$week->getClassName().'">';
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
        $html[] = '<div class="day2">' . $day->render() . '</div>';
        $html[] = '<div class="adjust-area">';
        if($day->everyDay()) {
          if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
            $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][1]" type="text" form="reserveSetting" value="'.$day->onePartFrame($day->everyDay()).'" disabled></p>';
            $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][2]" type="text" form="reserveSetting" value="'.$day->twoPartFrame($day->everyDay()).'" disabled></p>';
            $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][3]" type="text" form="reserveSetting" value="'.$day->threePartFrame($day->everyDay()).'" disabled></p>';
          } else {
            $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][1]" type="text" form="reserveSetting" value="'.$day->onePartFrame($day->everyDay()).'"></p>';
            $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][2]" type="text" form="reserveSetting" value="'.$day->twoPartFrame($day->everyDay()).'"></p>';
            $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][3]" type="text" form="reserveSetting" value="'.$day->threePartFrame($day->everyDay()).'"></p>';
          }
        }
        $html[] = '</div>';
        $html[] = '</div>';
      }
      $html[] = '</div>';
    }
    $html[] = '</div>';
    $html[] = '<form action="'.route('calendar.admin.update').'" method="post" id="reserveSetting">'.csrf_field().'</form>';
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
