@extends('app')
@section('title','ระบบเบิกครุภัณฑ์ ข้อมูลประเภทพัสดุ')
@section('content')
    <div class="container-fluid">
        
        <h1 class="mt-4">ประเภทพัสดุ</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">ข้อมูลประเภทพัสดุ</li>
        </ol>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">ข้อมูลประเภทพัสดุ</h5>
                <button class="btn btn-primary font-weight-bold" id="btnAdd"><i class="fas fa-plus mr-2"></i>เพิ่มประเภทพัสดุ</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped" id="dataTables" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ประเภทพัสดุ</th>
                                <th>วันที่เพิ่ม</th>
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


    <!-- Modal -->
    <div class="modal fade" id="typeModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center border-0 pt-4">
                    <h4 class="modal-title font-weight-bold"></h4>
                </div>
                <form id="formTypes" action="{{route('type.store')}}">
                <div class="modal-body">
                    @csrf
                    <div class="container">
                        <input type="hidden" name="id" id="id" readonly>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-lg" name="p_type_name" id="p_type_name" placeholder="ชื่อพัสดุ...">
                        </div>
                        <div class="form-group mb-3 text-right">
                            <button type="submit" id="btnClose" class="btn btn-primary font-weight-bold btn-lg"></button>
                            <button type="button" class="btn btn-dark font-weight-bold btn-lg" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        $(function(){
            /** fetch data */
            let table = $('#dataTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('type.index')}}',
                columns:[
                    {data:'DT_RowIndex',class:'text-center',width:'80'},
                    {data:'p_type_name',class:'text-center'},
                    {data:'created_at',class:'text-center',width:'200'},
                    {data:'updated_at',class:'text-center',width:'200'},
                    {data:'action',class:'text-center',orderable:false, searchable:false ,width:'250'},
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
                $('#typeModal').modal('show');
                $('#typeModal .modal-title').text('เพิ่มประเภทพัสดุ');
                $('#btnClose').text('บันทึก');

                $('#formTypes').find('small').remove();
                if($('input').hasClass('is-invalid')) {
                    $('input').removeClass('is-invalid');
                }
                $('#id').val('');
                $('#p_type_name').val('');
            });

            /** Edit */
            $(document).on('click','#btnEdit',function(){
                $('#typeModal').modal('show');
                $('#typeModal .modal-title').text('แก้ไขประเภทพัสดุ');
                $('#btnClose').text('อัพเดต');

                $('#formTypes').find('small').remove();
                if($('input').hasClass('is-invalid')) {
                    $('input').removeClass('is-invalid');
                }
                $('#id').val($(this).data('id'));
                $('#p_type_name').val($(this).data('name'));
            });

            /** Delete */
            $(document).on('click','#btnDelete', function(){
                Swal.fire({
                    icon: 'warning',
                    title: 'ลบข้อมูล',
                    text: 'ต้องการลบข้อมูล ใช่หรือไม่ ?',
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก',
                    cancelButtonColor: '#007bff',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'ลบข้อมูล'
                }).then(result =>{
                    if(result.value) {
                        const id = $(this).data('id');
                        axios.delete(`/type/${id}`)
                        .then(response =>{
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ !',
                                text: 'ลบข้อมูลสำเร็จ ค่ะ',
                                confirmButtonText: 'ตกลง',
                                confirmButtonColor: '#007bff',
                            });
                            table.ajax.reload();
                        });
                    }
                });
            });

            /** form submit */
            $('#formTypes').submit(function(e){
                e.preventDefault();
                axios.post(this.action,{
                    id: this.id.value, p_type_name: this.p_type_name.value
                }).then(response =>{
                    $('#typeModal').modal('hide');
                    Swal.fire({ icon: 'success', title:'สำเร็จ !', text:response.data.message, confirmButtonText: 'ตกลง' });
                    table.ajax.reload();
                }).catch(error =>{
                    $('#formTypes').find('small').remove();
                    if($('input').hasClass('is-invalid')) {
                        $('input').removeClass('is-invalid');
                    }
                    $.each(error.response.data.errors, function(key,value){
                        $('#'+key).closest('.form-group').append('<small class="invalid-feedback">'+value+'</small>');
                        $('#'+key).closest('.form-group input').addClass('is-invalid');
                    });
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