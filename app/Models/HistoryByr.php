<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryByr extends Model
{
    use HasFactory;
    protected $table = "histori_byr";
    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;
    protected $connection = 'datasource';


    public function customer ()
    {
        return $this->belongsTo(Customer::class,'no_sam','nosam');
    }

    public function tarifGolongan()
    {
        return $this->belongsTo(Tarif::class,'gol','tarif');
    }


}
