<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <title>店舗代表者編集画面</title>
  </head>
  <body class="min-h-screen bg-gray-100 px-8 lg:px-12">
    <header class="py-10">
      <div class="container mx-auto flex items-center justify-between">
        <h1 class="text-5xl font-extrabold text-blue-600"><span class="bg-blue-600 px-2 text-5xl font-extrabold text-white">Y</span>Rese</h1>
        <div class="mt-4">
          <a href="{{ route('owner.index') }}" class="rounded bg-gray-500 px-4 py-2 font-bold text-white">管理画面トップに戻る</a>
        </div>
      </div>
    </header>
    <main>
      <div class="mx-auto px-8 lg:px-12">
        <div class="font-bold">店舗更新</div>
        @if($errors->any())
        <div class="text-md mb-3 bg-red-500 px-4 py-3 text-white">
          <p>入力に誤りがあります。確認してください。</p>
        </div>
        @endif
        <form action="{{ route('owner.update-shop', $shop->id) }}" method="post" enctype="multipart/form-data">
          @csrf @method('PUT') @error('name')
          <span class="text-base text-red-600">{{ $message }}</span>
          @enderror
          <div>
            <label for="name" class="block text-base">店舗名</label>
            <input type="text" name="name" id="name" class="w-1/4 text-base" required />
          </div>
          @error('area_id')
          <span class="text-base text-red-600">{{ $message }}</span>
          @enderror
          <div>
            <label for="area_id" class="block text-base">都道府県</label>
            <select name="area_id" id="area_id" class="w-1/4 text-base" required>
              <option value="">選択してください</option>
              @foreach($areas as $area)
              <option value="{{ $area->id }}">{{ $area->name }}</option>
              @endforeach
            </select>
          </div>
          @error('genre_id')
          <span class="text-base text-red-600">{{ $message }}</span>
          @enderror
          <div>
            <label for="genre_id" class="block text-base">ジャンル</label>
            <select name="genre_id" id="genre_id" class="w-1/4 text-base" required>
              <option value="">選択してください</option>
              @foreach($genres as $genre)
              <option value="{{ $genre->id }}">{{ $genre->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label for="description" class="block text-base">店舗概要</label>
            <textarea name="description" id="description" class="w-full resize-none text-base" rows="5" required></textarea>
            @error('description')
            <span class="text-base text-red-600">{{ $message }}</span>
            @enderror
          </div>
          <div>
            <label for="image_url" class="block text-base">店舗画像</label>
            <input type="file" name="image_url" id="image_url" class="w-full text-base" accept="image/*" />
            @error('image_url')
            <span class="text-base text-red-600">{{ $message }}</span>
            @enderror
          </div>
          <div>
            <label for="image_url" class="block text-base">現在の店舗画像</label>
            <img src="{{ $shop->image_url }}" alt="現在の店舗画像" width="200" height="200" />
          </div>
          <div class="text-right">
            <button type="submit" class="bg-blue-500 px-4 py-2 text-base font-bold text-white">店舗情報を更新</button>
          </div>
        </form>
      </div>
    </main>
  </body>
</html>
