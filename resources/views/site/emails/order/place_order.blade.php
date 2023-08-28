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
                <td style="padding:0 20px" align="center"><a href="{{url('/')}}"><img src="{{asset('images/site/sitelogo.webp')}}" border="0" title="{{ config('global.website_title_camel_case') }}" /></a></td>
              </tr>
            </table>
        </td>
    </tr>
     <tr>
        <td style="text-align:center;">
            <p style="font-size:26px; text-align:center; line-height:21px; color:#e9724c; padding:50px 20px 0 20px; font-family:verdana;">Order SUCCESSFUL!</p>
        </td>
    </tr>
    <tr>
        <td>
            <p style="font-size:20px; text-align:center; line-height:21px; color:#e9724c; padding:50px 0px 0 0px; font-family:verdana;">Dear {{$data->name}},</p>
        </td>
    </tr>
    <tr>
        <td><h3 style="font-size:18px; text-align:center; line-height:21px; color:#60224b; padding:18px 0px 18PX 0px; font-family:Calibri;"><span style="color:#444444;">Thank you for shopping with us!</td>
    </tr>
    <tr>
        <td><h3 style="font-size:18px; text-align:center; line-height:21px; color:#60224b; padding:18px 0px 18PX 0px; font-family:Calibri;"><span style="color:#404041;">Here’s what you ordered:</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>   

    <?php
        $total_cart_price = 0; $final_price = 0; $shipping_charges = 0; $payable = 0;

        if( count($order_data) > 0 ) {
    ?>
    <tr>
        <td>
            <table width="100%" border="1" cellspacing="0" cellpadding="2" style="font-size:12px; font-family:verdana; text-align: center">
                <thead>
                    <tr>
                        <td style="text-align: center; color: #000"><strong>Image</strong></td>
                        <td style="text-align: center; color: #000"><strong>Item</strong></td>
                        <td style="text-align: center; color: #000"><strong>Delivery Date</strong></td>
                        <td style="text-align: center; color: #000"><strong>Qty</strong></td>
                        <td style="text-align: center; color: #000"><strong>Price</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach( $order_data as $data ) {
                    ?>
                    <tr>
                        <td align="center">
                            @if( isset($data['gift_addon_id']) && $data['gift_addon_id'] == 0 )
                                {{-- @php For product only @endphp --}}
                                @if(isset($data['image']) && $data['image'] != null )
                                    @if(file_exists(public_path('/uploaded/product/'.$data['image'])))
                                        {!! '<img src="' . URL::to('/') . '/uploaded/product/' . $data['image'] . '" style="width:75px; height:75px; float:left;" >' !!}
                                    @else
                                        {!! '<img src="' . URL::to('/').config('global.no_image') . '" style="width:75px; height:75px; float:left;" >' !!}
                                    @endif
                                @else
                                    {!! '<img src="'.URL::to('/').config('global.no_image').'" style="width:75px; height:75px; float:left;" >' !!}
                                @endif
                            @else
                                {{-- @php For gift addon only @endphp --}}
                                @if(isset($data['image']) && $data['image'] != null )
                                    @if(file_exists(public_path('/uploaded/product/'.$data['image'])))
                                        {!! '<img class="gift_image" src="' . URL::to('/') . '/uploaded/product/' . $data['image'] . '" style="width:75px; height:75px; float:left;" >' !!}
                                    @else
                                        {!! '<img class="gift_image" src="' . URL::to('/').config('global.no_image') . '" style="width:75px; height:75px; float:left;" >' !!}
                                    @endif
                                @else
                                    {!! '<img class="gift_image" src="'.URL::to('/').config('global.no_image').'" style="width:75px; height:75px; float:left;" >' !!}
                                @endif
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
                            @php
                                if( isset($data['product_id']) && $data['product_id'] != 0 ) {
                                    echo @$data['delivery_pincode'];
                                    echo '<br>'.date('D, M d, Y', strtotime($data['delivery_date'])).'<br>';
                                    //echo $data['shippingmethod_name'];
                                }
                            @endphp
                        </td>
                        <td align="center">{{ $data['qty'] }}</td>
                        <td align="center">
                            @php
                                $total_cart_price = $total_cart_price + ( $data['unit_price'] * $data['qty'] );

                                $shipping_charges = $shipping_charges + $data['ship_price'];        

                                if( $main_order_data->order_currency != null ) {
                                    echo '<span style="font-family: verdana;">'.$main_order_data->order_currency->html_code.'</span>';
                                }
                                echo Currency::orderCurrency($data['total_price'], ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]);
                            @endphp
                        </td>
                    </tr>
                    <?php
                        }
                    ?>
                    <tr>
                        <td colspan="4" style="text-align: center"><strong>Items Total</strong></td>
                        <td align="center">
                            <?php
                                if( $main_order_data->order_currency != null ) {
                                    echo '<span style="font-family: DejaVu Sans; sans-serif;">'.$main_order_data->order_currency->html_code.'</span>';
                                }
                                    echo Currency::orderCurrency($total_cart_price, ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]);
                                    $final_price = $total_cart_price + $final_price;
                            ?>
                        </td>
                    </tr>
                    <?php
                        if( $shipping_charges != 0 ) {
                    ?>
                    <tr>
                        <td colspan="4" style="text-align: center"><strong>Shipping Charges</strong></td>
                        <td align="center">
                            <?php
                            if( $main_order_data->order_currency != null ) {
                                echo '<span style="font-family: DejaVu Sans; sans-serif;">'.$main_order_data->order_currency->html_code.'</span>';
                            }
                                echo Currency::orderCurrency($shipping_charges, ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]);
                                $final_price = $final_price + $shipping_charges;
                            ?>
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
                        <td colspan="4" style="text-align: center"><strong>Discount</strong></td>
                        <td align="center">
                            <?php
                                $final_price = $final_price - $discount_amount;
                                if( $main_order_data->order_currency != null ) {
                                    echo '<span style="font-family: DejaVu Sans; sans-serif;">'.$main_order_data->order_currency->html_code.'</span>';
                                }

                                echo '<span>'.Currency::orderCurrency($discount_amount, ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]).'</span>';
                            ?>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>

                    <tr>
                        <td colspan="4" style="text-align: center"><strong>Amount Paid</strong></td>
                        <td align="center">
                            <?php
                            if( $main_order_data->order_currency != null ) {
                                echo '<span style="font-family: DejaVu Sans; sans-serif;">'.$main_order_data->order_currency->html_code.'</span>';
                            }
                                $payable = $final_price;
                                echo Currency::orderCurrency($payable, ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]);
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
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
        <td align="center" style="text-align:center;">
            <table width="100%" border="0" cellspacing="2" cellpadding="0" style="text-align: center;">
                <tr>
                    <td><strong>Order Number : </strong>  {{$main_order_data->unique_order_id}}</td>
                </tr>
                <!-- <tr>
                    <td><strong>Order Date : </strong>  
                        @if( $main_order_data->purchase_date != '0000-00-00 00:00:00')
                            {{ date('d-m-Y' , strtotime($main_order_data->purchase_date)) }}
                        @endif
                    </td>
                </tr> -->
                <tr>
                    <td><strong>Recipient Name : </strong>  
                        <?php
                            if( $main_order_data->delivery_user_name != NULL ) {
                        ?>
                            {!! $main_order_data->delivery_user_name !!}
                        <?php
                        }else{
                            echo 'NA';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Delivery Address : </strong>  
                        <?php
                        if( $main_order_data->delivery_user_name != NULL ) {
                        ?>
                            {!! $main_order_data->delivery_address !!} 
                            <!-- {!! $main_order_data->delivery_country !!}, 
                            {!! $main_order_data->delivery_city !!}, 
                            {!! $main_order_data->delivery_state !!} - {!! $main_order_data->delivery_pincode !!}<br> -->
                        <?php
                        }else{
                            echo 'NA';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Recipient’s City :  </strong>  {!! $main_order_data->delivery_city !!}</td>
                </tr>
                <tr>
                    <td><strong>Recipient’s Zipcode :  </strong>  {!! $main_order_data->delivery_pincode !!}</td>
                </tr>
                <tr>
                    <td><strong>Recipient’s State :  </strong>  {!! $main_order_data->delivery_state !!} </td>
                </tr>
                <tr>
                    <td><strong>Recipient’s Country :  </strong>  {!! $main_order_data->delivery_country !!}</td>
                </tr>
                <tr>
                    <td><strong>Recipient’s Phone Number : </strong>  
                        <?php
                            if( $main_order_data->delivery_mobile != NULL || $main_order_data->delivery_mobile !='') {
                        ?>
                            {!! $main_order_data->delivery_mobile !!}
                        <?php
                        }else{
                            echo 'NA';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Recipient’s Email :  </strong>  {!! $main_order_data->delivery_email !!}</td>
                </tr>
                <tr>
                    <td><strong>Occasion :</strong>  {{ $main_order_data->order_message->message_purpose }}</td>
                </tr>
                <tr>
                    <td><strong>Message : </strong>  {{ $main_order_data->order_message->sender_message }}</td>
                </tr>
                <tr>
                    <td><strong>From : </strong>  {{ $main_order_data->order_message->sender_name }}</td>
                </tr>
                <tr>
                    <td><strong>Special Instructions : </strong>  {{ $main_order_data->order_message->sender_special_instruction }}</td>
                </tr>
                <tr>
                    <td style="color: #545454;">{{ $main_order_data->order_message->sender_demand }}</td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td style="text-align:center;">
            <p style="font-size:18px; text-align:center; line-height:20px; color:#444444; padding:30px 20px 0 20px; font-family:Calibri;">If you have any questions about your order, please <a href="{{url('/contact-us')}}">contact us</a></p>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td>
        <p style="font-size:18px; text-align:center; line-height:20px; color:#000; padding:30px 0px 0 0px; font-family:Calibri;">Best Regards,<br />
            {{ config('global.email_regards') }}
        </p>
        </td>
    </tr>
    
    {{-- <tr>
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
    </tr> --}}
    <tr>
        <td class="footcont" valign="middle" style="text-align:center;">
        <p style="font-size:18px; text-align:center; line-height:20px; color:#000; padding:30px 20px; font-family:Calibri;">This message was sent to you by {{ config('global.website_title_camel_case') }}.<br />
        If you didn’t create this account, contact {{ config('global.website_title_camel_case') }} <a href="{{url('/contact-us')}}"><i>support</i></a>.</p>
        </td>
    </tr>
    </table>
</body>
</html>