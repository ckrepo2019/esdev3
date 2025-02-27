@extends($extends)


@section('content')


<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css')}}">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="{{asset('plugins/dropzone/min/dropzone.min.css')}}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="{{asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">


  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
  <script src="{{asset('plugins/pace-progress/pace.min.js') }}"></script>
  <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
  <!-- dropzonejs -->
  <script src="{{asset('plugins/dropzone/min/dropzone.min.js')}}"></script>
  <!-- Toastr -->
  <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
  <!-- bootstrap color picker -->
  <script src="{{asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
  <!-- Select2 -->
  <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
  <style>
    .appadd {
        white-space: nowrap;
        overflow: hidden;
        height: 10px;
        text-overflow: ellipsis; 
    }
    body{
        margin-left: 0px;
    }
    .modal-view-file .modal-dialog{
    max-width: unset; 
    margin: 20px 20px;;
    }
    /* .modal-dialog {
    max-width: unset; 
    margin: 20px 20px;;
} */
fieldset {
    display: block;
    margin-left: 2px;
    margin-right: 2px;
    padding-top: 0.35em;
    padding-bottom: 0.625em;
    padding-left: 0.75em;
    padding-right: 0.75em;
    border: 2px groove;
    
}
 
legend {
    display: block;
    padding-left: 2px;
    padding-right: 2px;
    border: none;
    width: unset;
}
img {
    border-radius: unset;
}

