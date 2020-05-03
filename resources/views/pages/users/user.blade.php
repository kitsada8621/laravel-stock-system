@extends('app')
@section('title','ระบบเบิกครุภัณฑ์ ข้อมูลพนักงาน')
@section('content')
    <div class="container-fluid">
        
        <h1 class="mt-4">ข้อมูลหน่วยงาน</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">ข้อมูลหน่วยงาน</li>
        </ol>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">ข้อมูลพนักงาน</h5>
                <button id="btnAdd" class="btn btn-primary font-weight-bold"><i class="fas fa-plus mr-2"></i>เพิ่มพนักงาน</button>
            </div>
            <div class="card-body m-0 p-0">
                <div class="table-responsive">
                    <table class="table table-striped" id="dataTables" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>โปรไฟล์</th>
                                <th>ชื่อ-สกุล</th>
                                <th>หน่วยงาน</th>
                                <th>ตำแหน่ง</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================================================############## Modal Bootstarp4 ##################================================================== -->
    
    @include('pages.users.modal')

@endsection
@push('script')
    <script>
        $(function(){

            /** fetch data */
            let table = $('#dataTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/user',
                columns:[
                    {data:'DT_RowIndex',class:'text-center'},
                    {data:'profile',class:'text-center',orderable:false,searchable:false},
                    {data:'name',class:'pl-3',orderable:false},
                    {data:'d_name',class:'text-center',orderable:false},
                    {data:'role',class:'text-center',orderable:false},
                    {data:'action',class:'text-center',orderable:false,searchable:false},
                ]
            });

            /** search form */
            $('#formSearch').submit(function(e){
                e.preventDefault();
                const query = $('input[name=inputSearch]').val();
                table.search(query).draw();
            });

            /** Insert */
            $('#btnAdd').click(function(){
                $('#userForm').find('small').remove();
                $('#usersModal').modal('show');
                $('#usersModal .modal-title-name').text('เพิ่มข้อมูลพนักงาน');
                $('#btnSubmit').text('บันทึก');
                inputClear();
            });

            /** update */
            $(document).on('click','#btnEdit',function(){
                $('#userForm').find('small').remove();
                $('#usersModal').modal('show');
                $('#usersModal .modal-title-name').text('แก้ไขข้อมูลพนักงาน');
                $('#btnSubmit').text('อัพเดต');

                $('#id').val($(this).data('id'));
                $('#name').val($(this).data('name'));
                $('#d_id').val($(this).data('d_id')).trigger('change');
                $('#role').val($(this).data('role')).trigger('change');
                $('input[name=role]').val("1").attr('checked',true);
                $('#email').val($(this).data('email'));
                $('#username').val($(this).data('username'));
                $('#password').val($(this).data('password'));
                $('#confirmation_password').val($(this).data('password'));
                if($(this).data('profile')) {
                    $('.profile-preview').attr('src',`/upload/profile/${$(this).data('profile')}`);
                }else {
                    $('.profile-preview').attr('src','https://st3.depositphotos.com/4111759/13425/v/450/depositphotos_134255710-stock-illustration-avatar-vector-male-profile-gray.jpg');
                }

            });

            /** delete */
            $(document).on('click','#btnDelete',function(){
                const id = $(this).data('id');
                const name = $(this).data('name');
                Swal.fire({
                    icon: 'warning',
                    title: 'ลบข้อมูล !',
                    text: `ต้องการลบข้อมูลคุณ${name} ใช่หรือไม่ ?`,
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก',
                    cancelButtonColor: '#007bff',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'ลบข้อมูล'
                }).then(result =>{
                    if(result.value) {
                        axios.delete(`/user/${id}`)
                        .then(response =>{
                            Swal.fire('สำเร็จ !','ลบข้อมูลพนักงาน สำเร็จค่ะ','success');
                            table.ajax.reload();
                        });
                    }
                });
            });

            /** details */
            $(document).on('click','#btnDetails',function(){
                $('#userDetails').modal('show');
                $('#imageDetails').attr('src',`/upload/profile/${$(this).data('profile')}`);
                $('.details-name').text($(this).data('name'));
                $('.detail-department').text($(this).data('d_name'));
                $('.detail-role').text($(this).data('role_name'));
                $('.detail-email').text($(this).data('email'));
                $('.detail-username').text($(this).data('username'));
                $('.detail-created').text($(this).data('created'));
                $('.detail-updated').text($(this).data('updated'));
            });

            

            /** preview image */
            $('#profile').change(function(){
                readURL(this);
            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    
                    reader.onload = function(e) {
                    $('.profile-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }


            /** input clear */
            function inputClear() {
                $('#id').val('');
                $('#name').val('');
                $('#d_id').val('').trigger('change');
                $('#role').val('').trigger('change');
                $('#email').val('');
                $('#username').val('');
                $('#password').val('');
                $('#confirmation_password').val('');
                $('#profile').val('');
                $('.profile-preview').attr('src','https://st3.depositphotos.com/4111759/13425/v/450/depositphotos_134255710-stock-illustration-avatar-vector-male-profile-gray.jpg');
            }

            /** form submit */
            $('#userForm').submit(function(e){
                e.preventDefault();
                let fd = new FormData(this);
                const config = { headers:{ 'content-type': 'multipart/form-data' } }
                axios.post(this.action,fd,config)
                .then((response)=>{
                    $('#usersModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ !',
                        text: response.data.message,
                        confirmButtonText: 'ตกลง'
                    });
                }).catch((error)=>{
                    $('#userForm').find('small').remove();
                    $.each(error.response.data.errors, function(key,value){
                        $('#'+key).closest('.form-group .col-sm-10').append('<small class="text-danger">'+value+'</small>');
                    });
                });
            });

            /** setting placeholder */
            $('#d_id').select2({
                placeholder: 'เลือกข้อมูล'
            });
            $('#role').select2({
                placeholder: 'เลือกข้อมูล'
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