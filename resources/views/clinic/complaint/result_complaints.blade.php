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
            <div class="card card-widget widget-user shadow-lg">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header text-white bg-warning"
                style="
                background: url({{asset('dist/img/photo20.jpg')}}) center center;
                ">
                {{-- <h3 class="widget-user-username text-right">{{$complaint->name_showlast}}</h3>
                <h5 class="widget-user-desc text-right">{{$complaint->utype}}</h5> --}}
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
            <div class="card-footer">
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
                        <button type="button" class="btn btn-sm btn-default btn-complaint-addprescription" data-id="{{$complaint->id}}"><i class="fa fa-medkit"></i></button>
                        @endif
                        <button type="button" class="btn btn-sm btn-default btn-complaint-addmedication" data-id="{{$complaint->id}}"><i class="fa fa-capsules"></i></button>
                        {{-- @if(count($complaintmeds) == 0)
                        @else
                        <button type="button" class="btn btn-sm btn-info btn-complaint-editmedication" data-id="{{$complaint->id}}"><i class="fa fa-capsules"></i></button> --}}
                        <button type="button" class="btn btn-sm btn-default btn-complaint-edit" data-id="{{$complaint->id}}"><i class="fa fa-edit"></i></button>
                        {{-- @php
                        $today = today();
                        @endphp
                        @if(date('M d, Y', strtotime($complaint->cdate)) == date_format($today, 'M d, Y'))
                        <button type="button" class="btn btn-sm btn-default btn-complaint-delete" data-id="{{$complaint->id}}"><i class="fa fa-trash"></i></button>
                        @endif --}}
                        <button type="button" class="btn btn-sm btn-default btn-complaint-delete" data-id="{{$complaint->id}}"><i class="fa fa-trash"></i></button>
                    </div>
                    <div class="col-md-12">
                        <div class="description-block text-left text-muted">
                            @if($complaint->benefeciaryname =="" || $complaint->benefeciaryname ==NULL)
                            <small ><strong>For :</strong> Self</small>
                            <br/>
                            @else
                            <small ><strong>For :</strong> &nbsp;{{$complaint->benefeciaryname}}</small>
                            <small ><strong> &nbsp; Relationship :</strong> &nbsp;{{$complaint->relationship}}</small>
                            <br/>
                            @endif
                            <small><strong>Date :</strong> <u>{{date('M d, Y', strtotime($complaint->cdate))}} &nbsp;{{date('h:i A', strtotime($complaint->ctime))}}</u></small>
                            <br/>
                            <small ><strong>Complaint :</strong> {{$complaint->description}}</small>
                            <br/>
                            <small><strong>Action Taken :</strong></small>
                            <small>{{$complaint->actiontaken}}</small>
                            <br/>
                            <small><strong>Medicine generic name given :</strong></small>
                            @if(count($complaintmeds) > 0)
                            @foreach($complaintmeds as $complaintmed)
                                <small>
                                    <u data-id="{{$complaintmed->createddatetime}}" class= "u-complaint-editmedication" style="color: #0000FF">{{$complaintmed->genericname}}:{{$complaintmed->quantity}} ,</u>
                                </small>
                            @endforeach
                            @else
                                <small>
                                None
                                </small>
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
<div class="image-holder">
<img src="{{asset('dist/img/no-data.jpg')}}" style= "width:100% ; height:100%; object-fit: cover;">
<a href="http://www.freepik.com" style="font-size: 10px">Designed by slidesgo / Freepik</a>
    {{-- <div class="col-md-12">
        <img src="{{asset('dist/img/2953962.jpg')}}"
            
            style=" max-width: 100%; max-height: 100px; ">
            </div> --}}
        </div>
@endif
    