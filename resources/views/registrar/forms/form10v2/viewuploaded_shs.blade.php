
    <!-- Toastr -->
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    @foreach($records as $eachrecord)
        @if(count($eachrecord->subjects)>0)
        <div class="row eachlevel-uploaded">
            <div class="col-md-12">
                <table class="table table-bordered" style="border: 2px solid black;">
                    <tr>
                        <th colspan="2" class="pl-2"><h5>{{$eachrecord->levelname}}</h5></th>
                        <th colspan="4" class="text-right">
                            <button type="button" class="btn btn-success btn-sm btn-save-uploaded" data-levelid="{{$eachrecord->levelid}}" data-semid="{{$eachrecord->semid}}"><i class="fa fa-share"></i> Save</button>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="6" class="p-0" style="vertical-align: top;">
                            <table class="m-0" style="font-size: 13.5px; width: 100%; table-layout: fixed;">
                                <tr>
                                    <td style="width: 15%;">School</td>
                                    <td colspan="3" style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm input-schoolname" value="{{$eachrecord->school}}"/></td>
                                </tr>
                                <tr>
                                    <td>School ID</td>
                                    <td colspan="3" style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm input-schoolid" value="{{$eachrecord->schoolid}}"/></td>
                                </tr>
                                <tr>
                                    <td class="text-right">School Year&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;" class="text-center"><input type="text" class="form-control form-control-sm input-sydesc" value="{{$eachrecord->sy}}"/></td>
                                    <td class="text-right">Semester&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;" class="text-center">@if($eachrecord->semid == 1)1st @elseif($eachrecord->semid == 2)2nd @endif</td>
                                </tr>
                            </table>
                            <table class="m-0" style="font-size: 13.5px; width: 100%; table-layout: fixed;">
                                <tr>
                                    <td style="width: 15%;">Grade Level</td>
                                    <td style="border-bottom: 1px solid #ddd;">{{$eachrecord->levelname}}</td>
                                    <td style="width: 15%;">Section</td>
                                    <td style="border-bottom: 1px solid #ddd; width: 30%;"><input type="text" class="form-control form-control-sm input-section" value="{{$eachrecord->section}}"/></td>
                                    {{-- <td style="width: 12%;" class="text-right">Adviser&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;">{{$eachrecord->teachername}}</td> --}}
                                </tr>
                                <tr>
                                    <td style="width: 15%;">Track & Strand</td>
                                    <td style="border-bottom: 1px solid #ddd;" colspan="3" class=""><input type="text" class="form-control form-control-sm input-trackandstrand" value="{{$eachrecord->trackandstrand}}"/></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <th style="width: 8%;">Indent</th>
                        <th style="width: 40%;">Subject</th>
                        <th>Q1</th>
                        <th>Q2</th>
                        <th style="width: 10%;">Final Rating</th>
                        <th>Remarks</th>
                    </tr>
                    @foreach($eachrecord->subjects as $eachsubject)
                        @if(strtolower($eachsubject[0]) == 'core' || strtolower($eachsubject[0]) == 'applied' || strtolower($eachsubject[0]) == 'specialized' || strtolower($eachsubject[0]) == 'other' || strtolower($eachsubject[0]) == 'others' || strtolower($eachsubject[0]) == 'other subjects')
                            <tr class="eachuploaded-subject">
                                <td class="td-indicate">{{$eachsubject[0]}}</td>
                                <td class="td-subjdesc">{{$eachsubject[1]}}</td>
                                <td class="td-q1 text-center">{{$eachsubject[2]}}</td>
                                <td class="td-q2 text-center">{{$eachsubject[3]}}</td>
                                <td class="td-final text-center">{{$eachsubject[4]}}</td>
                                <td class="td-remarks text-center">{{$eachsubject[5]}}</td>
                            </tr>
                        @endif
                    @endforeach
                    <tr class="eachuploaded-subject">
                        <td class="td-indicate"></td>
                        <td class="td-subjdesc">{{$eachrecord->genave[0] ?? null}}</td>
                        <td class="td-q1 text-center"></td>
                        <td class="td-q2 text-center"></td>
                        <td class="td-final text-center">{{$eachrecord->genave[1] ?? null}}</td>
                        <td class="td-remarks text-center">{{$eachrecord->genave[2] ?? null}}</td>
                    </tr>
                </table>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered" style="border: 2px solid black;">
                    <tr>
                        <td><h4>&nbsp;&nbsp;&nbsp;{{$eachrecord->levelname}}</h4></td>
                    </tr>
                    <tr>
                        <td class="pr-3 pl-3">
                            <p>Cannot be uploaded. The possible reasons are listed below:</p>
                            <p>1. There is an auto-generated record and cannot be replaced.</p>
                            <p>2. School Year field is empty. Please review.</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
    @endforeach
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<script>
    $('.btn-save-uploaded').on('click', function(){
        var thisbutton = $(this);
        var studentid = $('#select-studentid').val();
        var levelid = $(this).attr('data-levelid');
        var semid = $(this).attr('data-semid');
        var thisrow = $(this).closest('.eachlevel-uploaded');
        var schoolname      = thisrow.find('.input-schoolname').val();
        var schoolid        = thisrow.find('.input-schoolid').val();
        var section         = thisrow.find('.input-section').val();
        var sydesc          = thisrow.find('.input-sydesc').val();
        var trackandstrand     = thisrow.find('.input-trackandstrand').val();

        var checkvalidation = 0;
        var uploadsubjects  =  thisrow.find('.eachuploaded-subject');
        var subjects        = [];
        uploadsubjects.each(function(){
            var indicate = $(this).find('.td-indicate').text();
            var subjectname = $(this).find('.td-subjdesc').text()
            if(subjectname.replace(/^\s+|\s+$/g, "").length > 0)
            {
                var q1 = $(this).find('.td-q1').text();
                var q2 = $(this).find('.td-q2').text();
                var final = $(this).find('.td-final').text();
                var remarks = $(this).find('.td-remarks').text();

                obj = {
                    indicate : indicate,
                    subjectname : subjectname,
                    q1 : q1,
                    q2 : q2,
                    final : final,
                    remarks : remarks
                }
                subjects.push(obj)

            }
        })
        // if(/\d/.test(sydesc))
        // {

        // }
        if(/\d/.test(sydesc) == false)
        {
            thisrow.find('.input-sydesc').css('border','1px solid red')
        }
        if(subjects.length == 0)
        {
            checkvalidation=1;
            toastr.warning('No Subjects detected!')
        }else{

            if(checkvalidation == 0)
            {
                $.ajax({
                    url: '/reports_schoolform10v2/uploadrecord',
                    type: 'GET',
                    data:{
                        studentid           : studentid,
                        semid           : semid,
                        acadprogid          : 5,
                        levelid             :   levelid,
                        schoolname             :   schoolname,
                        schoolid             :   schoolid,
                        section             :   section,
                        sydesc             :   sydesc,
                        trackandstrand             :   trackandstrand,
                        subjects            :   JSON.stringify(subjects)

                    }, success:function(data)
                    {
                        if(data == 1)
                        {
                            thisbutton.prop('disabled',true)
                            thisbutton.text('Uploaded')
                            toastr.success('Uploaded successfully!')
                        }
                    }
                });
            }else{
                toastr.warning('Please fill in required field(s)!')
            }
            
        }
    })
    $('.btn-close').on('click', function(){
        $('#btn-getrecords').click()
    })
</script>
