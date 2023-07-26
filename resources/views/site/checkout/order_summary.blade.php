<?php
$total_shipping_price = 0;
$currency = App\Http\Helper::get_currency();
if( !empty( $ordersummary ) ) {
?>

<div class="review-currency">

    <select class="currency_rev noOutlineDropdown">
        @foreach($currency as $value)
            <option value="{{ $value->currency }}" <?php echo (Request::session()->get('currency')==$value->currency) ? 'selected' : '' ; ?>>{{ $value->currency }}</option>
        @endforeach
    </select>
</div>


<div class="shopping-cartn p-2 bg-light cart-review">
   <table border="1" bordercolor="#ddd" align="center">
	  	<tbody>
			<tr>
				<th scope="col">Image</th>
				<th scope="col">Product Name</th>
				<th scope="col">Quantity</th>
				<th scope="col">Unit Price</th>
				<th scope="col">Shipping</th>
			</tr>

		 	<?php
	        foreach ( $ordersummary['item_dtl'] as $data ) {
	            $total_shipping_price = $total_shipping_price + $data['ship_price'];
    		?>
		 	<tr>
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
				   <p class="ptitle">
				   	{{ $data['product_name'] }} <br>
	               @php
							if( $data['attribute_name'] != '' ) {
							   echo '('.$data['attribute_name'].')';
							}
	               @endphp
				   </p>
				</td>
				<td class="no-wrap">{{ $data['qty'] }}</td>
				<td>
					@php echo Currency::default($data['total_price'], ['need_currency'=>true, 'number_format'=>config('global.number_format_limit') ]); @endphp
				</td>
				<td>
					@php
	            if( $data['ship_price'] != 0 ) {
	                echo Currency::default($data['ship_price'], ['need_currency' => true, 'number_format' => config('global.number_format_limit') ]);
	            }
	            else {
	                echo "Free";
	            }
	            @endphp
				</td>
		 	</tr>
		 	<?php
		      }
		   ?>
	  	</tbody>
   </table>
</div>

<div class="shopping-cartn cart-calculate">
    <table>
    <tr>
        <td>&nbsp;</td>
        <td>Sub-Total:  </td>
        <td>
            @php
                $final_price = 0;

                $final_price = $ordersummary['total_cart_price'];
                
                echo Currency::default($ordersummary['total_cart_price'], ['need_currency' => true, 'number_format' => config('global.number_format_limit') ]);
            @endphp
        </td>
    </tr>
    <?php
        if( $total_shipping_price != 0 ) {
    ?>
    <tr>
        <td>&nbsp;</td>
        <td>Shipping :</td>
        <td>
            @php
                echo Currency::default($total_shipping_price, ['need_currency' => true, 'number_format' => config('global.number_format_limit') ]);
            @endphp
        </td>
    </tr>
    <?php
        }
    ?>
    <?php
    $discount_amount = 0;
    $coupondata = App\Http\Helper::get_coupon_details( $ordersummary['order_id'] );
    if( $coupondata != null ) {
    ?>
    <tr>
        <td>&nbsp;</td>
        <td>Discount:</td>
        <td>
            <?php
                if( $coupondata != null ) {
                    if( $coupondata->applied_for == 'WC' ) {   //If coupon related to whole Cart
                        if( $coupondata->coupon_detail->type == 'F' ) {
                            $discount_amount = number_format($coupondata->coupon_detail->amount, 2);;
                        }else {
                            $discount_amount = number_format((( $ordersummary['total_cart_price'] * $coupondata->coupon_detail->amount ) / 100), 2);
                        }
                    }
                    else{   //If coupon related to Occasion only
                        $total_related_products_price = 0;
                        $total_related_products_price = App\Http\Helper::get_selected_occasion_related_coupon_details($coupondata->coupon_id);
                        
                        if( $total_related_products_price > 0 ) {
                            if( $coupondata->coupon_detail->type == 'F' ) {
                                $discount_amount = $total_related_products_price - $coupondata->coupon_detail->amount;
                            }else {                                                
                                $discount_amount = ( $total_related_products_price * $coupondata->coupon_detail->amount ) / 100;
                            }
                        }
                    }
                    echo Currency::default($discount_amount, ['need_currency' => true, 'number_format' => config('global.number_format_limit') ]);

                    //Setting coupon discount amount in session
                    Session::put('coupon_discount_amount',$discount_amount);
                }
            ?>
        </td>
    </tr>
    <?php
        }
    ?>
    <tr>
        <td>&nbsp;</td>
        <td><strong>Grand Total:</strong></td>
        <td><strong>
            @php
                $final_price = $ordersummary['total_cart_price'] - $discount_amount + $total_shipping_price;

                echo Currency::default($final_price, ['need_currency' => true, 'number_format' => config('global.number_format_limit') ]);
            @endphp
        </strong></td>
    </tr>
    </table>
</div>

{{--   <hr>
<fieldset class="text-center">
	{!! Form::model(null, ['route'=>'site.order-placed', 'files'=>true, 'id'=>'orderPlaced', 'class'=>'login', 'method'=>'POST', 'novalidate'] ) !!}
	<input type="hidden" name="finalprice" name="finalprice" value="{{ base64_encode($final_price) }}">

	<div><label class="cscheckbox"><input name="checkout_agree" id="checkout_agree" type="checkbox" required><span class="checkmark"></span> I agree with the </label><a class="text-primary" href="{{ url('/terms-and-conditions')}}" target="_blank">Terms And Conditions</a></div>

	<div class="form-group button-container text-center">
		<button class="btn btns btns-primary">Make Payment</button>
	</div>
	{!! Form::close() !!}	
</fieldset> --}}

<?php
}
else{
?>
    <h3 class="fs-subtitle" style="color: red;">There are no products found in your cart.</h3>
    <a href="{{ url('/') }}" class="btns button-blue" >Continue Shopping</a>
<?php
}
?>

<script type="text/javascript">

    $('.currency_rev').on('change', function() {
        //alert( this.value );
        var currency_data = this.value;
        var mes_id     = $('#message_id').val();
            $('#order_summary_fieldset_loader').addClass('loading');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route("set_currency_order_summary") }}',
                type: 'POST',
                dataType: 'HTML',
                data: {
                    currency_data: currency_data
                },
                success: function(response_order_summary) {
                    if(response_order_summary){
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: '{{ route("checkout-step-order-summary") }}',
                            type: 'POST',
                            dataType: 'HTML',
                            data: {
                                message_id: mes_id
                            },
                            success: function(response_order_summary) {
                                setTimeout(function() {
                                    $('#order_summary').html(response_order_summary);
                                    $('#order_summary_fieldset_loader').removeClass('loading');
                                }, 10);
                            }
                        });
                    }
                }
            });
    });


    $(document).ready(function() {

        $("#orderPlaced").validate({
            rules: {                    
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
    });
	
	
    </script>