@extends('layouts.site.app')

@section('content')

<ul class="breadcrumb">
    <li><a href="{{ url('/') }}">Home</a></li>
    <li>View Ticket Conversation</li>
</ul>

<div class="ticket-ahead">
    <div class="ticket-heading">
        <h1>Ticket Ahead</h1>
    </div>
    <?php
        if( $conversation_details != null ) {
    ?>
        <div class="ticket-details">
            <p><b>Ticket No :</b> {{ $ticket_id }}</p>
            <p><b>Subject :</b> {{ $conversation_details->contact_type }}</p>
            <p><b>Status : </b> <?php
                                if($conversation_details->is_block == 'N')
                                    echo 'Open';
                                else
                                    echo 'Closed';
                                ?></p>
        </div>
        <div class="ticket-msg-box">
            <?php
                if( $conversation_details->ContactConversation != null ) {
                    foreach( $conversation_details->ContactConversation as $details ) {
            ?>
            @if( $details->created_by != 1 )
                <div class="msg-img-txt flex">
                    <div class="msg-img"><img src="{{ asset('images/site/user.jpg') }}" alt="Users"></div>
                    <div class="msg-txt"><p>{{ $details->message }}</p></div>
                </div>
                <div class="sender-date"><p>{{ $conversation_details->name }} - {{ date('jS M, Y',strtotime($details->created_at)) }}</p></div>
            @else
                <div class="msg-img-txt flex">
                    <div class="msg-img"><img src="{{ asset('images/site/admin.png') }}" alt="Admin"></div>
                    <div class="msg-txt"><p>{{ $details->message }}</p></div>
                </div>
                <div class="sender-date"><p>GermanFlorist - {{ date('jS M, Y',strtotime($details->created_at)) }}</p></div>
            @endif
            <?php
                    }
                }
            ?>
        </div>
    <?php
        }else{
    ?>
        <div class="col-md-10 mx-auto"><span>No records found.</span></div>
    <?php
        }
    ?>

    <?php
        if( $conversation_details != null ) {
            if($conversation_details->is_block == 'N') {
    ?>

    <div class="ticket-reply">
        <h3>Your Reply</h3>
        
        <form method="POST" action="{{route('view-ticket-details')}}" id="contactConversation" class="flex" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="contactid" id="contactid" value="{{base64_encode($conversation_details->id)}}">
            <input type="hidden" name="tid" id="tid" value="{{base64_encode($ticket_id)}}">
            <input type="hidden" name="temail" id="temail" value="{{base64_encode($conversation_details->email)}}">
            <input type="text" name="reply_message" id="reply_message" required placeholder="Type a message" aria-describedby="button-addon2" class="form-control py-4 bg-light">
            <button type="submit"><i class="fa-solid fa-reply"></i></button>
        </form>
    </div>

    <?php  
            } 
        }
    ?>

</div>

@endsection