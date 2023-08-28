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
                <td style="padding:0 20px" align="center"><a href="{{url('/')}}"><img src="{{asset('images/site/nfLogo.jpg')}}" border="0" title="{{ config('global.website_title_camel_case') }}" /></a></td>
              </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;">
            <p style="font-size:26px; text-align:center; line-height:21px; color:#dc446c; padding:50px 20px 0 20px; font-family:verdana;">You left something special behind!</p>
        </td>
    </tr>
    <tr>
        <td>
            <p style="font-size:22px; text-align:center; line-height:21px; color:#60224b; padding:50px 0px 0 0px; font-family:Calibri;">Dear <?php print_r($data); ?>,</p>
        </td>
    </tr>
    <tr>
        <td>
            <p style="font-size:18px; text-align:center; line-height:21px; color:#444444; padding:18px 0px 18PX 0px; font-family:Calibri;">
                you accidentally left behind something very special in the cart!
                <br>
                Do not panic, we’ve got you.
                <br>
                Return to the cart and send the lovely items you picked to the special person
            </p>

            <p style="color:#444444; text-align:center;">
                Here’s a 5% discount code to make your day even better: <b>NICE5</b>
            </p>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>      
    
    <?php
        if( count($order_data) > 0 ) {
    ?>
    <tr>
        <td>
            <table width="100%" border="1" cellspacing="0" cellpadding="2" style="font-size:12px; font-family:verdana; text-align: center">
                <thead>
                    <tr>
                        <td style="text-align: center; color: #000"><strong>Product Image</strong></td>
                        <td style="text-align: center; color: #000"><strong>Product Name</strong></td>
                        <td style="text-align: center; color: #000"><strong>Qty</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach( $order_data as $data ) {
                    ?>
                    <tr>
                        <td align="center">
                            
                            @if(isset($data['image']) && $data['image'] != null )
                                @if(file_exists(public_path('/uploaded/product/'.$data['image'])))
                                    {!! '<img src="' . config('global.website_url') . '/uploaded/product/' . $data['image'] . '" style="width:80px; height:80px;" >' !!}
                                @else
                                    {!! '<img src="' . config('global.website_url').config('global.no_image') . '" style="width:80px; height:80px;" >' !!}
                                @endif
                            @else
                                {!! '<img src="'.config('global.website_url').config('global.no_image').'" style="width:80px; height:80px;" >' !!}
                            @endif
                        </td>
                        <td align="center">
                            {{ $data['product_name'] }}
                            @php
                                if( $data['attribute_name'] != '' ) {
                                    echo '<br>('.$data['attribute_name'].')';
                                }
                            @endphp
                        </td>
                        <td align="center">
                            {{ $data['qty'] }}
                        </td>
                    </tr>
                    
                    <?php
                        }
                    ?>
                </tbody>
            </table>            
        </td>
    </tr>
    
    <tr>
        <td>&nbsp;</td>
    </tr>
    <?php   
    }else{
        echo 'No record found.';
    }
    ?>

    

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td valign="middle" style="text-align:center; margin:56px 0 30px 0; display:block;">
            <a href="https://www.giftbasketworldwide.com/abandonedcartcheck" style="width:auto; text-align:center; text-decoration:none; margin:0 auto; color:#fff; padding:10px 20px; border-radius:50px; background:#203060;">Let's do this!</a>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td>
        <p style="font-size:18px; text-align:center; line-height:21px; color:#000; padding:30px 0px 0 0px; font-family:Calibri;">Best Regards,<br />
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
            <p style="font-size:18px; text-align:center; line-height:20px; color:#000; padding:30px 20px; font-family:Calibri;">
                This message was sent to you by {{ config('global.website_title_camel_case') }}.<br />
                If you didn’t create this account, contact {{ config('global.website_title_camel_case') }} <a href="{{url('/contact-us')}}"><i>support</i></a>.
            </p>
        </td>
    </tr>
    </table>
</body>
</html>