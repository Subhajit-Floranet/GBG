@php
$currency = App\Http\Helper::get_currency();
@endphp


@extends('layouts.site.app', ['title' => $home_bottom_content->meta_title, 'meta_keyword' => $home_bottom_content->meta_keyword, 'meta_description' => $home_bottom_content->meta_description])

@section('content')

<!--index banner-->
<section class="home-banner">
    <div class="home-bg-banner">
        <div class="bg-lft-ban" onClick="window.location.href=''">
            <div class="image"><img src="images/Birthday-slide.webp" alt="Birthday Gifts"></div>
            <div class="bg-lft-txt banner-txt">
                <div class="bg-bold">Birthday</div>
                <div class="bg-light">Gift Baskets</div>
                <div class="bg-sm-txt left-txt">Lorem ipsum dolor sit, amet consectetur adipisicing elit.</div>
                <div class="shop-now" style="clear:both"><a href="">Shop Now</a></div>
            </div>
        </div>
        <div class="bg-rgt-ban" onClick="window.location.href=''">
            <div class="image"><img src="images/main-slide.webp" alt="Gourmet Gifts"></div>
            <div class="bg-rgt-txt banner-txt res-ban-txt">
                <div class="bg-light-rgt">Gourmet</div>
                <div class="bg-bold-rgt">Gift Baskets</div>
                <div class="bg-sm-txt">Lorem ipsum dolor sit, amet consectetur adipisicing elit.</div>
                <div class="shop-now"><a href="">Shop Now</a></div>
            </div>
        </div>
    </div>
    <div class="home-sm-banner">
        <div class="sm-ban-container" onClick="window.location.href=''">
            <div class="image"><img src="images/Corporate-slide.webp" alt="Corporate Gifts"></div>
            <div class="sm-ban-txtbox">
                <div class="sm-bold">Corporate</div>
                <div class="sm-light">Gift Baskets</div>
                <div class="smban-txt">Lorem ipsum dolor sit, amet consectetur adipisicing elit. </div>
                <div class="shop-now-sm"><a href="">Shop Now</a></div>
            </div>
        </div>
        <div class="sm-ban-container" onClick="window.location.href=''">
            <div class="image"><img src="images/Alcoholfree-slide.webp" alt="Alcohol Free Gifts"></div>
            <div class="sm-ban-txtbox">
                <div class="sm-bold">Alcoholfree</div>
                <div class="sm-light">Gift Baskets</div>
                <div class="smban-txt">Lorem ipsum dolor sit, amet consectetur adipisicing elit. </div>
                <div class="shop-now-sm"><a href="">Shop Now</a></div>
            </div>
        </div>
        <div class="sm-ban-container" onClick="window.location.href=''">
            <div class="image"><img src="images/Romantic-slide.webp" alt="Romantic Gifts"></div>
            <div class="sm-ban-txtbox">
                <div class="sm-bold">Romantic</div>
                <div class="sm-light">Gift Baskets</div>
                <div class="smban-txt">Lorem ipsum dolor sit, amet consectetur adipisicing elit. </div>
                <div class="shop-now-sm"><a href="">Shop Now</a></div>
            </div>
        </div>
    </div>
</section>

