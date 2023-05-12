<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Rese</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
  </head>
  <body>
    <div class="h-screen w-screen bg-gray-100 px-20 pb-6 pt-10">
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
      <div class="flex justify-center">
        <div class="shadow-3xl mt-32 flex h-auto w-96 flex-col items-center justify-center rounded-lg border border-white bg-white p-4">
          <div class="text-2xl font-bold tracking-wider">
            <p>認証が完了しました！</p>
          </div>
          <div class="my-5 text-center text-sm">
            <p>ご協力頂きありがとうございました。</p>
            <p>今後ともご利用の程よろしくお願い致します。</p>
          </div>
          <form action="{{ route('login') }}" method="GET">
            <button class="focus:shadow-outline rounded bg-blue-600 px-4 py-1 text-white hover:bg-blue-700 focus:outline-none" type="submit">ログインする</button>
          </form>
        </div>
      </div>
    </div>
    <script src="{{ asset('js/main.js') }}"></script>
  </body>
</html>
