<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends BaseModel
{
    use HasFactory;

    static $fillableColumn = [
        'fname',
        'mname',
        'lname',
        'gender',
        'birth_date',
        'address',
        'email',
        'primary_contact',
        'contact',
        'encoder',
        'company_id',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    // Mutators to ensure fname, mname, and lname are stored in lowercase
    public function setFnameAttribute($value)
    {
        $this->attributes['fname'] = $value ? strtolower($value) : null;
    }

    public function setMnameAttribute($value)
    {
        $this->attributes['mname'] = $value ? strtolower($value) : null;
    }

    public function setLnameAttribute($value)
    {
        $this->attributes['lname'] = $value ? strtolower($value) : null;
    }

    // Accessors to ensure fname, mname, and lname are retrieved in lowercase
    public function getFnameAttribute($value)
    {
        return strtolower($value);
    }

    public function getMnameAttribute($value)
    {
        return strtolower($value);
    }

    public function getLnameAttribute($value)
    {
        return strtolower($value);
    }
}
