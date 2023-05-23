<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <title>予約一覧</title>
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
      <div class="tab container mx-auto px-8 lg:px-12">
      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif
      @if (session('error'))
        <div class="alert alert-error">
          {{ session('error') }}
        </div>
      @endif
        <ul class="tab-menu">
          <!-- タブはJavaScriptで動的に生成 -->
        </ul>
        <div class="tab-content p-4 mb-4">
          <!-- タブのコンテンツもJavaScriptで動的に生成 -->
        </div>
      </div>
    </main>
    <script>
      const shopsData = @json($shops);
      const reservationsData = @json($reservations);
      const reservationDetailUrlTemplate = "{{ route('owner.reservation-detail', ['id' => 'RESERVATION_ID']) }}";
    </script>
    <script src="{{ asset('js/shop.js') }}"></script>
  </body>
</html>
