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
            <h4 class="page-title">{{ strtoupper($websiteShortCode) }} CMS</h4>
            <div class="ml-auto text-right">
                <a href="{{ route('admin.'.$websiteShortCode.'.cms.list') }}" class="btn btn-outline-info">Back</a>
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
                <form action="{{route('admin.'.$websiteShortCode.'.cms.edit', [base64_encode($dataDetails->id)])}}" method="POST" class="form-horizontal" id="formadd"  >
                    @csrf
                    <div class="card-body">
                        <h4 class="card-title">Cms Edit</h4>
                        
                        <input type="hidden" name="formid" value="<?php echo $dataDetails->id; ?>">

                        <div class="form-group m-t-20">
                            <label>Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="{{$dataDetails->title}}" required>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Content</label>
                            <textarea class="form-control" rows="5" id="postcontent" name="content" placeholder="Enter Content" required>{{$dataDetails->content}}</textarea>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" placeholder="Enter Slug" value="{{$dataDetails->slug}}" readonly>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Enter Meta Title" value="{{$dataDetails->meta_title}}" required>
                        </div>
                        <div class="form-group m-t-20">
                            <label>Meta Description</label>
                            <textarea class="form-control" rows="5" id="meta_description" name="meta_description" placeholder="Enter Meta Description" required>{{$dataDetails->meta_description}}</textarea>
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

    /*Tinymce editor*/
    if ($("#postcontent").length) {
        tinymce.init({
            selector: '#postcontent',
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


</script>    

@endsection