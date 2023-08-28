<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>{{ config('global.website_title') }}</title>
    <style>
        *
        {
          padding:0;
          margin:0;
        }
        .container690
        {
            width:100%;
            margin:0 auto;
        }
        .imgsec
        {
            padding:36px 20px;
        }
        @media only screen and (max-width:1023px) {
            .container690
            {
                width:90%;
                margin:0 auto;
            }
            .imgsec img
            {
                width: 98%;
                margin: 0 5px 0 0;
                float: left;
                display: inline-block;
            }
            p{ font-size:14px!important; }
            .footcont p
            {
               font-size:10px!important; text-align:center;
            }
        }
    </style>
</head>

<body>


<table width="100%" border="0" cellpadding="0" cellspacing="0" class="container690" style="background-color: white; font-size:13px; font-family:sans-serif; width: 100%; color: #404040">

    <tr><td style="font-size:13px; line-height:21px;">Dear Sir/Madam,</td></tr>
    <tr><td style="font-size:13px; line-height:25px; padding: 15px 0">Please find our order details given below.</td></tr>

    <tr><td style="line-height:15px;"><strong>Order ID:</strong>  {{$data}}</td></tr>
    <tr><td style="line-height:15px;"><strong>Delivery Address:</strong> {!! $main_order_data->delivery_user_name !!}<br>
    {!! $main_order_data->delivery_address !!},<br>
    {!! $main_order_data->delivery_country !!},<br>
    {!! $main_order_data->delivery_city !!},<br>
    {!! $main_order_data->delivery_state !!} - {!! $main_order_data->delivery_pincode !!}</td></tr>
    <tr><td style="line-height:15px;"><strong>Phone Number:</strong> {!! $main_order_data->delivery_mobile !!}</td></tr>

    <tr><td style="padding: 5px 0"></td></tr>
    <?php
    if( count($order_data) > 0 ) {
        foreach( $order_data as $data ) {
            if(in_array($data['product_id'], $data_item_id) == true || in_array($data['gift_addon_id'], $data_item_id) == true){
    ?>

    <tr><td>
        <table width="70%" cellspacing="0" cellpadding="0" align="left" style="font-size:13px; font-family:sans-serif; text-align: center; border: 1px solid #CBCBCB; padding: 5px">
            <tr style="border-bottom: 1px dotted #414141">
                <td width="22%" style="text-align: left">
                    @if( isset($data['gift_addon_id']) && $data['gift_addon_id'] == 0 )
                        {{-- @php For product only @endphp --}}
                        @if(isset($data['image']) && $data['image'] != null )
                            @if(file_exists(public_path('/uploaded/product/'.$data['image'])))
                                {!! '<img src="https://www.germanflorist.de/uploaded/product/' . $data['image'] . '" style="width:100px; height:100px; float:left;" >' !!}
                            @else
                                {!! '<img src="' . URL::to('/').config('global.no_image') . '" style="width:100px; height:100px; float:left;" >' !!}
                            @endif
                        @else
                            {!! '<img src="'.URL::to('/').config('global.no_image').'" style="width:100px; height:100px; float:left;" >' !!}
                        @endif
                    @else
                        {{-- @php For gift addon only @endphp --}}
                        @if(isset($data['image']) && $data['image'] != null )
                            @if(file_exists(public_path('/uploaded/product/'.$data['image'])))
                                {!! '<img class="gift_image" src="https://www.germanflorist.de/uploaded/product/' . $data['image'] . '" style="width:100px; height:100px; float:left;" >' !!}
                            @else
                                {!! '<img class="gift_image" src="' . URL::to('/').config('global.no_image') . '" style="width:100px; height:100px; float:left;" >' !!}
                            @endif
                        @else
                            {!! '<img class="gift_image" src="'.URL::to('/').config('global.no_image').'" style="width:100px; height:100px; float:left;" >' !!}
                        @endif
                    @endif
                </td>
                <td width="78%" style="text-align: left; padding-left: 10px">
                    <?php //print_r($data) ?>
                    @php $item = App\Http\Helper::get_item_detail($data['product_id'], $data['gift_addon_id']);@endphp 
                    {{ $item->item_detail }}
                    <br>
                <strong>Qty :</strong> {{ $data['qty'] }}<br>
                <strong>Delivery Date :</strong> 
                @php
                    //if( isset($data['product_id']) && $data['product_id'] != 0 ) {
                        echo date('D, M d, Y', strtotime($data['delivery_date']));
                    //}
                @endphp
                </td>
            </tr>
        </table>
    </td></tr>
    
    <?php }
        }
    }
    ?>


    <tr><td style="padding: 5px 0"></td></tr>
    
    <tr><td style="line-height:15px;"><strong>Sender Name:</strong> {{ $main_order_data->order_message->sender_name }}</td></tr>
    <tr><td style="line-height:15px;"><strong>Sender Message:</strong> {{ $main_order_data->order_message->sender_message }}</td></tr>
    <tr><td style="line-height:15px;"><strong>Special Instruction:</strong>{{ $main_order_data->order_message->sender_special_instruction }}</td></tr>
    @if($main_order_data->order_message->sender_demand != '')
    <tr><td style="line-height:15px;"><strong>**Note:{{ $main_order_data->order_message->sender_demand }}</strong></td></tr>
    @endif

    <tr><td style="font-size:13px; line-height:25px; padding: 15px 0">Please Acknowledge the Mail.</td></tr>
    <tr><td style="font-size:13px; line-height:15px; padding: 5px 0; font-weight: 600">Best Regards,<br>
    Order Department<br>
    Floranet</td></tr>
</table>
   

</body>
</html>