<style>

    .select2-container .select2-selection--single {
            height: 40px !important;
        }

    .select2-selection__choice {
        font-size: 12px; /* Change the font size */
        background-color: #ffffff !important; /* Change the background color */
        color:black !important;
        border-radius: 5px; /* Add rounded corners */
        padding: 2px 8px; /* Add some padding */
        margin-right: 5px; /* Add some space between items */
        }

    .image-holder{
        max-width: 100%;
    }

</style>
@if(count($complaints)>0)
    @foreach($complaints as $complaint)
        <div class="col-md-4">
            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user shadow">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header text-white bg-warning"
                style="
                background: url({{asset('dist/img/photo20.jpg')}}) center center;
                ">
                <h5 class="text-center" id="username{{$complaint->id}}">
                    @if(isset($complaint->name_showlast)) 
                        {{$complaint->name_showlast}}  
                    @endif
                </h5>
                <small class="widget-user-desc text-center" id='utype'>
                    @if(isset($complaint->utype)) 
                    {{$complaint->utype}}
                    @endif
                </small>
            </div>
            <div class="widget-user-image">
                @php
                    $avatar ='assets/images/avatars/unknown.png';
                    $number = rand(1,3);
                    if(isset($complaint->gender)){
                        if(strtolower($complaint->gender) == 'female'){
                            $avatar = 'avatar/T(F) '.$number.'.png';
                        }
                        elseif(strtolower($complaint->gender) == 'male'){
                            $avatar = 'avatar/T(M) '.$number.'.png';
                        }else{
                            $avatar = 'assets/images/avatars/unknown.png';
                        }
                    }
                    
                @endphp
                
                    @if(isset($complaint->name_showlast))<img class="img-circle" src="{{asset($complaint->picurl)}}"
                    onerror="this.onerror = null, this.src='{{asset($avatar)}}'" alt="User Avatar">
                    @endif
                
            </div>
            <div class="card-footer pt-3">
                <div class="row">
                    <div class="col-md-12 text-right">
                        @php
                        $info1 =  DB::table('clinic_prescription')
										->where('complaintid', $complaint->id)
										->where('deleted', 0)
										->get();
                        
                        $info2 =  DB::table('clinic_prescription')
										->select('Approve')
										->where('complaintid', $complaint->id)
										->where('deleted', 0)
										->first();

                        $complaintmeds = DB::table('clinic_complaintmed')
                            ->select('clinic_medicines.genericname','clinic_complaintmed.headerid','clinic_complaintmed.quantity', 'clinic_complaintmed.createddatetime')
                            ->join('clinic_medicines','clinic_complaintmed.drugid','=','clinic_medicines.id')
                            ->where('clinic_complaintmed.headerid', $complaint->id)
                            ->where('clinic_complaintmed.deleted','0')
                            ->where('clinic_complaintmed.quantity','!=',0)
                            ->get();
                        @endphp
                        @if($info2 && $info2->Approve == 1)
							<button type="button" class="btn btn-sm btn-info btn-complaint-viewprescription" data-id="{{$complaint->id}}"><i class="fa fa-medkit"></i></button>
                        @endif
                        @if($info2 && $info2->Approve == 0)
							<button type="button" class="btn btn-sm btn-success btn-complaint-viewprescription" data-id="{{$complaint->id}}"><i class="fa fa-medkit"></i></button>
                        @endif
                        @if(count($info1)== 0)
							<button type="button" class="btn btn-sm btn-primary btn-complaint-addprescription" data-id="{{$complaint->id}}"><i class="fa fa-medkit"></i></button>
                        @endif
							<button type="button" class="btn btn-sm btn-warning btn-complaint-addmedication" data-id="{{$complaint->id}}"><i class="fa fa-capsules"></i></button>
                        {{-- @if(count($complaintmeds) == 0)
                        @else
                        <button type="button" class="btn btn-sm btn-info btn-complaint-editmedication" data-id="{{$complaint->id}}"><i class="fa fa-capsules"></i></button> --}}
                        <button type="button" class="btn btn-sm btn-primary btn-complaint-edit" data-id="{{$complaint->id}}"><i class="fa fa-edit"></i></button>
                        {{-- @php
                        $today = today();
                        @endphp
                        @if(date('M d, Y', strtotime($complaint->cdate)) == date_format($today, 'M d, Y'))
                        <button type="button" class="btn btn-sm btn-danger btn-complaint-delete" data-id="{{$complaint->id}}"><i class="fa fa-trash"></i></button>
                        @endif --}}
                        <button type="button" class="btn btn-sm btn-danger btn-complaint-delete" data-id="{{$complaint->id}}"><i class="fa fa-trash"></i></button>
                    </div>
                    <div class="col-md-12">
                        <div class="description-block text-left text-muted">
                            @if($complaint->benefeciaryname =="" || $complaint->benefeciaryname ==NULL)
                           <strong>For :</strong> Self
                            <br/>
                            @else
                            <strong>For :</strong> &nbsp;{{$complaint->benefeciaryname}}
                            <strong> &nbsp; Relationship :</strong> &nbsp;{{$complaint->relationship}}
                            <br/>
                            @endif
                           <strong>Date :</strong> <u>{{date('M d, Y', strtotime($complaint->cdate))}} &nbsp;{{date('h:i A', strtotime($complaint->ctime))}}</u>
                            <br/>
                            <strong>Complaint :</strong> {{$complaint->description}}
                            <br/>
                            <strong>Action Taken :</strong>
                            {{$complaint->actiontaken}}
                            <br/>
                            <strong>Medicine generic name given :</strong>
                            @if(count($complaintmeds) > 0)
                            @foreach($complaintmeds as $complaintmed)
                                
                                    <u data-id="{{$complaintmed->createddatetime}}" class= "u-complaint-editmedication" style="color: #0000FF">{{$complaintmed->genericname}}:{{$complaintmed->quantity}} ,</u>
                                
                            @endforeach
                            @else
                                
                                None
                                
                            @endif
                        </div>
                    </div>
                    {{-- <div class="col-sm-4 border-right">
                        <div class="description-block">
                        <h5 class="description-header">3,200</h5>
                        <span class="description-text">SALES</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                        <h5 class="description-header">13,000</h5>
                        <span class="description-text">FOLLOWERS</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4">
                        <div class="description-block">
                        <h5 class="description-header">35</h5>
                        <span class="description-text">PRODUCTS</span>
                        </div>
                        <!-- /.description-block -->
                    </div> --}}
                <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            </div>
            <!-- /.widget-user -->
        </div>
    @endforeach
@else
<div class="col-md-12">
	<div class="card card-widget widget-user shadow-lg p-5">
			<p> No Available Complaints For Today </p>
	</div>
</div>
@endif
    