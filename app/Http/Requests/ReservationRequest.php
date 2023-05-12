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
      'num_of_users' => ['required', 'numeric', 'min:1'],
      'start_at' => ['required', 'date'],
      'time' => ['required'],
      'course_menu_id' => ['nullable', 'exists:course_menus,id'],
    ];
  }

  public function messages()
  {
    return [
      'start_at.required' => '日付は必須です。',
      'time.required' => '時間は必須です。',
      'num_of_users.required' => '人数は必須です。',
      'course_menu_id.exists' => '選択されたコースメニューが無効です。',
    ];
  }
}
