<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Repositories\KendaraanRepository;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KendaraanController extends Controller
{
    use ResponseAPI;

    public function __construct(KendaraanRepository $kendaraanRepository)
    {
        $this->kendaraanRepository = $kendaraanRepository;
    }


    public function index()
    {
        $data = Kendaraan::get()->toArray();
        return $this->success("berhasil", $data, 200, 1);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            return $this->success("bad request, error validation", $validator->errors(), 400, count($validator->errors()));
        }
        $id = $request->_id > 0 ? $request->_id : 0;


        $user = $this->kendaraanRepository->CreateOrUpdate($request->input(), $id);

        return $user;
    }

    public function detail()
    {
        # code...
    }

    public function remove(Request $request)
    {
        $id = $request->id > 0 ? $request->id : 0;
        $oldData = $this->kendaraanRepository->find($id);
        $cek = isset($oldData) && (int)$oldData->id > 0 ? 1 : 0;

        if ($cek > 0) {
            $data = $this->kendaraanRepository->delete($id);
            return $this->success("ok", $data, 200, 1);
        } else {
            return $this->success("data gak ada", $oldData, 400, 0);
        }
    }

    private function rules()
    {
        return [
            'tahun_keluaran' => 'required',
            'warna' => 'required',
            'harga' => 'required',
        ];
    }
}
