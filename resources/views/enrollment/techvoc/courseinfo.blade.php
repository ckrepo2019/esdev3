
{{-- <div class="row">
    <div class="col-12 col-sm-6"> --}}
        {{-- <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1"> --}}

                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Batches</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Settings</a>
                    </li>
                </ul>
            {{-- </div>

            <div class="card-body"> --}}
                <div class="tab-content" id="custom-tabs-one-tabContent" style="height: 450px;">
                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                        <table class="table table-hover">
                            <tr>
                                <th>No.</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Enrolled</th>
                                <th colspan="2" class="text-right"><button type="button" class="btn btn-sm btn-default" style="border: 1px solid green;" id="btncreatebatch" ><i class="fa fa-plus"></i>&nbsp;&nbsp; Add Batch</button></th>
                            </tr>
                            @if(count($batches)>0)
                                @foreach($batches as $batchkey => $batch)
                                <tr>
                                    <th>{{$batchkey+1}}</th>
                                    <th><input type="date" class="form-control form-control-sm input-startdate" value="{{$batch->startdate}}"/> </th>
                                    <th><input type="date" class="form-control form-control-sm input-enddate" value="{{$batch->enddate}}"/></th>
                                    <th class="text-center">{{$batch->enrolled}}</th>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-success btn-edit-batch" data-courseid="{{$courseinfo->id}}" data-id="{{$batch->id}}"><i class="fa fa-share"></i></button>
                                        <button type="button" class="btn btn-sm btn-default btn-delete-batch" data-courseid="{{$courseinfo->id}}" data-id="{{$batch->id}}"><i class="fa fa-trash-alt"></i></button>
                                    </td>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-sm btn-batch-activation @if($batch->isactive) btn-success @else btn-default @endif" data-id="{{$batch->id}}" data-courseid="{{$courseinfo->id}}" data-active="{{$batch->isactive}}">@if($batch->isactive)Activated @else Not Active @endif</button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">No batches shown</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label>Course Title</label>
                                <input type="text" class="form-control" value="{{$courseinfo->description}}" id="input-course-title"/>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label>Course Duration (No. of Months)</label>
                                <input type="number" class="form-control" value="{{$courseinfo->duration}}" id="input-course-duration"/>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 text-left">
                                <button type="button" class="btn btn-outline-danger" id="btn-delete-courseinfo" data-id="{{$courseinfo->id}}">Delete Course</button>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn btn-outline-success" id="btn-update-courseinfo" data-id="{{$courseinfo->id}}">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- </div> --}}
    
        {{-- </div> --}}
    {{-- </div>
</div> --}}

<div class="modal fade show" id="modal-batch" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title"><span id="coursedesc"></span></h5>
          <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
			<form id="batchdates">
	        <div class="form-group">
	        	<label for="dtstartdate" class="">Start Date</label>
	        	<input id="dtstartdate" type="date" name="" class="form-control">
	        </div>  

	        <div class="form-group">
	        	<label for="dtenddate" class="">End Date</label>
	        	<input id="dtenddate" type="date" name="" class="form-control">
			</div>  
		</form>
        </div>
        <div class="modal-footer justify-content-between"> 

        	<div class="float-left">
        		<button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Close</button>		
        	</div>        	
        	<div class="float-right">
        		<button id="btn-save-batch" type="button" class="btn btn-primary"  data-id="{{$courseinfo->id}}"><i class="fas fa-save"></i> Save</button>
        	</div>
        </div>
      </div>
    </div>
  </div>