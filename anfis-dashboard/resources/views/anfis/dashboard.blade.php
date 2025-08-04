@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mt-3 mb-4 text-center fw-bold text-primary">📊 Dashboard Hasil Perhitungan ANFIS</h2>

        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow border-0 text-white bg-primary text-center p-3">
                    <h6>RMSE</h6>
                    <h4>{{ number_format($rmse, 4) }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow border-0 text-white bg-success text-center p-3">
                    <h6>MAD</h6>
                    <h4>{{ number_format($mad, 4) }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow border-0 text-white bg-warning text-center p-3">
                    <h6>AARE</h6>
                    <h4>{{ number_format($aare, 2) }}%</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow border-0 text-white bg-danger text-center p-3">
                    <h6>Akurasi</h6>
                    <h4>{{ number_format($akurasi, 2) }}%</h4>
                </div>
            </div>
        </div>

        <!-- Grafik Parsial -->
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white fw-bold">Grafik Parsial (Aktual, Prediksi, Error)</div>
            <div class="card-body">
                <canvas id="chartParsial" height="100"></canvas>
            </div>
        </div>

        <!-- Grafik Perbandingan -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white fw-bold">Perbandingan Data Aktual vs Data Prediksi</div>
            <div class="card-body">
                <canvas id="chartPerbandingan" height="100"></canvas>
            </div>
        </div>

        <!-- Grafik Error Absolut -->
        <div class="card shadow mb-4">
            <div class="card-header bg-warning text-dark fw-bold">Error Absolut</div>
            <div class="card-body">
                <canvas id="chartError" height="100"></canvas>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="card shadow mb-4">
            <div class="card-header bg-dark text-white fw-bold">Detail Data ANFIS</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 text-center">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Data Aktual (Y Aktual)</th>
                                <th>Data Prediksi (Y Pred)</th>
                                <th>Error Absolute</th>
                                <th>Error Relatif (%)</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($data as $row)
                                <tr>
                                    <td>{{ $row->no }}</td>
                                    <td>{{ number_format($row->y_aktual, 3) }}</td>
                                    <td>{{ number_format($row->y_pred, 3) }}</td>
                                    <td>{{ number_format($row->error_abs, 3) }}</td>
                                    <td>{{ number_format($row->error_rel, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let labelsIndikator = @json($data->pluck('indikator_kinerja'));
        let dataAktual = @json($data->pluck('y_aktual'));
        let dataPred = @json($data->pluck('y_pred'));
        let dataErrorAbs = @json($data->pluck('error_abs'));
        let labelNo = @json($data->pluck('no'));

        // Tooltip custom
        function customTitle(tooltipItems) {
            let index = tooltipItems[0].dataIndex;
            return 'Indikator: ' + (labelsIndikator[index] ?? '-');
        }

        function customLabel(tooltipItem) {
            let index = tooltipItem.dataIndex;
            let datasetLabel = tooltipItem.dataset.label;
            if (datasetLabel === 'Data Aktual') return 'Aktual: ' + dataAktual[index];
            if (datasetLabel === 'Data Prediksi') return 'Prediksi: ' + dataPred[index];
            if (datasetLabel === 'Error Absolut') return 'Error Absolut: ' + dataErrorAbs[index];
        }

        // Grafik Parsial
        new Chart(document.getElementById('chartParsial').getContext('2d'), {
            type: 'line',
            data: {
                labels: labelNo,
                datasets: [{
                        label: 'Data Aktual',
                        data: dataAktual,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Data Prediksi',
                        data: dataPred,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Error Absolut',
                        data: dataErrorAbs,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.2)',
                        fill: true,
                        tension: 0.3,
                        yAxisID: 'y2'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                stacked: false,
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left'
                    },
                    y2: {
                        type: 'linear',
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            title: customTitle,
                            label: customLabel
                        }
                    }
                }
            }
        });

        // Chart Perbandingan
        new Chart(document.getElementById('chartPerbandingan').getContext('2d'), {
            type: 'line',
            data: {
                labels: labelNo,
                datasets: [{
                        label: 'Data Aktual',
                        data: dataAktual,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.2)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Data Prediksi',
                        data: dataPred,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.2)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            title: customTitle,
                            label: customLabel
                        }
                    }
                }
            }
        });

        // Chart Error Absolut
        new Chart(document.getElementById('chartError').getContext('2d'), {
            type: 'bar',
            data: {
                labels: labelNo,
                datasets: [{
                    label: 'Error Absolut',
                    data: dataErrorAbs,
                    backgroundColor: 'rgba(255, 193, 7, 0.8)',
                    borderColor: '#ffc107',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            title: customTitle,
                            label: customLabel
                        }
                    }
                }
            }
        });
    </script>
@endsection
