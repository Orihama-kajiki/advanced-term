<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rese</title>
  <link rel="stylesheet" href="css/reset.css"/>
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body >
  <div class="bg-gray-100 h-screen w-screen px-20 pt-10 pb-6">
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
    <div class="flex justify-center">
      <div class="h-64 w-96 mt-32 border border-white bg-white rounded-lg shadow-3xl flex flex-col justify-center items-center">
        <div class="text-2xl font-bold mb-12 tracking-wider">
          <p>ご予約ありがとうございます。</p>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white py-1 px-4 rounded focus:outline-none focus:shadow-outline">
          <a href="/" type="submit">戻る</a>
        </button>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>

