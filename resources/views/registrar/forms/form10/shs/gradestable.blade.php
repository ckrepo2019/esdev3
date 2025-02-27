<style>
    table td, table th{
        padding: 0px !important;
    }
    .input-norecord{
        height: unset;
    }
</style>
<div class="row">
    @foreach ($gradelevels as $gradelevel)
        @php
            $studentid = $studinfo->id;
            $recordsem1 = collect($records)->where('levelid', $gradelevel->id)->first();
            $recordatt = array();
            $syid =0;
            $sectionid =0;
            if($recordsem1)
            {
                $syid = $recordsem1->syid;
                $sydesc = $recordsem1->sydesc;
                $sectionid =$recordsem1->sectionid;
                $recordatt = collect($records)->where('levelid', $gradelevel->id)->first()->attendance;
            }else{
                $syid = 0;
                $sydesc = null;
                $sectionid = 0;
            }
            $subjects = array();
        @endphp
        <div class="col-md-12">
            <div class="card">
                @if(collect($records)->where('levelid', $gradelevel->id)->where('semid','1')->count() == 0)
                    <div class="card-header p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table m-0" style="font-size: 12px;">
                                    <tr>
                                        <td style="width: 15%;">School</td>
                                        <td colspan="7" style=""><input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolname"/></td>
                                    </tr>
                                    <tr>
                                        <td>School ID</td>
                                        <td colspan="7"><input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolid"/></td>
                                    </tr>
                                    <tr>
                                        <td>Grade Level</td>
                                        <td style="width: 30%; "><input type="text" class="form-control form-control-sm p-0 input-norecord input-levelid" data-id="{{$gradelevel->id}}" value="{{$gradelevel->levelname}}" readonly/></td>
                                        <td>SY</td>
                                        <td style="width: 15%; "><input type="text" class="form-control form-control-sm p-0 input-norecord input-sydesc"/></td>
                                        <td>Sem</td>
                                        <td style="width: 15%; "><input type="text" class="form-control form-control-sm p-0 input-norecord input-semid" data-id="1" value="1st" readonly/></td>
                                    </tr>
                                    <tr>
                                        <td>Track</td>
                                        <td style="width: 15%; " colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-trackname"/></td>
                                        <td>Strand</td>
                                        <td style="width: 15%; " colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-strandname"/></td>
                                    </tr>
                                    <tr>
                                        <td>Section</td>
                                        <td style="width: 15%; " colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-sectionname"/></td>
                                        <td>Adviser</td>
                                        <td style="width: 15%; " colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-adviser"/></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0 pr-1 pl-1">
                        <div class="row">
                            <div class="col-md-12">
                                @php
                                    
                                    $defaultsubjects = collect($gradelevel->subjects)->where('semid', 1)->values();
                                @endphp
                                <table class="table table-striped" style="font-size: 11px; table-layout: fixed;">
                                    <thead class="text-center">
                                        <tr>
                                            <th style="width: 8%;">Indication</th>
                                            <th style="width: 40%;">Subjects</th>
                                            <th>Q1</th>
                                            <th>Q2</th>
                                            <th style="width: 10%;">Final Grade</th>
                                            <th style="width: 15%;">Action Taken</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($defaultsubjects)>0)
                                            @foreach($defaultsubjects as $defaultsubject)
                                                <tr>
                                                    <td style="width: 15%;"><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjcode" placeholder="Ex: Core" value="{{$defaultsubject->subjcode}}"/></td>
                                                    <td style="width: 30%;"><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjdesc" placeholder="Subject" value="{{ucwords(strtolower($defaultsubject->subjtitle))}}"/></td>
                                                    <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q1" placeholder="Grade"/></td>
                                                    <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q2" placeholder="Grade"/></td>
                                                    <td style="width: 15%;"><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-finalgrade" placeholder="Final Grade"/></td>
                                                    <td style="width: 15%;"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-remarks" placeholder="Action Taken"/></td>
                                                    <td colspan="2"><button type="button" class="btn btn-sm btn-block btn-default p-0 btn-deleterow"><small><i class="fa fa-trash-alt"></i></small></button></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td colspan="8" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer p-0 pr-1 pl-1">
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sjaes')
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table" style="width: 100%; font-size: 11px;">
                                        <tr>
                                            <th style="width: 15% !important;" class="text-right">Remarks:</th>
                                            <td colspan="3" style="width: 85% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-semremarks"/></td>
                                        </tr>
                                        <tr>
                                            <th style="width: 15% !important;" class="text-right">Record's In-charge:</th>
                                            <td style="width: 60% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-recordsincharge"/></td>
                                            <th style="width: 15% !important;" class="text-right">Date Checked:</th>
                                            <td style="width: 10% !important;"><input type="date" class="form-control form-control-sm p-0 input-norecord input-semdatechecked"/></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        @endif
                        <div class="row mb-2">
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-sm btn-success btn-saverecord"><i class="fa fa-share"></i> Save this record</button>
                            </div>
                        </div>
                    </div>
                @else
                    @php
                        $eachrecord = collect($records)->where('levelid', $gradelevel->id)->where('semid','1')->first();
                        $grades = collect($records)->where('levelid', $gradelevel->id)->where('semid','1')->first()->grades;
                    @endphp
                    <div class="card-header p-0 pr-1 pl-1">
                        <div class="row">
                            @if($eachrecord->type == 1)
                                <div class="col-md-12"><span class="badge badge-warning">Auto Generated</span></div>
                            @else
                            <div class="col-md-6"><span class="badge badge-success">Manual</span> @if($eachrecord->syid == DB::table('sy')->where('isactive','1')->first()->id)<span class="badge badge-success">Current School Year</span>@endif</div>
                            @if($eachrecord->type==2)
                                <div class="col-md-6 text-right">
                                    <span class="badge badge-warning badge-clear-record" style="cursor: pointer;" data-id="{{$eachrecord->id}}">Clear This Record</span>
                                </div>
                            @endif
                            @endif
                            <div class="col-md-12">
                                <table class="table m-0" style="font-size: 12px;">
                                    <tr>
                                        <td style="width: 15%;">School</td>
                                        <td colspan="7" style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schoolname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolname" value="{{$eachrecord->schoolname}}"/>@endif </td>
                                    </tr>
                                    <tr>
                                        <td>School ID</td>
                                        <td colspan="7" style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schoolid}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolid" value="{{$eachrecord->schoolid}}"/>@endif</td>
                                    </tr>
                                    <tr>
                                        <td>Grade Level</td>
                                        <td style="width: 30%; border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->levelname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-levelid" data-id="{{$gradelevel->id}}" value="{{$gradelevel->levelname}}" readonly/>@endif</td>
                                        <td>SY</td>
                                        <td style="width: 20%; border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->sydesc}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-sydesc" value="{{$eachrecord->sydesc}}"/>@endif</td>
                                        <td>Sem</td>
                                        <td style="width: 20%; border-bottom: 1px solid black;">@if($eachrecord->type == 1)1st @else<input type="text" class="form-control form-control-sm p-0 input-norecord input-semid" data-id="1" value="1st" readonly/>@endif</td>
                                    </tr>
                                </table>
                                <table class="table m-0" style="font-size: 11px;">
                                    <tr>
                                        <td style="width: 20%;">Track</td>
                                        <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->trackname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-trackname" value="{{$eachrecord->trackname}}"/>@endif</td>
                                        <td>Strand</td>
                                        <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->strandname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-strandname" value="{{$eachrecord->strandname}}"/>@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 20%;">Section</td>
                                        <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->sectionname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-sectionname" value="{{$eachrecord->sectionname}}"/>@endif</td>
                                        <td>Adviser</td>
                                        <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->teachername}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-adviser" value="{{$eachrecord->teachername}}"/>@endif</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0 pr-1 pl-1">
                        <div class="row">
                            <div class="col-md-12">
                                @if(count($grades) == 0)
                                {{-- <table class="table"></table> --}}
                                @else
                                    <table class="table table-striped" style="font-size: 12px; table-layout: fixed;">
                                        <thead class="text-center">
                                            <tr>
                                                <th style="width: 15%;">Indication</th>
                                                <th style="width: 30%;">Subjects</th>
                                                <th>Q1</th>
                                                <th>Q2</th>
                                                <th style="width: 15%;">Final Grade</th>
                                                <th style="width: 10%;">Action Taken</th>
                                                @if($eachrecord->type == 1)
                                                <th style="width: 12%;">&nbsp;</th>                  
                                                @else
                                                <th colspan="2">Delete</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>     
                                            @foreach($grades as $grade)
                                                <tr @if($eachrecord->type != 1)s class="eachsubject" @endif>
                                                    <td>
                                                        @if($eachrecord->type == 1)@if(isset($grade->subjcode)) {{$grade->subjcode}} @endif @else<input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="{{$grade->id}}"/><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-subjcode" value="{{$grade->subjcode}}"/>@endif
                                                    </td>
                                                    <td>@if($eachrecord->type == 1){{ucwords(strtolower($grade->subjdesc))}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-subjdesc" value="{{$grade->subjdesc}}"/>@endif</td>

                                                    @if($grade->q1stat != 0)
                                                            <td class="text-center p-0">
                                                                <div class="row text-center p-0 m-0">
                                                                    <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline; font-size: 12px; height: 25px !important;" @if($grade->q1stat == 2) value="{{$grade->q1}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade->q1stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="1"  data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}">@if($grade->q1stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs m-0"></i>@endif</button>
                                                                </div>
                                                            </td>
                                                    @else
                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->q1 ?? $grade->q3}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1" value="{{$grade->q1}}"/>@endif</td>
                                                    @endif
                                                    @if($grade->q2stat != 0)
                                                            <td class="text-center p-0">
                                                                <div class="row text-center p-0 m-0">
                                                                    <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline; font-size: 12px; height: 25px !important;;" @if($grade->q2stat == 2) value="{{$grade->q2}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade->q2stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="2"  data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}">@if($grade->q2stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs m-0"></i>@endif</button>
                                                                </div>
                                                            </td>
                                                    @else
                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->q2 ?? $grade->q4}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2" value="{{$grade->q2}}"/>@endif</td>
                                                    @endif

                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->finalrating}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-finalgrade" value="{{$grade->finalrating}}"/>@endif</td>
                                                    
                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->remarks ?? $grade->actiontaken}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{$grade->remarks}}"/>@endif</td>
                                                    @if($eachrecord->type == 1)  
                                                    <td>&nbsp;</td>            
                                                    @else
                                                    <th colspan="2"> <button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-default btn-deletesubject text-sm" data-id="{{$grade->id}}"><i class="fa fa-trash-alt"></i></button></th>
                                                    @endif
                                                </tr>
                                            @endforeach      
                                            @if($eachrecord->type == 1)
                                                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                                    <tr>
                                                        <td colspan="6" class="p-0 pl-2 pt-2"><em class="text-danger">Note: The added subjects are not included in General Average computation</em></td>
                                                        <td class="text-center p-0"><button type="button" class="btn btn-default btn-sm m-0 btn-block btn-addsubjinauto" data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-plus"></i> Subject</button></td>
                                                    </tr>
                                                @else         
                                                <tr>
                                                    <td colspan="6" class="p-0 pl-2 pt-2"><em class="text-danger"></em></td>
                                                    <td class="text-center p-0" ><button type="button" class="btn btn-default btn-sm m-0 btn-block btn-addsubjinauto" data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-plus"></i> Subject</button></td>
                                                </tr>
                                                @endif
                                                @if(count($eachrecord->subjaddedforauto)>0)
                                                    @foreach($eachrecord->subjaddedforauto as $customsubjgrade)
                                                        <tr>
                                                            <td class="p-0"><input type="text" class="form-control form-control-sm subjcode" value="{{$customsubjgrade->subjcode}}" disabled/></td>
                                                            <td class="p-0"><input type="text" class="form-control form-control-sm subjdesc" value="{{$customsubjgrade->subjdesc}}" disabled/></td>
                                                            <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjq1" value="{{$customsubjgrade->q1}}" disabled/></td>
                                                            <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjq2" value="{{$customsubjgrade->q2}}" disabled/></td>
                                                            <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjfinalrating" value="{{$customsubjgrade->finalrating}}" disabled/></td>
                                                            <td class="text-center p-0"><input type="text" class="form-control form-control-sm subjremarks" value="{{$customsubjgrade->actiontaken}}" disabled/></td>
                                                            <td class="text-right p-0">
                                                                <button type="button" class="btn btn-sm btn-default btn-subjauto-edit"><i class="fa fa-edit text-warning"></i></button><button type="button" class="btn btn-sm btn-default btn-subjauto-update" data-id="{{$customsubjgrade->id}}" disabled><i class="fa fa-share text-success"></i></button><button type="button" class="btn btn-sm btn-default btn-subjauto-delete" data-id="{{$customsubjgrade->id}}" disabled><i class="fa fa-trash text-danger"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @else                         
                                            <tr>
                                                <td colspan="8" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                            </tr>
                                            @endif        
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($eachrecord->type == 1)
                    @else
                        <div class="card-footer p-0 pr-1 pl-1">
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sjaes')
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table" style="width: 100%; font-size: 11px;">
                                        <tr>
                                            <th style="width: 15% !important;" class="text-right">Remarks:</th>
                                            <td colspan="3" style="width: 85% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-semremarks" value="{{$eachrecord->remarks}}"/></td>
                                        </tr>
                                        <tr>
                                            <th style="width: 15% !important;" class="text-right">Record's In-charge:</th>
                                            <td style="width: 60% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-recordsincharge" value="{{$eachrecord->recordincharge}}"/></td>
                                            <th style="width: 15% !important;" class="text-right">Date Checked:</th>
                                            <td style="width: 10% !important;"><input type="date" class="form-control form-control-sm p-0 input-norecord input-semdatechecked" value="{{$eachrecord->datechecked}}"/></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            @endif
                            <div class="row mb-2">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-sm btn-success btn-updaterecord" data-id="{{$eachrecord->id}}"><i class="fa fa-share"></i> Save changes</button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                @if(collect($records)->where('levelid', $gradelevel->id)->where('semid','2')->count() == 0)
                    <div class="card-header p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table m-0" style="font-size: 12px;">
                                    <tr>
                                        <td style="width: 15%;">School</td>
                                        <td colspan="7" style=""><input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolname"/></td>
                                    </tr>
                                    <tr>
                                        <td>School ID</td>
                                        <td colspan="7"><input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolid"/></td>
                                    </tr>
                                    <tr>
                                        <td>Grade Level</td>
                                        <td style="width: 30%; "><input type="text" class="form-control form-control-sm p-0 input-norecord input-levelid" data-id="{{$gradelevel->id}}" value="{{$gradelevel->levelname}}" readonly/></td>
                                        <td>SY</td>
                                        <td style="width: 15%; "><input type="text" class="form-control form-control-sm p-0 input-norecord input-sydesc"/></td>
                                        <td>Sem</td>
                                        <td style="width: 15%; "><input type="text" class="form-control form-control-sm p-0 input-norecord input-semid" data-id="2" value="2nd" readonly/></td>
                                    </tr>
                                    <tr>
                                        <td>Track</td>
                                        <td style="width: 15%; " colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-trackname"/></td>
                                        <td>Strand</td>
                                        <td style="width: 15%; " colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-strandname"/></td>
                                    </tr>
                                    <tr>
                                        <td>Section</td>
                                        <td style="width: 15%; " colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-sectionname"/></td>
                                        <td>Adviser</td>
                                        <td style="width: 15%; " colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-adviser"/></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0 pr-1 pl-1">
                        <div class="row">
                            <div class="col-md-12">
                                @php
                                    
                                    $defaultsubjects = collect($gradelevel->subjects)->where('semid', 2)->values();
                                @endphp
                                <table class="table table-striped" style="font-size: 11px; table-layout: fixed;">
                                    <thead class="text-center">
                                        <tr>
                                            <th style="width: 10%;">Indication</th>
                                            <th style="width: 40%;">Subjects</th>
                                            <th>Q1</th>
                                            <th>Q2</th>
                                            <th style="width: 10%;">Final Grade</th>
                                            <th style="width: 15%;">Action Taken</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($defaultsubjects)>0)
                                            @foreach($defaultsubjects as $defaultsubject)
                                                <tr>
                                                    <td style="width: 15%;"><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjcode" placeholder="Ex: Core" value="{{$defaultsubject->subjcode}}"/></td>
                                                    <td style="width: 30%;"><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjdesc" placeholder="Subject" value="{{ucwords(strtolower($defaultsubject->subjtitle))}}"/></td>
                                                    <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q1" placeholder="Grade"/></td>
                                                    <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q2" placeholder="Grade"/></td>
                                                    <td style="width: 15%;"><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-finalgrade" placeholder="Final Grade"/></td>
                                                    <td style="width: 15%;"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-remarks" placeholder="Action Taken"/></td>
                                                    <td colspan="2"><button type="button" class="btn btn-sm btn-block btn-default p-0 btn-deleterow"><small><i class="fa fa-trash-alt"></i></small></button></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td colspan="8" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer p-0 pr-1 pl-1">
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sjaes')
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="width: 100%; font-size: 11px;">
                                    <tr>
                                        <th style="width: 15% !important;" class="text-right">Remarks:</th>
                                        <td colspan="3" style="width: 85% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-semremarks"/></td>
                                    </tr>
                                    <tr>
                                        <th style="width: 15% !important;" class="text-right">Record's In-charge:</th>
                                        <td style="width: 60% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-incharge"/></td>
                                        <th style="width: 15% !important;" class="text-right">Date Checked:</th>
                                        <td style="width: 10% !important;"><input type="date" class="form-control form-control-sm p-0 input-norecord input-semdatechecked"/></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @endif
                        <div class="row mb-2">
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-sm btn-success btn-saverecord"><i class="fa fa-share"></i> Save this record</button>
                            </div>
                        </div>
                    </div>
                @else
                    @php
                        $eachrecord = collect($records)->where('levelid', $gradelevel->id)->where('semid','2')->first();
                        $grades = collect($records)->where('levelid', $gradelevel->id)->first()->grades;
                    @endphp
                    <div class="card-header p-0 pr-1 pl-1">
                        <div class="row">
                            @if($eachrecord->type == 1)
                                <div class="col-md-12"><span class="badge badge-warning">Auto Generated</span></div>
                            @else
                            <div class="col-md-6"><span class="badge badge-success">Manual</span> @if($eachrecord->syid == DB::table('sy')->where('isactive','1')->first()->id)<span class="badge badge-success">Current School Year</span>@endif</div>
                            @if($eachrecord->type==2)
                                <div class="col-md-6 text-right">
                                    <span class="badge badge-warning badge-clear-record" style="cursor: pointer;" data-id="{{$eachrecord->id}}">Clear This Record</span>
                                </div>
                            @endif
                            @endif
                            <div class="col-md-12">
                                <table class="table m-0" style="font-size: 12px;">
                                    <tr>
                                        <td style="width: 15%;">School</td>
                                        <td colspan="7" style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schoolname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolname" value="{{$eachrecord->schoolname}}"/>@endif </td>
                                    </tr>
                                    <tr>
                                        <td>School ID</td>
                                        <td colspan="7" style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schoolid}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolid" value="{{$eachrecord->schoolid}}"/>@endif</td>
                                    </tr>
                                    <tr>
                                        <td>Grade Level</td>
                                        <td style="width: 30%; border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->levelname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-levelid" data-id="{{$gradelevel->id}}" value="{{$gradelevel->levelname}}" readonly/>@endif</td>
                                        <td>SY</td>
                                        <td style="width: 20%; border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->sydesc}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-sydesc" value="{{$eachrecord->sydesc}}"/>@endif</td>
                                        <td>Sem</td>
                                        <td style="width: 20%; border-bottom: 1px solid black;">@if($eachrecord->type == 1)2nd @else<input type="text" class="form-control form-control-sm p-0 input-norecord input-semid" data-id="2" value="2nd" readonly/>@endif</td>
                                    </tr>
                                </table>
                                <table class="table m-0" style="font-size: 11px;">
                                    <tr>
                                        <td style="width: 20%;">Track</td>
                                        <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->trackname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-trackname" value="{{$eachrecord->trackname}}"/>@endif</td>
                                        <td>Strand</td>
                                        <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->strandname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-strandname" value="{{$eachrecord->strandname}}"/>@endif</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 20%;">Section</td>
                                        <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->sectionname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-sectionname" value="{{$eachrecord->sectionname}}"/>@endif</td>
                                        <td>Adviser</td>
                                        <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->teachername}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-adviser" value="{{$eachrecord->teachername}}"/>@endif</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0 pr-1 pl-1">
                        <div class="row">
                            <div class="col-md-12">
                                @if(count($grades) == 0)
                                {{-- <table class="table"></table> --}}
                                @else
                                    <table class="table table-striped" style="font-size: 12px; table-layout: fixed;">
                                        <thead class="text-center">
                                            <tr>
                                                <th style="width: 15%;">Indication</th>
                                                <th style="width: 30%;">Subjects</th>
                                                <th>Q1</th>
                                                <th>Q2</th>
                                                <th style="width: 15%;">Final Grade</th>
                                                <th style="width: 10%;">Action Taken</th>
                                                @if($eachrecord->type == 1)
                                                <th style="width: 12%;">&nbsp;</th>       
                                                @else
                                                <th colspan="2">Delete</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>     
                                            @foreach($grades as $grade)
                                                <tr @if($eachrecord->type != 1) class="eachsubject" @endif>
                                                    <td>@if($eachrecord->type == 1)@if(isset($grade->subjcode)) {{$grade->subjcode}} @endif @else<input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="{{$grade->id}}"/><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-subjcode" value="{{$grade->subjcode}}"/>@endif</td>
                                                    <td>@if($eachrecord->type == 1){{ucwords(strtolower($grade->subjdesc))}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-subjdesc" value="{{$grade->subjdesc}}"/>@endif</td>
                                                    
                                                    @if($grade->q1stat != 0)
                                                            <td class="text-center p-0">
                                                                <div class="row text-center p-0 m-0">
                                                                    <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline; font-size: 12px; height: 25px !important;;" @if($grade->q1stat == 2) value="{{$grade->q1}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade->q1stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="1"  data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}">@if($grade->q1stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs m-0"></i>@endif</button>
                                                                </div>
                                                            </td>
                                                    @else
                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->q1}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1" value="{{$grade->q1}}"/>@endif</td>
                                                    @endif
                                                    @if($grade->q2stat != 0)
                                                        <td class="text-center p-0">
                                                            <div class="row text-center p-0 m-0">
                                                                <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline; font-size: 12px; height: 25px !important;;" @if($grade->q2stat == 2) value="{{$grade->q2}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade->q2stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="2"  data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}">@if($grade->q2stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs m-0"></i>@endif</button>
                                                            </div>
                                                        </td>
                                                    @else
                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->q2}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2" value="{{$grade->q2}}"/>@endif</td>
                                                    @endif

                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->finalrating}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-finalgrade" value="{{$grade->finalrating}}"/>@endif</td>
                                                    <td class="text-center">@if($eachrecord->type == 1){{isset($grade->actiontaken) ? $grade->actiontaken : $grade->remarks}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{isset($grade->actiontaken) ? $grade->actiontaken : $grade->remarks}}"/>@endif</td>
                                                    @if($eachrecord->type == 1)
                                                    <td>&nbsp;</td>            
                                                    @else
                                                    <th colspan="2"> <button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-default btn-deletesubject text-sm" data-id="{{$grade->id}}"><i class="fa fa-trash-alt"></i></button></th>
                                                    @endif
                                                </tr>
                                            @endforeach         
                                            @if($eachrecord->type == 1)
                                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                                <tr>
                                                    <td colspan="6" class="p-0 pl-2 pt-2"><em class="text-danger">Note: The added subjects are not included in General Average computation</em></td>
                                                    <td class="text-center p-0"><button type="button" class="btn btn-default btn-sm m-0 btn-block btn-addsubjinauto" data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-plus"></i> Subject</button></td>
                                                </tr>
                                            @else         
                                            <tr>
                                                <td colspan="6" class="p-0 pl-2 pt-2"><em class="text-danger"></em></td>
                                                <td class="text-center p-0" ><button type="button" class="btn btn-default btn-sm m-0 btn-block btn-addsubjinauto" data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-plus"></i> Subject</button></td>
                                            </tr>
                                            @endif
                                            @if(count($eachrecord->subjaddedforauto)>0)
                                                @foreach($eachrecord->subjaddedforauto as $customsubjgrade)
                                                    <tr>
                                                        <td class="p-0"><input type="text" class="form-control form-control-sm subjcode" value="{{$customsubjgrade->subjcode}}" disabled/></td>
                                                        <td class="p-0"><input type="text" class="form-control form-control-sm subjdesc" value="{{$customsubjgrade->subjdesc}}" disabled/></td>
                                                        <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjq1" value="{{$customsubjgrade->q1}}" disabled/></td>
                                                        <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjq2" value="{{$customsubjgrade->q2}}" disabled/></td>
                                                        <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjfinalrating" value="{{$customsubjgrade->finalrating}}" disabled/></td>
                                                        <td class="text-center p-0"><input type="text" class="form-control form-control-sm subjremarks" value="{{$customsubjgrade->actiontaken}}" disabled/></td>
                                                        <td class="text-right p-0">
                                                            <button type="button" class="btn btn-sm btn-default btn-subjauto-edit"><i class="fa fa-edit text-warning"></i></button><button type="button" class="btn btn-sm btn-default btn-subjauto-update" data-id="{{$customsubjgrade->id}}" disabled><i class="fa fa-share text-success"></i></button><button type="button" class="btn btn-sm btn-default btn-subjauto-delete" data-id="{{$customsubjgrade->id}}" disabled><i class="fa fa-trash text-danger"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            @else                         
                                            <tr>
                                                <td colspan="8" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                            </tr>
                                            @endif    
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($eachrecord->type == 1)
                    @else
                    <div class="card-footer p-0 pr-1 pl-1">
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sjaes')
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="width: 100%; font-size: 11px;">
                                    <tr>
                                        <th style="width: 15% !important;" class="text-right">Remarks:</th>
                                        <td colspan="3" style="width: 85% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-semremarks" value="{{$eachrecord->remarks}}"/></td>
                                    </tr>
                                    <tr>
                                        <th style="width: 15% !important;" class="text-right">Record's In-charge:</th>
                                        <td style="width: 60% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-recordsincharge" value="{{$eachrecord->recordincharge}}"/></td>
                                        <th style="width: 15% !important;" class="text-right">Date Checked:</th>
                                        <td style="width: 10% !important;"><input type="date" class="form-control form-control-sm p-0 input-norecord input-semdatechecked" value="{{$eachrecord->datechecked}}"/></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @endif
                        <div class="row mb-2">
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-sm btn-success btn-updaterecord" data-id="{{$eachrecord->id}}"><i class="fa fa-share"></i> Save changes</button>
                            </div>
                        </div>
                    </div>
                    @endif
                @endif
            </div>
        </div>
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mcs' && $sydesc != null && $syid == 0)
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-1 bg-success">
                        <div class="row">
                            <div class="col-md-8 text-bold">
                                @if($sydesc == null)
                                Attendance
                                @else
                                {{$sydesc}} Attendance
                                @endif
                            </div>
                            <div class="col-md-4 text-right">
                                @if($sydesc == null)
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control form-control-sm m-0" placeholder="ex: 2019-2020" name="attendance-sydesc"/>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-sm btn-default btn-block btn-updateatt" data-studid="{{$studinfo->id}}"><i class="fa fa-share"></i> Save</button>
                                        </div>
                                    </div>
                                @else
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="text" class="form-control form-control-sm m-0" placeholder="ex: 2019-2020" name="attendance-sydesc" value="{{$sydesc}}"/>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-sm btn-default btn-block btn-updateatt" data-studid="{{$studinfo->id}}"><i class="fa fa-share"></i> Save</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0 pl-1 pr-1">
                        <table class="table">
                            <thead style="font-size: 11px;">
                                <tr>
                                    <th style="width: 15%;"></th>
                                    <th>JUNE</th>
                                    <th>JULY</th>
                                    <th>AUGUST</th>
                                    <th>SEPTEMBER</th>
                                    <th>OCTOBER</th>
                                    <th>NOVEMBER</th>
                                    <th>DECEMBER</th>
                                    <th>JANUARY</th>
                                    <th>FEBRUARY</th>
                                    <th>MARCH</th>
                                    {{-- <th>TOTAL</th> --}}
                                </tr>
                            </thead>
                            <tr style="font-size: 11px;">
                                <th style="vertical-align: middle;">Days of School</th>
                                <td style="padding: 0px;">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthdays" name="JUNE" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'JUNE')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'JUNE')->first()->numdays}}" @endif/>
                                </td>
                                <td style="padding: 0px;">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthdays" name="JULY" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'JULY')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'JULY')->first()->numdays}}" @endif/>
                                </td>
                                <td style="padding: 0px;">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthdays" name="AUGUST" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'AUGUST')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'AUGUST')->first()->numdays}}" @endif/>
                                </td>
                                <td style="padding: 0px;">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthdays" name="SEPTEMBER" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'SEPTEMBER')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'SEPTEMBER')->first()->numdays}}" @endif/>
                                </td>
                                <td style="padding: 0px;">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthdays" name="OCTOBER" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'OCTOBER')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'OCTOBER')->first()->numdays}}" @endif/>
                                </td>
                                <td style="padding: 0px;">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthdays" name="NOVEMBER" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'NOVEMBER')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'NOVEMBER')->first()->numdays}}" @endif/>
                                </td>
                                <td style="padding: 0px;">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthdays" name="DECEMBER" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'DECEMBER')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'DECEMBER')->first()->numdays}}" @endif/>
                                </td>
                                <td style="padding: 0px;">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthdays" name="JANUARY" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'JANUARY')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'JANUARY')->first()->numdays}}" @endif/>
                                </td>
                                <td style="padding: 0px;">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthdays" name="FEBRUARY" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'FEBRUARY')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'FEBRUARY')->first()->numdays}}" @endif/>
                                </td>
                                <td style="padding: 0px;">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthdays" name="MARCH" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'MARCH')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'MARCH')->first()->numdays}}" @endif/>
                                </td>
                                {{-- <td></td> --}}
                            </tr>
                            <tr style="font-size: 11px;">
                                <th style="vertical-align: middle;">Days Present</th>
                                <td style="padding: 0px;" class="tdeachpresent">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthpresent" name="JUNE" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'JUNE')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'JUNE')->first()->numdayspresent}}" @endif/>
                                </td>
                                <td style="padding: 0px;" class="tdeachpresent">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthpresent" name="JULY" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'JULY')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'JULY')->first()->numdayspresent}}" @endif/>
                                </td>
                                <td style="padding: 0px;" class="tdeachpresent">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthpresent" name="AUGUST" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'AUGUST')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'AUGUST')->first()->numdayspresent}}" @endif/>
                                </td>
                                <td style="padding: 0px;" class="tdeachpresent">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthpresent" name="SEPTEMBER" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'SEPTEMBER')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'SEPTEMBER')->first()->numdayspresent}}" @endif/>
                                </td>
                                <td style="padding: 0px;" class="tdeachpresent">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthpresent" name="OCTOBER" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'OCTOBER')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'OCTOBER')->first()->numdayspresent}}" @endif/>
                                </td>
                                <td style="padding: 0px;" class="tdeachpresent">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthpresent" name="NOVEMBER" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'NOVEMBER')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'NOVEMBER')->first()->numdayspresent}}" @endif/>
                                </td>
                                <td style="padding: 0px;" class="tdeachpresent">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthpresent" name="DECEMBER" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'DECEMBER')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'DECEMBER')->first()->numdayspresent}}" @endif/>
                                </td>
                                <td style="padding: 0px;" class="tdeachpresent">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthpresent" name="JANUARY" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'JANUARY')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'JANUARY')->first()->numdayspresent}}" @endif/>
                                </td>
                                <td style="padding: 0px;" class="tdeachpresent">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthpresent" name="FEBRUARY" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'FEBRUARY')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'FEBRUARY')->first()->numdayspresent}}" @endif/>
                                </td>
                                <td style="padding: 0px;" class="tdeachpresent">
                                    <input type="number" class="form-control form-control-sm p-1 m-0 eachmonthpresent" name="MARCH" style="height: 25px;" @if(collect($recordatt)->where('monthdesc', 'MARCH')->count() > 0) value="{{collect($recordatt)->where('monthdesc', 'MARCH')->first()->numdayspresent}}" @endif/>
                                </td>
                                {{-- <td></td> --}}
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
<div class="card">
    <div class="card-header bg-info">
        &nbsp;
    </div>
    <div class="card-body" style="font-size: 13px;">
        <div class="row mb-4">
            <div class="col-md-3">Track/Strand Accomplished: </div>
            <div class="col-md-4"><input type="text" class="form-control" id="footerstrandaccomplished" placeholder="Enter text here" value="{{$footer->strandaccomplished}}"/></div>
            <div class="col-md-3">SHS General Average: </div>
            <div class="col-md-2"><input type="number" class="form-control" id="footergenave" value="{{$footer->shsgenave}}"/></div>
        </div>
        <div class="row mb-4">
            <div class="col-md-8">
                <label>Awards/Honors Received:</label><br/>
                <textarea id="footerhonorsreceived" class="form-control">{{$footer->honorsreceived}}</textarea>
            </div>
            <div class="col-md-4">
                <label>Date of SHS Garduation:</label><br/>
                <input type="date" class="form-control" id="footerdategrad" value="{{$footer->shsgraduationdateshow}}"/>
            </div>
        </div>
        
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hcb')
            <div class="row mb-5">
                <div class="col-md-2">COPY FOR: </div>
                <div class="col-md-4">
                    <table>
                        <tr>
                            <td style="border-bottom: 1px solid black;">
                                <input type="text" class="form-control" id="footercopyforupper" placeholder="Enter text here" value="{{$footer->copyforupper}}"/>
                            </td>
                        </tr>
                        
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hcb')
                            <tr>
                                <td>
                                    <input type="text" class="form-control" id="footercopyforlower" placeholder="Enter text here" value="{{$footer->copyforlower}}"/>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                <div class="col-md-2">Date Certified: </div>
                <div class="col-md-4"><input type="date" class="form-control" id="footerdatecertified" value="{{$footer->datecertifiedshow}}"/></div>
            </div>
        @endif
        <div class="row mb-5">
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
                <div class="col-md-2">REMARKS: </div>
                <div class="col-md-4">
                    <table>
                        <tr>
                            <td style="border-bottom: 1px solid black;">
                                <input type="text" class="form-control" id="footercopyforupper" placeholder="Enter text here" value="{{$footer->copyforupper}}"/>
                            </td>
                        </tr>
                        
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hcb')
                            <tr>
                                <td>
                                    <input type="text" class="form-control" id="footercopyforlower" placeholder="Enter text here" value="{{$footer->copyforlower}}"/>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            @endif
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
            <div class="col-md-6">
                <label>Registrar</label><br/>
                <input type="text" class="form-control" id="footerregistrar" placeholder="Enter text here" value="{{$footer->registrar ?? null}}"/>
            </div>
            @endif
            <div class="col-md-12 text-right d-block">
                <button type="button" class="btn btn-primary" id="btn-savefooter"><i class="fa fa-share"></i> Save Changes</button>
            </div>
        </div>
        {{-- <div class="row mb-5">
        </div> --}}
    </div>
