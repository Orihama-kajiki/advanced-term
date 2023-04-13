<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date' => ['required', 'date', 'after_or_equal:tomorrow'],
            'time' => ['required', 'regex:/^\d{2}:\d{2}$/'],
            'num_of_users' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'date.required' => '日付は必須です。',
            'date.date' => '日付は日付形式で入力してください。',
            'date.after_or_equal' => '日付は翌日以降で指定してください。',
            'time.required' => '時間は必須です。',
            'time.regex' => '時間は「H:i」形式で入力してください。',
            'num_of_users.required' => '人数は必須です。',
            'num_of_users.integer' => '人数は整数で入力してください。',
        ];
    }
}
