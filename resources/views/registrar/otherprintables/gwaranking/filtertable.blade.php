<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-warning"><strong>{{ count($students) }}</strong> Students</button>
            </div>
            <div class="col-md-6 text-right">
                <button class="btn btn-default" id="btn-exporttopdf"><i class="fa fa-file-pdf"></i>Export to PDF</button>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12" id="resultscontainer">
                <table class="table-hover table-bordered" style="width: 100%;" id="example">
                    <thead>
                        <tr>
                            <th style="">Student Name</th>
                            <th style="" class="text-center">Course</th>
                            <th style="" class="text-center">MAJOR</th>
                            <th style="" class="text-center">Credit</th>
                            <th style="" class="text-center">HPA</th>
                            <th style="" class="text-center">GWA</th>
                            <th style="" class="text-center">RANK</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 14px;">
                        @if (count($students) > 0)
                            @foreach ($students as $key => $student)
                                <tr>
                                    <td>{{ ucwords(strtolower($student->lastname)) }},
                                        {{ ucwords(strtolower($student->firstname)) }}
                                        {{ ucwords(strtolower($student->middlename)) }}
                                        {{ ucwords(strtolower($student->suffix)) }}</td>
                                    <td class="text-center">{{ $student->courseabrv }}</td>
                                    <td class="text-center">
                                        {{ $student->major ? $student->major : $student->courseabrv }}</td>
                                    <td class="text-center"> {{ $student->totalcredit }}</td>
                                    <td class="text-center"> {{ $student->hpa }}</td>
                                    <td class="text-center"> {{ $student->gwa }} </td>
                                    <td class="text-center"> {{ $key + 1 }}</td>
                                    {{-- <td>{{$student->sid}}</td>
                                                <td>{{$student->lrn}}</td>
                                                <td>{{ucwords(strtolower($student->lastname))}}, {{ucwords(strtolower($student->firstname))}} {{ucwords(strtolower($student->middlename))}} {{ucwords(strtolower($student->suffix))}}
                                                </td>
                                                <td>{{$student->levelname}}</td>
                                                <td><button type="button" class="btn btn-default btn-sm btn-block btn-export" data-studid="{{$student->id}}" data-syid="{{$student->syid}}" data-levelname="{{$student->levelname}}" data-enrolleddate="{{$student->dateenrolled}}"><i class="fa fa-file-pdf"></i> Certificate</button></td> --}}
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
