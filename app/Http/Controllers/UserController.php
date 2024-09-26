<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function index() {
        return view('dashboard.users.index', [
            'title' => 'Pengguna'
        ]);
    }

    public function dataTable(Request $request) {
        if ($request->ajax()) {
            $response = $this->userService->getListUsers();
            $decodedResponse = $this->decodeJsonResponse($response);
            $users = $decodedResponse['data']['users'];

            return DataTables::of($users)
                ->addColumn('action', function($row){
                    $editBtn = '<button class="badge badge-secondary border-0" title="Edit" onclick="editUser(this)" data-username="'. $row['username'] .'"><i class="fa fa-pencil"></i></button>';
                    $deleteBtn = '<button class="badge badge-danger border-0" title="Delete" onclick="deleteUser(this)"
                        data-token="'. csrf_token() .'" data-username="'. $row['username'] .'"><i class="fa fa-times"></i></button>';
                    $editPasswordBtn = '<button class="badge badge-dark border-0" title="Change Password" onclick="editPassword(this)" data-username="'. $row['username'] .'"><i class="fa fa-key"></i></button>';
                    $wrapper = '<div class="d-flex gap-2">' . $editBtn . $editPasswordBtn . $deleteBtn . '</div>';

                    return $wrapper;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getProfile($username) {
        if (Str::length($username) > 1) {
            $response = $this->userService->getDetailUser($username);
            return $response;
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'Username was not found'
        ], 404);
    }

    public function changeProfile(Request $request) {
        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ];
        $response = $this->userService->editUser($request->_username, $data);
        return $response;
    }

    public function delete(Request $request) {
        $request->validate([
            'username' => 'required|string'
        ]);
        $response = $this->userService->deleteUser($request->username);
        return $response;
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|min:5|max:16|regex:/^\S*$/u',
            'email' => 'required|email',
            'password' => 'required|string|min:8|max:64|regex:/^\S*$/u'
        ]);

        $data = $request->all();
        unset($data['_token']);
        unset($data['_method']);
        $response = $this->userService->addNewUser($data);

        return $response;
    }

    public function changePassword(Request $request) {
        $response = $this->userService->editPasswordUser($request->username, $request->password);
        return $response;
    }
}
