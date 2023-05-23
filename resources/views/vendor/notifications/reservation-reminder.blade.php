<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>予約リマインダー</title>
</head>
<body>
  <div style="max-width: 600px; margin: auto; border: 1px solid #cccccc; padding: 20px; text-align: center;">
    <h1>予約リマインダー</h1>
    <img src="{{ asset('img/rese.png') }}" alt="Rese Logo"> 
    <p>予約者名: {{ $reservation->user->name }}</p>
    <p>店舗名: {{ $reservation->shop->name }}</p>
    <p>予約日時: {{ \Carbon\Carbon::parse($reservation->start_at)->format('Y年m月d日 H:i') }}</p>
    <p>人数: {{ $reservation->num_of_users }} 人</p>
    <p>ご予約の確認をお願いいたします。</p>
    <p><a href="{{ url('/') }}">Reseへ</a></p>
  </div>
</body>
</html>
