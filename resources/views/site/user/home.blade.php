<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <p>{{Auth::guard('web')->user()->name}}</p>
            <p>{{Auth::guard('web')->user()->email}}</p>
            <p>Testing for GitHUB</p>
            <p>Testing for GitHUB</p>
            <p>
                <a href="{{route('user.logout')}}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">LOGOUT</a>
                
                <form id="logout-form" action="{{route('user.logout')}}" method="POST">
                    @csrf
                </form>
            </p>
        </div>
    </div>
</body>
</html>