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
                <label hidden>President</label>
                <input hidden type="text" class="form-control" id="input-president" value="{{collect($signatories)->where('title','President')->first()->name ?? null}}"/>
            </div>
            <div class="col-md-6 text-right">
                <label>&nbsp;</label><br/>
                <button type="button" class="btn btn-sm btn-warning">{{count($students)}} Student(s)</button>
            {{-- </div>
            <div class="col-md-3 text-right"> --}}
                {{-- <label>&nbsp;</label><br/> --}}
                <button type="button" class="btn btn-sm btn-default" id="btn-export-pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                <button type="button" class="btn btn-sm btn-default" id="btn-export-excel"><i class="fa fa-file-excel"></i> Export to EXCEL</button>
            </div>

        </div>
    </div>
    <div class="card-body" style="overflow-y: auto;">
        <table class="table table-bordered" style=" font-size: 12px; height:1px">
        {{-- <table class="table table-bordered" style="font-size: 12px;"> --}}
            <thead>
                <tr>
                    {{-- <th style="width: 10%:">Student ID</th/>
                    <th style="width: 10%:">LRN</th> --}}
                    <th style="width: 30% !important;">Student Name</th>
                    {{-- <th>S<br/>E<br/>X</th> --}}
                    <th>COURSE</th>
                    {{-- <th>Y<br/>E<br/>A<br/>R</th> --}}
                    <th>BIRTHDATE</th>
                    @for($x = 0; $x < $maxsubjects; $x++)
                    <th class="p-0">
                        <table style="width: 100%; font-size: 11px; text-align: center;">
                            <tr>
                                <td colspan="2" class="p-2">Subject</td>
                            </tr>
                            <tr>
                                <td class="p-2">Grade</td>
                                <td class="p-2">Units</td>
                            </tr>
                        </table>
                    </th>
                    {{-- <th>U<br/>N<br/>I<br/>T<br/>S</th> --}}
                    @endfor
                    <th>Total<br/>Units</th>
                </tr>
            </thead>
            @foreach($students as $student)
                <tr>
                    {{-- <td>{{$student->sid}}</td>
                    <td>{{$student->lrn}}</td> --}}
                    <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                    {{-- <td>{{$student->gender[0]}}</td> --}}
                    <td>{{$student->courseabrv}}</td>
                    {{-- <td>{{$student->yearid}}</td> --}}
                    <td>@if($student->dob != null){{date('m/d/Y', strtotime($student->dob))}}@endif</td>
                    @for($x = 0; $x < $maxsubjects; $x++)
                        <td class="p-0">
                            @if(isset($student->subjects[$x]))
                            <table style="width: 100%; font-size: 11px; text-align: center; table-layout: fixed; height: 100%; z-index: 0 !important;">
                                <tr>
                                    <td colspan="2" class="p-2"> @if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjcode ?? $student->subjects[$x]->subjectcode}} @endif</td>
                                </tr>
                                <tr>
                                    <td class="p-2" style="height: 100%;">@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjgrade}} @endif</td>
                                    <td class="p-2" style="height: 100%;">@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjunit}} @endif</td>
                                </tr>
                            </table>
                            @endif
                        </td>
                        {{-- <td>@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjunit}} @endif</td> --}}
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