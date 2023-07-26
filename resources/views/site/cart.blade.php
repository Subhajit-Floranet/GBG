@php $currency = App\Http\Helper::get_currency(); @endphp

@extends('layouts.site.app', ['title' => 'Shopping Cart', 'meta_keyword' => '', 'meta_description' => ''])

@section('content')

<ul class="breadcrumb">
    <li><a href="{{ url('/') }}">Home</a></li>
    <li><a href="javascript:void(0)" class="tempting">Shopping Cart</a></li>
</ul>


<div class="container1">
    @if( isset($cart_data['item_dtl']) && $cart_data['total_item'] > 0 )
        

    <div class="cart100">
        <h1>Shoppping Cart</h1>

        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
                <div class="col-12">
                    <div class="text-center">
                        <h5 class="font-weight-light alert alert-{{ $msg }}">{!! Session::get('alert-' . $msg) !!}</h5>
                    </div>
                </div>
            @endif
        @endforeach

        <div class="cart200">
            <div class="card-group">
                @php
                    $total_shiping_val = 0;  
                    //dd($cart_data['item_dtl']); 
                @endphp
                @foreach( $cart_data['item_dtl'] as $data )
                    <div class="shopping-card-1">
                        <div class="img-cross">
                            <div class="card-delete"><a class="cart-delete" onclick="return confirm('Are you sure you want to remove the product?')" href="{{ route('remove-item', [base64_encode($data['order_detail_id'])]) }}">X</a></div>
                            <div class="shopping-card-img-1">
                                @if(isset($data['image']) && $data['image'] != null )
                                    @if(file_exists(public_path('/uploaded/product/'.$data['image'])))
                                        {!! '<img src="' . URL::to('/') . '/uploaded/product/' . $data['image'] . '" >' !!}
                                    @else
                                        {!! '<img src="' . URL::to('/').config('global.no_image') . '" >' !!}
                                    @endif
                                @else
                                    {!! '<img src="'.URL::to('/').config('global.no_image').'" >' !!}
                                @endif
                            </div>
                        </div>
                        <div class="img-details">
                            <div class="shopping-cart-title">
                                <p>
                                    {{ $data['product_name'] }}
                                    @php
                                    if( $data['attribute_name'] != '' ) {
                                        echo '<br><small>'.$data['attribute_name'].'</small>';
                                    }
                                    @endphp
                                </p>
                                <span>{{$data['delivery_city_name']}} / {!! date('D, M d', strtotime($data['delivery_date']))!!}</span>
                            </div>
                            <div class="shopping-count">
                                <input type="number" class="cart-qty cq-{{$data['order_detail_id']}}" value="{{$data['qty']}}" min="1" max="30" >
                                <span><a href="javascript:void(0);" data-id="{{$data['order_detail_id']}}" data-prod-id="{{base64_encode($data['product_id'])}}"  data-detail-id="{{base64_encode($data['order_detail_id'])}}" class="cart-modify" style="cursor:pointer"><i class="fa fa-refresh"></i></a></span>
                            </div>
                            <div class="card-price"><p>Merchandise Price:</p> {!! Currency::default($data['unit_price'], ['need_currency' => true, 'number_format' => config('global.number_format_limit')]) !!}</div>
                            <div class="ind-ship-fee"><p>Shipping Fee:</p>{!! Currency::default($data['ship_price'], ['need_currency' => true, 'number_format' => config('global.number_format_limit')]) !!}</div>
                        </div>
                    </div>
                    @php
                        $total_shiping_val = $total_shiping_val+$data['ship_price'];
                    @endphp
                @endforeach
            </div>
        </div>
        <div class="back-to-shop">
            <a href="{{ url('/') }}">Back to Shop <span>&#8594;</span></a>
        </div>   
    </div>
            
    <div class="summary">
        <div class="coupon-container">
            <div class="coupon-head">Have a Coupon Code?</div>
            @php
                $coupondata = App\Http\Helper::get_coupon_details( $cart_data['order_id'] );
            @endphp
            <form name="coupon_form" id="coupon_form" method="POST" action="{{ route('coupon') }}" >
                {{ csrf_field() }}
                <div class="apply-coupon">
                    <input type="hidden" name="orderid" id="orderid" value="{{ $cart_data['order_id'] }}" />
                    <input type="text" name="couponcode" id="couponcode" placeholder="Coupon Code" value="{{ @$coupondata->coupon_detail->coupon_code }}">
                    <button type="submit">Apply</button>
                </div>
            </form>
            <div class="coupon-error">* Coupon Code Required</div>
        </div>

        <form name="checkout_form" id="checkout_form" method="POST" action="{{ route('cart-checkout') }}" style="width: 100%">
            {{ csrf_field() }}
            <div class="summ-head"><h3>Summary</h3></div>
            
            <div class="item-count">
                <div class="items-number">Total Merchandise Price</div>
                <div class="items-price">
                    @php
                        $final_price = $cart_price = 0;
                        echo Currency::default($cart_data['total_cart_price'], ['need_currency' => true, 'number_format' => config('global.number_format_limit')]);
                        //$final_price = $cart_data['total_cart_price'];
                    @endphp
                </div>
            </div>

            <?php 
                $cart_price = $cart_data['total_cart_price'];
                $discount_amount = 0;
                if( $coupondata != null ) {
                    if( $coupondata->applied_for == 'WC' ) {     //If coupon related to whole Cart
                        if( $coupondata->coupon_detail->type == 'F' ) {
                            $discount_amount = number_format($coupondata->coupon_detail->amount, 2);
                        }else {
                            $discount_amount = number_format((( $cart_data['total_cart_price'] * $coupondata->coupon_detail->amount ) / 100), 2);
                        }
                    }
            ?>

                <div class="item-count">
                    <div class="items-number">Discount
                        <a onclick="return confirm('Are you sure you want to remove this coupon?')" 
                                href="{{ route('remove-applied-coupon', [base64_encode($coupondata->id), base64_encode($coupondata->order_id)]) }}" style="color:red;font-size:20px;font-weight:normal;">
                            <i class="fa fa-trash"></i>
                        </a>
                        <br><span class="cart-discount">
                        ( {{$coupondata->coupon_detail->coupon_code}} - 
                            @php
                                if( $coupondata->coupon_detail->type == 'F' ) {
                                    echo 'Flat '.Currency::default($coupondata->coupon_detail->amount, ['need_currency' => true, 'number_format' => config('global.number_format_limit') ]).' off';
                                }else{
                                    echo $coupondata->coupon_detail->amount.'% off';
                                }
                            @endphp )</span>
                    </div>
                    <div class="items-price">
                        {!! '- '.Currency::default($discount_amount, ['need_currency' => true, 'number_format' => config('global.number_format_limit') ]) !!}
                    </div>
                </div>

            <?php
                $cart_price = $cart_data['total_cart_price'] - $discount_amount;
                //echo '</a>';
                }
            ?>
            
            <div class="shipping">
                <div class="ship">Total Shipping Fee</div>
                <div class="shipping-charges">{!! Currency::default($total_shiping_val, ['need_currency' => true, 'number_format' => config('global.number_format_limit')]) !!}</div>
            </div>
            
            <div class="total-shopping">
                <div class="tooltip1">
                    <p>Order Total</p>
                    <small class="small-tax">[**Taxes Included]</small>
                </div>
                <div class="total-shopping-rupee">
                    <?php 
                        $grand_total = $total_shiping_val+$cart_price;
                        echo Currency::default($grand_total, ['need_currency' => true, 'number_format' => config('global.number_format_limit')]);
                    ?>
                </div>
            </div>

            <div class="checkout-btn">
                <input type="hidden" name="final_price" id="final_price" value="<?php echo $grand_total;?>" />
                <input type="hidden" name="order_id" id="order_id" value="<?php echo $cart_data['order_id'];?>" />
                {{-- <a href="member-details.asp" class="checkout">PLACE ORDER</a> --}}
                <button type="submit" class="checkout">PLACE ORDER</button>
            </div>
            <div class="checkout-btn1">
                <a href="{{ url('/') }}" class="checkout1">CONTINUE SHOPPING</a>
            </div>
        </form>
        </div>
    </div>

