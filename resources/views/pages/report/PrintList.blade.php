@extends('app')
@section('title','ระบบเบิกครุภัณฑ์ ออกรายงาน')
@section('content')

    <div class="container-fluid">
        
        <h1 class="mt-4">ออกรายงานการเบิก</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">ออกรายงานรายการเบิก</li>
        </ol>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">รายการเบิกพัสดุ</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered" id="myTables" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>พัสดุ</th>
                                <th>จำนวน</th>
                                <th>ผู้เบิก</th>
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

@endsection
@push('script')
    <script>
        $(function(){
            // fetch data
            let tables = $('#myTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/report/list',
                columns:[
                    {data:'DT_RowIndex',class:'text-center',width:'60'},
                    {data:'p_id',class:'pl-3',orderable:false},
                    {data:'p_sale_unit',class:'text-center',orderable:false,width:'100'},
                    {data:'name',class:'text-center',orderable:false},
                    {data:'print',class:'text-center',orderable:false,searchable:false,width:'60'},
                ]
            });

            // print 
            $(document).on('click','#btnPrinted',function(){
                window.open(`/report/sale/${$(this).data('id')}`)
                // location.href=``;
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