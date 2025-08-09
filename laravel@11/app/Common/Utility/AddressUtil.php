<?php
// Author: DarCasanova
// Date: 04/03/2025
// Time: 8:35pm

namespace App\Common\Utility;

class AddressUtil
{
  /**
   * Format the address in "City, Province, Country" format.
   * Each first character in column is upper case.
   *
   * @param string|null $city
   * @param string|null $province
   * @param string|null $country
   * @return string
   */
  public static function formatAddress(?string $city, ?string $province, ?string $country): string
  {
    $parts = array_filter([
      $city ? ucfirst($city) : null,
      $province ? ucfirst($province) : null,
      $country ? ucfirst($country) : null,
    ]);

    return implode(', ', $parts);
  }
}
