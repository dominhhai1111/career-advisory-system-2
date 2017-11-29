<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redirect;

class MyController extends Controller
{
    public function index()
    {
        $nganh = DB::table("nganh")->get();
        $soThich = DB::table("sothich")->get();
        $nangKhieu = DB::table("nangkhieu")->get();
        $nganhSoThich = DB::table("nganh_sothich")->get();
        $nganhNangKhieu = DB::table("nganh_nangkhieu")->get();
        return view('index', ['nganhs' => $nganh, 'soThichs' => $soThich, 'nangKhieus' => $nangKhieu, 'nganhSoThichs' => $nganhSoThich, 'nganhNangKhieus' => $nganhNangKhieu]);
    }
    
    public function execute(Request $request)
    {
        $soThichs = $request->input('soThich');
        $nangKhieus = $request->input('nangKhieu');
        $diemToan = $request->input('diemToan');
        $diemLy = $request->input('diemLy');
        $diemHoa = $request->input('diemHoa');
        $tongDiem = $diemToan + $diemLy + $diemHoa;

        $nganhs = DB::table("nganh")->get();
        $soThichs = DB::table("sothich")->whereIn('id', $soThichs)->get();
        $nangKhieus = DB::table("nangkhieu")->whereIn('id', $nangKhieus)->get();

        // tính điểm đóng góp của sở thích với các nhóm ngành
        foreach ($nganhs as $nganh){
            $stDongGopNganh[$nganh->id] = 0;
            foreach ($soThichs as $soThich){
                $diemDongGop = DB::table("nganh_sothich")->select("diem_dong_gop")->where(['id_nganh' => $nganh->id, 'id_sothich' => $soThich->id])->first();
                $stDongGopNganh[$nganh->id] += $diemDongGop->diem_dong_gop;
            }
        }

        // tính điểm đóng góp của năng khiếu với các nhóm ngành
        foreach ($nganhs as $nganh){
            $nkDongGopNganh[$nganh->id] = 0;
            foreach ($nangKhieus as $nangKhieu){
                $diemDongGop = DB::table("nganh_nangkhieu")->select("diem_dong_gop")->where(['id_nganh' => $nganh->id, 'id_nangkhieu' => $nangKhieu->id])->first();
                $nkDongGopNganh[$nganh->id] += $diemDongGop->diem_dong_gop;
            }
        }

        // tính điểm đóng góp của điểm thi với các nhóm ngành
        foreach ($nganhs as $nganh){
            $diemChuanNganh = DB::table("nganh")->select("diem_chuan")->where('id_nganh', $nganh)->first();
            $nkDongGopNganh[$nganh->id] = $diemDongGop->diem_chuan;
        }

        var_dump($stDongGopNganh);
        var_dump($nkDongGopNganh); die;
        return view('showResult');
    }
}
