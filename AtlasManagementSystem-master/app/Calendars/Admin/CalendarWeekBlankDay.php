<?php
namespace App\Calendars\Admin;

class CalendarWeekBlankDay extends CalendarWeekDay{

  function getClassName(){
    return "day-blank";
  }

  function render(){
    return '';
  }

  function everyDay(){
    return '';
  }

  function dayPartCounts($ymd, $date = null){
    return '';
}


  function dayNumberAdjustment(){
    return '';
  }
}
