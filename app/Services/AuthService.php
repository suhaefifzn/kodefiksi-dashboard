<?php

namespace App\Services;

class AuthService extends MyWebService {
    public function __construct() {
        /**
         * Register endpoint and is required access token or not
         */
        parent::__construct('authentications', true);
    }

    /**
     * To generate access token (JWT) or login
     *
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public function generateAccessToken($email, $password) {
        $payload = [
            'email' => $email,
            'password' => $password
        ];

        return $this->post('', $payload);
    }

    /**
     * To logout or invalidate access token
     */
    public function deleteAccessToken() {
        return $this->delete();
    }

    public function checkToken() {
        return $this->get('/check');
    }
}
