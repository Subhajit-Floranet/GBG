@extends('layouts.site.app')

@section('content')


<section class="dashboard-container ">
	<div class="dashboard-menu">
		<ul>
			<li class="menus active" data-rel="tab1">Profile</li>
			<li class="menus" data-rel="tab2">Password</li>
			<li class="menus" data-rel="tab3">Address</li>
			<li class="menus" data-rel="tab4">Orders</li>
		</ul>
	</div>
	<div class="dashboard-content">
		<div class="profile-container profile-active " >
			<div class="profile-head">My Profile</div>
			<div class="profile-picture box-design">
				<div class="profile-img" ><i class="fa-solid fa-user"></i></div>
				<div class="profile-det">
					<div>{{ $user_data->name }}</div>
				</div>
			</div>
			<div class=" box-design">
				<div class="personal-info">
					<div>Personal Information</div>
					<div class="edit-btn" id="author_bio_wrap_toggle"><i class="fa-solid fa-pen-to-square"></i></div>
				</div>
				<div class="info-container marg-top">
					<div class="profile-title">Name :</div>
					<div class="profile-data" id="edit_name">{{ $user_data->name }}</div>
				</div>
				<div class="info-container">
					<div class="profile-title">Birthday :</div>
					<div class="profile-data" id="edit_dob">@if ($user_data->dob != '0000-00-00' && $user_data->dob != null){{ date('M j, Y',strtotime($user_data->dob)) }} @else {{ 'N/A' }}@endif</div>
				</div>
				<div class="info-container">
					<div class="profile-title">Mobile :</div>
					<div class="profile-data" id="edit_mobile">@if ($user_data->mobile){{ $user_data->mobile }} @else {{ 'N/A' }}@endif</div>
				</div>
				<div class="info-container">
					<div class="profile-title">Email :</div>
					<div class="profile-data" id="edit_mobile">{{ $user_data->email }}</div>
				</div>
			</div>
			<div class=" box-design" id="author_bio_wrap" style="display: none;">
						<div class="col-lg-6">
							<div class="user-interface">
							{{Form::open(['files' => true, 'id' => 'update_profile_information'])}}
							<fieldset>
								<div class="row" id="loginwithoutid">
									<div class="form-group">
										<label>Name :</label>
										{!! Form::text('name', $user_data->name, array('required', 'class'=>'form-control','placeholder'=>"Name", 'id' => 'name')) !!}
									</div>
									<div class="form-group">
										<label>Birthday :</label>
										<!-- <input type="date" placeholder="Birthday" class="form-control"> -->
										@php if($user_data->dob != '0000-00-00' && $user_data->dob != null)$dob = date('Y/m/d', strtotime($user_data->dob)); else $dob = ''; @endphp
                                        {!! Form::text('dob', $dob, array('required', 'class'=>'form-control', 'id' => 'dob', 'autocomplete' => 'off', 'data-provide' => 'datepicker', 'data-date-format' => 'yyyy/mm/dd', 'data-date-end-date' => '0d' )) !!}
									</div>
									<div class="form-group">
										<label>Mobile No. :</label>
										{!! Form::text('mobile', $user_data->mobile, array('required', 'class'=>'form-control', 'id' => 'mobile')) !!}</span>
									</div>
									<div class="form-group">
										<label>Email ID :</label>
										<input type="text" name="" class="form-control" value="{{ $user_data->email }}" readonly />
									</div>
									<div class="d-flex justify-content-around button-container">
										<button type="submit" class="btn button-nfjp">Save</button>
                                        <button type="button" id="cancel_edit_profile" class="btn button-nfjp-cancel">Cancel</button>
									</div>
								</div>
							</fieldset>
							</form>
							</form>
							</div>
						</div>
					</div>
		</div>
		<div class="profile-container" >
			<div class="profile-head">Change Password</div>		
			<div class="marg-top box-design">
				<div class="col-lg-6">
					<div class="user-interface">
					{{Form::open(['files' => true, 'id' => 'change_password_form'])}}
					<fieldset>
						<div class="row" id="loginwithoutid">
							<div class="form-group">
								<label>New Password :</label>
								{!! Form::password('password', array('required','placeholder'=>"xxxxxx", 'class'=>'form-control', 'id' => 'cp_password')) !!}
							</div>
							<div class="form-group">
								<label>Retype New Password :</label>
								{!! Form::password('confirm_password', array('required','placeholder'=>"xxxxxx", 'class'=>'form-control', 'id' => 'cp_confirm_password')) !!}
							</div>
							<div class="row rows button-container">
								<button type="submit" class="btn button-nfjp">Change Password</button>
							</div>
						</div>
					</fieldset>
					{{ Form::close() }}

					<div class="row"><div class="edit-pass-msg"></div></div>
					</div>
				</div>
			</div>	
		</div>

		<div class="profile-container" >
			<div class="profile-head">Address List</div>
			<div class="marg-top box-design">
				<div class="col-lg-6 my-address-container" id="address_data_div"></div>
					
				<div class="col-lg-6 new-address-add-open" id="new_address_add_form" style="display:none;">
					<h3>Add New Address</h3>
					<div class="user-interface">
						{{ Form::open(['files' => true, 'class'=>"form-horizontal", 'route' => 'users.add-address', 'id' => 'add_address_form']) }}
						<fieldset>
							<div class="row" id="loginwithoutid">
								<div class="form-group">
									<label>Name :</label>
									{!! Form::text('name', $user_data->name, array('required','placeholder'=>"Name", 'class'=>'form-control', 'id' => 'name_add')) !!}
								</div>
								<div class="form-group">
									<label>Address :</label>
									{!! Form::text('address', null, array('required', 'placeholder'=>"Address*", 'class'=>'form-control', 'id' => 'address_add', 'autocomplete' => 'off')) !!}
								</div>
								<div class="form-group">
									<label>Pincode :</label>
									{!! Form::text('pincode', null, array('required', 'id'=>"pincode_add",'placeholder'=>"Pincode", 'class'=>'form-control', 'autocomplete' => 'off')) !!}
								</div>
								<div class="form-group">
									<label>State/Province :</label>
									{!! Form::text('state_name', null, array('required', 'placeholder' => 'State/Province', 'class'=>'form-control', 'id' => 'state_id_add', 'autocomplete' => 'off')) !!}
								</div>
								<div class="form-group">
									<label>Country :</label>
									<select id="country_id_add" class="form-control selectpicker" name="country_id" required="true">
										<option value="">Select</option>
										<?php foreach ($country_list as $key => $value) { ?>
											<option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
										<?php } ?>
									</select>
								</div>
								<div class="form-group">
									<label>City :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
									@php $cityList = App\Http\Helper::getCities(80); @endphp
									<select id="city_id_add" class="form-control selectpicker" name="city_id" required="true">
										<option value="">Select</option>
										@foreach($cityList as $city)
											<option value="<?php echo $city->id;?>"><?php echo $city->name;?></option>
										@endforeach
									</select>
								</div>
								<div class="form-group">
									<label>Phone No :</label>
									{!! Form::text('mobile', $user_data->mobile, array('required', 'class'=>'form-control','placeholder'=>"Mobile", 'id' => 'mobile_add', 'autocomplete' => 'off')) !!}
								</div>
								<div class="d-flex justify-content-around button-container">
									
										<button type="submit" class="btn button-nfjp new-address-add-close">Save</button>
										<button type="button" id="cancel_save_address" class="btn button-nfjp-cancel">Cancel</button>
								</div>
								
							</div>
						</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="profile-container" >
			<div id="all_orders"></div>
		</div>
	</div>
