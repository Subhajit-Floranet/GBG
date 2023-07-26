@extends('layouts.admin.app')

@section('content')

{{-- <script type = "text/javascript" src = "https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.0/tinymce.min.js"></script>   --}}
{{-- <script src="{{ asset('admin/misc.js') }}"></script>
<script src="{{ asset('admin/tinymce.min.js') }}"></script> --}}

<script src="https://www.giftbasketworldwide.com/js/admin/misc.js"></script>
<script src="https://www.giftbasketworldwide.com/js/admin/tinymce.min.js"></script>

<body onload="myFunction()">
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">{{ strtoupper($websiteShortCode) }} Edit Coupon</h4>
            <div class="ml-auto text-right">
                <a href="{{ route('admin.'.$websiteShortCode.'.coupon.list') }}" class="btn btn-outline-info">Back</a>
            </div>
        </div>
      </div>
</div>

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <h4 class="font-weight-light alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</h4>
                    @endif
                @endforeach
                <form action="{{route('admin.'.$websiteShortCode.'.coupon.edit', [base64_encode($dataDetails->id)])}}" method="POST" class="form-horizontal" id="formadd" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <h4 class="card-title">Edit Coupon</h4>
                        <input type="hidden" id="formid" value="<?php echo $dataDetails->id; ?>">
                        <div class="form-group m-t-20">
                            <label>Coupon Code<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="coupon_code" name="coupon_code" placeholder="Enter Coupon Code" value="{{$dataDetails->coupon_code}}" required>
                        </div>
                        
                        <div class="form-group m-t-20">
                            <label>Discount Type<span style="color:red;">*</span></label><br>
                            <span><input type="radio" name="discount_type" class="discount_type" value="F" @if($dataDetails->type=="F") checked @endif>Flat</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span><input type="radio" name="discount_type" class="discount_type" value="P" @if($dataDetails->type=="P") checked @endif>Percent</span>
                        </div>

                        <div class="form-group m-t-20">
                            <label>Amount<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount" value="{{$dataDetails->amount}}" required>
                        </div>
                       
                        @php
                            $str = $dataDetails->start_date;
                            $arr = explode(" ",$str);
                            $str1 = $dataDetails->end_date;
                            $arr1 = explode(" ",$str1);
                        @endphp
                        <div class="form-group m-t-20">
                            <label>Start Date<span style="color:red;">*</span></label>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date" placeholder="Start Date" value="{{$arr[0]}} {{$arr[1]}}" required>
                        </div>
                        
                        <div class="form-group m-t-20">
                            <label>End Date<span style="color:red;">*</span></label>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date" placeholder="End Date" value="{{$arr1[0]}} {{$arr1[1]}}" required>
                        </div>
                        
                        <div class="form-group m-t-20">
                            <label>Minimum Cart Amount<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="minimum_cart_amount" name="minimum_cart_amount" placeholder="End Date" value="{{$dataDetails->minimum_cart_amount}}" required>
                        </div>
                    </div>
                    <div class="border-top">
                        <div class="card-body">
                            <input class="btn btn-primary" type="submit" value="Update">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input class="btn btn-primary" type="reset" value="Cancel">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">

    

    $.validator.setDefaults({
            submitHandler: function(form) {
                form.submit();
            }
    });
    $(function() {
        // validate the comment form when it is submitted
        $("#formadd").validate({
            ignore: [],
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

    /*Tinymce editor*/
    if ($("#falseurlcontenttop").length) {
        tinymce.init({
            selector: '#falseurlcontenttop',
            height: 200,
            theme: 'modern',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
            ],
            toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
            image_advtab: true,
            /*templates: [{
                    title: 'Test template 1',
                    content: 'Test 1'
                },
                {
                    title: 'Test template 2',
                    content: 'Test 2'
                }
            ],*/
            content_css: [],
            init_instance_callback: function (editor) {
			    editor.on('keydown', function (e) {
			      $('#content-error').hide();
			    });
			}
        });
    }

    if ($("#falseurlcontentbottom").length) {
        tinymce.init({
            selector: '#falseurlcontentbottom',
            height: 200,
            theme: 'modern',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
            ],
            toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
            image_advtab: true,
            /*templates: [{
                    title: 'Test template 1',
                    content: 'Test 1'
                },
                {
                    title: 'Test template 2',
                    content: 'Test 2'
                }
            ],*/
            content_css: [],
            init_instance_callback: function (editor) {
			    editor.on('keydown', function (e) {
			      $('#content-error').hide();
			    });
			}
        });
    }


    $('#title').on('blur', function(){
        var title = $.trim($(this).val());
        if(title != ''){
            //if($.trim($('#slug').val()) == ''){
                $('#slug').val(title.replace(/ /g,"-").toLowerCase());
                $('#slug-error').hide();
            //}
        }
    });
       



        $('.ctype').click(function(){
        //alert(this.value);
        if(this.value == 'P'){
            $('.price-range').show();
            $('#from_price').attr('required', true);
            $('#to_price').attr('required', true);
            $('#equation').attr('required', true);
        }else{
            $('.price-range').hide();
            $('#from_price').removeattr('required', true);
            $('#to_price').removeattr('required', true);
            $('#equation').removeattr('required', true);
        }
       
    })
  
    
    
   


</script>    

@endsection
