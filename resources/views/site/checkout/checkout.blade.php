@extends('layouts.site.app')

@section('content')

@php
if (Auth::check()) {
    $userId = base64_encode(Auth::id());
    $mainId = Auth::id();
}else{
    $userId = 0;
    $mainId = 0;
}
@endphp



<script src="{{asset('site/css/accordian/zebra_accordion.min.js') }}"></script>
<script src="{{asset('site/css/accordian/examples.js') }}"></script>


<div class="container-details">	
	<main class="row rows member-checkout">


        <dl class="Zebra_Accordion" id="Zebra_Accordion1">
            <dt id="login_gift_title" class="checkout-heading"><i>A</i> Login</dt>
            <dd id="login_gift_body">
                <div class="checkout_container" id="login_fieldset_loader">
                    <div class="checkout_container_text">
                        <div class="user-interface">
                            <form name="checkout_login_form" id="checkout_login_form"  method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="userid" id="userid" value="<?php echo $userId;?>">
                                <input type="hidden" name="usertype" id="usertype" value="GU">
                                <input type="hidden" name="mainid" id="mainid" value="<?php echo $mainId;?>">
                                <fieldset>
                                    <div class="form-group vuser" id="chkvaliduser">
                                        <label><input type="radio" value="GU" name="chkvaliduser" checked="checked" class="user_login_type"> Guest Checkout</label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label><input type="radio" value="C" name="chkvaliduser" class="user_login_type">Login with GermanFlorist account</label>
                                    </div>
                                    <!-- Guest User Form start here -->
                                    <div class="row guest_user_form" id="loginwithoutid">
                                        <div class="form-group">
                                            <label>Name :</label>
                                            <input type="text" name="checkout_guest_name" class="form-control" id="checkout_guest_name" placeholder="Name*" value="" >
                                        </div>
                                        <div class="form-group">
                                            <label>Email ID :</label>
                                            <input type="text" name="checkout_guest_email" class="form-control" id="checkout_guest_email" placeholder="Email*" value="" >
                                        </div>
                                        <div class="form-group">
                                            <label>Mobile No. :</label>
                                            <input type="text" name="checkout_guest_mobile" class="form-control" id="checkout_guest_mobile" placeholder="Mobile Number*" value="" >
                                        </div>
                                        <div class="form-group button-container text-center">
                                            <button class="btn button-nfjp step2 checkoutlogin">Proceed</button>
                                        </div>
                                    </div>
                                    <!-- Guest User Form end here -->

                                    <!-- Normal User Form start here -->
                                    <div class="row normal_user_form" id="loginwithid" style="display: none;">
                                        <div class="form-group">
                                            <label>Email ID :</label>
                                            <input type="text" name="checkout_login_email" class="form-control" id="checkout_login_email" placeholder="Email*" value="" >
                                        </div>
                                        <div class="form-group">
                                            <label>Password :</label>
                                            <input type="text" name="checkout_login_password" class="form-control" id="checkout_login_password" placeholder="Password*" value="" >
                                        </div>
                                        <div class="form-group button-container text-center">
                                            <button class="btn button-nfjp step2 checkoutlogin">Login</button>
                                        </div>
                                        <div class="form-group button-container text-center">
                                            {{-- <a href="" class="btns button-white">Forgot Password ?</a> --}}
                                            <a href="javascript:void(0)" id="forgotpassword">Forgot Password ?</a>
                                        </div>
                                    </div>
                                    <!-- Normal User Form end here -->
                                    <div class="row">
                                        <span id="login_error_message"></span>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <!-- <div class="checkout_container_img">
                        <div class="user-interface form-sperater">
                            <fieldset class="sperater-space">
                                <div class="form-group social-login text-center">
                                    <a href="javascript:void(0)" onclick="checkout_login_with_facebook();"><img src="{{ asset('images/site/facebook-chk.png')}}"></a>
                                </div>
                                <div class="form-group social-login text-center">
                                    <a href="javascript:void(0)" onclick="checkout_login_with_google();"><img src="{{ asset('images/site/google.png')}}"></a>
                                </div>
                            </fieldset>
                        </div>
                    </div> -->
                </div>

            </dd>
            <dt id="delivery_address_title" class="checkout-heading"><div class="summrysecheadernew"><i>B</i> Delivery Address</div></dt>
            <dd id="delivery_address_body">
                <div class="checkout_container" id="delivery_location_fieldset_loader">
                    <div class="checkout_container_text">
                        <div class="user-interface">
                            
                            <!-----------Address Book----------->
                            <div id="existing_delivery_addresses">
                                <!-----------Existing Address Book----------->
                            </div>
                            <!-----------/Address Book----------->							
                            
                            <div class="form-group button-container">
                                <button class="btn button-nfjp addshipaddress" id="add_new_delivery_address" style="display: none;">Add New Address</button>
                            </div>
                            
                            <div id="author_bio_wrap" style="display: block;">
                                <!-----------Add New Address----------->
                                <form name="add_new_delivery_address_form" id="add_new_delivery_address_form" href="JavaScript:Void(0);">
                                    <fieldset id="addshipaddress-new">
                                        <div class="form-group">
                                            <label>Full Name :</label>
                                            {!! Form::text('name', null, array('required', 'class'=>'form-control', 'id' => 'name1', 'autocomplete' => 'off', 'placeholder' => 'Full Name*')) !!}
                                        </div>
                                        <div class="form-group">
                                            <label>Street Address :</label>
                                            {!! Form::text('address', null, array('required', 'class'=>'form-control', 'id' => 'address1', 'autocomplete' => 'off', 'placeholder' => 'Street and number, P.O. box, c/o*')) !!}
                                        </div>
                                        <div class="form-group">
                                            <label>Country/Region :</label>
                                            <input type="text" class="form-control" name="country_name" id="country_name1" value="Germany" required="true" placeholder="Country" disabled="disabled">
                                            <input type="hidden" name="country_id" id="country_id1" value="80">
                                        </div>
                                        <div class="form-group">
                                            <label>City :</label>
                                            @if($del_city_dtl->check_other == 'N')
                                            <input type="text" class="form-control" name="city_name" id="city_name1" value="{{ $del_city_dtl->name }}" required="true" placeholder="City" disabled="disabled">
                                            @else
                                            <input type="text" class="form-control" name="city_name" id="city_name1" value="" required="true" placeholder="City">
                                            @endif
                                            <input type="hidden" name="city_id" id="city_id1" value="{{ $del_city_dtl->id }}">
                                        </div>
                                        <div class="form-group">
                                            <label>State/Province :</label>
                                            {!! Form::text('state_name', null, array('class'=>'form-control', 'id' => 'state_name1', 'autocomplete' => 'off', 'placeholder' => 'eg. Berlin')) !!}
                                        </div>
                                        <div class="form-group">
                                            <label>Zip Code :</label>
                                            {!! Form::text('pincode', null, array('class'=>'form-control', 'id' => 'pincode1', 'autocomplete' => 'off', 'placeholder' => 'eg. 01067')) !!}
                                        </div>
                                        <div class="form-group">
                                            <label>Local Phone Number :</label>
                                            {!! Form::number('mobile', null, array('required', 'class'=>'form-control', 'id' => 'mobile1', 'autocomplete' => 'off', 'placeholder' => 'eg. 1234567890')) !!}
                                        </div>
                                        <div class="form-group">
                                            <label>Email :</label>
                                            {!! Form::text('email', null, array('class'=>'form-control', 'id' => 'email1', 'autocomplete' => 'off')) !!}
                                        </div>
                                        <div class="form-group button-container text-center">
                                            <button type="submit" class="btn button-nfjp" id="save_address" style="display: none;">Continue</button>
                                        </div>
                                    </fieldset>
                                    <span id="address_error_message"></span>
                                </form>
                                <!-----------/Add New Address----------->
                                <span id="address_error_message"></span>
                            </div>

                            <div class="form-group button-container">
                                <button type="submit" class="btn button-nfjp step4 continuebtn" id="delivery_address_update" style="display: none;">Continue to Billing</button>
                            </div>

                        </div>
                    </div>
                    <!-- <div class="checkout_container_img extra-img text-center"><img src="{{asset('images/site/delivery_add_img.png') }}"></div> -->
                </div>
            </dd>

            <dt id="billing_address_title" class="checkout-heading"><div class="summrysecheadernew"><i>C</i> Billing Address</div></dt>
            <dd id="billing_address_body">
                <div class="checkout_container" id="billing_location_fieldset_loader">
                    <div class="checkout_container_text">
                        <form name="billing_address_form" id="billing_address_form" href="JavaScript:Void(0);">
                            <input type="hidden" name="billing_address_id" id="billing_address_id" value="0" />
                            <div class="user-interface">					
                                <fieldset>
                                    <div class="form-group">
                                        <label>Full Name :</label>
                                        {!! Form::text('billing_name', null, array('required', 'class'=>'form-control', 'id' => 'billing_name', 'autocomplete' => 'off', 'placeholder' => 'eg. Mike *')) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Street Address :</label>
                                        {!! Form::text('billing_address', null, array('required', 'class'=>'form-control', 'id' => 'billing_address', 'autocomplete' => 'off', 'placeholder' => 'Street and number, P.O. box, c/o')) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Country/Region :</label>
                                        <select name="billing_country_name" id="billing_country_name" class="form-control">
                                            <option value="">Select Country</option>
                                            @foreach ($country_list as $country)
                                                <option value="{{ $country->name }}|{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>City :</label>
                                        {!! Form::text('billing_city_name', null, array('required', 'class'=>'form-control', 'id' => 'billing_city_name', 'autocomplete' => 'off', 'readonly' => false, 'placeholder' => 'City *')) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>State/Province :</label>
                                        {!! Form::text('billing_state_name', null, array('class'=>'form-control', 'id' => 'billing_state_name', 'autocomplete' => 'off', 'placeholder' => 'eg. New Jersey (NJ)')) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Zip Code :</label>
                                        {!! Form::text('billing_pincode', null, array('class'=>'form-control', 'id' => 'billing_pincode', 'autocomplete' => 'off', 'placeholder' => 'eg. 07039')) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Phone Number :</label>
                                        {!! Form::text('billing_mobile', null, array('required', 'class'=>'form-control', 'id' => 'billing_mobile', 'autocomplete' => 'off', 'placeholder' => 'eg. 9876543210')) !!}
                                        <label style="font-size: 0.8em; font-weight: 500">Please don't add any special characters such as + - ( ) etc.</label>
                                    </div>
                                    <div class="form-group button-container text-center">
                                        <button type="submit" class="btn button-nfjp" id="billing_save_address">Continue</button>
                                    </div>
                                </fieldset>
                                <!-----------/Add New Address----------->
                            </div>
                        </form>
                        <span id="billing_address_error_message"></span>
                    </div>
                    <!-- <div class="checkout_container_img extra-img text-center"><img src="{{ asset('images/site/bill_detail_img.png')}}"></div> -->
                </div>
            </dd>

            <dt id="message_title" class="checkout-heading"><div class="summrysecheadernew"><i>D</i>Special Instruction</div></dt>
            <dd id="message_body">
                <div class="checkout_container" id="message_fieldset_loader">
                    <div class="checkout_container_text">
                        <form name="message_form" id="message_form" href="JavaScript:Void(0);">
                            <input type="hidden" name="message_id" id="message_id" value="0" />
                            <div class="user-interface">					
                                <fieldset>
                                    <div class="form-group">
                                        <!-- <label>Occasion :</label> -->
                                        <!-- {!! Form::text('message_purpose', null, array('required', 'class'=>'form-control', 'id' => 'message_purpose', 'autocomplete' => 'off', 'placeholder' => 'eg. Birthday')) !!} -->

                                        <label>Type of Order :</label>

                                        <div class="cus_message_purpose" style="padding-top: 7px">
                                            <label class="form-control">
                                                <input type="radio" id="message_purpose" name="message_purpose" value="Personal" checked="checked" />&nbsp;Personal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </label>
                                            <label class="form-control">
                                                <input type="radio" id="message_purpose" name="message_purpose" value="Professional" />&nbsp;Professional
                                            </label>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label>Sender Name :</label>
                                        {!! Form::text('sender_name', null, array('required', 'class'=>'form-control', 'id' => 'sender_name', 'autocomplete' => 'off', 'placeholder' => 'eg. John')) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Message :</label>
                                        {!! Form::textarea('sender_message', null, array('required', 'class'=>'form-control', 'id' => 'sender_message', 'autocomplete' => 'off', 'onKeyUp' => 'check_sender_message_length(this.form);', 'placeholder' => 'eg. Any special instruction you want to mention')) !!}

                                        <h6 class="txt">(Maximum characters: 200)</h6> <h5 class="txt2">Characters left <span id="sender_message_count">200</span></h5>
                                    </div>
                                    <div class="form-group">
                                        <label>Special Instructions :</label>
                                        {!! Form::textarea('sender_special_instruction', null, array('class'=>'form-control', 'id' => 'sender_special_instruction', 'autocomplete' => 'off', 'onKeyUp' => 'check_special_instruction_length(this.form);', 'placeholder' => 'eg. Any special instruction you want to mention, We shall try our best to follow the same but cannot guarantee.')) !!}

                                        <h6 class="txt">(Maximum characters: 200)</h6> <h5 class="txt2">Characters left <span id="sender_special_instruction_count">200</span></h5>
                                    </div>

                                    @php
                                        $senderDemand = App\Http\Helper::siteSetting( 'sender_demand' );
                                        //echo $senderDemand;
                                    @endphp
                                    @if(count($senderDemand)>0)
                                    <div class="form-group">
                                        <label>Select Option :</label>
                                        <div class="cusDemand">
                                            

                                            @foreach($senderDemand as $value)
                                            <label class="form-control">
                                              <input type="radio" id="cdcancel" name="sender_demand" value="{{ $value->option_msg }}" <?php if($value->sort == 1){ ?> checked="checked" <?php } ?> />
                                              {{ $value->option_msg }}
                                            </label><br>
                                            @endforeach
                                           
                                        </div>
                                    </div>
                                    @else

                                    <input type="hidden" name="sender_demand" value="">
                                    @endif

                                      
                                                       

                                    <div class="form-group button-container text-center">
                                        <button type="submit" class="btn button-nfjp" id="save_message">Review Order</button>
                                    </div>

                                </fieldset>
                                <!-----------/Add New Address----------->
                            </div>
                        </form>

                        <span id="save_message_error_message"></span>
                    </div>
                    <!-- <div class="checkout_container_img extra-img text-center"><img src="{{ asset('images/site/spl_Inst_img.png')}}"></div> -->
                </div>
            </dd>

            <dt id="order_summary_title" class="checkout-heading"><div class="summrysecheadernew"><i>E</i>Order Summary & Payment</div></dt>
            <dd id="order_summary_body">

                <div id="order_summary_fieldset_loader" class="loader_wrapper">
                    <div id="order_summary" class="ordSummary"></div>

                        <fieldset class="text-center paymentOption">
                             <div style="margin-bottom: 30px;">
                                <label class="cscheckbox">
                                    <!-- <p id="error" class="hidden">Please check the checkbox</p> -->
                                    <input name="checkout_agreed" id="checkout_agreed" type="checkbox" required>
                                    <label id="checkout_agreed_error" class="checkout_agreed_error"></label>
                                    <span class="checkmark"></span> I agree with the
                                </label>
                                <a href="{{ url('/terms-and-conditions')}}" target="_blank">Terms And Conditions</a>
                            </div>
                            
                            <div id="payment-buttons"></div>
                        </fieldset>
                    
                </div>
            </dd>
            
        </dl>

            
	</main>
</div>

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


<!-- Modal -->
<!-- <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="termsModalLabel">Terms snd Conditions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div> -->

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

    // function isEmail(email) {
    //   var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    //   return regex.test(email);
    // }

    
    //---Prevent other section without login or register-----
    $( document ).ready(function() {
        //alert($('#userid').val());
        if($('#userid').val() < 1){
            //$('.delivery_address_body .summrybody').css("display", "none");
            $('#delivery_address_title .summrysecheadernew').click(function( event ) {
                //alert(event);
                //event.preventDefault();
                event.stopImmediatePropagation();
            });
            $('#billing_address_title .summrysecheadernew').click(function( event ) {
                event.stopImmediatePropagation();
            });
            $('#message_title .summrysecheadernew').click(function( event ) {
                event.stopImmediatePropagation();
            });
            $('#order_summary_title .summrysecheadernew').click(function( event ) {
                event.stopImmediatePropagation();
            });
        }
    });

    //-------END----------------------------------------


    function check_sender_message_length(message_form){
        maxLen = 200;
        var sender_message_length = document.getElementById('sender_message').value.length;
        if(sender_message_length >= maxLen){
            // Reached the Maximum length so trim the textarea
            message_form.sender_message.value = message_form.sender_message.value.substring(0, maxLen);
            document.getElementById('sender_message_count').innerHTML = 0;
        }
        else{
            // Maximum length not reached so update the value of my_text counter
            document.getElementById('sender_message_count').innerHTML = maxLen - sender_message_length;
        }
    }

    function check_special_instruction_length(message_form){
        special_instruction_maxLen = 200;
        var special_instruction_length = document.getElementById('sender_special_instruction').value.length;
        if(special_instruction_length >= special_instruction_maxLen){
            // Reached the Maximum length so trim the textarea
            message_form.sender_special_instruction.value = message_form.sender_special_instruction.value.substring(0, special_instruction_maxLen);
            document.getElementById('sender_special_instruction_count').innerHTML = 0;
        }
        else{
            // Maximum length not reached so update the value of my_text counter
            document.getElementById('sender_special_instruction_count').innerHTML = special_instruction_maxLen - special_instruction_length;
        }
    }

    $(document).ready(function(){

        $.validator.addMethod("valid_email", function(value, element) {
            if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
                return true;
            } else {
                return false;
            }
        }, "Please enter a valid email.");

        //Login Form type change ( Normal / Guest user) start
        $('.user_login_type').click(function(){
            var radioValue = $(this).val();
            if(radioValue){
                $('#usertype').val(radioValue);
                $('#login_error_message').html('');

                if( radioValue == 'C' ){
                    $('#checkout_guest_name-error').remove();
                    $('#checkout_guest_mobile-error').remove();
                    $('#checkout_guest_email-error').remove();

                    $('.normal_user_form').css('display', 'block');
                    $('.guest_user_form').css('display', 'none');
                }else{
                    $('#checkout_login_email-error').remove();
                    $('#checkout_login_password-error').remove();

                    $('.normal_user_form').css('display', 'none');
                    $('.guest_user_form').css('display', 'block');
                }                
            }else{
                $('#usertype').val('GU');
            }
        }); 
        //Login Form type change ( Normal / Guest user) end

        // Checking is USER LOGGED in or not start here //
        var userid   = $('#userid').val();
        var usertype = $('#usertype').val();

        $('#login_gift_title').click(function(){
            var userid   = $('#mainid').val();
            //alert(userid);
            if( userid != 0 ){
                checkout_process();
            }
        });

        // For LOGIN / REGISTER
        if( userid != 0 ){
            checkout_process();
        }else{
            $('.checkoutlogin').on('click', function() {
                $('#checkout_login_form').validate({
                    rules: {
                        checkout_login_email: {
                            required: true,
                            valid_email: true
                        },
                        checkout_login_password: {
                            required: true
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
                        var user_type = $('#usertype').val();
                        $('#login_fieldset_loader').addClass('loading');

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        //alert(user_type); return false;

                        //For Normal User start
                        if( user_type == 'C' ) {
                            $.ajax({
                                url: '{{ route("users.checkout-login-process") }}',
                                method: 'POST',
                                dataType: 'JSON',
                                data: {
                                    email: $('#checkout_login_email').val(),
                                    password: $('#checkout_login_password').val(),
                                    userid: $('#userid').val(),
                                    usertype: $('#usertype').val()
                                },
                                success: function(response_login) {
                                    if( response_login.type == 'success' ) {
                                        

                                        $('#userid').val(response_login.user_id);
                                        $('#mainid').val(response_login.user_id);
                                        $('#usertype').val(response_login.user_type);

                                        checkout_process();

                                        setTimeout(function() {
                                            $('#login_fieldset_loader').removeClass('loading');
                                            //$('#delivery_address_title').trigger('click');
                                        },100);
                                    }
                                    else{
                                        $('#login_fieldset_loader').removeClass('loading');
                                        $('#login_error_message').html('<p class="nonsuccessinfo">'+response_login.msg+'</p>');
                                        setTimeout(function() {
                                            $('#login_error_message').html('');
                                        }, 2000);
                                    }
                                    location.reload(true);
                                }
                            });
                        }
                        //For Normal User end

                        //For Guest User start
                        else if( user_type == 'GU' ) {
                            $.ajax({
                                url: '{{ route("users.checkout-guest-login-process") }}',
                                type: 'POST',
                                dataType: 'JSON',
                                data: {
                                    checkout_guest_name: $('#checkout_guest_name').val(),
                                    checkout_guest_mobile: $('#checkout_guest_mobile').val(),
                                    checkout_guest_email: $('#checkout_guest_email').val(),
                                    userid: $('#userid').val(),
                                    usertype: $('#usertype').val()
                                },
                                success: function(response_login) {
                                    //console.log(response_login); return false;
                                    if( response_login.type == 'success' ) {
                                        //$('#cart_count').html(response_login.total_orders);
                                        //updating header login menu
                                        //$('#during_checkout').html('<li class="nodivdr"><a href="{{ route("users.dashboard") }}">My Account</a></li><li><a href="{{ route("users.logout") }}">Logout</a></li>');

                                        $('#userid').val(response_login.user_id);
                                        $('#mainid').val(response_login.user_id);
                                        $('#usertype').val(response_login.user_type);

                                        checkout_process();

                                        setTimeout(function() {
                                            $('#login_fieldset_loader').removeClass('loading');
                                            //$('#delivery_address_title').trigger('click');
                                        },100);
                                    }
                                    else{
                                        $('#login_fieldset_loader').removeClass('loading');
                                        $('#login_error_message').html('<p class="nonsuccessinfo">'+response_login.msg+'</h5>');
                                        setTimeout(function() {
                                            $('#login_error_message').html('');
                                        }, 2000);
                                    }
                                }
                            });
                        }
                        //For Guest User end

                    }
                });
            })
        }

    });

    function checkout_process(){
        
        //DELIVERY ADDRESS SECTION START: showing all existing delivery addresses//
        $('#delivery_address_title').trigger('click');

        $("#add_new_delivery_address").click(function(e){
            $("#author_bio_wrap").slideToggle("show");
            setTimeout(function(){
                if($('#author_bio_wrap').is(':visible')){
                    $("#save_address").show();
                    $("#delivery_address_update").hide();
                }else{
                    $("#delivery_address_update").show();
                }
            }, 500);
        });

        $('#author_bio_wrap').hide();

        $('#delivery_address_title').on('click',function(){
            setTimeout(function(){
                var delivery_adrs_id = $('#delivery_address_id').val();
                if(delivery_adrs_id != 0){
                    $('#add_new_delivery_address').show();
                    $('#delivery_address_update').show();
                    $('#author_bio_wrap').hide();
                }else{
                    $('#author_bio_wrap').show();
                    $('#add_new_delivery_address').hide();
                }
            }, 500);
        });

        $('#delivery_location_fieldset_loader').addClass('loading');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ route("checkout-step-delivery-address") }}',
            type: 'POST',
            dataType: 'HTML',
            data: {
            },
            success: function(response) {
                $('#existing_delivery_addresses').html(response);
                setTimeout(function(){
                    if($('#delivery_address_id').val() == 0) {
                        $('#delivery_address_update').hide();
                        $('#author_bio_wrap').show();
                        $('#save_address').show();
                    }else{
                        $('#add_new_delivery_address').show();
                        $('#delivery_address_update').show();
                    }
                    $('#delivery_location_fieldset_loader').removeClass('loading');
                }, 500);
            }
        });
        //showing all existing delivery addresses//

        $("#save_address").on('click', function() {
            $("#add_new_delivery_address_form").validate({
                rules: {
                    name: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                    country_name: {
                        required: true
                    },
                    city_name: {
                        required: true
                    },
                    mobile: {
                        required: true,
                        digits: true
                    },
                    email: {
                        required: true,
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
                    $('#delivery_location_fieldset_loader').addClass('loading');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ route("add-new-delivery-address") }}',
                        method: 'POST',
                        dataType: 'HTML',
                        data: {
                            name: $('#name1').val(),
                            address: $('#address1').val(),
                            pincode: $('#pincode1').val(),
                            city_name: $('#city_name1').val(),
                            city_id: $('#city_id1').val(),
                            state_name: $('#state_name1').val(),
                            country_name: $('#country_name1').val(),
                            country_id: $('#country_id1').val(),
                            mobile: $('#mobile1').val(),
                            email: $('#email1').val(),
                        },
                        success: function(response_add_address) {
                            if( response_add_address != 0 ) {
                                setTimeout(function(){
                                    $('#add_new_delivery_address_form')[0].reset();
                                    
                                    $('#existing_delivery_addresses').html(response_add_address);
                                    $('#delivery_address_update').show();
                                    $('#delivery_location_fieldset_loader').removeClass('loading');

                                    //Save & Continue to Billing Address
                                    var deliveryaddress_id = $('#delivery_address_id').val();
                                    if(deliveryaddress_id != 0) {
                                        $('#billing_address_title').trigger('click');

                                        //Getting existing billing addess
                                        $('#billing_location_fieldset_loader').addClass('loading');
                                        $.ajaxSetup({
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        });
                                        $.ajax({
                                            url: '{{ route("checkout-step-billing-address") }}',
                                            method: 'POST',
                                            dataType: 'JSON',
                                            data: {
                                                delivery_address_id : delivery_address_id
                                            },
                                            success: function( response_billing_address ) {
                                                
                                                if( response_billing_address.status == 'exist' ) {
                                                    //console.log(response_billing_address);
                                                    setTimeout(function(){
                                                        $('#billing_address_id').val( response_billing_address.billing_address.id );
                                                        
                                                        $('#billing_name').val( response_billing_address.billing_address.name );
                                                        $('#billing_address').val( response_billing_address.billing_address.address );
                                                        $('#billing_pincode').val( response_billing_address.billing_address.pincode );
                                                        $('#billing_state_name').val( response_billing_address.state_name );
                                                        
                                                        $('#billing_country_id').val( response_billing_address.country_id );
                                                        $('#billing_country_name').val( response_billing_address.country_name+'|'+response_billing_address.country_id );
                                                        //$('#billing_country_id').selectpicker( "refresh" );
                                                        $('#billing_city_id').val( response_billing_address.city_id );
                                                        $('#billing_city_name').val( response_billing_address.city_name );
                                                        //$('#billing_city_id').selectpicker( "refresh" );   

                                                        $('#billing_mobile').val( response_billing_address.billing_address.mobile );
                                                        //$('#billing_company').val(response_billing_address.billing_address_fieldsetdress.company);

                                                        $('#billing_location_fieldset_loader').removeClass('loading');
                                                    }, 1000);
                                                }
                                                else if( response_billing_address.status == 'not_exist' ) {
                                                    setTimeout(function(){
                                                        $('#billing_name').val( response_billing_address.name );
                                                        $('#billing_mobile').val( response_billing_address.mobile );
                                                        $('#billing_email').val( response_billing_address.email );
                                                        
                                                        $('#billing_location_fieldset_loader').removeClass('loading');
                                                    }, 1000);
                                                }
                                                
                                            }
                                        });
                                    }

                                }, 1000);
                            }else{
                                $('#delivery_location_fieldset_loader').removeClass('loading');
                                $('#address_error_message').html('<p class="nonsuccessinfo">'+response_add_address.error_meg+'</p>');
                                setTimeout(function() {
                                    $('#address_error_message').html('');
                                }, 2000);
                            }
                        }
                    });
                }
            });
        });

        $('#delivery_address_update').on('click', function(){
            var deli_address_id = $('#delivery_address_id').val();
            if(deli_address_id != 0){
                $('#delivery_location_fieldset_loader').addClass('loading');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route("delivery-address-update-cart") }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        selected_address_id:deli_address_id
                    },
                    success: function(response) {
                        setTimeout(function(){
                            if(response == 1){
                                $('#billing_form_section').show();
                                $('#delivery_location_fieldset_loader').removeClass('loading');
                                $('#billing_address_title').trigger('click');
                            }else{
                                $('#delivery_location_fieldset_loader').removeClass('loading');
                                $('#address_error_message').html('<p class="nonsuccessinfo">Please choose one delivery address</p>');
                                setTimeout(function() {
                                    $('#address_error_message').html('');
                                }, 2000);
                            }
                            
                        }, 1000);
                    }
                });
            }
        });
        //DELIVERY ADDRESS SECTION END: Save a new address and show all delivery addresses

        //BILLING ADDRESS SECTION START: Save or Update a billing address
        $("#billing_address_title").on('click', function() {     //For Billing details tab click
            var deliveryaddress_id  = $('#delivery_address_id').val();
            var billingaddress_id   = $('#billing_address_id').val();
            
            if(deliveryaddress_id != 0){
                $('#billing_location_fieldset_loader').addClass('loading');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route("checkout-step-billing-address") }}',
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        delivery_address_id : deliveryaddress_id
                    },
                    success: function( response_billing_address ) {
                        
                        if( response_billing_address.status == 'exist' ) {
                            //console.log(response_billing_address);
                            setTimeout(function(){
                                $('#billing_address_id').val( response_billing_address.billing_address.id );

                                $('#billing_name').val( response_billing_address.billing_address.name );
                                $('#billing_address').val( response_billing_address.billing_address.address );
                                $('#billing_pincode').val( response_billing_address.billing_address.pincode );

                                $('#billing_state_name').val( response_billing_address.billing_address.state_name );

                                $('#billing_country_id').val( response_billing_address.billing_address.country_id );
                                //$('#billing_country_name').val( response_billing_address.billing_address.country_name );
                                $('#billing_country_name').val( response_billing_address.billing_address.country_name+'|'+response_billing_address.billing_address.country_id );
                                
                                //$('#billing_country_id').selectpicker( "refresh" );
                                
                                $('#billing_city_id').html( response_billing_address.billing_address.city_id );
                                $('#billing_city_name').val( response_billing_address.billing_address.city_name);
                                //$('#billing_city_id').selectpicker( "refresh" );

                                $('#billing_mobile').val( response_billing_address.billing_address.mobile );
                                //$('#billing_company').val(response_billing_address.billing_address_fieldsetdress.company);

                                $('#billing_location_fieldset_loader').removeClass('loading');
                            }, 100);
                        }
                        else if( response_billing_address.status == 'not_exist' ) {
                            setTimeout(function(){
                                $('#billing_name').val( response_billing_address.name );
                                $('#billing_mobile').val( response_billing_address.mobile );
                                $('#billing_email').val( response_billing_address.email );
                                
                                $('#billing_location_fieldset_loader').removeClass('loading');
                            }, 100);
                        }
                        
                    }
                });
            }else{
                $('#delivery_address_title').trigger('click');
            }
        });

        $("#billing_save_address").on('click', function() {
            $("#billing_address_form").validate({
                rules: {
                    billing_name: {
                        required: true
                    },
                    billing_address: {
                        required: true
                    },
                    billing_country_name: {
                        required: true
                    },
                    billing_city_name: {
                        required: true
                    },
                    billing_mobile: {
                        required: true
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
                    $('#billing_location_fieldset_loader').addClass('loading');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ route("add-update-billing-address") }}',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            billing_address_id: $('#billing_address_id').val(),
                            name: $('#billing_name').val(),
                            address: $('#billing_address').val(),
                            pincode: $('#billing_pincode').val(),
                            city_id: $('#billing_city_id').val(),
                            city_name: $('#billing_city_name').val(),
                            state_name: $('#billing_state_name').val(),
                            country_id: $('#billing_country_id').val(),
                            country_name: $('#billing_country_name').val(),
                            mobile: $('#billing_mobile').val(),
                            //email: $('#billing_email').val(),
                            //company: $('#billing_company').val(),
                        },
                        success: function(response_address) {
                            if( response_address.type == 'success' ) {
                                setTimeout(function(){
                                    //$('#billing_address_form')[0].reset();

                                    $('#billing_address_id').val( response_address.billing_details.id );

                                    $('#billing_location_fieldset_loader').removeClass('loading');

                                    if(response_address.type == 'success') {
                                        
                                        $('#billing_address_error_message').html('');

                                        //Save or Update & Continue to Message
                                        var billingaddress_id = response_address.billing_details.id;
                                        if(billingaddress_id != 0) {
                                            $('#message_title').trigger('click');  
                                        }
                                    }else{
                                        $('#delivery_location_fieldset_loader').removeClass('loading');
                                        $('#billing_address_error_message').html('<p class="nonsuccessinfo">'+response_address.msg+'</p>');
                                        setTimeout(function(){
                                            $('#billing_address_error_message').html('');
                                        }, 2000);
                                    }
                                }, 100);
                            }else{
                                $('#delivery_location_fieldset_loader').removeClass('loading');
                                $('#billing_address_error_message').html('<p class="nonsuccessinfo">'+response_address.msg+'</p>');
                                setTimeout(function(){
                                    $('#billing_address_error_message').html('');
                                }, 2000);
                            }
                        }
                    });
                }
            });
        });
        //BILLING ADDRESS SECTION END: Save or Update a billing address

        //MESSAGE SECTION START: Add or Update a Message start //
        $("#message_title").on('click', function() {     //For Billing details tab click
            var delivery_id = $('#delivery_address_id').val();
            var billing_id  = $('#billing_address_id').val();

            if(delivery_id != 0 && billing_id != 0) {
                $('#message_fieldset_loader').addClass('loading');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route("checkout-step-existing-message") }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {    
                        billing_address_id : billing_id                
                    },
                    success: function(response_exist_message) {
                        if( response_exist_message.status == 'exist' ) {
                            setTimeout(function(){
                                $('#message_id').val(response_exist_message.existing_message.id);

                                $('#message_id').val(response_exist_message.existing_message.id);
                                //$('#message_purpose').val(response_exist_message.existing_message.message_purpose);
                                $('input[name^="message_purpose"][value="'+response_exist_message.existing_message.message_purpose+'"').prop('checked',true);
                                
                                $('#sender_name').val(response_exist_message.existing_message.sender_name);
                                $('#sender_message').val(response_exist_message.existing_message.sender_message);
                                $('#sender_special_instruction').val(response_exist_message.existing_message.sender_special_instruction);
                                
                                //for sender message//
                                message_maxLen = 200;
                                var sender_message_length = $('#sender_message').val();
                                sender_message_length     = sender_message_length.length;
                                if(sender_message_length > message_maxLen){
                                    document.getElementById('sender_message_count').innerHTML = 0;
                                }
                                else{
                                    document.getElementById('sender_message_count').innerHTML = message_maxLen - sender_message_length;
                                }
                                //for sender message//

                                //for sender special instruction//
                                special_instruction_maxLen = 200;
                                var sender_special_instruction_length = $('#sender_special_instruction').val();
                                sender_special_instruction_length     = sender_special_instruction_length.length;
                                if(sender_special_instruction_length > special_instruction_maxLen){
                                    document.getElementById('sender_special_instruction_count').innerHTML = 0;
                                }
                                else{
                                    document.getElementById('sender_special_instruction_count').innerHTML = special_instruction_maxLen - sender_special_instruction_length;
                                }
                                //for sender special instruction//
                                
                                $('#message_fieldset_loader').removeClass('loading');
                            }, 100);
                        }else{
                            setTimeout(function(){
                                $('#message_fieldset_loader').removeClass('loading');
                            }, 100);
                        }
                    }
                });
                // Show existing message end //
            }
            else{
                $('#billing_address_title').trigger('click');    
            }
        });

        $("#save_message").on('click', function() {
            //alert($('input[name="message_purpose"]:checked').val());
            $("#message_form").validate({
                rules: {
                    message_purpose: {
                        required: true
                    },
                    sender_name: {
                        required: true
                    },
                    sender_message: {
                        required: true
                    },
                    sender_demand: {
                        required: true
                    }
                },
                messages: {
                    message_purpose: {
                        required: "Please select one of these options"
                    },
                    sender_demand: {
                        required: "Please select one of these options"
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
                    $('#message_fieldset_loader').addClass('loading');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ route("add-update-message") }}',
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            message_id: $('#message_id').val(),
                            message_type_id: 0,
                            //message_purpose: $('#message_purpose').val(),
                            message_purpose: $('input[name="message_purpose"]:checked').val(),
                            sender_name: $('#sender_name').val(),
                            sender_message: $('#sender_message').val(),
                            sender_special_instruction: $('#sender_special_instruction').val(),
                            sender_demand: $('input[name="sender_demand"]:checked').val()
                        },
                        success: function(response_message) {
                            if( response_message.type == 'success' ) {
                                setTimeout(function(){
                                    //$('#message_form')[0].reset();
                                    $('#save_message_error_message').html('');

                                    $('#message_id').val( response_message.message_details.id );                                
                                    /*$('#message_type_id').val( response_message.message_details.message_type_id );
                                    $('#sender_name').val( response_message.message_details.sender_name );
                                    $('#sender_message').val( response_message.message_details.sender_message );
                                    $('#sender_special_instruction').val( response_message.message_details.sender_special_instruction );

                                    $('#save_message').val('Save & Review Order');
                                    $('#save_message').removeClass('save-btn');
                                    $('#save_message').addClass('update-btn');
                                    $('.message_fieldset').removeClass('loading');

                                    //for sender message//
                                    message_maxLen = 200;
                                    var sender_message_length = $('#sender_message').val();
                                    sender_message_length     = sender_message_length.length;
                                    if(sender_message_length > message_maxLen){
                                        document.getElementById('sender_message_count').innerHTML = 0;
                                    }
                                    else{
                                        document.getElementById('sender_message_count').innerHTML = message_maxLen - sender_message_length;
                                    }
                                    //for sender message//

                                    //for sender special instruction//
                                    special_instruction_maxLen = 200;
                                    var sender_special_instruction_length = $('#sender_special_instruction').val();
                                    sender_special_instruction_length     = sender_special_instruction_length.length;
                                    if(sender_special_instruction_length > special_instruction_maxLen){
                                        document.getElementById('sender_special_instruction_count').innerHTML = 0;
                                    }
                                    else{
                                        document.getElementById('sender_special_instruction_count').innerHTML = special_instruction_maxLen - sender_special_instruction_length;
                                    }
                                    //for sender special instruction//
                                    */

                                    $('#message_fieldset_loader').removeClass('loading');
                                    var msg_id = response_message.message_details.id;
                                    if(msg_id != 0) {
                                        $('#order_summary_title').trigger('click');
                                        //getMemberDetails();
                                    }
                                }, 100);
                            }else{
                                $('#message_fieldset_loader').removeClass('loading');
                                $('#save_message_error_message').html('<p class="nonsuccessinfo">'+response_message.msg+'</p>');
                                setTimeout(function(){
                                    $('#save_message_error_message').html('');
                                }, 2000);
                            }
                        }
                    });
                }
            });
        });
        //MESSAGE SECTION END: Add or Update a Message end

        //ORDER SUMMARY SECTION END: Add or Update a Message start
        $('#order_summary_title').on('click',function(){
            var delivery_id = $('#delivery_address_id').val();
            var billing_id  = $('#billing_address_id').val();
            var mesg_id     = $('#message_id').val();

            if(delivery_id != 0 && billing_id != 0 && mesg_id != 0) {
                $('#order_summary_fieldset_loader').addClass('loading');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route("checkout-step-order-summary") }}',
                    type: 'POST',
                    dataType: 'HTML',
                    data: {
                        message_id: mesg_id
                    },
                    success: function(response_order_summary) {
                        setTimeout(function() {
                            $('#order_summary').html(response_order_summary);
                            $('#order_summary_fieldset_loader').removeClass('loading');
                        }, 10);
                    }
                });
            }
            else if(delivery_id != 0 && billing_id != 0 && mesg_id == 0) {
                $('#message_title').trigger('click');
            }
            else if(delivery_id != 0 && billing_id == 0 && (mesg_id != 0 || mesg_id == 0 ) ) {
                $('#billing_address_title').trigger('click');
            }
            else if(delivery_id == 0 && (billing_id != 0 || billing_id == 0) && (mesg_id != 0 || mesg_id == 0 ) ) {
                $('#delivery_address_title').trigger('click');
            }
        });
        //ORDER SUMMARY SECTION END: Add or Update a Message end

    }

    /******************************************************
    * Checkout with GOOGLE *
    *******************************************************/
    function checkout_login_with_google() {
	    $('#login_fieldset_loader').addClass('loading');
        gapi.load('auth2', function() {
            gapi.auth2.authorize({
                //client_id: '464197552660-lo03iqhi3uo2ngsb8ajneb5ppskcnne4.apps.googleusercontent.com', //--Testing
                client_id: '416447053822-6pirqom41jb24lvepquohlt5uoudghlv.apps.googleusercontent.com', //--Orginal
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
                            url:"{{route('gmailregistercheckout')}}",
                            type:'POST',
                            data: {email:email, first_name:name},
                            async: false,
                            success:function(result){
                                console.log(result);
                                if( result.type == 'success' ) {
                                    //$('#cart_count').html(result.total_orders);
                                    //updating header login menu
                                    //$('#during_checkout').html('<li class="nodivdr"><a href="{{ route("users.dashboard") }}">Account</a></li><li><a href="{{ route("users.logout") }}">Logout</a></li>');
                                    
                                    $('#userid').val(result.user_id);
                                    $('#mainid').val(result.user_id);
                                    $('#usertype').val(result.user_type);

                                    // checkout_process_social();

                                    // setTimeout(function() {
                                    //     $('#login_fieldset_loader').removeClass('loading');
                                    // },100);
                                    location.reload(true);
                                }
                                else{
                                    $('#login_error_message').html('<h5 class="font-weight-light alert alert-danger">'+result.msg+'</h5>');
                                    setTimeout(function() {
                                        $('#login_error_message').html('');
                                    }, 3000);
                                }
                            }
                    });
                });
            });
            });
        });
    }

    /******************************************************
    * Checkout with FACEBOOK *
    *******************************************************/

    window.fbAsyncInit = function() {
        FB.init({
        appId      : '126655395434231',
        cookie     : true,  // enable cookies to allow the server to access
                            // the session
        xfbml      : true,  // parse social plugins on this page
        version    : 'v7.0' // use graph api version 2.8
        });
    //document.getElementById("fb_btn").disabled=false;
    };

    // Load the SDK Asynchronously
    (function (d) {
        var js, id = 'facebook-jssdk'; if (d.getElementById(id)) { return; }
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        d.getElementsByTagName('head')[0].appendChild(js);
    } (document));

    function checkout_login_with_facebook() {
        // var user_type = $('input[name=Type]:checked').val();
        //  $('#myModal').modal('hide');
        $('#login_fieldset_loader').addClass('loading');
        
        FB.login(function(response) {
            if (response.status === 'connected'){
                FB.api('/me', { fields: 'id,name,email,birthday,first_name,last_name,permissions,picture.width(350).height(350)'}, function(response) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var jqXHR = $.ajax({
                        url:"{{route('fbregistercheckout')}}",
                        type:'POST',
                        data: {email: response.email, fb_id: response.id , name:response.name, first_name:response.first_name, last_name:response.last_name,picture: response.picture.data.url,birthday:response.birthday},
                        async: false,
                        success:function(result){
                            console.log(result);
                            if( result.type == 'success' ) {
                                //$('#cart_count').html(result.total_orders);
                                //updating header login menu
                                //$('#during_checkout').html('<li class="nodivdr"><a href="{{ route("users.dashboard") }}">Account</a></li><li><a href="{{ route("users.logout") }}">Logout</a></li>');
                                
                                $('#userid').val(result.user_id);
                                $('#mainid').val(result.user_id);
                                $('#usertype').val(result.user_type);

                                //checkout_process_social();
                                $('#login_fieldset_loader').removeClass('loading');
                                
                                location.reload(true);
                            }
                            else if(result.type=='error'){
                                $('#login_fieldset_loader').removeClass('loading');
                                $('#login_error_message').html('<p class="nonsuccessinfo">'+result.msg+'</p>');
                                setTimeout(function() {
                                    $('#login_error_message').html('');
                                }, 3000);
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

<!-- -------------------------------------------------------------- -->

<script type="text/javascript">
    function getMemberDetails(){

        // if($('#checkout_agreed').is(':checked')){
            
        // }else{
        //     $('.checkout_agreed_error').html('Please check to agree..');
        //     setTimeout(function(){
        //         $('.checkout_agreed_error').html('');
        //     }, 5000);
        //     return false;
        // }

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ route("paypalOrderPlacedDetails") }}',
            method: 'POST',
            data: {},
            async: false,
            success: function(data){
                console.log(data);
                arr = data.split("*|*");
                bfname = arr[0];
                blname = arr[1];
                baddress = arr[2];
                bpincode = arr[3];
                bcountry = arr[4];
                bemail = arr[5];
                bmobile = arr[6];

                dname = arr[7];
                daddress = arr[8];
                dcity = arr[9];
                dstate = arr[10];
                dpincode = arr[11];
                dcountry = arr[12];

                amount = arr[13];
                invoice = arr[14];
                custom = arr[15];
            }
        });

        
    }
</script>




<!-- Sandbox  
<script src="https://www.paypal.com/sdk/js?client-id=AQOSA4dySpVUQ3cvmX9p4Bwf0yhtnKyUAij5FxlyaPVcXi_WBUGI_HvFAvWC0SyH5OggbxSuasr0Rx3I&currency=USD"></script>-->

<!-- <script nonce="" src="https://www.paypal.com/sdk/js?client-id=AebICI1y5FXWLTk3MHJ-I8g9_lUMlpxf6AP95fnbdtx3WQQFKpMFBP0KsEzeYQAE4GeQ18DlVxvw1qQ9&amp;commit=false&amp;currency=USD&amp;components=buttons%2Cfunding-eligibility&amp;merchant-id=7QGNUNSTUCZTS" data-partner-attribution-id="WIX_SP_EC" data-uid="uid_gsgwcqyaamrhxpucosxljrmoppaadh"></script> -->

<!-- <script src="https://www.paypal.com/sdk/js?client-id=AbLwbpnj6SBQvxp2jVcwkVZ4iQZNzO1_wOusAUnJOSx7Inwy69goIflkujIXo3YcxRTgLvsdY6gfO9-H&currency=USD"></script> -->

<!-- <script src="https://www.paypal.com/sdk/js?client-id=AVe3ppXr-Fy6i_j7ZZvXN5FAd4YsHj_akbGMB866_KCSFuPpMfUi8pi5GyBPqS4gtV46rAeN3I0Kbr9y&currency=USD"></script> -->

<script src="https://www.paypal.com/sdk/js?client-id=AZIZB12amZIDauE5CoOBNkTMsiVh9_gy4zgXRnFKxuKxDUYTz0qit2dekp5K4rvhCMe5RVOxMMK6VeL7&currency=USD"></script>

<!-- RFPL(payment@rf) <script src="https://www.paypal.com/sdk/js?client-id=AUzMzKAqAgMgPeiWjD2sl_103osOfkmXUi8sLrNQvBZXfS9yXV7mwzT51kbEiLMjJzERTMApiU1C08QN&currency=USD"></script> -->

<script>
    // Render the PayPal button into #paypal-button-container
    paypal.Buttons({
        style: {
            size: 'medium',
            layout: 'vertical',
            color: 'blue', //gold,blue,silver,white,black
            shape: 'pill', //rect,pill
            label: 'paypal',
            outline: 'none',
            fundingicons: 'true',
        },
        onInit: function(data, actions)  {
            actions.disable();
            // Listen for changes to the checkbox
            document.querySelector('#checkout_agreed')
                .addEventListener('change', function(event) {
                  // Enable or disable the button when it is checked or unchecked
                  if (event.target.checked)  {
                    actions.enable();
                  } else  {
                    actions.disable();
                  }
            });
        },
        onClick: function() {
          // Show a validation error if the checkbox is not checked
          if (!document.querySelector('#checkout_agreed').checked) {
            //document.querySelector('#error').classList.remove('hidden');
            //alert("heelo");
            $('.checkout_agreed_error').html('Please check to agree..');
                setTimeout(function(){
                    $('.checkout_agreed_error').html('');
                }, 5000);
            }
        },
        createOrder: function(data, actions) {
            return actions.order.create({
                "intent": "CAPTURE",
                "application_context":{
                    "brand_name":getMemberDetails(),
                    "user_action":"PAY_NOW",
                    "shipping_preference": "SET_PROVIDED_ADDRESS",
                    "payment_method":{
                        "payer_selected":"PAYPAL",
                        "UserAction":"CONTINUE",
                        "payee_preferred":"IMMEDIATE_PAYMENT_REQUIRED"
                    }
                },
                "payer":{
                    "name":{
                        "given_name":bfname,
                        "surname":blname,
                    },
                    "email_address":bemail,
                    "phone": {
                        "phone_number": {
                            "national_number": bmobile
                        }
                    },
                    "address": {
                        "address_line_1":baddress,
                        "address_line_2":"",
                        //"admin_area_2":"Bangalore",
                        //"admin_area_1":"Karnataka",
                        "postal_code":bpincode,
                        "country_code":bcountry
                    }
                },
                "purchase_units": [{
                    "description":"Transaction from GFDE",
                    "custom_id":custom,
                    "invoice_id": invoice,
                    "amount": {
                        "currency_code": "USD",
                        "value": amount,
                        "breakdown": {
                            "item_total": {
                                "currency_code": "USD",
                                "value": amount
                            },
                            "shipping": {
                                "currency_code": "USD",
                                "value": "0.00"
                            },
                            "handling": {
                                "currency_code": "USD",
                                "value": "0.00"
                            },
                            "tax_total": {
                                "currency_code": "USD",
                                "value": "0.00"
                            },
                            "shipping_discount": {
                                "currency_code": "USD",
                                "value": "0.00"
                            }
                        }   
                    },
                    "items": [{
                        "name": "Gifts",
                        "description": "None",
                        "unit_amount": {
                            "currency_code": "USD",
                            "value": amount
                        },
                        "tax": {
                            "currency_code": "USD",
                            "value": "0.00"
                        },
                        "quantity": "1",
                        "category": "PHYSICAL_GOODS"
                    }],
                    "shipping":{
                        "name": {
                            "full_name": dname
                        },
                        "address":{
                            "address_line_1":daddress,
                            //"address_line_2":"first STREET",
                            "admin_area_2":dcity,
                            "admin_area_1":dstate,
                            "postal_code":dpincode,
                            "country_code":dcountry
                        }
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details){

                // Show a success message to the buyer
                //alert('Transaction completed by ' + details.payer.name.given_name + '!' );
                //abc1();
                //console.log(details);
                //console.log(details.purchase_units[0].payments.captures[0].id);
                //console.log(details.id); // order id
                //console.log(details.status);
                //console.log(details.purchase_units[0].invoice_id);

                // if(details.status == 'COMPLETED'){
                //     location.href = "/pay-success?order_unique_id="+details.purchase_units[0].invoice_id;
                // }else{
                //     location.href = "/pay-failed?order_unique_id="+details.purchase_units[0].invoice_id;
                // }


                if(details.status == 'COMPLETED'){
                    return fetch('/pay-with-paypal-success', {
                                method: 'post',
                                headers: {
                                    'content-type': 'application/json',
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                body: JSON.stringify({
                                    uorderID: details.purchase_units[0].invoice_id,
                                    status: details.status,
                                    txnId : details.purchase_units[0].payments.captures[0].id
                                })
                            })
                            .then((response) => response.json())
                            .then(function(res){
                                //console.log(res);
                                if(res.status == 'success'){
                                    location.href = "/pay-success?order_unique_id="+res.unid;
                                }else{
                                    alert("Sorry..Transaction failed for capture issues..")
                                    // swal({
                                    //   title: "Sorry!",
                                    //   text: "Transaction failed for capture issues..",
                                    //   icon: "error",
                                    // });
                                }
                            })
                            .catch(function(error) {
                                console.log(error);
                                alert("Sorry..Transaction failed for internal issues..")
                                // swal({
                                //   title: "Sorry! Internal Issue",
                                //   text: "If amount deducted from your account..please contact us",
                                //   icon: "error",
                                // });
                            });
                } else{
                    location.href = "/pay-failed?order_unique_id="+details.purchase_units[0].invoice_id;
                }

                
            });
        },
        onError: function (err) {
            // For example, redirect to a specific error page
            //window.location.href = "/abc111.asp";
            alert('Sorry! Something wrong..try again' );
            console.log(err);

            //swal("Sorry! Something wrong..try again")

            // swal({
            //   title: "Sorry!",
            //   text: "Somethings wrong.Please try again...",
            //   icon: "error",
            // })
            // .then((value) => {
            //   location.reload();
            // });


            // var errorDetail = Array.isArray(orderData.details) && orderData.details[0];
    
            // if (errorDetail && errorDetail.issue === 'INSTRUMENT_DECLINED') 
            // {
            //     return actions.restart();         
            // }
            // console.log(errorDetail);
            
            // if (errorDetail) {
                
            //     var msg = 'Sorry, your transaction could not be processed.';
            //     if (errorDetail.description) msg += '\n\n' + errorDetail.description;
            //     if (orderData.debug_id) msg += ' (' + orderData.debug_id + ')';
            //     return alert(msg); // Show a failure message
            // }

        }
    }).render('#payment-buttons');

    
</script>

@endsection