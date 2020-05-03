@extends('app')
@section('title','ระบบเบิกครุภัณฑ์ ข้อมูลการนำเข้าพัสดุ')
@section('content')
    <div class="container-fluid">
        
        <h1 class="mt-4">ข้อมูลการนำเข้าพัสดุ</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item"><a href="#">นำเข้าพัสดุ</a></li>
            <li class="breadcrumb-item active">ข้อมูลการนำเข้าพัสดุ</li>
        </ol>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title m-0 font-weight-bold">ข้อมูลประวัตินำเข้าพัสดุ</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped" cellspacing="0" width="100%" id="myTables">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>รหัสพัสดุ</th>
                                <th>ชื่อพัสดุ</th>
                                <th>จำนวน</th>
                                <th>วันที่เบิก</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('script')
    <script>
        $(function(){
            /** fetch data **/
            let table = $('#myTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('import.history')}}',
                columns:[
                    {data:'DT_RowIndex',class:'text-center',orderable:false,width:'80'},
                    {data:'p_id',class:'pl-3',orderable:false},
                    {data:'p_name',class:'pl-2',orderable:false,width:''},
                    {data:'unit',class:'text-center',orderable:false,width:'100'},
                    {data:'date_in',class:'text-center',orderable:false,width:''},
                    {data:'action',class:'',orderable:false,searchable:false,width:'100'}
                ]
            });

            /** search */
            $('#formSearch').submit(function(e){
                e.preventDefault();
                const query = $('input[name=inputSearch]').val();
                table.search(query).draw();
            });

            /** unit change */
            $(document).on('keypress','#unit',function(e){
                const keycode = (e.keyCode ? e.keyCode : e.which);
                if(keycode === 13) {
                    Swal.fire({
                        icon: 'question',
                        title: 'แก้ไขข้อมูล !',
                        text: 'ต้องการแก้ไขข้อมูลการนำเข้า ใช่หรือไม่ ?',
                        confirmButtonText: 'ยืนยัน',
                        confirmButtonColor: '#007bff',
                        showCancelButton: true,
                        cancelButtonText: 'ยกเลิก',
                        cancelButtonColor: '#dc3545'
                    }).then(result =>{
                        if(result.value) {                
                            axios.put(`/import/data/${$(this).data('id')}`,{unit:this.value})
                            .then(response =>{
                                Swal.fire({
                                    icon: 'success',
                                    title: 'สำเร็จ !',
                                    text: response.data.message,
                                    confirmButtonText: 'ตกลง',
                                    confirmButtonColor: '#007bff',
                                });
                                table.ajax.reload();
                            }).catch(error =>{
                                alert(JSON.stringify(error.response.data));
                            });
                        }
                    });
                }
            });

            /** import delete */
            $(document).on('click','#btnDelete',function(){
                Swal.fire({
                    icon: 'warning',
                    title: 'ลบข้อมูล !',
                    text: 'ต้องการลบข้อมูลการนำเข้า ใช่หรือไม่ ?',
                    confirmButtonText: 'ลบข้อมูล',
                    confirmButtonColor: '#dc3545',
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก',
                    cancelButtonColor: '#007bff'
                }).then(result =>{
                    if(result.value) {
                        axios.delete(`/import/data/${$(this).data('id')}`)
                        .then(response =>{
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ !',
                                text: 'ลบข้อมูลการนำเข้า สำเร็จ ค่ะ',
                                confirmButtonText: 'ตกลง',
                                confirmButtonColor: '#007bff',
                            });
                            table.ajax.reload();
                        });
                    }
                });
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
    </style>
@endpush