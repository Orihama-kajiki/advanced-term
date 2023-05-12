<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href="{{ asset('css/app.css') }}" rel="stylesheet" />
<title>メール作成画面</title>
</head>
<body class="min-h-screen bg-gray-100 px-8 lg:px-12">
<header class="py-10">
<div class="container mx-auto flex items-center justify-between">
  <h1 class="text-5xl font-extrabold text-blue-600">Rese</h1>
  <div class="mt-4">
    <a href="{{ route('admin.index') }}" class="rounded bg-gray-500 px-4 py-2 font-bold text-white">管理画面トップに戻る</a>
  </div>
</div>
</header>
<main>
<div class="container mx-auto mt-8">
  <h2 class="mb-8 text-center text-3xl font-bold">一斉送信メール作成</h2>
  <div class="mx-auto w-full max-w-lg">
    <form action="{{ route('admin.send-email') }}" method="POST">
      @csrf
      <div class="mb-4">
        <label for="subject">件名:</label>
        <input type="text" name="subject" id="subject" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:shadow-outline" required>
      </div>
      <div>
        <label for="message">本文:</label>
        <textarea name="message" id="message" rows="10" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:shadow-outline" required></textarea>
      </div>
      <div class="flex items-center justify-end">
        <button type="submit" class="focus:shadow-outline rounded bg-blue-500 px-4 py-2 font-bold text-white focus:outline-none" onclick="return confirm('送信してもよろしいでしょうか？')">送信</button>
      </div>
    </form>
  </div>
</div>
</main>
</body>
</html>