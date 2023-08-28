@php
if(isset($city_details->meta_title)){
  $meta_data['keyword'] = $city_details->meta_keyword;
  $meta_data['description'] = $city_details->meta_description;
  $meta_title = $city_details->meta_title;
}else{
  $meta_data['keyword'] = '';
  $meta_data['description'] = '';
  $meta_title = 'GBG';
}
//$path = '/'.$city_details->slug;
@endphp
@php 
$allCities = App\Http\Helper::getCities(99); 
$pathinfo = explode('/', $request->getPathInfo());
//print_r($pathinfo);

if ($sort != ''){
    $meta_robots = 'noindex, nofollow';
}

@endphp

@php $meta = App\Http\Helper::get_meta($meta_data, $city_details); @endphp
@extends('layouts.site.app', ['title' => $meta_title, 'meta_keyword' => $meta['meta_keyword'], 'meta_description' => $meta['meta_description']])

@section('content')

<!--------------------------NEW BANNER DESIGN STARTS HERE---------->
@if($city_details->image != null)
    <section class="new-banner1"id="new-banner1">
        <div class="pg-ban-txt">
            @if($city_details->banner_heading != '')
                <div class="ban-bg-txt">
                    <h1>{{ $city_details->banner_heading }}</h1>
                </div>
            @endif  
        </div>
        
        <img src="{{ asset('uploaded/city_index_banner/'.$city_details->image)}}" alt="{{ $city_details->image_alt }}" height="300" width="1900">
    </section>
    <!----------------------------ASK MORE starts here----->
    <div class="faq" id="faq-1">
        <button class="faq-btn" >INFO &nbsp;<span><i class="fa-solid fa-caret-down"></i></span></button>
        <div class="faq-body">
            <button class="faq-cross">X</button>
            <div class="faq-text-div">
                {!! $city_details->content !!}               
            </div>
        </div>
    </div>

@else

    @if(isset($city_details->content) && $city_details->content != null) 
        <section class="new-banner1"id="new-banner1">
            <div class="pg-ban-txt">
                @if($city_details->banner_heading != '')
                    <div class="ban-bg-txt"><h1>{{ $city_details->banner_heading }}</h1></div>
                @endif 
            </div>
            <p>{!! $city_details->content !!}</p>
        </section>
    @endif

@endif

<!-----------------####---NEW BANNER DESIGN STARTS HERE---#####-------->

@php $ratingSchema = App\Http\Helper::getRatingSchemaCitywise($city_details->id); @endphp
@if($ratingSchema)
<script type="application/ld+json">
{ 
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "https://www.germanflorist.de/<?php echo $city_details->slug ?>",
    "aggregateRating":
    {
        "@type": "AggregateRating",
        "ratingValue": "{{$ratingSchema->ratingvalue}}",
        "ratingCount": "{{$ratingSchema->ratingcount}}",
        "reviewCount": "123"
    }
}
</script>
@endif


 <!-----------------------------------BREAD CRUMB starts -------------->
<div class="categories-bread-crumb"> 
    <div itemscope itemtype="https://schema.org/BreadcrumbList"> 
        <ul class="breadcrumb" id="bread-crumb1">
            <li><a href="{{ url('/')}}">Home</a></li>
            <li>{{ $city_details->name }}</li>
        </ul>
    </div> 
</div>
  
  <!-----------------------------------BREAD CRUMB ENDS -------------->
  <!-----------------------------------Products -------------->
<section class="home-products">
    @if ( $products != null && count($products) > 0 )

        <div class="prd-container">
            @include('site.product.loadmore')

            <div id="results" class="prd-container" ></div>
            <div class="more_products"></div>

            <div class="loadmore loader-position" >
                <div id="loadmore-btn"  class="row"></div>
            </div>
        </div>

    @else

        <div class="prd-container">
            @php echo 'No records found. Please click on the other category link above to see all available products.'; @endphp
        </section>
        
    @endif    
</section>

<input type="hidden" name="active_currency" id="active_currency">
<input type="hidden" name="psort" id="psort" value="<?php echo $sort; ?>">
<input type="hidden" name="checklisting" id="checklisting" value="1">

<section class="content">
    @if(isset($city_details->content_bottom) && $city_details->content_bottom != null) 
        <div class="con-sec">
            {!! $city_details->content_bottom !!}
        </div>
    @endif
</section>

<script type="text/javascript">
    var page = 2;
    var counter = 0;
    var psort = $('#psort').val();

    $(window).scroll(function() { 
        //alert($('.content').height());
        if($(window).scrollTop()>= $(document).height() - $(window).height() - $('.content').height() - $('.quick-links').height() - $('#footmenu').height() - 100) {
            //alert(counter);
            $('.loadmore').show();
            if( counter == 0 ){
                counter++;
                if($('.loadmore').is(':visible')){
                    $("#loadmore-btn").click();
                }
            } 
            // ajax call get data from server and append to the div
        }
    });

    $(function(){
        $("#loadmore-btn").on('click', function(){
            load_more(psort);
        });
    });

    function load_more(psort){
        if( page != 1 ) {
            $('#loadmore-btn').addClass('loading'); //loading class in Load More button
        }

        var ajaxurl = '{{ route("loadMoreCity") }}';
        var lasturl = ajaxurl+'/?sort_by='+psort+'&page='+page;
        $.ajax(
            {
                url: lasturl,
                type: "get",
                datatype: "HTML",
                beforeSend: function()
                {
                    if( page == 1 ) {
                        $('#results').addClass('loading');
                    }
                }
            })
            .done(function(data)
            {
                //console.log(data);
                //if(data.length == 0){
                if(data.startsWith('<script')){
                    $('#loadmore-btn').removeClass('loading');
                    $('.loadmore').hide();
                    //$('.more_products').html('No more records!');
                    return;
                } else {
                    page++;
                    setTimeout(function() {
                        if( page == 1 ) {
                            //$('#results').addClass('loading');
                        }
                        else {
                            $('#loadmore-btn').removeClass('loading');
                        }
                        //$('.ajax-load').hide();
                        //$("#post-data").append(data);
                        $('#loadmore-btn').removeClass('loading');
                        $('#results').removeClass('loading');
                        $("#results").append(data); //append data into #results element
                        
                                            
                        counter = 0;
                        //console.log($('.loadmore').is(':visible'));
                    }, 50);
                }
                
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                counter = 0;
                $('.loadmore').hide();
                console.log('No response from server');
            });
    }
</script>

@endsection