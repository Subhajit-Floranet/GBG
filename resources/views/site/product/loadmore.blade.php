@foreach ( $products as $product )
@php 
$currency = App\Http\Helper::get_currency();
$earliestDelivery = App\Http\Helper::get_earliest_delivery_date($product->delivery_delay_days, $product->id);
$reviewRating = App\Http\Helper::getProductReviewNRating($product->id);
@endphp

<div class="prd-block" id="{{ $product->id }}">

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

    
    <div class="prd-img-block">
        <a href="{{ url($product->slug) }}" target="_blank">
            @php
        		$productDefaultImg = App\Http\Helper::getdefaultProductImage( $product->id );
            @endphp
            @if(isset($productDefaultImg) && $productDefaultImg['name'] != null)
                @if(file_exists(public_path('/uploaded/product/'.$productDefaultImg['name'])))
                    <img fetchpriority='High' src="{{ asset('uploaded/product/'.$productDefaultImg['name'])}}" srcset="{{ asset('uploaded/product/'.$productDefaultImg['name'])}} 768w, {{ asset('uploaded/product/'.$productDefaultImg['name'])}} 1200w, {{ asset('uploaded/product/'.$productDefaultImg['name'])}} 1500w, {{ asset('uploaded/product/'.$productDefaultImg['name'])}} 1900w" imagesizes="" height="500" width="500" alt="{{$product->alt_key}}" loading="lazy">
                @else
                    {!! '<img class="img-fluid lazy" data-original="' . URL::to('/') . config('global.no_image') . '"  src="' . URL::to('/') . '/images/site/spinner.gif" alt="'. $product->alt_key .'" title="'. $product->alt_key .'" height="500" width="500">' !!}
                @endif
            @else
                {!! '<img class="img-fluid lazy" data-original="' . URL::to('/') . config('global.no_image') . '"  src="' . URL::to('/') . '/images/site/spinner.gif" alt="'. $product->alt_key .'" title="'. $product->alt_key .'" height="500" width="500">' !!}
            @endif
        </a>
    </div>
    <div class="prd-block-desc">
        <p>
            @if(strlen($product->product_name) > 34)
                {{ substr($product->product_name, 0, 32) }}..
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
    
    <div class="msz-del @if($reviewRating['rating'] > 0 ) rating-padding @endif">
        Earlist Delivery:{{  $earliestDelivery }}
    </div>
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
            <a href="{{ url($product->slug) }}">ADD TO CART</a>
        </div>
    </div>
</div>

@endforeach

<script src="{{asset('site/js/lazyload.js')}}"></script>
<script>
    $(document).ready(function() {
        $("img.lazy").lazyload();

        if($('#active_currency').val() == ''){
            $('.main-price-section').show();
            $('.currencyClass').hide();
        } else if($('#active_currency').val() != ''){
        	var curName = $('#active_currency').val().toLowerCase();
            $('.main-price-section').hide();
            $('.currencyClass').hide();
            $('.'+curName+'-price-section').show();
        }

    });

    $(document).ready(function() {
        //$('.curChange').on('change', function() {
        $(".curChange").change(function () {
            //alert(this.value);
            if(this.value != ''){
                $.ajax({
                    type : "GET",
                    url : "{{ route('set_currency') }}/?currency="+this.value,
                    success : function(response){
                        console.log(response);
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
    });
</script>