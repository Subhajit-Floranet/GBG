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
    <li><a href="javascript:void(0)">Delivery Locations</a></li>
</ul>

<div class="inside-container">
    <div class="inside-heading">{!! $page_details->title !!}</div>
    <div class="inside-txt">
        <div class="dld-body">
            <ul class="flex">
                @foreach($cities as $city)
                <li>
                    <p><a href="{{ $city->slug }}">{{ $city->name }}</a></p>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@endsection