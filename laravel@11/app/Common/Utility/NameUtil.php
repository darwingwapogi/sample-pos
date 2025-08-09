<?php
// Author: DarCasanova
// Date: 04/03/2025
// Time: 8:35pm

namespace App\Common\Utility;

class NameUtil
{
  /**
   * Get the full name in "Last Name, First Name, Middle Name" format.
   * Each first character in column is upper case
   *
   * @param string $fName
   * @param string $mName
   * @param string $lName
   * @return string
   */
  public static function getLastNameFirst(string $fName, ?string $lName = null, ?string $mName = null): string
  {
    if (!empty($fName) && empty($mName) && empty($lName)) {
      return ucfirst($fName);
    }

    return ucfirst($lName) . ', ' . ucfirst($fName) . ' ' . ucfirst($mName);
  }
}