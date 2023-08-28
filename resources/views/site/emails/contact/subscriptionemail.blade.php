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
                <td style="padding:0 20px"><a href="{{url('/')}}"><img src="{{asset('images/site/sitelogo.webp')}}" border="0" title="Gift" /></a></td>
                <td style="float:right;"><img src="{{asset('images/gift.jpg')}}" /></td>
              </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <h1 style="font-size:22px; line-height:26px; color:#92c13e; padding:50px 20px 0 20px; font-family:Calibri;">Dear Visitor,</h1>
        </td>
    </tr>

    <tr>
        <td><p style="font-size:18px; line-height:20px; color:#444444; padding:30px 20px 0 20px; font-family:Calibri;">
        Thank you for contacting us <br><br>
        We shall get back to you as soon as possible via email. 
        </p></td>
    </tr>

    
    <tr>
        <td>
        <p style="font-size:18px; line-height:20px; color:#000; padding:30px 20px 0 20px; font-family:Calibri;">Best Regards,<br />
            {{ config('global.email_regards') }}
        </p>
        </td>
    </tr>
    <tr>
        <td>
        <table width="100%" border="0" class="imgsec">
          <tr>
            <td><img src="{{asset('images/img01.jpg')}}" /></td>
            <td><img src="{{asset('images/img02.jpg')}}" /></td>
            <td><img src="{{asset('images/img03.jpg')}}" /></td>
            <td><img src="{{asset('images/img04.jpg')}}" /></td>
          </tr>
        </table>
        </td>
    </tr>
    {{-- <tr>
        <td valign="middle" style="text-align:center;">
            <table width="35%" border="0" style="margin:0 auto;">
				<tr>
					<td><a href="{{ config('global.facebook_link') }}" target="_blank"><img src="{{asset('images/facebook.jpg')}}" border="0" /></a></td>
					<td><a href="{{ config('global.twitter_link') }}" target="_blank"><img src="{{asset('images/twitter.jpg')}}" border="0" /></a></td>
					<td><a href="{{ config('global.pinterest_link') }}" target="_blank"><img src="{{asset('images/pinterest.jpg')}}" border="0" /></a></td>
					<td><a href="{{ config('global.youtube_link') }}" target="_blank"><img src="{{asset('images/youtube.jpg')}}" border="0" /></a></td>
				</tr>
            </table>
        </td>
    </tr> --}}
    <tr>
        <td class="footcont" valign="middle" style="text-align:center;">
        <p style="font-size:18px; line-height:20px; color:#000; padding:30px 20px; font-family:Calibri;">This message was sent to you by {{ config('global.website_title_camel_case') }}.<br />
        If you didn’t create this account, contact {{ config('global.website_title_camel_case') }} support</p>
        </td>
    </tr>
    </table>
</body>
</html>