<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Carbon\Carbon;
use Auth;
use DB;

class CalendarsController extends Controller
{
    // public function show(){
    //     $calendar = new CalendarView(time());
    //     $weeks = $calendar->getWeeks();
    //     $reservations = ReserveSettings::with('users')->whereHas('users', function($query) {
    //         $query->where('user_id', Auth::id());
    //     })->get();
    //     return view('authenticated.calendar.general.calendar', compact('calendar', 'weeks'));
    // }
    public function show($user_id){
        $calendar = new CalendarView(time());
        $weeks = $calendar->getWeeks();
        $reservations = ReserveSettings::with(['users' => function($query) {
            $query->select('reserve_setting_users.id'); // usersテーブルのidを指定
        }])->get();
        // dd($reservations);
        return view('authenticated.calendar.general.calendar', compact('calendar', 'weeks', 'reservations'));
    }


    public function reserve(Request $request){
        DB::beginTransaction();
        try{
            $getPart = $request->getPart;
            $getDate = $request->getData;
            // dd($getPart,$getDate);
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    // キャンセル処理のメソッドを追加
    // public function delete($id)
    // {
    //     $reservation = ReserveSettings::find($id);
    //     if ($reservation) {
    //         // $reservation->users()->detach(Auth::id()); // ユーザーと予約の関連を削除
    //         $reservation->delete(); // 予約を削除
    //     }
    //     return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    // }

    public function delete($id) {
        DB::table('reserve_setting_users')->where('id', $id)->delete();
        dd($id);
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }




}
