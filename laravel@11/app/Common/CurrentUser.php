<?php
// Author: DarCasanova
// Date: 03/08/2025
// Time: 4:32pm
// Description: Store logged in user to Cache for fast accessing of data
// Note: You can add as many functions as you want if needed

namespace App\Common;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Facades\JWTAuth;

class CurrentUser {

  private $user = null;
  private $dbUser = null;

  public function __construct() {
      $jwtUser = \Cookie::get('jwt-user');

      if ($jwtUser) {
        $payLoad = JWTAuth::setToken($jwtUser)->getPayload();
    
        $this->user = $payLoad['user'] ?? null;
      }

      if (is_null(value: $this->user)) {
          throw new \Exception('User is not logged in.');
      }

      $email = $this->user['email'];

      $this->dbUser = Cache::remember('user_'.$email, now()->addMinutes(60), function() use ($email) {
          return User::with('userType')
            ->where('email', $email)
            ->where('isActive', true)
            ->first();
      });

      if (is_null($this->dbUser)) {
          throw new \Exception('User with id: ' . $this->user['email'] . ' not found in the database or not isActive.');
      } else {
          if (!$this->dbUser->isActive) {
              $this->dbUser->isActive = true;
          }
      }

  }
  
  public function getUser() {
      return $this->dbUser;
  }

  public function isAdmin() {
      return $this->dbUser->user_type->name == 'admin';
  }

  public function isActive() {
      return $this->dbUser->isActive;
  }
  
  public function getEmail() {
      return $this->dbUser->email;
  }

  public function getId() {
      return $this->dbUser->id;
  }

  public function getCompanyId() {
      return $this->dbUser->company_id;
  }

}