@if( count($featured_category) > 0 )
    @php $t = 1; $position = 1; @endphp
    @foreach($featured_category as $category)
        @php
            //echo $category->category_id;
            $product_list = App\Http\Helper::getHomepageProductsByCatId( $category->category_id, $category->data_limit);
            //dd($product_list);
        @endphp
        
        @if( count($product_list['product']) > 0 )
            <section class="home-products" >
                <div class="hp-heading flex">
                    <div class="hph-txt">
                    <h2>{{ $category->title }}</h2>
                    <p>{{ $category->description }}</p>
                    </div>
                </div>
                <div class="prd-container">
                    <!--product block-->
                    @foreach( $product_list['product'] as $product )

                    @php
                        $reviewRating = App\Http\Helper::getProductReviewNRating($product->id);
                    @endphp

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

                    <div class="prd-block" id="{{$product->id}}">
                        @if($mrp > 0 && ($mrp > $mainPrice))
                            @php $discount = (($mrp - $mainPrice)/$mrp) * 100; @endphp
                            @if(number_format($discount, 2) > 0)
                                <div class="product-discount">
                                    <p>{{number_format($discount, 1)}}% OFF</p>
                                </div>
                            @endif
                        @endif
                        <div class="prd-img-block">
                            <a href="{{ url('/'.$product->slug) }}" target="_blank">
                                @if( $product->default_product_image != null )
                                    @if(file_exists(public_path('/uploaded/product/'.$product->default_product_image['name'])))
                                        <img fetchpriority='High' src="{{ asset('uploaded/product/'.$product->default_product_image['name'])}}" srcset="{{ asset('uploaded/product/thumb/'.$product->default_product_image['thumb'])}} 768w, {{ asset('uploaded/product/'.$product->default_product_image['name'])}} 1200w, {{ asset('uploaded/product/'.$product->default_product_image['name'])}} 1500w, {{ asset('uploaded/product/'.$product->default_product_image['name'])}} 1900w" imagesizes="" height="500" width="500" alt="{{$product->alt_key}}" loading="lazy">
                                    @else
                                        {!! '<img class="img-fluid" src="' . URL::to('/').config('global.no_image'). '" alt="'. $product->alt_key .'" title="'. $product->alt_key .'" loading="lazy" height="500" width="500" >' !!}
                                    @endif
                                @else
                                    {!! '<img class="img-fluid" src="' . URL::to('/').config('global.no_image'). '" alt="'. $product->alt_key .'" title="'. $product->alt_key .'" loading="lazy" height="500" width="500" >' !!}
                                @endif
                            </a>
                        </div>
                        <div class="prd-block-desc">
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
                        <div class="msz-del @if($reviewRating['rating'] > 0 ) rating-padding @endif">
                            @php
                                $earliestDelivery = App\Http\Helper::get_earliest_delivery_date($product->delivery_delay_days, $product->id);
                            @endphp
                            Earlist Delivery: {{  $earliestDelivery }}
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

                                        <div class="home-price flex {{ strtolower($cur->symbol) }}-price-section currencyClass" style="display:none;"> 
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
                                <a href="{{ url('/'.$product->slug) }}" target="_blank">ADD TO CART</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <!--product block ends-->
                    
                    <!--last product banner-->
                    <div id="cakes-banner">
                        <a href="" title="More Gifts">
                            <img class="ban-img" src="{{asset('images/'.$category->banner)}}" alt="More Gifts" width="400" height="400" />
                            <div class="more-products"><span>More Products</span></div>
                        </a>
                    </div>
                    <!--last product banner ends-->
                </div>
            </section>
        @endif

        @if( $t == 1 )

            <!---FAQ section-->
            <div class="accordion-section ">
                <div class="accordion-sub">
                    <p class="question1">FAQ</p>
                    <p class="question2">Frequently Asked Questions</p>
                </div>
                <div class="accordion">
                    <div class="accordion-content">
                        <header>
                            <span class="title">Can I customize the contents of a gift basket?</span>
                            <!--<i class="fa-solid fa-plus"></i>--->
                            <i class="fa-solid fa-angle-down"></i>
                        </header>
                
                        <p class="description">
                            Yes, we offer the option to customize the contents of select gift baskets.<br> 
                            You can choose from a variety of available items and create a personalized gift basket tailored to your recipient's preferences. 
                        </p>
                    </div>
                
                    <div class="accordion-content">
                        <header>
                            <span class="title">Can I track the status of my order?</span>
                            <i class="fa-solid fa-angle-down"></i>
                        </header>
                
                        <p class="description">
                            Absolutely! Once your order is confirmed and shipped, you will receive a tracking number via email. This tracking number can be used to monitor the status and location of your package. 
                        </p>
                    </div>
                    <div class="accordion-content">
                        <header>
                            <span class="title">Can I ship gift baskets to multiple addresses?</span>
                            <i class="fa-solid fa-angle-down"></i>
                        </header>
                
                        <p class="description">
                            Yes, we offer the convenience of shipping gift baskets to multiple addresses.<br>
                            During the checkout process, you will have the option to add multiple shipping addresses. Simply provide the necessary details for each address, select the desired gift basket for each recipient, and proceed with the order. This allows you to easily send thoughtful gifts to multiple locations without any hassle.
                        </p>
                    </div>
                    <div class="accordion-content">
                        <header>
                            <span class="title">Are the gift baskets packaged securely to ensure the items are not damaged during transit?</span>
                            <i class="fa-solid fa-angle-down"></i>
                        </header>
                
                        <p class="description">
                            Absolutely! We take great care in packaging our gift baskets to ensure they arrive in pristine condition.<br>Each gift basket is carefully arranged and securely packaged to protect the items during transit. We use high-quality packaging materials and cushioning to safeguard delicate items and prevent any damage during shipping. Rest assured that your gift basket will be delivered intact and ready to impress the recipient. 
                    </div>
                </div>
            </div>
            <div class="faq">
                <button class="faq-btn">MORE &nbsp;<span><i class="fa-solid fa-caret-down"></i></span></button>
                <div class="faq-body">
                    <button class="faq-cross">X</button>
                    <div class="faq-text-div">
                        <h2>About</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum sed fugiat praesentium inventore nemo numquam repudiandae quibusdam debitis. Esse impedit nostrum beatae blanditiis, ea tempora, nam molestias rem quia perferendis dolore aut debitis a ipsa suscipit voluptates similique accusantium error animi quis voluptatibus! Corporis,
                                eum! Eveniet recusandae incidunt aspernatur Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda nulla expedita, explicabo, cum ullam voluptatibus cumque atque et non officia impedit! Quis culpa dolores aspernatur veniam molestiae! Laboriosam repellendus a quod minima, omnis provident pariatur nam placeat nobis veritatis quisquam exercitationem. Earum esse nihil suscipit ullam quibusdam assumenda repellendus. Harum.
                        </p>
                    </div>
                </div>
            </div>

        @elseif( $t == 3 )

            <!--middle banner of delivery-->
            <div class="middle-banner">
                <div class="middle-first-banner">
                    <div class="middle-banner-img"><i class="fa-solid fa-car"></i></div>
                    <div class="middle-banner-text"><p>Guranteed Delivery</p></div>
                </div>
                <div class="middle-second-banner">
                    <div class="middle-banner-img"> <i class="fa-regular fa-money-bill-1"></i> </div>
                    <div class="middle-banner-text"> <p>Hassle Free Delivery</p> </div>
                </div>
                <div class="middle-third-banner"> 
                    <div class="middle-banner-img"> <i class="fa-solid fa-globe"></i> </div>
                    <div class="middle-banner-text"> <p>100% Secure Payments</p> </div> </div>
                <div class="middle-fourth-banner">
                    <div class="middle-banner-img"> <i class="fa-solid fa-headphones"></i> </div>
                    <div class="middle-banner-text"> <p>100% Secure Payments</p> </div>
                </div>
            </div>

        @endif

        @if( $t % 2 == 0 )

            @if($position == 0)
                
            

            @endif

            @php
                $position++;
            @endphp
            
        @endif

        @php
            $t++;
        @endphp


    @endforeach