</section>

<!---old dashboard------>
<section class="dashboard" style="display:none;">
    <div class="dashboard-body">
        <h2>Welcome! {{ $user_data->name }}</h2>

        <div class="cols col-12">
		
			<!---------------------Dashboard Start-------------------------->
			<ul class="tabss">
			<li class="activess" rel="tab1"><i class="fa-solid fa-user"></i>Personal Information</li>
			<li rel="tab2"><i class="fa-solid fa-key"></i>Change Password</li>
			<li rel="tab3"><i class="fa-solid fa-address-book"></i>My Address</li>
			<li rel="tab4"><i class="fa-solid fa-gift"></i>My Orders</li>
			</ul>
			<div class="tab_container">
				<h3 class="d_activess tab_drawer_heading" rel="tab1">Personal Information</h3>
				<div id="tab1" class="tab_content">
					<h2>Edit Your Acount</h2>					
					<div class="row rows myp-info">
						<div class="col-12 col-sm-3 col-md-3 col-lg-2">Name :</div>
						<div class="col-12 col-sm-9 col-md-9 col-lg-10" id="edit_name">{{ $user_data->name }}</div>
					</div>
					<div class="row rows myp-info">
						<div class="col-12 col-sm-3 col-md-3 col-lg-2">Birthday :</div>
						<div class="col-12 col-sm-9 col-md-9 col-lg-10" id="edit_dob">@if ($user_data->dob != '0000-00-00' && $user_data->dob != null){{ date('M j, Y',strtotime($user_data->dob)) }} @else {{ 'N/A' }}@endif</div>
					</div>
					<div class="row rows myp-info">
						<div class="col-12 col-sm-3 col-md-3 col-lg-2">Mobile :</div>
						<div class="col-12 col-sm-9 col-md-9 col-lg-10" id="edit_mobile">@if ($user_data->mobile){{ $user_data->mobile }} @else {{ 'N/A' }}@endif</div>
					</div>
					<div class="row rows myp-info">
						<div class="col-12 col-sm-3 col-md-3 col-lg-2">Email :</div>
						<div class="col-12 col-sm-9 col-md-9 col-lg-10">{{ $user_data->email }}</div>
					</div>
					<div class="row rows button-container">
						<!-- <button class="btn button-nfjp" id="author_bio_wrap_toggle">Edit Your Details</button> -->
					</div>

					<div class="row"><div class="edit-form-msg"></div></div>
					
					<div class="row rows my-info-editor" id="author_bio_wrap" style="display: none;">
						<div class="col-lg-6">
							<div class="user-interface">
							{{Form::open(['files' => true, 'id' => 'update_profile_information'])}}
							<fieldset>
								<div class="row" id="loginwithoutid">
									<div class="form-group">
										<label>Name :</label>
										{!! Form::text('name', $user_data->name, array('required', 'class'=>'form-control','placeholder'=>"Name", 'id' => 'name')) !!}
									</div>
									<div class="form-group">
										<label>Birthday :</label>
										<!-- <input type="date" placeholder="Birthday" class="form-control"> -->
										@php if($user_data->dob != '0000-00-00' && $user_data->dob != null)$dob = date('Y/m/d', strtotime($user_data->dob)); else $dob = ''; @endphp
                                        {!! Form::text('dob', $dob, array('required', 'class'=>'form-control', 'id' => 'dob', 'autocomplete' => 'off', 'data-provide' => 'datepicker', 'data-date-format' => 'yyyy/mm/dd', 'data-date-end-date' => '0d' )) !!}
									</div>
									<div class="form-group">
										<label>Mobile No. :</label>
										{!! Form::text('mobile', $user_data->mobile, array('required', 'class'=>'form-control', 'id' => 'mobile')) !!}</span>
									</div>
									<div class="form-group">
										<label>Email ID :</label>
										<input type="text" name="" class="form-control" value="{{ $user_data->email }}" readonly />
									</div>
									<div class="d-flex justify-content-around button-container">
										<button type="submit" class="btn button-nfjp">Save</button>
                                        <button type="button" id="cancel_edit_profile" class="btn button-nfjp-cancel">Cancel</button>
									</div>
								</div>
							</fieldset>
							</form>
							</form>
							</div>
						</div>
					</div>
				</div>

				<h3 class="tab_drawer_heading" rel="tab2">Change Password</h3>
				<div id="tab2" class="tab_content">
					<h2>Change Password</h2>		
					<div class="row rows">
						<div class="col-lg-6">
							<div class="user-interface">
							{{Form::open(['files' => true, 'id' => 'change_password_form'])}}
							<fieldset>
								<div class="row" id="loginwithoutid">
									<div class="form-group">
										<label>New Password :</label>
										{!! Form::password('password', array('required','placeholder'=>"xxxxxx", 'class'=>'form-control', 'id' => 'cp_password')) !!}
									</div>
									<div class="form-group">
										<label>Retype New Password :</label>
										{!! Form::password('confirm_password', array('required','placeholder'=>"xxxxxx", 'class'=>'form-control', 'id' => 'cp_confirm_password')) !!}
									</div>
									<div class="row rows button-container">
										<button type="submit" class="btn button-nfjp">Change Password</button>
									</div>
								</div>
							</fieldset>
							{{ Form::close() }}

                            <div class="row"><div class="edit-pass-msg"></div></div>
							</div>
						</div>
					</div>	
				</div>

				<h3 class="tab_drawer_heading" rel="tab3">My Address</h3>
				<div id="tab3" class="tab_content">
					<h2>My Addresses</h2>
					{{--<div class="col-lg-6 my-address-container">
						<address class="my-address">
							<p class="name">Mr. Lorium Ipsum Dummy Text</p>
							<div class="row myp-info">
								<div class="col-12 col-sm-3 col-md-3 col-lg-2">Address :</div>
								<div class="col-12 col-sm-9 col-md-9 col-lg-10">Lorem Ipsum is simply dummy text of the printing and typesetting industry</div>
							</div>
							<div class="row myp-info">
								<div class="col-12 col-sm-3 col-md-3 col-lg-2">Landmark :</div>
								<div class="col-12 col-sm-9 col-md-9 col-lg-10">Lorem Ipsum is simply dummy text of the printing and typesetting industry</div>
							</div>
							<div class="row myp-info">
								<div class="col-12 col-sm-3 col-md-3 col-lg-2">Mobile :</div>
								<div class="col-12 col-sm-9 col-md-9 col-lg-10">9876543210</div>
							</div>
							<i><button class="address-editor-open"><em class="material-icons-outlined text-success">edit_note</em></button> <button><em class="material-icons-outlined text-danger">delete_forever</em></button></i>
						</address>
						
						<address class="my-address">
							<p class="name">Mr. Lorium Ipsum Dummy Text</p>
							<div class="row myp-info">
								<div class="col-12 col-sm-3 col-md-3 col-lg-2">Address :</div>
								<div class="col-12 col-sm-9 col-md-9 col-lg-10">Lorem Ipsum is simply dummy text of the printing and typesetting industry</div>
							</div>
							<div class="row myp-info">
								<div class="col-12 col-sm-3 col-md-3 col-lg-2">Landmark :</div>
								<div class="col-12 col-sm-9 col-md-9 col-lg-10">Lorem Ipsum is simply dummy text of the printing and typesetting industry</div>
							</div>
							<div class="row myp-info">
								<div class="col-12 col-sm-3 col-md-3 col-lg-2">Mobile :</div>
								<div class="col-12 col-sm-9 col-md-9 col-lg-10">9876543210</div>
							</div>
							<i><button class="address-editor-open"><em class="material-icons-outlined text-success">edit_note</em></button> <button><em class="material-icons-outlined text-danger">delete_forever</em></button></i>
						</address>
						
						<address class="my-address">
							<p class="name">Mr. Lorium Ipsum Dummy Text</p>
							<div class="row myp-info">
								<div class="col-12 col-sm-3 col-md-3 col-lg-2">Address :</div>
								<div class="col-12 col-sm-9 col-md-9 col-lg-10">Lorem Ipsum is simply dummy text of the printing and typesetting industry</div>
							</div>
							<div class="row myp-info">
								<div class="col-12 col-sm-3 col-md-3 col-lg-2">Landmark :</div>
								<div class="col-12 col-sm-9 col-md-9 col-lg-10">Lorem Ipsum is simply dummy text of the printing and typesetting industry</div>
							</div>
							<div class="row myp-info">
								<div class="col-12 col-sm-3 col-md-3 col-lg-2">Mobile :</div>
								<div class="col-12 col-sm-9 col-md-9 col-lg-10">9876543210</div>
							</div>
							<i><button class="address-editor-open"><em class="material-icons-outlined text-success">edit_note</em></button> <button><em class="material-icons-outlined text-danger">delete_forever</em></button></i>
						</address>
					</div>
					
					<div class="col-lg-6 my-address-editor">
						<h3>Edit Your Address</h3>
						<div class="user-interface">
							<form onSubmit="return false">
							<fieldset>
								<div class="row" id="loginwithoutid">
									<div class="form-group">
										<label>Name :</label>
										<input type="text" class="form-control">
									</div>
									<div class="form-group">
										<label>Address :</label>
										<input type="date" class="form-control">
									</div>
									<div class="form-group">
										<label>Pincode :</label>
										<input type="text" class="form-control">
									</div>
									<div class="form-group">
										<label>State/Province :</label>
										<input type="text" class="form-control">
									</div>
									<div class="form-group">
										<label>Country :</label>
										<select class="form-control">
											<option>Choose Country</option>
											<option>USA</option>
											<option>Germany</option>
											<option>Japan</option>
											<option>Hongkong</option>
											<option>Singapore</option>
										</select>
									</div>
									<div class="form-group">
										<label>City :</label>
										<select class="form-control">
											<option>Choose City</option>
											<option>Kolkata</option>
											<option>Mumbai</option>
											<option>Chennai</option>
											<option>Bangalore</option>
											<option>Delhi</option>
										</select>
									</div>
									<div class="form-group">
										<label>Phone No :</label>
										<input type="text" class="form-control">
									</div>
									<div class="d-flex justify-content-around button-container">
										<button class="btn btns btns-primary my-address-editor-close">Save</button>
									</div>
								</div>
							</fieldset>
							</form>
						</div>
						<hr>
					</div>


					
					<div class="my-2">
						<button class="btn btns btns-primary new-address-add">Add New Address</button>
					</div>--}}

					<div class="col-lg-6 my-address-container" id="address_data_div"></div>
					
					<div class="col-lg-6 new-address-add-open" id="new_address_add_form" style="display:none;">
						<h3>Add New Address</h3>
						<div class="user-interface">
							{{ Form::open(['files' => true, 'class'=>"form-horizontal", 'route' => 'users.add-address', 'id' => 'add_address_form']) }}
							<fieldset>
								<div class="row" id="loginwithoutid">
									<div class="form-group">
										<label>Name :</label>
										{!! Form::text('name', $user_data->name, array('required','placeholder'=>"Name", 'class'=>'form-control', 'id' => 'name_add')) !!}
									</div>
									<div class="form-group">
										<label>Address :</label>
										{!! Form::text('address', null, array('required', 'placeholder'=>"Address*", 'class'=>'form-control', 'id' => 'address_add', 'autocomplete' => 'off')) !!}
									</div>
									<div class="form-group">
										<label>Pincode :</label>
										{!! Form::text('pincode', null, array('required', 'id'=>"pincode_add",'placeholder'=>"Pincode", 'class'=>'form-control', 'autocomplete' => 'off')) !!}
									</div>
									<div class="form-group">
										<label>State/Province :</label>
										{!! Form::text('state_name', null, array('required', 'placeholder' => 'State/Province', 'class'=>'form-control', 'id' => 'state_id_add', 'autocomplete' => 'off')) !!}
									</div>
									<div class="form-group">
										<label>Country :</label>
										<select id="country_id_add" class="form-control selectpicker" name="country_id" required="true">
                                            <option value="">Select</option>
                                            <?php foreach ($country_list as $key => $value) { ?>
                                                <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                                            <?php } ?>
                                        </select>
									</div>
									<div class="form-group">
										<label>City :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
										@php $cityList = App\Http\Helper::getCities(80); @endphp
										<select id="city_id_add" class="form-control selectpicker" name="city_id" required="true">
                                            <option value="">Select</option>
                                            @foreach($cityList as $city)
                                            	<option value="<?php echo $city->id;?>"><?php echo $city->name;?></option>
                                            @endforeach
                                        </select>
									</div>
									<div class="form-group">
										<label>Phone No :</label>
										{!! Form::text('mobile', $user_data->mobile, array('required', 'class'=>'form-control','placeholder'=>"Mobile", 'id' => 'mobile_add', 'autocomplete' => 'off')) !!}
									</div>
									<div class="d-flex justify-content-around button-container">
										
										 <button type="submit" class="btn button-nfjp new-address-add-close">Save</button>
                                         <button type="button" id="cancel_save_address" class="btn button-nfjp-cancel">Cancel</button>
									</div>
									
								</div>
							</fieldset>
							</form>
						</div>
					</div>
					
					
					
				</div>

				<h3 class="tab_drawer_heading" rel="tab4">My Orders</h3>
				<div id="tab4" class="tab_content">
					<h2>My Orders</h2>
					<div id="all_orders"></div>
					<!-- <section class="my-order">
						<span class="row">
							<div class="my-order-pldate"><em>Order Placed :</em>29th. Aug Sat, 2020</div>
							<div class="my-order-dwnld order-download"><em>Oeder No. : ABCD 1234 ABCD 1234</em> <a href="javascript:void(0)" class="pending">Pending</a>  | <a href=""><i class="material-icons-outlined">file_download</i></a> </div>
						</span>
						<div class="d-flex my-order-display my-order-display-bg">
							<div class="itm-pic align-self-center">Image</div>
							<div class="itm-desc align-self-center">Description</div>
							<div class="itm-price align-self-center">Price</div>
							<div class="itm-price align-self-center">Delivery Charge</div>
							<div class="itm-price align-self-center">Delivery Date</div>
						</div>
						<div class="d-flex my-order-display">
							<div class="itm-pic align-self-center"><img src="images/item4.jpg"></div>
							<div class="itm-desc align-self-center"><p class="name">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
							<div class="itm-price align-self-center mo-pr"><i>USD 1400.00</i></div>
							<div class="itm-price align-self-center mo-dc"><i>USD 250.00</i></div>
							<div class="itm-price align-self-center mo-dd"><i>31st. August, 2020</i></div>
						</div>
						<span class="bottom row rows">
							<div class="col-md-9 align-self-center mo-address"><em class="single-row">Ship To : </em> Mr. Lorem Ipsum dummy text<br>123 Ipsum Road. Simply dummy text of the printing</div>
							<div class="col-md-3 align-self-center mo-total"><em class="single-row">Order Total : </em> USD 1650.00</div>
						</span>
					</section>
					
					<section class="my-order">
						<span class="row rows">
							<div class="col-md-9 align-self-center"><em>Order Placed :</em>29th. Aug Sat, 2020</div>
							<div class="col-md-3 align-self-center order-download"><em>Oeder No. : ABCD 1234 ABCD 1234</em> <a href="" class="delivered"><i class="material-icons-outlined">description</i> Delivered</a> | <a href=""><i class="material-icons-outlined">file_download</i></a></div>
						</span>
						<div class="d-flex my-order-display my-order-display-bg">
							<div class="itm-pic align-self-center">Image</div>
							<div class="itm-desc align-self-center">Description</div>
							<div class="itm-price align-self-center">Price</div>
							<div class="itm-price align-self-center">Delivery Charge</div>
							<div class="itm-price align-self-center">Delivery Date</div>
						</div>
						<div class="d-flex my-order-display">
							<div class="itm-pic align-self-center"><img src="images/item4.jpg"></div>
							<div class="itm-desc align-self-center"><p class="name">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
							<div class="itm-price align-self-center mo-pr"><i>USD 1400.00</i></div>
							<div class="itm-price align-self-center mo-dc"><i>USD 250.00</i></div>
							<div class="itm-price align-self-center mo-dd"><i>31st. August, 2020</i></div>
						</div>
						<hr>
						<div class="d-flex my-order-display">
							<div class="itm-pic align-self-center"><img src="images/item4.jpg"></div>
							<div class="itm-desc align-self-center"><p class="name">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
							<div class="itm-price align-self-center mo-pr"><i>USD 1400.00</i></div>
							<div class="itm-price align-self-center mo-dc"><i>USD 250.00</i></div>
							<div class="itm-price align-self-center mo-dd"><i>31st. August, 2020</i></div>
						</div>
						<span class="bottom row rows">
							<div class="col-md-9 align-self-center"><em class="single-row">Ship To : </em> Mr. Lorem Ipsum dummy text</div>
							<div class="col-md-3 align-self-center mo-total"><em class="single-row">Order Total : </em> USD 1650.00</div>
						</span>
					</section> -->
				</div>
				
				
			</div>
			<!---------------------Dashboard End---------------------------->
		
		</div>

    </div>
