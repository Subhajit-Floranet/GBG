@extends('layouts.admin.app')

@section('content')

{{-- <script type = "text/javascript" src = "https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.0/tinymce.min.js"></script>   --}}
{{-- <script src="{{ asset('admin/misc.js') }}"></script>
<script src="{{ asset('admin/tinymce.min.js') }}"></script> --}}

<script src="https://www.giftbasketworldwide.com/js/admin/misc.js"></script>
<script src="https://www.giftbasketworldwide.com/js/admin/tinymce.min.js"></script>

<link rel="stylesheet" type="text/css" href="http://localhost/RFPL-admin/public/admin/assets/libs/select2/dist/css/select2.min.css">



{{--<link rel="stylesheet" href="{{asset('admin/selectpicker-css/bootstrap-select.css')}}">
<script src="{{asset('admin/selectpicker-js/bootstrap-select.js')}}"></script>--}}

{{-- <link rel="stylesheet" href="https://www.giftbasketworldwide.com/css/admin/selectpicker/bootstrap-select.css">
<script src="https://www.giftbasketworldwide.com/js/admin/selectpicker/bootstrap-select.js"></script> --}}




<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">{{ strtoupper($websiteShortCode) }} Create Product</h4>
            <div class="ml-auto text-right">
                <a href="{{ route('admin.'.$websiteShortCode.'.product.list') }}" class="btn btn-outline-info">Back</a>
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
                <form action="{{route('admin.'.$websiteShortCode.'.product.add')}}" method="POST" class="form-horizontal" id="formadd" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <h4 class="card-title">Create Product</h4>

                        <div class="form-group m-t-20" class="selectpicker">
                            <label>Category<span>*</span></label>
                            <select class="select2 form-control m-t-20" name="categories_id[]" multiple="multiple" id="cat_id" style="width:100%; height: 36px;">
                                @foreach($catdata as $c)
                                    <option value="{{$c->id}}">{{$c->name}}</option>
                                @endforeach 
                            </select>
                            
                        </div>
                        <div class="form-group m-t-20">
                            <label>Product Title</label><span>*</span>
                            <input type="text" class="form-control" id="product_title" name="product_title" placeholder="Enter Product Title" required>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Product Description</label><span>*</span>
                            <textarea class="form-control" rows="5" id="product_description" name="product_description" placeholder="Enter Product Description" required></textarea>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Content</label>
                            <textarea class="form-control" rows="5" id="content" name="content" placeholder="Enter Content"></textarea>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Shipping</label>
                            <textarea class="form-control" rows="5" id="shipping" name="shipping" placeholder="Enter Shipping"></textarea>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Has Attribute?</label><br>
                            <span><input type="radio" name="has_attribute" class="pattribute" value="Y">Yes</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span><input type="radio" name="has_attribute" class="pattribute" value="N" checked>No</span>
                        </div>

                        <div class="form-group m-t-20 has_attribute_show" id="formfield" style="display:none">
                            <label>Create Attribute</label><span>*</span>
                            <div>
                                <input type="text" name="attr_title[0]" id="attr_title0" placeholder="Enter Attribute Title">&nbsp;&nbsp;&nbsp;
                                <input type="number" min=0 name="attr_price[]" id="attr_price0" placeholder="Enter Price">
                                <input type="number" min=0 name="attr_actual_price[]" id="attr_actual_price0" placeholder="Enter MRP">
                                <input type="button" class="btn btn-info" id="addrow" value="Add More Attribute" />
                            </div>

                            <div class="addField"></div>
                        </div>
                        
                        <div class="form-group m-t-20 no_attribute_show" >
                            <label>Product Price</label><span>*</span>
                            <input type="text" class="form-control" id="product_price" name="product_price" placeholder="Enter Product Price">
                        </div>
                        <div class="form-group m-t-20 actual_price">
                            <label>MRP</label>
                            <input type="number" class="form-control" id="product_actual_price" name="product_actual_price" value="0" min=0>
                            <span>Note: You can leave it blank if product price and actual price is same</span>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Delivery Delay Days</label><span>*</span>
                            <input type="text" class="form-control" id="delivery_delay_days" name="delivery_delay_days" placeholder="0" required>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Alt Keyword</label><span>*</span>
                            <input type="text" class="form-control" id="alt_keyword" name="alt_keyword" placeholder="Enter Alt Keyword" required>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Meta Title</label><span>*</span>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Enter Meta Title" required>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Meta Description</label><span>*</span>
                            <textarea class="form-control" rows="5" id="meta_description" name="meta_description" placeholder="Enter Meta Description" required></textarea>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Search Tag</label>
                            <input type="text" class="form-control" id="search_tag" name="search_tag" placeholder="Enter Search Tag">
                        </div>
                        <div class="form-group m-t-20">
                            <label>FNID</label><span>*</span>
                            <input type="text" class="form-control" id="fnid" name="fnid" placeholder="Enter FIND" required>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Extra Field</label><span>*</span>
                            <input type="text" class="form-control" id="extra_field" name="extra_field" placeholder="Enter Extra Value" required>
                        </div>
                    </div>
                    <div class="border-top">
                        <div class="card-body">
                            <input class="btn btn-primary" type="submit" value="Create">
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
</body>

