<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>{{ config('global.website_title') }}</title>
</head>
<body>
   <table width="505" border="0" cellspacing="0" cellpadding="0" align="center" style="border:1px solid #d9d8d8; font-family:Arial, Helvetica, sans-serif;color:#666;font-size:13px">
      <?php
         $total_cart_price = 0; $final_price = 0; $shipping_charges = 0; $payable = 0;
      ?>
      <tr>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td style="padding:5px">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td rowspan="3">
                     <img src="{{asset('images/site/logo.png')}}" style="width:200px">
                     <p style="margin:0;padding:0;font-size:11px">Gifts for all occasions</p>               </td>
                  <td>&nbsp;</td>
               </tr>
               {{-- <tr>
                  <td style="line-height:22px;text-align:right">Invoice Number : 123456ABCD1234</td>
               </tr>
               <tr>
                  <td style="line-height:22px;text-align:right">Txn. ID : 123456ABCD1234</td>
               </tr> --}}
            </table>      
         </td>
      </tr>
      <tr>
         <td style="height:3px; background-color:#ff6963"></td>
      </tr>
      <tr>
         <td>
            <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
               <tr>
                     <td style="width:50%"></td>
                     <td style="width:50%"></td>
               </tr>
               <tr>
                     <td height="22"><strong>Order ID :</strong> {{ $order_dtl->unique_order_id }}</td>
                     <td height="22"><strong>Payment Method :</strong> PayU</td>
               </tr>
               <tr>
                     <td height="22"><strong>Order Date :</strong> 
                        @if( $order_dtl->purchase_date != '0000-00-00 00:00:00')
                           {{ date('jS M, Y' , strtotime($order_dtl->purchase_date)) }}
                        @endif
                     </td>
                     <td height="22"><strong>Txn. ID :</strong> {{ $order_dtl->txn_id }}</td>
               </tr>
            </table>      
         </td>
      </tr>
      <tr>
         <td style="height:3px;background-color:#fdb586"></td>
      </tr>
      <tr>
         <td style="padding:5px 2px; background-color:#f0f0f0">
            
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  @if( $order_dtl->billing_user_name != NULL )
                  <td style="width:50%;vertical-align:top">
                     <table width="99%" border="0" cellspacing="0" cellpadding="0" style="line-height:22px" align="center">
                        <tr>
                           <td><strong>Billing Address :</strong></td>
                        </tr>
                        <tr>
                           <td><strong>{!! $order_dtl->billing_user_name !!}</strong></td>
                        </tr>
                        <tr>
                           <td>{!! $order_dtl->billing_address !!}, {!! $order_dtl->billing_city !!}, {!! $order_dtl->billing_state !!}, {!! $order_dtl->billing_country !!} - {!! $order_dtl->billing_pincode !!}</td>
                        </tr>
                        <tr>
                           <td><strong>Phone No :</strong> {!! $order_dtl->billing_mobile !!}</td>
                        </tr>
                     </table>               
                  </td>
                  @endif
                  @if( $order_dtl->delivery_user_name != NULL )
                  <td style="width:50%;vertical-align:top">
                     <table width="99%" border="0" cellspacing="0" cellpadding="0" style="line-height:22px" align="right">
                        <tr>
                           <td><strong>Shipping Address :</strong></td>
                        </tr>
                        <tr>
                           <td><strong>{!! $order_dtl->delivery_user_name !!}</strong></td>
                        </tr>
                        <tr>
                           <td>{!! $order_dtl->delivery_address !!}, {!! $order_dtl->delivery_city !!}, {!! $order_dtl->delivery_state !!}, {!! $order_dtl->delivery_country !!} - {!! $order_dtl->delivery_pincode !!}</td>
                        </tr>
                        <tr>
                           <td><strong>Phone No :</strong> {!! $order_dtl->delivery_mobile !!}</td>
                        </tr>
                     </table>               
                  </td>
                  @endif
               </tr>
            </table>     
         </td>
      </tr>
      <tr>
         <td style="height:3px;background-color:#fdb586"></td>
      </tr>
      <tr>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td style="padding:5px;" valign="top">
            <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center" style="border:1px solid #fff;border-collapse:collapse">
               <tr>
                  <td colspan="2" style="width:50%">
                     <table width="100%" border="1" cellspacing="0" cellpadding="0" align="center" style="border:1px solid #818181;border-collapse:collapse">
                        <tr style="background-color:#d9d8d8; height:25px">
                           <th scope="col">Items</th>
                           <th scope="col" style="width:50px">Qty.</th>
                           <th scope="col" style="width:100px">Delivery Dare </th>
                           <th scope="col" style="width:100px">Price</th>
                        </tr>
                        @foreach( $order_details as $data )
                        <tr>
                           <td style="padding:2px">{{ $data['product_name'] }}</td>
                           <td style="padding:2px; text-align:center">{{ $data['qty'] }}</td>
                           <td style="padding:2px; text-align:center">{!! date('jS M, Y', strtotime($data['delivery_date'])) !!}</td>
                           <td style="padding:2px; text-align:center">
                              USD  
                              <?php
                              echo Currency::orderCurrency($data['total_price'], $order_dtl->id, ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]);
                              
                              $total_cart_price = $total_cart_price + ( $data['unit_price'] * $data['qty'] );

                              $shipping_charges = $shipping_charges + $data['ship_price'];
                              ?>
                           </td>
                        </tr>
                        @endforeach
                     </table>               
                  </td>
               </tr>
               <tr>
                  <td style="width:35%">&nbsp;</td>
                  <td>
                     <table width="100%" border="1" cellspacing="0" cellpadding="0" align="center" style="border:1px solid #818181;border-collapse:collapse;padding:5px 0px; line-height:23px">
                        {{-- <tr>
                           <td style="padding:2px">Quantity :</td>
                           <td style="padding:2px; text-align:center">7 Items</td>
                        </tr> --}}
                        <tr>
                           <td style="padding:2px">Subtotal :</td>
                           <td style="padding:2px; text-align:center">USD
                              <?php
                                 // if( $order_dtl->order_currency != null ) {
                                 //    echo '<span style="font-family: DejaVu Sans; sans-serif;">'.$order_dtl->order_currency->html_code.'</span>';
                                 // }
                                    echo Currency::orderCurrency($total_cart_price, $order_dtl->id, ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]);

                                    $final_price = $total_cart_price + $final_price;
                              ?>
                           </td>
                        </tr>
                        <?php
                           if( $shipping_charges != 0 ) {
                        ?>
                        <tr>
                           <td style="padding:2px">Shipping Charge :</td>
                           <td style="padding:2px; text-align:center">USD
                              <?php
                              // if( $order_dtl->order_currency != null ) {
                              //     echo '<span style="font-family: DejaVu Sans; sans-serif;">'.$order_dtl->order_currency->html_code.'</span>';
                              // }
                                  echo Currency::orderCurrency($shipping_charges, $order_dtl->id, ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]);

                                  $final_price = $final_price + $shipping_charges;
                              ?>
                           </td>
                        </tr>
                        <?php
                           }
                        ?>   
                         <!-- Coupon Section Start -->
                         <?php
                         /*$coupondata = App\Http\Helper::get_coupon_details($order_dtl->id);
                         if( $coupondata != null ) {
                         */
                         if( $order_dtl->order_coupon_data != null ) {
                             $coupondata = $order_dtl->order_coupon_data;

                             $discount_amount = 0;
                             if( $coupondata != null ) {
                                 $discount_amount = $order_dtl->order_coupon_data->coupon_discount_amount;
                             }
                         ?>
                        <tr>
                           <td style="padding:2px">Discount :</td>
                           <td style="padding:2px; text-align:center">USD
                              <?php
                                 $final_price = $final_price - $discount_amount;

                                 // if( $order_dtl->order_currency != null ) {
                                 //    echo '<span style="font-family: DejaVu Sans; sans-serif;">'.$order_dtl->order_currency->html_code.'</span>';
                                 // }
                                 echo Currency::orderCurrency($discount_amount, $order_dtl->id, ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]);
                              ?>
                           </td>
                        </tr>
                        <?php
                           }
                        ?>
                        <!-- Coupon Section End -->
                        <tr>
                           <td style="padding:2px"><strong>Grand Total:</strong></td>
                           <td style="padding:2px; text-align:center"><strong>USD
                              <?php
                                 $payable = $final_price;

                                 // if( $order_dtl->order_currency != null ) {
                                 //    echo '<span style="font-family: DejaVu Sans; sans-serif;">'.$order_dtl->order_currency->html_code.'</span>';
                                 // }
                                 echo Currency::orderCurrency($payable, $order_dtl->id, ['need_currency' => false, 'number_format' => config('global.number_format_limit') ]);
                              ?>
                           </strong></td>
                        </tr>
                     </table>               </td>
               </tr>
            </table>      </td>
      </tr>
      <tr>
         <td style="padding:5px;text-align:right"><img src="{{asset('images/site/logo.png')}}" style="width:110px"><br />
            <span style="font-size: 14px">Thank You!</span><br> <span style="font-size: 11px">for shopping with us</span>      </td>
      </tr>
      <tr>
         <td style="height:3px; background-color:#ff6963"></td>
      </tr>
      <tr>
         <td style="padding:5px;font-size:11px">This is a computer generated invoice. No signature required.</td>
      </tr>
      <tr>
         <td style="padding:5px;font-size:13px">Any query, please contact at {{ config('global.website_url') }}/contact-us</td>
      </tr>
   </table>
</body>
</html>