</section>
<!---old dashboard------>


<!---new script-->
<script>
jQuery(function($){
  $('.menus').click(function(){
	var activeTab = $(this).data("rel");
        //alert(activeTab);

		if(activeTab == 'tab3'){
            $('#all_addresses').addClass('loading');
            $('#address_data_div').show();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route("users.myAddresses") }}',
                method: 'POST',
                dataType: 'HTML',
                success: function(response_address) {
                    setTimeout(function(){
                        $('#address_data_div').html(response_address);
                        $('#all_addresses').removeClass('loading');
                    }, 500);
                }
            });
        }	
		else if(activeTab == 'tab4'){
            $('#all_orders').addClass('loading');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route("users.my-orders") }}',
                method: 'POST',
                dataType: 'HTML',
                success: function(response_orders) {
                    setTimeout(function(){
                        $('#all_orders').html(response_orders);
                        $('#all_orders').removeClass('loading');
                    }, 500);
                }
            });
        }
    $('.active').removeClass('active');
    $(this).addClass('active');
    $('.profile-active').removeClass('profile-active');
    const index = $(this).index();
    $('.profile-container').eq(index).addClass('profile-active');
	});

	
        // //For address tab click
        
  });


</script>
<!---new script-->









<script language="javascript">

// $(".tab_drawer_heading").click(function(){
//         var activeTab = $(this).attr("rel");
//         //alert(activeTab);
//         //For address tab click
//         if(activeTab == 'tab3'){
//             $('#all_addresses').addClass('loading');
//             $('#address_data_div').show();
//             $.ajaxSetup({
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 }
//             });
//             $.ajax({
//                 url: '{{ route("users.myAddresses") }}',
//                 method: 'POST',
//                 dataType: 'HTML',
//                 success: function(response_address) {
//                     setTimeout(function(){
//                         $('#address_data_div').html(response_address);
//                         $('#all_addresses').removeClass('loading');
//                     }, 500);
//                 }
//             });
//         }
//         //For my orders tab click
// 	    else if(activeTab == 'tab4'){
//             $('#all_orders').addClass('loading');
//             $.ajaxSetup({
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 }
//             });
//             $.ajax({
//                 url: '{{ route("users.my-orders") }}',
//                 method: 'POST',
//                 dataType: 'HTML',
//                 success: function(response_orders) {
//                     setTimeout(function(){
//                         $('#all_orders').html(response_orders);
//                         $('#all_orders').removeClass('loading');
//                     }, 500);
//                 }
//             });
//         }
        
