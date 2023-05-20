<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Mypage</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="css/reset.css">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="/js/jquery-ui-1.13.2.custom/jquery-ui.min.css" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0-beta2/css/all.css">
</head>
<body class="bg-gray-100" data-is-user-logged-in="{{ Auth::check() ? 'true' : 'false' }}">
  <!-- メイン -->
  <div class="w-screen h-screen px-20 pt-10 pb-6">
    <div class="relative w-full">
      <div class="flex">
        <div class="menu-btn mr-6 absolute left-0 bg-blue-600 shadow-3xl z-30">
          <span class="line_1"></span>
          <span class="line_2"></span>
          <span class="line_3"></span>
        </div>
        <a href="/" class="text-5xl font-extrabold text-blue-600 ml-20 sm:mb-10">Rese</a>
      </div>
    </div>
    <div class="col-span-1 py-5 lg:py-0 lg:col-span-1  lg:absolute lg:left-1/2 lg:top-20">
      <h4 class="text-4xl font-extrabold">{{ Auth::user()->name }} さん</h4>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 w-full gap-8">
      <div class="col-span-1 lg:col-span-1">

      <!-- タブメニュー -->
        <div class="flex">
          <button class="tablink bg-blue-600 text-white font-semibold text-2xl px-4 py-2 rounded mr-4 hover:bg-blue-200" onclick="changeTab(event, 'current-reservations')">予約状況</button>
          <button class="tablink bg-blue-600 text-white font-semibold text-2xl px-4 py-2 rounded hover:bg-blue-200" onclick="changeTab(event, 'past-reservations')">予約履歴</button>
        </div>

      <!-- 予約状況 -->
        <div id="current-reservations" class="tabcontent">
          <div class="col-span-1 lg:col-span-1">
          @forelse ($reservations as $index => $reservation)
            <div class="my-12 w-full lg:w-4/5">
              <div class="box-content border-blue-600 bg-blue-600 h-full w-full rounded-lg relative shadow-3xl">
                <div class="text-lg text-white pt-5 ml-5 mr-5 mb-5 ">
                  <div class="flex justify-between mb-5">
                    <div class="flex items-center">
                      <div class="bg-cover bg-center bg-no-repeat w-8 h-8" style="background-image: url('/img/clock.svg');"></div>
                      <h5 class="ml-12">予約{{ $index + 1 }}</h5>
                    </div>
                    <form action="{{ route('reserve.delete', $reservation->id) }}" method="post" class="inline">
                      @csrf
                      @method('DELETE')
                      <input type="hidden" id="reservation-id" name="reservation_id" value="{{ $reservation->id }}">
                      <button type="submit" class="rounded-full text-white text-3xl">
                        <i class="fa-regular fa-circle-xmark" style="color: #ffffff;"></i>
                      </button>
                    </form>
                  </div>
                  <div>
                    <table class="table-responsive">
                      <tr>
                        <th>Shop</th>
                        <td class="pl-5 pb-5" id="reservation-{{ $reservation->id }}-shop">{{ $reservation->shop->name ?? 'Unknown' }}</td>
                      </tr>
                      <tr>
                        <th>Date</th>
                        <td class="pl-5 pb-5" id="reservation-{{ $reservation->id }}-date">{{ date('Y-m-d', strtotime($reservation->start_at)) }}</td>
                      </tr>
                      <tr>
                        <th>Time</th>
                        <td class="pl-5 pb-5" id="reservation-{{ $reservation->id }}-time">{{ date('H:i', strtotime($reservation->start_at)) }}</td>
                      </tr>
                      <tr>
                        <th>Number</th>
                        <td class="pl-5 pb-5" id="reservation-{{ $reservation->id }}-num-of-users">{{ $reservation->num_of_users }}</td>
                      </tr>
                    </table>
                  </div>
                  <div class="flex justify-end">
                    <button class="open-modal-3 bg-white hover:bg-blue-200 text-blue-600 rounded-lg px-4 py-2 mr-2 mb-3" data-reservation-id="{{ $reservation->id }}">
                      <div class="inline-flex items-center h-full">
                        <span class="text-lg font-semibold">予約詳細</span>
                      </div>
                    </button>
                    <button id="change-reservation-{{ $reservation->id }}" class="update-btn bg-white hover:bg-blue-200 text-blue-600 rounded-lg px-4 py-2 mb-3" data-reservation-id="{{ $reservation->id }}">
                      <div class="inline-flex items-center h-full">
                        <span class="text-lg font-semibold">内容変更</span>
                      </div>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            @empty
            <div class="my-12 w-full lg:w-4/5">
              <h2 class="text-2xl font-semibold">現在予約はありません。</h2>
            </div>
          @endforelse
          </div> 
        </div>

      <!-- 予約履歴 -->
        <div id="past-reservations" class="tabcontent">
          @forelse ($pastReservations as $index => $reservation)
            <div class="my-12 w-full lg:w-4/5">
              <div class="box-content bg-gray-400 h-full w-full rounded-lg relative shadow-3xl">
                <div class="text-lg pt-5 mx-5 mb-5">
                  <div class="flex mb-5">
                    <div class="flex items-center">
                      <div class="bg-cover bg-center bg-no-repeat w-8 h-8" style="background-image: url('/img/clock.svg');"></div>
                      <h5 class="ml-12">予約{{ $index + 1 }}</h5>
                    </div>
                  </div>
                  <div>
                    <table class="table-responsive">
                      <tr>
                        <th>Shop</th>
                        <td class="pl-5 pb-5" id="reservation-{{ $reservation->id }}-shop">{{ $reservation->shop->name ?? 'Unknown' }}</td>
                      </tr>
                      <tr>
                        <th>Date</th>
                        <td class="pl-5 pb-5" id="reservation-{{ $reservation->id }}-date">{{ date('Y-m-d', strtotime($reservation->start_at)) }}</td>
                      </tr>
                      <tr>
                        <th>Time</th>
                        <td class="pl-5 pb-5" id="reservation-{{ $reservation->id }}-time">{{ date('H:i', strtotime($reservation->start_at)) }}</td>
                      </tr>
                      <tr>
                        <th>Number</th>
                        <td class="pl-5 pb-5" id="reservation-{{ $reservation->id }}-num-of-users">{{ $reservation->num_of_users }}</td>
                      </tr>
                    </table>
                  </div>
                  <div class="flex justify-end">
                    <button class="bg-white hover:bg-yellow-200 rounded-lg px-4 py-2 mb-3">
                      <a href="{{ route('reviews.create', ['shop_id' => $reservation->shop->id]) }}" class="inline-flex items-center h-full">
                        <span class="text-lg font-semibold">レビュー</span>
                      </a>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            @empty
            <div class="my-12 w-full lg:w-4/5">
              <h2 class="text-2xl font-semibold">過去の予約はありません。</h2>
            </div>
          @endforelse
        </div>
      </div>
      
      <!-- お気に入り一覧 -->
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
                  <button class="favorite-btn text-3xl text-gray-200 ml-auto p-2" data-shop-id="{{ $shop->id }}" onclick="toggleFavorite({{ $shop->id }})">
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

  <!-- ここからモーダルウィンドウ -->

  <!-- Menu -->
  <div class="fixed inset-0 bg-white z-20 modal-bg1 hidden">
    <div class="fixed inset-0 flex items-center justify-center z-30 modal-content1 hidden">
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

  <!-- 予約編集画面 -->
  <div class="modal-bg-2 fixed inset-0 bg-gray-500 bg-opacity-50 z-40 hidden"></div>
  <div class="modal-content-2 fixed inset-0 z-50 items-center justify-center flex hidden">
    <div class="h-96 w-80 mx-auto bg-white rounded-md overflow-hidden">
      <form id="update-reservation-form" action="{{ route('reserve.update', $reservation) }}" method="POST">
        @csrf
        <div class="bg-blue-600 px-4 py-3">
          <h3 class="text-lg font-medium text-white">予約情報を更新する</h3>
        </div>
        <div class="h-full px-4 pt-3">
          <label for="num_of_users" class="block font-medium text-gray-700 mb-2">人数</label>
        @if(isset($reservation))
          <input type="number" name="num_of_users" id="num_of_users" class="border-b border-gray-300 rounded-md w-full py-2 px-3 mb-3 focus:outline-none focus:ring-blue-600 focus:border-blue-600" value="{{ $reservation->num_of_users }}">
        @else
          <input type="number" name="num_of_users" id="num_of_users" class="border-b border-gray-300 rounded-md w-full py-2 px-3 mb-3 focus:outline-none focus:ring-blue-600 focus:border-blue-600" value="">
        @endif
          <label for="time" class="block font-medium text-gray-700 mb-2">時間</label>
          <select name="time" id="time" class="border-b border-gray-300 rounded-md w-full py-2 px-3 mb-3 focus:outline-none focus:ring-blue-600 focus:border-blue-600">
          @for ($i = 11; $i <= 22; $i++)
            <option value="{{ $i }}:00">{{ $i }}:00</option>
            <option value="{{ $i }}:30">{{ $i }}:30</option>
          @endfor
          </select>
          <label for="datepicker" class="block font-medium text-gray-700 mb-2">日程</label>
          <input type="text" class="border-b border-gray-300 rounded-md w-full py-2 px-3 mb-6 focus:outline-none focus:ring-blue-600 focus:border-blue-600" name="datepicker" id="datepicker" readonly>
          <div class="flex justify-between">
            <button type="button" class="cancel-btn bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded mr-2">
              キャンセル
            </button>
          @if(isset($reservation))
            <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                更新
            </button>
          @endif
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- 予約詳細画面 -->
  <div id="qr-code-modal" class="modal hidden fixed top-0 left-0 w-full h-full bg-gray-500 bg-opacity-50 z-40 flex items-center justify-center">
    <div class="modal-content z-50 bg-white flex items-center justify-center relative rounded-lg" style="width: 400px; height: 450px;">
        <img id="qr-code-image" src="" alt="QR Code" style="display:none;">
        <button id="close-modal" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded absolute bottom-4 right-4">Close</button>
      </div>
    </div>
  </div>

  <!-- script -->
	<script>
    window.favorites = @json($favoriteShops);
    $(function () {
      $("#datepicker").datepicker({
        dateFormat: "yy-mm-dd",
      });
    });
	</script>
	<script src="{{ asset('js/mypage.js') }}"></script>
</body>
</html>


