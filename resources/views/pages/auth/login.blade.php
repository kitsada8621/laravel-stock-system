@extends('pages.auth.master')
@section('title','เข้าสู่ระบบ')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">{{ __('ตรวจสอบสิทธ์') }}</h3></div>
                <div class="card-body">
                    <form action="/login" id="formLogin">
                        @csrf
                        <div class="form-group">
                            <label class="small mb-1" for="username">{{__('ชื่อผู้ใช้')}}</label>
                            <input class="form-control py-4" name="username" id="username" type="text" placeholder="Enter username">                      
                        </div>
                        <div class="form-group">
                            <label class="small mb-1" for="password">{{__('รหัสผ่าน')}}</label>
                            <input class="form-control py-4" name="password" id="password" type="password" placeholder="Enter password">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" id="remember" name="remember" type="checkbox" />
                                <label class="custom-control-label" for="remember">{{__('จดจำรหัสผ่าน')}}</label>
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                            <a class="small" href="{{route('password.request')}}">{{__('ลืมรหัสผ่าน')}}</a>
                            <button type="submit" class="btn btn-primary font-weight-bold">{{__('เข้าสู่ระบบ')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
        $(function(){
            $('#formLogin').submit(function(e){
                e.preventDefault();
                const actions = this.action;
                const username = this.username.value;
                const password = this.password.value;
                const remember = this.remember.checked;
                const token = this._token.value;

                axios.post(actions,{
                    username: username,
                    password: password,
                    remember: remember,
                    _token: this._token,
                }).then(response =>{
                    location.href="/";
                }).catch(error =>{
                    $('#formLogin').find('small').remove();
                    $('input').hasClass('is-invalid') ? $('input').removeClass('is-invalid') : '';
                    if(error.response.status === 422) {
                        $.each(error.response.data.errors, function(key,value){
                            $('#'+key).closest('.form-group').append('<small class="invalid-feedback">'+value+'</small>');
                            $('#'+key).closest('.form-group input').addClass('is-invalid');
                        });
                    }
                });

            });

            $('input[name=username]').keyup(function(){

                $('#username').closest('.form-group').find('small').remove();
                $('#username').hasClass('is-invalid') ? $('#username').removeClass('is-invalid') : '';
                if(this.value == "") {
                    if(!$('#username').hasClass('is-invalid'))
                    $('#username').addClass('is-invalid');
                    $('#username').closest('.form-group').append('<small class="invalid-feedback">The username field is required.</small>');
                }
             
            });

            $('input[name=password]').keyup(function(){

                $('#password').closest('.form-group').find('small').remove();
                $('#password').hasClass('is-invalid') ? $('#password').removeClass('is-invalid') : '';
                if(this.value == "") {
                    if(!$('#password').hasClass('is-invalid'))
                    $('#password').addClass('is-invalid');
                    $('#password').closest('.form-group').append('<small class="invalid-feedback">The password field is required.</small>');
                }
             
            });

        });
    </script>
@endpush
@push('style')
    
@endpush