</div>
{{-- '<tr>'+
    '<td style="width: 15%;"><input type="text" class="form-control form-control-sm p-0 input-norecord" placeholder="Ex: Core"/></td>'+
    '<td style="width: 30%;"><input type="text" class="form-control form-control-sm p-0 input-norecord" placeholder="Subject"/></td>'+
    '<td><input type="text" class="form-control form-control-sm p-0 input-norecord" placeholder="Grade"/></td>'+
    '<td><input type="text" class="form-control form-control-sm p-0 input-norecord" placeholder="Grade"/></td>'+
    '<td style="width: 15%;"><input type="text" class="form-control form-control-sm p-0 input-norecord" placeholder="Final Grade"/></td>'+
    '<td style="width: 15%;"><input type="text" class="form-control form-control-sm p-0 input-norecord" placeholder="Action Taken"/></td>'+
    '<td><button type="button" class="btn btn-sm btn-block btn-default p-0 btn-saverow"><small><i class="fa fa-share"></i> Save</small></button></td>'+
    '<td><button type="button" class="btn btn-sm btn-block btn-default p-0 btn-deleterow"><small><i class="fa fa-trash-alt"></i></small></button></td>'+
'</tr>' --}}
<script>
    $('.btn-saverecord').hide()
    $('.btn-addrow').on('click', function(){
        var thistbody = $(this).closest('tbody');
        thistbody.append(
            '<tr class="eachsubject">'+
                '<td style="width: 15%;"><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="0"/><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjcode" placeholder="Ex: Core"/></td>'+
                '<td style="width: 30%;"><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjdesc" placeholder="Subject"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q1" placeholder="Grade"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q2" placeholder="Grade"/></td>'+
                '<td style="width: 15%;"><input type="number" class="form-control form-control-sm p-0 input-norecord new-inpuy input-finalgrade" placeholder="Final Grade"/></td>'+
                '<td style="width: 15%;"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-remarks" placeholder="Action Taken"/></td>'+
                '<td colspan="2"><button type="button" class="btn btn-sm btn-block btn-default p-0 btn-deleterow"><small><i class="fa fa-trash-alt"></i></small></button></td>'+
            '</tr>'
        )
    })
    $(document).on('click','.btn-deleterow', function(){
        $(this).closest('tr').remove()
    })
    $(document).on('input','.input-norecord', function(){
        $(this).closest('.card').find('.btn-saverecord').show()
    })
    $('.btn-saverecord').on('click', function(){
        var thiscardheader = $(this).closest('.card').find('.card-header');
        var schoolname = thiscardheader.find('.input-schoolname').val();
        var schoolid = thiscardheader.find('.input-schoolid').val();
        var gradelevelid = thiscardheader.find('.input-levelid').attr('data-id');
        var sectionname = thiscardheader.find('.input-sectionname').val();
        var schoolyear = thiscardheader.find('.input-sydesc').val();
        var semester = thiscardheader.find('.input-semid').attr('data-id');
        var trackname = thiscardheader.find('.input-trackname').val();
        var strandname = thiscardheader.find('.input-strandname').val();
        var teachername = thiscardheader.find('.input-adviser').val();
        
        var thistbody = $(this).closest('.card').find('.card-body');
        var thistrs = thistbody.find('tr.eachsubject');
        var subjects = [];
        thistrs.each(function(){
            var subjcode    = $(this).find('.input-subjcode').val();
            var subjdesc    = $(this).find('.input-subjdesc').val();
            var q1          = $(this).find('.input-q1').val();
            var q2          = $(this).find('.input-q2').val();
            var finalgrade  = $(this).find('.input-finalgrade').val();
            var remarks     = $(this).find('.input-remarks').val();
            if (subjdesc != null){
                if(subjdesc.replace(/ /g,'').length > 0)
                {
                    if(subjcode.replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        subjcode = " ";
                    }
                    if(q1.replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        q1 = 0;
                    }
                    if(q2.replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        q2 = 0;
                    }
                    if(finalgrade.replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        finalgrade = 0;
                    }
                    if(remarks.replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        remarks = "";
                    }

                    obj = {
                        subjcode      : subjcode,
                        subjdesc      : subjdesc,
                        q1              : q1,
                        q2          : q2,
                        final      : finalgrade,
                        remarks      : remarks,
                        fromsystem   : 0,
                        editablegrades   : 0,
                        inmapeh   : 0,
                        intle   : 0
                    };
                    subjects.push(obj);
                }
            }
        })
        if(subjects.length == 0)
        {
                            toastr.warning('Empty Subjects!')
        }
        // else{
            var semesterremarks = $(this).closest('.card').find('.input-semremarks').val();
            var recordsincharge = $(this).closest('.card').find('.input-recordsincharge').val();
            var datechecked = $(this).closest('.card').find('.input-semdatechecked').val();
            
            $.ajax({
                url: '/reports_schoolform10/submitnewform',
                type: 'GET',
                data:{
                    studentid           : '{{$studinfo->id}}',
                    acadprogid          : 5,
                    schoolname          :   schoolname,
                    schoolid            :   schoolid,
                    gradelevelid        :   gradelevelid,
                    trackname           :   trackname,
                    strandname          :   strandname,
                    sectionname         :   sectionname,
                    schoolyear          :   schoolyear,
                    semester            :   semester,
                    teachername         :   teachername,
                    recordsincharge     :   recordsincharge,
                    datechecked         :   datechecked,
                    // indications         :   indications,
                    subjects            :   JSON.stringify(subjects),
                    semesterremarks     :   semesterremarks

                }, success:function(data)
                {
                            toastr.success('Record added successfully!')
                            $('#btn-reload').click();
                    $('#addcontainer').empty()
                    $('#addrecord').prop('disabled',false)
                    getrecords();
                }
            });
        // }
    })
    $('.btn-deletesubject').on('click', function(){
        var id = $(this).attr('data-id')
        var thisrow = $(this).closest('tr');
        Swal.fire({
            title: 'Are you sure you want to delete this?',
            html: 'You won\'t be able to revert this!',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading()
        }).then((reasoninput) => {
            if (reasoninput.value) {
                billedamount = 0.00;
                stipend = 0.00;
                disabilityamount = 0.00;
                $.ajax({
                    url: '/reports_schoolform10/deleterecord',
                    type: 'GET',
                    data: {
                        action          : 'subject',
                        id              : id,
                        acadprogid      : 5
                    },
                    success:function(data){
                        if(data == 1)
                        {
                            toastr.success('Deleted successfully!')
                            thisrow.remove()

                        }else{
                            toastr.error('Something went wrong!')
                        }
                    }
                })
            }
        })
    })
    $('.btn-updaterecord').on('click', function(){
        var id = $(this).attr('data-id')
        var thiscardheader = $(this).closest('.card').find('.card-header');
        var schoolname = thiscardheader.find('.input-schoolname').val();
        var schoolid = thiscardheader.find('.input-schoolid').val();
        var gradelevelid = thiscardheader.find('.input-levelid').attr('data-id');
        var sectionname = thiscardheader.find('.input-sectionname').val();
        var schoolyear = thiscardheader.find('.input-sydesc').val();
        var semester = thiscardheader.find('.input-semid').attr('data-id');
        var trackname = thiscardheader.find('.input-trackname').val();
        var strandname = thiscardheader.find('.input-strandname').val();
        var teachername = thiscardheader.find('.input-adviser').val();
        var thistbody = $(this).closest('.card').find('.card-body');
        var thistrs = thistbody.find('tr.eachsubject');
        var subjects = [];
        thistrs.each(function(){
            if($(this).find('input').length >0)
            {
                var subjid    = $(this).find('.input-subjid').val();
                var subjcode    = $(this).find('.input-subjcode').val();
                var subjdesc    = $(this).find('.input-subjdesc').val();
                var q1          = $(this).find('.input-q1').val();
                var q2          = $(this).find('.input-q2').val();
                var finalgrade  = $(this).find('.input-finalgrade').val();
                var remarks     = $(this).find('.input-remarks').val();
                // if (subjcode != null && subjdesc != null && q1 != null && q2 != null && finalgrade != null && remarks != null){
                    if(subjdesc.replace(/ /g,'').length > 0)
                    {
                        if(subjcode.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            subjcode = " ";
                        }
                        if(q1.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            q1 = 0;
                        }
                        if(q2.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            q2 = 0;
                        }
                        if(finalgrade.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            finalgrade = 0;
                        }
                        if(remarks.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            remarks = "";
                        }

                        obj = {
                            id      : subjid,
                            subjcode      : subjcode,
                            subjdesc      : subjdesc,
                            q1              : q1,
                            q2          : q2,
                            final      : finalgrade,
                            remarks      : remarks,
                            fromsystem   : 0,
                            editablegrades   : 0,
                            inmapeh   : 0,
                            intle   : 0
                        };
                        subjects.push(obj);
                    }
                // }
            }
        })
        
        if(subjects.length == 0)
        {
            toastr.warning('No Subjects detected!')
        }
        // else{
            var semesterremarks = $(this).closest('.card').find('.input-semremarks').val();
            var recordsincharge = $(this).closest('.card').find('.input-recordsincharge').val();
            var datechecked = $(this).closest('.card').find('.input-semdatechecked').val();
            
            $.ajax({
                url: '/reports_schoolform10/updateform',
                type: 'POST',
                data:{
                    studentid           : '{{$studinfo->id}}',
                    acadprogid          : 5,
                    id          : id,
                    schoolname          :   schoolname,
                    schoolid            :   schoolid,
                    gradelevelid        :   gradelevelid,
                    trackname           :   trackname,
                    strandname          :   strandname,
                    sectionname         :   sectionname,
                    schoolyear          :   schoolyear,
                    semester            :   semester,
                    teachername         :   teachername,
                    recordsincharge     :   recordsincharge,
                    datechecked         :   datechecked,
                    // indications         :   indications,
                    subjects            :   JSON.stringify(subjects),
                    semesterremarks     :   semesterremarks

                }, success:function(data)
                {
                        toastr.success('Record updated successfully!')
                            $('#btn-reload').click();
                    $('#addcontainer').empty()
                    $('#addrecord').prop('disabled',false)
                }
            });
        // }
    })
    $('.badge-clear-record').on('click', function(){
        var id = $(this).attr('data-id')
        Swal.fire({
            title: 'Are you sure you want to clear this record?',
            html: 'You won\'t be able to revert this!',
            showCancelButton: true,
            confirmButtonText: 'Clear',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading()
        }).then((reasoninput) => {
            if (reasoninput.value) {
                billedamount = 0.00;
                stipend = 0.00;
                disabilityamount = 0.00;
                $.ajax({
                    url: '/reports_schoolform10/deleterecord',
                    type: 'GET',
                    data: {
                        // action          : 'record',
                        id              : id,
                        acadprogid      : 5
                    },
                    success:function(data){
                        if(data == 1)
                        {
                            toastr.success('Deleted successfully!')
                            $('#btn-reload').click();
                            thisrow.remove()

                        }else{
                            toastr.error('Something went wrong!')
                        }
                    }
                })
            }
        })
    })
    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mcs')
    $('.btn-updateatt').on('click', function(){
        var studentid = $(this).attr('data-studid');
        var sydesc = $(this).closest('.card').find('input[name="attendance-sydesc"]').val();
        var monthinputs =  $(this).closest('.card').find('.eachmonthdays');
        var months = [];
        var atttable = $(this).closest('.card').find('.table');
        var atttbody = $(this).closest('.card').find('tbody');
        monthinputs.each(function(){
            var cellindex = $(this).parent().index();
            var tdpresent = atttbody[0].children[1].children[cellindex];
        // console.log(tdpresent)
            var eachmonthvalue = $(this).val();
            var eachmonthpresent = $(tdpresent).find('input').val();
            if(eachmonthvalue.replace(/^\s+|\s+$/g, "").length == 0)
            {
                eachmonthvalue = 0
            }
            if(eachmonthpresent.replace(/^\s+|\s+$/g, "").length == 0)
            {
                eachmonthpresent = 0
            }
            obj = {
                monthdesc : $(this).attr('name'),
                numdays : eachmonthvalue,
                numdayspresent : eachmonthpresent
            }
            months.push(obj)
        })
        $.ajax({
                url: '/reports_schoolform10/updateattendance',
            type: 'GET',
            data: {
                // action          : 'record',
                studentid      : studentid,
                sydesc         : sydesc,
                acadprogid     : 5,
                attendance         : JSON.stringify(months)
            },
            success:function(data){
                if(data == 1)
                {
                    toastr.success('Attendance updated successfully!')

                }else{
                    toastr.error('Something went wrong!')
                }
            }
        })
    })
    @endif

</script>