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
            <h2>Register</h2>
            @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger">{{Session::get('error')}}</div>
            @endif
            <div class="col-md-5">
                <form action="{{route('user.create-user')}}" method="POST">
                    @csrf
                    <input type="text" placeholder="Your Name" id="name" name="name" value="{{old('name')}}">
                    <span class="text-danger">@error('name'){{$message}}@enderror</span>
                    <br><br>
                    <input type="text" placeholder="Email address" id="email" name="email" value="{{old('email')}}">
                    <span class="text-danger">@error('email'){{$message}}@enderror</span>
                    <br><br>
                    <input type="password" placeholder="New Password" id="password" name="password">
                    <span class="text-danger">@error('password'){{$message}}@enderror</span>
                    <br><br>
                    <input type="password" placeholder="Confirm Password" id="cpassword" name="cpassword">
                    <span class="text-danger">@error('cpassword'){{$message}}@enderror</span>
                    <br><br>
                    <div class="form-group row">
                      <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Register</button>
                      </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

</body>
</html>