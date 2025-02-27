
    <style>
        
        .donutTeachers{
            margin-top: 90px;
            margin: 0 auto;
            background: transparent url("{{asset('assets/images/corporate-grooming-20140726161024.jpg')}}") no-repeat  28% 60%;
            background-size: 30%;
        }
        .donutStudents{
            margin-top: 90px;
            margin: 0 auto;
            background: transparent url("{{asset('assets/images/student-cartoon-png-2.png')}}") no-repeat  28% 60%;
            background-size: 30%;
        }
        #studentstable{
            font-size: 13px;
        }
        @media (min-width: 768px) {
            .modal-xl {
                width: 90%;
                max-width:1200px;
            }
        }
        
    .tableFixHead       { overflow-y: auto; height: 500px; }

/* #studentstable { border-collapse: collapse; width: 100%; } */

#studentstable th,
#studentstable td    { /* padding: 8px 16px; */ }

#studentstable th    { position: sticky; top: 0; background-color: #eee; z-index: 100;}
#studentstable thead{
    background-color: #eee !important;
    z-index: 100;
}

#studentstable                    {width:100%; font-size:12px;; stext-transform: uppercase; }

/* .table thead th:first-child { position: sticky; left: 0; background-color: #fff;
z-index: 9999999 } */
#studentstable thead{

position: sticky;
top: 0;
}
#studentstable thead th:first-child  { 
position: sticky; 
/* width: 20% !important; */
width: 200px !important;
left: -20; 
background-color: #fff; 
outline: 2px solid #dee2e6;
outline-offset: -1px;
z-index: 999 !important
}
#studentstable thead th:last-child  { 
position: sticky !important; 
right: -20; 
background-color: #fff; 
outline: 2px solid #dee2e6;
outline-offset: -1px;
z-index: 999 !important
}
/* .table thead {

z-index: 999
} */

#studentstable tbody td:last-child  { 
position: sticky; 
right: -20; 
background-color: #fff; 
outline: 2px solid #dee2e6;
outline-offset: -1px;
/* z-index: 999 */
}

/* #studentstable td:first-child, #studentstable th:first-child {
position:sticky;
left:0;
z-index:1;
background-color:white;
} */
/* #studentstable td:nth-child(2),
#studentstable th:nth-child(2)  { 
position:sticky;
left:24px;
z-index:1;
background-color:white;
}
#studentstable td:nth-last-child(2),
#studentstable th:nth-last-child(2)  { 
position:sticky;
right:85px;
z-index:1;
background-color:gold !important;
}
#studentstable td:nth-last-child(3),
#studentstable th:nth-last-child(3)  { 
position:sticky;
right:150px;
z-index:1;
background-color: darksalmon !important;
}

#studentstable th:nth-last-child(3),
#studentstable th:nth-last-child(2)  { 
z-index: 999 !important;
} */



#studentstable tbody td:first-child  {  
position: sticky; 
left: -20; 
background-color: #fff; 
width: 150px !important;
background-color: #fff; 
outline: 2px solid #dee2e6;
outline-offset: -1px;
}

/* #studentstable thead th:first-child  { 
    position: sticky; left: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
} */
    </style>
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
            <div class="col-md-6 text-right">
                <label>&nbsp;</label><br/>
                <button type="button" class="btn btn-sm btn-warning">{{count($students)}} Student(s)</button>
            {{-- </div>
            <div class="col-md-3 text-right"> --}}
                {{-- <label>&nbsp;</label><br/> --}}
                <button type="button" class="btn btn-sm btn-default" id="btn-export-pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                <button type="button" class="btn btn-sm btn-default" id="btn-export-excel"><i class="fa fa-file-excel"></i> Export to Excel</button>
            </div>

        </div>
    </div>
    <div class="card-body" style="overflow-y: auto;">
        <table class="table table-bordered" style=" font-size: 12px;">
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
                                    <td class="p-2" style="border-bottom: none !important;"></td>
                                    <td class="p-2" style="border-bottom:: none !important;">@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjunit}} @endif</td>
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