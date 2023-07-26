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
            <h4 class="page-title">{{ strtoupper($websiteShortCode) }} Create False URL</h4>
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
                <form action="{{route('admin.'.$websiteShortCode.'.falseurl.add')}}" method="POST" class="form-horizontal" id="formadd" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <h4 class="card-title">Create False URL</h4>

                        <div class="form-group m-t-20">
                            <label>False URL<span>*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">{!!$websiteUrl!!}</span>
                                </div>
                                <input type="text" class="form-control" id="falseurl" name="falseurl" placeholder="Enter Url" required>
                            </div>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Category<span>*</span></label>
                            <select id="category" name="category" class="form-control" required>
                                <!-- @foreach($catdata as $key => $category)
                                    <option value="{{$key}}">{{$category}}</option>
                                @endforeach -->
                                @foreach($catdata as $c)
                                    <option value="{{$c->id}}">{{$c->name}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                        <div class="form-group m-t-20">
                            <label>Image</label>
                            <input type="file" class="form-control" id="fimage" name="fimage"  >
                            <p>Please upload minimum 1500px X 400px image only</p>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Banner Alt</label>
                            <input type="text" class="form-control" id="baneeralt" name="banneralt" placeholder="Enter Banner ALT" required>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Banner Heading</label>
                            <input type="text" class="form-control" id="baneeheading" name="bannerheading" placeholder="Enter Banner Heading" required>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Content Top</label>
                            <textarea class="form-control" rows="5" id="falseurlcontenttop" name="falseurlcontenttop" placeholder="Enter Content Top" required></textarea>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Content Bottom</label>
                            <textarea class="form-control" rows="5" id="falseurlcontentbottom" name="falseurlcontentbottom" placeholder="Enter Content Bottom" required></textarea>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Product Sort Order<span>*</span></label>
                            <select id="productsordr"  name="productsorder" class="form-control" required>
                                <!-- @foreach($catdata as $key => $category)
                                    <option value="{{$key}}">{{$category}}</option>
                                @endforeach -->
                                @foreach($orderdata as $od)
                                    <option value="{{$od->id}}">{{$od->name}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                        <div class="form-group m-t-20">
                            <label>Meta Title</label>
                            <input type="text" class="form-control" id="falseurlmeta_title" name="falseurlmeta_title" placeholder="Enter Meta Title" required>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Meta Description</label>
                            <textarea class="form-control" rows="5" id="falseurlmeta_description" name="falseurlmeta_description" placeholder="Enter Meta Description" required></textarea>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Tagline</label>
                            <input type="text" class="form-control" id="falseurltagline" name="falseurltagline" placeholder="Enter Tagline" required>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Top Head</label>
                            <input type="text" class="form-control" id="falseurltophead" name="falseurltophead" placeholder="Enter Tophead" required>
                        </div>
                    </div>
                    <div class="border-top">
                        <div class="card-body">
                            <input class="btn btn-primary" type="submit" value="CREATE">
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
