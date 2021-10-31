<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private $base_uri = ['base_uri' => 'https://iot.amtiss.com/api/'];
    public function login(Request $request)
    {
        $client = new Client($this->base_uri);
        $get_credentials = $client->request('GET', "login?email={$request->email}&password={$request->password}");
        $credentials = json_decode($get_credentials->getBody()->getContents(), true);
        return $credentials;
    }
}
