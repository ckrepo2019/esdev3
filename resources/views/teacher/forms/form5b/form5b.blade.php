<style>
    th, td{
        padding: 1px !important;
    }
</style>
<div class="row">
    <div class="col-md-6">
        <div class="card" style="border: 3px solid rgb(96, 180, 96); font-size: 10px;">
            <div class="card-header text-center text-bold p-1">
                SUMMARY TABLE A
            </div>
            <div class="card-body p-0 pb-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="">STATUS</th>
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tr>
                        <td>Learners who completed SHS Program within 2 SYs or 4 semesters</td>
                        <td class="text-center">{{collect($students)->where('gender','male')->where('completed','1')->count()}}</td>
                        <td class="text-center">{{collect($students)->where('gender','female')->where('completed','1')->count()}}</td>
                        <td class="text-center">{{collect($students)->where('completed','1')->count()}}</td>
                    </tr>
                    <tr>
                        <td>Learners who completed SHS Program in more than 2 SYs or 4 semesters</td>
                        <td class="text-center">{{collect($students)->where('gender','male')->where('status','OVERSTAYING')->count()}}</td>
                        <td class="text-center">{{collect($students)->where('gender','female')->where('status','OVERSTAYING')->count()}}</td>
                        <td class="text-center">{{collect($students)->where('status','OVERSTAYING')->count()}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td class="text-center">{{collect($students)->where('gender','male')->where('completed','1')->count()+collect($students)->where('gender','male')->where('status','OVERSTAYING')->count()}}</td>
                        <td class="text-center">{{collect($students)->where('gender','female')->where('completed','1')->count()+collect($students)->where('gender','female')->where('status','OVERSTAYING')->count()}}</td>
                        <td class="text-center">{{collect($students)->where('completed','1')->count()+collect($students)->where('status','OVERSTAYING')->count()}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card" style="border: 3px solid rgb(96, 180, 96); font-size: 10px;">
            <div class="card-header text-center text-bold p-1">
                SUMMARY TABLE B
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
                        <td>NC III</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>NC II</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>NC I</td>
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
                </table>Note: NCs are recorded here for documentation but is not a requirement for graduation.			
			

            </div>
        </div>
    </div>
</div>
<div class="card" style="border: none; font-size: 11px;">
    <div class="card-body p-1">
        <div class="row">
            <div class="col-md-6">
                Course/s (only for TVL)
                <input type="text" class="form-control" id="input-courses" placeholder="Course/s (only for TVL)"/>
            </div>
            <div class="col-md-6 mb-2 text-right mt-3">
                <button type="button" id="btn-exportpdf" class="btn btn-default"><i class="fa fa-file-pdf"></i> Export to PDF </button>
                <button type="button" id="btn-exportexcel" class="btn btn-default"><i class="fa fa-file-excel"></i> Export to Excel </button>
            </div>
            <div class="col-md-12 table-responsive" style="height: 500px;">
                <table class="table table-bordered table-head-fixed">
                    <thead class="text-center">
                        <tr>
                            <th style="width: 5%;">No.</th>
                            <th style="width: 12%;">LRN</th>
                            <th style="width: 35%;">NAME</th>
                            <th style="width: 15%;">Completed SHS in 2 SYs?<br/>(Y/N)</th>
                            <th style="width: 25%;">National Certification Level Attained (only if applicable)</th>
                            {{-- <th></th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5">MALE</td>
                        </tr>
                        @foreach(collect($students)->where('gender','male')->values() as $key => $eachstudent)
                            <tr class="eachstudent" id="{{$eachstudent->id}}">
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">{{$eachstudent->lrn}}</td>
                                <td>{{$eachstudent->lastname}}, {{$eachstudent->firstname}} {{$eachstudent->middlename}}</td>
                                <td>
                                    <div class="form-group clearfix m-0">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="radio{{$eachstudent->id}}1" name="radio{{$eachstudent->id}}" @if($eachstudent->completed == 1) checked="" @endif value="1">
                                            <label for="radio{{$eachstudent->id}}1">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="radio{{$eachstudent->id}}2" name="radio{{$eachstudent->id}}" @if($eachstudent->completed == 0) checked="" @endif value="0">
                                            <label for="radio{{$eachstudent->id}}2">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <input type="text" class="form-control form-control-sm input{{$eachstudent->id}}" value="{{$eachstudent->certificationlevel}}"/>
                                </td>
                                {{-- <td><button type="button" class="btn btn-sm btn-warning p-1" style="font-size: 11px;"><i class="fa fa-share"></i> Save</button></td> --}}
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5">FEMALE</td>
                        </tr>
                        @foreach(collect($students)->where('gender','female')->values() as $key => $eachstudent)
                            <tr class="eachstudent" id="{{$eachstudent->id}}">
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">{{$eachstudent->lrn}}</td>
                                <td>{{$eachstudent->lastname}}, {{$eachstudent->firstname}} {{$eachstudent->middlename}}</td>
                                <td>
                                    <div class="form-group clearfix m-0">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="radio{{$eachstudent->id}}1" name="radio{{$eachstudent->id}}" @if($eachstudent->completed == 1) checked="" @endif value="1">
                                            <label for="radio{{$eachstudent->id}}1">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="radio{{$eachstudent->id}}2" name="radio{{$eachstudent->id}}" @if($eachstudent->completed == 0) checked="" @endif value="0">
                                            <label for="radio{{$eachstudent->id}}2">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <input type="text" class="form-control form-control-sm input{{$eachstudent->id}}" value="{{$eachstudent->certificationlevel}}"/>
                                </td>
                                {{-- <td><button type="button" class="btn btn-sm btn-warning p-1" style="font-size: 11px;"><i class="fa fa-share"></i> Save</button></td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 mb-2 text-right mt-1">
                <button type="button" id="btn-save" class="btn btn-success"><i class="fa fa-share"></i> Save Changes </button>
            </div>
            <div class="col-md-12 mt-2">
                <label>GUIDELINES:	</label>
                <p>
                    1. This form should be accomplished by the Class Adviser at End of School Year.<br/>
                    2. It should be compiled and checked by the School Head and passed to the Division Office before graduation. 	
                </p>
            </div>
        </div>
    </div>
</div>
<script>
    $('#btn-exportexcel').on('click', function(){
        var courses = $('#input-courses').val()
        window.open('/forms/form5b?action=export&exporttype=excel&semid={{$semester->id}}&strandid={{$strandid}}&sectionid={{$gradeAndLevel[0]->sectionid}}&levelid={{$gradeAndLevel[0]->levelid}}&syid={{$syid}}&courses='+courses,'_blank')
    })

    
    $('#btn-exportpdf').on('click', function(){
        var courses = $('#input-courses').val()
        window.open('/forms/form5b?action=export&exporttype=pdf&semid={{$semester->id}}&strandid={{$strandid}}&sectionid={{$gradeAndLevel[0]->sectionid}}&levelid={{$gradeAndLevel[0]->levelid}}&syid={{$syid}}&courses='+courses,'_blank')
    })

    $('#btn-save').on('click', function(){
        var students = [];
        $('tr.eachstudent').each(function(){
            var studid = $(this).attr('id');

            var completed = 0;

            if($('input[name="radio'+studid+'"]:checked').length >0)
            {
                completed = $('input[name="radio'+studid+'"]:checked').val()
            }
            var certificationlevel = $('.input'+studid).val();

            obj = {
                studid : studid,
                completed: completed,
                certificationlevel: certificationlevel
            };
            students.push(obj)
        })        
        $.ajax({
            url: '/forms/form5b?action=updateeachstudent',
            type:"GET",
            // dataType:"json",
            data:{
                syid    :  '{{$syid}}',
                semid    :  '{{$semester->id}}',
                levelid    :  '{{$gradeAndLevel[0]->levelid}}',
                sectionid    :  '{{$gradeAndLevel[0]->sectionid}}',
                strandid    :  '{{$strandid}}',
                students    :  JSON.stringify(students)
            },
            success: function(data){
                toastr.success('Updated successfully!')
            }
        })
    })
</script>