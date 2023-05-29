<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
use App\Models\CourseMenu;
use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;
use Aws\S3\S3Client;
use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopOwnerController extends Controller
{
  public function index()
  {
    $shops = Shop::where('user_id', Auth::id())->get();
    return view('owner.index', [
      'shops' => $shops,
    ]);
  }

  public function create()
  {
    $areas = Area::all();
    $genres = Genre::all();
    $shops = Shop::where('user_id', Auth::id())->get();

    return view('owner.create-shop', [
      'areas' => $areas,
      'genres' => $genres,
      'shops' => $shops
    ]);
  }

  public function store(CreateShopRequest $request)
  {
    $shop = new Shop($request->all());

    $shop->user_id = Auth::id();

    if ($request->hasFile('image_url')) {
        $file = $request->file('image_url');
        $path = $file->store('shops', 'public');
        $shop->image_url = $path;

        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $result = $s3->putObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key'    => $path,
            'Body'   => fopen($file->getRealPath(), 'r'),
            'ContentType' => $file->getMimeType(),
            'CacheControl' => 'max-age=31536000',
        ]);

        $shop->image_url = $result['ObjectURL'];
    }

    $shop->save();
    if ($request->has('course_name') && $request->has('course_price') && $request->has('course_description')) {
      $courseNames = $request->input('course_name');
      $coursePrices = $request->input('course_price');
      $courseDescriptions = $request->input('course_description');

    for ($i = 0; $i < count($courseNames); $i++) {
      if (!empty($courseNames[$i]) || !empty($coursePrices[$i]) || !empty($courseDescriptions[$i])) {
        if (empty($courseNames[$i]) || empty($coursePrices[$i]) || empty($courseDescriptions[$i])) {
          return back()->withErrors([
            'course_name' => 'すべてのコースメニュー項目を入力してください',
          ])->withInput();
        } else {
          $courseMenu = new CourseMenu();
          $courseMenu->shop_id = $shop->id;
          $courseMenu->name = $courseNames[$i];
          $courseMenu->price = $coursePrices[$i];
          $courseMenu->description = $courseDescriptions[$i];
          $courseMenu->save();
        }
      }
    }
  }
    return redirect()->route('owner.create-shop')->with('success', '店舗を作成しました');
  }

  public function edit($id)
  {
    $shop = Shop::findOrFail($id);
    $areas = Area::all();
    $genres = Genre::all();
    $courseMenus = $shop->courseMenus ?? [];

    
    return view('owner.edit-shop', [
      'shop' => $shop,
      'areas' => $areas,
      'genres' => $genres,
      'courseMenus' => $courseMenus,
    ]);
  }

  public function update(UpdateShopRequest $request, $id)
  {
    $shop = Shop::findOrFail($id);

    $data = $request->all();

    if ($request->hasFile('image_url')) {
      $file = $request->file('image_url');
      $path = $file->store('shops', 'public');
      $data['image_url'] = $path;

      $s3 = new S3Client([
        'version' => 'latest',
        'region'  => env('AWS_DEFAULT_REGION'),
        'credentials' => [
          'key'    => env('AWS_ACCESS_KEY_ID'),
          'secret' => env('AWS_SECRET_ACCESS_KEY'),
        ],
      ]);

      $result = $s3->putObject([
        'Bucket' => env('AWS_BUCKET'),
        'Key'    => $path,
        'Body'   => fopen($file->getRealPath(), 'r'),
        'ContentType' => $file->getMimeType(),
        'CacheControl' => 'max-age=31536000',
      ]);

      $data['image_url'] = $result['ObjectURL'];
    }

    $shop->update($data);

    $course_names = $request->input('course_name') ?? [];
    $course_prices = $request->input('course_price') ?? [];
    $course_descriptions = $request->input('course_description') ?? [];
    $deletedCourseMenuIds = $request->input('deleted_course_menu_ids', []);

    foreach ($course_names as $index => $course_name) {
      if ($index < count($shop->courseMenus)) {
        $courseMenu = $shop->courseMenus[$index];
        $courseMenu->name = $course_name;
        $courseMenu->price = $course_prices[$index];
        $courseMenu->description = $course_descriptions[$index];
        if ($courseMenu->name !== null && $courseMenu->price !== null && $courseMenu->description !== null) {
          $courseMenu->save();
        }
      } else {
        $courseMenu = new CourseMenu();
        $courseMenu->shop_id = $shop->id;
        $courseMenu->name = $course_name;
        $courseMenu->price = $course_prices[$index];
        $courseMenu->description = $course_descriptions[$index];
        if ($courseMenu->name !== null && $courseMenu->price !== null && $courseMenu->description !== null) {
          $courseMenu->save();
        }
      }
    }

    foreach ($deletedCourseMenuIds as $courseMenuId) {
      CourseMenu::findOrFail($courseMenuId)->delete();
    }    

    return redirect()->route('owner.create-shop')->with('success', '店舗情報を更新しました');
  }

  public function delete(Shop $shop)
  {
    if ($shop->image_url) {
      $s3 = new S3Client([
        'version' => 'latest',
        'region'  => env('AWS_DEFAULT_REGION'),
        'credentials' => [
          'key'    => env('AWS_ACCESS_KEY_ID'),
          'secret' => env('AWS_SECRET_ACCESS_KEY'),
        ],
      ]);

      $key = str_replace(env('AWS_URL') . '/', '', $shop->image_url);

      $result = $s3->deleteObject([
        'Bucket' => env('AWS_BUCKET'),
        'Key'    => $key,
      ]);
    }

    $shop->delete();

    return redirect()->route('owner.create-shop')->with('success', '店舗を削除しました。');
  }

  public function reservationlist()
  {
    $shops = Shop::where('user_id', Auth::id())->get();
    $reservations = Reservation::whereIn('shop_id', $shops->pluck('id'))->with('user', 'course_menu')->get();
    $reservationsByShop = [];

    foreach ($reservations as $reservation) {
      if (!isset($reservationsByShop[$reservation->shop_id])) {
        $reservationsByShop[$reservation->shop_id] = [];
      }
      $reservationsByShop[$reservation->shop_id][] = $reservation;
    }

    return view('owner.reservation-list', [
      'reservations' => $reservationsByShop,
      'shops' => $shops
    ]);
  }

  public function reservationDetail($id)
  {
    $reservation = Reservation::with('user')->findOrFail($id);
    $reservation->start_at = Carbon::parse($reservation->start_at);

    return view('owner.reservation-detail', compact('reservation'));
  }

  public function updateReservation(Request $request, $id)
  {
    $request->validate([
      'num_of_users' => 'required|integer',
      'date' => 'required|date',
      'time' => 'required|date_format:"H:i"',
    ]);

    $reservation = Reservation::find($id);
    $reservation->num_of_users = $request->input('num_of_users');
    $reservation->start_at = Carbon::createFromFormat('Y-m-d H:i', $request->input('date') . ' ' . $request->input('time'));

    $reservation->save();

    return redirect()->route('owner.reservation-list')->with('success', '予約が更新されました。');
  }

  public function deleteReservation($id)
  {
    $reservation = Reservation::find($id);
    if ($reservation) {
      $reservation->delete();
      return response()->json(['success' => '予約が削除されました。'], 200);
    } else {
      return response()->json(['error' => '予約が見つかりませんでした。'], 404);
    }
  }

  public function generateQrCode($id)
  {
    $url = route('owner.reservation-detail', ['id' => $id]);
    $qrCode = QrCode::create($url);
    $pngWriter = new PngWriter();
    $result = $pngWriter->write($qrCode);
    $dataUri = $result->getDataUri();

    return response()->json(['qr_code_url' => $dataUri]);
  }

}
