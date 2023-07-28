@php
//dd($product_details);
$meta_data['keyword'] = strip_tags($product_details->meta_keyword);
$meta_data['description'] = $product_details->meta_description;
$meta_title = $product_details->meta_title;

//dd($request);
//echo Request::server('HTTP_REFERER');
//print_r(array_slice(explode('/', Request::server('HTTP_REFERER')), -1, 1));


@endphp
@php $currency = App\Http\Helper::get_currency(); @endphp
@php $meta = App\Http\Helper::get_meta($meta_data, $data); @endphp
@php $deliveryDelayDays = App\Http\Helper::getDeliveryDelayDays($product_details->delivery_delay_days); @endphp

@extends('layouts.site.app', ['title' => $meta_title, 'meta_keyword' => $meta['meta_keyword'], 'meta_description' => $meta['meta_description']])

@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>


<!----------------------------BREAD CRUMB STARTS FROM HERE------------------>
<ul class="breadcrumb">
    <li><a href="{{ url('/') }}">HOME</a></li>
    @php
        $lastCat = array_slice(explode('/', Request::server('HTTP_REFERER')), -1, 1);
        //dd(strpos($lastCat[0], '?query='));
    @endphp
    @if($lastCat[0] != '')
        @if(str_contains($lastCat[0], '?query='))

        @else
        <li><a href="{{ url('/'.$lastCat[0]) }}">{{ strtoupper(str_replace("-"," ",$lastCat[0])) }}</a></li>
        @endif
    @endif

    @foreach($breadcrumb as $key=>$breadcrumb_text)
        
        @php //echo $key; 
            //echo $breadcrumb[1];
            if($key == 0){
                $url_breadcrumb = '/'.$breadcrumb[0];
            }elseif($key == 1){
                $checkFUrl = App\Http\Helper::checkFalseUrl($breadcrumb[1]);
                if($checkFUrl == 1){
                    $url_breadcrumb = '/'.$breadcrumb[1];
                }else{
                    $url_breadcrumb = '/'.$breadcrumb[0].'/'.$breadcrumb[1];
                }
                //$url_breadcrumb = '/'.$breadcrumb[0].'/'.$breadcrumb[1];
            }
        @endphp

        @if(end($breadcrumb) == $breadcrumb_text)
            <li>{{ strtoupper(str_replace('-', ' ', $breadcrumb_text)) }}</li>
        @else
            <li><a href="{{ url('/').$url_breadcrumb }}">{{ strtoupper(str_replace('-', ' ', $breadcrumb_text)) }}</a></li>
        @endif
    @endforeach
</ul>
    <!-----------------------------------BREAD CRUMB ENDS HERE-------------->
