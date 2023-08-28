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
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="container590">
          <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#fcfcfc">
                  <tr>
                    <td class="logo" style=" padding:20px;"><a href="{{url('/')}}"><img border="0" src="{{asset('images/site/logo.png')}}" alt="" /></a></td>
                    <td><img src="{{asset('images/flower.jpg')}}" width="82" height="114" style="float:right;" /></td>
                  </tr>
                </table>

            </td>
          </tr>
          <tr>
            <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" style="padding:50px 20px 0 20px; font-family:Georgia, 'Times New Roman', Times, serif;"><h1 style="color:#c92d2d; font-size:22px;">Dear {{$data->name}},</h1></td>
                </tr>
                <tr>
                    <td valign="middle" style="text-align:left; padding:30px 20px 0 20px;"><p style="width:100%; float:left; text-align:left; font-size:14px; font-family:Georgia, 'Times New Roman', Times, serif; line-height:24px;">Thank you for shopping with us!</p></td>
                </tr>
                <tr>
                    <td valign="middle" style="text-align:left; padding:40px 20px 0 20px;"><h3 style="width:100%; float:left; text-align:left; font-size:17px; font-family:Georgia, 'Times New Roman', Times, serif; line-height:28px; margin:0 0 0 0;">Here’s what is on your cart: </h3></td>
                </tr>
             
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                   
                    <td style="width:auto; float:left; margin:12px 0 0 20px;font-size:14px; font-family:Georgia, 'Times New Roman', Times, serif; line-height:28px; font-weight:700;">Cart Date:</td>
                    <td style="width:auto; float:left; margin:12px 0 0 20px;font-size:14px; font-family:Arial, Helvetica, sans-serif; line-height:28px; font-weight:100;">
                        @if( $main_order_data->purchase_date != '0000-00-00 00:00:00')
                            {{ date('d-m-Y' , strtotime($main_order_data->purchase_date)) }}
                        @endif
                    </td>
                    </tr>
                </table>
                <?php
                $total_cart_price = 0; $final_price = 0; $shipping_charges = 0; $payable = 0;

                if( count($order_data) > 0 ) {
                ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:20px 0 0 20px;">
                    <tr>
                        <td><label style="text-align:left; font-size:14px; text-align:center; width:80px; float:left; font-family:Georgia, 'Times New Roman', Times, serif; line-height:28px; font-weight:700;">Image</label></td>
                        <td><label style="text-align:left; font-size:14px; text-align:center; width:80px; float:left; font-family:Georgia, 'Times New Roman', Times, serif; line-height:28px; font-weight:700;">Product</label></td>
                        <td><label style="text-align:left; font-size:14px; text-align:center; width:92px; float:left;  font-family:Georgia, 'Times New Roman', Times, serif; line-height:28px; font-weight:700;">Send To</label></td>
                        <td><label style="text-align:left; font-size:14px; text-align:center; width:100%; float:left;  font-family:Georgia, 'Times New Roman', Times, serif; line-height:28px; font-weight:700;">Price</label></td>
                        <td><label style="text-align:left; font-size:14px; text-align:center; width:100%; float:left;  font-family:Georgia, 'Times New Roman', Times, serif; line-height:28px; font-weight:700;">Qty</label></td>
                        
                        <td><label style="text-align:left; font-size:14px; text-align:center; width:100%; float:left; font-family:Georgia, 'Times New Roman', Times, serif; line-height:28px; font-weight:700;">Total</label></td>
                    </tr>
                    <?php
                        foreach( $order_data as $data ) {
                    ?>
                    <tr>
                        <td style="margin: 12px 0 13px 0; float: left;">
                            <span>
                            @if( isset($data['gift_addon_id']) && $data['gift_addon_id'] == 0 )
                                @php //For product only @endphp
                                @if(isset($data['image']) && $data['image'] != null )
                                    @if(file_exists(public_path('/uploaded/product/thumb/'.$data['image'])))
                                        {!! '<img src="' . URL::to('/') . '/uploaded/product/thumb/' . $data['image'] . '" style="width:75px; height:75px; float:left;" >' !!}
                                    @else
                                        {!! '<img src="' . URL::to('/').config('global.no_image_thumb') . '" style="width:75px; height:75px; float:left;" >' !!}
                                    @endif
                                @else
                                    {!! '<img src="'.URL::to('/').config('global.no_image_thumb').'" style="width:75px; height:75px; float:left;" >' !!}
                                @endif
                            @else
                                @php //For gift addon only @endphp
                                @if(isset($data['image']) && $data['image'] != null )
                                    @if(file_exists(public_path('/uploaded/product_extra/thumb/'.$data['image'])))
                                        {!! '<img class="gift_image" src="' . URL::to('/') . '/uploaded/product_extra/thumb/' . $data['image'] . '" style="width:75px; height:75px; float:left;" >' !!}
                                    @else
                                        {!! '<img class="gift_image" src="' . URL::to('/').config('global.no_image_thumb') . '" style="width:75px; height:75px; float:left;" >' !!}
                                    @endif
                                @else
                                    {!! '<img class="gift_image" src="'.URL::to('/').config('global.no_image_thumb').'" style="width:75px; height:75px; float:left;" >' !!}
                                @endif
                            @endif
                            </span>
                        </td>
                        <td>
                            <span style="width: 100%;float: left;text-align: left;font-size: 14px;font-family: Georgia, 'Times New Roman', Times, serif;line-height: 28px;">
                                {{ $data['product_name'] }}
                            </span>
                        </td>
                        <td>
                            <span style="width: 100%; float: left; text-align: left; font-size: 14px; font-family: Georgia, 'Times New Roman', Times, serif; line-height: 20px; padding-left: 17px;">
                                @php
                                    if( isset($data['product_id']) && $data['product_id'] != 0 ) {
                                        echo @$data['delivery_pincode'];
                                        echo '<br>'.date('D, M d, Y', strtotime($data['delivery_date'])).'<br>';
                                        echo $data['shippingmethod_name'];
                                    }
                                @endphp
                            </span>
                        </td>
                        <td>
                            <span style="width:100%; float:left; text-align:center; font-size:14px; font-family:Georgia, 'Times New Roman', Times, serif; line-height:28px;">
                            @php
                            if( $main_order_data->order_currency != null ) {
                                echo '<span style="font-family: DejaVu Sans; sans-serif;">'.$main_order_data->order_currency->html_code.'</span>';
                            }
                                echo Currency::orderCurrency($data['unit_price'], ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]);

                                $total_cart_price = $total_cart_price + ( $data['unit_price'] * $data['qty'] );

                                $shipping_charges = $shipping_charges + $data['ship_price'];
                            @endphp
                            </span>
                        </td>
                        <td>
                            <span style="width:100%; float:left; text-align:center; font-size:14px; font-family:Georgia, 'Times New Roman', Times, serif; line-height:28px;">
                            {{ $data['qty'] }}
                            </span>
                        </td>
                        <td>
                            <span style="width:100%; float:left; text-align:center; font-size:14px; font-family:Georgia, 'Times New Roman', Times, serif; line-height:28px;">
                            @php
                            if( $main_order_data->order_currency != null ) {
                                echo '<span style="font-family: DejaVu Sans; sans-serif;">'.$main_order_data->order_currency->html_code.'</span>';
                            }
                                echo Currency::orderCurrency($data['total_price'], ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]);
                            @endphp
                            </span>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </table>
                
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:0 0 0 20px;">
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>
                            <label style="font-size:14px; text-align:right; width:auto; float:right; font-family:Arial, Helvetica, sans-serif;; line-height:28px; font-weight:700;">Grand Total</label>
                        </td>
                        <td>
                            <label style="font-size:14px; text-align:right; width:auto; float:right; font-family:Arial, Helvetica, sans-serif; line-height:28px; font-weight:100; padding-right:20px;">
                            <i class="fa fa-inr"></i>            
                            <?php
                            if( $main_order_data->order_currency != null ) {
                                echo '<span style="font-family: DejaVu Sans; sans-serif;">'.$main_order_data->order_currency->html_code.'</span>';
                            }
                                echo Currency::orderCurrency($total_cart_price, ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]);
                                $final_price = $total_cart_price + $final_price;
                            ?>
                            </label>
                        </td>
                    </tr>

                    <?php
                        if( $shipping_charges != 0 ) {
                    ?>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td><label style="font-size:14px; text-align:right; width:auto; float:right; font-family:Arial, Helvetica, sans-serif; line-height:28px; font-weight:700;">Shipping Price</label></td>
                        <td>
                            <label style="font-size:14px; text-align:right; width:auto; float:right; font-family:Arial, Helvetica, sans-serif; line-height:28px; font-weight:100; padding-right:20px;">
                            <i class="fa fa-inr"></i>
                            <?php
                            if( $main_order_data->order_currency != null ) {
                                echo '<span style="font-family: DejaVu Sans; sans-serif;">'.$main_order_data->order_currency->html_code.'</span>';
                            }
                                echo Currency::orderCurrency($shipping_charges, ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]);
                                $final_price = $final_price + $shipping_charges;
                            ?>
                            </label>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>

                    <?php
                    if( $main_order_data->order_coupon_data != null ) {
                        $coupondata = $main_order_data->order_coupon_data;

                        $discount_amount = 0;
                        if( $coupondata != null ) {
                            $discount_amount = $main_order_data->order_coupon_data->coupon_discount_amount;
                        }
                    ?>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td><label style="font-size:14px; text-align:right; width:auto; float:right; font-family:Arial, Helvetica, sans-serif; line-height:28px; font-weight:700;">Discount</label></td>
                        <td>
                            <label style="font-size:14px; text-align:right; width:auto; float:right; font-family:Arial, Helvetica, sans-serif; line-height:28px; font-weight:100; padding-right:20px;">
                            <i class="fa fa-inr"></i>
                            <?php
                                $final_price = $final_price - $discount_amount;
                            if( $main_order_data->order_currency != null ) {
                                echo '<span style="font-family: DejaVu Sans; sans-serif;">'.$main_order_data->order_currency->html_code.'</span>';
                            }

                                echo '<a style="margin-top: 5px;">'.Currency::orderCurrency($discount_amount, ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]).'</a>';
                            ?>
                            </label>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td><label style="font-size:14px; text-align:right; width:auto; float:right; font-family:Arial, Helvetica, sans-serif; line-height:28px; font-weight:700;">Total</label></td>
                        <td>
                        <label style="font-size:14px; text-align:right; width:auto; float:right; font-family:Arial, Helvetica, sans-serif; line-height:28px; font-weight:100; padding-right:20px;"><i class="fa fa-inr"></i>
                        <?php
                        if( $main_order_data->order_currency != null ) {
                            echo '<span style="font-family: DejaVu Sans; sans-serif;">'.$main_order_data->order_currency->html_code.'</span>';
                        }
                            $payable = $final_price;
                            echo Currency::orderCurrency($payable, ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]);
                        ?>
                        </label>
                        </td>
                    </tr>
                </table>
                <?php   
                }else{
                    echo 'No record found.';
                }
                ?>
                <tr>
                    <td>
                    <p style="font-size:18px; line-height:20px; color:#000; padding:30px 20px 0 20px; font-family:Calibri;">Best regards,<br />
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