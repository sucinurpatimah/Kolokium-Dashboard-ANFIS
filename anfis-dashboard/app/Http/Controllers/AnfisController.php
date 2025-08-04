<?php

namespace App\Http\Controllers;

use App\Models\AnfisResult;
use Illuminate\Http\Request;

class AnfisController extends Controller
{
    public function index()
    {
        $data = AnfisResult::all();

        if ($data->isEmpty()) {
            return view('anfis.dashboard', [
                'data' => $data,
                'rmse' => 0,
                'mad' => 0,
                'aare' => 0,
                'akurasi' => 0
            ]);
        }

        // Hitung RMSE
        $rmse = sqrt(
            $data->avg(function ($row) {
                return pow($row->y_aktual - $row->y_pred, 2);
            })
        );

        // Hitung MAD
        $mad = $data->avg(function ($row) {
            return abs($row->y_aktual - $row->y_pred);
        });

        // Hitung AARE (hindari pembagian 0)
        $aare = $data->filter(function ($row) {
            return $row->y_aktual != 0;
        })->avg(function ($row) {
            return abs(($row->y_aktual - $row->y_pred) / $row->y_aktual) * 100;
        }) ?? 0;

        // Hitung Akurasi dengan toleransi
        $toleransiPersen = 40; //
        $totalData = $data->filter(function ($row) {
            return $row->y_aktual != 0;
        })->count();

        $trueDecision = $data->filter(function ($row) use ($toleransiPersen) {
            if ($row->y_aktual == 0) {
                return false;
            }
            $errorRel = abs(($row->y_aktual - $row->y_pred) / $row->y_aktual) * 100;
            return $errorRel <= $toleransiPersen;
        })->count();

        $akurasi = ($totalData > 0) ? ($trueDecision / $totalData) * 100 : 0;

        return view('anfis.dashboard', compact('data', 'rmse', 'mad', 'aare', 'akurasi'));
    }
}
