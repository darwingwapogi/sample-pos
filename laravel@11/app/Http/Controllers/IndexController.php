<?php

namespace App\Http\Controllers;

use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Facades\JWTAuth;

class IndexController extends BaseController
{
    public function index() {
        $tokenizedUser = [];
        $validUser = false;
        $salesRepAccnt = false;
        $token = isset($_COOKIE['jwt-user']) ? $_COOKIE['jwt-user'] : null;

        if (!empty($token)) {
            if (!empty($token)) {
            try {
                $tokenizedUser = JWTAuth::setToken($token)->authenticate();
                if ($tokenizedUser instanceof User && $tokenizedUser->exists()) {
                    $validUser = true;
    
                        if ($tokenizedUser->isSalesRep()) {
                            $salesRepAccnt = true;
                        }
                    }
                } catch (TokenBlacklistedException $e) {
                    $tokenizedUser = null;
            } catch (JWTException $e) {
                    $tokenizedUser = null;
                }
            }
        }

        return view('app', [
            'isValidJWT' => $validUser,
            'isSalesRep' => $salesRepAccnt
        ]);
    }
}
