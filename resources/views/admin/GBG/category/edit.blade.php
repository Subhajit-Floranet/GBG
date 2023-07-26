@extends('layouts.admin.app')

@section('content')

{{-- <script type = "text/javascript" src = "https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.0/tinymce.min.js"></script>   --}}
{{-- <script src="{{ asset('admin/misc.js') }}"></script>
<script src="{{ asset('admin/tinymce.min.js') }}"></script> --}}

<script src="https://www.giftbasketworldwide.com/js/admin/misc.js"></script>
<script src="https://www.giftbasketworldwide.com/js/admin/tinymce.min.js"></script>

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">{{ strtoupper($websiteShortCode) }} CATEGORY</h4>
            <div class="ml-auto text-right">
                <a href="{{ route('admin.'.$websiteShortCode.'.category.list') }}" class="btn btn-outline-info">Back</a>
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
                <form action="{{route('admin.'.$websiteShortCode.'.category.edit', [base64_encode($dataDetails->id)])}}" method="POST" class="form-horizontal" id="formadd" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <h4 class="card-title">Category Edit</h4>
                        
                        <input type="hidden" name="formid" value="<?php echo $dataDetails->id; ?>">

                        <?php //die; ?>
                        
                        <div class="form-group m-t-20">
                            <label>Category Title<span>*</span></label>
                            <input type="text" class="form-control" id="ctitle" name="ctitle" value="{{$dataDetails->name}}" placeholder="Enter Category Title" required>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Category Page Head<span>*</span></label>
                            <input type="text" class="form-control" id="chead" name="chead" value="{{$dataDetails->page_head}}" placeholder="Enter Category Page Heading" required>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Image</label>
                            <input type="file" class="form-control" id="cimage" name="cimage">
                            <p>Please upload minimum 1920px X 308px image only</p>
                            @if ($dataDetails->image!='')
                                <img src="{{asset('uploads/banner/'.$dataDetails->image) }}" style="width: 30%; height: 100px">&nbsp;&nbsp;<a href="{{route('admin.'.$websiteShortCode.'.category.deleteimage', [base64_encode($dataDetails->id)])}}" class="btn btn-danger">Delete Image</a>
                            @endif
                        </div>
                        <div class="form-group m-t-20">
                            <label>Banner Heading</label>
                            <input type="text" class="form-control" id="cbannerhead" name="cbannerhead" value="{{$dataDetails->banner_heading}}" placeholder="Enter Banner Heading" required>
                        </div>
                        
                        <div class="form-group m-t-20">
                            <label>Menu Head Only<span>*</span></label>
                            <p><input type="radio" class="form-control2" id="cmenuhead" name="cmenuhead" {{$dataDetails->menu_head_only == 'N' ? 'checked' : ''}} value="N" >No</p>
                            <p><input type="radio" class="form-control2" id="cmenuhead" name="cmenuhead" {{$dataDetails->menu_head_only == 'Y' ? 'checked' : ''}} value="Y" >Yes</p>
                        </div>
                       
                        <input type="hidden" name="cattype" id="cattype" value="{{$dataDetails->cat_section}}">
                       
                        <div class="form-group m-t-20">
                            <label>Category Type<span>*</span></label>
                            <p><input type="radio" class="form-control2 ctype"  name="ctype" {{$dataDetails->cat_section == 'N' ? 'checked' : ''}} value="N" checked required>Category/Occasion Related</p>
                            <p><input type="radio" class="form-control2 ctype" name="ctype" {{$dataDetails->cat_section == 'P' ? 'checked' : ''}}  value="P" required>Price Related</p>
                        </div>


                        
                        <div class="form-group m-t-20 price-range" style="display:none">
                            <label>Price Details(Please put value USD currency)</label>
                            <p><input type="text" id="from_price" name="from_price" placeholder="From Price" value="@if($pricedtl != ''){{$pricedtl->from_price}}@endif ">&nbsp;&nbsp; 
                            <input type="text" id="to_price" name="to_price" placeholder="To Price" value="@if($pricedtl != ''){{$pricedtl->to_price}}@endif">&nbsp;&nbsp;
                                <select id="equation" name="equation">
                                       <option value="">Select Option</option>
                                       <option value="between" @if (($pricedtl != '') and ($pricedtl->equation=="between")) selected @endif>Between</option>
                                       <option value="greater" @if (($pricedtl != '') and ($pricedtl->equation=="greater")) selected @endif>Greater</option>
                                       <option value="less"    @if (($pricedtl != '') and ($pricedtl->equation=="less")) selected @endif>Less</option>
                                </select>
                            </p>
                         </div>



                        <div class="form-group m-t-20">
                            <label>Content Top</label>
                            <textarea class="form-control" rows="5" id="ccontenttop" name="ccontenttop" placeholder="Enter Content Top" required>{{$dataDetails->content_top}}</textarea>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Content Bottom</label>
                            <textarea class="form-control" rows="5" id="ccontentbottom" name="ccontentbottom" placeholder="Enter Content Bottom" required>{{$dataDetails->content_bottom}}</textarea>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Meta Title</label>
                            <input type="text" class="form-control" id="cmeta_title" name="cmeta_title" value="{{$dataDetails->meta_title}}" placeholder="Enter Meta Title" required>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Meta Description</label>
                            <textarea class="form-control" rows="5" id="cmeta_description" name="cmeta_description" placeholder="Enter Meta Description" required>{{$dataDetails->meta_description}}</textarea>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Tagline</label>
                            <input type="text" class="form-control" id="ctagline" name="ctagline" value="{{$dataDetails->tag_line}}" placeholder="Enter Tagline" required>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Top Head</label>
                            <input type="text" class="form-control" id="ctophead" name="ctophead" value="{{$dataDetails->tophead}}" placeholder="Enter Tophead" required>
                        </div>
                    </div>
                    <div class="border-top">
                        <div class="card-body">
                            <input class="btn btn-primary" type="submit" value="UPDATE">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

    /Tinymce editor/
    if ($("#ccontenttop").length) {
        tinymce.init({
            selector: '#ccontenttop',
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

    if ($("#ccontentbottom").length) {
        tinymce.init({
            selector: '#ccontentbottom',
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

    if($('#cattype').val() == 'P'){
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


</script>     

@endsection