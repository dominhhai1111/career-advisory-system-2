<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Career Advisory System</title>
    <link rel="stylesheet" href="bootstrap-3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script type="text/javascript" src="jquery/jquery.min.js"></script>
    <script type="text/javascript" src="bootstrap-3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
    <h2>Hệ thống tư vấn chọn ngành</h2>
    <div class="container">
        <div class="introduce text-center">
            <p>* Điền đầy đủ thông tin sau đây, hệ thống sẽ tư vấn cho bạn ngành nghề phù hợp với bạn!</p>
        </div>
        <div class="content">
            <form action="{{route('execute')}}" method="post">
                <div class="info">
                    <p class="title">Sở thích cá nhân:<p>
                        <div class="row">
                        @foreach($soThichs as $soThich)
                            <p class="col-sm-3"><input type="checkbox" name="soThich[]" value="{{$soThich->id}}">{{$soThich->ten}}</p>
                        @endforeach
                </div>
                <div class="info">
                    <p class="title">Năng khiếu:</p>
                    <div class="row">
                        @foreach($nangKhieus as $nangKhieu)
                            <p class="col-sm-3"><input type="checkbox" name="nangKhieu[]" value="{{$nangKhieu->id}}">{{$nangKhieu->ten}}</p>
                        @endforeach
                    </div>
                </div>
                <div class="info">
                    <p class="title">Điểm thi thử các môn:</p>
                    <div class="row">
                        <p class="col-sm-3">Khối A:</p>
                        <p class="col-sm-3">Toán: <input type="number" name="diemToan" class="input-score"></p>
                        <p class="col-sm-3">Lý: <input type="number" name="diemLy" class="input-score"></p>
                        <p class="col-sm-3">Hóa: <input type="number" name="diemHoa" class="input-score"></p>
                    </div>
                </div>
                <input type="submit" name="submit" value="Gửi" class="center-block">
                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
            </form>
        </div>
    </div>
</body>
</html>