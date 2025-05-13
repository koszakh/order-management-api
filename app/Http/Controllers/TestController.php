<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\States\OrderState;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request) {
        return ['a' => 123];
    }
}
