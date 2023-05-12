<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateShopRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'area_id' => 'required|integer|exists:areas,id',
            'genre_id' => 'required|integer|exists:genres,id',
            'description' => 'required|string|max:150',
            'image_url' => 'required|file|image|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '店舗名は必須です。',
            'name.string' => '店舗名は文字列で入力してください。',
            'name.max' => '店舗名は255文字以内で入力してください。',
            'area_id.required' => '都道府県を選択してください。',
            'area_id.integer' => '都道府県の値が不正です。',
            'area_id.exists' => '選択された都道府県が存在しません。',
            'genre_id.required' => 'ジャンルを選択してください。',
            'genre_id.integer' => 'ジャンルの値が不正です。',
            'genre_id.exists' => '選択されたジャンルが存在しません。',
            'description.required' => '店舗概要は必須です。',
            'description.string' => '店舗概要は文字列で入力してください。',
            'description.max' => '店舗概要は150文字以内で入力してください。',
            'image_url.required' => '店舗画像は必須です。',
            'image_url.file' => '店舗画像はファイルでアップロードしてください。',
            'image_url.image' => '店舗画像は画像ファイルでアップロードしてください。',
            'image_url.max' => '店舗画像は2MB以内のファイルをアップロードしてください。',
        ];
    }
}
