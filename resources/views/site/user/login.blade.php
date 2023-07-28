@extends('layouts.site.app')

@section('content')

<ul class="breadcrumb">
    <li><a href="{{ url('/') }}">Home</a></li>
    <li><a href="#" class="tempting">Member Login</a></li>
</ul>

<section class="log-in">
    <div class="log-in-body">
        <h1>Member Log In</h1>
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
                <h4 class="font-weight-light alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</h4>
            @endif
        @endforeach 
        <form method="POST" accept-charset="UTF-8" id="Userlogin" novalidate="">
            {{ csrf_field() }}
            <div class="log-in-form flex">
                <div class="l-form-group">
                    <label for="email">Email</label>
                    <div class="mno flex">
                        <div class="l-prefix">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input required="" placeholder="Email *" class="form-control log-inputtextfield login_email valid" id="email" name="email" type="text" required>
                    </div>
                </div>
                <div class="l-form-group">
                    <label for="password">Password</label>
                    <div class="mno flex">
                        <div class="l-prefix">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input required="" placeholder="Password *" class="form-control pass log-inputtextfield login_password valid" id="password" name="password" type="password" value="" required>
                    </div>
                </div>
            </div>
            <div class="forgot-pass"><a href="javascript:void(0)" id="forgotpassword">Forgot Password ?</a></div>
            <div class="log-in-btn"><button>Log In</button></div>
            <div class="log-in-social">
                <hr>
                <p>Or Login Using</p>
                <div class="fb-n-gle flex my-social">
                    <a href="javascript:;" onclick="login_with_facebook();">
                        <img src="https://www.nipponflorist.jp/images/site/button-fb.png" alt="">
                    </a>
                    <a href="javascript:;" onclick="login_with_google();">
                        <img src="https://www.nipponflorist.jp/images/site/button-google.png" alt="">
                    </a>
                </div>
                <p>New User, Click to <a href="{{ route('users.register') }}">Sign Up</a></p>
            </div>
        </form>
    </div>
</section>

<!-----------------Modal Forgot Password---------------->
<div class="pass-modal" id="resetpass">
    <div class="modal-content">
        <div class="pass-modal-heading">
            <p class="forgot-pass-head"><i class="fa-solid fa-lock"></i>Forgot Your Password</p>
            <i class="fa-solid fa-xmark" id="resetboxclose"></i>
            <hr>
        </div>
        <form class="login" id="UserForgot">
            <div class="pass-modal-body flex">
                <p>Enter your email id and we'll send you instructions to reset your password.</p>
                <div class="pass-email">
                    <div class="alert alert-danger forgot_alert_danger" style="display:none;"></div>
                    <div class="alert alert-success forgot_alert_success" style="display:none;"></div>
                </div>
                <div class="pass-email">
                    <label for="email">Email ID</label>
                    {!! Form::text('email', null, array('required', 'class'=>'form-control', 'placeholder'=>'Email *', 'id' => 'forgot_email')) !!}
                </div>
            </div>
            <button type="submit">Send</button>
        </form> 
    </div>
</div>
<!-----------------Modal Forgot Password----------------> 

<script src="https://apis.google.com/js/platform.js" async defer></script>
<script src="https://apis.google.com/js/client:plusone.js" type="text/javascript"></script>

<script>

$('#forgotpassword').on('click', function() {
    $("#resetpass").attr("style", "display:block");
    $("body").addClass("modal-open");
});

$('#resetboxclose').on('click', function() {
    $("#resetpass").attr("style", "display:none");
    $("body").removeClass("modal-open");
})

/* Forgot password */
$("#UserForgot").validate({
  rules: {
      email: {
          required: true,
          email: true
      }
  },
  errorPlacement: function(label, element) {
      label.addClass('mt-2 text-danger');
      label.insertAfter(element);
  },
  highlight: function(element, errorClass) {
      $(element).parent().addClass('has-danger');
      $(element).addClass('form-control-danger');
  },
  submitHandler: function (form) {
      $("#UserForgot").addClass('loading');
      $('.forgot_alert_danger').hide();
      $('.forgot_alert_danger').html('');
      $('.forgot_alert_success').hide();
      $('.forgot_alert_success').html('');

      //var ajax_url = "{{route('users.register')}}";
      //var forgot_ajax_url = site_url+'sendResetLinkEmail';
      var forgot_ajax_url = "{{route('forgot')}}";
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
          url: forgot_ajax_url,
          method: 'post',
          data: {
              email: $('#forgot_email').val()
          },
          success: function(data){
              if(data.success){
                  $('.forgot_alert_success').show();
                  $('.forgot_alert_success').append('<strong>Success!</strong> '+data.success);
                  $('#UserForgot')[0].reset();
                  $("#UserForgot").removeClass('loading');
              }else if(data.errors){
                  $('.forgot_alert_danger').show();
                  $('.forgot_alert_danger').html('<p>'+data.errors+'</p>');
                  $("#UserForgot").removeClass('loading');
                  // setTimeout(function(){
                  //     $("#UserForgot").removeClass('loading');
                  //     $('.forgot_alert_danger').hide();
                  // }, 4000);
              }
          }
      });
      return false;
  }
});

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}


$(document).ready(function(){
    $("#Userlogin").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            }
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
        //client_id: '464197552660-lo03iqhi3uo2ngsb8ajneb5ppskcnne4.apps.googleusercontent.com', //--Testing
        client_id: '416447053822-6pirqom41jb24lvepquohlt5uoudghlv.apps.googleusercontent.com', //--Orginal
        //client_id: '180515560125-oaf5cti272qkblheu2k4g3j8h7h33ibm.apps.googleusercontent.com',
        fetch_basic_profile: false,
        scope: 'email profile openid',
        response_type: 'id_token permission',
        plugin_name: 'streamy'
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
      appId      : '414796533781933',
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
    //js.src = "https://connect.facebook.net/en_US/sdk.js";
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