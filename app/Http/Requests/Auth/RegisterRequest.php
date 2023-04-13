<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'unique:users', 'string', 'max:191'],
            'password' => ['required', 'string', 'min:8', 'max:191'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ユーザーネームは必須です',
            'name.string' => 'ユーザーネームは文字列で指定してください',
            'name.max' => 'ユーザーネームは:max文字以内で指定してください',
            'email.required' => 'メールアドレスは必須です',
            'email.email' => '正しいメールアドレスの形式で指定してください',
            'email.unique' => 'このメールアドレスは既に使用されています',
            'email.string' => 'メールアドレスは文字列で指定してください',
            'email.max' => 'メールアドレスは:max文字以内で指定してください',
            'password.required' => 'パスワードは必須です',
            'password.string' => 'パスワードは文字列で指定してください',
            'password.min' => 'パスワードは:min文字以上で指定してください',
            'password.max' => 'パスワードは:max文字以内で指定してください',
        ];
    }
}
