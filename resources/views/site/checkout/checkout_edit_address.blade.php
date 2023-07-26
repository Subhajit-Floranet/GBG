<div id="edit_account_information">
    <h3>Edit Your Address</h3>
    <div class="user-interface">
        <form method="POST" action="{{route('checkout-edit-address', [$id])}}"  class="form-horizontal" id="edit_address_form" >
        <fieldset>
            <div class="row" id="loginwithoutid">
                <div class="form-group">
                    <label>Name :</label>
                    <input type="text" name="name" class="form-control" id="name_add" placeholder="Name" value="{{$address_details->name}}" required>
                </div>
                <div class="form-group">
                    <label>Address :</label>
                    <input type="text" name="address" class="form-control" id="address_add" placeholder="Address*" value="{{$address_details->address}}" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label>Pincode :</label>
                    <input type="text" name="pincode" class="form-control" id="pincode_add" placeholder="Pincode" value="{{$address_details->pincode}}" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label>State/Province :</label>
                    <input type="text" name="state_name" class="form-control" id="state_name_add" placeholder="State" value="{{$address_details->state_name}}" autocomplete="off">
                </div>
                
                <div class="form-group">
                    <label>Phone No :</label>
                    <input type="text" name="mobile" class="form-control" id="mobile_add" placeholder="Mobile" value="{{$address_details->mobile}}" autocomplete="off" required>
                </div>
                <div class="flex justify-content-around button-container" style="display:flex; flex-wrap: unset;">
                    <input type="hidden" name="country_id" id="country_id" value="{{ $address_details->country_id }}">
                    <input type="hidden" name="city_id" id="city_id" value="{{ $address_details->city_id }}">

                    <input id="address_data_id" name="address_data_id" type="hidden" value="{{$id}}">
                    <input id="address_state" name="address_state" type="hidden" value="edit">

                    <button type="submit" class="btn button-nfjp new-address-add-close">Save</button>
                    <button type="button" id="cancel_edit_address" class="btn button-nfjp-cancel">Cancel</button>
                </div>
            </div>
        </fieldset>
        </form>
    </div>
</div>

<script type="text/javascript">
    $('#cancel_edit_address').on('click', function(){
        $('#edit_account_information').hide();
        $('#add_new_delivery_address').show();
        $('#delivery_address_update').show();
        $('#edit_account_information').addClass('loading');
        $.ajax({
            url: '{{ route("checkout-step-delivery-address") }}',
            method: 'POST',
            dataType: 'HTML',
            success: function(response) {
                $('#edit_account_information').removeClass('loading');
                $('#existing_delivery_addresses').show();
                $('#existing_delivery_addresses').html(response);                          
            }
        });
    });

    $("#edit_address_form").validate({
      rules: {
          name: {
              required: true,
          },
          mobile: {
              required: true,
          },
          email: {
              required: true,
              email: true
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
        $('#edit_account_information').addClass('loading');
        $('#address_msg_div').show();
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          url: "{{route('users.add-address')}}",
          method: 'post',
          data: {
              name: $('#name_add').val(),
              address: $('#address_add').val(),
              country_id: $('#country_id').val(),
              state_name: $('#state_name_add').val(),
              city_id: $('#city_id').val(),
              //city_name: $('#city_name_add').val(),
              mobile: $('#mobile_add').val(),
              pincode: $('#pincode_add').val(),
              address_state : $('#address_state').val(),
              address_data_id : $('#address_data_id').val()
          },
          success: function(data){
            setTimeout(function(){
              $('#edit_account_information').removeClass('loading');
              if(data.success){
                $('#address_msg_div').html('<p class="successinfo">'+data.success+'</p>');
                setTimeout(function() {$('#address_msg_div').fadeOut('slow');}, 2000);

                $('#edit_account_information').hide();
  
                $.ajax({
                  url: '{{ route("checkout-step-delivery-address") }}',
                  method: 'POST',
                  dataType: 'HTML',
                  success: function(response) {
                        $('#existing_delivery_addresses').show();
                        $('#existing_delivery_addresses').html(response);   
                        $('#add_new_delivery_address').show();
                        $('#delivery_address_update').show();                      
                  }
                });
  
              }else if(data.errors){
                $('#address_msg_div').html('<p class="nonsuccessinfo">'+data.errors+'</p>');
                setTimeout(function() {$('#address_msg_div').fadeOut('slow');}, 2000);
              }
            }, 1000);
          }
        });
        return false;
      }
    });
  
    //Get Country, State, City respect to Pincode
    $(document).ready(function() {
        $('#country_id_add').change(function(){
          var country_id = $(this).val();
          $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route("users.get-country-cities") }}',
                method: 'POST',
                dataType: 'HTML',
                data: { country_id: country_id },
                success: function(response_cities) {
                    $('#city_id_add').html(response_cities);
                    $('.selectpicker').selectpicker('refresh');
                }
            });
        });
    });
</script>