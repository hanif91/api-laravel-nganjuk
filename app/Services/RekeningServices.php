<?php


namespace App\Services;


use Carbon\Carbon;
use Carbon\CarbonImmutable;

class RekeningServices
{

    public static function hitungDenda($periode) : int
    {
        $tglDenda = CarbonImmutable::create(now()->year,now()->month,config('settings.tgl_batas_denda'),23,59,00);
        $tglPeriode  = Carbon::createFromFormat('Y-m-d',$periode);
        $denda = (
            $tglDenda->diffInMonths($tglPeriode) == 1 && now() <= $tglDenda ? 0 : config('settings.denda')
        );

        return $denda;
    }

}
