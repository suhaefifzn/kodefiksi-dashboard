<?php

namespace App\Services;

class CategoryService extends MyWebService {
    public function __construct() {
        /**
         * Register endpoint and is required access token or not
         */
        parent::__construct('categories', true);
    }

    /**
     * To get list of categories for all user
     *
     * @return mixed
     */
    public function getListCategories() {
        return $this->get('');
    }

    /**
     * To get detail of one category for all user
     *
     * @param string $slug
     * @return mixed
     */
    public function getOneCategory($slug) {
        return $this->get("/$slug");
    }

    /**
     * To add new category by admin
     *
     * @param string $name
     * @return mixed
     */
    public function addNewCategory($name) {
        $payload = [
            'name' => $name
        ];

        return $this->post('', $payload);
    }

    /**
     * To edit category by admin
     *
     * @param string $slug
     * @param string $newCategoryName
     * @return mixed
     */
    public function editCategory($slug, $newCategoryName) {
        $payload = [
            'name' => $newCategoryName
        ];

        return $this->put("/$slug", $payload);
    }

    /**
     * To delete category by admin
     *
     * @param string slug
     * @return mixed
     */
    public function deleteCategory($slug) {
        return $this->delete("/$slug");
    }
}