<script src="http://localhost/RFPL-admin/public/admin/assets/libs/select2/dist/js/select2.full.min.js"></script>
<script src="http://localhost/RFPL-admin/public/admin/assets/libs/select2/dist/js/select2.min.js"></script>

<script type="text/javascript">

    $(".select2").select2();

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

    if ($("#product_description").length) {
        tinymce.init({
            selector: '#product_description',
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

    if ($("#content").length) {
        tinymce.init({
            selector: '#content',
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

    if ($("#shipping").length) {
        tinymce.init({
            selector: '#shipping',
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
       
    $('.pattribute').click(function(){
        if(this.value == 'Y'){
            $('.has_attribute_show').slideDown();
            $('.no_attribute_show').slideUp();
            $('.actual_price').slideUp();
            $("input[id*=attr_price]").attr("required",true);
            $("input[id*=attr_title]").attr("required",true);
            $("input[id*=product_price]").attr("required",false);
        }else{
            $("input[id*=product_price]").attr("required",true);
            $("input[id*=attr_price]").attr("required",false);
            $("input[id*=attr_title]").attr("required",false);
            $('.has_attribute_show').slideUp();
            $('.no_attribute_show').slideDown();
            $('.actual_price').slideDown();

        }
    });
  
    $(function(){
        $('select').selectpicker();
    })

    $(document).ready(function(){
    /* Add more functionality */
        var counter = 0;
        $("#addrow").on("click", function () {

            if(counter < 9){
                counter++;
                var cols = '';
                var newRow = $('<div class="row" style="margin-top: 5px;">');

                cols += '<div class="col-sm-4"><input required class="form-control" placeholder="Enter Attribute Title" id="attr_title'+counter+'" name="attr_title['+counter+']" type="text"></div>';
                cols += '<div class="col-sm-3"><input required class="form-control" placeholder="Enter Price" id="attr_price'+counter+'" name="attr_price['+counter+']" type="number" min=0></div>';
                cols += '<div class="col-sm-3"><input required class="form-control" placeholder="Enter MRP" id="attr_actual_price'+counter+'" name="attr_actual_price['+counter+']" type="number" min=0></div>';
                cols += '<div class="col-sm-2"><a class="deleteRow" href="javascript: void(0);"><i class="fas fa-trash-alt ibtnDel"></i></a></div>';

                newRow.append(cols);
                $(".addField").append(newRow);
            }else{
                alert("You can't add more than 8 attributes.");
            }

        });

        $(".row").on("click", ".ibtnDel", function (event) {
            $(this).closest(".row").remove();
            counter--;
        });

    });

</script>    

@endsection
