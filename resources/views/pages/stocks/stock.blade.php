@extends('app')
@section('title','ระบบเบิกครุภัณฑ์ พัสดุทั้งหมด')
@section('content')
    <div class="container-fluid">

        <h1 class="mt-4">พัสดุทั้งหมด</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">พัสดุทั้งหมด</li>
        </ol>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title m-0">ข้อมูลพัสดุ</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped" id="mytable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>รหัสพัสดุ</th>
                                <th>ชื่อพัสดุ</th>
                                <th>ราคา/หน่วย</th>
                                <th>จำนวน</th>
                                <th>รวม</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('script')
    <script>
        $(function(){
            /** feth data */
            let table = $('#mytable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('stock.index')}}',
                columns:[
                    {data:'DT_RowIndex',class:'text-center',width:'80'},
                    {data:'p_id',class:'pl-3'},
                    {data:'p_name',class:'pl-3'},
                    {data:'p_price',class:'text-center'},
                    {data:'unit',class:'text-center',width:'80'},
                    {data:'total',class:'text-center'},
                ]
            });

            /** search */
            $('#formSearch').submit(function(e){
                e.preventDefault();
                const query = $('input[name=inputSearch]').val();
                table.search(query).draw();
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