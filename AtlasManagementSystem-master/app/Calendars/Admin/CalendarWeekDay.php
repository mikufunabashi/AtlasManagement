<?php
namespace App\Calendars\Admin;

use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

class CalendarWeekDay{
  protected $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  function getClassName(){
    return "day-" . strtolower($this->carbon->format("D"));
  }

  function render(){
    return '<p class="day">' . $this->carbon->format("j") . '日</p>';
  }

  function everyDay(){
    return $this->carbon->format("Y-m-d");
  }

  function dayPartCounts($ymd, $days){
    $html = [];
    $one_part_reservations = ReserveSettings::withCount('users')
                                            ->where('setting_reserve', $ymd)
                                            ->where('setting_part', '1')
                                            ->first();
    $two_part_reservations = ReserveSettings::withCount('users')
                                            ->where('setting_reserve', $ymd)
                                            ->where('setting_part', '2')
                                            ->first();
    $three_part_reservations = ReserveSettings::withCount('users')
                                              ->where('setting_reserve', $ymd)
                                              ->where('setting_part', '3')
                                              ->first();

    $one_part_count = $one_part_reservations ? $one_part_reservations->users_count : 0;
    $two_part_count = $two_part_reservations ? $two_part_reservations->users_count : 0;
    $three_part_count = $three_part_reservations ? $three_part_reservations->users_count : 0;

    $html[] = '<div class="text-left">';
    $html[] = '<div><a href="' . route('calendar.admin.detail', ['date' => $ymd, 'part' => '1']) . '" class="day_part m-0 pt-1">1部</a> ' . $one_part_count . '件</div>';
    $html[] = '<div><a href="' . route('calendar.admin.detail', ['date' => $ymd, 'part' => '2']) . '" class="day_part m-0 pt-1">2部</a> ' . $two_part_count . '件</div>';
    $html[] = '<div><a href="' . route('calendar.admin.detail', ['date' => $ymd, 'part' => '3']) . '" class="day_part m-0 pt-1">3部</a> ' . $three_part_count . '件</div>';
    $html[] = '</div>';

    return implode("", $html);
  }


  function onePartFrame($day){
    $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first();
    if($one_part_frame){
      $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first()->limit_users;
    }else{
      $one_part_frame = "20";
    }
    return $one_part_frame;
  }
  function twoPartFrame($day){
    $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first();
    if($two_part_frame){
      $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first()->limit_users;
    }else{
      $two_part_frame = "20";
    }
    return $two_part_frame;
  }
  function threePartFrame($day){
    $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first();
    if($three_part_frame){
      $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first()->limit_users;
    }else{
      $three_part_frame = "20";
    }
    return $three_part_frame;
  }

  //
  function dayNumberAdjustment(){
    $html = [];
    $html[] = '<div class="adjust-area">';
    $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="1" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="2" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="3" type="text" form="reserveSetting"></p>';
    $html[] = '</div>';
    return implode('', $html);
  }
}
