@php
$meta_data['keyword'] = $page_details->meta_keyword;
$meta_data['description'] = $page_details->meta_description;
$meta_title = $page_details->meta_title;

$meta = App\Http\Helper::get_meta($meta_data, $page_details);

@endphp
@extends('layouts.site.app', ['title' => $meta_title, 'meta_keyword' => $meta['meta_keyword'], 'meta_description' => $meta['meta_description']])

@section('content')

<ul class="breadcrumb">
    <li><a href="{{ url('/') }}">Home</a></li>
    <li>{!! $page_details->title !!}</li>
</ul>
    
<div class="inside-container">
    <div class="inside-heading">{!! $page_details->title !!}</div>
    <div class="inside-txt">
        {!! $page_details->content !!}
    </div>
</div>

<style>
.faq-content{width:100%;padding:0;margin:0 auto;padding:0 60px 0 0}.faq-content .centerplease{margin:0 auto;max-width:270px;font-size:40px}.faq-content .question{position:relative;background:#e1d0d0;margin:0;padding:10px 10px 10px 50px;display:block;width:100%;cursor:pointer}.faq-content .answers{background:#ede9e9;padding:0 15px;margin:5px 0;max-height:0;overflow:hidden;z-index:-1;position:relative;opacity:0;-webkit-transition:.7s ease;-moz-transition:.7s ease;-o-transition:.7s ease;transition:.7s ease}.faq-content .questions:checked~.answers{max-height:500px;opacity:1;padding:15px}.faq-content .plus{position:absolute;margin-left:10px;z-index:5;font-size:2.5em;line-height:100%;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;-o-user-select:none;user-select:none;-webkit-transition:.3s ease;-moz-transition:.3s ease;-o-transition:.3s ease;transition:.3s ease}.faq-content .questions:checked~.plus{-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-o-transform:rotate(45deg);transform:rotate(45deg)}.faq-content .questions{display:none}
</style>

@endsection