<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <title>店舗代表者作成画面</title>
  </head>
  <body class="min-h-screen bg-gray-100">
    <header class="py-10">
      <div class="container mx-auto flex items-center justify-between px-8 lg:px-12">
        <h1 class="text-5xl font-extrabold text-blue-600"><span class="bg-blue-600 px-2 text-5xl font-extrabold text-white">Y</span>Rese</h1>
        <div class="mt-4">
          <a href="{{ route('owner.index') }}" class="rounded bg-gray-500 px-4 py-2 font-bold text-white">管理画面トップに戻る</a>
        </div>
      </div>
    </header>
    <main>
      <div class="tab container mx-auto px-8 lg:px-12">
        <ul class="tab-menu">
          <li class="tab-menu__item active font-bold">店舗作成</li>
          <li class="tab-menu__item font-bold">店舗編集</li>
        </ul>
        <div class="tab-content p-4">
          <div class="tab-content__item show">
            <div>
              @if($errors->any())
                <div class="bg-red-500 text-white text-md px-4 py-3 mb-3">
                  <p>入力に誤りがあります。確認してください。</p>
                </div>
              @endif
              <form action="{{ route('owner.store-shop') }}" method="post" enctype="multipart/form-data" novalidate>
                @csrf
                @error('name')
                  <span class="text-red-600 text-base">{{ $message }}</span>
                @enderror
                <div>
                  <label for="name" class="block text-base">店舗名</label>
                  <input type="text" name="name" id="name" class="w-1/4 text-base" required />
                </div>
                @error('area_id')
                  <span class="text-red-600 text-base">{{ $message }}</span>
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
                  <span class="text-red-600 text-base">{{ $message }}</span>
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
                  <textarea name="description" id="description" class="w-full text-base resize-none" rows="5" required></textarea>
                  @error('description')
                    <span class="text-red-600 text-base">{{ $message }}</span>
                  @enderror
                </div>
                <div>
                  <label for="image_url" class="block text-base">店舗画像</label>
                  <input type="file" name="image_url" id="image_url" class="w-full text-base" accept="image/*">
                  @error('image_url')
                    <span class="text-red-600 text-base">{{ $message }}</span>
                  @enderror
                </div>
                <div class="text-right">
                  <button type="submit" class="rounded bg-blue-500 px-4 py-2 font-bold text-white text-base">店舗を作成</button>
                </div>
              </form>
            </div>
          </div>
          <div class="tab-content__item">
            @if(count($shops) > 0)
            <table class="table-auto w-full">
              <thead>
                <tr class="space-x-4">
                  <th class="text-center">店舗名</th>
                  <th class="text-center">都道府県</th>
                  <th class="text-center">ジャンル</th>
                  <th class="text-center">編集</th>
                  <th class="text-center">削除</th>
                </tr>
              </thead>
              <tbody>
                @foreach($shops as $shop)
                <tr class="space-x-4 text-2xl font-semibold">
                  <td class="text-center pb-2">{{ $shop->name }}</td>
                  <td class="text-center pb-2">{{ $shop->area->name }}</td>
                  <td class="text-center pb-2">{{ $shop->genre->name }}</td>
                  <td class="text-center pb-2">
                    <a href="{{ route('owner.edit-shop', $shop->id) }}" class="rounded bg-blue-500 px-4 py-2 font-bold text-white">編集</a>
                  </td>
                  <td class="text-center pb-2">
                    <form action="{{ route('owner.delete-shop', $shop->id) }}" method="POST" onsubmit="return confirm('本当に削除してもよろしいですか？');">
                      @csrf @method('DELETE')
                      <button type="submit" class="rounded bg-red-500 px-4 py-2 font-bold text-white">削除</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @else
            <p>作成した店舗はありません</p>
            @endif
          </div>
        </div>
      </div>
    </main>
    <script src="{{ asset('js/shop.js') }}"></script>
  </body>
</html>
