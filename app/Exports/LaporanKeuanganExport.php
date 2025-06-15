<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanKeuanganExport implements FromView
{
    protected $data, $period;

    public function __construct($data, $period)
    {
        $this->data = $data;
        $this->period = $period;
    }

    public function view(): View
    {
        return view('laporan.keuangan_excel', [
            'data' => $this->data,
            'period' => $this->period,
        ]);
    }
}
