<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rese</title>
  <script src="https://js.stripe.com/v3/"></script>
  <script src="/js/jquery-3.6.4.min.js"></script>
  <script src="/js/jquery-ui-1.13.2.custom/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="/css/reset.css" />
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="/js/jquery-ui-1.13.2.custom/jquery-ui.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="detail-page bg-gray-100 w-screen h-screen md:flex md:flex-col">
  <div class="flex w-screen flex-grow overflow-hidden px-20 pt-10 pb-6 flex-col md:flex-row">

    <!-- 左側 -->
    <div class="md:flex-1 md:pl-10 md:pb-0 pb-10 w-full md:w-auto">
      <div class="grid grid-cols-1 w-full md:pr-10">
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

        <!-- 店舗情報部分 -->
        <div class="pt-16 relative">
          <div class="flex">
            <a href="/" class="arrow_btn cursor-pointer h-10 w-10 mr-6 absolute left-0 bg-white rounded-lg shadow-3xl"></a>
            <h2 class="text-4xl font-extrabold ml-14 text-center">{{ $shop->name }}</h2>
          </div>
        </div>
        <div class="w-full h-96 my-8">
          <img src="{{ $shop->image_url }}" alt="店舗画像" class="w-full h-full object-cover object-center">
        </div>
        <div class="overflow-y-auto" style="max-height:35vh;">
          <div class="flex items-center mb-5">
            <h3 class="text-lg font-bold">#{{ $shop->area->name }}</h3>
            <h3 class="text-lg font-bold">#{{ $shop->genre->name }}</h3>
          </div>
          <div class="w-full h-24 shop-description mb-12">
            <h4 class="text-lg font-bold">{{ $shop->description }}</h4>
          </div>
          <div class="md:mb-14">
            <h5 class="text-xl lg:text-3xl font-bold mb-4">お店のレビュー</h5>
            @foreach ($shop->reviews as $review)
              <div class="review-item bg-white shadow-md rounded-lg p-4 mt-4">
                <div class="block flex justify-between items-center">
                  <h4 class="text-lg font-bold">{{ $review->user->name }}</h4>
                  <div class="flex">
                    @for ($i = 0; $i < $review->rating; $i++)
                      <span class="text-yellow-500">★</span>
                    @endfor
                  </div>
                </div>
                <p class="mt-2">{{ $review->comment }}</p>
              </div>
            @endforeach
          </div>
        </div>

      </div>
    </div>

    <!-- 右側 -->
    <div class="md:flex-1 md:pl-10 w-full md:w-auto">
      <div class="box-content border-blue-600 bg-blue-600 min-h-full w-full rounded-lg relative shadow-3xl md:pb-0 pb-20">
        <div class="text-2xl text-white font-bold pt-8 ml-5 mb-5 ">
          <h6>予約</h6>
        </div>

        <!-- 予約入力・確認部分 -->
        <form novalidate id="reservation-form" action="/api/reservations" method="POST">
          @csrf
          <input type="hidden" name="shop_id" value="{{ $shop->id }}">
          <input type="hidden" name="start_at" id="start_at">
          <input type="hidden" name="user_id" value="{{ Auth::check() ? Auth::id() : '' }}">
          @if ($errors->has('start_at'))
            <div class="text-red-500 text-sm ml-5">
                {{ $errors->first('start_at') }}
            </div>
          @endif
          <div class="relative flex ml-5 mb-4">
            <input class="rounded-md h-8 pl-2 items-center font-bold pr-12" type="text" id="calendar" placeholder="日付(必須)" readonly>
            <i class="fa-sharp fa-regular fa-calendar carender-icon text-black text-xl"></i>
          </div>
          @if ($errors->has('time'))
            <div class="text-red-500 text-sm ml-5">
              {{ $errors->first('time') }}
            </div>
          @endif
          <div class="resavation_detail h-8 w-full ml-5 mb-4 pr-8">
            <select name="time" class="w-11/12 h-full pl-2 rounded-md font-bold items-center focus:outline-none appearance-none" id="time">
              <option value="" disabled selected>開始時間(必須)</option>
              @for ($i = 11; $i <= 23; $i++)
                <option value="{{ $i }}:00">{{ $i }}:00</option>
                <option value="{{ $i }}:30">{{ $i }}:30</option>
              @endfor
            </select>
          </div>
          @if ($errors->has('num_of_users'))
            <div class="text-red-500 text-sm ml-5">
              {{ $errors->first('num_of_users') }}
            </div>
          @endif
          <div class="resavation_detail h-8 w-full ml-5 mb-4 pr-8">
            <select name="num_of_users" class="w-11/12 h-full pl-2 rounded-md font-bold items-center focus:outline-none appearance-none" id="num_of_users">
              <option value="" disabled selected>人数(必須)</option>
              @for ($i = 1; $i <= 25; $i++)
                <option value="{{ $i }}">{{ $i }}人</option>
              @endfor
            </select>
          </div>
          @if ($errors->has('course_menu_id'))
            <div class="text-red-500 text-sm ml-5">
              {{ $errors->first('course_menu_id') }}
            </div>
          @endif
          <div class="resavation_detail h-8 w-full ml-5 mb-4 pr-8">
            <select name="course_menu_id" class="w-11/12 h-full pl-2 rounded-md font-bold items-center focus:outline-none appearance-none" id="course_menu_id">
              <option value="">メニュー(任意)<span>*選択しない場合はそのままで結構です</span></option>
              @foreach ($course_menus as $course_menu)
                <option value="{{ $course_menu->id }}">{{ $course_menu->name }} (¥{{ number_format($course_menu->price) }})</option>
              @endforeach
            </select>
          </div>
          <div class="w-11/12 ml-5 px-3 py-5 border-blue-300 bg-blue-300 border-4 rounded-lg">
            <table class="table-fixed font-bold text-white">
              <tr>
                <th class="block mr-5 text-left">Shop</th>
                <td>{{ $shop->name }}</td>
              </tr>
              <tr>
                <th class="block mr-5 text-left">Date</th>
                <td><span id="selected-date"></span></td>
              </tr>
              <tr>
                <th class="block mr-5 text-left">Time</th>
                <td><span id="selected-time"></span></td>
              </tr>
              <tr>
                <th class="block mr-5 text-left">Number</th>
                <td><span id="selected-number"></span></td>
              </tr>
              <tr>
                <th class="block mr-5 text-left">Course</th>
                <td><span id="selected-course"></span></td>
              </tr>
            </table>
          </div>
          <div class="bg-blue-700 border-4 h-16 w-full bottom-0 rounded-lg absolute">
            <button type="button" id="reservation-form-btn" class="text-lg text-white text-center items-center w-full h-full">予約する</button>
          </div>
        </form>

        <!-- コース料理説明 -->
        <div class="overflow-y-auto" style="max-height: 40vh;">
          <div class="mx-5">
          @if($course_menus)
            @foreach($course_menus as $course_menu)
            <div class="review-item bg-white shadow-md rounded-lg p-4 mt-4">
              <div class="block flex justify-between items-center">
                <h4 class="text-lg font-bold">{{ $course_menu->name }}</h4>
                <p class="text-lg">{{ number_format($course_menu->price) }}円</p>
              </div>
              <p class="mt-2">{{ $course_menu->description }}</p>
            </div>
            @endforeach
          @endif
          </div>
        </div>

      </div>
    </div>
  </div>
<script>
const stripe = Stripe('{{ env('STRIPE_KEY') }}');
$(document).ready(function() {
    $("#datepicker").datepicker();
  });
</script>
<script src="{{ asset('js/detail.js') }}"></script>
</body>
</html>