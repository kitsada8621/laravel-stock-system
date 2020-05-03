@extends('app')
@section('title','ระบบเบิกครุภัณฑ์ ออกรายงานข้อมูบการคืนพัสดุ')
@section('content')
    <div class="container-fluid">
        
        <h1 class="mt-4">ออกรายงานข้อมูลการคืนพัสดุ</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">ออกรายงานข้อมูลการคืนพัสดุ</li>
        </ol>

        <div class="card mb-3">
            <h5 class="card-header">ข้อมูลการคืน</h5>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0" id="dataTable">
                        <thead class="">
                            <tr>
                                <th>เลือก</th>
                                <th>รายการ</th>
                                <th>ผู้เบิก</th>
                                <th>จำนวน</th>
                                <th>วันที่เบิก</th>
                                <th>วันที่คืน</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="mb-3" style="display: none;">
            <div id="PrintIn">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between mb-4">
                        <div class="d-flex align-items-center">
                            <img src="https://images.vexels.com/media/users/3/144204/isolated/preview/f6d082b22c3fbdc5d2927ff1c7cd57d4-hospital-building-icon-by-vexels.png" class="mr-2" width="auto" height="60">
                            <div>
                                <h5 class="font-weight-bold mb-0">โรงพยาบาลกรุงศรีสยาม</h5>
                                <p class="text-uppercase mb-0 font-weight-bolder">KrungSrisiam Hopital</p>
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end">
                            <h5 class="text-center font-weight-light mb-1 mb-3">รายงานการคืน</h5>
                            <p class="mb-1 small">4875/10 Thongmaha Juyburi Thailand 78954.</p>
                            <p class="small"><b>Email:</b> Email@hopital.co.th&nbsp;&nbsp;<b>Phone:</b> 098-898-7896</p>
                        </div>

                    </div>
                    <p class="mb-2 font-weight-bold small text-right">วันที่พิมพ์ : {{formatDateThai(date('Y-m-d H:i:s'))}}</p>
                    <div id="printTables"></div>
                </div>
            </div>
        </div>

        <button class="btn btn-primary font-weight-bold mb-3" id="btnPrints"><i class="fas fa-print"></i>&nbsp;ออกรายงาน</button>
        
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="modelDetails" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body py-5" id="detailsPrint">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-1">รายละเอียดรายการคืน (Return Details)</h5>
                            <p class="mb-0 small"><b>วันที่ยืม</b> : <span class="timeIn"></span></p>
                            <p class="small"><b>วันที่คืน</b> : <span class="timeOut"></span></p>
                        </div>
                        <div class="d-flex flex-column align-items-end">
                            <h5>ผู้เบิก: <span class="names"></span></h5>
                            <p class="mb-0 small"><b>Department:</b> <span class="depart"></span> <b>Position:</b> <span class="posi"></span></p>
                            <p class="small"><b>อีเมลล์</b>: <span class="mail"></span> <b>เบอร์โทร</b>: <span class="phone"></span></p>
                        </div>
                    </div>
                    <table class="table">
                        <tr style="font-size: 12px; font-weight:600;" class="bg-light text-center">
                            <th width="60">ลำดับ</th>
                            <th class="text-left">รายการ</th>
                            <th width="80">จำนวน</th>
                            <th width="100">ราคา/หน่วย</th>
                            <th width="100">ราคารวม</th>
                        </tr>
                        <tr class="text-center" style="font-size: 13px; font-weight:400;">
                            <td>1</td>
                            <td class="text-left"><span class="product-list"></span></td>
                            <td><span class="unit-list"></span></td>
                            <td><span class="price-list"></span></td>
                            <td><span class="total-list"></span></td>
                        </tr>
                        <tr>
                            <td colspan="5" style="height: 60px;"></td>
                        </tr>
                        <tr class="bg-light">
                            <td colspan="4" class="text-right pr-3">ราคารวม</td>
                            <td class="text-center"><span class="total-footer"></span> บาท</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-circle" data-dismiss="modal"><span style="font-size: 1rem;" class="fas fa-times"></span></button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        $(function(){
            // fetch data
            let table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/report/return',
                columns:[
                    {data:'select',class:'text-center',width:'60',orderable:false,searchable:false},
                    {data:'p_name',class:'pl-2',orderable:false},
                    {data:'name',class:'pl-2',orderable:false},
                    {data:'p_return_unit',class:'text-center',orderable:false},
                    {data:'times_in',class:'text-center'},
                    {data:'times_out',class:'text-center'},
                    {data:'action',class:'text-center',width:'80',orderable:false,searchable:false},
                ],
                columnDefs: [
                    { targets: [0], orderable: false }
                ]
            });

            // report 
            $(document).on('click','#btnPrints',function(){
                const myId =new Array;
                $('input[name=selected]').each(function(){
                    if(this.checked) {
                        myId.push($(this).data('id'));
                    }
                });

                if($.isEmptyObject(myId)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'กรุณาเลือกข้อมูลก่อน นะคะ',
                        confrimButtonText: 'ตกลง',
                        confrimButtonColor: '#007bff'
                    });
                }else {
                    axios.post('/report/return/print',{
                        id: myId,
                    }).then(response =>{
                        let result = '<table class="table table-bordered">';
                        result += `<tr><th class="text-center">ลำดับ</th>
                        <th>รายการ</th>
                        <th>ผู้เบิก</th>
                        <th class="text-center">จำนวน</th>
                        <th class="text-center">ราคา</th>
                        <th width="140" class="text-center">วันที่เบิก</th>
                        <th width="140" class="text-center">วันที่คืน</th></tr>`;                  
                        $.each(response.data,function(key ,value){
                            result += `<tr><td class="text-center">${key + 1}</td>
                            <td>${value['p_id']} ${value['p_name']}</td>
                            <td>${value['name']}</td>
                            <td class="text-center">${value['p_return_unit']} ${value['unit_type_name']}</td>
                            <td class="text-center">${value['p_price']} บาท</td>
                            <td class="text-center">${timsCoverter(value['times_in'])}</td>
                            <td class="text-center">${timsCoverter(value['times_out'])}</td>
                            </tr>`;
                        });
                        result += '</table>';
                        $('#printTables').html(result);
                        $('#PrintIn').printThis();
                        $('input[name=selected]').prop('checked',false);
                    });
                }

            });

            // times covers 
            function timsCoverter(times) {
                let months_arr = ['ม.ค','ก.พ','มี.ค','เม.ย','พ.ค','มิ.ย','ก.ค','ส.ค','ก.ย','ต.ค','พ.ย','ธ.ค'];
                let date = new Date(times*1000);
                let day = date.getDate();
                let month = months_arr[date.getMonth()];
                let year = date.getFullYear() + 543;
                return day+' '+month+' '+year;
            }

            // details 
            $(document).on('click','#btnDetails',function(){
                $('#modelDetails').modal('show');
                $('.depart').text($(this).data('d_name'));
                $('.mail').text($(this).data('email'));
                $('.phone').text($(this).data('tel'));
                $('.names').text($(this).data('name'));
                $('.timeIn').text($(this).data('times_in'));
                $('.timeOut').text($(this).data('times_out'));
                $('.posi').text(function(){
                    return $(this).data('role') ? 'แอดมิน' : 'พนักงาน';
                });
                $('.product-list').text(`${$(this).data('p_id')} ${$(this).data('p_name')}`);
                $('.unit-list').text(`${$(this).data('unit')} ${$(this).data('unit_type')}`);
                $('.price-list').text(`${$(this).data('p_price')} บาท`);
                $('.total-list').text(`${$(this).data('total')} บาท`);
                $('.total-footer').text(`${$(this).data('total')}`)
            });

            //details print
            $('#btnPrinted').click(function(){
                $('#detailsPrint').printThis();
            });
        
        });
    </script>
@endpush
@push('styles')
    <style>
        .dataTables_filter, 
        .dataTables_length, 
        .dataTables_info {
            display: none;
        }
        .table.dataTable {
            margin-top: 0 !important;
            margin-bottom: 8px !important;
        }
        div.dataTables_wrapper 
        div.dataTables_paginate ul.pagination{
            margin: 10px 0;
            padding: 0 10px;
        }
        table.table-bordered {
            border-left: none;
            border-right: none;
            border-bottom: 1px solid #dee2e6;
        }

        .btn-circle {
            width: 40px;
            height: 40px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 50%;
        }
    </style>
@endpush