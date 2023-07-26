<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <h2>Login</h2>
            @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger">{{Session::get('error')}}</div>
            @endif
            <div class="col-md-5">
                <form action="{{route('user.dologin')}}" method="POST">
                    @csrf
                    <div class="lpr-user-name">
                        <label><i class="fa fa-user"></i> Email</label>
                        <input type="text" id="email" name="email" value="{{old('email')}}" placeholder="Type your email address">
                        <span class="text-danger">@error('email'){{$message}}@enderror</span>
                    </div>
                    <br><br>
                    <div class="lpr-password">
                        <label><i class="fa fa-lock"></i> Password</label>
                        <input type="password" id="password" name="password" placeholder="Type your password">
                        <span class="text-danger">@error('password'){{$message}}@enderror</span>
    
                        {{-- <p class="forgot-pass"><a href="#">Forgot Password?</a></p> --}}
                    </div>
                    <button type="submit" class="login-btn">Login</button>
                </form>
            </div>
        </div>
    </div>
    

</body>
</html>