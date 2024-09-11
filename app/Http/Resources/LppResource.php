<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LppResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "tgl_bayar" => $this->tglreal,
            "no_sambungan" => $this->customer->nosam,
            "nama" => $this->customer->nama,
            "alamat" => $this->customer->al,
            "golongan" => $this->customer->tarif,
            "periode" => Carbon::createFromFormat('Y-m-d',$this->periode)->translatedFormat('M Y'),
            "m3" => $this->m3,
            "harga_air" => $this->ha,
            "dana_meter" => $this->dm,
            "administrasi" => $this->adm,
            "total_air" => $this->ha + $this->dm + $this->adm,
            "materai" => $this->materai??0,
            "denda" => $this->denda+$this->ppnd,
            "total_tagihan" => $this->ha + $this->dm + $this->adm + $this->materai + $this->denda + $this->ppnd,
            "user" => $this->user,

        ];
    }

}
