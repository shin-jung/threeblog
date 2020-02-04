<?php

namespace App\Services;

use App\Repositories\ApiRepository;
use Illuminate\Http\Request;

class ApiService
{
	protected $apiRepository;

	public function __construct(ApiRepository $apiRepository) //
	{
		$this->apiRepository = $apiRepository;
	}

	public function register(Request $request)
	{
		return $this->apiRepository->register($request);
	}
}