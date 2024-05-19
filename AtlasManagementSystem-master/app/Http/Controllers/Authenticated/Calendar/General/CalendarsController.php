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

   public function delete(Request $request) {
        // dd([
        //     'user_id' => Auth::id(),
        //     'reserveDate' => $request->reserveDate,
        //     'reservePartNumber' => $request->reservePartNumber,
        // ]);
        DB::beginTransaction();
        try {
            $user_id = Auth::id();
            $reserve_date = $request->reserveDate;
            $reserve_part = $request->reservePartNumber;

            // ReserveSettingsのIDを取得
            $reserve_settings = ReserveSettings::where('setting_reserve', $reserve_date)
                                                ->where('setting_part', $reserve_part)
                                                ->first();

            if ($reserve_settings) {
                // reserve_setting_usersテーブルのIDを取得して削除
                $reserve_setting_user = DB::table('reserve_setting_users')
                                            ->where('reserve_setting_id', $reserve_settings->id)
                                            ->where('user_id', $user_id)
                                            ->first();

                if ($reserve_setting_user) {
                    DB::table('reserve_setting_users')->where('id', $reserve_setting_user->id)->delete();

                    // 予約の人数を元に戻す
                    $reserve_settings->increment('limit_users');
                }
            }

            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }





}
