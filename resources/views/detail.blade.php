<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rese</title>
  <script src="/js/jquery-3.6.4.min.js"></script>
  <script src="/js/jquery-ui-1.13.2.custom/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="/css/reset.css" />
  <link rel="stylesheet" href="/js/jquery-ui-1.13.2.custom/jquery-ui.min.css" />
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="detail-page bg-gray-100 w-screen min-h-screen">
  <div class="flex bg-gray-100 w-screen flex-grow px-20 pt-10 pb-6 flex-col md:flex-row">
    <div class="md:flex-1 w-full md:w-auto pb-20">
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
          <div class="pt-16 relative">
            <div class="flex">
              <a href="/" class="arrow_btn cursor-pointer h-10 w-10 mr-6 absolute left-0 bg-white rounded-lg shadow-3xl"></a>
              <h2 class="text-4xl font-extrabold ml-14 text-center">{{ $shop->name }}</h2>
            </div>
          </div>
          <div class="w-full h-96 my-8">
            <img src="{{ $shop->image_url }}" alt="店舗画像" class="w-full h-full object-cover object-center">
          </div>
          <div class="flex items-center mb-5">
            <h3 class="text-lg font-bold">#{{ $shop->area->name }}</h3>
            <h3 class="text-lg font-bold">#{{ $shop->genre->name }}</h3>
          </div>
          <div class="w-full h-20">
            <h4 class="text-lg font-bold">{{ $shop->description }}</h4>
          </div>
        </div>
      </div>

    <div class="md:flex-1 md:pl-10 w-full md:w-auto">
      <div class="box-content border-blue-600 bg-blue-600 min-h-full w-full rounded-lg relative shadow-3xl pb-20">
        <div class="text-2xl text-white font-bold pt-8 ml-5 mb-5 ">
          <h5>予約</h5>
        </div>
        <form method="POST" action="{{ route('reserve.store') }}">
          @csrf
          <input type="hidden" name="shop_id" value="{{ $shop->id }}">
          <input type="hidden" name="user_id" value="{{ Auth::check() ? Auth::id() : '' }}">
          <div class="relative flex ml-5 mb-4">
            <input name="start_at" class="rounded-md h-8 pl-2 items-center font-bold pr-12" type="text" id="calendar" readonly>
            <i class="fa-sharp fa-regular fa-calendar carender-icon text-black text-xl"></i>
          </div>
          <div class="resavation_detail h-8 w-full ml-5 mb-4 pr-8">
              <select name="time" class="w-11/12 h-full pl-2 rounded-md font-bold items-center focus:outline-none appearance-none" id="time">
                @for ($i = 11; $i <= 23; $i++)
                  <option value="{{ $i }}:00">{{ $i }}:00</option>
                  <option value="{{ $i }}:30">{{ $i }}:30</option>
                @endfor
              </select>
          </div>
          <div class="resavation_detail h-8 w-full ml-5 mb-5 pr-8">
            <select name="num_of_users" class="w-11/12 h-full pl-2 rounded-md font-bold items-center focus:outline-none appearance-none" id="number">
                @for ($i = 1; $i <= 25; $i++)
                    <option value="{{ $i }}">{{ $i }}人</option>
                @endfor
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
            </table>
          </div>
          <div class="bg-blue-700 border-4 h-16 w-full bottom-0 rounded-lg absolute">
            <button type="submit" id="reservation-form-btn"class="text-lg text-white text-center items-center w-full h-full" onclick="return {{ Auth::check() ? 'true' : 'alert(\'ログインが必要です。\')' }}">
              <h6>予約する</h6>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
<script>
  $(document).ready(function() {
    $("#datepicker").datepicker();
  });
</script>
<script src="{{ asset('js/detail.js') }}"></script>
</body>
</html>