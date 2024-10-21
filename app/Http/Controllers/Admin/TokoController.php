<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TokoController extends Controller
{
    public function data(): JsonResponse
    {
        $data = DB::table('toko')->select(['cabang', 'kdtk AS kode_toko', 'ip AS ip_induk'])
        ->where('is_induk', true)->get();

        return response()->json([
            'message'   => 'success get data toko',
            'data'      => [
                'toko'  => $data
            ]
        ]);
    }
}