//     })

// $(".tab_content").hide();
// $(".tab_content:first").show();
// $("ul.tabss li").click(function () {
//   $(".tab_content").hide();
//   var activessTab = $(this).attr("rel");
//   //alert(activessTab);
//   //For address tab click
//     if(activessTab == 'tab3'){
//         $('#all_addresses').addClass('loading');
//         $('#address_data_div').show();
//         $.ajaxSetup({
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             }
//         });
//         $.ajax({
//             url: '{{ route("users.myAddresses") }}',
//             method: 'POST',
//             dataType: 'HTML',
//             success: function(response_address) {
//                 setTimeout(function(){
//                     $('#address_data_div').html(response_address);
//                     $('#all_addresses').removeClass('loading');
//                 }, 500);
//             }
//         });
//     }
//     //For my orders tab click
//     else if(activessTab == 'tab4'){
//         $('#all_orders').addClass('loading');
//         $.ajaxSetup({
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             }
//         });
//         $.ajax({
//             url: '{{ route("users.my-orders") }}',
//             method: 'POST',
//             dataType: 'HTML',
//             success: function(response_orders) {
//                 setTimeout(function(){
//                     $('#all_orders').html(response_orders);
//                     $('#all_orders').removeClass('loading');
//                 }, 500);
//             }
//         });
//     }


