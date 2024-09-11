<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lppa extends Model
{
    use HasFactory;
    protected $table = 'lppa';
    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;
    protected $connection = 'datasource';

    public function customer ()
    {
        return $this->belongsTo(Customer::class,'no_sam','nosam');
    }

}
