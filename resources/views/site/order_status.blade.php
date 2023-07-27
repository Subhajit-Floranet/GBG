@extends('layouts.site.app')

@section('content')

<ul class="breadcrumb">
    <li><a href="{{ url('/') }}">Home</a></li>
    <li><a href="javascript:void(0)">Track Order</a></li>
</ul>

<div class="inside-container">
    <div class="inside-heading">Track Order</div>
    <div class="inside-txt">
        <div class="track-order-body">
            <p>To track your order please enter your Order ID and email in the box below and press the "Submit" button. This was given to you on your receipt and in the confirmation email you should have received.</p>
            <hr />
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                    <h5 class="font-weight-light alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</h5>
                @endif
            @endforeach
            
            <form method="POST" action="{{ route('order-status') }}"  id="orderStatus" enctype="multipart/form-data"> 
                @csrf   
                <div class="track-container">
                    <div class="track-order-form ">
                        <div class="tof-form-group">
                            <label for="name">Order ID</label>
                            <input required="" class="form-control" id="order_id" placeholder="Order ID" name="order_id" type="text">
                        </div>
                        <div class="tof-form-group">
                            <label for="email">Email</label>
                            <input required="" email="" class="form-control" id="emailid" placeholder="Email" name="emailid" type="text">
                        </div>
                    </div>
                    <div class="track-order-btn">
                        <button>Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // validate the comment form when it is submitted
        $("#orderStatus").validate({
            rules: {
				order_id: {
					required: true
				},
				emailid: {
					required: true,
					valid_email: true
				}
			},
            errorPlacement: function(label, element) {
                label.addClass('mt-2 text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                $(element).parents('.form-group').addClass('has-danger')
                $(element).addClass('form-control-danger')
            }
        });

    });
</script>

@endsection