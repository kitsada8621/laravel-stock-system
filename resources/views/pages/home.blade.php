@extends('app')
@section('title','ระบบเบิกครุภัณฑ์')
@section('content')
<div class="container-fluid">

    <h1 class="mt-4">พัสดุทั้งหมด</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">หน้าหลัก</li>
    </ol>

    <div class="row">
        <div class="col-xl-12">
            <div class="mb-2">
                <label for="alls" class="btn btn-primary"><i class="fas fa-globe-africa"></i> ทั้งหมด <span class="count-all"></span></label>
                <input type="radio" class="d-none" name="category" id="alls" value="0">
                <label for="wait_confirm" class="btn btn-warning"><i class="fas fa-sync-alt"></i> รออนุมัติ <span class="count-wait"></span></label>
                <input type="radio" class="d-none" name="category" id="wait_confirm" value="1">
                <label for="confrim" class="btn btn-success"><i class="fas fa-glass-cheers"></i> อนุมัติ <span class="count-confirm"></span></label>
                <input type="radio" class="d-none" name="category" id="confrim" value="2">
                <label for="not_confirm" class="btn btn-danger">ไม่อนุมัติ <span class="count-no-confirm"></span></label>
                <input type="radio" class="d-none" name="category" id="not_confirm" value="3">
            </div>

            <div class="card mb-4 rounded-0">
                <h5 class="card-header">@if(Auth::user()->role) ข้อมูลรายการ@else รายการเบิกของฉัน @endif</h5>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover" id="orderTables" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>รายการ</th>
                                    <th>ผู้ร้อง</th>
                                    <th>สถานะ</th>
                                    <th>จำนวน</th>
                                    <th>ว/ด/ป</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
        $(function(){
            fetch_data();
            // fetch order list
            function fetch_data(category = 1) {
                $('#orderTables').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/',
                        data: {category:category}
                    },
                    columns:[
                        {data:'DT_RowIndex',class:'text-center',width:'50'},
                        {data:'p_id',class:'pl-1',orderable:false},
                        {data:'name',class:'pl-1',orderable:false},
                        {data:'status',class:'text-center',orderable:false},
                        {data:'p_sale_unit',class:'text-center',orderable:false},
                        {data:'created_at',class:'text-center',orderable:false},
                        {data:'action',class:'text-center',orderable:false,searchable:false},
                    ]
                });
            } 

            // fillter search
            $('input[name=category]').change(function(){
                if(this.checked) {
                    $('#orderTables').DataTable().destroy();
                    fetch_data(this.value);
                }
            });

            /** search */
            $('#formSearch').submit(function(e){
                e.preventDefault();
                const query = $('input[name=inputSearch]').val();
                $('#orderTables').DataTable().search(query).draw();
            });

            //confirm orders
            $(document).on('click','#btnConfirm',function(){
                const id = $(this).data('id');
                Swal.fire({
                    icon: 'question',
                    title: 'อนุมัติ !',
                    text: 'ต้องการอนุมัติรายการเบิกพัสดุ ใช่หรือไม่ ?',
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ยืนยัน',
                }).then(result =>{
                    if(result.value) {
                        axios.patch(`/order/${id}`,{
                            id: id
                        }).then(response =>{
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ !',
                                text: response.data.message,
                                confirmButtonText: 'ตกลง',
                            });
                            $('#orderTables').DataTable().ajax.reload();
                            countAll();
                            countConfrim();
                            countAll();
                            countConfrim();
                            countWaitConfrim();
                            countRemove();
                        }).catch(error =>{
                            alert(JSON.stringify(error.response.data));
                        });
                    }
                });
            });

            // unconfrim order
            $(document).on('click','#unconfirm',function(){
                const id = $(this).data('id');
                Swal.fire({
                    icon: 'warning',
                    title: 'ตำเตือน !',
                    text: 'ต้องการยกเลิกการเบิกพัสดุ ใช่หรือไม่ ?',
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ยืนยัน',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#007bff',
                }).then(result =>{
                    if(result.value) {
                        axios.patch(`/order/unconfrim/${id}`)
                        .then(response =>{
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ !',
                                text: response.data.message,
                                confirmButtonText: 'ยืนยัน',
                            });
                            $('#orderTables').DataTable().ajax.reload();
                            countAll();
                            countConfrim();
                            countAll();
                            countConfrim();
                            countWaitConfrim();
                            countRemove();
                        });
                    } 
                });
            });

            // remove order
            $(document).on('click','#btnRemoveOrder',function(){
                Swal.fire({
                    icon: 'warning',
                    title: 'ตำเตือน !',
                    text: 'ต้องการลบคำร้องข้อเบิก ใช่หรือไม่ ?',
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ยืนยัน',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#007bff',
                }).then(result =>{
                    if(result.value) {
                        axios.delete(`/order/${$(this).data('id')}`)
                        .then(response =>{
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ !',
                                text: response.data.message,
                                confirmButtonText: 'ยืนยัน',
                            });
                            $('#orderTables').DataTable().ajax.reload();
                            countAll();
                            countConfrim();
                            countWaitConfrim();
                            countRemove();
                        }).catch(error =>{
                            alert(JSON.stringify(error.response.data));
                        });
                    }
                });
            });

            // show data counts
            countAll();
            countConfrim();
            countWaitConfrim();
            countRemove();
            // show count 
            function countAll() {
                axios.get('/count/all').then(response =>{
                    $('.count-all').html(`(${response.data})`);
                });
            }
            function countConfrim() {
                axios.get('/count/confrim')
                .then(response =>{
                    $('.count-confirm').html(`(${response.data})`);
                });
            }
            function countWaitConfrim() {
                axios.get('/count/wait/confrim').then(response =>{
                    $('.count-wait').html(`(${response.data})`);
                });
            }
            function countRemove() {
                axios.get('/count/remove').then(response =>{
                    $('.count-no-confirm').html(`(${response.data})`);
                });
            }

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
        #orderSearch {
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/7/7e/Vector_search_icon.svg/1200px-Vector_search_icon.svg.png');
            padding-left: 25px;
            background-size: 15px;
            background-repeat: no-repeat;
            background-position: 5px center;
            font-weight: 300;
            font-size: 1rem;
        }
        table.dataTable {
            border-collapse: collapse !important;
        }
        .table {
            /* border: 1px solid #dee2e6; */
            border-bottom: 1px solid #dee2e6;
        }
    </style>
@endpush