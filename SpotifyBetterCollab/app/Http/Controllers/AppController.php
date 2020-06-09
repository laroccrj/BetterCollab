<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller;
use function view;


class AppController extends Controller
{
    public function index()
    {
        return view('index');
    }
}
