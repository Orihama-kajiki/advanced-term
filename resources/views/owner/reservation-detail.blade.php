<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <title>予約詳細</title>
  </head>
  <body class="min-h-screen bg-gray-100">
    <header class="py-10">
      <div class="container mx-auto flex items-center justify-between px-8 lg:px-12">
        <h1 class="text-5xl font-extrabold text-blue-600"><span class="bg-blue-600 px-2 text-5xl font-extrabold text-white">Y</span>Rese</h1>
        <div class="mt-4">
          <a href="{{ route('owner.index') }}" class="rounded bg-gray-500 px-4 py-2 font-bold text-white">管理画面トップに戻る</a>
        </div>
      </div>
    </header>
    <main>
      <div class="container mx-auto px-8 lg:px-12">
        <h2 class="text-4xl font-bold mb-6">予約詳細</h2>
        <form action="{{ route('owner.reservation-update', $reservation->id) }}" method="post">
          @csrf
          @method('PUT')
          <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="mb-4">
              <h3 class="text-2xl font-semibold mb-2">ユーザー名</h3>
              <p>{{ $reservation->user->name }}</p>
            </div>
            <div class="mb-4">
              <h3 class="text-2xl font-semibold mb-2">人数</h3>
              <input type="number" name="num_of_users" id="num_of_users" class="w-1/4 border rounded px-3 py-2" min="1" value="{{ $reservation->num_of_users }}" required>
            </div>
            <div class="mb-4">
              <h3 class="text-2xl font-semibold mb-2">年月日</h3>
              <input type="text" name="date" id="date" class="w-1/4 border rounded px-3 py-2" value="{{ $reservation->start_at->format('Y-m-d') }}" required>
            </div>
            <div class="mb-4">
              <h3 class="text-2xl font-semibold mb-2">時間</h3>
              <select name="time" id="time" class="border rounded px-3 py-2">
                @for ($i = 11; $i <= 23; $i++)
                  <option value="{{ $i }}:00" {{ $i == $reservation->start_at->format('H') ? 'selected' : '' }}>{{ $i }}:00</option>
                  <option value="{{ $i }}:30" {{ $i == $reservation->start_at->format('H') && $reservation->start_at->format('i') == '30' ? 'selected' : '' }}>{{ $i }}:30</option>
                @endfor
              </select>
            </div>
            <div class="pt-2 mb-4">
              <h3 class="text-2xl font-semibold mb-2">コースメニュー:{{ $reservation->course_menu->name ?? 'コースメニューは選択されていません' }}</h3>
              <p class="text-red-500">*コースが選択されている場合、既に支払いが発生しています。変更等が発生した場合は決済ページ等で変更・返金してください</p>
            </div>
            <div class="my-4 flex justify-between">
              <a href="{{ route('owner.reservation-list') }}" class="rounded bg-gray-500 px-4 py-2 font-bold text-white">戻る</a>
              <button type="submit" class="bg-blue-500 text-white rounded px-4 py-2">更新</button>
            </div>
          </div>
        </form>
      </div>
    </main>
    <script>
      $(document).ready(function () {
        $("#date").datepicker({
          dateFormat: "yy-mm-dd",
        });
        const initDate = "{{ $reservation->start_at->format('Y-m-d') }}";
        $("#date").datepicker("setDate", initDate);
      });
    </script>
  </body>
</html>
