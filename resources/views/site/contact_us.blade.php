@php
$meta_data['keyword'] = $contact_us->meta_keyword;
$meta_data['description'] = $contact_us->meta_description;
$meta_title = $contact_us->meta_title;
@endphp
@php $meta = App\Http\Helper::get_meta($meta_data); @endphp
@php $gencode = App\Http\Helper::contactCapcha(); @endphp
@extends('layouts.site.app', ['title' => $meta_title, 'meta_keyword' => $meta['meta_keyword'], 'meta_description' => $meta['meta_description']])

@section('content')

<ul class="breadcrumb">
    <li><a href="{{ url('/') }}">Home</a></li>
    <li>Contact Us</li>
</ul>

<div class="contact-container">
  <div class="contact-lt">
    <div class="lt-head">Contact Us</div>
    <div class="lt-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
                <h4 class="font-weight-light alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</h4>
            @endif
        @endforeach

        <form method="POST" action="{{route('contact-us')}}" id="contactUs" class="contact-form" enctype="multipart/form-data">
        @csrf
        <div class="con-field">
            <div class="title"><sup>*</sup><label>Type of Query :</label></div>
            <select name="contact_type" id="contact_type" required>
                <option value="">Please select an Option</option>
                <option value="related">Want to place an order?</option>
                <option value="existing">Regarding an existing order</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="con-field hidediv" id="showrelated" style="display: none;">
            <div class="title"><sup>*</sup><label>Related to:</label></div>
            <select name="" id="">
                <option value="0">Please Select an Option</option>
                <option value="- Delivery Location">- Delivery Location</option>
                <option value="- When can we deliver">- When can we deliver</option>
                <option value="- Products">- Products</option>
                <option value="- Payments">- Payments</option>
                <option value="- Refund Policy">- Refund Policy</option>
                <option value="- Bulk Order">- Bulk Order</option>
                <option value="- Corporate enquiry">- Corporate enquiry</option>
                <option value="- Customized order">- Customized order</option>
                <option value="- Other">- Other</option>
            </select>
        </div>
        <div class="hidediv hidediv" id="showexisting" style="display: none;">
            <div class="con-field " >
                <div class="title"><sup>*</sup><label>Related to:</label></div>
                <select id="" name="" >	
                    <option value="0">Please Select an Option</option>
                    <option value="- Did you receive my order?">- Did you receive my order?</option>
                    <option value="- When will my order get delivered?">- When will my order get delivered?</option>
                    <option value="- Cancel my order">- Cancel my order</option>
                    <option value="- Want a refund">- Want a refund</option>
                    <option value="- Make a complaint">- Make a complaint</option>
                    <option value="- Other">- Other</option>
                </select>
            </div>
            <div class="con-field " >
                <div class="title"><sup>*</sup><label>Order ID:</label></div>
                <input type="text" name="" id="" placeholder="Please Enter User ID">
            </div>
        </div>
        <div class="con-field hidediv" id="showother" style="display: none;">
            <div class="title">
            <sup>*</sup><label for="">Subject</label></div>
            <input type="text" name="" id="" placeholder="Enter Your Subject">
        </div>
        <div class="con-field">
            <div class="title">
            <sup>*</sup><label for="">Name</label></div>
            <input required="" id="contact_name" placeholder="Name*" name="name" type="text">
        </div>
        <div class="con-field">
            <div class="title">
            <sup>*</sup><label for="">Email</label></div>
            <input required="" id="contact_email" placeholder="Email*" name="email" type="text">
        </div>
        <div class="con-field">
            <div class="title">
            <sup>*</sup><label for="">Mobile</label></div>
            <input required="" id="contact_mobile" placeholder="Mobile*" name="mobile" type="text">
        </div>
        {{-- <div class="con-field selcountry"  >
            <div class="title">
            <sup>*</sup><label for="">Where are you from ?:</label></div>
            <select name="country" id="country">
            <option value="0">Select Country</option>
            <option value="Afghanistan">Afghanistan</option>
            <option value="Albania">Albania</option>
            <option value="Algeria">Algeria</option>
            <option value="American Samoa">American Samoa</option>
            <option value="Andorra">Andorra</option>
            <option value="Angola">Angola</option>
            <option value="Anguilla">Anguilla</option>
            <option value="Antigua">Antigua</option>
            <option value="Antigua And Barbuda">Antigua And Barbuda</option>
            <option value="Argentina">Argentina</option>
            <option value="Armenia">Armenia</option>
            <option value="Aruba">Aruba</option>
            <option value="Australia">Australia</option>
            <option value="Austria">Austria</option>
            <option value="Azerbaijan">Azerbaijan</option>
            <option value="Bahamas">Bahamas</option>
            <option value="Bahrain">Bahrain</option>
            <option value="Bangladesh">Bangladesh</option>
            <option value="Barbados">Barbados</option>
            <option value="Belarus">Belarus</option>
            <option value="Belgium">Belgium</option>
            <option value="Belize">Belize</option>
            <option value="Benin">Benin</option>
            <option value="Bermuda">Bermuda</option>
            <option value="Bhutan">Bhutan</option>
            <option value="Bolivia">Bolivia</option>
            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
            <option value="Botswana">Botswana</option>
            <option value="Brazil">Brazil</option>
            <option value="British Virgin Islands">British Virgin Islands</option>
            <option value="Brunei">Brunei</option>
            <option value="Bulgaria">Bulgaria</option>
            <option value="Burkina Faso">Burkina Faso</option>
            <option value="Burma">Burma</option>
            <option value="Burundi">Burundi</option>
            <option value="Cambodia">Cambodia</option>
            <option value="Cameroon">Cameroon</option>
            <option value="Canada">Canada</option>
            <option value="Cape Verde">Cape Verde</option>
            <option value="Cayman Islands">Cayman Islands</option>
            <option value="Central African Republic">Central African Republic</option>
            <option value="Chad">Chad</option>
            <option value="Chile">Chile</option>
            <option value="China">China</option>
            <option value="Christmas Island">Christmas Island</option>
            <option value="Colombia">Colombia</option>
            <option value="Comoros">Comoros</option>
            <option value="Congo">Congo</option>
            <option value="Cook Islands">Cook Islands</option>
            <option value="Costa Rica">Costa Rica</option>
            <option value="Croatia">Croatia</option>
            <option value="Cuba">Cuba</option>
            <option value="Curacao">Curacao</option>
            <option value="Cyprus">Cyprus</option>
            <option value="Czech Republic">Czech Republic</option>
            <option value="Denmark">Denmark</option>
            <option value="Djibouti">Djibouti</option>
            <option value="Dominica">Dominica</option>
            <option value="Dominican Republic">Dominican Republic</option>
            <option value="East Timor">East Timor</option>
            <option value="Ecuador">Ecuador</option>
            <option value="Egypt">Egypt</option>
            <option value="El Salvador">El Salvador</option>
            <option value="Equatorial Guinea">Equatorial Guinea</option>
            <option value="Eritrea">Eritrea</option>
            <option value="Estonia">Estonia</option>
            <option value="Ethiopia">Ethiopia</option>
            <option value="Falkland Islands">Falkland Islands</option>
            <option value="Faroe Islands">Faroe Islands</option>
            <option value="Fiji">Fiji</option>
            <option value="Finland">Finland</option>
            <option value="France">France</option>
            <option value="French Guiana">French Guiana</option>
            <option value="French Polynesia">French Polynesia</option>
            <option value="Gabon">Gabon</option>
            <option value="Gambia">Gambia</option>
            <option value="Georgia">Georgia</option>
            <option value="Germany">Germany</option>
            <option value="Ghana">Ghana</option>
            <option value="Gibraltar">Gibraltar</option>
            <option value="Greece">Greece</option>
            <option value="Greenland">Greenland</option>
            <option value="Grenada">Grenada</option>
            <option value="Guadeloupe">Guadeloupe</option>
            <option value="Guam">Guam</option>
            <option value="Guatemala">Guatemala</option>
            <option value="Guinea">Guinea</option>
            <option value="Guinea-Bissau">Guinea-Bissau</option>
            <option value="Guyana">Guyana</option>
            <option value="Haiti">Haiti</option>
            <option value="Honduras">Honduras</option>
            <option value="Hong Kong">Hong Kong</option>
            <option value="Hungary">Hungary</option>
            <option value="Iceland">Iceland</option>
            <option value="India">India</option>
            <option value="Indonesia">Indonesia</option>
            <option value="Iran">Iran</option>
            <option value="Iraq">Iraq</option>
            <option value="Ireland">Ireland</option>
            <option value="Israel">Israel</option>
            <option value="Italy">Italy</option>
            <option value="Ivory Coast">Ivory Coast</option>
            <option value="Jamaica">Jamaica</option>
            <option value="Japan">Japan</option>
            <option value="Jordan">Jordan</option>
            <option value="Kazakhstan">Kazakhstan</option>
            <option value="Kenya">Kenya</option>
            <option value="Kiribati">Kiribati</option>
            <option value="Kuwait">Kuwait</option>
            <option value="Kyrgyzstan">Kyrgyzstan</option>
            <option value="Laos">Laos</option>
            <option value="Latvia">Latvia</option>
            <option value="Lebanon">Lebanon</option>
            <option value="Lesotho">Lesotho</option>
            <option value="Liberia">Liberia</option>
            <option value="Libya">Libya</option>
            <option value="Liechtenstein">Liechtenstein</option>
            <option value="Lithuania">Lithuania</option>
            <option value="Luxembourg">Luxembourg</option>
            <option value="Macau">Macau</option>
            <option value="Macedonia">Macedonia</option>
            <option value="Madagascar">Madagascar</option>
            <option value="Malawi">Malawi</option>
            <option value="Malaysia">Malaysia</option>
            <option value="Maldives">Maldives</option>
            <option value="Mali">Mali</option>
            <option value="Malta">Malta</option>
            <option value="Marshall Islands">Marshall Islands</option>
            <option value="Martinique">Martinique</option>
            <option value="Mauritania">Mauritania</option>
            <option value="Mauritius">Mauritius</option>
            <option value="Mayotte">Mayotte</option>
            <option value="Mexico">Mexico</option>
            <option value="Micronesia">Micronesia</option>
            <option value="Moldova">Moldova</option>
            <option value="Monaco">Monaco</option>
            <option value="Mongolia">Mongolia</option>
            <option value="Montenegro">Montenegro</option>
            <option value="Montserrat">Montserrat</option>
            <option value="Morocco">Morocco</option>
            <option value="Mozambique">Mozambique</option>
            <option value="Namibia">Namibia</option>
            <option value="Nauru">Nauru</option>
            <option value="Nepal">Nepal</option>
            <option value="Netherlands">Netherlands</option>
            <option value="Netherlands Antilles">Netherlands Antilles</option>
            <option value="New Caledonia">New Caledonia</option>
            <option value="New Zealand">New Zealand</option>
            <option value="Nicaragua">Nicaragua</option>
            <option value="Niger">Niger</option>
            <option value="Nigeria">Nigeria</option>
            <option value="Niue">Niue</option>
            <option value="Norfolk Island">Norfolk Island</option>
            <option value="North Korea">North Korea</option>
            <option value="Northern Mariana Islands">Northern Mariana Islands</option>
            <option value="Norway">Norway</option>
            <option value="Oman">Oman</option>
            <option value="Pakistan">Pakistan</option>
            <option value="Palau">Palau</option>
            <option value="Palestine">Palestine</option>
            <option value="Panama">Panama</option>
            <option value="Papua new Guinea">Papua new Guinea</option>
            <option value="Paraguay">Paraguay</option>
            <option value="Peru">Peru</option>
            <option value="Philippines">Philippines</option>
            <option value="Pitcairn Island">Pitcairn Island</option>
            <option value="Poland">Poland</option>
            <option value="Portugal">Portugal</option>
            <option value="Puerto Rico">Puerto Rico</option>
            <option value="Qatar">Qatar</option>
            <option value="Reunion">Reunion</option>
            <option value="Romania">Romania</option>
            <option value="Russia">Russia</option>
            <option value="Rwanda">Rwanda</option>
            <option value="Saint Helena">Saint Helena</option>
            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
            <option value="Saint Lucia">Saint Lucia</option>
            <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
            <option value="Saint Vincent and the Grenadin">Saint Vincent and the Grenadin</option>
            <option value="Samoa">Samoa</option>
            <option value="San Marino">San Marino</option>
            <option value="Sao Tome and Principe">Sao Tome and Principe</option>
            <option value="Saudi Arabia">Saudi Arabia</option>
            <option value="Scotland">Scotland</option>
            <option value="Senegal">Senegal</option>
            <option value="Serbia and Montenegro">Serbia and Montenegro</option>
            <option value="Seychelles">Seychelles</option>
            <option value="Sierra Leone">Sierra Leone</option>
            <option value="Singapore">Singapore</option>
            <option value="Slovakia">Slovakia</option>
            <option value="Slovenia">Slovenia</option>
            <option value="Solomon Islands">Solomon Islands</option>
            <option value="Somalia">Somalia</option>
            <option value="South Africa">South Africa</option>
            <option value="South Georgia">South Georgia</option>
            <option value="South Korea">South Korea</option>
            <option value="South Sandwich Islands">South Sandwich Islands</option>
            <option value="Spain">Spain</option>
            <option value="Sri Lanka">Sri Lanka</option>
            <option value="Sudan">Sudan</option>
            <option value="Suriname">Suriname</option>
            <option value="Swaziland">Swaziland</option>
            <option value="Sweden">Sweden</option>
            <option value="Switzerland">Switzerland</option>
            <option value="Syria">Syria</option>
            <option value="Taiwan">Taiwan</option>
            <option value="Tajikistan">Tajikistan</option>
            <option value="Tanzania">Tanzania</option>
            <option value="Thailand">Thailand</option>
            <option value="Togo">Togo</option>
            <option value="Tokelau">Tokelau</option>
            <option value="Tonga">Tonga</option>
            <option value="Trinidad And Tobago">Trinidad And Tobago</option>
            <option value="Tunisia">Tunisia</option>
            <option value="Turkey">Turkey</option>
            <option value="Turkmenistan">Turkmenistan</option>
            <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
            <option value="Tuvalu">Tuvalu</option>
            <option value="U.S. Virgin Islands">U.S. Virgin Islands</option>
            <option value="Uganda">Uganda</option>
            <option value="Ukraine">Ukraine</option>
            <option value="United Arab Emirates">United Arab Emirates</option>
            <option value="United Kingdom">United Kingdom</option>
            <option value="United States">United States</option>
            <option value="Uruguay">Uruguay</option>
            <option value="Uzbekistan">Uzbekistan</option>
            <option value="Vanuatu">Vanuatu</option>
            <option value="Vatican City (Holy City)">Vatican City (Holy City)</option>
            <option value="Venezuela">Venezuela</option>
            <option value="Vietnam">Vietnam</option>
            <option value="Wallis and Futuna">Wallis and Futuna</option>
            <option value="West Bank">West Bank</option>
            <option value="Western Sahara">Western Sahara</option>
            <option value="Yemen">Yemen</option>
            <option value="Yugoslavia">Yugoslavia</option>
            <option value="Zambia">Zambia</option>
            <option value="Zimbabwe">Zimbabwe</option><br/>
            </select>
        </div> --}}
        <div class="con-field no-flex">
            <div class="title">
            <sup>*</sup><label for="">Message:</label></div>
            <textarea required="" id="contact_message" placeholder="Message*" name="message" cols="30" rows="10"></textarea>
        </div>

        <div class="verify-captcha-block">
            <div class="verify">
                <div>
                    <input type="text" name="gencode" id="gencode" class="contactform_new_captcha" disabled="disabled" value="<?php echo $gencode; ?>" />
                    <a href="javascript:void(0)" class="reloadcaptcha"><i class="fa-solid fa-arrows-rotate"></i></a>
                </div>
            </div>
            <div class="captcha-down">
                <input required="" autocomplete="off" id="capcha_code" placeholder="Type the text*" equalto="#gencode" name="capchacode" type="text">
                <input type="hidden" name="gencode_verify" id="gencode_verify" value="<?php echo $gencode; ?>" />
            </div>
        </div>

        <div class="con-field flex-end">
            <button type="submit">Submit</button>
        </div>
    </div>
