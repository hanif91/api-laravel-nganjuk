<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function pelangganNotFound()
    {
        return response()->json([
            "data" => null,
            "message" => "pelanggan tidak ditemukan",
        ],404);
    }


    public function tagihanNotFound()
    {
        return response()->json([
            "data" => null,
            "message" => "tagihan tidak ditemukan",
        ],404);
    }

    public function lppNotFound()
    {
        return response()->json([
            "data" => null,
            "message" => "data pembayaran tidak ditemukan",
        ],404);
    }

    public function adaTunggakan()
    {
        return response()->json([
            "data" => null,
            "message" => "pelanggan memiliki tunggakan, hanya bisa membayar di kantor PDAM",
        ],412);
    }

    public function invalid($message = "invalid parameter",$code = 406)
    {
        return response()->json([
            "data" => null,
            "message" => $message,
        ],$code);
    }

    public function paymentSuccess($data)
    {
        return response()->json([
            "data" =>[
                "tanggal" => now()->translatedFormat('l, d F Y H:i:s'),
                "user" => auth()->user()->name,
                "no_sam" => $data['nomor'],
                "periode" => Carbon::createFromFormat('Y-m-d',$data['periode'])->translatedFormat('M Y'),
            ],
            "message" => "Pembayaran Berhasil",
        ]);
    }

    public function excededMaxHari()
    {
        return response()->json([
            "data" => null,
            "message" => "Jumlah hari tidak boleh melebihi ".config('settings.max_hari'). ' Hari',
        ],412);
    }
}
