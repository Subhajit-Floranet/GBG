<div id="edit_account_information">
    <h3>Edit Your Address</h3>
    <div class="user-interface">
        {!! Form::model($address_details, ['route' => ['users.edit-address', $id], 'class' => 'form-horizontal', 'id' => 'edit_address_form', 'method' => 'POST'] ) !!}
        <fieldset>
            <div class="row" id="loginwithoutid">
                <div class="form-group">
                    <label>Name :</label>
                    {!! Form::text('name', $address_details->name, array('required','placeholder'=>"Name", 'class'=>'form-control', 'id' => 'name_add')) !!}
                </div>
                <div class="form-group">
                    <label>Address :</label>
                    {!! Form::text('address', $address_details->address, array('required', 'placeholder'=>"Address*", 'class'=>'form-control', 'id' => 'address_add', 'autocomplete' => 'off')) !!}
                </div>
                <div class="form-group">
                    <label>Pincode :</label>
                    {!! Form::text('pincode', $address_details->pincode, array( 'placeholder'=>"Pincode", 'class'=>'form-control', 'id' => 'pincode_add', 'autocomplete' => 'off')) !!}
                </div>
                <div class="form-group">
                    <label>State/Province :</label>
                    {!! Form::text('state_name', $address_details->state_name, array('class'=>'form-control', 'id' => 'state_name_add', 'autocomplete' => 'off')) !!}

                </div>
                <div class="form-group">
                    <label>Country :</label>
                    <select id="country_id_add" class="form-control selectpicker" name="country_id" required="true" disabled>
                        <option value="">Select</option>
                        <?php foreach ($country_list as $key => $value) { ?>
                            <option value="<?php echo $value['id'];?>" <?php if($address_details->country_id == $value['id'])echo 'selected';?>><?php echo $value['name'];?></option>
                        <?php  } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>City :</label>
                    <select id="city_id_add" class="form-control selectpicker" name="city_id" required="true">
                        <?php foreach ($city_list as $key => $value) { ?>
                            <option value="<?php echo $value['id'];?>" <?php if($address_details->city_id == $value['id'])echo 'selected';?>><?php echo $value['name'];?></option>
                        <?php }?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Phone No :</label>
                    {!! Form::text('mobile', $address_details->mobile, array('required', 'class'=>'form-control','placeholder'=>"Mobile", 'id' => 'mobile_add', 'autocomplete' => 'off')) !!}
                </div>
                <div class="d-flex justify-content-around button-container">
                    {!! Form::hidden('address_data_id', $id, array('id'=>'address_data_id') ) !!}
                    {!! Form::hidden('address_state', 'edit', array('id'=>'address_state')) !!}
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
              country_id: $('#country_id_add').val(),
              state_name: $('#state_name_add').val(),
              city_id: $('#city_id_add').val(),
              city_name: $('#city_name_add').val(),
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
                  url: '{{ route("users.myAddresses") }}',
                  method: 'POST',
                  dataType: 'HTML',
                  success: function(response_address) {
                      $('#address_data_div').show();
                      $('#address_data_div').html(response_address);                            
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