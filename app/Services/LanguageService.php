<?php

namespace App\Services;

class LanguageService extends MyWebService {
    public function __construct() {
        /**
         * Register endpoint and is required access token or not
         */
        parent::__construct('languages', true);
    }

    /**
     * To get all languages
     *
     * @return mixed
     */
    public function getLanguages() {
        return $this->get('');
    }
}
