@extends('layouts.site.app')

@section('content')

<ul class="breadcrumb">
    <li><a href="{{ url('/') }}">Home</a></li>
    <li><a href="javascript:void(0)">Order Status Details</a></li>
</ul>

<div class="inside-container">
    
    <div class="inside-txt">
        <?php
            //dd($cart_array);
            $total_cart_price = 0; $final_price = 0; $shipping_charges = 0; $payable = 0;
            if( count($cart_array) > 0 ) {
        ?>
        <section class="order-status-details">
            <p class="osd-head">Order ID: {{ $order_id }}</p>
            <?php if($order_dtl->order_delivery_status=='C'){?>
                <p><b>Order Completed</b></p>
            <?php } ?>
            
                <table border="1">
                    <tbody>
                        <tr>
                            <th scope="col" style="width: 100px;">Status</th>
                            <th scope="col" style="width: 100px;">Image</th>
                            <th scope="col" style="width: 270px;">Product</th>
                            <th scope="col" style="width: 130px;">Send To</th>
                            <th scope="col" style="width: 100px;">Price</th>
                            <th scope="col" style="width: 60px;">Qty</th>
                            <th scope="col" style="width: 100px;">Total</th>
                        </tr>
                        <?php
                            //dd($cart_array);
                            foreach( $cart_array as $data ) {
                        ?>
                        <tr>
                            <td><b><!-- <i class="pending">Pending</i> Order Placed -->
                                @if($data['order_delivery_status'] != null)
                                    @if($data['order_delivery_status'] == 'P')
                                        ORDER PLACED
                                        
                                    @elseif($data['order_delivery_status'] == 'A')
                                        ACCEPTED
                                    @elseif($data['order_delivery_status'] == 'D')
                                        DELIVERED
                                    @elseif($data['order_delivery_status'] == 'H')
                                        HOLD
                                        @if($data['hold_reason'] != null)
                                            <br><b>Cause:</b>{!! $data['hold_reason'] !!}</span>
                                        @endif
                                    @endif
                                @else
                                    ORDER PLACED
                                @endif
                                </b>
                            </td>
                            <td>
                                @if(isset($data['image']) && $data['image'] != null )
                                    @if(file_exists(public_path('/uploaded/product/'.$data['image'])))
                                        {!! '<img src="' . URL::to('/') . '/uploaded/product/' . $data['image'] . '" >' !!}
                                    @else
                                        {!! '<img src="' . URL::to('/').config('global.no_image') . '" >' !!}
                                    @endif
                                @else
                                    {!! '<img src="'.URL::to('/').config('global.no_image').'" >' !!}
                                @endif
                            </td>
                            <td>
                                <p class="o-ptitle">{{ $data['product_name'] }}</p>
                                                
                                    
                                    @php
                                    if( $data['attribute_name'] != '' ) {
                                        echo '<br /><small><a>('.$data['attribute_name'].')</a></small>';
                                    }
                                    echo '<br>';
                                    if( $data['product_extra_addon_name'] != null ) {
                                        echo '<small>';
                                        $h=1;
                                        foreach( $data['product_extra_addon_name'] as $key_extra_addon => $val_extra_addon ) {
                                            echo $val_extra_addon;
                                            if( $h < count($data['product_extra_addon_name']) ) {
                                                echo '<br />';
                                            }
                                            $h++;
                                        }
                                        echo '</small>';
                                    }
                                    @endphp
                            </td>
                            <td class="send-to">
                                <?php if( isset($data['product_id']) && $data['product_id'] != 0 ) { ?>
                                <p>{{$data['delivery_city']}}, {{$data['delivery_country']}}</p>
                                <p class="o-ptitle">{!! date('D, M jS Y', strtotime($data['delivery_date'])) !!}</p>
                                <?php } ?>
                            </td>
                            <td><p class="osd-price">
                                    <?php
                                        echo Currency::orderCurrency($data['unit_price'], $order_dtl->id, ['need_currency' => true, 'number_format' => 2]);
                                        
                                        $total_cart_price = $total_cart_price + ( $data['unit_price'] * $data['qty'] );

                                        $shipping_charges = $shipping_charges + $data['ship_price'];
                                    ?>               
                            </p></td>
                            <td><p>{{ $data['qty'] }}</p></td>
                            <td><p class="osd-price">
                                    @php
                                        echo Currency::orderCurrency($data['total_price'], $order_dtl->id, ['need_currency' => true, 'number_format' => 2]);
                                    @endphp               
                            </p></td>
                        </tr>

                        <?php 
                            if(count($addon_gift_array)>0){
                                foreach ($addon_gift_array as $key => $addon_value) {
                                    if($data['order_detail_id'] == $addon_value['order_details_id_giftaddon']){
                        ?>

                        <tr>
                            <td></td>
                            <td>
                                @if(isset($addon_value['image']) && $addon_value['image'] != null )
                                    @if(file_exists(public_path('/uploaded/product/'.$addon_value['image'])))
                                        {!! '<img src="' . URL::to('/') . '/uploaded/product/' . $addon_value['image'] . '" >' !!}
                                    @else
                                        {!! '<img src="' . URL::to('/').config('global.no_image') . '" >' !!}
                                    @endif
                                @else
                                    {!! '<img src="'.URL::to('/').config('global.no_image').'" >' !!}
                                @endif
                            </td>
                            <td>
                                <p class="o-ptitle">{{ $addon_value['product_name'] }}</p>
                            </td>
                            <td class="send-to">
                                <?php if( isset($addon_value['product_id']) && $addon_value['product_id'] != 0 ) { ?>
                                
                                <p class="o-ptitle">{!! date('D, M jS Y', strtotime($data['delivery_date'])) !!}</p>
                                <?php } ?>
                            </td>
                            <td><p class="osd-price">
                                    <?php
                                        echo Currency::orderCurrency($addon_value['unit_price'], $order_dtl->id, ['need_currency' => true, 'number_format' => 2]);
                                        
                                        $total_cart_price = $total_cart_price + ( $addon_value['unit_price'] * $addon_value['qty'] );

                                        $shipping_charges = $shipping_charges + $addon_value['ship_price'];
                                    ?>               
                            </p></td>
                            <td><p>{{ $addon_value['qty'] }}</p></td>
                            <td><p class="osd-price">
                                    @php
                                        echo Currency::orderCurrency($addon_value['total_price'], $order_dtl->id, ['need_currency' => true, 'number_format' => 2]);
                                    @endphp              
                            </p></td>
                        </tr>

                        <?php 
                                            }
                                        }
                                    }
                                    ?>
                        <?php } ?>
                    </tbody>
                </table>
        </section>

        <?php }else{
            echo 'No record found.';
        } ?>
    </div>
</div>



	

@endsection