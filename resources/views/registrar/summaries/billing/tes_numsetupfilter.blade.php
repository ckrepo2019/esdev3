
        @if(count($students)>0)
        <div class="card">
    <div class="card-body">
        <div class="row">
            {{-- <div class="col-md-12 text-right mb-2">
                <button type="button" class="btn btn-default btn-export-pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                <button type="button" class="btn btn-default btn-export-excel"><i class="fa fa-file-excel"></i> Export to Excel</button>

            </div> --}}
            <div class="col-md-12">
                <table class="table table-hover m-0" style="font-size: 13px;" cellspacing="0" width="100%" id="table-results">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>SID</th>
                            <th>Student Name</th>
                            <th>Award No. Per NOA</th>
                            <th>TES Award No.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $key=>$student)
                            <tr>
                                {{-- <td></td> --}}
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>{{$student->sid}}</td>
                                <td>{{ucwords(mb_strtolower($student->lastname))}}, {{ucwords(mb_strtolower($student->firstname))}} {{ucwords(mb_strtolower($student->middlename))}} {{$student->suffix}}</td>
                                <td>
                                    <input type="text" class="form-control form-control-sm input-awardnopernoa" data-studid="{{$student->id}}" data-saved="" @if(collect($student->awardnos)->where('notype',1)->count()>0) value="{{collect($student->awardnos)->where('notype',1)->first()->awardno}}" @endif/>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm input-awardnotes" data-studid="{{$student->id}}"  data-saved=""  @if(collect($student->awardnos)->where('notype',0)->count()>0) value="{{collect($student->awardnos)->where('notype',0)->first()->awardno}}" @endif/>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif