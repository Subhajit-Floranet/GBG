@extends('layouts.site.app')

@section('content')

<ul class="breadcrumb">
    <li><a href="{{ url('/') }}">Home</a></li>
    <li><a href="javascript:void(0)" class="tempting">Reset Password</a></li>
</ul>

<section class="log-in">
    <div class="log-in-body">
        <h1>Reset Your Password</h1>
        <div class="alert alert-danger resetpassword_alert_danger" style="display:none"></div>
        <div class="alert alert-success resetpassword_alert_success" style="display:none"></div>   
        <form name="resetPasswordForm" id="resetPasswordForm">
            {{ csrf_field() }}
            <div class="log-in-form flex">
                <div class="l-form-group">
                    <label for="email">New Password</label>
                    <div class="mno flex">
                        <div class="l-prefix">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input placeholder="New Password*" class="form-control" id="reset_password" name="password" type="password" required>
                    </div>
                </div>
                <div class="l-form-group">
                    <label for="password">Confirm Password</label>
                    <div class="mno flex">
                        <div class="l-prefix">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input placeholder="Confirm New Password*" class="form-control" id="reset_confirm_password" name="confirm_password" type="password" value="" required>
                    </div>
                </div>
            </div>
            <div class="forgot-pass"><a href="javascript:void(0)" id="forgotpassword">Forgot Password ?</a></div>
            <div class="log-in-btn">
                {{-- <button>Log In</button> --}}
                <button type="submit" name="accountedit" >Submit</button>
            </div>
            
        </form>
    </div>
</section>

<script>

    // For reset password
    $("#resetPasswordForm").validate({
        rules: {
            password: {
                required: true,
                minlength: 6
            },
            confirm_password: {
                required: true,
                minlength: 6,
                equalTo: "#reset_password"
            },
        },
        errorPlacement: function(label, element) {
            label.addClass('mt-2 text-danger');
            label.insertAfter(element);
        },
        highlight: function(element, errorClass) {
            $(element).parent().addClass('has-danger')
            $(element).addClass('form-control-danger')
        },
        submitHandler: function (form) {
            $('#resetPasswordForm').addClass('loading');

            $('.resetpassword_alert_danger').hide();
            $('.resetpassword_alert_danger').html('');
            $('.resetpassword_alert_success').hide();
            $('.resetpassword_alert_success').html('');

            //var ajax_url = "{{route('site.users.register')}}";
            //var reset_pass_ajax_url = site_url+'resetPassword';
            var reset_pass_ajax_url = "{{route('resetPassword')}}";
            $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });
            $.ajax({
                url: reset_pass_ajax_url,
                method: 'post',
                data: {
                    password: $('#reset_password').val(),
                    token: $('#reset_token_value').val(),
                },
                success: function(data){
                    console.log(data);
                    if(data.success){
                        $('.resetpassword_alert_success').show();
                        $('.resetpassword_alert_success').append('<strong>Success!</strong><p> '+data.success+'</p>');
                        $('#resetPasswordForm')[0].reset();
                        $('#resetPasswordForm').removeClass('loading');
                        setTimeout(function(){
                            //window.location.href = site_url+'login';
                            window.location.href = "{{route('login')}}";
                        }, 3000);
                    }else if(data.error){
                        $('.resetpassword_alert_danger').show();
                        $('.resetpassword_alert_danger').html('<p>'+data.error+'</p>');
                        $('#resetPasswordForm')[0].reset();
                        $('#resetPasswordForm').removeClass('loading');
                        setTimeout(function(){
                            $('.resetpassword_alert_danger').hide();
                        }, 3000);
                    }
                }
            });
            return false;
        }
    });
    
</script>

@endsection