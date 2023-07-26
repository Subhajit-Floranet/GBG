<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>Financial Health - Admin panel</title>
    <!-- Custom CSS -->
    <link href="{{ asset('admin/dist/css/style.min.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
        <style>
            .powered-by {display: flex;justify-content: center;/* color: #fff; */align-items: center; margin-top: 40px; background-color: #ffffffb5; width: 360px; border-radius: 5px;}
            .powered-by p {padding-right: 10px; padding-top: 20px; font-size: 18px;}
            .powered-by img {width: 37px; margin-left: -14px;}
            .msg-danger{position: absolute; top: 93%; color: #e98c7f;}
        </style>

</head>

<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
            <div class="auth-box bg-dark border-top border-secondary">
                
                <div id="loginform">
                    <div class="text-center p-t-20 p-b-20">
                        <span class="db">
                            {{-- <img src="{{ asset('images/logo-1.jpg') }}" alt="logo" style="width: 100%; height:12vh; border-radius:5px; opacity: 0.7;" /> --}}
                            <h2 style="color:#cecece">RSPL ADMIN PANEL</h2>
                        </span>
                    </div>
                    <!-- Form -->
                    @if(Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">{{Session::get('success')}}</h4>
                        </div>
                    @endif
                    @if(Session::has('error'))
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">{{Session::get('error')}}</h4>
                        </div>
                    @endif
                    <form action="{{route('admin.dologin')}}" method="POST" class="form-horizontal m-t-20" id="loginform">
                    @csrf
                        <div class="row p-b-30">
                            <div class="col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-success text-white" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-lg" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" id="email" name="email"  value="{{old('email')}}" required="">
                                    <div class="msg-danger">@error('email'){{$message}}@enderror</div>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-warning text-white" id="basic-addon2"><i class="ti-pencil"></i></span>
                                    </div>
                                    <input type="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" id="password" name="password" required="">
                                    <span class="msg-danger">@error('password'){{$message}}@enderror</span>
                                </div>
                            </div>
                        </div>
                        <div class="row border-top border-secondary">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="p-t-20">
                                        
                                        <button class="btn btn-success float-right" type="submit"><b>LOGIN</b></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="powered-by">
                    <p>Powered By Right Shopping Pvt. Ltd.</p>
                </div>
            </div>
        </div>
        
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="{{ asset('admin/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('admin/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>

    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();
    // ============================================================== 
    // Login and Recover Password 
    // ============================================================== 
    $('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });
    $('#to-login').click(function(){
        
        $("#recoverform").hide();
        $("#loginform").fadeIn();
    });
    </script>

</body>

</html>