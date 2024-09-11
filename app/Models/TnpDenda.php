<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TnpDenda extends Model
{
    use HasFactory;
    protected $table = "tnp_denda";
    protected $connection = 'datasource';
    protected $primaryKey =  null;
    public $incrementing  = false;
    public $timestamps  = false;


}