<div class="product-details flex">
    <div class="product-image">
        
        @if( $product_details->default_product_image != null )
            @if(file_exists(public_path('/uploaded/product/'.$product_details->default_product_image['name'])))
            @php $default_image = URL::to('/') . '/uploaded/product/'.$product_details->default_product_image['name']; @endphp
                <img src="{{ $default_image }}" alt="{{ $product_details->alt_key }}" height="500" width="500">
            @else
                @php $default_image = URL::to('/').config('global.no_image');@endphp
                <img src="{{ $default_image }}" alt="{{ $product_details->alt_key }}" height="500" width="500">
            @endif
        @else
            @php $default_image = URL::to('/').config('global.no_image');@endphp
            <img src="{{ $default_image }}" alt="{{ $product_details->alt_key }}">
        @endif
       
    </div>
    <div class="product-description @if ( $product_details->has_attribute == 'Y' ) product-description-968 @endif" >
        {{-- <form name="delivery_details" id="delivery_details" method="POST">
        {{ csrf_field() }} --}}
        <div>
            <h1 class="p1">{{ $product_details->product_name }}</h1>
            @if(count($productRating) > 0)
                <?php
                    $totalRating = $avgRating = 0;
                    foreach($productRating as $rate){
                        $totalRating = $totalRating + $rate->rating;
                    }
                    $avgRating = number_format($totalRating / count($productRating), 1 );
                ?>
                <div class="rating-n-review">
                    @for($k=1; $k<=5; $k++)
                        @if( $avgRating >= $k)
                            <span class="gold">&#9733;</span>
                        @else
                            <span class="grey">&#9733;</span>
                        @endif    
                    @endfor
                    <a href="#customer-saying" class="product-reviews">
                    <span>{{ count($productRating) }}</span> Reviews </a>
                </div>
            @else
                <div class="your-review">
                    <p>No reviews added ? Be the first reviewer ? <a href="javascript:void(0)" id="add-review">Click Here</a>
                    </p>
                </div>
            @endif
            <div class="select-city-delivery">
                <div class="sel-city">
                    <select name="typed_city" id="typed_city" class="select-delivery-date noOutlineDropdown">
                        <option value=0>Choose City</option>
                        <?php
                        if(count($cityList) > 0){
                            foreach($cityList as $city){
                        ?>
                            <option value="{{ $city->id }}"  >{{ $city->name }}</option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                    <label id="delivery_city_error"></label>
                </div>
                <div class="sel-date">  
                    <input type="text" name='date' value="" id="datepicker" placeholder="Select Delivery Date" class="delivery-date plclass noOutlineDropdown" readonly>
                    <label id="delivery_date_error"></label>
                    <div class="delivery-asap">Earliest Delivery : <span>Wednesday, April 5, 2023</span></div>
                </div>
            </div>
            <div class="price-section">
                <div class="select-rupee ">
                    <select name="prodcurChange" id="prodcurChange" class="curChange noOutlineDropdown">
                        <?php foreach($currency as $value){ ?>
                            <option value="{{$value->currency}}" 
                            <?php echo (Request::session()->get('currency')==$value->currency) ? 'selected' : '' ; ?>  >
                            {{$value->currency}}</option>
                        <?php } ?>
                    </select>
                    <span class="main-price-section">
                        <div>{!! Currency::default($product_details->price, ['need_currency' => false, 'number_format' => config('global.number_format_limit')]) !!}</div>
                        @if($product_details->actual_price > $product_details->price)
                            <div class="stripe-text">{!! Currency::default($product_details->actual_price, ['need_currency' => false, 'number_format' => config('global.number_format_limit')]) !!}</div>
                        @endif
                    </span>

                    <?php foreach($currency as $cur){ ?>
                        <span class="{{ strtolower($cur->symbol) }}-price-section currencyClass" id="price-tag-usd" style="display:none;">
                            <div>{!! Currency::default($product_details->price, ['need_currency' => false, 'number_format' => config('global.number_format_limit'), 'currency' => $cur->symbol]) !!}</div>
            
                            @if($product_details->actual_price > $product_details->price)
                                <div class="stripe-text">{!! Currency::default($product_details->actual_price, ['need_currency' => false, 'number_format' => config('global.number_format_limit')]) !!}</div>
                            @endif
                        </span>
                    <?php } ?>
                </div>
                    @if($product_details->actual_price > 0 && ($product_details->actual_price > $product_details->price))
                        @php $discount = (($product_details->actual_price - $product_details->price)/$product_details->actual_price) * 100; @endphp
                        @if(number_format($discount, 2) > 0)
                            <div class="offer">{{number_format($discount, 1)}}% Off</div>
                        @endif
                    @endif
                <div class="quantity">
                    <label for="">QTY:</label>
                    <input type="number" id="quantity" name="quantity" min="1" value="1" onkeypress="return !(event.charCode == 46)" oninput="this.value=(parseInt(this.value)||1)" step="1" class="form-control Qty rk-qty" placeholder="1" max="30" >
                </div>

                
            </div>   
            <div class="price-n-qty @if ( $product_details->has_attribute == 'N' ) no-display @endif" >
                    @php $product_attr_id = 0; @endphp
                    @if ( $product_details->has_attribute == 'Y' )
                        <div>
                        @php
                            if ( $product_details->product_attribute != null ){
                                $d=1;
                                foreach ( $product_details->product_attribute as $attribute ) {
                        @endphp
                            <div class="radio">
                                <label class="option-container">
                                    <input type="radio" name="optradio" class="pro_attr" data-attrid="{{ $attribute->id }}" <?php if($d==1)echo 'checked="checked"'; ?> autocomplete="off">
                                    <span class="checkmark"></span>
                                    <div class="option-qty-price flex">
                                        <span class="option-qty">
                                            {{ $attribute->title }} 
                                        </span>
                                        <span class="option-price">
                                            {!! Currency::default($attribute->price, ['need_currency' => true, 'number_format' => config('global.number_format_limit') ]) !!} 
                                            
                                            @if($attribute->actual_price > $attribute->price)
                                                <span class="stripe-text">{!! Currency::default($attribute->actual_price, ['need_currency' => false, 'number_format' => config('global.number_format_limit')]) !!}</span>
                                            @endif
                                            <!-- <div class="prd-det-off">
                                                <p>14.0% Off</p>
                                            </div> -->
                                        </span>
                                    </div>
                                </label>
                                <?php if($d==1){ 
                                    $product_attr_id = $attribute->id;
                                } ?>
                            </div>
                        @php
                        $has_options = true;
                                $d++;
                                }
                            }
                        @endphp
                        </div>
                    @endif
                </div> 
            <div style="     padding: 1% 0;">
                <button type="submit" class="pro-addCart product_add_to_cart">ADD TO CART</button>                    
                <div id="addtocart_div"></div>
                <div class="row alert_add_to_cart"></div>
            </div>                        
        
            <input type="hidden" name="product_id" id="product_id" value="{{ base64_encode($product_details->id) }}">
            <input type="hidden" id="delCityID">
            <input type="hidden" id="delDelayDays" value="{{$deliveryDelayDays}}">
            <input type="hidden" id="delDate">
            <input type="hidden" id="delCharge" <?php if($product_details->fnid == 'FN') { ?> value="0" <?php } else { ?> value="9.9" <?php } ?>>
            <input type="hidden" id="checkSameday" value="{{$product_details->delivery_delay_days}}">
            <input type="hidden" id="citywisedelDelayDays" value="0">
            <input type="hidden" name="product_attr_id" id="product_attr_id" autocomplete="off" value="{!! $product_attr_id !!}">
      <!----------------------------TAB CONTENT STARTS FROM HERE--------->
      <div class="">
        <div class="tabs">
            @if ( $product_details->description != '' )	  
                <p class="active delivery my-tab-1">Description</p>
            @endif
            @if ( $product_details->content != '' )
                <p class="delivery-info my-tab-2">Delivery Info</p>
            @endif    
        </div>
        <div class="tab-content">
            @if ( $product_details->description != '' )	
                <div class="active">
                    {!! $product_details->description !!}
                </div>
            @endif
            @if ( $product_details->content != '' )
                <div>
                    {!! $product_details->content !!}
                </div>
            @endif
        </div>
      </div>
      <!--------------------TAB CONTENT ENDS HERE--------------------->
    </div>
    {{-- </form> --}}
    </div>
</div>
  
<div class="bottom-banner" id="customer-saying">
    <div class="bottom-banner-1">
        <i class="fa-solid fa-shield-halved"></i>
        <p>Safe And Secure</p>
    </div>
    <div class="bottom-banner-1">
        <i class="fa-solid fa-phone-volume"></i>
        <p>24x7 Support</p>
    </div>
    <div class="bottom-banner-1">
        <i class="fa-regular fa-circle-check"></i>
        <p> 100%  Quality  </p>
    </div>
    <div class="bottom-banner-1">
        <i class="fa-solid fa-truck"></i>
        <p>Express Delivery</p>
    </div>
</div>

<!---------------------CUSTOMER- REVIEW----------->
@if(count($productRating) > 0)
<div class="customer-review-slider-heading" >
    What Customers Are Saying
    <p class="your-review2" id="add-review" >Add a Review</p>
</div>

<div class="owl-carousel owl-theme customer-review-slider">
    @foreach( $productRating as $rate )
        <div class="item">
            <div class="rating1">
                @for($k=1; $k<=5; $k++)
                    @if( $rate->rating >= $k)
                        <span class="gold">&#9733;</span>
                    @else
                        <span class="grey">&#9733;</span>
                    @endif    
                @endfor
            </div>
            <div class="owner-details12">
                {{ $rate->user_name }} From {{ $rate->sender_place }} On  {!! date('jS M, Y', strtotime($rate->created_at))!!}
            </div>
            <div class="customer-saying-content">
                {{ $rate->review }}
            </div>
        </div>
    @endforeach
</div>
@endif    
<!---------------------CUSTOMER- REVIEW----------->
@php
    //echo $lastCat[0];
    $similar_product_list = App\Http\Helper::getSimilarProducts($lastCat[0], $product_details->id, 10);
@endphp
@if( count($similar_product_list['product']) > 0 )
<div class="customer-review-slider-heading">Customers Also Viewed</div>
<div class="owl-carousel owl-theme related-product">
    @foreach( $similar_product_list['product'] as $product )
        @php
            $reviewRating = App\Http\Helper::getProductReviewNRating($product->id);
        @endphp
        <div class="item">
            <?php
                if($product->has_attribute == 'Y'){
                    $prodPrice = App\Http\Helper::get_group_price( $product->id );
                    $mainPrice = $prodPrice->price;
                    $mrp = $prodPrice->actual_price;
                }else{
                    $mainPrice = $product->price;
                    $mrp = $product->actual_price;
                }
                //echo $mainPrice;
            ?>

            @if($mrp > 0 && ($mrp > $mainPrice))
                @php $discount = (($mrp - $mainPrice)/$mrp) * 100; @endphp
                @if(number_format($discount, 2) > 0)
                    <div class="product-discount">
                        <p>{{number_format($discount, 1)}}% Off</p>
                    </div>
                @endif
            @endif
            <a href="{{ url('/'.$product->slug) }}">
                @if( $product->default_product_image != null )
                    @if(file_exists(public_path('/uploaded/product/'.$product->default_product_image['name'])))
                        <img fetchpriority='High' src="{{ asset('uploaded/product/'.$product->default_product_image['name'])}}" srcset="{{ asset('uploaded/product/thumb/'.$product->default_product_image['thumb'])}} 768w, {{ asset('uploaded/product/'.$product->default_product_image['name'])}} 1200w, {{ asset('uploaded/product/'.$product->default_product_image['name'])}} 1500w, {{ asset('uploaded/product/'.$product->default_product_image['name'])}} 1900w" imagesizes="" height="500" width="500" alt="{{$product->alt_key}}" loading="lazy">
                    @else
                        {!! '<img class="img-fluid" src="' . URL::to('/').config('global.no_image'). '" alt="'. $product->alt_key .'" title="'. $product->alt_key .'" loading="lazy" >' !!}
                    @endif
                @else
                    {!! '<img class="img-fluid" src="' . URL::to('/').config('global.no_image'). '" alt="'. $product->alt_key .'" title="'. $product->alt_key .'"  loading="lazy" >' !!}
                @endif
            </a>
            <div class="item-desc1"id="item-title-1">
                <p>
                    @if(strlen($product->product_name) > 50)
                        {{ substr($product->product_name, 0, 48) }}..
                    @else
                        {{ $product->product_name }}
                    @endif
                </p>
            </div>

            @if($reviewRating['rating'] > 0 )
                <div class="ratings">
                    <div class="rating-yellow">
                        @php
                            $avgRating = round($reviewRating['rating']/$reviewRating['review'], 0);
                        @endphp
                        @for($k=1; $k<=5; $k++)
                            @if( $avgRating >= $k)
                                <span class="gold">&#9733;</span>
                            @else
                                <span class="grey">&#9733;</span>
                            @endif
                        @endfor
                    </div>
                </div>
            @endif

            @php
                $earliestDelivery = App\Http\Helper::get_earliest_delivery_date($product->delivery_delay_days, $product->id);
            @endphp
            <div class="msz-del @if($reviewRating['rating'] > 0 ) rating-padding @endif">Earlist Delivery: {{  $earliestDelivery }}</div>
            <div class="btn-price flex">
                <div class="prd-price-box flex">
                    <div class="price-tag">
                        
                        <div class="home-price flex main-price-section"> 
                            @if($mrp > $mainPrice)
                                <div class="price-tag old-price">{!! Currency::default($mrp, ['need_currency' => false, 'number_format' => config('global.number_format_limit')]) !!}</div>
                            @endif
                            <div class="price-tag">
                                {!! Currency::default($product->price, ['need_currency' => false, 'number_format' => config('global.number_format_limit')]) !!}
                            </div>
                        </div>
        
                        {{-- FOR CHANGE CURRENCY --}}
        
                        <?php foreach($currency as $cur){ ?>
        
                            <div class="home-price flex {{ strtolower($cur->symbol) }}-price-section" style="display:none;"> 
                                @if($mrp > $mainPrice)
                                    <div class="price-tag old-price">{!! Currency::default($mrp, ['need_currency' => false, 'number_format' => config('global.number_format_limit'), 'currency' => $cur->symbol]) !!}</div>
                                @endif
                                <div class="price-tag">
                                    {!! Currency::default($mainPrice, ['need_currency' => false, 'number_format' => config('global.number_format_limit'), 'currency' => $cur->symbol]) !!}
                                </div>
                            </div>
                        <?php } ?>
        
                        {{-- ###FOR CHANGE CURRENCY#### --}}

                    </div>
                </div>
                <div class="price-select select ">
                    <select name="curChange" id="curChange{{ $product->id }}" class="curChange noOutlineDropdown">
                        <?php foreach($currency as $value){ ?>
                            <option value="{{$value->currency}}" 
                            <?php echo (Request::session()->get('currency')==$value->currency) ? 'selected' : '' ; ?>  >
                            {{$value->currency}}</option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="btn-section">
            <a href="{{ url('/'.$product->slug) }}">ADD TO CART</a>
            </div>
        </div>
    @endforeach
</div>
@endif

<!-----------------------------POPUP STARTS FROM HERE------------->

<div class="add-review" id="rating_addon">
    <div class="modal-content">
        <div class="modal-heading">
            <h1>Submit Your Review</h1>
            <i class="fa-solid fa-xmark" href="javascript: void(0);" id="rating_popup_close"></i>
            <!--onClick="remove_modal()"-->
        </div>
        <div class="modal-body flex">
            <!-- <div class="add-review flex"> -->
                <div class="add-review-main">
                    <p class="review-success"></p>
                    <form class="contact-form">
                        <input type="hidden" id="rvpid" value="{{ base64_encode($product_details->id) }}" />
                        <input type="text" placeholder="Customer Name*" id="rvname"  required />
                        <label class="rtname review-error"></label>
                        <div class="add-rating flex">
                            <p>Rate this product</p>
                            <div class="star-rating">
                                <input type="radio" id="5-stars" name="rating" value="5" />
                                <label for="5-stars" class="star">&#9733;</label>
                                <input type="radio" id="4-stars" name="rating" value="4" />
                                <label for="4-stars" class="star">&#9733;</label>
                                <input type="radio" id="3-stars" name="rating" value="3" />
                                <label for="3-stars" class="star">&#9733;</label>
                                <input type="radio" id="2-stars" name="rating" value="2" />
                                <label for="2-stars" class="star">&#9733;</label>
                                <input type="radio" id="1-star" name="rating" value="1" />
                                <label for="1-star" class="star">&#9733;</label>
                            </div>
                        </div>
                        <span class="rtstar review-error"></span>
                        <textarea placeholder="Review*" id="rvtext" rows="5"></textarea>
                        <label class="rtarea review-error"></label>
                        <input type="text" placeholder="Delivery Location*" id="rvdel_location" required />
                        <input type="text" placeholder="From*" id="rvsender_location" required />
                        <input type="text" placeholder="Email*" id="rvemail" required />
                        <label class="rtemail review-error"></label>
                        
                        <button type="submit" id="review_prod">Submit</button>
                    </form>
                </div>
        
        </div>
    </div>
</div>

<!----------------------------POPUP ENDS HERE-------->

<div class="modal-addon" id="gift_addon">
    <div class="modal-content">
    	<div class="modal-heading">
    		<h1>Add on something to make it extra special!</h1>
    		<!-- <i class="fa-solid fa-xmark" href="javascript: void(0);" id="gift_popup_close"></i> -->
    		<!--onClick="remove_modal()"-->
    	</div>
    	<div class="modal-body ">
    		<input type="hidden" name="giftaddon_ids" id="giftaddon_ids" value="">
    		@if ( count($all_extra_addons) > 0 )
                @foreach ( $all_extra_addons as $product_extra )
               
    				<div class="modal-product">
                        
    					<div class="md-up">
    						@if(isset($product_extra->default_product_image) && $product_extra->default_product_image != null )
                                @if(file_exists(public_path('/uploaded/product/'.$product_extra->default_product_image['name'])))
                                    {!! '<img src="' . URL::to('/') . '/uploaded/product/' . $product_extra->default_product_image['name'] . '" alt="'. $product_extra->alt_key .'" >' !!}
                                @else
                                    {!! '<img src="' . URL::to('/').config('global.no_image'). '" alt="'. $product_extra->alt_key .'" >' !!}
                                @endif
                            @else
                                {!! '<img src="' . URL::to('/').config('global.no_image'). '" alt="'. $product_extra->alt_key .'" >' !!}
                            @endif
    						<p>{{ $product_extra->product_name }}</p>
    						<div class="md-price">
    							<h3>{!! Currency::default($product_extra->price, ['need_currency' => true, 'number_format' => config('global.number_format_limit')]) !!}</h3>
    						</div>
    					</div>

    					<div class="md-down">
    						<!-- <input type="checkbox" name="modal-check" value="modal-check">	 -->
    						<input type="checkbox" class="giftaddon" value="{{ $product_extra->id }}" autocomplete="off">	
    					</div>
    				</div>
                
    			@endforeach
    		@endif	
    	</div>
    	<div class="modal-footer flex" id="continue_div">
    		<button type="button" class="btns button-grey" id="gift_addon_not_now">No, Thanks</button>
        	<button type="button" class="btns button-blue" id="gift_addon_continue">Continue with <span id="count-checked-checkboxes">0</span> Addon</button>
    	</div>
    </div>
</div>


<script>
    $(document).ready(function(){

        let delayDays = $('#delDelayDays').val();

        function calcTime(city, offset) {
            var d = new Date();
            var utc = d.getTime() + (d.getTimezoneOffset() * 60000);
            var nd = new Date(utc + (3600000*offset));
            return nd;
        }

        function addDays(date, days) {
            var result = new Date(date);
            //console.log(days)
            //result.setDate(result.getDate() + days);
            result.setTime(result.getTime() +  (days * 24 * 60 * 60 * 1000));
            //console.log(result);
            return result;
        }

        var dates = ["24/12/2022","25/12/2022","01/01/2023","08/01/2023","15/01/2023"];

        function DisableDates(date){
            var str = jQuery.datepicker.formatDate('dd/mm/yy', date);
            return [dates.indexOf(str) == -1];
        }

        $('.delivery-date').datepicker({
            dateFormat: 'DD, d MM,yy',
            beforeShowDay: function(date) { //DISABELED SUNDAY
                    var day = date.getDay();
                    return [(day != 0), ''];
                },
            //beforeShowDay: DisableDates,
            //startDate: "+3d",
            //minDate: new Date(),
            //minDate: "3",
            //displayTimezone: 'Asia/Tokyo',
            //minDate: $('#delDelayDays').val(),
            minDate: addDays(calcTime('germany', '+2'), delayDays),
            //minDate: addDays(japanTime, delayDays),
            autoclose: true,
            onSelect: function(dateText, inst) {
                    var date = formatDate($(this).val());
                    $("#delDate").val(btoa(date));
                }
        });


        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) 
                month = '0' + month;
            if (day.length < 2) 
                day = '0' + day;

            return [year, month, day].join('-');
        }

    })


    $('#add-review').on('click', function() {
        $("#rating_addon").fadeToggle("fast","linear");
        $("body").css('overflow','hidden');
    });

    $('#rating_popup_close').on('click', function() {
        $("#rating_addon").fadeToggle("fast","linear");
        $("body").css('overflow','unset');
    })

    $('#rating_addon').click(function (event) 
        {
            if(!$(event.target).closest('.modal-content').length && !$(event.target).is('.modal-content')) {
                $('#rating_addon').fadeToggle("fast","linear");
                $('body').css('overflow','unset');  
            }     
        });   

    $('#review_prod').on('click', function(e){
        e.preventDefault();
        var pid = $('#rvpid').val();
        var rtext = $('#rvtext').val();
        var rname = $('#rvname').val();
        var rvdel_location = $('#rvdel_location').val();
        var rvsender = $('#rvsender_location').val();
        var rvemail = $('#rvemail').val();
        var valuerate = $('input[type=radio][name="rating"]:checked').val();

        //alert(valuerate);

        if(rname.length < 1){
            $('.rtname').html('*Required');
        }else if(rtext.length < 1){
            $('.rtarea').html('*Required');
        }else if(!valuerate){
            $('.rtstar').html('*Please select the ratings above.');
        }else if(rvemail.length < 1){
            $('.rtemail').html('*Required');
        }else{
            $('.contact-form').addClass('loading');
            $('.rtstar').html('');
            $('.rtname').html('');
            $('.rtarea').html('');
            $('.rtemail').html('');
            $.ajax({
                type : "POST",
                url : "{{ route('reviewpost') }}",
                data : {
                    product_id : pid,
                    user_name : rname,
                    rating : valuerate,
                    review : rtext,
                    delplace : rvdel_location,
                    fromplace : rvsender,
                    email : rvemail
                    },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success : function(response){
                    console.log(response);
                    //response = JSON.parse(response);
                    if(response.status == 'success'){
                        $('.contact-form').removeClass('loading');
                        $('.contact-form')[0].reset();
                        $('.review-success').addClass('rvsc');
                        $('.review-success').html('Thank you for your reviews.');
                        setTimeout(function(){
                            $('.review-success').removeClass('rvsc');
                            // $('.review-success').html('Thank you for your reviews.');
                            $('.review-success').hide();
                            $("#rating_addon").attr("style", "display:none");
                            $("body").removeClass("modal-open");
                            location.reload();
                        }, 3000);
                    }
                },
                error : function(){
                }
            });
        }

    })


    $('#gift_popup_close').on('click', function() {
        $("#gift_addon").attr("style", "display:none")
    })

    $('#gift_addon_not_now').on('click', function() {
        //alert("hel");
        $("#gift_addon").attr("style", "display:none")
        //$('.modal-backdrop.fade.in').remove();
        //location.reload();
        window.location.href = '{{ route("cart") }}';
    });

    var gift_addon_array = [];
    function existanceChecker(gift_addon_id) {
        var array_length = gift_addon_array.length;
        if( array_length > 0 ) {
            for ( var k = 0; k < array_length; k++ ) {
                if ( gift_addon_array[k] == gift_addon_id ) {
                    return true;
                }
            }
        }
        gift_addon_array.push(gift_addon_id);
    }

    $('.giftaddon').each(function() {
        var gift_addon_id = $(this).val();
        $(this).change(function() {
            localStorage.setItem(gift_addon_id, $(this).is(':checked'));
            if ($(this).is(':checked')) {
                existanceChecker(gift_addon_id);
            }
            else {
                var cboxValue = gift_addon_array.indexOf(gift_addon_id);
                if (cboxValue >= 0) {
                    gift_addon_array.splice( cboxValue, 1 );
                }
            }
            //console.log(extra_addon_array);
            //console.log(gift_addon_array.length);
            $('#count-checked-checkboxes').html(gift_addon_array.length);
            $('#giftaddon_ids').val(gift_addon_array);
        });
    });

    $("#gift_addon_continue").on('click', function() {
        var giftaddon_ids   = $('#giftaddon_ids').val();
        var clicked_button  = $('#clicked_button').val();

        if( giftaddon_ids == '' ) {
            window.location.href = '{{ route("cart") }}';
        }else{
            $('#continue_div').addClass('loading'); //For Gift Addon Continue button
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route("gift-addon-add-to-cart") }}',
                method: 'POST',
                data: {
                    giftaddon_ids: giftaddon_ids
                },
                success: function(data) {
                if( data.success ) {
                    $('#continue_div').removeClass('loading'); //For Gift Addon Continue button
                    //$('#cart_count').html(data.header_cart_count);
                    $("#gift_addon").attr("style", "display:none");
                    $("body").removeClass("modal-open");
                    setTimeout(function(){
                        window.location.href = '{{ route("cart") }}';
                    }, 1000);
                }
                else if( data.error ) {
                    $('#giftaddon_msg').show();
                    $('#giftaddon_msg').html('<h5 class="font-weight-light alert alert-danger">'+data.error+'</h5>');
                    setTimeout(function(){
                        $('#giftaddon_msg').hide(500);
                        $('#giftaddon_msg').html('');
                    }, 3000);
                }
                }
            });
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

    $('#typed_city').on('change', function(){
        var city_id = $.trim($(this).val());

        if(city_id != 0){
            $('#delivery_city_error').html('');
            $('#delCityID').val(city_id);
        }else{
            $('#delivery_city_error').html('<label class="error mt-2 "><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;Please choose delivery city.</label>');
            $('#delCityID').val('');
        }
    });

    if($('.pro_attr').length){
        $('.pro_attr:checked').click();
    }

    $('.pro_attr').on('click', function() {
        //$('#price_tab').addClass('loading');
        var attr_id = $(this).data("attrid");
        //alert(attr_id);
        if ( attr_id != '' ) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{route("attribute-details")}}',
                method: 'POST',
                data: {
                    attr_id: attr_id
                },
                success: function(data){
                    //alert(data);
                    if ( data != '' ) {
                        $('#product_attr_id').val(data.id);
                    }else{
                        $('#attribute-error').html('An error occurred during processing. Please try again.');
                    }
                }
            });
        }
    });

    $(".product_add_to_cart").on('click', function() {
        //alert($('.delivery-date').val());

        product_id            = $('#product_id').val();
        quantity              = $('#quantity').val();
        product_delivery_date = atob($('#delDate').val());
        delivery_city_id      = $('#delCityID').val();
        delivery_country_id   = 1;
        ship_price            = $('#delCharge').val();
        product_attr_id       = 0;
        checkSameday          = $('#checkSameday').val();
        product_attr_id       = $('#product_attr_id').val();

        //alert("hello");

        if(delivery_city_id == '') {
            $('#delivery_city_error').html('<label class="error mt-2 "><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;Please choose delivery city.</label>');
            return false;
        }else{
            $('#delivery_city_error').html('');
        }

        if(product_delivery_date == '') {
            $('#delivery_date_error').html('<label class="error mt-2 "><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;Please select delivery date.</label>');
            return false;
        }else{
            $('#delivery_date_error').html('');
        }

        <?php 
        $delivery_city_id = Session::get('Delivery.delivery_city_id');
        ?>

        <?php if($delivery_city_id<>''){ ?>

        if(delivery_city_id != <?php echo $delivery_city_id; ?>){
            if(confirm('You have products for another City in the cart already, please finish that transaction before adding products for a different City. If you choose "OK" your existing cart will be deleted.')){
                //return true;
            }else{
                return false;
            }
        }

        <?php }?>

        

        $('#addtocart_div').addClass('loading');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ route("add-to-cart") }}',
            method: 'POST',
            data: {
                product_id: product_id,
                quantity: quantity,
                product_delivery_date: product_delivery_date,
                ship_price: ship_price,
                delivery_city_id: delivery_city_id,
                delivery_country_id: delivery_country_id,
                product_attr_id: product_attr_id
            },
            success: function(data) {
                $('#addtocart_div').removeClass('loading'); //For Add to Cart button

                console.log(data);
                
                if( data.success ) {
                    //$('#delivery_details')[0].reset();
                    $('.alert_add_to_cart').show();
                    $('.alert_add_to_cart').html('<h5 class="font-weight-light alert alert-success">'+data.success+'</h5>');
                    setTimeout(function(){
                        $('.alert_add_to_cart').hide(500);
                        $('.alert_add_to_cart').html('');
                        //location.href = '{{ route("cart") }}';

                        if(checkSameday == 0){
                            location.href = '{{ route("cart") }}';
                        }else{

                            <?php
                            //If product extra addon found for popup
                            //if( $product_details->addon_group_id > 0 ){
                            if(count($all_extra_addons)>0){
                            ?>
                                //Extra Addon section
                                //$('#gift_addon').modal({backdrop: 'static', keyboard: false});
                                $("#gift_addon").attr("style", "display:block");
                                $("body").addClass("modal-open");
                                //Extra Addon section
                            <?php
                            }else{
                            ?>
                                location.href = '{{ route("cart") }}';
                            <?php
                            }
                            ?>
                        }

                    }, 1000);
                }
                else if(data.error){
                    $('.alert_add_to_cart').show();
                    $('.alert_add_to_cart').html('<h5 class="font-weight-light alert alert-danger">'+data.error+'</h5>');
                    setTimeout(function(){
                        $('.alert_add_to_cart').hide(500);
                        $('.alert_add_to_cart').html('');
                    }, 3000);
                }
                $('#cart_count').html(data.header_cart_count);
            }
        });
        

        


    });


