@extends('app')
@section('title','ระบบเบิกครุภัณฑ์ ข้อมูลพัสดุ')
@section('content')
    <div class="container-fluid">
        
        <h1 class="mt-4">ข้อมูลพัสดุ</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">ข้อมูลพัสดุ</li>
        </ol>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0">ข้อมูลพัสดุ</h5>
                <button class="btn btn-primary font-weight-bold" id="btnAdd"><i class="fas fa-plus mr-2"></i>เพิ่มข้อมูลพัสดุ</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped" id="mytables" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>รหัสพัสดุ</th>
                                <th>ชื่อพัสดุ</th>
                                <th>ราคา</th>
                                <th>หน่วยนับ</th>
                                <th>ประเภท</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('pages.products.modal')
@endsection
@push('script')
    <script>
        $(function(){
            /** feth data */
            let table = $('#mytables').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/product',
                columns:[
                    {data:'DT_RowIndex',class:'text-center',width:'80'},
                    {data:'p_id',class:'text-center',orderable:false},
                    {data:'p_name',class:'pl-3',orderable:false},
                    {data:'p_price',class:'text-center',orderable:false},
                    {data:'unit_type_name',class:'text-center',width:'150',orderable:false},
                    {data:'p_type_name',class:'pl-1',orderable:false},
                    {data:'action',class:'text-center',orderable:false,searchable:false},
                ]
            });

            /** search */
            $('#formSearch').submit(function(e){
                e.preventDefault();
                const query = $('input[name=inputSearch]').val();
                table.search(query).draw();
            });

            /** insert */
            $('#btnAdd').click(function(){
                $('#productModal').modal('show');
                $('#productModal .modal-title').text('เพิ่มข้อมูลพัสดุ');
                $('#btnConfirm').text('บันทึก');
                $('#p_id').attr('readOnly',false);
                $('#productForm').find('small').remove();
                clearProductForm();
            });

            /** edit **/
            $(document).on('click','#btnEdit',function(){
                $('#productModal').modal('show');
                $('#productModal .modal-title').text('แก้ไขข้อมูลพัสดุ');
                $('#btnConfirm').text('อัพเดต');
                $('#p_id').attr('readOnly',true);
                $('#productForm').find('small').remove();
                $('#id').val($(this).data('p_id'));
                $('#p_id').val($(this).data('p_id'));
                $('#p_name').val($(this).data('p_name'));
                $('#p_price').val($(this).data('p_price'));
                $('#unit_type_id').val($(this).data('unit_type_id')).trigger('change');
                $('#p_type_id').val($(this).data('p_type_id')).trigger('change');
            });

            /** delete */
            $(document).on('click','#btnDelete',function(){
                Swal.fire({
                    icon: 'warning',
                    title: 'ลบข้อมูล !',
                    text: 'ต้องการลบข้อมูล ใช่หรือไม่ ?',
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก',
                    cancelButtonColor: '#007bff',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'ลบข้อมูล',
                }).then(result =>{
                    if(result.value) {
                        const id = $(this).data('id');
                        axios.delete(`/product/${id}`).then(response =>{
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ !',
                                text: 'ลบข้อมูลสำเร็จ ค่ะ',
                                confirmButtonColor: '#007bff',
                                confirmButtonText: 'ตกลง',
                            });
                            table.ajax.reload();
                        });
                    }
                });
            });

            /** form submit */
            $('#productForm').submit(function(e){
                e.preventDefault();
                const fd = $('#productForm').serialize();
                axios.post('/product',fd)
                .then(response =>{
                    $('#productModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ !',
                        text: response.data.message,
                        confirmButtonText: 'ตกลง',
                    });
                    table.ajax.reload();
                }).catch(error =>{
                    $('#productForm').find('small').remove();
                    $.each(error.response.data.errors, function(key,value){
                        $('#'+key).closest('.form-group .col-sm-10').append('<small class="text-danger">'+value+'</small>');
                    });
                });
            });

            /** clear form */
            function clearProductForm () {
                $('#id').val('');
                $('#p_id').val('');
                $('#p_name').val('');
                $('#p_price').val('');
                $('#unit_type_id').val('').trigger('change');
                $('#p_type_id').val('').trigger('change');
            }

            /** select2 setting */
            $('#unit_type_id').select2({
                placeholder: 'กรุณาเลือกประเภทหน่วยนับ',
            });
            $('#p_type_id').select2({
                placeholder: 'กรุณาเลือกประเภทพัสดุ',
            });

            /** filter input */
            $('input[name=p_id]').filter_input({regex:'[a-zA-Z0-9_-]'});  
            $('input[name=p_price]').filter_input({regex:'[0-9]'});  
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