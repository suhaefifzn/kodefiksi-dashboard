<?php

namespace App\Services;

class ArticleService extends MyWebService {
    public function __construct() {
        /**
         * Register endpoint and is required access token or not
         */
        parent::__construct('articles', true);
    }

    /**
     * To get list articles
     *
     * @param boolean $isDraft
     * @return mixed
     */
    public function getListArticles($isDraft) {
        $urlParams = '?is_draft=' . $isDraft;
        return $this->get($urlParams);
    }

    /**
     * To get detail of one article
     *
     * @param string $slug
     * @return mixed
     */
    public function getOneArticle($slug) {
        return $this->get("/$slug");
    }

    /**
     * To delete article
     *
     * @param string slug
     * @return mixed
     */
    public function deleteArticle($slug) {
        return $this->delete("/$slug");
    }

    /**
     * To generate string slug from title
     *
     * @param string $title
     * @return mixed
     */
    public function generateSlug($title) {
        $payload = [
            'title' => $title
        ];

        return $this->post('/generate-slug', $payload);
    }

    /**
     * To add new article
     *
     * @param array $payload
     * e.g., [
     *  'category_id' => 1,
     *  'title' => 'New Article About Anime',
     *  'is_draft' => false,
     *  'body' => 'Your article paragraphs',
     * ]
     * @param $imageThumbnail
     * @return mixed
     */
    public function addArticle($payload, $imageThumbnail) {
        return $this->postOrPutWithFile('POST', '', $payload, $imageThumbnail, 'img_thumbnail');
    }

    /**
     * To edit article
     *
     * @param string $slug
     * @param array $payload
     * e.g., [
     *  'category_id' => 1,
     *  'title' => 'Edit Article About Anime',
     *  'is_draft' => true,
     *  'body' => 'Your edited article paragraphs',
     * ]
     * @param $imageThumbnail
     * @return mixed
     */
    public function editArticle($slug, $payload, $imageThumbnail) {
        $payload['_method'] = 'PUT';
        return $this->postOrPutWithFile('POST', "/$slug", $payload, $imageThumbnail, 'img_thumbnail');
    }

    /**
     * To get list of uploaded body images
     *
     * @return mixed
     */
    public function getBodyImages() {
        return $this->get('/images');
    }

    /**
     * To upload article body image
     *
     * @param $image
     * @return mixed
     */
    public function addBodyImage($image) {
        return $this->postOrPutWithFile('POST', '/upload-image', [], $image, 'image');
    }

    public function getStats() {
        return $this->get('/stats');
    }

    public function generateSlugByTilte(string $title) {
        return $this->post('/generate-slug', [
            'title' => $title
        ]);
    }

    /**
     * To get list of slugs that available for sitemap
     *
     * @return mixed
     */
    public function getSlugs() {
        return $this->get('/slugs');
    }
}
