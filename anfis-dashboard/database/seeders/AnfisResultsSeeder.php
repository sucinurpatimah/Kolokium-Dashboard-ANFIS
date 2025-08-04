<?php

namespace Database\Seeders;

use App\Models\AnfisResult;
use Illuminate\Database\Seeder;

class AnfisResultsSeeder extends Seeder
{
    public function run(): void
    {
        // Nilai akhir SCM asli (tidak dinormalisasi)
        $y_aktual_asli = [
            1.80,
            2.86,
            2.37,
            2.01,
            2.74,
            4.45,
            4.62,
            4.45,
            2.61,
            2.37,
            4.62,
            4.93,
            3.68,
            4.45,
            2.74,
            2.74,
            4.30,
            4.15,
            4.93,
            2.26,
            0.00,
            3.68
        ];

        // Prediksi dalam bentuk normalisasi (hasil ANFIS)
        $y_pred_normalisasi = [
            0.237,
            0.343,
            0.295,
            0.259,
            0.331,
            0.484,
            0.497,
            0.484,
            0.319,
            0.295,
            0.497,
            0.524,
            0.418,
            0.484,
            0.331,
            0.331,
            0.471,
            0.458,
            0.524,
            0.284,
            0.017,
            0.418
        ];

        // Nama indikator
        $indikator_kinerja = [
            'MPS - Commitment Monthly Order',
            'Cycle Time',
            'Forecasting Demand Accuracy',
            'Planning Flexibility',
            '% Supplier with EMS/ISO 14001',
            'Precentage quality accuracy by supplier',
            'Precentage quantity accuracy by supplier',
            'Supplier Lead Time Compliance',
            'Supplier Flexibility',
            'Energy used',
            'Chemical used',
            'Number of Trouble Machines',
            'Yield',
            'Work Safety Compliance',
            '% of solid waste recycling',
            'Downtime Recovery Speed',
            'Delivery quantity accuracy',
            'Shipping Document Accuracy',
            'Delivery Time Compliance',
            'Delivery Flexibility',
            '% Error - free return shipped',
            'Handling of Return Materials'
        ];

        // Cari min & max dari nilai asli SCM
        $min_scm = min($y_aktual_asli);
        $max_scm = max($y_aktual_asli);

        // Hitung prediksi dalam skala asli (denormalisasi)
        $y_pred_asli = [];
        foreach ($y_pred_normalisasi as $yp) {
            $y_pred_asli[] = $yp * ($max_scm - $min_scm) + $min_scm;
        }

        // Hitung statistik global berdasarkan data asli
        $n = count($y_aktual_asli);
        $rmse = 0;
        $mad = 0;
        $aare = 0;
        $count_nonzero = 0;

        foreach ($y_aktual_asli as $i => $ya) {
            $err_abs = abs($ya - $y_pred_asli[$i]);
            $err_rel = ($ya != 0) ? ($err_abs / $ya * 100) : 0;

            $rmse += pow($err_abs, 2);
            $mad += $err_abs;

            if ($ya != 0) {
                $aare += $err_rel;
                $count_nonzero++;
            }
        }

        $rmse = sqrt($rmse / $n);
        $mad = $mad / $n;
        $aare = ($count_nonzero > 0) ? $aare / $count_nonzero : 0;
        $akurasi = 100 - $aare;

        // Simpan ke database
        foreach ($y_aktual_asli as $i => $ya) {
            $err_abs = abs($ya - $y_pred_asli[$i]);
            $err_rel = ($ya != 0) ? ($err_abs / $ya * 100) : 0;

            AnfisResult::create([
                'no' => $i + 1,
                'y_aktual' => $ya, // nilai asli
                'y_pred' => $y_pred_asli[$i], // hasil denormalisasi
                'error_abs' => $err_abs,
                'error_rel' => $err_rel,
                'rmse' => $rmse,
                'mad' => $mad,
                'aare' => $aare,
                'akurasi' => $akurasi,
                'indikator_kinerja' => $indikator_kinerja[$i]
            ]);
        }
    }
}
