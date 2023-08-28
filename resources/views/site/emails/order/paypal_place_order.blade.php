<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
    <meta name="viewport" content="width=600,initial-scale = 2.3,user-scalable=no">
    <!--[if !mso]><!-- -->
    <link href='https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700' rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Quicksand:300,400,700' rel="stylesheet">
    <!-- <![endif]-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>{{ config('global.website_title') }}</title>

    <style type="text/css">
        body {
            width: 100%;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            mso-margin-top-alt: 0px;
            mso-margin-bottom-alt: 0px;
            mso-padding-alt: 0px 0px 0px 0px;
        }

        p,
        h1,
        h2,
        h3,
        h4 {
            margin-top: 0;
            margin-bottom: 0;
            padding-top: 0;
            padding-bottom: 0;
        }

        span.preheader {
            display: none;
            font-size: 1px;
        }

        html {
            width: 100%;
        }

        table {
            font-size: 14px;
            border: 0;
        }
        .container590
        {
           width:650px;
           margin:0 auto;   
        }
        .socl
        {
           width:35%;   
        }
        .tempthumb
        {
           padding:0 20px;  
        }
        .txt
        {
            border-bottom: 1px dashed #a8a8a8;
            border-left: 0;
            border-top: 0;
            border-right: 0;
            outline: none;
            margin: 0 0 20px 0;
            width: 100%;
        }
        .txt2
        {
            border-bottom:0;
            border-left: 0;
            border-top: 0;
            border-right: 0;
            outline: none;
            margin: 0 0 20px 0;
            width:90%;
        }
        .sub
        {
             color:#e25959; text-align:center; width:105%; float:left; font-size:16px;  
        }
        .success
        {
             color:#c92d2d; width:100%; float:left; text-align:center; font-size:12px; padding:50px 20px 0 20px; font-family:Georgia, 'Times New Roman', Times, serif;
        }
        .cartsectemp
        {
            margin:20px 0 0 0;  
        }
        /* ----------- responsivity ----------- */

        @media only screen and (max-width: 640px) {
        .success
        {
             color:#c92d2d; width:100%; float:left; text-align:center; font-size:12px; padding:50px 0 0 0; font-family:Georgia, 'Times New Roman', Times, serif;
        }
        .sub
        {
             color:#e25959; text-align:center; width:100%; float:left; font-size:16px;  
        }
            .tempthumb img
            {
               width: 94%;
               float: left;
               margin: 0 6px;   
            }
            .socl
            {
               width:50%;   
            }
            .container590
            {
                width:100%; 
            }
            /*------ top header ------ */
            .main-header {
                font-size: 20px !important;
            }
            .main-section-header {
                font-size: 28px !important;
            }
            .show {
                display: block !important;
            }
            .hide {
                display: none !important;
            }
            .align-center {
                text-align: center !important;
            }
            .no-bg {
                background: none !important;
            }
            /*----- main image -------*/
            .main-image img {
                width:10% !important;
                height: auto !important;
            }
            /* ====== divider ====== */
            .divider img {
                width:10% !important;
            }
            /*-------- container --------*/
            .container590 {
                width:100% !important;
            }
            .container580 {
                width:100% !important;
            }
            .main-button {
                width:100% !important;
            }
            /*-------- secions ----------*/
            .section-img img {
                width:100% !important;
                height: auto !important;
            }
            .team-img img {
                width: 100% !important;
                height: auto !important;
            }
        }

        @media only screen and (max-width: 479px) {
            /*------ top header ------ */
            .main-header {
                font-size: 18px !important;
            }
            .main-section-header {
                font-size: 26px !important;
            }
            /* ====== divider ====== */
            .divider img {
               width:100% !important;
            }
            /*-------- container --------*/
            .container590 {
                width:100% !important;
            }
            .container590 {
                width: 280px !important;
            }
            .container580 {
                width:100% !important;
            }
            /*-------- secions ----------*/
            .section-img img {
                width:100% !important;
                height: auto !important;
            }
        }
    </style>
</head>


<body class="respond" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="container590" style="background-color: white">
          <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                  <tr>
                    <td class="logo" style=" padding:20px;"><a href="{{url('/')}}"><img border="0" src="{{asset('images/site/logo.png')}}" alt="Gift" /></a></td>
                    <td><img src="{{asset('images/gift.jpg')}}" width="82" height="114" style="float:right;" /></td>
                  </tr>
                </table>

            </td>
          </tr>
          <tr>
            <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" style="padding:50px 20px 0 20px; font-family:Georgia, 'Times New Roman', Times, serif;"><h1 style="color:#c92d2d; font-size:22px;">


                    <?php echo '<pre>'; print_r($data); ?>

                    </h1></td>
                </tr>
                <tr>
                    <td valign="middle" style="text-align:left; padding:30px 20px 0 20px;"><p style="width:100%; float:left; text-align:left; font-size:14px; font-family:Georgia, 'Times New Roman', Times, serif; line-height:24px;">Thank you for shopping with us!</p></td>
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
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tempthumb">
                    <tr>
                        <td><img src="{{asset('images/img01.jpg')}}" /></td>
                        <td><img src="{{asset('images/img02.jpg')}}" /></td>
                        <td><img src="{{asset('images/img03.jpg')}}" /></td>
                        <td><img src="{{asset('images/img04.jpg')}}" /></td>
                    </tr>
                    </table>
                    </td>
                </tr>
                <tr>
                    <td valign="middle" style="color:#444444; font-size:15px; padding:38px 0 28px 0; font-family:Georgia, 'Times New Roman', Times, serif; text-align:center;">
						<table class="socl" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">
							<tr>
								<td><a href="{{ config('global.facebook_link') }}" target="_blank"><img src="{{asset('images/facebook.jpg')}}" border="0" /></a></td>
								<td><a href="{{ config('global.twitter_link') }}" target="_blank"><img src="{{asset('images/twitter.jpg')}}" border="0" /></a></td>
								<td><a href="{{ config('global.pinterest_link') }}" target="_blank"><img src="{{asset('images/pinterest.jpg')}}" border="0" /></a></td>
								<td><a href="{{ config('global.youtube_link') }}" target="_blank"><img src="{{asset('images/youtube.jpg')}}" border="0" /></a></td>
							</tr>
                        </table>
                    </td>
                </tr>
                <tr>        
                    <td class="footcont" valign="middle" style="text-align:center;">
                        <p style="font-size:18px; line-height:20px; color:#000; padding:30px 20px; font-family:Calibri;">This message was sent to you by {{ config('global.website_title_camel_case') }}.<br />
                        If you didn't create this account, contact {{ config('global.website_title_camel_case') }} support
                        </p>
                    </td>
                </tr>
            </table>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
    </table>

</body>

</html>