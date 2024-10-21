<?php

namespace App\Http\Controllers\Sql;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Database\ConnectionController;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QueryController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'syntax'    => ['required', 'string'],
            'dbName'    => ['nullable', 'string']
        ]);

        if($validate->fails()){
            throw new HttpResponseException(response([
                'errors'   => $validate->errors()
            ], 422));
        }

        try {
            $data = DB::select("USE $request->dbName");
        } catch (\Throwable $th) {
            throw new HttpResponseException(response([
                'errors'   => $th->getMessage()
            ], 422));
        }

        try {
            $data = DB::select($request->syntax);
        } catch (\Throwable $th) {
            throw new HttpResponseException(response([
                'errors'   => $th->getMessage()
            ], 422));
        }

        return response()->json([
            'message'   => 'success query',
            'data'      => [
                'syntax'     => $request->syntax,
                'result'    => $data
            ]
        ]);
    }

    public function queryToko(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'syntax'    => ['required', 'string'],
            'kodeToko' => ['required', 'string', 'max:8'],
            'dbName'    => ['nullable', 'string', 'max:50'],
        ]);

        if($validate->fails()){
            throw new HttpResponseException(response([
                'errors'   => $validate->errors()
            ], 422));
        }

        $toko = Cache::remember("toko_{$request->kodeToko}", 3600, function () use ($request) {
            return DB::table('toko')
                ->where('kdtk', $request->kodeToko)
                ->where('is_induk', true)
                ->select(['kdtk', 'ip'])
                ->first();
        });

        if(!$toko){
            throw new HttpResponseException(response([
                'errors'   => "store code $request->kodeToko not found"
            ], 422));
        }

        $dbName = $request->dbName ? $request->dbName : 'pos';
        $query = explode(';', $request->syntax);
        $query = array_filter($query);

        try {
            $mysql = new ConnectionController($toko->ip);
            $conn = $mysql->getConnection();
            $conn->select("USE $dbName");

            $result = [];
            foreach ($query as $value) {
                // Eksekusi query dan simpan hasilnya
                $queryResult = $conn->select($value);

                // Jika hasil query tidak kosong, tambahkan ke $result
                if (!empty($queryResult)) {
                    $result[] = $queryResult;
                }
            }

            if(empty($result)){
                $result = 'ok';
            }

        } catch (\Throwable $th) {
            throw new HttpResponseException(response([
                'errors'   => $th->getMessage()
            ], 422));
        }

        return response()->json([
            'message'   => 'success query',
            'data'      => [
                'syntax'    => $query,
                'result'    => $result,
            ]
        ]);
    }
}
