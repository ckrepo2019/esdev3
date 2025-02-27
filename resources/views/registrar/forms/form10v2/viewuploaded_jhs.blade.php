
    <!-- Toastr -->
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
@if(count($setups)>0)
    @foreach($setups as $setup)
        @php
        
        $display = 1;
        $header = collect($headers)->where('levelid', $setup->levelid)->values();   
        
        if(count($header)>0)
        {
            $isThereNumber = false;
            for ($i = 0; $i < strlen($header[0]->col7); $i++) {
                if ( ctype_digit($header[0]->col7[$i]) ) {
                    $isThereNumber = true;
                    break;
                }
            }
            if ( $isThereNumber ) {
                if($header[0]->col7 == DB::table('sy')->where('isactive','1')->first()->sydesc)
                {
                    $display = 0;
                }else{
                    $display = 1;
                }
            } else {
                    $display = 0;
            }
        
        }
        @endphp
        @if($display == 1) 
        <div class="row eachlevel-uploaded">
            <div class="col-md-12">
                <table class="table table-bordered" style="border: 2px solid black;">
                    <tr>
                        <th colspan="3" class="pl-2"><h5>{{DB::table('gradelevel')->where('id', $setup->levelid)->first()->levelname}}</h5></th>
                        <th colspan="5" class="text-right">
                            <button type="button" class="btn btn-success btn-sm btn-save-uploaded" data-levelid="{{$setup->levelid}}"><i class="fa fa-share"></i> Save</button>
                            {{-- <button type="button" class="btn btn-outline-danger btn-sm"><i class="fa fa-times"></i> Remove</button> --}}
                        </th>
                    </tr>
                    <tr>
                        <td colspan="8" class="p-0" style="vertical-align: top;">
                            @if(count($header) == 0)
                                <table style="width: 100%;" class="container-header">
                                    <tr>
                                        <td style="width: 15%;">School:</td>
                                        <td colspan="3"><input type="text" class="form-control form-control-sm input-schoolname"/></td>
                                        <td style="width: 15%;">School ID</td>
                                        <td><input type="text" class="form-control form-control-sm input-schoolid"/></td>
                                    </tr>
                                    <tr>
                                        <td>District</td>
                                        <td><input type="text" class="form-control form-control-sm input-district"/></td>
                                        <td>Division</td>
                                        <td><input type="text" class="form-control form-control-sm input-division"/></td>
                                        <td>Region</td>
                                        <td><input type="text" class="form-control form-control-sm input-region"/></td>
                                    </tr>
                                    <tr>
                                        <td>Grade Level</td>
                                        <td><input type="text" class="form-control form-control-sm" value="{{DB::table('gradelevel')->where('id', $setup->levelid)->first()->levelname}}" readonly/></td>
                                        <td>Section</td>
                                        <td><input type="text" class="form-control form-control-sm input-section"/></td>
                                        <td>School Year</td>
                                        <td><input type="text" class="form-control form-control-sm input-sydesc"/></td>
                                    </tr>
                                    <tr>
                                        <td>Adviser/Teacher:</td>
                                        <td colspan="7"><input type="text" class="form-control form-control-sm input-teachername"/></td>
                                    </tr>
                                </table>
                            @else
                                <table style="width: 100%;" class="container-header">
                                    <tr>
                                        <td style="width: 15%;">School:</td>
                                        <td colspan="3"><input type="text" class="form-control form-control-sm input-schoolname" value="{{$header[0]->col1}}"/></td>
                                        <td style="width: 15%;">School ID</td>
                                        <td><input type="text" class="form-control form-control-sm input-schoolid" value="{{$header[0]->col2}}"/></td>
                                    </tr>
                                    <tr>
                                        <td>District</td>
                                        <td><input type="text" class="form-control form-control-sm input-district" value="{{$header[0]->col3}}"/></td>
                                        <td>Division</td>
                                        <td><input type="text" class="form-control form-control-sm input-division" value="{{$header[0]->col4}}"/></td>
                                        <td>Region</td>
                                        <td><input type="text" class="form-control form-control-sm input-region" value="{{$header[0]->col5}}"/></td>
                                    </tr>
                                    <tr>
                                        <td>Grade Level</td>
                                        <td><input type="text" class="form-control form-control-sm" value="{{DB::table('gradelevel')->where('id', $setup->levelid)->first()->levelname}}" readonly/></td>
                                        <td>Section</td>
                                        <td><input type="text" class="form-control form-control-sm input-section" value="{{$header[0]->col6}}"/></td>
                                        <td>School Year</td>
                                        <td><input type="text" class="form-control form-control-sm input-sydesc" value="{{$header[0]->col7}}"/></td>
                                    </tr>
                                    <tr>
                                        <td>Adviser/Teacher:</td>
                                        <td colspan="7"><input type="text" class="form-control form-control-sm input-teachername"  value="{{$header[0]->col8}}"/></td>
                                    </tr>
                                </table>
                            @endif
                        </td>
                    </tr>
                    <tr class="text-center">
                        <th style="width: 8%;">Indent</th>
                        <th style="width: 40%;">Subject</th>
                        <th>Q1</th>
                        <th>Q2</th>
                        <th>Q3</th>
                        <th>Q4</th>
                        <th style="width: 10%;">Final Rating</th>
                        <th>Remarks</th>
                    </tr>
                    @foreach($setup->subjects as $eachsubject)
                    <tr class="eachuploaded-subject">
                        <td class="text-center pl-3"><input type="checkbox" class="form-control checkbox-indent" style="width: 20px;height: 20px;"></td>
                        <td class="td-subjdesc">{{$eachsubject->col1}}</td>
                        <td class="td-q1 text-center">{{$eachsubject->col2}}</td>
                        <td class="td-q2 text-center">{{$eachsubject->col3}}</td>
                        <td class="td-q3 text-center">{{$eachsubject->col4}}</td>
                        <td class="td-q4 text-center">{{$eachsubject->col5}}</td>
                        <td class="td-final text-center">{{$eachsubject->col6}}</td>
                        <td class="td-remarks text-center">{{$eachsubject->col7}}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered" style="border: 2px solid black;">
                    <tr>
                        <td><h4>&nbsp;&nbsp;&nbsp;{{DB::table('gradelevel')->where('id', $setup->levelid)->first()->levelname}}</h4></td>
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
@endif
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<script>
    $('.btn-save-uploaded').on('click', function(){
        var thisbutton = $(this);
        var studentid = $('#select-studentid').val();
        var levelid = $(this).attr('data-levelid');
        var thisrow = $(this).closest('.eachlevel-uploaded');
        var schoolname      = thisrow.find('.input-schoolname').val();
        var schoolid        = thisrow.find('.input-schoolid').val();
        var district        = thisrow.find('.input-district').val();
        var division        = thisrow.find('.input-division').val();
        var region          = thisrow.find('.input-region').val();
        var section         = thisrow.find('.input-section').val();
        var sydesc          = thisrow.find('.input-sydesc').val();
        var teachername     = thisrow.find('.input-teachername').val();

        var checkvalidation = 0;
        if(sydesc.replace(/^\s+|\s+$/g, "").length == 0)
        {
            checkvalidation=1;
            thisrow.find('.input-sydesc').css('border','1px solid red')
        }
        var uploadsubjects  =  thisrow.find('.eachuploaded-subject');
        var subjects        = [];
        uploadsubjects.each(function(){
            var indent = $(this).find('.checkbox-indent:checked').length;
            var subjectname = $(this).find('.td-subjdesc').text()
            if(subjectname.replace(/^\s+|\s+$/g, "").length > 0)
            {
                var q1 = $(this).find('.td-q1').text();
                var q2 = $(this).find('.td-q2').text();
                var q3 = $(this).find('.td-q3').text();
                var q4 = $(this).find('.td-q4').text();
                var final = $(this).find('.td-final').text();
                var remarks = $(this).find('.td-remarks').text();

                obj = {
                    indent : indent,
                    subjectname : subjectname,
                    q1 : q1,
                    q2 : q2,
                    q3 : q3,
                    q4 : q4,
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
                        acadprogid          : 4,
                        levelid             :   levelid,
                        schoolname             :   schoolname,
                        schoolid             :   schoolid,
                        district             :   district,
                        division             :   division,
                        region             :   region,
                        section             :   section,
                        sydesc             :   sydesc,
                        teachername             :   teachername,
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
