@php
if(isset($data['cat_meta_title']) && isset($data['cat_meta_description'])) {
    if($data['cat_meta_title'] != '' && $data['cat_meta_description'] != '') {
        $meta_data['keyword'] = $data['cat_meta_keyword'];
        $meta_data['description'] = $data['cat_meta_description'];
        $meta_title = $data['cat_meta_title'];
    }else{
        $meta_data['keyword'] = '';
        $meta_data['description'] = '';
        $meta_title = 'German Florist';
    }
} else {
    $meta_data['keyword'] = '';
    $meta_data['description'] = '';
    $meta_title = 'German Florist';
} 

if ($sort != ''){
    $meta_robots = 'noindex, nofollow';
}

$currency = App\Http\Helper::get_currency();
$pathinfo = explode('/', $request->getPathInfo());
@endphp


@php $meta = App\Http\Helper::get_meta($meta_data, $data); @endphp

@extends('layouts.site.app', ['title' => $meta_title, 'meta_keyword' => $meta['meta_keyword'], 'meta_description' => $meta['meta_description']])

@section('content')

<!--------------------------NEW BANNER DESIGN STARTS HERE---------->
@if($data['cat_image'] != null)
    <section class="new-banner1"id="new-banner1">
        <div class="pg-ban-txt">
            @if($data['banner_heading'] != '')
                <div class="ban-bg-txt"><h1>{{ $data['banner_heading'] }}</h1></div>
            @endif  
        </div>
        
        <img src="{{ asset('uploaded/category_banner/'.$data['cat_image'])}}" alt="{{ $data['banner_image_alt'] }}" height="300" width="1900">
    </section>
    <!----------------------------ASK MORE starts here----->
    <div class="faq" id="faq-1">
        <button class="faq-btn" >INFO &nbsp;<span><i class="fa-solid fa-caret-down"></i></span></button>
        <div class="faq-body">
            <button class="faq-cross">X</button>
            <div class="faq-text-div">
                {!! $data['cat_top_content'] !!}                
            </div>
        </div>
    </div>

@else

    @if(isset($data['cat_top_content']) && $data['cat_top_content'] != null) 
        <section class="new-banner1"id="new-banner1">
            <div class="pg-ban-txt">
                @if($data['banner_heading'] != '')
                    <div class="ban-bg-txt"><h1>{{ $data['banner_heading'] }}</h1></div>
                @endif  
            </div>
            <p>{!! $data['cat_top_content'] !!}</p>
        </section>
    @endif

@endif

<!-----------------####---NEW BANNER DESIGN STARTS HERE---#####-------->

@php $ratingSchema = App\Http\Helper::getRatingSchema($data['cat_id']); @endphp
@if($ratingSchema)
    <script type="application/ld+json">
    { 
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "https://www.germanflorist.de/<?php echo $data['cat_slug'] ?>",
        "aggregateRating":
        {
            "@type": "AggregateRating",
            "ratingValue": "{{$ratingSchema->ratingvalue}}",
            "ratingCount": "{{$ratingSchema->ratingcount}}",
            "reviewCount": "251"
        }
    }
    </script>
@endif


 <!-----------------------------------BREAD CRUMB starts -------------->
<div class="categories-bread-crumb"> 
    <div itemscope itemtype="https://schema.org/BreadcrumbList"> 
        <ul class="breadcrumb" id="bread-crumb1">
                
            <li itemprop='itemListElement' itemscope itemtype='https://schema.org/ListItem' >
                <a itemprop='item' href="{{ url('/')}}"><span itemprop='name'>Home</span></a>
                <meta itemprop="position" content="1" />
            </li>
            @php $url_breadcrumb = ''; @endphp
            @foreach($breadcrumb as $key=>$breadcrumb_text)
                @php //echo $key; 
                    if($key == 0){
                        $url_breadcrumb = '/'.$breadcrumb[0];
                    }elseif($key == 1){
                        $url_breadcrumb = '/'.$breadcrumb[0].'/'.$breadcrumb[1];
                    }
                @endphp
                @if(end($breadcrumb) == $breadcrumb_text)
                    <li aria-current="page" itemprop='itemListElement' itemscope itemtype='https://schema.org/ListItem'>
                        <span itemprop='name'>{{ ucwords(str_replace('-', ' ', $breadcrumb_text)) }}</span>
                        <meta itemprop="position" content="2" />
                    </li>
                @else
                    <li itemprop='itemListElement' itemscope itemtype='https://schema.org/ListItem'>
                        <a itemscope itemtype="https://schema.org/WebPage" itemprop="item" itemid="{{ url('/').$url_breadcrumb }}" href="{{ url('/').$url_breadcrumb }}" ><span itemprop='name'>{{ ucwords(str_replace('-', ' ', $breadcrumb_text)) }}</span></a>
                        <meta itemprop="position" content="2" />
                    </li>
                @endif
            @endforeach
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
    @if(isset($data['cat_bottom_content']) && $data['cat_bottom_content'] != null) 
        <div class="con-sec">
            {!! $data['cat_bottom_content'] !!}
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

        var ajaxurl = '{{ route("loadMore") }}';
        var lasturl = ajaxurl+'/?cat_id=<?php echo $data['cat_id']; ?>&sort_by='+psort+'&pathurl=<?php echo $data['urlpath']; ?>&page='+page;
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