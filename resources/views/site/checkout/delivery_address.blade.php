<?php

if( $address_list != null && count($address_list) > 0 ) {
    $f=1;
    //echo $selectaddress;
?>

<div class="row your-assress-book" id="from-addressbook">
    <?php
    foreach( $address_list as $key_address => $val_address ) {
        if( $f == 1 && $last_address_id == 0 ){ $last_address_id = $val_address->id; }
    ?>
    <label id="my_address_{{$val_address->id}}">
        
        <input type="radio" id="existing_address<?php echo $val_address->id;?>" name="existing_address" value="{{ $val_address->id }}" 
        <?php if( $val_address->id == $last_address_id )echo 'checked="checked"';?> class="from-addressbook-select">

        <div class="row">
            <a href="javascript:void(0)" data-href="{{ route('checkout-edit-address', [  App\Http\Helper::flower_encrypt_decrypt($val_address->id,'e')]) }}" class="address-editor-open address_edit_btn" style="margin-left: 0.7em;"><i class="fa-solid fa-edit" style="color:darkgreen;"></i></a>
            <em><i class="fa fa-user" aria-hidden="true"></i></em> {!! $val_address->name !!} 
            
        </div>
        <div class="row"><em><strong>Address :</strong></em> {!! $val_address->address !!}</div>
        <div class="row justify-start"><em><strong>Country</strong> : {!! $val_address->country_name !!}</em></div>
        <div class="row justify-start"> <em><strong>City</strong> : {!! $val_address->city_name !!}</em> <em><strong>Pincode</strong> : {!! $val_address->pincode !!}</em></div>
        <div class="row"><em><i class="fa fa-mobile" aria-hidden="true"></i></em> {{--<em>Phone :</em>--}} {!! $val_address->mobile !!}</div>
    </label>
    <?php
        $f++;
        }
    }
    ?>
    <input type="hidden" name="delivery_address_id" id="delivery_address_id" value="{{ $last_address_id }}">
<div>

<script type="text/javascript">

$('.address_edit_btn').click(function(){
    $('#add_new_delivery_address').hide();
    $('#delivery_address_update').hide();
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
            $('#existing_delivery_addresses').html(response_address);
            //$('.selectpicker').selectpicker('refresh');
        }
    });
});

// $(document).ready(function( ){
//     $('.address_options').change(function(){
//         if( $(this).is(":checked") ){
//             var selected_addressid = $(this).val();
//             if( $(this).val() > 0 ) {
//                 $('#delivery_address_id').val($(this).val());
//                 $.ajaxSetup({
//                     headers: {
//                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                     }
//                 });
//                 $.ajax({
//                     url: '{{ route("delivery-address-update-cart") }}',
//                     method: 'POST',
//                     data: {
//                         selected_address_id: selected_addressid
//                     },
//                     success: function(response) {                        
//                     }
//                 });                
//             }else{
//                 $('#delivery_address_id').val(0);
//             }
//         }
//     });
// });
$(document).ready(function( ){
        
        $('.from-addressbook-select:checked').parent().addClass("addressbook-selected");
        
        $('.from-addressbook-select').change(function(){
            $('.from-addressbook-select:not(:checked)').parent().removeClass("addressbook-selected");
            $('.from-addressbook-select:checked').parent().addClass("addressbook-selected");
            
            if( $(this).is(":checked") ){
                
                var selected_addressid = $(this).val();
                //alert(selected_addressid);
                if( $(this).val() > 0 ) {
                    $('#delivery_address_id').val($(this).val());
                    // $.ajaxSetup({
                    //     headers: {
                    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    //     }
                    // });
                    // $.ajax({
                    //     url: '{{ route("delivery-address-update-cart") }}',
                    //     method: 'POST',
                    //     data: {
                    //         selected_address_id: selected_addressid
                    //     },
                    //     success: function(response) {     
                    //         console.log(response);                   
                    //     }
                    // });                
                }else{
                    $('#delivery_address_id').val(0);
                }
            }
        });
});
</script>
</div>