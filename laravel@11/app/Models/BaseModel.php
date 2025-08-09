<?php

namespace App\Models;
// Author: DarCasanova
// Date: 03/09/2025
// Time: 10:06pm
// Description: used for creating common methods usable for all models
// Note: You can add as many functions if needed

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
  /* use this company id 
  * for testing in local or UAT 
  */
  public static $companyId = 1000;
  
  protected static function getCurrentSequence($seq) {
    return DB::selectOne("SELECT last_value FROM $seq")->last_value;
  }

  protected static function resetSequence($seq, $val): void
  {
    DB::statement("SELECT setval('$seq', $val)");
  }
}