.modal-view-file .modal {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        overflow: hidden;
    }

    .modal-view-file .modal-dialog {
        position: fixed;
        margin: 0;
        width: 100%;
        height: 100%;
        padding: 0;
    }
    @media (min-width: 576px)
    {
        .modal-view-file .modal-dialog {
            max-width:  unset !important;
            margin: unset !important;
        }
    }
    .modal-view-file .modal-content {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        border: 2px solid #3c7dcf;
        border-radius: 0;
        box-shadow: none;
    }

    .modal-view-file .modal-header {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 50px;
        padding: 10px;
        background: #6598d9;
        border: 0;
    }

    .modal-view-file .modal-title {
        font-weight: 300;
        font-size: 2em;
        color: #fff;
        line-height: 30px;
    }

    .modal-view-file .modal-body {
        position: absolute;
        top: 50px;
        bottom: 60px;
        width: 100%;
        font-weight: 300;
        overflow: auto;
        background-color: rgba(0,0,0,.0001) !important;
    }
    .modal-view-file .modal-footer {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        height: 60px;
        padding: 10px;
        background: #f1f3f5;
    }
  </style>



			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-12">
								<h4><i class="fa fa-folder-open"></i> {{$folderinfo->foldername}}</h4>
						</div>
						<div class="col-sm-12">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="/home">Home</a></li>
								<li class="breadcrumb-item"><a href="/administrator/schoolfolders">School Folders</a></li>
								<li class="breadcrumb-item active">{{$folderinfo->foldername}}</li>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>
			<section class="content">
				<div class="container-fluid">
					<div class="row">
						<div class="@if($authorized == 1) col-md-9 @else col-md-12 @endif">
							<div class="row mb-2 p-0">
								@if($authorized == 1 || $canupload == 1)
									<div class="col-6">
										<button type="button" class="btn btn-primary" id="btn-add-file">Add file</button>
									</div>
								@endif
								@if($authorized == 1)
									<div class="col-6 text-right">
										<button type="button" class="btn btn-primary" id="btn-who-can-upload"><i class="fa fa-cogs"></i></button>
									</div>
								@endif
							</div>
							<div class="card" style="border: unset;">
								<div class="card-body p-2">
								{{-- </div> --}}
								<!-- /.card-header -->
								{{-- <div class="card-body"  style="height: 900px;overflow-y: scroll;"> --}}
									<div class="tab-content">
										<div class="active tab-pane" id="activity">
											@if(count($files)>0)
												@foreach($files as $file)
													@if(count(collect($file->files)->where('view',0)) < count(collect($file->files)))
														<div class="post">
															<div class="user-block">
																@php
																	$number = rand(1,3);
																	$teacherinfo = DB::table('teacher')
																		->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
																		->where('userid',$file->createdby)
																		->first();
																	
																	if($teacherinfo)
																	{
																		if(strtoupper($teacherinfo->gender) == 'FEMALE'){
																			$avatar = 'avatar/T(F) '.$number.'.png';
																		}
																		else{
																			$avatar = 'avatar/T(M) '.$number.'.png';
																		}
																	}else{
																		$avatar = 'assets/images/avatars/unknown.png';
																	}
																@endphp
																
																<img src="{{asset($teacherinfo->picurl)}}" onerror="this.onerror = null, this.src='{{asset($avatar)}}'" class="img-circle elevation-2" alt="User Image">
																<span class="username">
																		<a href="#">{{$file->firstname}} {{$file->lastname}}</a>
																</span>
															</div>
															@if(count($file->files)>0)
																<div class="row">
																	@foreach($file->files as $eachfile)
																		@if($eachfile->view == 1)
																			<div class="col-md-12">
																				
																				<div class="info-box shadow-none mb-0" data-toggle="tooltip" data-placement="bottom" title="{{$eachfile->filename}}"  id="view-file-{{$eachfile->id}}">
																					@if($eachfile->extension == 'png' || $eachfile->extension == 'jpg')
																						<img class="img-fluid info-box-icon" src="{{asset($eachfile->filepath)}}" alt="Photo" width="80px;">
																					@elseif($eachfile->extension == 'mp4' || $eachfile->extension == 'mkv')
																						<img class="img-fluid info-box-icon" src="{{asset('assets/images/mp4.png')}}" alt="Video" width="80px;">
																					@elseif($eachfile->extension == 'pdf')
																						<img class="img-fluid info-box-icon" src="{{asset('assets/images/pdf.png')}}" alt="PDF" width="80px;">
																					@elseif($eachfile->extension == 'xlsx' || $eachfile->extension == 'xls')
																						<img class="img-fluid info-box-icon" src="{{asset('assets/images/xls.png')}}" alt="Excel" width="80px;">
																					@elseif($eachfile->extension == 'doc' || $eachfile->extension == 'docx')
																						<img class="img-fluid info-box-icon" src="{{asset('assets/images/doc.png')}}" alt="Excel" width="80px;">
																					@endif
																					<div class="info-box-content">
																						<span class="info-box-text">{{$eachfile->filename}}</span>
																						<span class="info-box-number">
																							@if($eachfile->extension == 'png' || $eachfile->extension == 'jpg' || $eachfile->extension == 'mp4' || $eachfile->extension == 'm4a' || $eachfile->extension == 'mkv' || $eachfile->extension == 'pdf')
																								<button type="button" class="btn btn-sm btn-default btn-view-file" data-id="{{$eachfile->id}}" data-extension="{{$eachfile->filepath}}" data-filepath="{{$eachfile->filepath}}" data-toggle="tooltip" data-placement="bottom" title="View" style="display: inline;"><i class="fa fa-eye text-secondary" data-toggle="modal" data-target="#modal-view-file-{{$eachfile->id}}"></i></button>
																								<div class="modal fade modal-view-file" id="modal-view-file-{{$eachfile->id}}" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false">
																									<div class="modal-dialog">
																										<div class="modal-content">
																											<div class="modal-body" id="view-file-container">
																												<div class="row mb-2">
																													<div class="col-md-12" id="file-container-{{$eachfile->id}}">

																													</div>
																												</div>
																												@if($eachfile->createdby == auth()->user()->id)
																													<div class="row">
																														<div class="col-12 text-left">
																															<form action="/administrator/updatevisibilitytype" method="GET">
																																@csrf
																																<fieldset>
																																	<legend>Audience</legend>
																																	<div class="row">
																																		<div class="col-12">
																																			<div class="form-group clearfix">
																																				<div class="icheck-primary">
																																					@if($eachfile->visibilitytype == 3)
																																						<input type="radio" id="radio-visibilitytype-onlyme-{{$eachfile->id}}" name="visibilitytype" value="3" data-visibilitytype="onlyme" checked>
																																					@else
																																						<input type="radio"  name="visibilitytype" id="radio-visibilitytype-onlyme-{{$eachfile->id}}" data-visibilitytype="onlyme" value="3" > 
																																					@endif
																																					<label for="radio-visibilitytype-onlyme-{{$eachfile->id}}">
																																						Only Me
																																					</label>
																																				</div>
																																				<div class="icheck-primary">
																																					@if($eachfile->visibilitytype == 1)
																																						<input type="radio" id="radio-visibilitytype-public-{{$eachfile->id}}" name="visibilitytype" value="1" data-visibilitytype="public" checked>
																																					@else
																																						<input type="radio"  name="visibilitytype" id="radio-visibilitytype-public-{{$eachfile->id}}" data-visibilitytype="public" value="1" > 
																																					@endif
																																					<label for="radio-visibilitytype-public-{{$eachfile->id}}">
																																						Public
																																					</label>
																																				</div>
																																				<div class="icheck-primary">
																																					@if($eachfile->visibilitytype == 2)
																																						<input type="radio"  name="visibilitytype" id="radio-visibilitytype-custom-{{$eachfile->id}}" value="2" data-visibilitytype="custom" data-id="custom{{$eachfile->id}}" checked>
																																					@else
																																						<input type="radio" name="visibilitytype" id="radio-visibilitytype-custom-{{$eachfile->id}}" data-visibilitytype="custom" value="2" data-id="custom{{$eachfile->id}}"> 
																																					@endif
																																					<label for="radio-visibilitytype-custom-{{$eachfile->id}}">
																																						Custom
																																					</label>
																																				</div>
																																			</div>
																																		</div>
																																		<div class="col-12" class="custom-audience-container-select" id="custom-container{{$eachfile->id}}">
																																			<select class="select-custom-users" multiple="multiple" data-placeholder="Select audience" style="width: 100%;" name="input-audiences[]">
																																				@foreach($users as $user)
																																					<option value="{{$user->userid}}">{{$user->lastname}}, {{$user->firstname}} {{$user->middlename}}</option>
																																				@endforeach
																																			</select>
																																			<input type="hidden" name="fileid" value="{{$eachfile->id}}"/>
																																		</div>
																																		<div class="col-12 custom-audience-view-container mt-5" id="custom-audience-view-container-{{$eachfile->id}}">
																																			@if(count($eachfile->audiences)>0)
																																				@foreach($eachfile->audiences as $aud)
																																					<button type="button" class="btn btn-sm btn-default btn-audience-name mb-1" data-id="{{$aud->userid}}" data-file-id="{{$eachfile->id}}"><span class="right badge badge-warning">{{$aud->utype}}</span> {{$aud->lastname}}, {{$aud->firstname}} {{$aud->middlename}}</button>
																																				@endforeach
																																			@endif
																																		</div>
																																		<script>
																																			@if($eachfile->visibilitytype == '1' || $eachfile->visibilitytype == '3')
																																				$('#custom-container{{$eachfile->id}}').hide()
																																				$('#custom-audience-view-container-{{$eachfile->id}}').hide()
																																			@endif
																																				$('input[name="visibilitytype"]').on('click', function(){
																																					if($(this).attr('data-visibilitytype') == 'custom')
																																					{
																																							$(this).closest('.row').find('.custom-audience-view-container').empty()
																																							$('#custom-container{{$eachfile->id}}').show()
																																							$('#custom-audience-view-container-{{$eachfile->id}}').show()
																																					}else{
																																							$(this).closest('.row').find('.custom-audience-container').empty()
																																							$('#custom-container{{$eachfile->id}}').hide()
																																							$('#custom-audience-view-container-{{$eachfile->id}}').hide()
																																					}
																																				})
																																		</script>
																																		<div class="col-12">
																																			@if($eachfile->createdby == auth()->user()->id)
																																				<button type="submit" class="btn btn-default btn-sm float-right btn-file-update-submit mt-2">Save Changes</button>
																																			@endif
																																		</div>
																																	</div>
																																</fieldset>
																															</form>
																														</div>
																													</div>
																												@endif
																											</div>
																											<div class="modal-footer justify-content-between">
																												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																											</div>
																										</div>
																										<!-- /.modal-content -->
																									</div>
																									<!-- /.modal-dialog -->
																								</div>
																							@endif
																							@if($authorized == 1)
																								<a href="{{asset($eachfile->filepath)}}" class="btn btn-default btn-sm" download><i class="fa fa-download text-secondary"></i></a>
																							@endif
																							@if($eachfile->createdby == auth()->user()->id)
																								<button type="button" class="btn btn-sm btn-default btn-delete-file"  data-id="{{$eachfile->id}}" data-toggle="tooltip" data-placement="bottom" title="Delete" style="display: inline;"><i class="fa fa-trash text-secondary"></i></button>
																							@endif
																						</span>
																					</div>
																				</div>
																			</div>
																		@endif
																	@endforeach
																</div>
															@endif
														</div>
													@endif
												{{-- </div> --}}
												<!-- /.post -->
												@endforeach
											@endif
										</div>
										<!-- /.tab-pane -->
									</div>
									<!-- /.tab-content -->
								</div>
								<!-- /.card-body -->
							</div>
							<!-- /.card -->
						</div>
						@if($authorized == 1)
							<div class="col-md-3">
						<!-- Profile Image -->
								<div class="card card-primary card-outline">
									<div class="card-body">
										@if($folderinfo->foldername != null)
											<div class="text-center">
												<div class="p-3">
													@php
													$words = explode(" ", $folderinfo->foldername);
													$acronym = "";
				
													foreach ($words as $w) {
													$acronym .= $w[0];
													}
													@endphp
													<h1>{{$acronym}}</h1>
												</div>
											</div>
										@endif				
										<h3 class="profile-username text-center"><input type="text" class="form-control" id="input-foldername" value="{{$folderinfo->foldername}}"/></h3>
										<button type="button" class="btn btn-sm btn-default btn-block text-danger" id="btn-delete-folder"><i class="fa fa-trash"></i> Delete Folder</button>
										{{-- <ul class="list-group list-group-unbordered mb-3">
											<li class="list-group-item">
											<b>Color</b> <input type="text" id="input-update-color" class="form-control my-colorpicker1 colorpicker-element" data-colorpicker-id="1" data-original-title="" title="" value="{{$folderinfo->color}}" style="background-color: {{$folderinfo->color}}"/>
											</li>
										</ul> --}}
										<strong><i class="fas fa-book mr-1"></i> Visible to:</strong>
				
										<div class="form-group clearfix" style="height: 400px; overflow: scroll;">
											@foreach($usertypes as $usertype)
												<div class="icheck-primary">
													<input type="checkbox" class="checkbox-usertype" value="{{$usertype->id}}" id="checkboxPrimary{{$usertype->id}}" @if($usertype->checked == 1) checked=""@endif>
													<label for="checkboxPrimary{{$usertype->id}}">
															{{$usertype->utype}}
													</label>
												</div>
											@endforeach
										</div>
										<hr>
				
										<strong><i class="fas fa-image mr-1"></i> Accepted File Types</strong>
										<div class="form-group clearfix">
											
											<div class="icheck-primary">
													<input type="checkbox" id="checkboxPrimaryIMAGE" class="checkbox-filetypes" value="image" @if(count(collect($filetypes)->where('filetype','image'))>0) checked @endif>
													<label for="checkboxPrimaryIMAGE">
													IMAGE
													</label>
											</div>
											<div class="icheck-primary">
													<input type="checkbox" id="checkboxPrimaryVIDEO" class="checkbox-filetypes" value="video" @if(count(collect($filetypes)->where('filetype','video'))>0) checked @endif>
													<label for="checkboxPrimaryVIDEO">
													VIDEO
													</label>
											</div>
											<div class="icheck-primary">
													<input type="checkbox" id="checkboxPrimaryWORD" class="checkbox-filetypes" value="word" @if(count(collect($filetypes)->where('filetype','word'))>0) checked @endif>
													<label for="checkboxPrimaryWORD">
													WORD
													</label>
											</div>
											<div class="icheck-primary">
													<input type="checkbox" id="checkboxPrimaryEXCEL" class="checkbox-filetypes" value="excel" @if(count(collect($filetypes)->where('filetype','excel'))>0) checked @endif>
													<label for="checkboxPrimaryEXCEL">
													EXCEL
													</label>
											</div>
											<div class="icheck-primary">
													<input type="checkbox" id="checkboxPrimaryPDF" class="checkbox-filetypes" value="pdf" @if(count(collect($filetypes)->where('filetype','pdf'))>0) checked @endif>
													<label for="checkboxPrimaryPDF">
													PDF
													</label>
											</div>
											<div class="icheck-primary">
													<input type="checkbox" id="checkboxPrimaryPPT" class="checkbox-filetypes" value="ppt" @if(count(collect($filetypes)->where('filetype','ppt'))>0) checked @endif>
													<label for="checkboxPrimaryPPT">
													PPT
													</label>
											</div>
										</div>
									</div>
									<!-- /.card-body -->
								</div>
						<!-- /.card -->
							</div>
						@endif
				<!-- /.col -->
				<!-- /.col -->
				<!-- /.row -->
					</div>
			<!-- /.container-fluid -->
        		</section>

			<div class="modal fade" id="modal-add-file" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
						<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title" >Add file(s)</h4>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">×</span>
										</button>
								</div>
								<div class="modal-body" style="max-height: calc(100vh - 200px) !important;
								overflow-y: auto !important;">
										<div class="form-group">
											<label for="document">Documents</label>
											<div class="needsclick dropzone" id="document-dropzone">

											</div>
										</div>
								</div>
								<div class="modal-footer justify-content-between">
										<button type="button" class="btn btn-default" data-dismiss="modal" hidden>Close</button>
										<button type="submit" class="btn btn-primary" id="btn-add-file-close" >Close</button>
								</div>
						</div>
						<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<div class="modal fade" id="modal-delete-folder" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" >Delete folder</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body" >
								<h4><strong>Are you sure you want to delete this folder?</strong></h4>
							</div>
							<div class="modal-footer justify-content-between">
								<form action="/administrator/deletefolder" method="GET">
										@csrf
										<input type="hidden" name="folderid" value="{{$folderinfo->id}}"/>
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
										<button type="submit" class="btn btn-danger" id="btn-delete-folder-submit" >Delete</button>
								</form>
							</div>
						</div>
						<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<div class="modal fade" id="modal-delete-file" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" >Delete folder</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body" >
								<h4><strong>Are you sure you want to delete this file?</strong></h4>
							</div>
							<div class="modal-footer justify-content-between">
								<form action="/administrator/deletefile" method="GET">
										@csrf
										<input type="hidden" name="fileid" id="input-file-id"/>
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
										<button type="submit" class="btn btn-danger" >Delete</button>
								</form>
							</div>
						</div>
						<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<div class="modal fade" id="modal-remove-audience" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header bg-danger">
								<h4 class="modal-title" >Remove Audience</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body" >
								<h4><strong>Are you sure you want to remove this person from your audience?</strong></h4>
							</div>
							<div class="modal-footer justify-content-between">
								{{-- <form action="/administrator/deletefile" method="GET">
										@csrf --}}
										<input type="hidden" name="userid" id="input-user-id"/>
										<input type="hidden" name="fileid" id="input-each-file-id"/>
										<button type="button" class="btn btn-default" data-dismiss="modal" id="btn-remove-audience-cancel">Cancel</button>
										<button type="submit" class="btn btn-danger" id="btn-remove-audience" >Remove</button>
								{{-- </form> --}}
							</div>
						</div>
						<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<div class="modal fade" id="modal-who-can-upload" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" >Who can upload more files?</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body" id="modal-body-container">
								
							</div>
							<div class="modal-footer justify-content-between">
								{{-- <form action="/administrator/deletefile" method="GET">
										@csrf --}}
										<input type="hidden" name="userid" id="input-user-id"/>
										<input type="hidden" name="fileid" id="input-each-file-id"/>
										<button type="button" class="btn btn-default" data-dismiss="modal" id="btn-modal-cancel-whocanupload">Cancel</button>
										<button type="submit" class="btn btn-primary" id="btn-modal-update-whocanupload" >Update</button>
								{{-- </form> --}}
							</div>
						</div>
						<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
		</div>
  
		@yield('footerjavascript')


  <!-- bootstrap color picker -->
  <script src="{{asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>

		<script>
			$(document).ready(function(){
			$('.select-custom-users').select2({
						theme: 'bootstrap4'
						})
				window.alert = function() {};
	
			})
				document.addEventListener('contextmenu', function(e) {
					e.preventDefault();
				});
				$(document).keydown(function (event) {
					if (event.keyCode == 123) { // Prevent F12
						return false;
					} else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
						return false;
					}
				});
		</script> 
		<script>
			$(document).ready(function(){
			const Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 2000,
					showCloseButton: true,
			});

			$( document ).ajaxError(function() {
					Toast.fire({
							type: 'error',
							title: 'Unable to process online!'
					})
			});

			})
		</script>
		<script>
			$(document).ready(function(){
						// DropzoneJS Demo Code End
					$('#btn-add-file').on('click', function(){
						$('#modal-add-file').modal('show')
						$('#modal-add-file').on('show.bs.modal', function () {
							$('.modal .modal-body').css('overflow-y', 'auto'); 
							$('.modal .modal-body').css('max-height', $(window).height() * 0.7);
						});
					})
			})
		</script>


