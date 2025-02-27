<div class="row mb-2">
    <div class="col-md-3 text-left">
        <button type="button" class="btn btn-default btn-sm" id="btn-export-es"><i class="fa fa-file-pdf"></i> Export Table</button>
            @if($department == '6')
        <button type="button" class="btn btn-default btn-sm" id="btn-export-estable"><i class="fa fa-file-pdf"></i> Export Summary</button>
        @endif
    </div>
    <div class="col-md-9 text-right">
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'pcc')
            @if($department == '6')
            <button type="button" class="btn btn-default btn-sm btn-export-es-ched" data-reporttype="summary"><i class="fa fa-file-excel"></i> Export Enrollment List/Promotional Report</button>
            @endif
        @else
            @if($department == '6')
            <button type="button" class="btn btn-default btn-sm btn-export-es-ched" data-reporttype="summary"><i class="fa fa-file-excel"></i> Export Enrollment List</button>
            @endif
            @if($department == '6')
            <button type="button" class="btn btn-default btn-sm btn-export-es-ched" data-reporttype="promotional"><i class="fa fa-file-excel"></i> Export Promotional Report</button>
            @endif
        @endif
        @if($department == '6' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'dcc')
        <button type="button" class="btn btn-default btn-sm" id="btn-export-es-enrollment-stat"><i class="fa fa-file-pdf"></i> Export Enrollment Statistics</button>
        @endif
    </div>
</div>
{{-- @if($department != 'basiced') --}}
<hr/>
<div class="row mb-2 align-items-end">
    <div class="col-md-4">
        <label>Registrar</label>
        <input type="text" class="form-control form-control-sm" id="es_registrar" value="{{collect($signatories)->where('title','Registrar')->first()->name ?? ''}}"/>
    </div>
    <div class="col-md-5">
        <label>President</label>
        <input type="text" class="form-control form-control-sm" id="es_president" value="{{collect($signatories)->where('title','President')->first()->name ?? ''}}"/>
    </div>
    <div class="col-md-3 text-right">
        {{-- <label>&nbsp;</label><br/> --}}
        <button type="button" class="btn btn-sm btn-default mt-2" id="btn-saveessignatories"><i class="fa fa-share"></i> Save Changes</button>
    </div>
</div>
{{-- @endif --}}
<div class="row">
    <div class="col-md-12">
        @php
            $gtotalmale = 0;
            $gtotalfemale = 0;
        @endphp
        <table class="table">
            <thead>
                <tr>
                    <th>DEPARTMENT</th>
                    <th>COURSE</th>
                    <th>CLASSIFICATION</th>
                    <th>MALE</th>
                    <th>FEMALE</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $key => $eachstudent)
                    <tr>
                        <td colspan="6">{{$key}} Department</td>
                    </tr>
                    @php
                    $totaleachstudent = $eachstudent;
                        //if($key != 'Basic Ed')
                        //{
                           // $totaleachcourse = collect($totaleachstudent)->sortByDesc('coursesourtid')->values()->all();
                        //}
						
                            $totaleachstudent = collect($totaleachstudent)->sortBy('sortid')->values()->all();
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                        {
                        $eachstudent = collect($totaleachstudent)->groupBy('coursename');
                        }else{
                        $eachstudent = collect($totaleachstudent)->groupBy('completecourse');
                        }
                    @endphp
                    @foreach($eachstudent as $coursekey => $eachcourse)
                        @if($coursekey != '')
                        <tr>
                            <td></td>
                            <td colspan="5">{{$coursekey}}</td>
                        </tr>
                        @endif
                        @php
                            // if($key != 'Basic Ed')
                            // {
                            // $totaleachcourse = collect($eachcourse)->sortByDesc('coursesourtid')->values()->all();
                            // }else{
                            $totaleachcourse = collect($eachcourse)->sortBy('sortid')->values()->all();
                            // }
                            $levels = collect($totaleachcourse)->groupBy('levelname');
                        @endphp
                        @foreach($levels as $levelkey => $eachlevel)
                            <tr>
                                <td>
                                    {{-- {{collect($levels)}} --}}
                                    {{-- @if($levelkey == '1ST YEAR COLLEGE')
                                        {{$eachlevel}}
                                    @endif --}}
                                </td>
                                <td></td>
                                <td>{{$levelkey}}</td>
                                <td>{{collect($eachlevel)->where('gender','male')->count()}}</td>
                                <td>{{collect($eachlevel)->where('gender','female')->count()}}</td>
                                <td>{{collect($eachlevel)->count()}}</td>
                            </tr>
                        @endforeach
                        @if($key != 'Basic Ed')
                        <tr>
                            <td></td>
                            <td colspan="2">TOTAL {{$coursekey}}</td>
                            <td>{{collect($totaleachcourse)->where('gender','male')->count()}}</td>
                            <td>{{collect($totaleachcourse)->where('gender','female')->count()}}</td>
                            <td>{{collect($totaleachcourse)->count()}}</td>
                        </tr>
                        @endif
                    @endforeach
                    @php
                        $gtotalmale += collect($totaleachstudent)->where('gender','male')->count();
                        $gtotalfemale += collect($totaleachstudent)->where('gender','female')->count();
                    @endphp
                    <tr>
                        <td colspan="2">TOTAL {{$key}} Department</td>
                        <td></td>
                        <td>{{collect($totaleachstudent)->where('gender','male')->count()}}</td>
                        <td>{{collect($totaleachstudent)->where('gender','female')->count()}}</td>
                        <td>{{collect($totaleachstudent)->count()}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2">GRAND TOTAL :</td>
                    <td></td>
                    <td>{{$gtotalmale}}</td>
                    <td>{{$gtotalfemale}}</td>
                    <td>{{$gtotalmale+$gtotalfemale}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    @if(count($students) == 0)
                            toastr.warning('No enrolled students!')
    @endif
</script>