@extends('pages.auth.master')
@section('title','ลืมรหัสผ่าน')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">ลืมรหัสผ่าน</h3></div>
                <div class="card-body">
                    
                    {{-- @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @else
                    @endif --}}
                    <div id="message_alert" class="alert alert-success" role="alert" style="display:none;">
                        <small class="messages"></small>
                    </div>
                    <div class="small mb-3 text-muted text-center">ป้อนที่อยู่อีเมลของคุณแล้วเราจะส่งลิงก์ให้คุณรีเซ็ตรหัสผ่าน</div>
                    <form method="POST" action="{{ route('password.email') }}" id="formPassword">
                        @csrf
                        <div class="form-group">
                            <label class="small mb-1" for="email">อีเมลล์</label>
                            <input class="form-control py-4" id="email" name="email" type="email" aria-describedby="emailHelp" placeholder="Enter email address" />
                            {{-- @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror --}}
                        </div>
                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                            <a class="small" href="{{route('login')}}">กลับไปยัง หน้าล็อกอิน</a>
                            <button type="submit" class="btn btn-primary font-weight-bold">ส่งลิงค์รีเซ็ตรหัสผ่าน</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <div class="small"><a href="#">Need an account? Sign up!</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
        $(function(){

            $('#formPassword').submit(function(e){
                e.preventDefault();

                const actions = this.action;
                const email = this.email.value;
                const token = this._token.value;

                axios.post(actions,{
                    _token: token,
                    email: email,
                }).then(response =>{
                    $('#message_alert').show();
                    $('.messages').text(response.data.message);
                    $('#email').val('');
                    console.log(response.data.message);
                }).catch(error =>{
                    // remove error
                    $('#formPassword').find('small').remove();
                    $('input').hasClass('is-invalid') ? $('input').removeClass('is-invalid') : '';
                    // show error
                    if(error.response.status === 422) {
                        $.each(error.response.data.errors, function(key,value){
                            $('#'+key).closest('.form-group').append('<small class="invalid-feedback">'+value+'</small>');
                            $('#'+key).closest('.form-group input').addClass('is-invalid');
                        });
                    }
                });
            });

            $('#email').keyup(function(){

                //remove
                $('#formPassword').find('small').remove();
                $('#email').hasClass('is-invalid') ? $('#email').removeClass('is-invalid') : '';
                // add and show
                if(!this.value) {
                    $('#email').addClass('is-invalid');
                    $('#email').closest('.form-group').append('<small class="invalid-feedback">The email field is required.</small>');
                }
            });

        });
    </script>
@endpush
@push('style')
    
@endpush