@else    

    <div class="cartempty">
        <h1>Shopping Cart</h1>
        <div class="blank text-center">
            <p class="p-2 h4 mt10">Unfortunately, Your Shopping Cart Is Empty</p>
            <p class="p-2 h5 mt10">Please Add Something In Your Cart</p>
            <div class="mt10"><a href="{{ url('/') }}" class="btn button-nfjp">Continue Shopping</a></div>
        </div>
    </div>
</div>
@endif    

<script type="text/javascript">
    $(function() {

        $(".cart-modify").click(function () {
            var order_detail_id = $(this).attr('data-detail-id');
            var product_id = $(this).attr('data-prod-id');
            var oid = $(this).attr('data-id');
            var qty = $('.cq-'+oid).val();
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route("update-item") }}',
                method: 'POST',
                data: {
                    qty: qty,
                    order_detail_id: order_detail_id,
                    product_id: product_id
                },
                success: function(data){
                    //console.log(data);
                    if(data.success){
                        location.reload();
                    }else if(data.errors){
                        alert("Sorry! Something wrong");
                    }
                }
            });            
        });

        $("#coupon_form").validate({
            rules: {
                couponcode: {
                    required: true
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

        $("#prodcurChange").change(function () {
        if(this.value != ''){
            $.ajax({
                type : "GET",
                url : "{{ route('set_currency') }}/?currency="+this.value,
                success : function(response){
                    console.log(response);
                    response = JSON.parse(response);
                    if(response.status == 'success'){
                        location.reload();
                    }
                },
                error : function(){
                }
            });
        }
    });
    });
</script>

@endsection