<script type="text/javascript">
			$(function () {
			})
			$(document).ready(function(){
				$('.select2bs4').select2({
							theme: 'bootstrap4'
							})
						})
			$('[data-toggle="tooltip"]').tooltip()
			$('.my-colorpicker1').colorpicker()
			var filetypes = [];
			@foreach($filetypes as $filetype)
				filetypes.push('{{$filetype->filetype}}')
			@endforeach

			var acceptedfiles = "";
			if($.inArray('image', filetypes)!== -1)
			{
				acceptedfiles+='image/*,';
			}
			if($.inArray('video', filetypes)!== -1)
			{
				acceptedfiles+='.mp4,.mkv,.avi,';
			}
			if($.inArray('word', filetypes)!== -1)
			{
				acceptedfiles+='.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,';
			}
			if($.inArray('excel', filetypes)!== -1)
			{
				acceptedfiles+='.xls, .xlsx,';
			}
			if($.inArray('pdf', filetypes)!== -1)
			{
				acceptedfiles+='.pdf,';
			}
			if($.inArray('ppt', filetypes)!== -1)
			{
				acceptedfiles+='application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.slideshow,application/vnd.openxmlformats-officedocument.presentationml.presentation,';
			}

			var uploadedDocumentMap = {}
			Dropzone.options.documentDropzone = {
				url: '/administrator/media?folderid={{$folderinfo->id}}',
				maxFilesize: 50, // MB
				addRemoveLinks: true,
				acceptedFiles: acceptedfiles.slice(0,-1),
				type: 'POST',
				headers: {
				'X-CSRF-TOKEN': "{{ csrf_token() }}"
				},
				success: function (file, response) {
				$('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
				uploadedDocumentMap[file.name] = response.name
				$('.dz-remove').remove()
				},
				removedfile: function (file) {
				file.previewElement.remove()
				var name = ''
				if (typeof file.file_name !== 'undefined') {
					name = file.file_name
				} else {
					name = uploadedDocumentMap[file.name]
				}
				$('form').find('input[name="document[]"][value="' + name + '"]').remove()
				},
				init: function () {
				@if(isset($project) && $project->document)
					var files =
						{!! json_encode($project->document) !!}
					for (var i in files) {
						var file = files[i]
						this.options.addedfile.call(this, file)
						file.previewElement.classList.add('dz-complete')
						$('form').append('<input type="hidden" name="document[]" value="' + file.file_name + '">')
					}
				@endif
				}
			}
			// $(document).ready(function(){
				// $('[data-toggle="tooltip"]').tooltip()
				$('#btn-add-file-close').on('click', function(){
					window.location.reload()
				})
			// })
			$(document).on("keypress", "#input-foldername", function(e){
				if(e.which == 13){
						var foldername = $(this).val();
						$.ajax({
						url: '/administrator/updatefoldername',
						type: 'GET',
						data: {
							foldername : foldername,
							folderid   : '{{$folderinfo->id}}'
						}, success:function(data)
						{
							if(data == '1')
							{
								toastr.success('Updated succesfully!','Folder Name')
							}else{
								toastr.error('Something went wrong!','Folder Name')
							}
						}
						})
				}
			});
			$(document).on("keypress", "#input-update-color", function(e){
				if(e.which == 13){
						var color = $(this).val();
						$.ajax({
						url: '/administrator/updatefoldercolor',
						type: 'GET',
						data: {
							color : color,
							folderid   : '{{$folderinfo->id}}'
						}, success:function(data)
						{
							if(data == '1')
							{
								toastr.success('Updated succesfully!','Folder Name')
								$('#input-update-color').removeAttr('style')
								$('#input-update-color').css('background-color',color)

							}else{
								toastr.error('Something went wrong!','Folder Name')
							}
						}
						})
				}
			});
			$('.checkbox-usertype').on('click', function(){
				var id = $(this).val();
				if(!$(this).is(':checked'))
				{
						console.log('unchecked')
						var status = 1;
						var stringview = 'removed';
				}else{
						console.log('checked')
						var status = 0;
						var stringview = 'addedd';
				}

				$.ajax({
					url: '/administrator/updatevisibleto',
					type: 'GET',
					data: {
						status  : status,
						id      : id,
						folderid: '{{$folderinfo->id}}'
					}, success:function(data)
					{
						if(data == '1')
						{
						toastr.success('Updated succesfully!','Folder Visibility')
						}else{
						toastr.error('Something went wrong!','Folder Visibility')
						}
					}
				})
			})
			$('.checkbox-filetypes').on('click', function(){
				var id = $(this).val();
				if(!$(this).is(':checked'))
				{
						console.log('unchecked')
						var status = 1;
						var stringview = 'removed';
				}else{
						console.log('checked')
						var status = 0;
						var stringview = 'addedd';
				}

				$.ajax({
					url: '/administrator/updatefiletype',
					type: 'GET',
					data: {
						status  : status,
						id      : id,
						folderid: '{{$folderinfo->id}}'
					}, success:function(data)
					{
						if(data == '1')
						{
						toastr.success('Updated succesfully!','Accepted File Types')
						}else{
						toastr.error('Something went wrong!','Accepted File Types')
						}
					}
				})
			})
			$('#btn-delete-folder').on('click', function(){
				$('#modal-delete-folder').modal('show');
			})
			$('.btn-delete-file').on('click', function(){
				$('#input-file-id').val($(this).attr('data-id'))
				$('#modal-delete-file').modal('show')
			})
			$('.btn-audience-name').on('click', function(){
				$('#modal-remove-audience').modal('show')
				$('#input-user-id').val($(this).attr('data-id'))
				$('#input-each-file-id').val($(this).attr('data-file-id'))
			})
			$('#btn-remove-audience').on('click', function(){
				var fileid = $('#input-each-file-id').val();
				var userid = $('#input-user-id').val();
				$.ajax({
					url: '/administrator/removeaudience',
					type: 'GET',
					data: {
						userid  : userid,
						fileid      : fileid
					}, success:function(data)
					{
						if(data == '1')
						{
							$('#btn-remove-audience-cancel').click();
							$('.btn-audience-name[data-id="'+userid+'"][data-file-id="'+fileid+'"]').remove()
						toastr.success('Removed succesfully!','File Audience')
						}else{
						toastr.error('Something went wrong!','File Audience')
						}
					}
				})
			})
			$('#btn-who-can-upload').on('click', function(){
				$('#modal-who-can-upload').modal('show')
				$.ajax({
					url: '/administrator/whocanupload',
					type: 'GET',
					data: {
						
						folderid   			: '{{$folderinfo->id}}',
					},
					success:function(data)
					{
						$('#modal-body-container').empty()
						$('#modal-body-container').append(data)
						$('#checkbox-all-selection').hide()
						// if(data == '1')
						// {
						// 	$('#btn-remove-audience-cancel').click();
						// 	$('.btn-audience-name[data-id="'+userid+'"][data-file-id="'+fileid+'"]').remove()
						// 	toastr.success('Removed succesfully!','File Audience')
						// }else{
						// 	toastr.error('Something went wrong!','File Audience')
						// }
					}
				})
			})
			$(document).on('click', 'input[name="checkbox-limit-who"]', function(){
				if($(this).val() == 'all')
				{
					$('#users-control-container').hide();
					$('#checkbox-all-selection').hide()
					$('#people-who-can-upload-container').removeAttr('style')
					$('#people-who-can-upload-container').empty();
				}
				else if($(this).val() == 'custom')
				{
					$('#users-control-container').show();
					Swal.fire({
						title: 'Fetching users...',
						onBeforeOpen: () => {
							Swal.showLoading()
						},
						allowOutsideClick: false
					})
					$('#checkbox-all-selection').hide()
					$('#people-who-can-upload-container').removeAttr('style')
					$('#people-who-can-upload-container').empty();
					// var htmlselect = '<div class="col-md-12"><select class="select2bs4" multiple="multiple" data-placeholder="Select a State" style="width: 100%;" name="input-custom-users-whocanupload">';
					// @foreach($users as $user)
					// 	htmlselect+='<option value="{{$user->userid}}">{{$user->lastname}},{{$user->firstname}},{{$user->middlename}},</option>';
					// @endforeach
					// htmlselect+='</select></div>';
					// $('#people-who-can-upload-container').append(htmlselect)
					// $('.select2bs4').select2({
					// 	theme: 'bootstrap4'
					// })
					$.ajax({
						url: '/administrator/whocanuploadgetusers',
						type: 'GET',
						success:function(data)
						{
							$('#people-who-can-upload-container').empty();
							if(data.length > 0)
							{
								var htmlselect = '<div class="col-12" id="select2container"><select class="select2bs4" multiple="multiple" data-placeholder="Select a user" name="input-custom-users-whocanupload[]">';
								$.each(data, function(key, value){
										htmlselect+='<option value="'+value.userid+'">'+value.lastname+', '+value.firstname+' '+value.middlename+'</option>';
									
								})
								htmlselect+='</select></div>';
								$('#people-who-can-upload-container').append(htmlselect)
								$('.select2bs4').select2({
									theme: 'bootstrap4'
								})
							}
							
							$(".swal2-container").remove();
							$('body').removeClass('swal2-shown')
							$('body').removeClass('swal2-height-auto')
						}
					})
					
																																					

				}else{
					$('#users-control-container').hide();
					$('#checkbox-all-selection').show()
					$('#people-who-can-upload-container').css('height', '600px')
					$('#people-who-can-upload-container').css('overflow-y', 'scroll')
					$.ajax({
						url: '/administrator/whocanuploadget',
						type: 'GET',
						dataType: 'json',
						success:function(data)
						{
							console.log(data);
							$('#people-who-can-upload-container').empty();
							if(data.length > 0)
							{
								$.each(data, function(key, value){
									
									$('#people-who-can-upload-container').append(
										'<div class="col-md-12">'+
											'<div class="icheck-primary d-inline">'+
												'<input type="checkbox" id="checkbox-usertype-'+value.id+'" name="who-can-usertypes[]" value="'+value.id+'">'+
												'<label for="checkbox-usertype-'+value.id+'">'+value.utype+'</label>'+
											'</div>'+
										'</div>'
									);
								})
							}
						}
					})
				}
			})
			$('.btn-view-file').on('click', function(){
				var fileid = $(this).attr('data-id');
				$.ajax({
					url: '/administrator/getfileview',
					type: 'GET',
					data: {
						fileid      : fileid
					}, success:function(data)
					{
						$('#file-container-'+fileid).empty()
						$('#file-container-'+fileid).append(data)
						$('[data-toggle="tooltip"]').tooltip('close')
						document.addEventListener('contextmenu', function(e) {
							e.preventDefault();
						});
					}
				})
			})
			$(document).on('click','#checkbox-usertype', function(){
				if($(this).prop('checked'))
				{
					$('input[name="who-can-usertypes[]"]').each(function(){
						$(this).attr('checked',true)
					})
				}else{
					$('input[name="who-can-usertypes[]"]').each(function(){
						$(this).removeAttr('checked')
					})
				}
			})
			$(document).on('click','#btn-modal-update-whocanupload', function(){
				var selectedtype = $('input[name=checkbox-limit-who]:checked').val();
				var selectedusertypes = [];
				$('input[name="who-can-usertypes[]"]:checked').each(function(){
					selectedusertypes.push($(this).val())
				})
				var users = $('select[name="input-custom-users-whocanupload[]"]').val();
				$.ajax({
					url: '/administrator/whocanuploadsubmit',
					type: 'GET',
					data: {
						folderid   			: '{{$folderinfo->id}}',
						selectedtype		:	selectedtype,
						selectedusertypes	:	selectedusertypes,
						users				:	users
					},
					complete:function(data)
					{
						toastr.success('Updated succesfully!','User Control')
						$('#btn-modal-cancel-whocanupload').click();
						// if(data == '1')
						// {
						// 	$('#btn-remove-audience-cancel').click();
						// 	$('.btn-audience-name[data-id="'+userid+'"][data-file-id="'+fileid+'"]').remove()
						// 	toastr.success('Removed succesfully!','File Audience')
						// }else{
						// 	toastr.error('Something went wrong!','File Audience')
						// }
					}
				})
							// $('#modal-body-container').append(data)
							// if(data == '1')
							// {
							// 	$('#btn-remove-audience-cancel').click();
							// 	$('.btn-audience-name[data-id="'+userid+'"][data-file-id="'+fileid+'"]').remove()
							// 	toastr.success('Removed succesfully!','File Audience')
							// }else{
							// 	toastr.error('Something went wrong!','File Audience')
							// }
				
			})
		</script>
@endsection
