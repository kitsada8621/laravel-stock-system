@extends('pages.auth.master')
@section('title','')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Password Recovery</h3></div>
                <div class="card-body">
                    <div class="small mb-3 text-muted">Enter your email address and we will send you a link to reset your password.</div>
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="small mb-1" for="email">Email</label>
                            <input class="form-control py-4" id="email" name="email" type="email" aria-describedby="emailHelp" placeholder="Enter email address" />
                        </div>
                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                            <a class="small" href="login.html">Return to login</a>
                            <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
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

        });
    </script>
@endpush
@push('style')
    <style>

    </style>
@endpush