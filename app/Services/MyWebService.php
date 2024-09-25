<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Session;

abstract class MyWebService {
    protected $client;
    protected $baseUrl;
    protected $endpoint;
    protected $fullURL;
    protected $clientToken;
    protected $headers = [];
    protected $tokenRequired;

    /**
     * Constructor to initialize the endpoint and token requirement.
     *
     * @param string $endpoint
     * @param bool $tokenRequired
     */
    public function __construct($endpoint, $tokenRequired = false) {
        $this->client = new Client();
        $this->clientToken = config('app.my_config.client_token');
        $this->baseUrl = config('app.my_config.api_url');
        $this->endpoint = $endpoint;
        $this->fullURL = $this->baseUrl . '/' . $this->endpoint;
        $this->tokenRequired = $tokenRequired;
        $this->setHeaders();
    }

    /**
     * Set the default headers, including Authorization if token is required.
     */
    public function setHeaders() {
        $this->headers = [
            'Accept' => 'application/json',
            'Client-Token' => $this->clientToken,
            'Authorization' => 'Bearer ' . $this->getToken()
        ];
    }

    public function getResponse($rawResponse) {
        $decodedResponse = json_decode($rawResponse->getBody()->getContents(), true);
        $response = [
            'message' => isset($decodedResponse['message']) ? $decodedResponse['message'] : null,
            'data' => isset($decodedResponse['data']) ? $decodedResponse['data'] : null
        ];

        if (($rawResponse->getStatusCode() == 200) || ($rawResponse->getStatusCode() == 201)) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'fail';
        }

        return response()->json($response, $rawResponse->getStatusCode());
    }

    /**
     * Get method to perform a GET request.
     *
     * @param string $urlParams
     * @return mixed
     */
    public function get($urlParams = '') {
        $fullURL = $this->fullURL . $urlParams;

        try {
            $response = $this->client->request('GET', $fullURL, [
                'headers' => $this->headers,
            ]);
            return $this->getResponse($response);
        } catch (RequestException $e) {
            return $this->getResponse($e->getResponse());
        }
    }

    /**
     * Post method to perform a POST request.
     *
     * @param string $urlParams
     * @param array $payload
     * @return mixed
     */
    public function post($urlParams = '', $payload = []) {
        $fullURL = $this->fullURL. $urlParams;

        if ($this->endpoint === 'authentications') {
            unset($this->headers['Authorization']);
        }

        try {
            $response = $this->client->request('POST', $fullURL, [
                'headers' => $this->headers,
                'json' => $payload,
            ]);
            return $this->getResponse($response);
        } catch (RequestException $e) {
            return $this->getResponse($e->getResponse());
        }
    }

    /**
     * Post method to send text and file using multipart form data.
     *
     * @param string $method
     * @param string $urlParams
     * @param array $payload
     * @param string $filePath
     * @param string $fileName
     * @return mixed
     */
    public function postOrPutWithFile($method = 'POST', $urlParams = '', $payload = [], $filePath, $fileName = 'file') {
        $fullURL = $this->fullURL . $urlParams;

        try {
            $multipartData = [];

            if (count($payload) > 0) {
                foreach ($payload as $key => $value) {
                    $multipartData[] = [
                        'name' => $key,
                        'contents' => $value,
                    ];
                }
            }

            $multipartData[] = [
                'name' => $fileName,
                'contents' => fopen($filePath, 'r'),
                'filename' => basename($filePath),
            ];

            $response = $this->client->request($method, $fullURL, [
                'headers' => $this->headers,
                'multipart' => $multipartData,
            ]);

            return $this->getResponse($response);
        } catch (RequestException $e) {
            return $this->getResponse($e->getResponse());
        }
    }

    /**
     * Put method to perform a PUT request.
     *
     * @param string $urlParams
     * @param array $payload
     * @return mixed
     */
    public function put($urlParams = '', $payload = []) {
        $fullURL = $this->fullURL . $urlParams;

        try {
            $response = $this->client->request('PUT', $fullURL, [
                'headers' => $this->headers,
                'json' => $payload,
            ]);
            return $this->getResponse($response);
        } catch (RequestException $e) {
            return $this->getResponse($e->getResponse());
        }
    }

    /**
     * Delete method to perform a DELETE request.
     *
     * @return $urlParams
     * @return mixed
     */
    public function delete($urlParams = '') {
        $fullURL = $this->fullURL . $urlParams;

        try {
            $response = $this->client->request('DELETE', $fullURL, [
                'headers' => $this->headers,
            ]);
            return $this->getResponse($response);
        } catch (RequestException $e) {
            return $this->getResponse($e->getResponse());
        }
    }

    /**
     * Get the authorization token if required.
     *
     * @return string|null
     */
    private function getToken() {
        // Fetch the token from a secure source or configuration
        return Session::get('access_token');
    }
}
