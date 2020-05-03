@extends('app')
@section('title','ระบบเบิกครุภัณฑ์ เบิกพัสดุ')
@section('content')
    <div class="container-fluid">
        
        <h1 class="mt-4">เบิกพัสดุ</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">เบิกพัสดุ</li>
        </ol>

        <div class="card">
            <h5 class="card-header font-weight-bold">รายการพัสดุ</h5>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped" id="myTables" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>รหัสพัสดุ</th>
                                <th>ชื่อพัสดุ</th>
                                <th>ราคา/หน่วย</th>
                                <th>ประเภท</th>
                                <th>คลัง</th>
                                <th>แก้ไขล่าสุด</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>


    <!-- ==================================================================== ====================================================================  -->

    <!-- Modal -->
    <div class="modal fade" id="saleModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="saleForm">
                    <div class="container-fluid">
                        @csrf
                        <div class="modal-title p-2 mb-3 text-center">
                            <h2 class="font-weight-bold">เบิกพัสดุ</h2>
                        </div>
                        <input type="hidden" name="id" id="id" readonly>
                        <div class="form-group">
                          <input type="number" name="unit" id="unit" class="form-control form-control-lg" placeholder="จำนวน" min="0" required autocomplete="off">
                        </div>
                        <div class="form-group mb-4">
                          <input type="text" name="users" id="users" class="form-control form-control-lg" placeholder="ผู้เบิก" autocomplete="off" @if(!Auth::user()->role) readonly @endif>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary font-weight-bold btn-lg">บันทึก</button>
                            <button type="button" class="btn btn-secondary font-weight-bold btn-lg" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script>
        $(function(){
            /**  fetch data */
            let table = $('#myTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('sale.index')}}',
                columns:[
                    {data:'DT_RowIndex',class:'text-center',width:'80'},
                    {data:'p_id',class:'',orderable:false},
                    {data:'p_name',class:'',orderable:false},
                    {data:'p_price',class:'text-center',orderable:false},
                    {data:'p_type_name',class:'text-center',orderable:false},
                    {data:'unit',class:'text-center',orderable:false,width:'100'},
                    {data:'updated_at',class:'text-center',orderable:false},
                    {data:'action',class:'text-center',orderable:false,searchable:false}
                ]
            });

            /** search */
            $('#formSearch').submit(function(e){
                e.preventDefault();
                const query = $('input[name=inputSearch]').val();
                table.search(query).draw();
            });

            /** sale */
            $(document).on('click','#btnSale',function(){
                $('#saleModal').modal('show');

                $('#id').val($(this).data('id'));
                $('#unit').val('');
                $('#users').val('');
                $('#saleForm').find('small').remove();
            });

            /** confrim sale */
            $('#saleForm').submit(function(e){
                e.preventDefault();
                axios.put(`/sale/${this.id.value}`,{
                    'unit': this.unit.value,
                    'users': this.users.value,
                }).then(response =>{
                    $('#saleModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: response.data.message,
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#007bff',
                    });
                    table.ajax.reload();
                }).catch(error =>{
                    console.log(error.response.data);
                });
            });

            $('input[name=users]').typeahead({
                source:  function (query, process) {
                    return axios.get('/autocomplete/user/name',{query: query})
                    .then(res =>{
                        return process(res.data);
                    });
                }
            });


            /** filter input */
            $('input[name=p_id]').filter_input({regex:'[a-zA-Z0-9_-]'});  
            $('input[name=unit]').filter_input({regex:'[0-9]'});  
        });
    </script>

@endpush
@push('styles')
    <link rel="stylesheet" href="{{asset('asset/css/jquery-ui.css')}}">
    <style>
        .dataTables_filter, 
        .dataTables_length,
        .dataTables_info {
            display: none;
        }
        .table.dataTable {
            margin-top: 0 !important;
            margin-bottom: 8px !important;
            border-collapse: collapse !important;
        }
        div.dataTables_wrapper 
        div.dataTables_paginate ul.pagination{
            margin: 10px 0;
            padding: 0 10px;
        }
    </style>
@endpush