<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rese</title>
  <link rel="stylesheet" href="css/reset.css" />
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0-beta2/css/all.css">
</head>
<body >
  <div class="bg-gray-100 w-screen px-20 pt-10 pb-6">
    <div id="app-js" class="relative">
      <div class="flex">
        <div class="menu-btn mr-6 absolute left-0 bg-blue-600 shadow-3xl z-40" onclick="toggleModal()">
          <span class="line_1"></span>
          <span class="line_2"></span>
          <span class="line_3"></span>
        </div>
        <a href="/" class="text-5xl font-extrabold text-blue-600 ml-20">Rese</a>
      </div>
      <div class="fixed inset-0 bg-white z-20 modal-bg hidden" onclick="toggleModal()"></div>
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
    <div class="h-full flex justify-center items-center">
      <div class="bg-gray-100 h-screen flex items-center justify-center">
        <div class="h-64 w-96 border border-white bg-white rounded-lg shadow-3xl relative">
          <div class="h-1/4 w-full border border-blue-600 bg-blue-600 box-content rounded-t-lg absolute">
            <h2 class="m-4 text-xl text-white items-center">Login</h2>
          </div>
          <div class="h-80 flex justify-center items-center flex-col">
            <form method="POST" action="{{ route('login') }}" class="w-full pl-8 pr-7 flex flex-col">
            @csrf
                <div class="Email flex items-center mb-4">
                <i class="fas fa-envelope mr-2 w-8 h-8"></i>
                <input id="email" type="email" class="appearance-none border-b-2 border-gray-400 w-full py-2 text-gray-700 leading-tight focus:outline-none focus:border-blue-600" name="email" placeholder="Email" required autofocus>
                </div>
              <div class="Password flex items-center mb-6">
                <i class="fas fa-lock mr-2 w-8 h-8"></i>
                <input id="password" type="password" class=" appearance-none border-b-2 border-gray-400 w-full py-2 text-gray-700 leading-tight focus:outline-none focus:border-blue-600" name="password" placeholder="Password" required>
              </div>
              <div class="flex items-center justify-end">
                <button class="bg-blue-600 hover:bg-blue-700 text-white py-1 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                  ログイン
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
