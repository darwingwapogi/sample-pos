<?php

namespace App\Services;

use App\Models\User;
use Exception;

class UserService extends BaseService {

  public static function create($data) {
    parent::create($data, User::class);
  }

  public static function list(Int $compId): array
  {
    parent::list($compId, User::class);
  }
}