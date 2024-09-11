<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = "customer";
    public $timestamps = false;
    protected $connection = 'datasource';


    public function rekening()
    {
        return $this->hasMany(HistoryByr::class,'no_sam','nosam');
    }

    public function unpaid_rekening()
    {
        return $this->hasMany(HistoryByr::class,'no_sam','nosam')
            ->whereNull('tgl_byr')
            ->where('periode','<',now()->format('Y-m-01'));
    }
}