</div>


<div class="contact-rt">
    <div class="bulk">
      <div>Corporate/Bulk Order</div>
      <input type="button" value="click here">
    </div>
    <div class="bulk-add-con">
      <div class="bulk-add-head">Address</div>
      <div class="bulk-add"><i class="fa-sharp fa-solid fa-location-dot"></i> Los Angeles<br>CA 90040, USA</div>
    </div>
  </div>
</div>

<style type="text/css">
    .captchazone{border: 1px solid #a7a8a8; border-radius: 3px; padding: 5px; margin: 0!important; margin-bottom: 1em!important;}
    .fsmallcaptcha{width: 100%!important;}
    .reloadcaptcha{font-size: 25px!important; cursor: pointer;}
    .contactform_new_captcha {
        border: none;
        text-align: center;
        font-size: 30px!important;
        font-style: italic;
        font-family: 'sailecregular';
        letter-spacing: 30px;
        font-weight: 900;
        color: #817e94;
        padding: 0;
        background-image: url(http://localhost/live-project/nfjp/public/images/capcha2.jpg);
        padding: 0!important;
        margin-top: 0!important;
        width: 100%;
    }
</style>

<script type="text/javascript">
    $(document).ready(function(){
      $("#query").on('change', function(){
        var demovalue = $(this).val();  
          $(".hidediv").hide();
          if(demovalue == "existing"){
            $(".selcountry").hide();
          }
          else{
            $(".selcountry").show();
          }
          $("#show"+demovalue).show();
      });
    }) ;

    $(".reloadcaptcha").on('click', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ route("reload-captcha") }}',
            method: 'get',
            success: function(data){
                console.log(data);
                    $('#gencode').val(data.vcode);
                    $('#gencode_verify').val(data.vcode);
            }
        });
    });

    $.validator.setDefaults({
        submitHandler: function(form) {
            form.submit();
        }
    });

    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // validate the comment form when it is submitted
        $("#contactUs").validate({
            rules: {
                capcha_code: {
                    equalTo: '#gencode'
                }
            },
            errorPlacement: function(label, element) {
                label.addClass('mt-2 text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                $(element).parents('.form-group').addClass('has-danger')
                $(element).addClass('form-control-danger')
            }
        });
    });
    
</script> 

@endsection