@endif

<!---Banner slider---->
<section class="banner-slider">
    <img class="banner-slider-img" src="images/carousel_banner.webp" alt="" width="1900" height="600">
    <img class="banner-slider-img-mobile" src="images/mobile-banner-min.webp" alt="" width="600" height="600">
    <div class="owl-carousel owl-theme banner-slider-txt">
        <div class="bst-inner">
            <p class="bst-txt2">Fresh Flowers</p>
            <p class="bst-txt1">Send Flowers to USA</p> 
            <a href="">Buy Now</a>
        </div>
        <div class="bst-inner">
            <p class="bst-txt2">Gourmet Gifts </p>
            <p class="bst-txt1">Send Gourmet Gifts to USA</p>  
            <a href="">Buy Now</a>
        </div>
        <div class="bst-inner">
            <p class="bst-txt2">Chocolates</p>
            <p class="bst-txt1">Send Chocolates to USA</p>
            <a href="">Buy Now</a>
        </div>
    </div>
</section>

<!---shop by categories---->    
<section class="shop-by-cat">
    <div class="sbc-heading">
    <div class="sbch-txt">
    <p>Unleash the Meaningful Range of Gift Baskets</p>
    <h5 class="sub-heading">Send Heartfelt Gifts to USA, Conveying Your Deepest Sentiments</h5>
    </div>
    </div>
    <div class="sbc-body clearfix">
    <a href="" title="Fresh Flowers" class="sbc-box">
    <div class="sbcb-cat">
    <img src="images/bottom_flowers_ban.webp" alt="" width="408" height="410">
    </div>
    <h3>Fresh Flowers</h3>
    </a>
    <a href="" title="Wine Gift Baskets" class="sbc-box">
    <div class="sbcb-cat">
    <img src="images/bottom_wines_ban.webp" alt="" width="408" height="410">
    </div>
    <h3>Wine Gift</h3>
    </a>
    <a href="" title="Chocolates" class="sbc-box">
    <div class="sbcb-cat">
    <img src="images/bottom_chocolates_ban.webp" alt="" width="408" height="410">
    </div>
    <h3>Chocolates</h3>
    </a>
    <a href="" title="Fruit Gift Baskets" class="sbc-box">
    <div class="sbcb-cat">
    <img src="images/bottom_fruits_ban.webp" alt="" width="408" height="410">
    </div>
    <h3>Fruit Gift</h3>
    </a>
    <a href="" title="Christmas Gifts" class="sbc-box">
    <div class="sbcb-cat">
    <img src="images/bottom_christmas_ban.webp" alt="" width="408" height="410">
    </div>
    <h3>Christmas Gifts</h3>
    </a>
    <a href="" title="Birthday Gifts" class="sbc-box">
    <div class="sbcb-cat">
    <img src="images/bottom_birthdat_ban.webp" alt="" width="408" height="410">
    </div>
    <h3>Birthday</h3>
    </a>
    <a href="" title="Love and Romance" class="sbc-box">
    <div class="sbcb-cat">
    <img src="images/bottom_love_ban.webp" alt="" width="408" height="410">
    </div>
    <h3>Love and Romance</h3>
    </a>
    <a href="" title="Non Alcoholic Hamper" class="sbc-box">
    <div class="sbcb-cat">
    <img src="images/bottom_nonalcohol_ban.webp" alt="" width="408" height="410">
    </div>
    <h3>Non Alcoholic</h3>
    </a>
    </div>