//   $("#" + activessTab).fadeIn();

//   $("ul.tabss li").removeClass("activess");
//   $(this).addClass("activess");

//   $(".tab_drawer_heading").removeClass("d_activess");
//   $(".tab_drawer_heading[rel^='" + activessTab + "']").addClass("d_activess");
// });

// $(".tab_drawer_heading").click(function () {
//   $(".tab_content").hide();
//   //alert("hello");
//   var d_activessTab = $(this).attr("rel");
//   $("#" + d_activessTab).fadeIn();
//   $(".tab_drawer_heading").removeClass("d_activess");
//   $(this).addClass("d_activess");
//   $("ul.tabss li").removeClass("activess");
//   $("ul.tabss li[rel^='" + d_activessTab + "']").addClass("activess");
// });
// $('ul.tabss li').last().addClass("tab_last");









// $(".my-info-editor").hide();
// $(".my-info").click(function() {
//   $(".my-info-editor").toggle("slow");
// });
// $(".new-address-add-open").hide();
// $(".new-address-add").click(function() {
//   $(".new-address-add-open").show("slow");
// });
// $(".new-address-add-close").click(function() {
//   $(".new-address-add-open").hide("slow");
// });
// $(".my-address-editor").hide();
// $(".address-editor-open").click(function() {
//   $(".my-address-editor").show("slow");
//   $(".my-address-container").hide();
// });
// $(".my-address-editor-close").click(function() {
//   $(".my-address-editor").hide("slow");
//   $(".my-address-container").show("slow");
// });