$(document).ready(function() {
    $('.currencyClass').hide();
});

$('.curChange').on('change', function() {
    //alert(this.value);
    if(this.value != ''){
        $.ajax({
            type : "GET",
            url : "{{ route('set_currency') }}/?currency="+this.value,
            success : function(response){
                //console.log(response);
                response = JSON.parse(response);
                if(response.status == 'success'){
                    $('#active_currency').val(response.currency);

                    var activeCurName = response.currency.toLowerCase();

                    if(response.currency != ''){
                        $('.main-price-section').hide();
                        $('.currencyClass').hide();
                        $('.'+activeCurName+'-price-section').show();
                        $('.curChange option[value="'+ response.currency +'"]').prop('selected', true);
                    }
                }
            },
            error : function(){
            }
        });
    }
});
</script>

<script>
    let tabs = document.querySelectorAll(".tabs p");
    let tabContents = document.querySelectorAll(".tab-content div");
    tabs.forEach((tab, index) => {
        tab.addEventListener("click", () => {
            tabContents.forEach((content) => {
            content.classList.remove("active");
            });
            tabs.forEach((tab) => {
            tab.classList.remove("active");
            });
            tabContents[index].classList.add("active");
            tabs[index].classList.add("active");
        });
    });
    
    
        // const viewBtn = document.querySelector(".view-modal"),
        // popup = document.querySelector(".popup"),
        // close = popup.querySelector(".close"),
        // field = popup.querySelector(".field"),
        // input = field.querySelector("input"),
        // copy = field.querySelector("button");
        // viewBtn.onclick = ()=>{
        //     popup.classList.toggle("show");
        // }
        // close.onclick = ()=>{
        //     viewBtn.click();
        // }
        // copy.onclick = ()=>{
        //     input.select(); //select input value
        //     if(document.execCommand("copy")){ //if the selected text copy
        //     field.classList.add("active");
        //     copy.innerText = "Copied";
        //     setTimeout(()=>{
        //         window.getSelection().removeAllRanges(); //remove selection from document
        //         field.classList.remove("active");
        //         copy.innerText = "Copy";
        //     }, 3000);
        //     }
        // }
    
 
    
    


</script>

<script>
    $(document).ready(function(){
        $('#add-review').click(function(){
            $('#myModal').fadeToggle("fast","linear");
            $("body").css("overflow","hidden");
        });
        $('.close').click(function(){
            $('#myModal').fadeToggle("fast","linear");
            $("body").css("overflow","unset");
        });
        $('.modal-review').click(function (event) 
        {
            if(!$(event.target).closest('.modal-content').length && !$(event.target).is('.modal-content')) {
                $('#myModal').fadeToggle("fast","linear");
                $('body').css('overflow','unset');  
            }     
        });   

    });
</script>
@endsection