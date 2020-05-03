<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>@yield('title')</title>
        <link href="{{asset('asset/css/styles.css')}}" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <link rel="stylesheet" href="{{asset('asset/css/custom-select2.css')}}">
        <link href="{{asset('asset/css/select2.min.css')}}" rel="stylesheet" />
        <style>
            ::-webkit-scrollbar {
                width: 5px;
            }
            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }
            ::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 0.25rem;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #555;
            }        
        </style>
        @stack('styles')
    </head>
    <body>
        <div class="sb-nav-fixed">
            <x-navbar/>
            <div id="layoutSidenav">
                <x-sidebar/>
                <div id="layoutSidenav_content">
                    <main>
                        @yield('content')                   
                    </main>
                    <x-footer/>
                </div>
            </div>
        </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="{{asset('asset/js/jquery.filter_input.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{asset('asset/js/printThis.js')}}"></script>
        <script src="{{asset('asset/js/scripts.js')}}"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        <script>
            $(function(){

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                /** select 2 */
                /** select2 bootstarp4 */
                $( ".select2" ).select2();

                /** logout */
                $('#loggedout').click(function(){
                    axios.get('/logout')
                    .then(response =>{
                        location.href="/login";
                    }).catch(error =>{
                        console.log(error.response.status);
                    });
                });
            });
        </script>
        @stack('script')

    </body>
</html>
