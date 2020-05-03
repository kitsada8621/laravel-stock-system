@extends('app')
@section('title','ระบบเบิกครุภัณฑ์ คืนพัสดุ')
@section('content')
    <div class="container-fluid">
        
        <h1 class="mt-4">คืนพัสดุ</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">คืนพัสดุ</li>
        </ol>

        <div class="card mb-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped" id="myTables" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th></th>
                                <th>รหัสพัสดุ</th>
                                <th>ชื่อพัสดุ</th>
                                <th>ผู้เบิก</th>
                                <th>วันที่เบิก</th>
                                <th>จำนวน</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================================================================== modal =====================================================================-->


@endsection
@push('script')
    <script>
        $(function(){
            /** fetch data */
            let table = $('#myTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/product/return',
                columns:[
                    {data:'DT_RowIndex',class:'text-center'},
                    {data:'p_id',orderable:false},
                    {data:'p_name',orderable:false},
                    {data:'name',class:'pl-1',orderable:false},
                    {data:'times_in',class:'text-center',orderable:false},
                    {data:'unit',class:'text-center',orderable:false,searchable:false},
                    {data:'action',class:'text-center',orderable:false,searchable:false},
                ]
            });

            /** search */
            $('#formSearch').submit(function(e){
                e.preventDefault();
                const query = $('input[name=inputSearch]').val();
                table.search(query).draw();
            });

            /** click return */
            $(document).on('click','#btnReturn',function(){
                const id = $(this).data('id');
                const unit = $(`input[name=${id}]`).val();
                Swal.fire({
                    icon:'question',
                    title: 'คืนพัสดุ',
                    text: 'ต้องการคืนพัสดุ ใช่หรือไม่ ?',
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'ยืนยัน',
                    confrimButtonColor: '#007bff',
                }).then(result => {
                    if(result.value) {
                        axios.post(`/product/return/${id}`,{
                            unit: unit
                        }).then(response =>{
                            Swal.fire({
                                icon:'success',
                                title: 'สำเร็จ !',
                                text: response.data.message,
                                confirmButtonText: 'ตกลง',
                                confrimButtonColor: '#007bff',
                            });
                            table.ajax.reload();
                        }).catch(error =>{
                            alert(JSON.stringify(error.response.data));
                        });
                    }
                });
            })
                 

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