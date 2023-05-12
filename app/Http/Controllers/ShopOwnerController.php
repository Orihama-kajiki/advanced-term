<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
use App\Http\Requests\CreateShopRequest;
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

        return redirect()->route('owner.index');
    }

    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        $areas = Area::all();
        $genres = Genre::all();

        return view('owner.edit-shop', [
            'shop' => $shop,
            'areas' => $areas,
            'genres' => $genres
        ]);
    }

    public function update(CreateShopRequest $request, $id)
    {
        $shop = Shop::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:191',
            'area_id' => 'required|integer|exists:areas,id',
            'genre_id' => 'required|integer|exists:genres,id',
            'description' => 'required|string',
            'image_url' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

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

        return redirect()->route('owner.index');
    }

    public function destroy(Shop $shop)
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

        return redirect()->route('owner.index')->with('success', '店舗を削除しました。');
    }

    public function reservationlist()
    {
        $shops = Shop::where('user_id', Auth::id())->get();
        $reservations = Reservation::whereIn('shop_id', $shops->pluck('id'))->with('user')->get();

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
            'hour' => 'required|integer|min:0|max:23',
            'minute' => 'required|integer|min:0|max:59',
        ]);

        $reservation = Reservation::find($id);
        $reservation->num_of_users = $request->input('num_of_users');
        $reservation->start_at = Carbon::createFromFormat('Y-m-d H:i', $request->input('date') . ' ' . $request->input('hour') . ':' . $request->input('minute'));
        $reservation->save();

        return redirect()->route('owner.reservation-list')->with('success', '予約が更新されました。');
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
