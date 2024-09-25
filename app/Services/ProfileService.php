<?php

namespace App\Services;

class ProfileService extends MyWebService {
    public function __construct() {
        /**
         * Register endpoint and is required access token or not
         */
        parent::__construct('users', true);
    }

    /**
     * To get profile account
     *
     * @return mixed
     */
    public function getMyProfile() {
        return $this->get('/my/profile');
    }

    /**
     * To update profile data
     *
     * @param array $payload
     * e.g., [
     *  'name' => 'Suhaefi',
     *  'username' => 'suhaefifzn',
     *  'email' => 'suhaefi@example.com'
     * ]
     *
     * @return mixed
     */
    public function editMyProfileData($payload) {
        return $this->put('/my/profile', $payload);
    }

    /**
     * To update password account
     *
     * @param array $payload
     * e.g., [
     *  'old_password' => 'youroldpassword',
     *  'new_password' => 'yournewpassword',
     *  'confirm_new_password' => 're_type_yournewpassword'
     * ]
     *
     * @return mixed
     */
    public function editMyPassword($payload) {
        return $this->put('/my/password', $payload);
    }

    /**
     * To update profile image
     *
     * @param mixed $filePath
     */
    public function editMyProfileImage($filePath) {
        return $this->postOrPutWithFile('POST', '/my/image', [], $filePath, 'image');
    }
}
