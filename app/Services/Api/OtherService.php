<?php

namespace App\Services\Api;

class OtherService
{
    public function getApiUrl($fullUrl)
    {
        $pattern = '/(^api\/)/';
        $apiUrl = preg_replace($pattern, '', $fullUrl);

        return $apiUrl;
    }
}
