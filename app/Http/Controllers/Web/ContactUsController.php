<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactUs;
use App\Models\Attribute;

class ContactUsController extends Controller
{
    public function contact() {
        return view('contact');
    }
}
