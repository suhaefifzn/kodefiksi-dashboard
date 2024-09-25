<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

// ? Service
use App\Services\ProfileService;

class ProfilController extends Controller
{
    private $profileService;

    public function __construct() {
        $this->profileService = new ProfileService();
    }

    public function index() {
        $response = $this->profileService->getMyProfile();
        $decodedResponse = $this->decodeJsonResponse($response);

        return view('dashboard.profil.index', [
            'title' => 'Profil',
            'data' => $decodedResponse['data']
        ]);
    }

    public function updateProfileData(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|string'
        ]);

        $response = $this->profileService->editMyProfileData([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ]);

        return $response;
    }

    public function updatePasswordAccount(Request $request) {
        $request->validate([
            'new_password' => 'required|string',
            'confirm_new_password' => 'required|same:new_password|string',
            'old_password' => 'required|string'
        ]);

        $response = $this->profileService->editMyPassword([
            'new_password' => $request->new_password,
            'confirm_new_password' => $request->confirm_new_password,
            'old_password' => $request->old_password
        ]);

        return $response;
    }

    public function updateImage(Request $request) {
        $request->validate([
            'image' => 'required|image|mimes:jpg,png|max:2048'
        ]);

        $file = $request->file('image');
        $response = $this->profileService->editMyProfileImage($file);
        $decodedResponse = $this->decodeJsonResponse($response);

        if ($decodedResponse['status'] === 'success') {
            Session::put('user_img', $decodedResponse['data']['image_url']);
            return redirect()->back();
        }

        return redirect()->back()->with('error_image', $decodedResponse['message']);
    }
}
