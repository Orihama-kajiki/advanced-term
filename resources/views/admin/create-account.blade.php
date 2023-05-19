<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
<title>店舗代表者作成画面</title>
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
	<h2 class="mb-8 text-center text-3xl font-bold">店舗責任者アカウント作成</h2>
	<div class="mx-auto w-full max-w-lg">
		<div class="mb-4 rounded bg-white px-8 pb-8 pt-6 shadow-md">
			<form action="{{ route('admin.store-account') }}" method="POST" novalidate>
				@csrf
				<div class="form-group mb-4">
					<label for="name" class="mb-2 block text-base font-bold text-gray-700">名前 <span class="text-red-500">必須</span></label>
					<input type="text" name="name" id="name" class="form-control focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"/>
					@error('name')
						<div class="text-red-500">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group mb-4">
					<label for="email" class="mb-2 block text-base font-bold text-gray-700">メールアドレス <span class="text-red-500">必須</span></label>
					<input type="email" name="email" id="email" class="form-control focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"/>
					@error('email')
						<div class="text-red-500">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group mb-4">
					<label for="password" class="mb-2 block text-base font-bold text-gray-700">パスワード <span class="text-red-500">必須</span></label>
					<input type="password" name="password" id="password" class="form-control focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none" autocomplete="new-password"/>
					@error('password')
						<div class="text-red-500">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group mb-6">
					<label for="password_confirmation" class="mb-2 block text-base font-bold text-gray-700">パスワード確認 <span class="text-red-500">必須</span></label>
					<input type="password" name="password_confirmation" id="password_confirmation" class="form-control focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"/>
					@error('password_confirmation')
						<div class="text-red-500">{{ $message }}</div>
					@enderror
				</div>
				<div class="flex items-center justify-end">
					<button type="submit" class="focus:shadow-outline rounded bg-blue-500 px-4 py-2 font-bold text-white focus:outline-none" onclick="return confirm('作成してもよろしいでしょうか？')">アカウント作成</button>
				</div>
			</form>
		</div>
	</div>
</div>
</main>
</body>
</html>