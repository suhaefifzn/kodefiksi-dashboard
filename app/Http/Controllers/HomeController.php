<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

// ? Service
use App\Services\ProfileService;
use App\Services\ArticleService;

class HomeController extends Controller
{
    public function index() {
        $articleService = new ArticleService();
        $profileService = new ProfileService();
        $articleStats = $articleService->getStats();
        $decodedArticleStatsResponse = $this->decodeJsonResponse($articleStats)['data'];
        $profileUser = $profileService->getMyProfile();
        $decodedProfileResponse = $this->decodeJsonResponse($profileUser)['data'];

        // save some profile data to session
        Session::put('user_img', (config('app.my_config.api_url') . '/' . $decodedProfileResponse['image']));
        Session::put('user_name', $decodedProfileResponse['name']);
        Session::put('user_admin', $decodedProfileResponse['is_admin']);

        return view('dashboard.home.index', [
            'title' => 'Beranda',
            'data' => $decodedArticleStatsResponse
        ]);
    }
}
