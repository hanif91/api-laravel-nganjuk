<?php

namespace App\Http\Resources;

use App\Models\TnpDenda;
use App\Services\RekeningServices;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class TagihanResource extends JsonResource
{

    public function toArray(Request $request): array
    {

        $noDenda = TnpDenda::query()->where('no_sam',$this->nosam)->count() > 0;

        return [
            "no_sambungan" => $this->nosam,
            "nama" => $this->nama,
            "alamat" => $this->al,
            "golongan" => $this->tarif,
            "tagihan" => $this->createTagihan($noDenda),

        ];
    }

    private function createTagihan($noDenda) : array
    {
        $tagihan = [];

        foreach ($this->unpaid_rekening as $rek)
        {
            $denda = ($noDenda ? 0 : RekeningServices::hitungDenda($rek->periode));
            $tglPeriode  = Carbon::createFromFormat('Y-m-d',$rek->periode);
            $ppn = $denda * config('settings.ppn');
            $tagihan[] = [
                "periode" => $tglPeriode->translatedFormat('Y-m-d'),
                "periode_name" => $tglPeriode->translatedFormat('M Y'),
                "stan_lalu" => $rek->lama,
                "stan_ini" => $rek->baru,
                "m3" => $rek->m3,
                "harga_air" => $rek->hrgair,
                "dana_meter" => $rek->dm,
                "administrasi" => $rek->adm,
                "total_air" => $rek->tot,
                "materai" => $rek->materai??0,
                "denda" => $denda,
                "ppn" => $ppn,
                "total_tagihan" => $rek->tot + $rek->materai + $denda + $ppn
            ];
        }
        return $tagihan;
    }
}
