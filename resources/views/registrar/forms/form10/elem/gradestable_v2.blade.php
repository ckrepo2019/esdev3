<style>
    table td, table th{
        padding: 0px !important;
    }
    .input-norecord{
        height: unset;
    }
</style>

<div class="row">
    <div class="col-12">    
        <div class="card">
            <div class="card-header d-flex p-0">
                <ul class="nav nav-pills ml-auto p-2">
                    @foreach ($gradelevels as $key=>$gradelevel)
                    <li class="nav-item"><a class="nav-link @if($key == 0) active @endif" href="#tab_{{$key}}" data-toggle="tab">{{$gradelevel->levelname}}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    @foreach ($gradelevels as $key=>$gradelevel)
                        <div class="tab-pane  @if($key == 0) active @endif" id="tab_{{$key}}">                            
                                @if(collect($records)->where('levelid', $gradelevel->id)->count() == 0)                                    
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table m-0" style="font-size: 12px; table-layout: fixed;">
                                                    <tr>
                                                        <td style="width: 15%;">School</td>
                                                        <td colspan="5" style=""><input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolname"/></td>
                                                    </tr>
                                                    <tr>
                                                        <td>School ID</td>
                                                        <td colspan="5"><input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolid"/></td>
                                                    </tr>
                                                    <tr>
                                                        <td>District</td>
                                                        <td><input type="text" class="form-control form-control-sm p-0 input-norecord input-district"/></td>
                                                        <td>Division</td>
                                                        <td><input type="text" class="form-control form-control-sm p-0 input-norecord input-division"/></td>
                                                        <td>Region</td>
                                                        <td ><input type="text" class="form-control form-control-sm p-0 input-norecord input-region"/></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Grade Level</td>
                                                        <td colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-levelid" data-id="{{$gradelevel->id}}" value="{{$gradelevel->levelname}}" readonly/></td>
                                                        <td>School Year</td>
                                                        <td style="width: 15%; "><input type="text" class="form-control form-control-sm p-0 input-norecord input-sydesc"/></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Section</td>
                                                        <td colspan="2"><input type="text" class="form-control form-control-sm p-0 input-norecord input-sectionname"/></td>
                                                        <td>Adviser</td>
                                                        <td style="" colspan="2"><input type="text" class="form-control form-control-sm p-0 input-norecord input-adviser"/></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    
                                    
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-striped" style="font-size: 11px; table-layout: fixed;">
                                                    <thead class="text-center">
                                                        <tr>
                                                            <th>Indent</th>
                                                            <th style="width: 30%;">Subjects</th>
                                                            <th>1st</th>
                                                            <th>2nd</th>
                                                            <th>3rd</th>
                                                            <th>4th</th>
                                                            <th style="width: 8%;">Final</th>
                                                            <th style="width: 15%;">Remarks</th>
                                                            <th style="width: 8%;">Credit Earned</th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="11" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="gradescontainer">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    
                                    
                                        <div class="row">
                                            <div class="col-md-12 text-right">
                                                <button type="button" class="btn btn-sm btn-success btn-saverecord"><i class="fa fa-share"></i> Save this record</button>
                                            </div>
                                        </div>
                                    
                                @else
                                @php
                                    $eachrecord = collect($records)->where('levelid', $gradelevel->id)->first();
                                    $grades = collect($records)->where('levelid', $gradelevel->id)->first()->grades;
                                @endphp
                                    
                                        <div class="row">
                                            @if($eachrecord->type == 1)
                                                <div class="col-md-6"><span class="badge badge-warning">Auto Generated: You cannot revise this record</span> @if($eachrecord->sydesc == DB::table('sy')->where('isactive','1')->first()->sydesc)<span class="badge badge-success">Current School Year</span>@endif</div>
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
                                                        <td colspan="5" style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schoolname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolname" value="{{$eachrecord->schoolname}}"/>@endif </td>
                                                    </tr>
                                                    <tr>
                                                        <td>School ID</td>
                                                        <td colspan="5" style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schoolid}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolid" value="{{$eachrecord->schoolid}}"/>@endif</td>
                                                    </tr>
                                                    <tr>
                                                        <td>District</td>
                                                        <td style="width: 20%; border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schooldistrict}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-istrict" value="{{$eachrecord->schooldistrict}}"/>@endif</td>
                                                        <td>Division</td>
                                                        <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schooldivision}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-division" value="{{$eachrecord->schooldivision}}"/>@endif</td>
                                                        <td>Region</td>
                                                        <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schoolregion}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-region" value="{{$eachrecord->schoolregion}}"/>@endif</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Grade Level</td>
                                                        <td style="border-bottom: 1px solid black;" colspan="3">@if($eachrecord->type == 1){{$eachrecord->levelname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-levelid" data-id="{{$gradelevel->id}}" value="{{$gradelevel->levelname}}" readonly/>@endif</td>
                                                        <td>School Year</td>
                                                        <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->sydesc}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-sydesc" value="{{$eachrecord->sydesc}}"/>@endif</td>
                                                    </tr>
                                                </table>
                                                <table class="table m-0" style="font-size: 11px;">
                                                    <tr>
                                                        <td style="width: 15%;">Section</td>
                                                        <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->sectionname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-sectionname" value="{{$eachrecord->sectionname}}"/>@endif</td>
                                                        <td>Adviser</td>
                                                        <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->teachername}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-adviser" value="{{$eachrecord->teachername}}"/>@endif</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    
                                    
                                        <div class="row">
                                            <div class="col-md-12">
                                                @if(count($grades) == 0)
                                                    @if($eachrecord->type == 1)
                                                    <table class="table table-striped" style="font-size: 12px;">
                                                        <thead class="text-center">
                                                            <tr>
                                                                <th style="width: 30%;">Subjects</th>
                                                                <th>1st</th>
                                                                <th>2nd</th>
                                                                <th>3rd</th>
                                                                <th>4th</th>
                                                                <th style="width: 8%;">Final</th>
                                                                <th style="width: 15%;">Remarks</th>
                                                                <th style="width: 8%;">Credit Earned</th>
                                                                @if($eachrecord->type == 1)
                                                                @else
                                                                <th colspan="2">Delete</th>
                                                                @endif
                                                            </tr>
                                                        </thead>
                                                        @if($eachrecord->type == 1)
                                                        @else                         
                                                        <tr>
                                                            <td colspan="11" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                                        </tr>
                                                        @endif 
                                                        <tbody class="gradescontainer">    
                                                        </tbody>
                                                    </table>
                                                    @else
                                                    <table class="table table-striped" style="font-size: 12px;">
                                                        <thead class="text-center">
                                                            <tr>
                                                                <th>Indent</th>
                                                                <th style="width: 30%;">Subjects</th>
                                                                <th>1st</th>
                                                                <th>2nd</th>
                                                                <th>3rd</th>
                                                                <th>4th</th>
                                                                <th style="width: 8%;">Final</th>
                                                                <th style="width: 15%;">Remarks</th>
                                                                <th style="width: 8%;">Credit Earned</th>
                                                                <th colspan="2">Delete</th>
                                                            </tr>
                                                        </thead>                 
                                                        <tr>
                                                            <td colspan="11" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                                        </tr>
                                                        <tbody class="gradescontainer">    
                                                        </tbody>
                                                    </table>
                                                    @endif
                
                                                @else
                                                    @if($eachrecord->type == 1)
                                                    <table class="table table-striped" style="font-size: 12px;">
                                                        <thead class="text-center">
                                                            <tr>
                                                                <th style="width: 30%;">Subjects</th>
                                                                <th>1st</th>
                                                                <th>2nd</th>
                                                                <th>3rd</th>
                                                                <th>4th</th>
                                                                <th style="width: 15%;">Final</th>
                                                                <th style="width: 15%;">Remarks</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="gradescontainer">     
                                                            @foreach($grades as $grade)
                                                                <tr>
                                                                    <td>@if($eachrecord->type == 1){{ucwords(strtolower($grade->subjdesc))}}@else<input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="{{$grade->id}}"/><input type="text" class="form-control form-control-sm p-0 input-norecord input-subjdesc" value="{{$grade->subjdesc}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->q1}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1" value="{{$grade->q1}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->q2}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2" value="{{$grade->q2}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->q3}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q3" value="{{$grade->q3}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->q4}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q4" value="{{$grade->q4}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->finalrating > 0 ? $grade->finalrating : ''}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-finalgrade" value="{{$grade->finalrating}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{isset($grade->actiontaken) ? $grade->actiontaken : $grade->remarks}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{isset($grade->actiontaken) ? $grade->actiontaken : $grade->remarks}}"/>@endif</td>
                                                                </tr>
                                                            @endforeach  
                                                            @if(count($eachrecord->generalaverage)>0)
                                                                <tr>
                                                                    <td>@if($eachrecord->type == 1){{ucwords(strtolower(collect($eachrecord->generalaverage)->first()->subjdesc))}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-subjdesc" value="{{collect($eachrecord->generalaverage)->first()->subjdesc}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{collect($eachrecord->generalaverage)->first()->q1}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1" value="{{collect($eachrecord->generalaverage)->first()->q1}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{collect($eachrecord->generalaverage)->first()->q2}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2" value="{{collect($eachrecord->generalaverage)->first()->q2}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{collect($eachrecord->generalaverage)->first()->q3}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1" value="{{collect($eachrecord->generalaverage)->first()->q3}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{collect($eachrecord->generalaverage)->first()->q4}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2" value="{{collect($eachrecord->generalaverage)->first()->q4}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{collect($eachrecord->generalaverage)->first()->finalrating}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-finalgrade" value="{{collect($eachrecord->generalaverage)->first()->finalrating}}"/>@endif</td>
                                                                    <td class="text-center">
                                                                        @if($eachrecord->type == 1)
                                                                            @if(isset(collect($eachrecord->generalaverage)->first()->actiontaken) || isset(collect($eachrecord->generalaverage)->first()->remarks))
                                                                            {{ collect($eachrecord->generalaverage)->first()->actiontaken ?? collect($eachrecord->generalaverage)->first()->remarks}}
                                                                            @endif
                                                                            @if(collect($eachrecord->generalaverage)->contains('actiontaken'))
                                                                                <input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{collect($eachrecord->generalaverage)->first()->actiontaken}}"/>
                                                                            @else
                                                                                @if(collect($eachrecord->generalaverage)->contains('remarks'))
                                                                                    <input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{collect($eachrecord->generalaverage)->first()->remarks}}"/>
                                                                                @endif
                                                                            @endif
                                                                        @else
                                                                            @if(isset(collect($eachrecord->generalaverage)->first()->actiontaken) || isset(collect($eachrecord->generalaverage)->first()->remarks))                                                        
                                                                                <input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{ collect($eachrecord->generalaverage)->first()->actiontaken ?? collect($eachrecord->generalaverage)->first()->remarks}}"/>
                                                                            @endif
                                                                        @endif
                                                                    </td>
                                                                    @if($eachrecord->type == 1)
                                                                    @else
                                                                    <th colspan="2"> <button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-default btn-deletesubject text-sm" data-id="{{$grade->id}}"><i class="fa fa-trash-alt"></i></button></th>
                                                                    @endif
                                                                </tr>
                                                            @endif  
                                                        </tbody>
                                                    </table>
                                                    @else
                                                    <table class="table table-striped" style="font-size: 12px;">
                                                        <thead class="text-center">
                                                            <tr>
                                                                <th>Indent</th>
                                                                <th style="width: 30%;">Subjects</th>
                                                                <th>1st</th>
                                                                <th>2nd</th>
                                                                <th>3rd</th>
                                                                <th>4th</th>
                                                                <th style="width: 8%;">Final</th>
                                                                <th style="width: 15%;">Remarks</th>
                                                                <th style="width: 8%;">Credit Earned</th>
                                                                @if($eachrecord->type == 1)
                                                                @else
                                                                <th colspan="2">Delete</th>
                                                                @endif
                                                            </tr>
                                                        </thead>       
                                                        <tr>
                                                            <td colspan="11" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                                        </tr> 
                                                        <tbody class="gradescontainer">     
                                                            @foreach($grades as $grade)
                                                                <tr class="eachsubject">
                                                                    <td><input type="checkbox" class="form-control" id="{{$grade->id}}" style="width: 20px;height: 20px;" @if($grade->inMAPEH == 1) checked @endif></td>
                                                                    <td>@if($eachrecord->type == 1){{ucwords(strtolower($grade->subjdesc))}}@else<input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="{{$grade->id}}"/><input type="text" class="form-control form-control-sm p-0 input-norecord input-subjdesc" value="{{$grade->subjdesc}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->q1}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1" value="{{$grade->q1}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->q2}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2" value="{{$grade->q2}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->q3}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q3" value="{{$grade->q3}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->q4}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q4" value="{{$grade->q4}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{$grade->finalrating > 0 ? $grade->finalrating : ''}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-finalgrade" value="{{$grade->finalrating}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1){{isset($grade->actiontaken) ? $grade->actiontaken : $grade->remarks}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{isset($grade->actiontaken) ? $grade->actiontaken : $grade->remarks}}"/>@endif</td>
                                                                    <td class="text-center">@if($eachrecord->type == 1) @else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-credits" value="{{isset($grade->credits) ? $grade->credits : 0}}"/>@endif</td>
                                                                    @if($eachrecord->type == 1)
                                                                    @else
                                                                    <th colspan="2"> <button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-default btn-deletesubject text-sm" data-id="{{$grade->id}}"><i class="fa fa-trash-alt"></i></button></th>
                                                                    @endif
                                                                </tr>
                                                            @endforeach  
                                                        </tbody>
                                                    </table>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    
                                    @if($eachrecord->type == 1)
                                        
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Attendance</label><br/>
                                                    <table class="table" style="font-size: 10.5px;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 20%;">Month</th>
                                                                @if(count($eachrecord->attendance) == 0)
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                @else
                                                                    @foreach($eachrecord->attendance as $att)
                                                                        <th>{{substr($att->monthdesc, 0, 3)}}</th>
                                                                    @endforeach
                                                                @endif
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>School days</td>
                                                                @if(count($eachrecord->attendance) == 0)
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                @else
                                                                    @foreach($eachrecord->attendance as $att)
                                                                        <td>{{$att->days}}</td>
                                                                    @endforeach
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td>Days present</td>
                                                                @if(count($eachrecord->attendance) == 0)
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                @else
                                                                    @foreach($eachrecord->attendance as $att)
                                                                        <td>{{$att->present}}</td>
                                                                    @endforeach
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td>Days absent</td>
                                                                @if(count($eachrecord->attendance) == 0)
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                @else
                                                                    @foreach($eachrecord->attendance as $att)
                                                                        <td>{{$att->absent}}</td>
                                                                    @endforeach
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td>Times tardy</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                     
                                    
                                    @else
                                    
                                        <div class="row">
                                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
                                            <div class="col-md-12">
                                                <table class="table" style="width: 100%; font-size: 11px;">
                                                    <tr>
                                                        <th style="width: 30% !important; vertical-align: bottom;" class="text-right">Has advance credit in&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                        <td style="vertical-align: bottom;"><input type="text" class="form-control form-control-sm pb-0 input-norecord input-credit-advance" value="{{$eachrecord->credit_advance}}"/></td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 30% !important; vertical-align: bottom;" class="text-right">Lacks credits in&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                        <td style="vertical-align: bottom;"><input type="text" class="form-control form-control-sm pb-0  input-norecord input-credit-lacks" value="{{$eachrecord->credit_lack}}"/></td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 30% !important; vertical-align: bottom;" class="text-right">Total number of years in school to date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                        <td style="vertical-align: bottom;"><input type="number" class="form-control form-control-sm pb-0  input-norecord input-noofyears" value="{{$eachrecord->noofyears}}"/></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            @else
                                            @endif
                                            <div class="col-md-12 text-right">
                                                <button type="button" class="btn btn-sm btn-success btn-updaterecord" data-id="{{$eachrecord->id}}"><i class="fa fa-share"></i> Save changes</button>
                                            </div>
                                            <div class="col-md-12">
                                                <label>Attendance</label><br/>
                                                <sup><em>Note: Check the template if there's an Attendance section</em></sup>
                                                <table class="table mb-1" style="font-size: 10.5px;">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 20%;">Month</th>
                                                            @if(count($eachrecord->attendance) == 0)
                                                            <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                            <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                            <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                            <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                            <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                            <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                            <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                            <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                            <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                            <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                            <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                            <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                            @else
                                                                @for($x = 0; $x < 12; $x++)
                                                                    @if(isset($eachrecord->attendance[$x]))
                                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month" value="{{$eachrecord->attendance[$x]->monthdesc}}"/></th>
                                                                    @else
                                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                                    @endif
                                                                @endfor
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td># of school days</td>
                                                            @if(count($eachrecord->attendance) == 0)
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                            @else
                                                                @for($x = 0; $x < 12; $x++)
                                                                    @if(isset($eachrecord->attendance[$x]))
                                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum" value="{{$eachrecord->attendance[$x]->numdays}}"/></td>
                                                                    @else
                                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                                    @endif
                                                                @endfor
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <td># of days present</td>
                                                            @if(count($eachrecord->attendance) == 0)
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                            @else
                                                                @for($x = 0; $x < 12; $x++)
                                                                    @if(isset($eachrecord->attendance[$x]))
                                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum" value="{{$eachrecord->attendance[$x]->numdayspresent}}"/></td>
                                                                    @else
                                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                                    @endif
                                                                @endfor
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <td># of days absent</td>
                                                            @if(count($eachrecord->attendance) == 0)
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                            @else
                                                                @for($x = 0; $x < 12; $x++)
                                                                    @if(isset($eachrecord->attendance[$x]))
                                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum" value="{{$eachrecord->attendance[$x]->numdaysabsent}}"/></td>
                                                                    @else
                                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                                    @endif
                                                                @endfor
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <td># of times tardy</td>
                                                            @if(count($eachrecord->attendance) == 0)
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                            @else
                                                                @for($x = 0; $x < 12; $x++)
                                                                    @if(isset($eachrecord->attendance[$x]))
                                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum" value="{{$eachrecord->attendance[$x]->numtimestardy}}"/></td>
                                                                    @else
                                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                                    @endif
                                                                @endfor
                                                            @endif
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12 text-right mb-2">
                                                <button type="button" class="btn btn-sm btn-success btn-updateattendance" data-id="{{$eachrecord->id}}"><i class="fa fa-share"></i> Save Attendance</button>
                                            </div>
                                        </div>
                                    
                                    @endif
                                @endif
                            
                        </div>
                    @endforeach
                </div>            
            </div>
        </div>    
    </div>    
</div>
@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs' )
@else
<div class="card">
    <div class="card-header bg-info">
        <h5>C E R T I F I C A T I O N</h5>
    </div>
    <div class="card-body" style="font-size: 13px;">
      @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
      <div class="row mb-2">
          <div class="col-md-4">
              <label>Copy sent to:</label>
              <input type="text" class="form-control" id="certcopysentto" value="{{$footer->copysentto}}" placeholder=""/>
          </div>
          <div class="col-md-4">
              <label>Address:</label>
              <input type="text" class="form-control" id="certaddress" value="{{$footer->address}}" placeholder=""/>
          </div>
          <div class="col-md-4 text-right">
              <label>&nbsp;</label><br/>
              <button type="button" class="btn btn-primary" id="btn-savefooter"><i class="fa fa-share"></i> Save Changes</button>
          </div>
      </div>
      @else
          <div class="row mb-2">
              <div class="col-md-12">
                  <label>Purpose</label>
                  <input type="text" class="form-control" id="purpose" value="{{$footer->purpose}}" placeholder="Type purposes of the copy here"/>
              </div>
          </div>
          <div class="row mb-2">
              <div class="col-md-4">
                  <label>Class Adviser</label>
                  <input type="text" class="form-control" id="classadviser" value="{{$footer->classadviser}}" placeholder="Class Adviser"/>
              </div>
              <div class="col-md-4">
                  <label>Records In-charge</label>
                  <input type="text" class="form-control" id="recordsincharge" value="{{$footer->recordsincharge}}" placeholder="Records In-charge"/>
              </div>
              <div class="col-md-4">
                  <label>Last School Year Attended:</label>
                  <input type="text" class="form-control" id="lastsy" value="{{$footer->lastsy}}" placeholder="Records In-charge"/>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12 text-right">
                  <label>&nbsp;</label><br/>
                  <button type="button" class="btn btn-primary" id="btn-savefooter"><i class="fa fa-share"></i> Save Changes</button>
              </div>
          </div>
      @endif
    </div>
</div>
@endif
<script>
    $('.btn-saverecord').hide()
    $('.btn-addrow').on('click', function(){
        var thistbody = $(this).closest('table').find('.gradescontainer');
        thistbody.append(
            '<tr class="eachsubject">'+
                '<td><input type="checkbox" class="form-control" style="width: 20px;height: 20px;"></td>'+
                '<td><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="0"/><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjdesc" placeholder="Subject"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q1"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q2"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q3"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q4"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-finalgrade" placeholder="Final"/></td>'+
                '<td><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-remarks" placeholder="Remarks"/></td>'+
                '<td><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-credits" placeholder="Credits"/></td>'+
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
        var thiscardheader = $(this).closest('.tab-pane');
        var schoolname = thiscardheader.find('.input-schoolname').val();
        var schoolid = thiscardheader.find('.input-schoolid').val();
        var district = thiscardheader.find('.input-district').val();
        var division = thiscardheader.find('.input-division').val();
        var region = thiscardheader.find('.input-region').val();
        var gradelevelid = thiscardheader.find('.input-levelid').attr('data-id');
        var sectionname = thiscardheader.find('.input-sectionname').val();
        var schoolyear = thiscardheader.find('.input-sydesc').val();
        var teachername = thiscardheader.find('.input-adviser').val();
        
        var thiscardbody = thiscardheader;
        var thistbody = $(this).closest('.card').find('.gradescontainer');
        var thistrs = thistbody.find('tr.eachsubject');
        var subjects = [];
        thistrs.each(function(){
            var indentcheck = $(this).find('input[type="checkbox"]:checked');
            var subjdesc    = $(this).find('.input-subjdesc').val();
            var q1          = $(this).find('.input-q1').val();
            var q2          = $(this).find('.input-q2').val();
            var q3          = $(this).find('.input-q3').val();
            var q4          = $(this).find('.input-q4').val();
            var finalgrade  = $(this).find('.input-finalgrade').val();
            var remarks     = $(this).find('.input-remarks').val();
            var credits     = $(this).find('.input-credits').val();
            var indentsubj = 0;
            if(indentcheck.length > 0)
            {
                var indentsubj = 1;
            }
            if(subjdesc.replace(/^\s+|\s+$/g, "").length > 0)
            {
                if(subjdesc.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    subjdesc = " ";
                }
                if(q1.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    q1 = 0;
                }
                if(q2.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    q2 = 0;
                }
                if(q3.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    q3 = 0;
                }
                if(q4.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    q4 = 0;
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
                    indentsubj      : indentsubj,
                    subjdesc      : subjdesc,
                    q1              : q1,
                    q2          : q2,
                    q3          : q3,
                    q4          : q4,
                    final      : finalgrade,
                    remarks      : remarks,
                    credits      : credits,
                    fromsystem   : 0,
                    editablegrades   : 0,
                    inmapeh   : 0,
                    intle   : 0
                };
                subjects.push(obj);
            }
        })
        if(subjects.length == 0)
        {
            toastr.warning('No Subjects detected!')
        }
        // else{
            var remarks = thiscardbody.find('.input-remarks').val();
            var recordsincharge = thiscardbody.find('.input-incharge').val();
            var datechecked = thiscardbody.find('.input-datechecked').val();
            if(schoolyear.replace(/^\s+|\s+$/g, "").length == 0)
            {
                toastr.warning('Please fill in the School Year!')
                thiscardheader.find('.input-sydesc').css('border','1px solid red')
            }else{
                thiscardheader.find('.input-sydesc').removeAttr('style')
                $.ajax({
                    url: '/reports_schoolform10/submitnewform',
                    type: 'GET',
                    data:{
                        studentid           : '{{$studinfo->id}}',
                        acadprogid          : 3,
                        schoolname          :   schoolname,
                        schoolid            :   schoolid,
                        district            :   district,
                        division            :   division,
                        region              :   region,
                        gradelevelid        :   gradelevelid,
                        sectionname         :   sectionname,
                        schoolyear          :   schoolyear,
                        teachername         :   teachername,
                        recordsincharge     :   recordsincharge,
                        datechecked         :   datechecked,
                        // indications         :   indications,
                        subjects            :   JSON.stringify(subjects),
                        remarks             :   remarks

                    }, success:function(data)
                    {
                        toastr.success('Record added successfully!')
                        $('#addcontainer').empty()
                        $('#addrecord').prop('disabled',false)
                            $('#btn-reload').click();
                        // getrecords();
                    }
                });
            }
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
                        acadprogid      : 3
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
        var thiscardheader = $(this).closest('.tab-pane');
        var schoolname = thiscardheader.find('.input-schoolname').val();
        var schoolid = thiscardheader.find('.input-schoolid').val();
        var district = thiscardheader.find('.input-district').val();
        var division = thiscardheader.find('.input-division').val();
        var region = thiscardheader.find('.input-region').val();
        var gradelevelid = thiscardheader.find('.input-levelid').attr('data-id');
        var sectionname = thiscardheader.find('.input-sectionname').val();
        var schoolyear = thiscardheader.find('.input-sydesc').val();
        var teachername = thiscardheader.find('.input-adviser').val();
        
        var thiscardbody =thiscardheader;
        var thistbody = $(this).closest('.card').find('.gradescontainer');
        var thistrs = thistbody.find('tr.eachsubject');
        var subjects = [];
        thistrs.each(function(){
            var indentcheck = $(this).find('input[type="checkbox"]:checked');
            var subjid    = $(this).find('.input-subjid').val();
            var subjdesc    = $(this).find('.input-subjdesc').val();
            var q1          = $(this).find('.input-q1').val();
            var q2          = $(this).find('.input-q2').val();
            var q3          = $(this).find('.input-q3').val();
            var q4          = $(this).find('.input-q4').val();
            var finalgrade  = $(this).find('.input-finalgrade').val();
            var remarks     = $(this).find('.input-remarks').val();
            var credits     = $(this).find('.input-credits').val();
            var indentsubj = 0;
            if(indentcheck.length > 0)
            {
                var indentsubj = 1;
            }
            if(subjdesc.replace(/^\s+|\s+$/g, "").length > 0)
            {
                if(subjdesc.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    subjdesc = " ";
                }
                if(q1.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    q1 = 0;
                }
                if(q2.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    q2 = 0;
                }
                if(q3.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    q3 = 0;
                }
                if(q4.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    q4 = 0;
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
                    indentsubj      : indentsubj,
                            id      : subjid,
                    subjdesc      : subjdesc,
                    q1              : q1,
                    q2          : q2,
                    q3          : q3,
                    q4          : q4,
                    final      : finalgrade,
                    remarks      : remarks,
                    credits      : credits,
                    fromsystem   : 0,
                    editablegrades   : 0,
                    inmapeh   : 0,
                    intle   : 0
                };
                subjects.push(obj);
            }
        })
        
        if(subjects.length == 0)
        {
            toastr.warning('No Subjects detected!')
        }
        // else{
            var remarks = thiscardbody.find('.input-remarks').val();
            var recordsincharge = thiscardbody.find('.input-incharge').val();
            var datechecked = thiscardbody.find('.input-datechecked').val();
            var credit_advance = thiscardbody.find('.input-credit-advance').val();
            var credit_lacks = thiscardbody.find('.input-credit-lacks').val();
            var noofyears = thiscardbody.find('.input-noofyears').val();

            
            var paramet = {
                    "_token": "{{ csrf_token() }}",
                    studentid           : '{{$studinfo->id}}',
                    acadprogid          : 3,
                    id          :   id,
                    schoolname          :   schoolname,
                    schoolid            :   schoolid,
                    district            :   district,
                    division            :   division,
                    region              :   region,
                    gradelevelid        :   gradelevelid,
                    sectionname         :   sectionname,
                    schoolyear          :   schoolyear,
                    teachername         :   teachername,
                    recordsincharge     :   recordsincharge,
                    datechecked         :   datechecked,
                    credit_advance      :   credit_advance,
                    credit_lacks        :   credit_lacks,
                    noofyears           :   noofyears,
                    // indications         :   indications,
                    subjects            :   JSON.stringify(subjects),
                    remarks             :   remarks
                }
            $.ajax({
                url: '/reports_schoolform10/updateform',
                type: 'POST',
                data: $.param(paramet)
                , success:function(data)
                {
                        toastr.success('Record updated successfully!')
                        $('#addcontainer').empty()
                        $('#addrecord').prop('disabled',false)
                            $('#btn-reload').click();
                }
            });
        // }
    })
    $('.input-month').on('input', function(){
        if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
        {
            var thindex = $(this).parent().index();
            var trs = $(this).closest('table').find('tbody').find('tr')
            trs.each(function(){
                var thistd = $(this).find('td').eq(thindex)
                thistd.find('input').val('')
            })
        }
    })
    $('.input-daynum').on('input', function(){
        var tdindex = $(this).parent().index();
        var thparent = $(this).closest('table').find('thead').find('th').eq(tdindex)
        var monthvalue = thparent.find('input').val();
        // console.log(thparent)
        // console.log(monthvalue)
        if(monthvalue.replace(/^\s+|\s+$/g, "").length == 0)
        {
            thparent.css('border','1px solid red')
            toastr.error('Please fill in the months\' row')
            $(this).val('')
        }else{
            thparent.removeAttr('style')
        }
    })
    $('.btn-updateattendance').on('click', function(){
        var recordid = $(this).attr('data-id');
        var attendance = [];
        $('.input-month').each(function(){
            var monthdesc = $(this).val();
            var schooldays = 0;
            var dayspresent = 0;
            var daysabsent = 0;
            var timestardy = 0;
            if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
            {
                var thindex = $(this).parent().index();
                var trs = $(this).closest('table').find('tbody').find('tr')
                trs.each(function(){
                    
                    var thistd = $(this).find('td').eq(thindex)
                    var thisinput = thistd.find('input');
                    var thisvalue = thistd.find('input').val();

                    if(thisinput.hasClass('input-dschool'))
                    {
                        schooldays = thisvalue;
                    }else if(thisinput.hasClass('input-dpresent'))
                    {
                        dayspresent = thisvalue;
                    }else if(thisinput.hasClass('input-dabsent'))
                    {
                        daysabsent = thisvalue;
                    }else if(thisinput.hasClass('input-dtardy'))
                    {
                        timestardy = thisvalue;
                    }
                })
                obj = {
                    monthdesc : monthdesc,
                    schooldays : schooldays,
                    dayspresent : dayspresent,
                    daysabsent : daysabsent,
                    timestardy : timestardy
                }
                attendance.push(obj)
            }
        })
        // if(attendance.length == 0)
        // {
        //     toastr.warning('Please fill in the Attendance first!')
        // }else{
            $.ajax({
                url: '/reports_schoolform10/updateattendance',
                type: 'GET',
                data:{
                    studentid           : '{{$studinfo->id}}',
                    acadprogid          : 3,
                    id                  :   recordid,
                    attendance          :   JSON.stringify(attendance)

                }, success:function(data)
                {
                    toastr.success('Attendance updated successfully!')
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
                        acadprogid      : 3
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

</script>