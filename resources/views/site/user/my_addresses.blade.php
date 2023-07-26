@forelse($address_list as $address)
<address class="my-address" id="my_address_{{$address->id}}">
	<p class="name"><b>{{ $address->name }}</b>
        <a href="javascript:void(0)" data-href="{{ route('users.edit-address', [  App\Http\Helper::flower_encrypt_decrypt($address->id,'e')]) }}" class="address-editor-open address_edit_btn"><i class="fa-solid fa-edit" style="color:darkgreen;"></i></a> 
        <a href="javascript:void(0)" onclick="delete_address_my_account({{$address->id}});"><i class="fa-solid fa-trash" style="color:red;"></i></a>
    </p>
	<div class="row myp-info">
		<div class="col-12 col-sm-3 col-md-3 col-lg-2">Address :</div>
		<div class="col-12 col-sm-9 col-md-9 col-lg-10">{{ $address->address }}</div>
	</div>
	<div class="row myp-info">
		<div class="col-12 col-sm-3 col-md-3 col-lg-2">Landmark :</div>
		<div class="col-12 col-sm-9 col-md-9 col-lg-10">{{ $address->city_name }} - {{ $address->pincode }}, {{ $address->state_name }}, {{ $address->country_name }}</div>
	</div>
	<div class="row myp-info">
		<div class="col-12 col-sm-3 col-md-3 col-lg-2">Mobile :</div>
		<div class="col-12 col-sm-9 col-md-9 col-lg-10">{{ $address->mobile }}</div>
	</div>
	<!-- <i>
		<a href="javascript:void(0)" data-href="{{ route('users.edit-address', [  App\Http\Helper::flower_encrypt_decrypt($address->id,'e')]) }}" class="address-editor-open address_edit_btn"><i class="fa-solid fa-edit"></i></a> 
		<a href="javascript:void(0)" onclick="delete_address_my_account({{$address->id}});"><i class="fa-solid fa-trash"></i></a>
	</i> -->
</address>
@empty
    No address found.
@endforelse

<div class="my-2" style="margin-top:10px">
	<button class="btn button-nfjp" id="new_address_add">Add New Address</button>
</div>


<script>
    $("#add_address_form").validate({
        rules: {
            name: {
                required: true,
            },
            address: {
                required: true,
            },
            city_name: {
                required: true,
            },
            country_name: {
                required: true,
            },
            mobile: {
                required: true,
                //email: true
            },
        },
        errorPlacement: function(label, element) {
            label.addClass('mt-2 text-danger');
            label.insertAfter(element);
        },
        highlight: function(element, errorClass) {
            $(element).parent().addClass('has-danger')
            $(element).addClass('form-control-danger')
        },
    	submitHandler: function (form) {
            $('#add_account_information').addClass('loading');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
            	url: '{{ route("users.add-address") }}',
                method: 'POST',
                data: {
                    name: $('#name_add').val(),
                    address: $('#address_add').val(),
                    country_id: $('#country_id_add').val(),
                    state_name: $('#state_id_add').val(),
                    city_id: $('#city_id_add').val(),
                    city_name: $('#city_name_add').val(),
                    mobile: $('#mobile_add').val(),
                    pincode: $('#pincode_add').val()
                },
                success: function(data){
                	setTimeout(function(){
                		$('#add_account_information').removeClass('loading');
                    	if(data.success){
		                    $('#address_msg_div').append('<p class="successinfo">'+data.success+'</p>');
		                    setTimeout(function() {$('#address_msg_div').fadeOut('slow');}, 2000);

		                    $('#new_address_add_form').hide();

		                    $('#add_address_form').trigger("reset");
                    
                    		$.ajax({
                        		url: '{{ route("users.myAddresses") }}',
                        		method: 'POST',
                        		dataType: 'HTML',
                        		success: function(response_address) {
                            		$('#address_data_div').show();
                            		$('#address_data_div').html(response_address);
								}
                    		});

		                }else if(data.errors){
		                	$('#address_msg_div').append('<p class="nonsuccessinfo"> '+data.error+'</p>');
                    		setTimeout(function() {$('#address_msg_div').fadeOut('slow');}, 2000);
		                }
            		}, 1000);
                }
            });
            return false;
        }
    });


    $(document).ready(function() {
        $('#new_address_add').click(function(){
            $('#address_data_div').hide();
            $('#new_address_add_form').show();
        });
    });

    function delete_address_my_account(id) {

        if (confirm("Are you sure? You won't be able to revert this!")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('users.delete-address')}}",
                type: 'POST',
                dataType: "JSON",
                data: {
                    id: id
                },
                success: function(response){
                    if(response.type == 'success'){
                        // swal({title: response.title, text: response.message, type: response.type}).then(function(){
                        //         //location.reload();
                        //     }
                        // );
                        $('#my_address_'+id).remove();
                    }else{
                        setTimeout(function () {
                            //swal(response.title, response.message, response.type);
                        }, 200);
                    }
                }
            });
        } else {
          // Do nothing!
          console.log('Okay');
        }

        // swal({
        //     title: 'Are you sure?',
        //     text: "You won't be able to revert this!",
        //     icon: "warning",
        //     buttons: true,
        //     dangerMode: true,
        // }).then((willDelete) => {
        //     if (willDelete) {
        //         $.ajaxSetup({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             }
        //         });
        //         $.ajax({
        //             url: "{{route('users.delete-address')}}",
        //             type: 'POST',
        //             dataType: "JSON",
        //             data: {
        //                 id: id
        //             },
        //             success: function(response){
        //                 if(response.type == 'success'){
        //                     swal({title: response.title, text: response.message, type: response.type}).then(function(){
        //                             //location.reload();
        //                         }
        //                     );
        //                     $('#my_address_'+id).remove();
        //                 }else{
        //                     setTimeout(function () {
        //                         swal(response.title, response.message, response.type);
        //                     }, 200);
        //                 }
        //             }
        //         });
        //     } else {
        //         //swal("Your imaginary file is safe!");
        //     }
        // })

       
    }

    $('.address_edit_btn').click(function(){
                var address_edit_url = $(this).attr('data-href');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: address_edit_url,
                    method: 'POST',
                    dataType: 'HTML',
                    success: function(response_address) {
                        //$('#address_data_div').show();
                        $('#address_data_div').html(response_address);
                        $('.selectpicker').selectpicker('refresh');
                    }
                });
    });

    $('#cancel_save_address').on('click', function(){
        $('#new_address_add_form').hide();
        $.ajax({
            url: '{{ route("users.myAddresses") }}',
            method: 'POST',
            dataType: 'HTML',
            success: function(response_address) {
                $('#address_data_div').show();
                $('#address_data_div').html(response_address);                            
            }
        });
    });
</script>