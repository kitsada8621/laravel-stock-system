@extends('app')
@section('title','ระบบเบิกครุภัณฑ์ ข้อมูลหน่วยนับ')
@section('content')
    <div class="container-fluid">
        
        <h1 class="mt-4">ข้อมูลหน่วยนับ</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">ข้อมูลหน่วยนับ</li>
        </ol>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0">ข้อมูลหน่วยนับ</h5>
                <button id="btnAdd" class="btn btn-primary font-weight-bold"><i class="fas fa-plus mr-2"></i>เพิ่มข้อมูล</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped" id="dataTables" width="100%" cellspacing="0">
                        <thead>
                            <th>#</th>
                            <th>หน่วยนับ</th>
                            <th>วันที่เพิ่ม</th>
                            <th>แก้ไขล่าสุด</th>
                            <th></th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Modal -->
    <div class="modal fade" id="unitModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center pt-4 border-0">
                    <h4 class="modal-title font-weight-bold"></h4>         
                </div>
                <form id="formUnit" action="{{route('unit.store')}}">
                <div class="modal-body">
                    @csrf
                    <div class="container">
                        <input type="hidden" name="id" id="id" readonly>
                        <div class="form-group">
                            <input type="text" name="unit_type_name" id="unit_type_name" class="form-control form-control-lg" placeholder="ชื่อหน่วยนับ...">
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" id="btnConfirm" class="btn btn-primary font-weight-bold btn-lg"></button>
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
                ajax: '{{route('unit.index')}}',
                columns:[
                    {data:'DT_RowIndex',class:'text-center',width:'60'},
                    {data:'unit_type_name',class:'text-center',orderable:false},
                    {data:'created_at',class:'text-center',orderable:false,width:'200'},
                    {data:'updated_at',class:'text-center',orderable:false,width:'200'},
                    {data:'action',class:'text-center',orderable:false,searchable:false,width:'200'},
                ]
            });

            /** search */
            $('#formSearch').submit(function(e){
                e.preventDefault();
                const query = $('input[name=inputSearch]').val();
                table.search(query).draw();
            });

            /** click add */
            $('#btnAdd').click(function(){
                $('#formUnit').find('small').remove();
                $('input').hasClass('is-invalid') ? $('input').removeClass('is-invalid'):'';
                $('#unitModal').modal('show');
                $('#unitModal .modal-title').text('เพิ่มหน่วยนับ');
                $('#btnConfirm').text('บันทึก');

                $('#id').val('');
                $('#unit_type_name').val('');
            });

            /** Edit */
            $(document).on('click','#btnEdit',function(){
                $('#formUnit').find('small').remove();
                $('input').hasClass('is-invalid') ? $('input').removeClass('is-invalid'):'';
                $('#unitModal').modal('show');
                $('#unitModal .modal-title').text('แก้ไขข้อมูลหน่วยนับ');
                $('#btnConfirm').text('อัพเดต');

                $('#id').val($(this).data('id'));
                $('#unit_type_name').val($(this).data('name'));
            });

            /*** Delete  */
            $(document).on('click','#btnDelete',function(){
                const id = $(this).data('id');
                Swal.fire({
                    icon:'warning', title: 'ลบข้อมูล !', text: 'ต้องการลบข้อมูล ใช่หรือไม่ ?',
                    showCancelButton: true, cancelButtonColor: '#007bff', cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#dc3545', confirmButtonText:'ลบข้อมูล'
                }).then(result =>{
                    if(result.value) {
                        axios.delete(`/unit/${id}`)
                        .then(response =>{
                            Swal.fire({ icon:'success', title:'สำเร็จ !', text:'ลบข้อมูลสำเร็จ ค่ะ', confirmButtonText:'ตกลง' });
                            table.ajax.reload();
                        });
                    }
                });
            });

            /** form submit */
            $('#formUnit').submit(function(e){
                e.preventDefault();
                $data = {
                    id: this.id.value,
                    unit_type_name: this.unit_type_name.value,
                }
                axios.post(this.action,$data)
                .then(response =>{
                    $('#unitModal').modal('hide');
                    Swal.fire({icon:'success',title:'สำเร็จ',text:response.data.message,confirmButtonText:'ตกลง'});
                    table.ajax.reload();
                }).catch(error =>{
                    $('#formUnit').find('small').remove();
                    $('input').hasClass('is-invalid') ? $('input').removeClass('is-invalid'):'';
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