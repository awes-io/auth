<?php

namespace AwesIO\Auth\Controllers;

use Illuminate\Http\Request;
use AwesIO\Auth\Controllers\Controller;

class TwoFactorController extends Controller
{
    public function index()
    {
        return view('awesio-auth::twofactor.index');
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
