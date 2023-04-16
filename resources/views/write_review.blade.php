<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Rese</title>
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0-beta2/css/all.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 px-8 md:px-12 lg:px-20 min-h-screen">
  <header class="w-full py-10">
    <div id="app-js" class="relative">
      <div class="flex">
        <div class="menu-btn mr-6 absolute left-0 bg-blue-600 shadow-3xl z-40">
          <span class="line_1"></span>
          <span class="line_2"></span>
          <span class="line_3"></span>
        </div>
        <a href="/" class="text-5xl font-extrabold text-blue-600 ml-20">Rese</a>
      </div>
      <div class="fixed inset-0 bg-white z-20 modal-bg hidden"></div>
      <div class="fixed inset-0 flex items-center justify-center z-30 modal-content hidden">
        <div class="bg-white text-center">
          <ul class="menu">
            <li class="menu__item px-4 py-2"><a href="/">Home</a></li>
            @guest
            <li class="menu__item px-4 py-2"><a href="{{ route('register') }}">Registration</a></li>
            <li class="menu__item px-4 py-2"><a href="{{ route('login') }}">Login</a></li>
            @else
            <li class="menu__item px-4 py-2"><a href="{{ route('mypage') }}">Mypage</a></li>
            <li class="menu__item px-4 py-2">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
              </form>
            </li>
            @endguest
          </ul>
        </div>
      </div>
    </div>
  </header>
  <div class="flex flex-col md:flex-row bg-gray-100 h-2/3">
    <div class="flex-1 mb-10 md:mr-10 md:mb-0">
    @if (session('success'))
      <div class="flex justify-center alert alert-success">
        <div class="h-64 w-96 mt-32 border border-white bg-white rounded-lg shadow-3xl flex flex-col justify-center items-center">
          <div class="text-2xl font-bold mb-12 tracking-wider">
            <p>{{ session('success') }}</p>
          </div>
          <button class="bg-blue-600 hover:bg-blue-700 text-white py-1 px-4 rounded focus:outline-none focus:shadow-outline">
            <a href="{{ route('mypage') }}" class="inline-flex items-center justify-center h-full px-4 text-lg text-white font-semibold">
              マイページに戻る
            </a>
          </button>
        </div>
      </div>
    @elseif ($shop)
      <div class="box-content border-blue-600 bg-blue-600 min-h-full w-full rounded-lg relative shadow-3xl md:min-h-full h-[30rem]">
        <div class="text-2xl text-white font-bold pt-8 ml-5 mb-5">
          <span>点数</span>
        </div>
          <form method="POST" action="{{ route('reviews.store') }}" class="h-2/3">
            @csrf
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
            <div class="flex ml-5 mb-6">
              <span class="star text-5xl cursor-pointer" id="1" data-rating="1">★</span>
              <span class="star text-5xl cursor-pointer" id="2" data-rating="2">★</span>
              <span class="star text-5xl cursor-pointer" id="3" data-rating="3">★</span>
              <span class="star text-5xl cursor-pointer" id="4" data-rating="4">★</span>
              <span class="star text-5xl cursor-pointer" id="5" data-rating="5">★</span>
              <input type="hidden" name="rating" id="rating" value="">
            </div>
            <div class="form-group mx-3 mt-auto h-2/3">
              <textarea name="comment" id="comment" rows="4" class="form-control min-h-full w-full px-3 py-3 border-blue-300 bg-blue-300 border-4 rounded-lg resize-none" placeholder="ここにコメントを入力してください。"></textarea>
            </div>
            <div class="bg-blue-700 h-16 w-full bottom-0 rounded-b-lg absolute">
              <button type="submit" class="text-lg text-white text-center items-center w-full h-full">
                <p class="font-bold">送信</p>
              </button>
            </div>
          </form>
        @else
          <p>お店が見つかりませんでした。もう一度試してください。</p>
        @endif
      </div>
    </div>
    <div class="flex-1 pb-10">
      @if ($shop && !session('success'))
        <div class="w-full h-96 my-8">
          <img src="{{ $shop->image_url }}" alt="店舗画像" class="w-full h-full object-cover object-center">
        </div>
        <p class="text-4xl font-extrabold mb-5">{{ $shop->name }}</p>
        <div class="flex items-center mb-5">
          <h3 class="text-lg font-bold">#{{ $shop->area->name }}</h3>
          <h3 class="text-lg font-bold">#{{ $shop->genre->name }}</h3>
        </div>
        <div class="w-full h-20">
          <h4 class="text-lg font-bold">{{ $shop->description }}</h4>
        </div>
      @endif
    </div>
  </div>
  <script src="{{ asset('js/review.js') }}"></script>
</body>
</html>