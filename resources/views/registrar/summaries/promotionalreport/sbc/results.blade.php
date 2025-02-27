<div class="card">
    <div class="card-header">
        {{-- <div class="row">
            <div class="col-md-6">
                <button type="button" class="btn btn-sm btn-warning">{{count($students)}} Student(s)</button>
            </div>
            <div class="col-md-6 text-right">
                @if($levelid > 0)
                <button type="button" class="btn btn-sm btn-default" id="btn-export-pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                @endif
            </div>
        </div> --}}
        <div class="row">
            
            <div class="col-md-3">
                <label>Registrar</label>
                <input type="text" class="form-control" id="input-registrar" value="{{collect($signatories)->where('title','Registrar')->first()->name ?? null}}"/>
            </div>
            <div class="col-md-3">
                <label>President</label>
                <input type="text" class="form-control" id="input-president" value="{{collect($signatories)->where('title','President')->first()->name ?? null}}"/>
            </div>
            <div class="col-md-3 text-right align-self-end">
                <button type="button" class="btn btn-sm btn-warning">{{$totalnoofstudents}} Student(s)</button>
            </div>
            <div class="col-md-3 text-right align-self-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Export Data
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <h6 class="dropdown-header">Print Pages</h6>
                      @if($totalexports>0)
                        @php
                            $firstpage = 1;
                            $tabno = 1;
                        @endphp
                        @for($x = 0; $x<$totalexports; $x++)
                        <button class="dropdown-item each-button-export" type="button" data-id="{{$tabno}}" data-description="Pages {{$firstpage}} to {{$firstpage+14}}" data-firstpage="{{$firstpage}}">Pages {{$firstpage}} to @if(($x+1) == $totalexports) {{$firstpage+$countlastpages}} @else {{$firstpage+14}}@endif</button>
                        @php
                            if(($x+1) == $totalexports) 
                            {
                            $firstpage += ($countlastpages);
                            }else{
                            $firstpage += 15;
                            }
                            $tabno += 1;
                        @endphp
                        @endfor
                      @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body" style="overflow-y: auto;">
        <table class="table table-bordered table-hover  table-head-fixed" id="studentstable">
        {{-- <table class="table table-bordered" style="font-size: 12px;"> --}}
            <thead>
                <tr>
                    {{-- <th style="width: 10%:">Student ID</th/>
                    <th style="width: 10%:">LRN</th> --}}
                    <th style="width: 150px !important;">Student Name</th>
                    <th>S<br/>E<br/>X</th>
                    <th>COURSE</th>
                    <th>Y<br/>E<br/>A<br/>R</th>
                    <th>BIRTHDATE</th>
                    @for($x = 0; $x < $maxsubjects; $x++)
                    <th>Subject-{{$x+1}}</th>
                    <th>U<br/>N<br/>I<br/>T<br/>S</th>
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                    <th>Grade</th>
                    @endif
                    @endfor
                    <th>Total<br/>Units</th>
                </tr>
            </thead>
            @foreach(collect($students)->where('display','1')->values() as $student)
                <tr>
                    <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                    <td>{{$student->gender[0]}}</td>
                    <td>{{$student->courseabrv}}</td>
                    <td>{{$student->yearid}}</td>
                    <td>@if($student->dob != null){{date('m/d/Y', strtotime($student->dob))}}@endif</td>
                    @for($x = 0; $x < $maxsubjects; $x++)
                        <td>@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjcode ?? $student->subjects[$x]->subjectcode ?? ''}} @endif</td>
                        <td>@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjunit}} @endif</td>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                        <td>@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjgrade}} @endif</td>
                        @endif
                    @endfor
                    <td>{{collect($student->subjects)->sum('subjunit')}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
<script>    
    $('.table').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": false,
        "autoWidth": false,
        "responsive": false,
    });
</script>