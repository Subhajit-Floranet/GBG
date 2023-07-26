@forelse( $order_list as $val )
	@php $my_order_id = isset($val->id)?$val->id:0; @endphp
	@php
		$delStatus = $delStatusClass = ''; 
		switch ($val->order_delivery_status) {
			case "P":
				$delStatusClass = 'pending';
				$delStatus = 'Pending';
				break;
			case "PC":
				$delStatusClass = 'delivered';
				$delStatus = 'Processed';
				break;
			case "S":
				$delStatusClass = 'delivered';
				$delStatus = 'Shipped';
				break;
			case "D":
				$delStatusClass = 'delivered';
				$delStatus = 'Delivered';
				break;
			case "H":
				$delStatusClass = 'pending';
				$delStatus = 'Hold';
				break;
			case "CL":
				$delStatusClass = 'pending';
				$delStatus = 'Cancelled';
				break;
			default:
				$delStatus = '';
		}	
	@endphp
<section class="my-order">
	<span class="row rows">
		<div class="my-order-pldate"><em>Order Placed :</em>{!! date('D, M jS Y', strtotime($val->purchase_date)) !!}</div>
		<div class="my-order-dwnld order-download"><em>Order No. : {!! $val->unique_order_id;!!}</em> 
			<a href="javascript:void(0)" class="{{ $delStatusClass }}">{{ $delStatus }}</a><!--  | 
			<a href="{{ route('users.generate-invoice',[App\Http\Helper::flower_encrypt_decrypt($val->id,'e')]) }}"><i class="material-icons-outlined">file_download</i></a> --></div>
	</span>
	<div class="d-flex my-order-display my-order-display-bg">
		<div class="itm-pic align-self-center">Image</div>
		<div class="itm-desc align-self-center">Description</div>
		<div class="itm-price align-self-center">Price</div>
		<div class="itm-price align-self-center">Delivery Charge</div>
		<div class="itm-price align-self-center">Delivery Date</div>
	</div>
	<?php
    $all_total_price = 0; $shipping_charge = 0; $coupon_discount_amount = 0;
    if( count($ordered[$val->id]) > 0 ) {
        $invoice_show = 1;
        //dd($ordered[$val->id]);
        foreach ($ordered[$val->id] as $key_dtl => $dtl) {  //Order Details loop
            $shipping_charge = $shipping_charge + $dtl['ship_price'];
            $all_total_price = $all_total_price + $dtl['total_price'];
    ?>
	<div class="d-flex my-order-display">
		<div class="itm-pic align-self-center">
			@if(isset($dtl['image']) && $dtl['image'] != null )
                @if(file_exists(public_path('/uploaded/product/'.$dtl['image'])))
                    {!! '<img src="' . URL::to('/') . '/uploaded/product/' . $dtl['image'] . '" >' !!}
                @else
                    {!! '<img src="' . URL::to('/').config('global.no_image') . '" >' !!}
                @endif
            @else
                {!! '<img src="'.URL::to('/').config('global.no_image').'" >' !!}
            @endif
		</div>
		<div class="itm-desc align-self-center"><p class="name">
			{!! $dtl['product_name'] !!}
                <br>
                @php
                if( $dtl['attribute_name'] != '' ) {
                    echo '<small>'.$dtl['attribute_name'].'</small>';
                }
                @endphp
		</p></div>
		<div class="itm-price align-self-center mo-pr"><i>@php echo Currency::orderCurrency($dtl['total_price'], $my_order_id, ['need_currency' => true, 'number_format' => config('global.number_format_limit') ]); @endphp</i></div>
		<div class="itm-price align-self-center mo-dc"><i>@php echo Currency::orderCurrency($dtl['ship_price'], $my_order_id, ['need_currency' => true, 'number_format' => config('global.number_format_limit') ]); @endphp</i></div>
		<div class="itm-price align-self-center mo-dd"><i>{!! date('jS M, Y', strtotime($dtl['delivery_date'])) !!}</i></div>
	</div>
	<?php
        $invoice_show++;
        }
    }
    ?>
	<span class="bottom row rows">
		<div class="col-md-9 align-self-center mo-address"><em class="single-row">Ship To : </em> {!! $val->delivery_user_name !!}
			<br>{!! $val->delivery_city !!}, {!! $val->delivery_country !!}</div>
		<div class="col-md-3 align-self-center mo-total">
			<?php
				$final_price = 0;
				if( isset($applied_coupon[$val->id]) && count($applied_coupon[$val->id]) > 0 ) {
					$coupon_discount_amount = $applied_coupon[$val->id]['coupon_discount_amount'];
				}
				$final_price = ($all_total_price + $shipping_charge) - $coupon_discount_amount;
            ?>
            <em class="single-row">
                @if($coupon_discount_amount > 0)
                Discount : {!! Currency::orderCurrency($coupon_discount_amount, $my_order_id, ['need_currency' => true, 'number_format' => config('global.number_format_limit') ]) !!}
                <br>
                @endif
                Order Total :  {!! Currency::orderCurrency($final_price, $my_order_id, ['need_currency' => true, 'number_format' => config('global.number_format_limit') ]) !!}
            </em>
		</div>
	</span>
</section>
@empty
    <p class="empty_list">You do not have any orders yet.</p>
@endforelse