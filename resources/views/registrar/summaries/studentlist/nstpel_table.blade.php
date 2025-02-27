<style>
    

    .table thead th:first-child  { 
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    z-index: 999 !important
}

.table tbody td:first-child  {  
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    width: 150px !important;
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

.table thead th:first-child  { 
        position: sticky; left: 0; 
        width: 150px !important;
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
}
</style>
<hr/>
<div class="row mb-2 align-items-end">
    <div class="col-md-3">
        <label>Registar Consultant</label>
        <input type="text" class="form-control form-control-sm" id="reg_consultant" value="{{$signatory->name ?? null}}"/>
    </div>
    <div class="col-md-3">
        {{-- <label>&nbsp;</label><br/> --}}
        <button type="button" class="btn btn-sm btn-default mt-2" id="btn-savesignatories"><i class="fa fa-share"></i> Save Changes</button>
    </div>
    @if($reporttype == 'listofgraduates')
    <div class="col-md-6 text-right">
        <button type="button" class="btn btn-sm btn-default" id="btn-export-nstplist"><i class="fa fa-file-excel"></i> Export List of NSTP Graduates</button>
    </div>
    @endif
</div>
@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
    @if(count($subjects) > 0)
        <div id="accordion">
            @foreach($subjects as $subject)
                <div class="row m-0 p-1" style="border: 1px solid #ddd; border-radius: 5px;">
                    <div class="col-md-6">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapse{{str_replace(' ', '', $subject->subjcode)}}">
                            <h4 class="m-0">{{$subject->subjcode}} <small style="font-size: 11px;"><em>(Click Me!)</em></small></h4>
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" class="btn btn-default btn-sm btn-export-nstpel-sy" data-nstpcomp="{{$subject->subjcode}}" data-exporttype="excel"><i class="fa fa-file-ecel"></i> Export to Excel</button>
                        @if($reporttype != 'listofgraduates')
                        <button type="button" class="btn btn-default btn-sm btn-export-nstpel-sy" data-nstpcomp="{{$subject->subjcode}}" data-exporttype="pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                        @endif
                        {{-- <button type="button" class="btn btn-default btn-sm btn-export-nstpel-sem"><i class="fa fa-file-pdf"></i> Export for this Semester</button> --}}
                    </div>
                    <div class="col-md-12">
                        <div id="collapse{{str_replace(' ', '', $subject->subjcode)}}" class="collapse" data-parent="#accordion">
                            <div class="row">
                                <div class="col-md-12"  style="height: 500px !important; overflow: scroll;">
                                    @php
                                        $gtotal = 0;
                                    @endphp
                                    <table class="table table-head-fixed text-nowrap mt-2" style="font-size: 11.8px;">
                                        <thead>
                                            <tr>
                                                <th>I.D. No.</th>
                                                <th>Student Name</th>
                                                @if($reporttype == 'listofgraduates')
                                                <th>Birthdate</th>
                                                @endif
                                                <th>Sex</th>
                                                @if($reporttype == 'listofgraduates')
                                                <th>Contact No.</th>
                                                <th>Address</th>
                                                <th>Year</th>
                                                <th>Course</th>
                                                <th>Grade</th>
                                                <th>Units</th>
                                                @else
                                                <th>Year</th>
                                                <th>Course</th>
                                                <th>Grade</th>
                                                <th>Units</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($subject->students as $key => $eachcourse)
                                                <tr>
                                                    <td>{{$eachcourse->sid}}</td>
                                                    <td><span class="text-bold">{{$eachcourse->lastname}}</span>, {{ucwords(strtolower($eachcourse->firstname))}}</td>
                                                    @if($reporttype == 'listofgraduates')
                                                    <td>{{$eachcourse->dob != null ? date('M d, Y', strtotime($eachcourse->dob)) : ''}}</td>
                                                    @endif
                                                    <td>{{$eachcourse->gender}}</td>
                                                    @if($reporttype == 'listofgraduates')
                                                    <td>{{$eachcourse->contactno}}</td>
                                                    <td>{{$eachcourse->street}}, {{$eachcourse->barangay}}, {{$eachcourse->city}}, {{$eachcourse->province}}</td>
                                                    <td>{{$eachcourse->yearlevel}}</td>
                                                    <td>{{$eachcourse->courseabrv}}</td>
                                                    <td>{{$eachcourse->eqgrade}}</td>
                                                    <td>{{$eachcourse->units ?? ''}}</td>
                                                    @else
                                                    <td>{{$eachcourse->yearlevel}}</td>
                                                    <td>{{$eachcourse->courseabrv}}</td>
                                                    <td>@if($reporttype == 'promotional'){{$eachcourse->eqgrade}}@endif</td>
                                                    <td>{{$eachcourse->units ?? ''}}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            @endforeach
        </div>
    @endif
    <script>
        $('#btn-export-nstplist').on('click', function(){
                var reporttype =  '{{$reporttype}}'
                window.open('/registrar/studentlist?type=nstpel&action=exportexcel&reporttype='+reporttype+'&syid={{$syid}}&semid={{$semid}}&filetype=excel','_blank')
        })
    </script>
@else
    @if(count($students) > 0)
        <div id="accordion">
            @foreach($students as $key => $eachcomp)
            @php
                $eachcomp = collect($eachcomp)->sortBy('firstname')->sortBy('lastname');
            @endphp
                <div class="row m-0 p-1" style="border: 1px solid #ddd; border-radius: 5px;">
                    <div class="col-md-6">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapse{{$key}}">
                            <h4 class="m-0">{{$key}} <small style="font-size: 11px;"><em>(Click Me!)</em></small></h4>
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" class="btn btn-default btn-sm btn-export-nstpel-sy" data-nstpcomp="{{$key}}"><i class="fa fa-file-pdf"></i> Export for this S.Y</button>
                        {{-- <button type="button" class="btn btn-default btn-sm btn-export-nstpel-sem"><i class="fa fa-file-pdf"></i> Export for this Semester</button> --}}
                    </div>
                    <div class="col-md-12">
                        <div id="collapse{{$key}}" class="collapse" data-parent="#accordion">
                            <div class="row">
                                <div class="col-md-12"  style="height: 500px !important; overflow: scroll;">
                                    @php
                                        $gtotal = 0;
                                    @endphp
                                    <table class="table table-head-fixed text-nowrap mt-2" style="font-size: 11.8px;">
                                        <thead>
                                            <tr>
                                                <th>Student Name</th>
                                                <th>Semester</th>
                                                <th>Course</th>
                                                <th>Gender</th>
                                                <th>Birthdate</th>
                                                <th>City Address</th>
                                                <th>Provincial Address</th>
                                                <th>Contact #</th>
                                                <th>Email Address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($eachcomp as $key => $eachcourse)
                                                <tr>
                                                    <td>{{$key+1}}. <span class="text-bold">{{$eachcourse->lastname}}</span>, {{ucwords(strtolower($eachcourse->firstname))}}</td>
                                                    <td>{{$eachcourse->semid}}</td>
                                                    <td>{{$eachcourse->coursename}}</td>
                                                    <td>{{strtoupper($eachcourse->gender)}}</td>
                                                    <td>@if($eachcourse->dob != null){{date('m/d/Y', strtotime($eachcourse->dob))}}@endif</td>
                                                    <td>{{$eachcourse->city}}</td>
                                                    <td>{{$eachcourse->province}}</td>
                                                    <td>{{$eachcourse->contactno}}</td>
                                                    <td>{{$eachcourse->email}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            @endforeach
        </div>
    @endif

@endif