@extends('app')
@section('title','ระบบเบิกครุภัณฑ์ แก้ไขข้อมูลส่วนตัว')
@section('content')
    <div class="container-fluid">
        
        <h1 class="mt-4">แก้ไขข้อมูลส่วนตัว</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">แก้ไขข้อมูลส่วนตัว</li>
        </ol>

        <div class="card">
            <div class="card-body">
                <form id="userForm" action="{{route('setting.submit',Auth::user()->id)}}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 mx-auto">
                            <div class="d-flex justify-content-center align-items-center flex-column mb-3">
                                <img id="my_profile" @if(Auth::user()->profile) src="/upload/profile/{{Auth::user()->profile}}" @else src="https://www.iconbunny.com/icons/media/catalog/product/1/2/1270.9-user-icon-iconbunny.jpg" @endif alt="" width="150" height="150"  style="border-radius:50%;">
                                <label for="profile" class="btn btn-light btn-sm shadow-sm font-weight-bold mt-2">เลือกรูปภาพ</label>
                                <input type="file" name="profile" id="profile" class="d-none">
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-form-label col-sm-2 text-md-right">ชื่อ-สกุล</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{Auth::user()->name}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="d_id" class="col-form-label col-sm-2 text-md-right">แผนก</label>
                                <div class="col-sm-10">
                                    <select class="custom-select select2" name="d_id" id="d_id">
                                        @foreach (App\Users\Department::get() as $row)
                                            <option {{ Auth::user()->d_id == $row->d_id ? 'selected' : '' }} value="{{$row->d_id}}">{{$row->d_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="role" class="col-form-label col-sm-2 text-md-right">ตำแหน่ง</label>
                                <div class="col-sm-10">
                                    <select class="custom-select select2" name="role" id="role">
                                        <option {{ Auth::user()->role == false ? 'selected' : '' }} value="0">พนักงาน</option>
                                        <option {{ Auth::user()->role == true ? 'selected' : '' }} value="1">แอดมิน</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-form-label col-sm-2 text-md-right">อีเมลล์</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="email" id="email" value="{{Auth::user()->email}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="username" class="col-form-label col-sm-2 text-md-right">ชื่อผู้ใช้งาน</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="username" id="username" value="{{Auth::user()->username}}">
                                </div>
                            </div>
                            <div class="alert alert-info col-sm-10 offset-2 align-self-center" role="alert">
                                <i class="fas fa-exclamation-triangle text-warning"></i> <span class="small">ยืนยันรหัสผ่าน</span>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-form-label col-sm-2 text-md-right">รหัสผ่าน</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="password" id="password">
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary font-weight-bold">บันทึก</button>
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

            $('#profile').change(function(){
                previewProfiles(this);
            });
            // submit form
            $('#userForm').submit(function(e){
                e.preventDefault();
                const config = { headers:{ 'content-type': 'multipart/form-data' } }
                const fd = new FormData(this);
                axios.post(this.action,fd,config)
                .then(response =>{
                    Swal.fire({
                        icon:'success',
                        title:'สำเร็จ',
                        text:response.data.message,
                        confirmButtonText:'ตกลง',
                        confirmButtonColor: '#007bff',
                    }).then(result =>{
                        location.reload();
                    });
                }).catch(error =>{
                    $('#userForm').find('small').remove();
                    if(error.response.status === 422) {
                        $.each(error.response.data.errors, function(key,value){
                            $('#'+key).closest('.form-group .col-sm-10').append('<small class="text-danger">'+value+'</small>');
                        });
                    }
                    if(error.response.status === 404) {
                        $('#password').closest('.form-group .col-sm-10').append('<small class="text-danger">'+error.response.data.message+'</small>');
                    }
                });
            });
            // preview image profile
            function previewProfiles(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    
                    reader.onload = function(e) {
                    $('#my_profile').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

        });
    </script>
@endpush
@push('styles')
    <style>
    </style>
@endpush