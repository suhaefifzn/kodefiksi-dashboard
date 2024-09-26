<?php

namespace App\Services;

class UserService extends MyWebService {
    public function __construct() {
        /**
         * Register endpoint and is required access token or not
         */
        parent::__construct('users', true);
    }

    /**
     * To get list users
     *
     * @return mixed
     */
    public function getListUsers() {
        return $this->get('');
    }

    /**
     * To get detail of one user by username
     *
     * @param string $username
     * @return mixed
     */
    public function getDetailUser($username) {
        return $this->get("/$username");
    }

    /**
     * To add new user
     *
     * @param array $payload
     * e.g., [
     *  'name' => 'Member One',
     *  'username' => 'member1',
     *  'email' => 'member1@example.com',
     *  'password' => 'member1password'
     * ]
     *
     * @return mixed
     */
    public function addNewUser($payload) {
        return $this->post('', $payload);
    }

    /**
     * To delete user account
     *
     * @param string $username
     * @return mixed
     */
    public function deleteUser($username) {
        return $this->delete("/$username");
    }

    /**
     * To update user account data
     *
     * @param string $username
     * @param array $payload
     * e.g., [
     *  'name' => 'Member 1 Edit',
     *  'username' => 'member1',
     *  'email' => 'member1edit@example.com'
     * ]
     *
     * @return mixed
     */
    public function editUser($username, $payload) {
        return $this->put("/$username", $payload);
    }

    /**
     * To update user account password
     *
     * @param string $username
     * @param string password
     * @return mixed
     */
    public function editPasswordUser($username, $password) {
        $payload = [
            'new_password' => $password
        ];

        return $this->put("/$username/password", $payload);
    }
}
