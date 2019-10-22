<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class VerificationCodesController extends Controller
{
    public function getCode()
    {
        return $this->response()->array(['data' => app()->environment()]);
    }
}
