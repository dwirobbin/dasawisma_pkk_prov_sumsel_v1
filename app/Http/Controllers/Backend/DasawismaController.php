<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DasawismaController extends Controller
{
    public function index()
    {
        return view('pages.backend.data-input.dasawismas.index');
    }
}
