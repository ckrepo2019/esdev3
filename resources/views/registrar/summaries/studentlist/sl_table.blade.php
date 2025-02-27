<div class="row mb-2">
    <div class="col-md-12 text-left">
        <button type="button" class="btn btn-default" id="btn-export-sl"><i class="fa fa-file-pdf"></i> Export to PDF</button>
        <button type="button" class="btn btn-default" id="btn-export-sl-excell"><i class="fas fa-file-excel text-success"></i> Export to Excell</button>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        @php
            $gtotal = 0;
        @endphp
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Gender</th>
                    <th>Units Enrolled</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $key => $eachcourse)
                    <tr>
                        <td colspan="5">{{$key}}</td>
                    </tr>
                    @php
                        $gtotal+=count($eachcourse);
                        $totaleachcourse = $eachcourse;
                        $levels = collect($eachcourse)->sortBy('sortid')->groupBy('levelname');
                    @endphp
                    @foreach($levels as $levelkey => $eachlevel)
                        <tr>
                            <td></td>
                            <td colspan="4">{{$levelkey}}</td>
                        </tr>
                        @foreach(collect($eachlevel)->sortBy('lastname') as $eachstudent)
                            <tr>
                                <td></td>
                                <td>{{$eachstudent->sid}}</td>
                                <td>{{$eachstudent->lastname}},{{$eachstudent->firstname}} {{$eachstudent->middlename}}</td>
                                <td>{{ucwords($eachstudent->gender)}}</td>
                                <td class="text-left">{{number_format($eachstudent->totalunits)}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2"></td>
                            <td style="border-top: 1px solid black;">
                            Year Level Count: {{count($eachlevel)}}</td>
                            <td colspan="2"></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td style="border-top: 1px solid black;">Course Total: {{count($totaleachcourse)}}</td>
                        <td colspan="3"></td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="border-top: 1px solid black; border-bottom: 1px solid black;">GRAND TOTAL : {{$gtotal}}</td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>