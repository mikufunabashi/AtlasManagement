<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'over_name' => ['required', 'string', 'max:10'],
            'under_name' => ['required', 'string', 'max:10'],
            'over_name_kana' => ['required', 'string', 'max:30', 'regex:/^[ァ-ヶー]+$/u'],
            'under_name_kana' => ['required', 'string', 'max:30', 'regex:/^[ァ-ヶー]+$/u'],
            'mail_address' => ['required', 'email', 'max:100', 'unique:users'],
            'sex' => ['required', 'in:1,2,3'],
            // 🌟誕生日を年月日を全部くっつける
            'date_of_birth' => ['required', 'date', 'after_or_equal:2000-01-01', 'before_or_equal:today'],
            // ここで月に応じた適切な日付か検証する必要があります
            'role' => ['required', 'in:1,2,3,4'],
            'password' => ['required', 'string', 'confirmed', 'between:8,30'],
        ];
    }
}
