@extends('pages.auth.master')
@section('title','รหัสผ่านใหม่')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">{{__('รีเซ็ตรหัสผ่าน')}}</h3></div>
                <div class="card-body">
                    <div id="message_alert" class="alert alert-success" role="alert" style="display:none;">
                        <small class="messages"></small>
                    </div>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group">
                            <label class="small mb-1" for="email">{{__('อีเมลล์')}}</label>
                            <input class="form-control py-4 @error('email') is-invalid @enderror" value="{{ $email ?? old('email') }}" id="email" name="email" type="email" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="small mb-1" for="email">{{__('รหัสผ่าน')}}</label>
                            <input class="form-control py-4 @error('password') is-invalid @enderror" id="password" name="password" type="password" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="small mb-1" for="password_confirmation">{{__('ยืนยัน รหัสผ่าน')}}</label>
                            <input class="form-control py-4" id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn btn-primary font-weight-bold float-right">{{__('รีเซ็ตรหัสผ่าน')}}</button>
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

        });
    </script>
@endpush
@push('style')
    
@endpush