<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AdminEmail;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
  public function index()
  {
    return view('admin.index');
  }

  public function create()
  {
    return view('admin.create-account');
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    $role = Role::findByName('店舗責任者');
    $user->assignRole($role);
    
    return redirect()->route('admin.index')->with('success', '店舗責任者アカウントが作成されました');
  }

  public function createEmail()
  {
    return view('admin.create-email');
  }

  public function sendEmail(Request $request)
  {
    $request->validate([
      'subject' => 'required',
      'message' => 'required',
    ]);

    $subject = $request->input('subject');
    $message = $request->input('message');

    $users = User::all();

    foreach ($users as $user) {
      $user->notify(new AdminEmail($subject, $message));
    }

    return redirect()->back()->with('success', 'メールが送信されました');
  }

}
