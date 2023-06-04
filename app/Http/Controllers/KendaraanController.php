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


    public function index(Request $request)
    {
        $per_page = (int)$request->per_page > 0 ? (int)$request->per_page : 0;
        $keyword = !empty($request->keyword) ? strtolower($request->keyword) : '';
        $keyword_column = !empty($request->keyword_column) ? $request->keyword_column : 'tahun_keluaran';
        $sort_column = !empty($request->sort_column) ? $request->sort_column : 'tahun_keluaran';
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