jQuery(document).ready(function($){	 
 	$("#author_bio_wrap_toggle").click(function(){	
		$("#author_bio_wrap").slideToggle( "slow");	
	 	if ($("#author_bio_wrap_toggle").text() == "Edit Your Details"){	
			// $("#author_bio_wrap_toggle").html("Edit Your Details")
	 	}else{	
			// $("#author_bio_wrap_toggle").text("Edit Your Details")
	 	}	
 	});
});

$(function () {
		$("#dob").datepicker({ 
		autoclose: true, 
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0",
        maxDate: 0,
		todayHighlight: true
 	}).datepicker('update');
});

$('#cancel_edit_profile').on('click', function(){
    $('#author_bio_wrap_toggle').trigger('click');
});

/* Update Profile Section */
$("#update_profile_information").validate({
    rules: {
        name: {
            required: true,
        },
        dob: {
            required: true
        },
        mobile: {
            required: true
        }
    },
    submitHandler: function (form) {
        $('#personalinfo').addClass('loading');
        $('.edit-form-msg').show();
        $('.edit-form-msg').html('');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ route("users.editPersonalInformation") }}',
            method: 'POST',
            data: {
                name: $('#name').val(),
                mobile: $('#mobile').val(),
                dob: $('#dob').val()
            },
            success: function(data){
                setTimeout(function(){
                    $('#personalinfo').removeClass('loading');
                    if(data.success){
                        $('#author_bio_wrap').hide(500);                            
                        setTimeout(function() {$('.edit-form-msg').fadeOut('slow');}, 2000);
                        $('.edit-form-msg').append('<p class="alert alert-success">'+data.success+'</p>');
                        $('#edit_name').html(data.name);
                        $('#edit_dob').html(data.dob);
                        $('#edit_mobile').html(data.mobile);
                    }else if(data.errors){
                        $('#author_bio_wrap').hide(500);                            
                        setTimeout(function() {$('.edit-form-msg').fadeOut('slow');}, 2000);
                        $('.edit-form-msg').append('<p class="alert alert-error">'+data.errors+'</p>');
                    }
                }, 1000);
            }
        });
        return false;
    }
});

