@extends('layouts.admin.app')

@section('content')

<link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/extra-libs/multicheck/multicheck.css') }}">
<link href="{{ asset('admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">{{ strtoupper($websiteShortCode) }} CMS</h4>
            <div class="ml-auto text-right">
                <a href="{{ route('admin.'.$websiteShortCode.'.cms.add') }}" class="btn btn-success">ADD CMS</a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <h4 class="font-weight-light alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</h4>
                        @endif
                    @endforeach
                    <h5 class="card-title">CMS</h5>
                    @if(count($result) > 0)
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 5%"><b>No.</b></th>
                                    <th style="width: 25%"><b>Title</b></th>
                                    <th style="width: 35%"><b>Content</b></th>
                                    <th style="width: 20%"><b>Created Date</b></th>
                                    <th style="width: 6%"><b>Status</b></th>
                                    <th style="width: 9%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1; @endphp
                                @foreach($result as $key => $data)
                                <tr>
                                    <td>{{ $i + $key }}</td>
                                    <td>{{$data->title}}</td>
                                    <td>{!! substr(strip_tags($data->content), 0, 100) !!}...</td>
                                    <td>{{date('d-m-Y', strtotime($data->created_at))}}</td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="blockstatus" name="stat" id="stat" data-id="{{ base64_encode($data->id) }}" @if($data->is_block == 'N') checked @endif>
                                            <span class="slider roundsemi"></span>
                                        </label>
                                        <label id="status-info-{{ $data->id }}" class="status-stat"></label>
                                    </td>
                                    <td >
                                        <a href="{{ route('admin.'.$websiteShortCode.'.cms.edit', base64_encode($data->id).'?redirect='.urlencode($request->fullUrl())) }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a onclick="return confirm('Are you sure you want to delete the CMS?')" href="{{ route('admin.'.$websiteShortCode.'.cms.delete', base64_encode($data->id)) }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="card">No Data Found...</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



<script src="{{ asset('admin/assets/extra-libs/multicheck/datatable-checkbox-init.js') }}"></script>
<script src="{{ asset('admin/assets/extra-libs/multicheck/jquery.multicheck.js') }}"></script>
<script src="{{ asset('admin/assets/extra-libs/DataTables/datatables.min.js') }}"></script>
<script>
    /****************************************
     *       Basic Table                   *
     ****************************************/
    $('#zero_config').DataTable({
        "order": [[5, "desc"]] 
    });

    $(function(){
        setTimeout(function(){ $('.alert').hide(); }, 3000);
		var status, id;
		$('.blockstatus').change(function(){
			if(this.checked){
				status = 'Y';
			}else{
				status = 'N'
			}
			id = $(this).attr('data-id');
			var ajaxurl = "{{ route('admin.'.$websiteShortCode.'.cms.status') }}";	
			//alert(ajaxurl);
			$.ajax({
				type : 'POST',
				url : ajaxurl,
				data : { 'id' : id, 'status' : status },
				headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success : function(response){
					response = JSON.parse(response);
					//alert('status-info-'+id);
					if(response.status == 1){
						$('#status-info-'+response.id).html(response.event).fadeIn(100).delay(1000).fadeOut();;
					}
				},
				error : function(){
					$('.selected_records_data').html('<span class="error-msg text-danger">There was an unexpected error!! Please try again later.</span>');
				}
			});
		});
    })

    
</script>




@endsection