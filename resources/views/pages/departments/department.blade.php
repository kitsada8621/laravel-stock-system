@extends('app')
@section('title','ระบบเบิกครุภัณฑ์ ข้อมูลหน่วยงาน')
@section('content')
    <div class="container-fluid">
        
        <h1 class="mt-4">ข้อมูลหน่วยงาน</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">ข้อมูลหน่วยงาน</li>
        </ol>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 card-title">ข้อมูลหน่วยงาน</h5>
                <button class="btn btn-primary font-weight-bold" id="btnAdd"><i class="fas fa-plus mr-2"></i>เพิ่มหน่วยงาน</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTables" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>หน่วยงาน</th>
                                <th>วันที่เพิ่ม</th>
                                <th>อัพเดตล่าสุด</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ===============================================================================############====================================================== -->
    
    <!-- Modal -->
    <div class="modal fade" id="departmentModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-0">
                <form id="departmentForm" action="{{route('department.store')}}">
                <div class="modal-body">
                    @csrf
                    <div class="modal-title mb-4 mt-4 text-center">
                        <h4 class="font-weight-bold modal-title-name"></h4>
                    </div>
                    <input type="hidden" name="id" id="id" readonly>
                    <div class="form-group">
                        <input type="text" name="d_name" id="d_name" class="form-control form-control-lg" placeholder="หน่วยงาน">
                    </div>
                    <div class="form-group text-center">    
                        <button type="submit" id="btnSubmit" class="btn btn-primary font-weight-bold" style="padding:10px 30px;"></button>
                        <button type="button" class="btn btn-dark font-weight-bold" data-dismiss="modal" style="padding:10px 30px;">ยกเลิก</button>
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
            /* fetch data */
            let tables = $('#dataTables').DataTable({
                processing: true,
                serverSidd: true,
                ajax: '/department',
                columns:[
                    {data:'DT_RowIndex',class:'text-center',width:'60'},
                    {data:'d_name',class:'pl-3',orderable:false},
                    {data:'created_at',class:'text-center'},
                    {data:'updated_at',class:'text-center'},
                    {data:'action',class:'text-center',orderable:false,searchable:false},
                ]
            });
            /** search form */
            $('#formSearch').submit(function(e){
                e.preventDefault();
                const query = $('input[name=inputSearch]').val();
                tables.search(query).draw();
            });
            /** add */
            $('#btnAdd').click(function(){
                $('#departmentForm').find('small').remove();
                $('.form-group input').hasClass('is-invalid') ? $('input').removeClass('is-invalid') : '';
                $('#departmentModal').modal('show');
                $('.modal-title-name').text('เพิ่มข้อมูลหน่วยงาน');
                $('#btnSubmit').text('บันทึก');
                $('#id').val('');
                $('#d_name').val('');
            });
            /** edit */
            $(document).on('click','#btnEdit',function(){
                $('.form-group input').hasClass('is-invalid') ? $('input').removeClass('is-invalid') : '';
                $('#departmentForm').find('small').remove();
                $('#departmentModal').modal('show');
                $('.modal-title-name').text('แก้ไขข้อมูลหน่วยงาน');
                $('#btnSubmit').text('อัพเดต');
                $('#id').val($(this).data('id'));
                $('#d_name').val($(this).data('name'));
            });
            /** delete */
            $(document).on('click','#btnDelete',function(){
                const id = $(this).data('id');
                Swal.fire({
                    icon: 'warning',
                    title: 'ลบข้อมูล !',
                    text: 'ต้องการลบข้อมูลหน่วยงาน ใช่หรือไม่ ?',
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ลบข้อมูล',
                    cancelButtonColor: '#007bff',
                    confirmButtonColor: '#dc3545',
                }).then(result =>{
                    if(result.value){
                        axios.delete(`/department/${id}`)
                        .then(response =>{
                            Swal.fire(
                                'สำเร็จ !',
                                'ลบข้อมูลหน่วยงานสำเร็จ ค่ะ',
                                'success'
                            );
                            tables.ajax.reload();
                        });
                    }
                });
            });
            /* form submit **/
            $('#departmentForm').submit(function(e){
                e.preventDefault();
                
                const fd = { id:this.id.value, d_name: this.d_name.value }
                axios.post(this.action,fd)
                .then(response =>{
                    if(response.data.success === true) {
                        $('#departmentModal').modal('hide');
                        Swal.fire(
                            'สำเร็จ !',
                            response.data.message,
                            'success'
                        );
                        tables.ajax.reload();
                    }
                }).catch(error =>{
                    $('#departmentForm').find('small').remove();
                    $('.form-group input').hasClass('is-invalid') ? $('input').removeClass('is-invalid') : '';
                    $.each(error.response.data.errors, function(key,value){
                        $('#'+key).closest('.form-group').append('<small class="text-danger">'+value+'</small>');
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
    </style>
@endpush