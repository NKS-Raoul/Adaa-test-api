<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    //
    public function __invoke(): array
    {
        return [
            'success' => true,
            'message' => "Hello world"
        ];
    }
}
