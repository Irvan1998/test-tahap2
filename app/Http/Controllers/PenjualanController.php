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


    public function index(Request $request)
    {
        $per_page = (int)$request->per_page > 0 ? (int)$request->per_page : 0;
        $keyword = !empty($request->keyword) ? strtolower($request->keyword) : '';
        $keyword_column = !empty($request->keyword_column) ? $request->keyword_column : 'nama_pembeli';
        $sort_column = !empty($request->sort_column) ? $request->sort_column : 'nama_pembeli';
        $sort_order = !empty($request->sort_order) ? $request->sort_order : 'ASC';
        $page_number = (int)$request->page_number > 0 ? (int)$request->page_number : 1;

        $where = array();

        $count = $this->kendaraanRepository->countData($where);

        $data = [];
        if ($count > 0) {
            if (!empty($keyword)) {
                $per_page = $per_page > 0 ? $per_page : $count;
                $offset = ($page_number - 1) * $per_page;
                $data = $this->kendaraanRepository->searchData($where, (int)$per_page, (int)$offset, $sort_column, $sort_order, $keyword_column, $keyword);
            } else {
                $per_page = $per_page > 0 ? $per_page : $count;
                $offset = ($page_number - 1) * $per_page;
                $data = $this->kendaraanRepository->getAllData($where, (int)$per_page, (int)$offset, $sort_column, $sort_order);
            }
        }

        return $this->success("berhasil", $data, 200, 1);
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
