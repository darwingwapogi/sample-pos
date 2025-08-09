<?php

namespace App\ResourceCollection;

use App\Common\Utility\NameUtil;

class CustomerCollection
{
    public static function filterData($collection)
    {
        return $collection->map(function ($customer) {
            return [
                'id' => $customer->id,
                'full_name' => NameUtil::getLastNameFirst($customer->fname, 
                    $customer->lname, $customer->mname),
                'gender' => $customer->gender,
                'address' => $customer->address,
            ];
        })->toArray();
    }
}
