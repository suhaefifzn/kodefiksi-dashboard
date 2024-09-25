<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function decodeJsonResponse($responseFromService) {
        return $responseFromService->getData('data');
    }
}
