<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetLppByNomorRequest;
use App\Http\Requests\GetLppRequest;
use App\Http\Requests\GetTagihanRequest;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\LppResource;
use App\Http\Resources\TagihanResource;
use App\Models\Customer;
use App\Models\HistoryByr;
use App\Models\Lppa;
use App\Models\TnpDenda;
use App\Services\LogPaymentServices;
use App\Services\RekeningServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class ApiController extends Controller
{

    public function index () {

        return response()->json([
            "name" => "Api Endpoint PDAM Nganjuk",
            "version" => "v1"
        ]);
    }


    public function getTagihan(GetTagihanRequest $request)
    {
        $customer = Customer::query()
            ->with('unpaid_rekening')
            ->where('nosam',$request->nomor)
            ->first();


        $periodeBerjalan = now()->subMonths(1)->format('Y-m-01');

        if(is_null($customer))
            return $this->pelangganNotFound();

        if($customer->unpaid_rekening->count()==0)
            return $this->tagihanNotFound();

        if($customer->unpaid_rekening->count()>1)
            return $this->adaTunggakan();

        if($customer->unpaid_rekening->where('periode','<',$periodeBerjalan)->count()>0)
            return $this->adaTunggakan();

        if($customer->unpaid_rekening->count()==1 &&
            $customer->unpaid_rekening->where('periode',$periodeBerjalan)->count()>0)
        {
            Cache::put($request->nomor.time(),["nomor"=>$request->nomor,"periode"=>$periodeBerjalan,'uid'=>auth()->id()],now()->addMinutes(config('settings.menit_cache')));
            return TagihanResource::make($customer)->additional(["message"=>"success","request_id"=>$request->nomor.time()]);
        }

        return $this->invalid();
    }


    public function bayarTagihan(PaymentRequest $request)
    {

        if(!cache()->has($request->request_id))
            return $this->invalid("Request tidak valid");


        $data = cache($request->request_id);

        if($data["uid"]== auth()->id())
            $this->invalid('Invalid user token request.');

        if(!$this->cekTagihan($data['nomor'],$data['periode']))
            return $this->invalid('Data Request tidak valid.');


        $tagihan = HistoryByr::query()
            ->with(['customer','tarifGolongan'])
            ->where(['periode'=>$data['periode'],"no_sam"=>$data['nomor']]);


        if(is_null($tagihan->first()))
            return $this->tagihanNotFound();

        try {
            DB::transaction(function () use ($tagihan,$request,$data){
                $noDenda = TnpDenda::query()
                        ->where('no_sam',$data['nomor'])
                        ->count() > 0;

                $tagihan->update([
                    "user" => auth()->user()->name,
                    "tgl_byr" => now()->format('Y-m-d'),
                    "jam" => now()->format("H:i:s"),
                    "kas" => "PPOB",
                    "denda" => ($noDenda ? 0 : RekeningServices::hitungDenda($data['periode'])),

                ]);

                $tagihan = $tagihan->first();
                Lppa::query()->create([
                    "tgl_byr" => now()->format('Y-m-d'),
                    "periode" => $tagihan->periode,
                    "norek" => $tagihan->norek,
                    "no_sam" => $tagihan->no_sam,
                    "nama" => $tagihan->customer->nama,
                    "kec" => $tagihan->customer->kec??'',
                    "singk" => $tagihan->tarifGolongan->tarif,
                    "tarif" => $tagihan->tarifGolongan->golongan,
                    "m3" => $tagihan->m3,
                    "ha" => $tagihan->hrgair,
                    "dm" => $tagihan->dm,
                    "adm" => $tagihan->adm,
                    "denda" => $tagihan->denda,
                    "meterai" => $tagihan->materai??0,
                    "user" => auth()->user()->name,
                    "lkt" => "PPOB",
                    "tglreal" => now(),
                    "ppn" => 0,
                    "na" => 0,
                    "angs"=>0,
                    "ppnd" => $tagihan->denda*config('settings.ppn'),
                    "bk" => 0,
                ]);

                LogPaymentServices::createLog($request,"success","Logged Successfully");
//                throw_if(!$logging,new \HttpException("failed to log transactions"));
            });

            cache()->forget($request->request_id);
            return $this->paymentSuccess($data);
        }catch (\Exception $e){
            LogPaymentServices::createLog($request,"error",$e->getMessage());
            return $this->invalid($e->getMessage());
        }

    }

    public function getLpp(GetLppRequest $request)
    {
        $selisih_hari = Carbon::createFromFormat('Y-m-d',$request->start)
            ->diffInDays(Carbon::createFromFormat('Y-m-d',$request->end));

        if($selisih_hari>=config('settings.max_hari'))
            return $this->excededMaxHari();

        $data = Lppa::query()
            ->with('customer')
            ->whereDate('tgl_byr','>=',$request->start)
            ->whereDate('tgl_byr','<=',$request->end)
            ->where('user',auth()->user()->name)
            ->get();

        return LppResource::collection($data)->additional(["message"=>"success"]);
    }

    public function getLppByNomor(GetLppByNomorRequest $request)
    {
        $data = Lppa::query()
            ->with('customer')
            ->whereDate('tgl_byr',$request->tanggal)
            ->where(['user'=>auth()->user()->name,'no_sam'=>$request->nomor])
            ->get();

        if($data->count()==0)
            return $this->lppNotFound();

        return LppResource::collection($data)->additional(["message"=>"success"]);
    }

    private function cekTagihan($nomor,$periode)
    {
        $customer = Customer::query()
            ->with('unpaid_rekening')
            ->where('nosam',$nomor)
            ->first();

        if($customer->unpaid_rekening->count()==1 &&
            $customer->unpaid_rekening->where('periode',$periode)->count()>0)
            return true;

        return false;
    }



}
