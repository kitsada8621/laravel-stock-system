@extends('app')
@section('title','ระบบเบิกครุภัณฑ์ แก้ไขรหัสผ่าน')
@section('content')
    <div class="container-fluid">
        
        <h1 class="mt-4">แก้ไขรหัสผ่าน</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">แก้ไขรหัสผ่าน</li>
        </ol>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-2 mt-lg-5">
                    <form id="passform" action="/setting/pass/{{Auth::user()->id}}">
                        @csrf
                        <div class="form-group row">
                            <label for="password" class="col-form-label col-sm-3 text-md-right">รหัสผ่านใหม่</label>
                            <div class="col-sm-9">
                                <input type="password" name="password" id="password" class="form-control" autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password_confirmation" class="col-form-label col-sm-3 text-md-right">ยืนยันรหัสผ่าน</label>
                            <div class="col-sm-9">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="password_old" class="col-form-label col-sm-3 text-md-right">รหัสผ่านเก่า</label>
                            <div class="col-sm-9">
                                <input type="password" name="password_old" id="password_old" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-check-circle"></i> เปลี่ยนรหัสผ่าน</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('script')
    <script>
        $(function(){
            // update password
            $('#passform').submit(function(e){
                e.preventDefault();
                axios.patch(this.action,$('#passform').serialize()).then(response =>{
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ !',
                        text: response.data.message,
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#007bff'
                    }).then(result =>{
                        location.reload();
                    });
                }).catch(error =>{
                    $('#passform').find('small').remove();
                    $('input').hasClass('is-invalid') ? $('input').removeClass('is-invalid') : '';
                    if(error.response.status === 422) {
                        $.each(error.response.data.errors, function(key,value){
                            // remove errors
                            // $('#'+key).hasClass('is-invalid') ? $('#'+key).removeClass('is-invalid') : '';
                            // show errors
                            $('#'+key).closest('.form-group input').addClass('is-invalid');
                            $('#'+key).closest('.form-group .col-sm-9').append('<small class="invalid-feedback">'+value+'</small>');
                        });
                    }
                    if(error.response.status === 404) {
                        // $('#password_old').hasClass('is-invalid') ? $('#password_old').removeClass('is-invalid') : '';
                        // $('#password_old').closest('.form-group').find('small').remove();
                        $('#password_old').closest('.form-group input').addClass('is-invalid');
                        $('#password_old').closest('.form-group .col-sm-9').append('<small class="invalid-feedback">'+error.response.data.message+'</small>');
                    }
                });
            });
        });
    </script>
@endpush
@push('styles')
    
@endpush