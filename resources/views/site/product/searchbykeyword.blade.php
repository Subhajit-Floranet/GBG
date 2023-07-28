@php
    $meta_data['keyword'] = $token;
    $meta_data['description'] = $token;
    $meta_title = 'Search By : '.$token;
    $data = [];
    //$urlpath = $City_detail->slug.'/';
    $urlpath = '';
    $pathinfo = explode('/', $request->getPathInfo());
@endphp
@php $meta = App\Http\Helper::get_meta($meta_data, $data); @endphp



@extends('layouts.site.app', ['title' => $meta_title, 'meta_keyword' => $meta['meta_keyword'], 'meta_description' => $meta['meta_description']])

@section('content')


 <!-----------------------------------BREAD CRUMB starts -------------->
<div class="categories-bread-crumb"> 
    <ul class="breadcrumb" id="bread-crumb1">
        <li><b>Search By : </b>{{ strtoupper(Request::query('query')) }}</li>
    </ul>
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

<script type="text/javascript">
    var page = 2;
    var counter = 0;
    var psort = $('#psort').val();

    $(window).scroll(function() { 
        //alert($('.content').height());
        if($(window).scrollTop()>= $(document).height() - $(window).height() - $('.quick-links').height() - $('#footmenu').height() - 100) {
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

        
        var ajaxurl = '{{ route("loadMoreSearchByKeyword") }}';
        var lasturl = ajaxurl+'?page='+page+'&sort_by='+psort+'&query={!! $token !!}'+'&pathurl={!! $urlpath !!}';
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