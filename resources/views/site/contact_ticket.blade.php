@extends('layouts.site.app')

@section('content')

<?php
if( URL::to('/') != null ){
	$site_url = URL::to('/');
}else{
	$site_url = config('global.website_url');
}
?>

<ul class="breadcrumb">
    <li><a href="{{ url('/') }}">Home</a></li>
    <li>Contact Ticket</li>
</ul>

<div class="inside-container">
    <?php
        if( $conversation_details != null && isset($conversation_details) ) {
    ?>
    <div class="inside-heading">Hi {{ucwords($conversation_details->name)}}, </div>
    <div class="inside-txt">
        <div class="team-thanks">Thank you for contacting our support team!</div>
        <div>A support ticket has now been opened. You will be notified by email when a response is made. The details of your query have been emailed to you for record.</div>
        <div class="ticket-link">
            Please save the below link.You can view / update the ticket at any time.
            <a href="{{ $site_url }}/view-ticket-details?ticket_id={{$conversation_details->ticket_id}}&email={{$conversation_details->email}}" target="_blank">{{ $site_url }}/view-ticket-details?ticket_id={{$conversation_details->ticket_id}}&email={{$conversation_details->email}}</a>
        </div>
        <div class="thanks-note">
            <span>** Note:</span> We strive to reply to all mails within 6 working hours, if not sooner. Except during weekends, when we may take more time. However, during important occasions & festivals, due to sudden spurt in mail volumes, we may take more time. Thank you for your understanding and patience.
        </div>
    </div>
    <?php
        }
    ?>
</div>


@endsection