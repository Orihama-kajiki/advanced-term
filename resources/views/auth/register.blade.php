<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rese</title>
    <link rel="stylesheet" href="css/reset.css" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
  </head>
  <body class="bg-gray-100">
    <div class="h-screen w-screen overflow-y-auto bg-gray-100 px-20 pb-6 pt-10">
      <div id="app-js" class="relative">
        <div class="flex">
          <div class="menu-btn shadow-3xl absolute left-0 z-40 mr-6 bg-blue-600">
            <span class="line_1"></span>
            <span class="line_2"></span>
            <span class="line_3"></span>
          </div>
          <a href="/" class="ml-20 text-5xl font-extrabold text-blue-600">Rese</a>
        </div>
        <div class="modal-bg fixed inset-0 z-20 hidden bg-white"></div>
        <div class="modal-content fixed inset-0 z-30 flex hidden items-center justify-center">
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
      <div class="mt-40 flex items-center justify-center">
        <div class="shadow-3xl relative flex min-h-[280px] w-96 flex-col rounded-lg border border-white bg-white">
          <div class="absolute box-content flex h-20 w-full items-center justify-start rounded-t-lg border border-blue-600 bg-blue-600">
            <h2 class="m-4 items-center text-xl text-white">Registration</h2>
          </div>
          <div class="flex flex-grow flex-col items-center justify-end">
            <form method="POST" action="{{ route('register') }}" class="flex w-full flex-col px-6 pb-4 pt-10" novalidate>
              @csrf
              @error('name')
              <p class="text-red-500 text-xs pt-14">{{ $message }}</p>
              @enderror
              <div class="Name mb-2 flex items-center">
                <i class="fas fa-user mr-2 h-8 w-8"></i>
                <input id="name" type="text" class="w-full appearance-none border-b-2 border-gray-400 leading-tight text-gray-700 focus:border-blue-600 focus:outline-none" name="name" placeholder="Username"/>
              </div>
              @error('email')
              <p class="text-red-500 text-xs">{{ $message }}</p>
              @enderror
              <div class="Email mb-2 flex items-center">
                <i class="fas fa-envelope mr-2 h-8 w-8"></i>
                <input id="email" type="email" class="w-full appearance-none border-b-2 border-gray-400 leading-tight text-gray-700 focus:border-blue-600 focus:outline-none" name="email" placeholder="Email"/>
              </div>
              @error('password')
              <p class="text-red-500 text-xs">{{ $message }}</p>
              @enderror
              <div class="Password mb-6 flex items-center">
                <i class="fas fa-lock mr-2 h-8 w-8"></i>
                <input id="password" type="password" class="w-full appearance-none border-b-2 border-gray-400 leading-tight text-gray-700 focus:border-blue-600 focus:outline-none" name="password" placeholder="Password"/>
              </div>
              <div class="flex items-center justify-end">
                <button class="focus:shadow-outline rounded bg-blue-600 px-4 py-1 text-white hover:bg-blue-700 focus:outline-none" type="submit">登録</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script src="{{ asset('js/main.js') }}"></script>
  </body>
</html>