</section>
    
<!---shop by price---->        
<section class="shop-by-price">
    <div class="sbp-heading">
    <div class="sbph-txt">
    <p>Explore Gifts for Every Budget</p>
    <h5 class="sub-heading">Find Perfect Gifts Within Your Budget for USA Delivery</h5>
    </div>
    </div>
    <div class="sbp-body clearfix">
    <a href="" class="sbp-box">
    <div class="sbpb-cat">
    <img src="images/under_75.webp" alt="" width="408" height="410">
    </div>
    <h3>Under $75</h3>
    </a>
    <a href="" class="sbp-box">
    <div class="sbpb-cat">
    <img src="images/under_125.webp" alt="" width="408" height="410">
    </div>
    <h3>Under $125</h3>
    </a>
    <a href="" class="sbp-box">
    <div class="sbpb-cat">
    <img src="images/under_175.webp" alt="" width="408" height="410">
    </div>
    <h3>Under $175</h3>
    </a>
    <a href="" class="sbp-box">
    <div class="sbpb-cat">
    <img src="images/over_175.webp" alt="" width="408" height="410">
    </div>
    <h3>Over $175</h3>
    </a>
    </div>
</section>
    
<!---shop by relation---->     
<section class="shop-by-relation">
    <div class="sbr-heading">
    <div class="sbrh-txt">
    <p>Thoughtful Gifts for Every Connection in USA</p>
    <h5 class="sub-heading">Discover a Diverse Assortment of Gifts for Meaningful Relations</h5>
    </div>
    </div>
    <div class="sbr-body clearfix">
    <a href="" class="sbrb-box">
    <div class="sbrb-cat">
    <img src="images/forhim_ban.webp" alt="" width="408" height="410">
    </div>
    <h3>For Him</h3>
    </a>
    <a href="" class="sbrb-box">
    <div class="sbrb-cat">
    <img src="images/forher_ban.webp" alt="" width="408" height="410">
    </div>
    <h3>For Her</h3>
    </a>
    <a href="" class="sbrb-box">
    <div class="sbrb-cat">
    <img src="images/forkids_ban.webp" alt="" width="408" height="410">
    </div>
    <h3>For Kids</h3>
    </a>
    <a href="" class="sbrb-box">
    <div class="sbrb-cat">
    <img src="images/corporate_ban.webp" alt="" width="408" height="410">
    </div>
    <h3>Corporate</h3>
    </a>
    </div>
</section>

<!--Testimonial-->
<section class="testimonials">
    <div class="testimonial-div">
        <p class="t-head">Testimonials</p>
        <span class="divider"></span>
    </div>
  
    <div class="t-body owl-carousel owl-theme flex">
        @foreach($testimonials as $testimonial)
            <div class="test-sec">
                <h3>{!! $testimonial->name !!}</h3>
                <div class="tst-date-loc">
                    <p>From : {!! $testimonial->send_place !!}</p>
                    <p>Delivery To : {!! $testimonial->place !!}</p>
                </div>
                <p>{!! $testimonial->content !!}</p>
            </div>
        @endforeach
    </div>
  
</section>

<!--Writeup-->
<section class="content">
    <div class="con-sec">
        {!! $home_bottom_content->content !!}
    </div>
</section>

<script type="text/javascript">

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

@endsection