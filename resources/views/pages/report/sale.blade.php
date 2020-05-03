<!doctype html>
<html lang="en">
  <head>
    <title>ใบเสร็จการเบิกพัสดุ ของ {{$data->name}}</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .style {
            padding: 3rem 10rem;
            
        }
    </style>
    </head>
  <body style="display:none;">
    <div id="reports">
        <div class="style">
            <div class="row" style="margin-bottom:30px;">
                <div class="col-8">
                    <h1 style="font-family: 'Prompt', sans-serif;">โรงพยาบาลกรุงศรีมหาลัย</h1>
                    <p style="font-family: 'Prompt', sans-serif;">784/10 ตำบลเหล่าทอง อำเภอโซ่พิสัย จังหวัดบึงกาฬ 389578</p>
                </div>
                <div class="col-4">
                    <h2 style="font-family: 'Prompt', sans-serif;" class="text-center">ใบเสร็จการเบิกพัสดุ (Receipt)</h2>
                </div>
            </div>
            
            <div class="row">
                <div class="col-6">
                    <h5 style="font-family: 'Prompt', sans-serif;">รายละเอียดผู้เบิก</h5>
                    <p style="font-family: 'Prompt', sans-serif;">ชื่อ-สกุล {{$data->name}} <br>
                    แผนก {{$data->d_name}} <br>
                    ติดต่อ โทร ................................  อีเมลล์ {{$data->email}}</p>
                </div>
                <div class="col-6 d-flex flex-column justify-content-end align-items-end pb-2" style="font-family: 'Prompt', sans-serif;">
                    วันที่เบิกพัสดุ : {{date('d M Y H:i:s',$data->times_in)}} <br>
                    วันที่พิมพ์เอกสารนี้ : {{date('d M Y H:i:s')}}
                </div>
            </div>
            <table class="table table-bordered" style="margin-bottom:50px;" >
                <tr>
                    <th style="font-family: 'Prompt', sans-serif;" class="text-center" width="5%">ลำดับ</th>
                    <th style="font-family: 'Prompt', sans-serif;">รายการ</th>
                    <th style="font-family: 'Prompt', sans-serif;" class="text-center" width="10%">ราคา</th>
                    <th style="font-family: 'Prompt', sans-serif;" class="text-center" width="20%">จำนวน</th>
                </tr>
                <tr style="height:150px;">
                    <td style="font-family: 'Prompt', sans-serif;" class="text-center">1</td>
                    <td style="font-family: 'Prompt', sans-serif;">{{$data->p_id." ".$data->p_name}}</td>
                    <td style="font-family: 'Prompt', sans-serif;" class="text-center">{{$data->p_price}} บาท</td>
                    <td style="font-family: 'Prompt', sans-serif;" class="text-center">{{$data->p_sale_unit." ".$data->unit_type_name}}</td>
                </tr>
                <tr>
                    <td style="font-family: 'Prompt', sans-serif;" colspan="2" class="font-weight-bold text-center">ราคารวม</td>
                    <td style="font-family: 'Prompt', sans-serif;" colspan="2" class="text-center">{{$data->p_price * $data->p_sale_unit}} บาท</td>
                </tr>
            </table>
            <div class="d-flex flex-column align-items-end" style="padding-right:30px; padding-top:50px;">
                <div style="font-family: 'Prompt', sans-serif;" class="text-center" style="margin-bottom:30px;">
                    ลงชื่อ......................................................... <br>
                    <span>( ผู้อนุมัติการเบิก )</span>
                </div>
                <br>
                <div style="font-family: 'Prompt', sans-serif;" class="text-center">
                    ลงชื่อ......................................................... <br>
                    <span>( ผู้เบิกพัสดุ )</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="{{asset('asset/js/printThis.js')}}"></script>
    <script>
        window.onload = function() {
            $('#reports').printThis();
        }
    </script>
  </body>
</html>