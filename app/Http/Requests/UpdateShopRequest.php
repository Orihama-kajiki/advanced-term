<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShopRequest extends FormRequest
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
      'image_url' => 'sometimes|file|image|mimes:jpeg,gif|max:2048',
      'course_name.*' => 'nullable|string|max:255',
      'course_price.*' => 'nullable|integer',
      'course_description.*' => 'nullable|string|max:255',
    ];
  }
  public function withValidator($validator)
  {
    $validator->after(function ($validator) {
      $course_names = $this->get('course_name') ?? [];
      $course_prices = $this->get('course_price') ?? [];
      $course_descriptions = $this->get('course_description') ?? [];

      $course_count = max(count($course_names), count($course_prices), count($course_descriptions));

      for ($i = 0; $i < $course_count; $i++) {
        $name = $course_names[$i] ?? null;
        $price = $course_prices[$i] ?? null;
        $description = $course_descriptions[$i] ?? null;

        if ($name || $price || $description) {
          if (!$name || !$price || !$description) {
            $validator->errors()->add('courses', 'すべてのコースフィールドを入力するか、すべて空白にしてください。');
            break;
          }
        }
      }
    });
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
      'image_url.file' => '店舗画像はファイルでアップロードしてください。',
      'image_url.image' => '店舗画像は画像ファイルでアップロードしてください。',
      'image_url.mimes' => '店舗画像は｢.jpeg｣または｢.gif｣形式のファイルをアップロードしてください。',
      'image_url.max' => '店舗画像は2MB以内のファイルをアップロードしてください。',
      'course_name.*.string' => 'コース名は文字列で入力してください。',
      'course_name.*.max' => 'コース名は255文字以内で入力してください。',
      'course_price.*.integer' => 'コース価格は数値で入力してください。',
      'course_description.*.string' => 'コース説明は文字列で入力してください。',
      'course_description.*.max' => 'コース説明は255文字以内で入力してください。',
    ];
  }
}
