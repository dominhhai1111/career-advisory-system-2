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
        // nhận dữ liệu người dùng nhập vào
        $soThichs = $request->input('soThich');
        $nangKhieus = $request->input('nangKhieu');
        $diemToan = $request->input('diemToan');
        $diemLy = $request->input('diemLy');
        $diemHoa = $request->input('diemHoa');
        $tongDiem = $diemToan + $diemLy + $diemHoa;

        // truy vấn dữ liệu ngành, sở thích, năng khiếu
        $nganhs = DB::table("nganh")->get();
        $soThichs = DB::table("sothich")->whereIn('id', $soThichs)->get();
        $nangKhieus = DB::table("nangkhieu")->whereIn('id', $nangKhieus)->get();

        // truy xuất giá trị đóng góp của học phí, mức lương, cơ hội thăng tiến, áp lực công việc với các nhóm ngành
        foreach ($nganhs as $nganh){
            $hpDongGopNganh[$nganh->id] = $nganh->hoc_phi;
            $chDongGopNganh[$nganh->id] = $nganh->co_hoi_thang_tien;
            $alDongGopNganh[$nganh->id] = $nganh->ap_luc_cong_viec;
        }

        // tính giá trị đóng góp của sở thích với các nhóm ngành
        foreach ($nganhs as $nganh){
            $stDongGopNganh[$nganh->id] = 0;
            foreach ($soThichs as $soThich){
                $diemDongGop = DB::table("nganh_sothich")->select("diem_dong_gop")->where(['id_nganh' => $nganh->id, 'id_sothich' => $soThich->id])->first();
                $stDongGopNganh[$nganh->id] += $diemDongGop->diem_dong_gop;
            }
        }

        // tính giá trị đóng góp của năng khiếu với các nhóm ngành
        foreach ($nganhs as $nganh){
            $nkDongGopNganh[$nganh->id] = 0;
            foreach ($nangKhieus as $nangKhieu){
                $diemDongGop = DB::table("nganh_nangkhieu")->select("diem_dong_gop")->where(['id_nganh' => $nganh->id, 'id_nangkhieu' => $nangKhieu->id])->first();
                $nkDongGopNganh[$nganh->id] += $diemDongGop->diem_dong_gop;
            }
        }

        // tính giá trị đóng góp của điểm thi với các nhóm ngành
        foreach ($nganhs as $nganh){
            $dsDongGopNganh[$nganh->id] = ($tongDiem - $nganh->diem_chuan) / $nganh->diem_chuan;
        }

        var_dump($stDongGopNganh); echo "<br/>";
        var_dump($nkDongGopNganh); echo "<br/>";
        var_dump($dsDongGopNganh);
        die;
        return view('showResult');
    }

    // chuẩn hóa dữ liệu thành thuộc [0, 1]
    public function chuanHoaTuyenTinh($mang_du_lieu){
        $gia_tri_max = max($mang_du_lieu);
        foreach ($mang_du_lieu as $key => $du_lieu){
            $mang_du_lieu_chuan_hoa[$key] = $du_lieu / $gia_tri_max;
        }
        return $mang_du_lieu_chuan_hoa;
    }

    public function nhanTrongSo($mang_du_lieu, $trong_so){
        foreach ($mang_du_lieu as $key => $du_lieu){
            $mang_du_lieu_sau_TS[$key] = $du_lieu * $trong_so;
        }
        return $mang_du_lieu_sau_TS;
    }
}
