<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<title>管理画面</title>
</head>
<body class="min-h-screen bg-gray-100 px-8 lg:px-12">
<header class="py-10">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-5xl font-extrabold text-blue-600">Rese</h1>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="bg-red-500 text-white font-bold py-2 px-4 rounded">ログアウト</button>
      </form>
    </div>
</header>
<main>
  <div class="container mx-auto py-8">
    <h2 class="text-3xl font-bold text-center my-8 pb-8">管理画面</h2>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
      <a href="{{ route('admin.create-account') }}" class="bg-blue-500 text-white font-bold py-3 px-4 rounded block text-center">ユーザー管理</a>
      <a href="#" class="bg-blue-500 text-white font-bold py-3 px-4 rounded block text-center">ショップ管理</a>
      <a href="{{ route('admin.create-email') }}" class="bg-blue-500 text-white font-bold py-3 px-4 rounded block text-center">一斉メール送信</a>
    </div>
  </div>
</main>
</body>
</html>
