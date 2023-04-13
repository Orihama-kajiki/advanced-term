<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Rese</title>
  <link rel="stylesheet" href="css/reset.css" />
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0-beta2/css/all.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
<div class="px-8 md:px-12 lg:px-20">
  <header class="flex justify-between w-full py-10">
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
    <div class="w-2/4 shadow-3xl">
      <form class="flex h-16 rounded-full shadow-md">
        <div class="area_detail w-1/4">
          <select class="w-full h-full pl-3 rounded-tl-lg rounded-bl-lg font-bold focus:outline-none appearance-none" name="area" id="areaSelect">
            <option value="">All area</option>
            @foreach ($areas as $area)
              <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="genre_detail w-1/4">
          <select class="w-full h-full font-bold focus:outline-none appearance-none pl-3" name="genre" id="genreSelect">
            <option value="">All genre</option>
            @foreach ($genres as $genre)
              <option value="{{ $genre->id }}">{{ $genre->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="search_detail flex items-center w-1/2 rounded-tr-lg rounded-br-lg">
          <span class="sr-only">Search</span>
          <span class="bg-white bg-center bg-no-repeat w-6 h-6" id="search-icon" style="background-image: url('/img/search.svg');"></span>
          <input type="text" class="block h-full w-full py-2 pl-12 rounded-tr-lg rounded-br-lg placeholder-gray-500 text-gray-900 focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent sm:text-lg" placeholder="Search ..." id="searchInput">
        </div>
      </form>
    </div>
  </header>
  <div id="search-results" class="grid grid-cols-1 lg:grid-cols-none gap-4"></div>
</div>
<script>
  window.shops = @json($shops);
  window.favorites = @json($favorites);
  window.isUserLoggedIn = "{{ Auth::check() }}" === "1";
</script>
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>