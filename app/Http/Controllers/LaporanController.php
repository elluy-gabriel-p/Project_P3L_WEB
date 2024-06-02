<?php

namespace App\Http\Controllers;

use App\Models\Bahan_baku;
use App\Models\Detil_pesanan;
use App\Models\Pembelian_bahan_baku;
use App\Models\Pesanan;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Penitip;
use App\Models\Produk;
use App\Models\Pengeluaran_lain;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function stokBahan()
    {
        // Set locale to Indonesian
        Carbon::setLocale('id');

        // Prepare data for the view
        $data = [
            'title' => 'Laporan Stok Bahan Baku',
            'date' => Carbon::now()->translatedFormat('j F Y'), // Use translated format for date
            'bahan' => Bahan_baku::all()
        ];

        // Generate PDF
        $pdf = Pdf::loadView('report.laporanStokBahan', $data);

        // Return PDF stream
        return $pdf->stream('Laporan Stok Bahan Baku.pdf');
    }

    public function penjualanProduk(Request $request)
    {
        // Set locale to Indonesian
        Carbon::setLocale('id');

        // Get the month from the request
        $bulan = $request->input('bulan');

        // Convert the month to a more usable format
        $monthYear = Carbon::parse($bulan)->format('Y-m');

        // Fetch data based on the selected month
        $detail_pesanan = Detil_pesanan::whereHas('pesanan', function ($query) use ($monthYear) {
            $query->whereYear('tanggal_lunas', Carbon::parse($monthYear)->year)
                ->whereMonth('tanggal_lunas', Carbon::parse($monthYear)->month)
                ->where('status', 'Pesanan Diterima');
        })->get();

        // Calculate total sales
        $total = $detail_pesanan->sum(function ($item) {
            return $item->kuantitas * $item->harga_produk;
        });

        // Prepare data for the view
        $data = [
            'title' => 'Laporan Penjualan Produk',
            'date' => Carbon::now()->translatedFormat('j F Y'), // Use translated format for date
            'bulan' => Carbon::parse($bulan)->translatedFormat('F'), // Translate month name
            'tahun' => Carbon::parse($bulan)->year,
            'detail_pesanan' => $detail_pesanan,
            'total' => $total
        ];

        // Generate PDF
        $pdf = Pdf::loadView('report.laporanPenjualanProduk', $data);

        // Return PDF stream
        return $pdf->stream('Laporan Penjualan Produk.pdf');
    }


    public function presensi(Request $request)
    {
        Carbon::setLocale('id');
        $bulan = $request->input('bulan');
        $monthYear = Carbon::parse($bulan)->format('Y-m');

        $karyawanList = Karyawan::all();

        $karyawan = Presensi::join('karyawan', 'karyawan.id_karyawan', '=', 'presensi.id_karyawan')
        ->where('presensi.status_presensi', 'Hadir')
        ->whereYear('tanggal_presensi', Carbon::parse($monthYear)->year)
            ->whereMonth('tanggal_presensi', Carbon::parse($monthYear)->month)
            ->get();

        $presensiGrouped = $karyawan->groupBy('karyawan_id');

        $karyawanData = [];

        foreach ($karyawanList as $karyawan) {
            
            $karyawanId = $karyawan->id;
            $presensiKaryawan = $presensiGrouped->get($karyawanId, collect());
            

            $karyawanData[] = [
                'nama_karyawan' => $karyawan->nama_karyawan,
                'honor_harian' => $karyawan->honor_harian,
                'kehadiran' => $karyawan->kehadiran,
                'bonus' => $karyawan->bonus,
                'presensi' => $presensiKaryawan,
            ];
        }

        $itung = $presensiGrouped->sum(function ($presensiKaryawan) {
            return $presensiKaryawan->sum('hitung');
        });

        $total = $karyawanList->sum(function ($item) use ($itung) {
            return $item->honor_harian * $itung;
        });

        $data = [
            'title' => 'Laporan Presensi Karyawan',
            'date' => Carbon::now()->translatedFormat('j F Y'),
            'bulan' => Carbon::parse($bulan)->translatedFormat('F'),
            'tahun' => Carbon::parse($bulan)->year,
            'karyawanData' => $karyawanData,
            'total' => $total,
            'itung' => $itung,
        ];

        $pdf = Pdf::loadView('report.laporanPresensi', $data);

        return $pdf->stream('Laporan Presensi Karyawan.pdf');
    }

    public function penitip(Request $request)
    {
        Carbon::setLocale('id');
        $bulan = $request->input('bulan');
        $monthYear = Carbon::parse($bulan)->format('Y-m');
        $penitip = Detil_pesanan::join('produk', 'produk.id_produk', '=', 'detil_pesanan.id_produk')
        ->join('pesanan', 'pesanan.id_pesanan', '=', 'detil_pesanan.id_pesanan')
        ->where('produk.id_penitip', '!=', null)
        ->get();
        foreach($penitip as $item)
        {
            $data = [
                'title' => 'Laporan Penitip',
                'date' => Carbon::now()->translatedFormat('j F Y'), // Use translated format for date
                'bulan' => Carbon::parse($bulan)->translatedFormat('F'), // Translate month name
                'tahun' => Carbon::parse($bulan)->year, 
                'penitip' => $penitip, 
                
            ];
        }
        $pdf = Pdf::loadView('report.laporanPenitip', $data);
        return $pdf->stream('Laporan Penitip.pdf');
    }

    public function pemasukan(Request $request)
    {
        Carbon::setLocale('id');

        $bulan = $request->input('bulan');

        $monthYear = Carbon::parse($bulan)->format('Y-m');

        $pesanan = Detil_pesanan::join('produk', 'produk.id_produk', '=', 'detil_pesanan.id_produk')
        ->join('pesanan', 'pesanan.id_pesanan', '=', 'detil_pesanan.id_pesanan')
        ->whereHas('pesanan', function ($query) use ($monthYear) {
            $query->whereYear('tanggal_lunas', Carbon::parse($monthYear)->year)
                ->whereMonth('tanggal_lunas', Carbon::parse($monthYear)->month)
                ->where('produk.id_penitip', '=', null)
                ->where('tanggal_lunas', '!=', null);

        })->get();

        $pesanan1 = Detil_pesanan::join('pesanan', 'pesanan.id_pesanan', '=', 'detil_pesanan.id_pesanan')
        ->join('produk', 'produk.id_produk', '=', 'detil_pesanan.id_produk')
        ->whereHas('pesanan', function ($query) use ($monthYear) {
            $query->whereYear('tanggal_lunas', Carbon::parse($monthYear)->year)
                ->whereMonth('tanggal_lunas', Carbon::parse($monthYear)->month)
                ->where('produk.id_penitip', '!=', null)
                ->where('tanggal_lunas', '!=', null);

        })->get();

        $pengeluaran_lain = Pengeluaran_lain::whereYear('tanggal_pengeluaran', Carbon::parse($monthYear)->year)
                ->whereMonth('tanggal_pengeluaran', Carbon::parse($monthYear)->month)
                ->get();

            $bahanbaku = Pembelian_bahan_baku::whereYear('tanggal_pengeluaran', Carbon::parse($monthYear)->year)
                ->whereMonth('tanggal_pengeluaran', Carbon::parse($monthYear)->month)
                ->get();
        $total = $pesanan->sum(function ($item) {
            return $item->kuantitas * $item->harga_produk;
        });

        $total1 = $pengeluaran_lain->sum(function($item){
            return $item->total_pengeluaran;
        });

        $total2 = $bahanbaku->sum(function($item){
            return  $item->harga_bahan_baku * $item->kuantitas;
        });

        $total3 = $pesanan1->sum(function ($item) {
            return 20/100*$item->kuantitas * $item->harga_produk;
        });

        $total4 = $total + -1*$total1 + -1*$total2 + $total3;

        $data = [
            'title' => 'Laporan Penjualan Produk',
            'date' => Carbon::now()->translatedFormat('j F Y'), 
            'bulan' => Carbon::parse($bulan)->translatedFormat('F'), 
            'tahun' => Carbon::parse($bulan)->year,
            'pesanan' => $pesanan,
            'pesanan1' => $pesanan1,
            'pengeluaranLain' => $pengeluaran_lain,
            'belanja_bahan' => $bahanbaku,
            'total' => $total,
            'total1' => $total1,
            'total2' => $total2,
            'total3' => $total3,
            'total4' => $total4,
        ];

        $pdf = PDF::loadView('report.laporanPemPengBul', $data);

        return $pdf->stream('laporan_bulanan.pdf');
    }

}