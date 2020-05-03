@extends('app')
@section('title','ระบบเบิกครุภัณฑ์ นำเข้าพัสดุ')
@section('content') 
    <div class="container-fluid">
        
        <h1 class="mt-4">นำเข้าพัสดุ</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">นำเข้าพัสดุ</li>
        </ol>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title m-0">ข้อมูลพัสดุ</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped" id="myTables" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>รหัสพัสดุ</th>
                                <th>ชื่อพัสดุ</th>
                                <th>ราคา/หน่วย</th>
                                <th>จำนวน</th>
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
            /** fetch data */
            let table = $('#myTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('import.index')}}',
                columns:[
                    {data:'DT_RowIndex',class:'text-center',width:'80'},
                    {data:'p_id',class:'pl-2',orderable:false},
                    {data:'p_name',class:'pl-2',orderable:false},
                    {data:'p_price',class:'text-center',orderable:false},
                    {data:'unit',class:'text-center',orderable:false,width:'150'},
                    {data:'action',class:'text-center',orderable:false,searchable:false}
                ]
            });

            /** search */
            $('#formSearch').submit(function(e){
                e.preventDefault();
                const query = $('input[name=inputSearch]').val();
                table.search(query).draw();
            });

            /** Import */
            $(document).on('click','#import',function(){
                const id = $(this).data('id');
                const unit = $(`input[name=${id}]`);

                if(unit.val()) {
                    axios.post(`/import/${id}`,{
                        id:id,
                        unit:unit.val()
                    }).then(response =>{
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ !',
                            text: response.data.message,
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#007bff'
                        });
                        table.ajax.reload();
                    }).catch(error =>{
                        console.log(error.response.data);
                    });
                }else {
                    unit.focus();
                }



            });

            /** input  setting */
            // $(document).on('input[id=unit]',filter_input({regex:'[0-9]'}));
            // $('input[id=unit]').filter_input({regex:'[0-9]'});
        })
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