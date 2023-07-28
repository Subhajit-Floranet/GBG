@extends('layouts.site.app')

@section('content')

<ul class="breadcrumb">
    <li><a href="{{ url('/') }}">Home</a></li>
    <li><a href="javascript:void(0)" class="tempting">Member Signup</a></li>
</ul>

<section class="sign-up">
    <div class="sign-up-body">
        <h1>Create Your Account</h1>

        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
                <h4 class="font-weight-light alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</h4>
            @endif
        @endforeach

        <form method="POST" action="" id="registrationForm" novalidate="novalidate">
        @csrf
            <div class="sign-up-form flex">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input placeholder="Name*" class="form-control name" id="name" name="name" type="text" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input placeholder="Email*" class="form-control email" id="email" name="email" type="text" required>
                </div>
                <div class="form-group">
                    <label for="mobile">Mobile</label>
                    <input placeholder="Mobile No*" class="form-control mobile" id="mobile_number" name="mobile" type="text" required>
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    @php
                        $get_countries = App\Http\Helper::getAllCountries();
                    @endphp
                    <select class="form-control" name="countries_id" id="countries_id" required="">
                    <option value="">-- Select Country --</option>
                    @if(count($get_countries)>0)
                        @foreach($get_countries as $country)
                            <option value="{{$country->id}}">{{$country->name}}</option>
                        @endforeach
                    @endif
                    </select>
                    @if ($errors->has('countries_id'))
                    <span class="error">
                        {{ $errors->first('countries_id') }}
                    </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input placeholder="Password*" class="pass form-control" id="password" name="password" type="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input required="" placeholder="Confirm Password*" class="form-control confirm_password" id="confirm_password" name="confirm_password" type="password" required>
                </div>
                            
                
            </div>
            <div class="sign-up-btn">
                <button>Sign Up</button>
            </div>
            <div class="sign-up-social">
                <hr>
                {{-- <p>Or Sign Up with social media</p> --}}
                {{-- <div class="fb-n-gle flex my-social1">
                    <a href="javascript:;" onclick="if (!window.__cfRLUnblockHandlers) return false; login_with_facebook();">
                        <img src="https://www.nipponflorist.jp/images/site/button-fb.png" alt="Facebook Login">
                    </a>
                    <a href="javascript:;" onclick="if (!window.__cfRLUnblockHandlers) return false; login_with_google();">
                        <img src="https://www.nipponflorist.jp/images/site/button-google.png" alt="Google Login">
                    </a>
                </div> --}}
                <p>Already member, click here to <a href="{{ route('users.login') }}">Login</a>
                </p>
            </div>
        </form>
    </div>
</section>

<script src="https://apis.google.com/js/platform.js" async defer></script>
<script src="https://apis.google.com/js/client:plusone.js" type="text/javascript"></script>

<script>
$(document).ready(function(){
    $("#registrationForm").validate({
        ignore: ".ignore",
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            },
            confirm_password: {
                required: true,
                minlength: 6,
                equalTo: "#password"
            },
            term_check: {
                required: true
            },
            countries_id: {
                required: true
            },
            // hiddenRecaptcha: {
            //     required: function () {
            //         if (grecaptcha.getResponse() == '') {
            //             return true;
            //         } else {
            //             return false;
            //         }
            //     }
            // }
        }
    });
});

/******************************************************
* Google Login *
*******************************************************/
function login_with_google() {
    $('#log-wrapper').addClass('loading');
    var site_url = $('#websiteurl').val()+'/';
    gapi.load('auth2', function() {
        
        gapi.auth2.authorize({
        client_id: '464197552660-lo03iqhi3uo2ngsb8ajneb5ppskcnne4.apps.googleusercontent.com', //--Testing
        //client_id: '671978063931-t7nk8vcseqpg5nj14obkj1qqvnbmd4as.apps.googleusercontent.com', //--Orginal
        fetch_basic_profile: false,
        scope: 'email profile openid',
        response_type: 'id_token permission'
        },
        function(response) {
        console.log(response);
        if (response.error) {
            // An error happened!
            return false;
        }
        
        // The user authorized the application for the scopes requested.
        var accessToken = response.access_token;
        var idToken = response.id_token;
        // You can also now use gapi.client to perform authenticated requests.
            gapi.client.load('oauth2','v2', function(){
                var request = gapi.client.oauth2.userinfo.get({
                'userId':'me'
                });
                request.execute(function(resp) {
                console.log(resp);
                var g_id=resp.id;
                var name=resp.name;
                var email=resp.email;
                    $.ajaxSetup({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url:"{{route('gmailregister')}}",
                        type:'POST',
                        data: {email:email, name:name},
                        async: false,
                        success:function(result){
                            console.log(result);
                            if(result==1){
                                $('.is_logged_in').val('Y');
                                $('#log-wrapper').removeClass('loading');
                                window.location = "{{route('users.dashboard')}}";
                
                            }else if(result==2){
                                $('#log-wrapper').removeClass('loading');
                                alert('Account is inactive.');
                            }
                            $('#website-loader').hide();
                        }
                    });
                });
            });
        });
    });
}

/******************************************************
* Facebook Login *
*******************************************************/
window.fbAsyncInit = function() {
    FB.init({
      appId      : '126655395434231',
      cookie     : true,  // enable cookies to allow the server to access
                          // the session
      xfbml      : true,  // parse social plugins on this page
      version    : 'v7.0' // use graph api version 2.8
    });
 document.getElementById("fb_btn").disabled=false;
};

// Load the SDK Asynchronously
(function (d) {
    var js, id = 'facebook-jssdk'; if (d.getElementById(id)) { return; }
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    d.getElementsByTagName('head')[0].appendChild(js);
} (document));

function login_with_facebook() {
  FB.login(function(response) {
    if (response.status === 'connected'){
      FB.api('/me', { fields: 'id,name,email,birthday,first_name,last_name,permissions,picture.width(350).height(350)'}, function(response) {
        //$('#website-loader').show();
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        var jqXHR = $.ajax({
              url:"{{route('fbregister')}}",
              type:'POST',
              data: {email: response.email, fb_id: response.id , name:response.name, first_name:response.first_name, last_name:response.last_name,picture: response.picture.data.url,birthday:response.birthday},
              async: false,
              success:function(result){
                  console.log(result);
                  if(result==1){
                    $('.is_logged_in').val('Y');
                    if($('#command').find('.after_login').length){
                      $('#command').find('.after_login').each(function(){
                        var command = $(this).text();
                        command = JSON.parse(command);
                        $('[data-id="'+command.id+'"]').click();
                      });
                      redirectDashboard();
                    }else{
                      window.location = "{{route('users.dashboard')}}";
                    }
                  }else if(result==2){
                    $('.is_logged_in').val('N');
                    alert('Account is inactive.');
                  }
              }
        });
      });
    }
    else{
      console.log('User cancelled login or did not fully authorize.');
    }
  },{scope: 'email,public_profile', return_scopes: true/*scope: 'email,user_photos,public_profile', return_scopes: true*/});
}
</script>

@endsection