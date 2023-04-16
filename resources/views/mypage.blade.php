<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mypage</title>
  <link rel="stylesheet" href="css/reset.css" />
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0-beta2/css/all.css">
</head>
<body class="bg-gray-100" data-is-user-logged-in="{{ Auth::check() ? 'true' : 'false' }}">
  <div class="w-screen h-screen px-20 pt-10 pb-6">
    <div class="relative w-full">
      <div class="flex md:mb-20">
        <div class="menu-btn mr-6 absolute left-0 bg-blue-600 shadow-3xl z-40" onclick="window.toggleModal()">
          <span class="line_1"></span>
          <span class="line_2"></span>
          <span class="line_3"></span>
        </div>
        <a href="/" class="text-5xl font-extrabold text-blue-600 ml-20 sm:mb-10">Rese</a>
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

    <div class="col-span-1 lg:col-span-1  lg:absolute lg:left-1/2 lg:top-28">
      <h4 class="text-4xl font-extrabold md:mb-10">{{ Auth::user()->name }} さん</h4>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-2 w-full gap-8">
      <div class="col-span-1">
        <h2 class="text-3xl font-extrabold">予約状況</h2>
        <div class="col-span-1 lg:col-span-1">
          @foreach ($reservations as $index => $reservation)
          <div class="my-12 w-full lg:w-4/5">
            <div class="box-content border-blue-600 bg-blue-600 h-full w-full rounded-lg relative shadow-3xl">
              <div class="text- text-white pt-5 ml-5 mr-5 mb-5 ">
                        <div class="flex justify-between mb-5">
                            <div class="flex items-center">
                                <div class="bg-cover bg-center bg-no-repeat w-8 h-8" style="background-image: url('/img/clock.svg');"></div>
                                <h5 class="ml-12">予約{{ $index + 1 }}</h5>
                            </div>
                            <form action="{{ route('reserve.delete') }}" method="post" class="inline">
                                @csrf
                                <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                                <button type="submit" class="rounded-full text-white text-3xl">
                                    <i class="fa-regular fa-circle-xmark" style="color: #ffffff;"></i>
                                </button>
                            </form>
                        </div>
                        <div>
                          <table class="table-responsive">
                            <tr>
                              <th>Shop</th>
                              <td class="pb-5">{{ $reservation->shop->name ?? 'Unknown' }}</td>
                            </tr>
                            <tr>
                              <th>Date</th>
                              <td class="pb-5">{{ date('Y-m-d', strtotime($reservation->start_at)) }}</td>
                            </tr>
                            <tr>
                              <th>Time</th>
                              <td class="pb-5">{{ date('H:i', strtotime($reservation->start_at)) }}</td>
                            </tr>
                            <tr>
                              <th>Number</th>
                              <td class="pb-5">{{ $reservation->num_of_users }}</td>
                            </tr>
                          </table>
                        </div>
                        <div class="flex justify-between">
                          <button class="bg-white hover:bg-blue-200 text-blue-600 rounded-lg px-4 py-2 mb-3">
                            <a href="" class="inline-flex items-center h-full">
                              <span class="text-lg font-semibold">更新</span>
                            </a>
                          </button>
                          <button class="bg-white hover:bg-blue-200 text-blue-600 rounded-lg px-4 py-2 mb-3">
                            <a href="{{ route('reviews.create', ['shop_id' => $reservation->shop->id]) }}" class="inline-flex items-center h-full">
                              <span class="text-lg font-semibold">レビュー</span>
                            </a>
                          </button>
                        </div>
                      </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>

          
      <div class="col-span-1">
        <h5 class="text-3xl font-extrabold pt-16 pb-3 lg:pt-0">お気に入り店舗</h5>
        <div class="grid grid-cols-1 gap-4 w-full mb-8 mt-4 rounded-lg md:grid-cols-2">
          @foreach ($favoriteShops as $shop)
            <div class="bg-white rounded-lg overflow-hidden shadow-3xl">
              <img src="{{ $shop->image_url }}" class="w-full h-48 object-cover object-center">
              <div class="p-4">
                <h3 class="text-lg font-bold text-gray-900">{{ $shop->name }}</h3>
                <div class="flex items-center mt-2 text-gray-700">
                  <span class="text-sm font-bold">#{{ $shop->area->name }}</span>
                  <span class="text-sm font-bold">#{{ $shop->genre->name }}</span>
                </div>
                <div class="flex items-center mt-2 text-gray-700">
                  <a href="/detail/{{ $shop->id }}" class="bg-blue-600 text-white px-5 py-1 rounded-lg tracking-widest text-base">詳しくみる</a>
                  <button class="favorite-btn text-3xl text-gray-200 ml-auto p-2" data-shop-id="{{ $shop->id }}">
                    <i class="fa fa-heart"></i>
                  </button>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>


    </div>
  </div>
	<script>
    window.favorites = @json($favoriteShops);
	</script>
	<script src="{{ asset('js/mypage.js') }}"></script>
</body>
</html>


