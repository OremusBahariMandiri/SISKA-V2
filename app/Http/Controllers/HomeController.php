<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $shortcuts = [
            ['icon' => 'users',        'label' => 'Total Karyawan',       'tanya' => 'Berapa total karyawan yang ada?'],
            ['icon' => 'user-check',   'label' => 'Karyawan Aktif',        'tanya' => 'Berapa karyawan yang statusnya aktif?'],
            ['icon' => 'sitemap',      'label' => 'Per Departemen',        'tanya' => 'Tampilkan jumlah karyawan per departemen'],
            ['icon' => 'map-marker-alt','label' => 'Per Wilayah Kerja',    'tanya' => 'Berapa karyawan di setiap wilayah kerja?'],
            ['icon' => 'file-contract','label' => 'Per Jenis Kontrak',     'tanya' => 'Tampilkan karyawan berdasarkan jenis kontrak'],
            ['icon' => 'building',     'label' => 'Per Perusahaan',        'tanya' => 'Berapa karyawan di masing-masing perusahaan?'],
            ['icon' => 'venus-mars',   'label' => 'Per Jenis Kelamin',     'tanya' => 'Berapa karyawan laki-laki dan perempuan?'],
        ];

        return view('home', compact('shortcuts'));
    }
}