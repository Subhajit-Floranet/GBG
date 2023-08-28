<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>{{ config('global.website_title') }}</title>
    <style>
        *
        {
          padding:0;
          margin:0;
        }
        .container690
        {
            width:650px;
            margin:0 auto;
        }
        .imgsec
        {
            padding:36px 20px;
        }
        @media only screen and (max-width:1023px) {
            .container690
            {
                width:90%;
                margin:0 auto;
            }
            .imgsec img
            {
                width: 98%;
                margin: 0 5px 0 0;
                float: left;
                display: inline-block;
            }
            p{ font-size:14px!important; }
            .footcont p
            {
               font-size:10px!important; text-align:center;
            }
        }
    </style>
</head>

<body>
   <table width="100%" border="0" cellpadding="0" cellspacing="0" class="container690" style="background-color: white">
    <tr>
        <td>
            <table width="100%" border="0" bgcolor="#ffffff">
              <tr>
                <td style="padding:0 20px" align="center"><a href="{{url('/')}}"><img src="{{asset('images/site/gfLogo.jpg')}}" border="0" title="{{ config('global.website_title_camel_case') }}" /></a></td>
              </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;">
            <p style="font-size:26px; font-weight: normal; text-align:center; line-height:21px; color:#e9724c; padding:50px 20px 0 20px; font-family:verdana;">Your order has been delivered!</p>
        </td>
    </tr>
    <tr>
        <td>
            <p style="font-size:20px; font-weight: normal; text-align:center; line-height:21px; color:#e9724c; padding:50px 20px 0 20px; font-family:verdana;">Dear {{$data['customer_data']->name}},</p>
        </td>
    </tr>
    <tr>
        <td><p style="font-size:18px; text-align:center; line-height:21px; color:#444444; padding:30px 20px 0 20px; font-family:Calibri;">
            Your order has been successfully delivered!
            <br><br>
            We hope you had a great experience with us.
            Do let us know how we can make the ordering experience better for you and how else we can serve you!<br>
        </p></td>
    </tr>
    <tr>
        <td><p style="font-size:15px; text-align:center; line-height:21px; color:#444444; padding:30px 20px 0 20px; font-family:Calibri;">
            If you could do one of the following, that’d be great:
            <br><br>
            1. Like us - 10 Brownie Points <a href="{{ config('global.facebook_link') }}" style="font-size:14px;">Click Here</a><br>
            2. Rate us on Facebook - 30 Brownie Points & 5% Discount Coupon <a href="{{ config('global.facebook_link') }}" style="font-size:14px;">Click Here</a><br>
            3. Like us AND Rate us on Facebook - 100 Brownie Points & 10% Discount Coupon <a href="{{ config('global.facebook_link') }}" style="font-size:14px;">Click Here</a>
        </p></td>
    </tr>
    
    <tr>
        <td>
        <p style="font-size:18px; text-align:center; line-height:20px; color:#000; padding:30px 20px 0 20px; font-family:Calibri;">Best Regards,<br />
            {{ config('global.email_regards') }}
        </p>
        </td>
    </tr>
    
    <tr>
        <td valign="middle" style="text-align:center;">
            <table width="35%" border="0" style="margin:0 auto;">
                <tr>
                    <td><a href="{{ config('global.facebook_link') }}" target="_blank"><img src="{{asset('images/site/mail/facebook.jpg')}}" border="0" /></a></td>
                    <td><a href="{{ config('global.twitter_link') }}" target="_blank"><img src="{{asset('images/site/mail/twitter.jpg')}}" border="0" /></a></td>
                    <td><a href="{{ config('global.instagram_link') }}" target="_blank"><img src="{{asset('images/site/mail/instagram.jpg')}}" border="0" /></a></td>
                    <td><a href="{{ config('global.youtube_link') }}" target="_blank"><img src="{{asset('images/site/mail/youtube.jpg')}}" border="0" /></a></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="footcont" valign="middle" style="text-align:center;">
        <p style="font-size:18px; text-align:center; line-height:20px; color:#000; padding:30px 20px; font-family:Calibri;">This message was sent to you by {{ config('global.website_title_camel_case') }}.<br />
        If you didn’t create this account, contact {{ config('global.website_title_camel_case') }} <a href="{{url('/contact-us')}}"><i>support</i></a>.</p>
        </td>
    </tr>
    </table>
</body>
</html>