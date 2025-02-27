<style>
    th, td{
        padding: 1px !important;
    }
</style>
<div class="row">
    <div class="col-md-4">
        <div class="card" style="border: none; font-size: 10px;">
            <div class="card-header text-center text-bold">
                SUMMARY TABLE 1ST SEM
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STATUS</th>
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tr>
                        <td>COMPLETE</td>
                        <td>{{collect($students)->where('gender','male')->where('sem1status','1')->count()}}</td>
                        <td>{{collect($students)->where('gender','female')->where('sem1status','1')->count()}}</td>
                        <td>{{collect($students)->where('sem1status','1')->count()}}</td>
                    </tr>
                    <tr>
                        <td>INCOMPLETE</td>
                        <td>{{collect($students)->where('gender','male')->where('sem1status','0')->count()}}</td>
                        <td>{{collect($students)->where('gender','female')->where('sem1status','0')->count()}}</td>
                        <td>{{collect($students)->where('sem1status','0')->count()}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td>{{collect($students)->where('gender','male')->count()}}</td>
                        <td>{{collect($students)->where('gender','female')->count()}}</td>
                        <td>{{collect($students)->where('sem1status','0')->count()+collect($students)->where('sem1status','1')->count()}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="border: none; font-size: 10px;">
            <div class="card-header text-center text-bold">
                SUMMARY TABLE 2nd SEM
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STATUS</th>
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tr>
                        <td>COMPLETE</td>
                        <td>{{collect($students)->where('gender','male')->where('sem2status','1')->count()}}</td>
                        <td>{{collect($students)->where('gender','female')->where('sem2status','1')->count()}}</td>
                        <td>{{collect($students)->where('sem2status','1')->count()}}</td>
                    </tr>
                    <tr>
                        <td>INCOMPLETE</td>
                        <td>{{collect($students)->where('gender','male')->where('sem2status','0')->count()}}</td>
                        <td>{{collect($students)->where('gender','female')->where('sem2status','0')->count()}}</td>
                        <td>{{collect($students)->where('sem2status','0')->count()}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td>{{collect($students)->where('sem2status','0')->where('gender','male')->count()+collect($students)->where('sem2status','1')->where('gender','male')->count()}}</td>
                        <td>{{collect($students)->where('sem2status','0')->where('gender','female')->count()+collect($students)->where('sem2status','1')->where('gender','female')->count()}}</td>
                        <td>{{collect($students)->where('sem2status','0')->count()+collect($students)->where('sem2status','1')->count()}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="border: none; font-size: 10px;">
            <div class="card-header text-center text-bold">
                SUMMARY TABLE (End of the School Year Only)
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STATUS</th>
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tr>
                        <td>REGULAR</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>IRREGULAR</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="card" style="border: none; font-size: 11px;">
    <div class="card-body p-1">
        <div class="row">
            <div class="col-md-8">
                Course/s (only for TVL)
                <input type="text" class="form-control" id="input-courses" placeholder="Course/s (only for TVL)"/>
            </div>
            <div class="col-md-4 mb-2 text-right mt-3">
                <!--<button type="button" id="btn-exportexcel" class="btn btn-default"><i class="fa fa-file-excel"></i> Excel </button>-->
                <button type="button" id="btn-exportpdf" class="btn btn-default"><i class="fa fa-file-pdf"></i> Export to PDF </button>
            </div>
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead class="text-center">
                        <tr>
                            <th style="width: 5%;">No.</th>
                            <th style="width: 12%;">LRN</th>
                            <th style="width: 30%;">NAME</th>
                            <th style="width: 18%;">Back Subjects</th>
                            <th style="width: 15%;">End of Semester Status</th>
                            <th style="width: 10%;">End of School Year Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6">MALE</td>
                        </tr>
                        @foreach(collect($students)->where('gender','male')->values() as $key => $eachstudent)
                            <tr>
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">{{$eachstudent->lrn}}</td>
                                <td>{{$eachstudent->lastname}}, {{$eachstudent->firstname}} {{$eachstudent->middlename}}</td>
                                <td>
                                    {{collect($eachstudent->backsubjects)->where('semid', $semester->id)->count()}} Subject(s)
                                    {{-- @if(collect($eachstudent->backsubjects)->count()>0){{implode(', ', collect(collect($eachstudent->backsubjects)->pluck('subjdesc'))->toArray())}}@endif --}}
                                </td>
                                <td class="text-center">
                                    @if($semester->id == 1)
                                        @if($eachstudent->sem1status == 1) COMPLETE @else INCOMPLETE @endif
                                    @else 
                                        @if($eachstudent->sem2status == 1) COMPLETE @else INCOMPLETE @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($semester->id == 2)
                                        @if($eachstudent->sem1status == 1 && $eachstudent->sem2status == 1) REGULAR @else IRREGULAR @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="6">FEMALE</td>
                        </tr>
                        @foreach(collect($students)->where('gender','female')->values() as $key => $eachstudent)
                            <tr>
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">{{$eachstudent->lrn}}</td>
                                <td>{{$eachstudent->lastname}}, {{$eachstudent->firstname}} {{$eachstudent->middlename}}</td>
                                <td>
                                    {{collect($eachstudent->backsubjects)->where('semid', $semester->id)->count()}} Subject(s)
                                    {{-- @if(collect($eachstudent->backsubjects)->count()>0){{implode(', ', collect(collect($eachstudent->backsubjects)->pluck('subjdesc'))->toArray())}}@endif --}}
                                </td>
                                <td class="text-center">
                                    @if($semester->id == 1)
                                        @if($eachstudent->sem1status == 1) COMPLETE @else INCOMPLETE @endif
                                    @else 
                                        @if($eachstudent->sem2status == 1) COMPLETE @else INCOMPLETE @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($semester->id == 2)
                                        @if($eachstudent->sem1status == 1 && $eachstudent->sem2status == 1) REGULAR @else IRREGULAR @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    
    $('#btn-exportpdf').on('click', function(){
        var courses = $('#input-courses').val()
        window.open('/forms/form5a?action=export&exporttype=pdf&semid={{$semester->id}}&strandid={{$strandid}}&sectionid={{$gradeAndLevel[0]->sectionid}}&levelid={{$gradeAndLevel[0]->levelid}}&syid={{$syid}}&courses='+courses,'_blank')
    })

</script>