/* Change Password Section */
$("#change_password_form").validate({
    rules: {
        password: {
            required: true,
            minlength: 6
        },
        confirm_password: {
            required: true,
            minlength: 6,
            equalTo: "#cp_password"
        }            
    },
    submitHandler: function (form) {
        $('#changepass').addClass('loading');
        $('.edit-pass-msg').show();
        $('.edit-pass-msg').html('');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ route("users.changePassword") }}',
            method: 'POST',
            data: {
                password: $('#cp_password').val(),
                confirm_password: $('#cp_confirm_password').val()
            },
            success: function(data){
                setTimeout(function(){
                    $('#changepass').removeClass('loading');
                    if(data.success){
                        $('#cp_password').val('');
                        $('#cp_confirm_password').val('');
                        $('.edit-pass-msg').append('<p class="alert alert-success">'+data.success+'</p>');
                        setTimeout(function() {$('.edit-pass-msg').fadeOut('slow');}, 2000);
                    }else if(data.errors){
                        $('#cp_password').val('');
                        $('#cp_confirm_password').val('');
                        $('.edit-pass-msg').append('<p class="alert alert-error">'+data.errors+'</p>');
                        setTimeout(function() {$('.edit-pass-msg').fadeOut('slow');}, 2000);
                    }
                }, 1000);
            }
        });
        return false;
    }
});

// $(document).ready(function(){
        
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     });
//     $.ajax({
//         url: '{{ route("users.get-country-cities") }}',
//         method: 'POST',
//         dataType: 'HTML',
//         data: { country_id: 99 },
//         success: function(response_cities) {
//             $('#city_id_add').html(response_cities);
//             $('.selectpicker').selectpicker('refresh');
//         }
//     });
// });

</script>

@endsection