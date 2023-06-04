<?php

namespace App\Http\Controllers;

use App\Repositories\KendaraanRepository;
use App\Repositories\PenjualanRepository;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends Controller
{
    use ResponseAPI;

    public function __construct(
        PenjualanRepository $penjualanRepository,
        KendaraanRepository $kendaraanRepository
    ) {
        $this->penjualanRepository = $penjualanRepository;
        $this->kendaraanRepository = $kendaraanRepository;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            return $this->success("bad request, error validation", $validator->errors(), 400, count($validator->errors()));
        }
        $where = array('_id' => $request->id_kendaraan);
        $cnt = $this->kendaraanRepository->countData($where);
        if ($cnt == 0) {
            return $this->error("data kendaraan gak ada", 400, 1);
        }
        $kendaraan = $this->kendaraanRepository->find($request->id_kendaraan);

        if ($kendaraan->qty < $request->qty) {
            return $this->error("stok kendaraan kurang untuk di jual", 400, 1);
        }

        $id = $request->_id > 0 ? $request->_id : 0;
        $request->request->add(['total' => $request->qty * $kendaraan->harga]);
        $data = array(
            "qty" => $kendaraan->qty - $request->qty,
        );

        $kendaraanUpdate = $this->kendaraanRepository->updateWhere($where, $data);
        $penjualan = $this->penjualanRepository->CreateOrUpdate($request->input(), $id);

        return $penjualan;
    }


    private function rules()
    {
        return [
            'nama_pembeli' => 'required',
        ];
    }
}
