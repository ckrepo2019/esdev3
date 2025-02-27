
    <div class="row">
        <div class="col-md-12">
            <div class="card collapsed-card">
                <div class="card-header p-0">
                    <button type="button" class="btn btn-warning btn-block text-bold" data-card-widget="collapse">View Eligibility <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 bg-gray text-center mb-2">
                            <h6>ELIGIBILITY FOR ELEMENTARY SCHOOL ENROLMENT</h6>
                        </div>
                    </div>
                    <div class="row p-1" style="font-size: 12px; border: 1px solid black;">
                        <div class="col-3">
                            Credential Presented for Grade 1:
                        </div>
                        
                {{-- $eligibility = (object)array(
                    'kinderprogreport'  =>  0,
                    'eccdchecklist'     =>  0,
                    'kindergartencert'  =>  0,
                    'schoolid'          =>  null,
                    'schoolname'        =>  null,
                    'schooladdress'     =>  null,
                    'pept'              =>  0,
                    'peptrating'        =>  null,
                    'examdate'          =>  null,
                    'centername'        =>  null,
                    'centeraddress'     =>  null,
                    'remarks'           =>  null,
                    'specifyothers'     =>  null
                ); --}}
                        <div class="col-3">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox"  id="checkbox-kinderprogressreport" value="{{$eligibility->kinderprogreport}}" @if($eligibility->kinderprogreport == 1) checked="" @endif>
                                  <label for="checkbox-kinderprogressreport">
                                      Kinder Progress Report
                                  </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox"  id="checkbox-eccdchecklist" value="{{$eligibility->eccdchecklist}}" @if($eligibility->eccdchecklist == 1) checked="" @endif>
                                  <label for="checkbox-eccdchecklist">
                                      ECCD Checklist
                                  </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox"  id="checkbox-kindergartencert" value="{{$eligibility->kindergartencert}}" @if($eligibility->kindergartencert == 1) checked="" @endif>
                                  <label for="checkbox-kindergartencert">
                                        Kindergarten Certificate of Completion
                                  </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            Name of School: <input type="text" class="form-control" id="schoolname" value="{{$eligibility->schoolname}}"/>
                        </div>
                        <div class="col-4">
                            School ID: <input type="text" class="form-control" id="schoolid" value="{{$eligibility->schoolid}}"/>
                        </div>
                        <div class="col-4">
                            Address of School: <input type="text" class="form-control" id="schooladdress" value="{{$eligibility->schooladdress}}"/>
                        </div>
                    </div>
                    <div class="row" style="font-size: 12px;">
                        <div class="col-12">
                            Other Credential Presented
                        </div>
                        <div class="col-4">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox" id="checkbox-peptpasser" value="{{$eligibility->pept}}" @if($eligibility->pept == 1) checked="" @endif>
                                  <label for="checkbox-peptpasser">
                                        PEPT Passer
                                  </label>
                                </div>
                            </div>
                            Rating: <input type="text" id="peptrating" class="form-control form-control-sm" value="{{$eligibility->peptrating}}"/>
                        </div>
                        <div class="col-4">
                            Date of Examination/Assessment (mm/dd/yyyy):<input type="date" id="examdate" class="form-control form-control-sm" value="{{$eligibility->examdate}}"/>
                        </div>
                        <div class="col-4">
                            Other (Pls.Specify)
                            <textarea class="form-control" id="specify">{{$eligibility->specifyothers}}</textarea>
                        </div>
                    </div>
                    <div class="row mt-2" style="font-size: 12px;position: relative;">
                        <div class="col-3"><span style="position: absolute;bottom: 0;">Name and Address of Testing Center:</span></div>
                        <div class="col-5"><input type="text" id="centername" class="form-control form-control-sm" value="{{$eligibility->centername}}"/></div>
                        <div class="col-1"><span style="position: absolute;bottom: 0;">Remarks:</span></div>
                        <div class="col-3"><input type="text" id="remarks" class="form-control form-control-sm" value="{{$eligibility->remarks}}"/></div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-sm btn-primary" id="btn-eligibility-update"><i class="fa fa-edit"></i> Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-sm-12">
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item active">Learner's Permanent Academic Record </li>
            </ol>
        </div> --}}
        <div class="col-md-6">
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hcb')
                <form action="/reports_schoolform10/getrecordselem" target="_blank" method="get" class="m-0 p-0" style="display: inline;">
                    <input type="hidden" value="1" name="export"/>
                    <input type="hidden" value="{{$studentid}}" name="studentid"/>
                    <input type="hidden" value="{{$acadprogid}}" name="acadprogid"/>
                    <input type="hidden" value="pdf" name="exporttype"/>
                    <button type="submit" class="btn btn-primary btn-sm text-white">
                        <i class="fa fa-file-pdf"></i>
                            PDF
                    </button>
                </form>
            @endif
            {{-- <form action="/reports_schoolform10/getrecordselem" target="_blank" method="get" class="m-0 p-0" style="display: inline;">
                <input type="hidden" value="1" name="export"/>
                <input type="hidden" value="{{$studentid}}" name="studentid"/>
                <input type="hidden" value="{{$acadprogid}}" name="acadprogid"/>
                <input type="hidden" value="excel" name="exporttype"/>
                <button type="submit" class="btn btn-primary btn-sm text-white">
                    <i class="fa fa-file-excel"></i>
                        EXCEL
                </button>
            </form> --}}
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-default btn-sm" id="btn-reload"><i class="fa fa-sync"></i> Reload</button>
            {{-- <button id="btn-import-grades" type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i> Import Grades</button> --}}
            {{-- <button id="addrecord" type="button" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i> Add new record</button> --}}
            
        </div>
        &nbsp;
        <div class="col-md-12" id="addcontainer">
            
        </div>
        <div class="col-md-12"id="recordscontainer">
            
        </div>
    </div>
    <div class="modal fade" id="show-edit-info" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">School Info</h4>
            <button type="button" id="btn-modal-close-info" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-12 mb-2">
                      <label class="m-0">School Name</label>
                      <input type="text" id="edit-schoolname" class="form-control"/>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">School ID</label>
                      <input type="text" id="edit-schoolid" class="form-control"/>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">District</label>
                      <input type="text" id="edit-schooldistrict" class="form-control"/>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">Division</label>
                      <input type="text" id="edit-schooldivision" class="form-control"/>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">Region</label>
                      <input type="text" id="edit-schoolregion" class="form-control"/>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">Classified as Grade</label>
                      <select id="edit-levelid" class="form-control"></select>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">Section</label>
                      <input type="text" id="edit-sectionname" class="form-control"/>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">School Year</label>
                      <input type="text" id="edit-schoolyear" class="form-control"/>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">Name of Adviser</label>
                      <input type="text" id="edit-teachername" class="form-control"/>
                  </div>
              </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default btn-edit-close" data-dismiss="modal">Close</button>
            <button type="button" id="btn-edit-save-info" class="btn btn-primary">Save Changes</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="show-edit-info" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">School Info</h4>
            <button type="button" id="closeremarks" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
              
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default btn-edit-close" data-dismiss="modal">Close</button>
            <button type="button" id="btn-edit-save-info" class="btn btn-primary">Save Changes</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="modal fade show-modal" id="show-edit-grades" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Report Card</h4>
            <button type="button" id="btn-modal-close-reportcard" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body" id="edit-gradescontainer">
              
          </div>
          {{-- <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default btn-edit-close" data-dismiss="modal">Close</button>
            <button type="button" id="btn-edit-save-info" class="btn btn-primary">Save Changes</button>
          </div> --}}
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="modal fade show-modal" id="show-edit-remedial" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Remedial Classes</h4>
            <button type="button" id="btn-modal-close-remedial" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body" id="edit-remedialclasscontainer">
              
          </div>
          {{-- <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default btn-edit-close" data-dismiss="modal">Close</button>
            <button type="button" id="btn-edit-save-info" class="btn btn-primary">Save Changes</button>
          </div> --}}
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-addnew">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add new Record</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="row mb-2">
                  <div class="col-md-12">
                    <label>Select Grade Level</label>
                    <select class="form-control" id="select-addnewlevelid">
                        @foreach($gradelevels as $gradelevel)
                            <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                        @endforeach
                    </select>
                  </div>
              </div>
              <div class="row mb-2">
                  <div class="col-md-12">
                    <label>S.Y</label>
                    <input type="tel" class="form-control" id="input-addnewschoolyear"/>
                    <small>
                        <em>Note:</em>
                        <ol>
                            <li>Example: <strong>2019-2020</strong></li>
                            <li>Should be 9 characters only</li>
                            <li>Avoid white spaces.</li>
                            <li></li>
                            <li></li>
                        </ol>
                    </small>
                    {{-- <select class="form-control" id="select-addnewlevelid">
                        @foreach($gradelevels as $gradelevel)
                            <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                        @endforeach
                    </select> --}}
                  </div>
              </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="button-closeadd">Close</button>
            <button type="button" class="btn btn-primary" id="button-submitadd">Add</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    {{-- </div> --}}
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- fullCalendar 2.2.5 -->
    <!-- InputMask -->
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('plugins/jquery-year-picker/js/yearpicker.js')}}"></script>
    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
    <script>
        $(document).ready(function(){

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            $('#btn-reload').on('click', function(){
                
                getrecords();
            })
            var kinderprogressreport = $('#checkbox-kinderprogressreport').val()
            var eccdchecklist = $('#checkbox-eccdchecklist').val()
            var kindergartencert = $('#checkbox-kindergartencert').val()
            var peptpasser = $('#checkbox-peptpasser').val()
            var infoid ;

            function getrecords()
            {
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                }) 
                $.ajax({
                    url: '/reports_schoolform10/getrecordselem',
                    type: 'GET',
                    data:{
                        studentid : '{{$studentid}}',
                        acadprogid : '{{$acadprogid}}'
                    }, success:function(data)
                    {
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        $('#recordscontainer').empty()
                        $('#recordscontainer').append(data)
                    }
                });
            }

            getrecords();

            $('#checkbox-kinderprogressreport').change(function(){
                if($(this).prop('checked'))
                {
                    $(this).val('1')
                    kinderprogressreport = 1;
                }else{
                    $(this).val()
                    kinderprogressreport = 0;
                }
            })
            $('#checkbox-eccdchecklist').change(function(){
                if($(this).prop('checked'))
                {
                    $(this).val('1')
                    eccdchecklist = 1;
                }else{
                    $(this).val()
                    eccdchecklist = 0;
                }
            })
            $('#checkbox-kindergartencert').change(function(){
                if($(this).prop('checked'))
                {
                    $(this).val('1')
                    kindergartencert = 1;
                }else{
                    $(this).val()
                    kindergartencert = 0;
                }
            })
            $('#checkbox-peptpasser').change(function(){
                if($(this).prop('checked'))
                {
                    $(this).val('1')
                    peptpasser = 1;
                }else{
                    $(this).val()
                    peptpasser = 0;
                }
            })


            $('#btn-eligibility-update').on('click', function(){
                var schoolname = $('#schoolname').val();
                var schoolid = $('#schoolid').val();
                var schooladdress = $('#schooladdress').val();
                var peptrating = $('#peptrating').val();
                var examdate = $('#examdate').val();
                var specify = $('#specify').val();
                var centername = $('#centername').val();
                var remarks = $('#remarks').val();
                
                $.ajax({
                    url: '/reports_schoolform10/updateeligibility',
                    type: 'GET',
                    data:{
                        studentid           : '{{$studentid}}',
                        acadprogid          : '{{$acadprogid}}',
                        kinderprogressreport:   kinderprogressreport,
                        eccdchecklist       :   eccdchecklist,
                        kindergartencert    :   kindergartencert,
                        peptpasser          :   peptpasser,
                        schoolname          :   schoolname,
                        schoolid            :   schoolid,
                        schooladdress       :   schooladdress,
                        peptrating          :   peptrating,
                        examdate            :   examdate,
                        specify             :   specify,
                        centername          :   centername,
                        remarks             :   remarks
                    }, success:function(data)
                    {

                            Toast.fire({
                                type: 'success',
                                title: 'Eligibility',
                                html: 'Updated successfully!'
                            })
                    }
                });
            })
            $(document).on('click','.eachrecord', function(){
                infoid = $(this).attr('data-id')
                console.log(infoid)
            })

            
            $('#addrecord').on('click',function(){
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                $('#modal-addnew').modal('show')
                @else
                    $(this).prop('disabled', true)
                    $.ajax({
                        url: '/reports_schoolform10/getaddnew',
                        type: 'GET',
                        data:{
                            acadprogid : '{{$acadprogid}}'
                        }, success:function(data)
                        {
                            $('#addcontainer').append(data);
                            // $('#card-body-elem-addsubjects').hide()
                        }
                    });
                @endif
                // $(this).prop('disabled', true)
                // $.ajax({
                //     url: '/reports_schoolform10/getaddnew',
                //     type: 'GET',
                //     data:{
                //         acadprogid : '{{$acadprogid}}'
                //     }, success:function(data)
                //     {
                //         $('#addcontainer').append(data);
                //         $('#card-body-elem-addsubjects').hide()
                //     }
                // });
            });
            // $('#input-addnewschoolyear').on('keypress', function(key) {
            //     if(key.charCode < 48 || key.charCode > 57) return false;
            // });
            $('#button-submitadd').on('click', function(){
                var schoolyearvalue = $('#input-addnewschoolyear').val();
                if(schoolyearvalue.replace(/^\s+|\s+$/g, "").length < 9 || schoolyearvalue.replace(/^\s+|\s+$/g, "").length > 9)
                {
                    $('#input-addnewschoolyear').css('border','1px solid red')
                }else{
                // console.log($('#input-addnewschoolyear'))
                // if(schoolyearvalue.replace(/(^\s*)|(\s*$)/gi,"").length < 9 || schoolyearvalue.replace(/(^\s*)|(\s*$)/gi,"").length > 9)
                // {
                //     $('#input-addnewschoolyear').css('border','1px solid red')
                // }else{
                    $('#input-addnewschoolyear').removeAttr('style')
                    var levelidvalue = $('#select-addnewlevelid').val();
                    $.ajax({
                        url: '/reports_schoolform10/getaddnew',
                        type: 'GET',
                        data:{
                            studentid           :   '{{$studentid}}',
                            acadprogid  : '{{$acadprogid}}',
                            levelid     : levelidvalue,
                            sectionid   : '{{$sectionid}}',
                            schoolyear  : schoolyearvalue
                        }, success:function(data)
                        {
                            $('#addcontainer').append(data);
                            $('#button-closeadd').click()
                            $('#addrecord').prop('disabled', true)
                            // $('#card-body-elem-addsubjects').hide()
                        }
                    });

                }
                // select-addnewlevelid

            })
            // $(document).on('change','#gradelevelid', function(){
            //     if($(this).val().replace(/^\s+|\s+$/g, "").length == 0){
            //         $('#card-body-elem-addsubjects').hide()
            //     }else{
            //         $.ajax({
            //             url: '/reports_schoolform10/getsubjects',
            //             type: 'GET',
            //             data:{
            //                 acadprogid : '{{$acadprogid}}',
            //                 levelid    : $(this).val()
            //             }, success:function(data)
            //             {
            //                 $('#addcontainer').append(data);
            //                 $('#card-body-elem-addsubjects').hide()
            //             }
            //         });
            //     }
            // })
            $(document).on('click', '#addrow', function(){
                var closestTable = $(this).closest("table");
                closestTable.append(
                    '<tr class="tr-eachsubject">'+
                        '<td class="tdInputClass"><input type="text" class="form-control input0" value="1" hidden/><input type="text" class="form-control input00" value="0" hidden><input type="text" class="form-control input1" name="add-subject[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control input2 grades" name="add-q1[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control input3 grades" name="add-q2[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control input4 grades" name="add-q3[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control input5 grades" name="add-q4[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control input6 grades" name="add-final[]" required/></td>'+
                        '<td class="tdInputClass"><input type="text" class="form-control input7" name="add-remarks[]" required/></td>'+
                        // '<td class="tdInputClass"><input type="number" class="form-control" name="entry[]" required/></td>'+
                        '<td class="removebutton"><center><i class="fa fa-trash text-gray"></i></center></td>'+
                    '</tr>'
                );
                
                // $('.grades').on('change', function () {
                //     var input = parseInt(this.value);
                //     if (input < 60 )
                //         $(this).val('60')
                //     else if (input > 100 )
                //         $(this).val('100')
                //     return;
                // });
            });
            $(document).on('click', '#btn-submitnewform', function(){
                var schoolname = $('input[name="add-schoolname"]').val();
                var schoolid = $('input[name="add-schoolid"]').val();
                var schooldistrict = $('input[name="add-schooldistrict"]').val();
                var schooldivision = $('input[name="add-schooldivision"]').val();
                var schoolregion = $('input[name="add-schoolregion"]').val();
                var gradelevelid = $('select[name="add-gradelevelid"]').val();
                var sectionname = $('input[name="add-sectionname"]').val();
                var schoolyear = $('input[name="add-schoolyear"]').val();
                var teachername = $('input[name="add-teachername"]').val();
                
                var subjects = [];
                $('.tr-eachsubject').each(function(){
                    var input0 = $(this).find('.input0').val();
                    var input00 = $(this).find('.input00').val();
                    var input000mapeh = $(this).find('.input000mapeh').val();
                    var input000tle = $(this).find('.input000tle').val();
                    var input1 = $(this).find('.input1').val();
                    var input2 = $(this).find('.input2').val();
                    var input3 = $(this).find('.input3').val();
                    var input4 = $(this).find('.input4').val();
                    var input5 = $(this).find('.input5').val();
                    var input6 = $(this).find('.input6').val();
                    var input7 = $(this).find('.input7').val();
                    if(input1.replace(/^\s+|\s+$/g, "").length > 0)
                    {
                        if(input2.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            input2 = 0;
                        }
                        if(input3.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            input3 = 0;
                        }
                        if(input4.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            input4 = 0;
                        }
                        if(input5.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            input5 = 0;
                        }
                        if(input6.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            input6 = 0;
                        }
                        if(input7.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            input7 = " " ;
                        }

                        obj = {
                            inmapeh      : input000mapeh,
                            intle      : input000tle,
                            editablegrades      : input0,
                            fromsystem      : input00,
                            subjdesc      : input1,
                            q1      : input2,
                            q2      : input3,
                            q3      : input4,
                            q4      : input5,
                            final      : input6,
                            remarks      : input7
                        };
                        subjects.push(obj);
                    }
                })

                var generalaverageval = $('input[name="add-generalaverageval"]').val();
                
                if(subjects.length>0)
                {
                    $.ajax({
                        url: '/reports_schoolform10/submitnewform',
                        type: 'GET',
                        data:{
                            studentid           :   '{{$studentid}}',
                            acadprogid          :   '{{$acadprogid}}',
                            schoolname          :   schoolname,
                            schoolid            :   schoolid,
                            schooldistrict      :   schooldistrict,
                            schooldivision      :   schooldivision,
                            schoolregion        :   schoolregion,
                            gradelevelid        :   gradelevelid,
                            sectionname         :   sectionname,
                            schoolyear          :   schoolyear,
                            teachername         :   teachername,
                            subjects            :   JSON.stringify(subjects),
                            // q1                  :   q1,
                            // q2                  :   q2,
                            // q3                  :   q3,
                            // q4                  :   q4,
                            // final               :   final,
                            // remarks             :   remarks,
                            generalaverageval   :   generalaverageval

                        }, success:function(data)
                        {
                            if(data == 1)
                            {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Record added successfully!'
                                })
                                $('#addrecord').removeAttr('disabled')
                                $('#addcontainer').empty()
                                getrecords();
                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: 'Empty Subjects!'
                                })
                            }
                        }
                    });
                }else{
                            Toast.fire({
                                type: 'error',
                                title: 'Empty Subjects!'
                            })
                }
                
            })
            $(document).on('click','.btn-delete-syinfo', function(){
                var id = $(this).attr('data-id');
                var thiscard = $(this).closest('.card');
                Swal.fire({
                    title: 'Are you sure you want to delete the selected record?',
                    // text: "You won't be able to revert this!",
                    html:
                        "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/reports_schoolform10/deleterecord',
                            type:"GET",
                            dataType:"json",
                            data:{
                                id: id
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            complete: function(){

                                        Toast.fire({
                                            type: 'success',
                                            title: 'Record successfully!'
                                        })

                                        thiscard.remove();
                                        

                            }
                        })
                    }
                })
            })
            $(document).on('click', '.removebutton', function () {
                $(this).closest('tr').remove();
                // return false;
            });
            $(document).on('click','.removeCard', function () {
                $(this).closest('.card').remove();
                $('#addrecord').removeAttr('disabled')
                return false;
            });
            $(document).on('click', '.btn-edit-syinfo', function(){
                infoid = $(this).attr('data-id');

                $('#show-edit-info').modal('show')
                $.ajax({
                    url: '/reports_schoolform10/getinfo',
                    type: 'GET',
                    data:{
                        acadprogid : '{{$acadprogid}}',
                        infoid : infoid
                    }, success:function(data)
                    {
                        $('#edit-levelid').empty()
                        $('#edit-schoolname').val(data.schoolname)
                        $('#edit-schoolid').val(data.schoolid)
                        $('#edit-schooldistrict').val(data.schooldistrict)
                        $('#edit-schooldivision').val(data.schooldivision)
                        $('#edit-schoolregion').val(data.schoolregion)
                        $('#edit-levelid').append(data.selectlevel)
                        $('#edit-sectionname').val(data.sectionname)
                        $('#edit-schoolyear').val(data.sydesc)
                        $('#edit-teachername').val(data.teachername)
                    }
                });
            })
            $(document).on('click','#btn-edit-save-info', function(){
                var schoolname = $('#edit-schoolname').val()
                var schoolid = $('#edit-schoolid').val()
                var schooldistrict = $('#edit-schooldistrict').val()
                var schooldivision = $('#edit-schooldivision').val()
                var schoolregion = $('#edit-schoolregion').val()
                var levelid = $('#edit-levelid').val()
                var sectionname = $('#edit-sectionname').val()
                var schoolyear = $('#edit-schoolyear').val()
                var teachername = $('#edit-teachername').val()

                $.ajax({
                    url: '/reports_schoolform10/updateinfo',
                    type: 'GET',
                    data:{
                        acadprogid : '{{$acadprogid}}',
                        infoid : infoid,
                        schoolname : schoolname,
                        schoolid : schoolid,
                        schooldistrict : schooldistrict,
                        schooldivision : schooldivision,
                        schoolregion : schoolregion,
                        levelid : levelid,
                        sectionname : sectionname,
                        schoolyear : schoolyear,
                        teachername : teachername
                    }, success:function(data)
                    {
                        $('.btn-edit-close').click();
                        getrecords();
                    }
                });
            })
            $(document).on('click', '.btn-edit-reportcard', function(){
                infoid = $(this).attr('data-id');
                $('#show-edit-grades').modal('show')
                $.ajax({
                    url: '/reports_schoolform10/getgradesedit',
                    type: 'GET',
                    data:{
                        acadprogid : '{{$acadprogid}}',
                        infoid : infoid
                    }, success:function(data)
                    {
                        $('#edit-gradescontainer').empty()
                        $('#edit-gradescontainer').append(data)
                    }
                });
            })
            $(document).on('click','#btn-modal-close-reportcard', function(){
                getrecords();
            })
            var gradeid;
            $(document).on('click', '.btn-edit-deletesubject', function(){
                gradeid = $(this).attr('data-id');
                var thistr = $(this).closest('tr');
                Swal.fire({
                    title: 'Are you sure you want to delete this row?',
                    // text: "You won't be able to revert this!",
                    html:
                        "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/reports_schoolform10/deletesubjectgrades',
                            type:"GET",
                            dataType:"json",
                            data:{
                                acadprogid : '{{$acadprogid}}',
                                gradeid: gradeid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            complete: function(){

                                        Toast.fire({
                                            type: 'success',
                                            title: 'Report Card updated successfully!'
                                        })

                                        thistr.remove();
                                        

                            }
                        })
                    }
                })
            })
            $(document).on('click','.btn-edit-editsubject', function(){
                gradeid         = $(this).attr('data-id');
                editsubject     = $('#subject'+gradeid).val();
                editq1          = $('#q1'+gradeid).val();
                editq2          = $('#q2'+gradeid).val();
                editq3          = $('#q3'+gradeid).val();
                editq4          = $('#q4'+gradeid).val();
                editfinalrating = $('#finalrating'+gradeid).val();
                editremarks     = $('#remarks'+gradeid).val();
                if($('#inMAPEH'+gradeid).is(':checked')){
                    var editinmapeh = 1;
                }else{
                    var editinmapeh = 0;
                }
                if($('#inTLE'+gradeid).is(':checked')){
                    var editintle = 1;
                }else{
                    var editintle = 0;
                }
                
                $.ajax({
                    url: '/reports_schoolform10/editsubjectgrades',
                    type:"GET",
                    dataType:"json",
                    data:{
                        acadprogid : '{{$acadprogid}}',
                        gradeid: gradeid,
                        editsubject: editsubject,
                        editq1: editq1,
                        editq2: editq2,
                        editq3: editq3,
                        editq4: editq4,
                        editfinalrating: editfinalrating,
                        editremarks: editremarks,
                        editinmapeh: editinmapeh,
                        editintle: editintle
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    complete: function(){

                        Toast.fire({
                            type: 'success',
                            title: 'Updated successfully!'
                        })
                        
                    }
                })
            })
            $(document).on('click', '#btn-edit-addrow', function(){
                $('#grades-tbody').append(
                    '<tr>'+
                        '<td><input type="text" class="form-control" name="add-new-subject" required/></td>'+
                        '<td><input type="number" class="form-control" name="add-new-q1" required/></td>'+
                        '<td><input type="number" class="form-control" name="add-new-q2" required/></td>'+
                        '<td><input type="number" class="form-control" name="add-new-q3" required/></td>'+
                        '<td><input type="number" class="form-control" name="add-new-q4" required/></td>'+
                        '<td><input type="number" class="form-control" name="add-new-final" required/></td>'+
                        '<td><input type="text" class="form-control" name="add-new-remarks" required/></td>'+
                        // '<td class="tdInputClass"><input type="number" class="form-control" name="entry[]" required/></td>'+
                        '<td><button type="button" class="btn btn-sm btn-success p-1 btn-edit-addsubject" data-id="'+gradeid+'"><i class="fa fa-edit"></i> Save &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> <button type="button" class="btn btn-sm btn-default p-1 removebutton"><i class="fa fa-trash text-danger"></i>&nbsp;</button></td>'+
                    '</tr>'
                );
            })
            $(document).on('click','.btn-edit-addsubject', function(){
                gradeid        = $(this).attr('data-id');
                addsubject     = $(this).closest('tr').find('input[name="add-new-subject"]').val()
                addq1          = $(this).closest('tr').find('input[name="add-new-q1"]').val()
                addq2          = $(this).closest('tr').find('input[name="add-new-q2"]').val()
                addq3          = $(this).closest('tr').find('input[name="add-new-q3"]').val()
                addq4          = $(this).closest('tr').find('input[name="add-new-q4"]').val()
                addfinalrating = $(this).closest('tr').find('input[name="add-new-final"]').val()
                addremarks     = $(this).closest('tr').find('input[name="add-new-remarks"]').val()

                var validationcheck = 0;

                if(addsubject.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-subject"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-subject"]').removeAttr('style')
                }

                if(addq1.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-q1"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-q1"]').removeAttr('style')
                }
                if(addq2.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-q2"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-q2"]').removeAttr('style')
                }
                if(addq3.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-q3"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-q3"]').removeAttr('style')
                }
                if(addq4.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-q4"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-q4"]').removeAttr('style')
                }
                if(addfinalrating.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-final"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-final"]').removeAttr('style')
                }

                if(validationcheck == 6)
                {
                    $.ajax({
                        url: '/reports_schoolform10/addsubjectgrades',
                        type:"GET",
                        dataType:"json",
                        data:{
                            acadprogid : '{{$acadprogid}}',
                            infoid: infoid,
                            gradeid: gradeid,
                            addsubject: addsubject,
                            addq1: addq1,
                            addq2: addq2,
                            addq3: addq3,
                            addq4: addq4,
                            addfinalrating: addfinalrating,
                            addremarks: addremarks
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){
                            if(data == 1)
                            {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Updated successfully!'
                                })
                                $.ajax({
                                    url: '/reports_schoolform10/getgradesedit',
                                    type: 'GET',
                                    data:{
                                        acadprogid : '{{$acadprogid}}',
                                        infoid : infoid
                                    }, success:function(data)
                                    {
                                        $('#edit-gradescontainer').empty()
                                        $('#edit-gradescontainer').append(data)
                                    }
                                });
                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: 'Already exists!'
                                })
                            }
                            
                        }
                    })
                }else{
                    Toast.fire({
                        type: 'warning',
                        title: 'Some fields are empty!'
                    })
                }

            })
            $(document).on('click','.btn-edit-remedialclasses', function(){
                $('#show-edit-remedial').modal('show');
                $.ajax({
                    url: '/reports_schoolform10/getremedialclass',
                    type: 'GET',
                    data:{
                        acadprogid : '{{$acadprogid}}',
                        infoid : infoid
                    }, success:function(data)
                    {
                        $('#edit-remedialclasscontainer').empty()
                        $('#edit-remedialclasscontainer').append(data)
                    }
                });
            })
            
            $(document).on('click','#btn-edit-remedialheader', function(){
                conductdatefrom     = $('#remedial-datefrom').val();
                conductdateto       = $('#remedial-dateto').val();

                // var validationcheck = 0;
                // if(conductdatefrom.replace(/^\s+|\s+$/g, "").length == 0)
                // {
                //     $('#remedial-datefrom').css('border','1px solid red');
                // }else{
                //     validationcheck+=1;
                //     $('#remedial-datefrom').removeAttr('style');
                // }
                // if(conductdateto.replace(/^\s+|\s+$/g, "").length == 0)
                // {
                //     $('#remedial-dateto').css('border','1px solid red');
                // }else{
                //     conductdateto+=1;
                //     $('#remedial-dateto').removeAttr('style');
                // }
                
                // if(validationcheck == 2)
                // {
                    $.ajax({
                        url: '/reports_schoolform10/updateremedialheader',
                        type:"GET",
                        dataType:"json",
                        data:{
                            acadprogid : '{{$acadprogid}}',
                            conductdatefrom: conductdatefrom,
                            conductdateto: conductdateto,
                            infoid : infoid
                        },
                        complete: function(){

                            Toast.fire({
                                type: 'success',
                                title: 'Updated successfully!'
                            })
                            
                        }
                    })
                // }

            })
            $(document).on('click','#btn-edit-addremedial', function(){
                $('#remedial-tbody').append(
                    '<tr>'+
                        '<td><input type="text" class="form-control" name="add-new-subject" required/></td>'+
                        '<td><input type="number" class="form-control" name="add-new-finalrating" required/></td>'+
                        '<td><input type="text" class="form-control" name="add-new-classmark" required/></td>'+
                        '<td><input type="number" class="form-control" name="add-new-recomputed" required/></td>'+
                        '<td><input type="text" class="form-control" name="add-new-remarks" required/></td>'+
                        // '<td class="tdInputClass"><input type="number" class="form-control" name="entry[]" required/></td>'+
                        '<td><button type="button" class="btn btn-sm btn-success p-1 btn-edit-addremedial" data-id="'+infoid+'"><i class="fa fa-edit"></i> Save &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> <button type="button" class="btn btn-sm btn-default p-1 removebutton"><i class="fa fa-trash text-danger"></i>&nbsp;</button></td>'+
                    '</tr>'
                );
            })
            $(document).on('click','.btn-edit-addremedial', function(){
                info           = $(this).attr('data-id');
                addsubject     = $(this).closest('tr').find('input[name="add-new-subject"]').val()
                addfinalrating = $(this).closest('tr').find('input[name="add-new-finalrating"]').val()
                addclassmark   = $(this).closest('tr').find('input[name="add-new-classmark"]').val()
                addrecomputed  = $(this).closest('tr').find('input[name="add-new-recomputed"]').val()
                addremarks     = $(this).closest('tr').find('input[name="add-new-remarks"]').val()

                var validationcheck = 0;

                if(addsubject.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-subject"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-subject"]').removeAttr('style')
                }

                if(addfinalrating.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-finalrating"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-finalrating"]').removeAttr('style')
                }
                if(addclassmark.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-classmark"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-classmark"]').removeAttr('style')
                }
                if(addrecomputed.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-recomputed"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-recomputed"]').removeAttr('style')
                }

                if(validationcheck == 4)
                {
                    $.ajax({
                        url: '/reports_schoolform10/addremedial',
                        type:"GET",
                        dataType:"json",
                        data:{
                            acadprogid : '{{$acadprogid}}',
                            infoid: infoid,
                            addsubject: addsubject,
                            addfinalrating: addfinalrating,
                            addclassmark: addclassmark,
                            addrecomputed: addrecomputed,
                            addremarks: addremarks
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){
                            if(data == 1)
                            {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Updated successfully!'
                                })
                                $.ajax({
                                    url: '/reports_schoolform10/getremedialclass',
                                    type: 'GET',
                                    data:{
                                        acadprogid : '{{$acadprogid}}',
                                        infoid : infoid
                                    }, success:function(data)
                                    {
                                        $('#edit-remedialclasscontainer').empty()
                                        $('#edit-remedialclasscontainer').append(data)
                                    }
                                });
                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: 'Already exists!'
                                })
                            }
                            
                        }
                    })
                }else{
                    Toast.fire({
                        type: 'warning',
                        title: 'Some fields are empty!'
                    })
                }

            })
            $(document).on('click','.btn-edit-editremedial', function(){
                var remedialid = $(this).attr('data-id')
                editsubject     = $('#subject'+remedialid).val();
                editfinalrating          = $('#finalrating'+remedialid).val();
                editremclassmark          = $('#remclassmark'+remedialid).val();
                editrecomputedfinal          = $('#recomputedfinal'+remedialid).val();
                editremarks          = $('#remarks'+remedialid).val();

                $.ajax({
                    url: '/reports_schoolform10/editremedial',
                    type:"GET",
                    dataType:"json",
                    data:{
                        acadprogid : '{{$acadprogid}}',
                        remedialid: remedialid,
                        editsubject: editsubject,
                        editfinalrating: editfinalrating,
                        editremclassmark: editremclassmark,
                        editrecomputedfinal: editrecomputedfinal,
                        editremarks: editremarks
                    },
                    complete: function(){

                        Toast.fire({
                            type: 'success',
                            title: 'Updated successfully!'
                        })
                        
                    }
                })

            })
            $(document).on('click','.btn-edit-deleteremedial', function(){
                var remedialid = $(this).attr('data-id');
                var thistr = $(this).closest('tr');
                Swal.fire({
                    title: 'Are you sure you want to delete this row?',
                    // text: "You won't be able to revert this!",
                    html:
                        "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/reports_schoolform10/deleteremedial',
                            type:"GET",
                            dataType:"json",
                            data:{
                                acadprogid : '{{$acadprogid}}',
                                remedialid: remedialid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            complete: function(){

                                        Toast.fire({
                                            type: 'success',
                                            title: 'Remedial Classess updated successfully!'
                                        })

                                        thistr.remove();
                                        

                            }
                        })
                    }
                })
            })
            $(document).on('click','#btn-savefooter', function(){
                var purpose         = $('#purpose').val(); 
                var classadviser    = $('#classadviser').val();
                var recordsincharge = $('#recordsincharge').val(); 
                var lastsy = $('#lastsy').val(); 
                var admissiontograde = $('#admissiontograde').val(); 

                var certcopysentto = $('#certcopysentto').val(); 
                var certaddress = $('#certaddress').val(); 
                
                $.ajax({
                    url: '/reports_schoolform10/submitfooter',
                    type:"GET",
                    dataType:"json",
                    data:{
                        studentid               :   '{{$studentid}}',
                        acadprogid              : '{{$acadprogid}}',
                        purpose                 : purpose,
                        classadviser            : classadviser,
                        recordsincharge         : recordsincharge,
                        lastsy                  : lastsy,
                        admissiontograde        : admissiontograde,
                        certcopysentto          : certcopysentto,
                        certaddress             : certaddress
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    complete: function(){

                        Toast.fire({
                            type: 'success',
                            title: 'Form Footer updated successfully!'
                        })                              

                    }
                })
            })
            $(document).on('click','.btn-addsubjinauto', function(){
                var tbody = $(this).closest('table').find('tbody');
                var syid  = $(this).attr('data-syid');
                var levelid  = $(this).attr('data-levelid');

                tbody.append(
                    '<tr>'+
                        '<td class="p-0"><input type="text" class="form-control form-control-sm subjdesc" placeholder="Description"/></td>'+
                        '<td class="p-0"><input type="number" class="form-control form-control-sm subjq1" placeholder="Q1 Grade"/></td>'+
                        '<td class="p-0"><input type="number" class="form-control form-control-sm subjq2" placeholder="Q2 Grade"/></td>'+
                        '<td class="p-0"><input type="number" class="form-control form-control-sm subjq3" placeholder="Q3 Grade"/></td>'+
                        '<td class="p-0"><input type="number" class="form-control form-control-sm subjq4" placeholder="Q4 Grade"/></td>'+
                        '<td class="p-0"><input type="number" class="form-control form-control-sm subjfinalrating" placeholder="Final Grade"/></td>'+
                        '<td class="p-0"><input type="text" class="form-control form-control-sm subjremarks" placeholder="Remarks"/></td>'+
                        '<td class="p-0 text-right"><button type="button" class="btn btn-default text-success btn-subjauto-save btn-sm" data-syid="'+syid+'" data-levelid="'+levelid+'"><i class="fa fa-share"></i></button><button type="button" class="btn btn-default text-danger removebutton btn-sm"><i class="fa fa-times"></i></button></td>'+
                    '</tr>'
                )
            })
            $(document).on('click','.btn-subjauto-save', function(){
                var subjcode = $(this).closest('tr').find('.subjcode').val();
                var subjdesc = $(this).closest('tr').find('.subjdesc').val();
                var subjq1 = $(this).closest('tr').find('.subjq1').val();
                var subjq2 = $(this).closest('tr').find('.subjq2').val();
                var subjq3 = $(this).closest('tr').find('.subjq3').val();
                var subjq4 = $(this).closest('tr').find('.subjq4').val();
                var subjfinalrating = $(this).closest('tr').find('.subjfinalrating').val();
                var subjremarks = $(this).closest('tr').find('.subjremarks').val();

                if(subjdesc.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('.subjdesc').css('border','1px solid red')
                }else{
                    $(this).closest('tr').find('.subjdesc').removeAttr('style');

                    var syid  = $(this).attr('data-syid');
                    var levelid  = $(this).attr('data-levelid');
                    var thistd  = $(this).closest('td')
                    var thistr  = $(this).closest('tr')
                    $.ajax({
                        url: '/reports_schoolform10/addsubjgradesinauto',
                        type:"GET",
                        dataType:"json",
                        data:{
                            studentid       :   '{{$studentid}}',
                            acadprogid      : '{{$acadprogid}}',
                            syid            : syid,
                            levelid         : levelid,
                            subjcode        : subjcode,
                            subjdesc        : subjdesc,
                            subjq1          : subjq1,
                            subjq2          : subjq2,
                            subjq3          : subjq3,
                            subjq4          : subjq4,
                            subjfinalrating : subjfinalrating,
                            subjremarks     : subjremarks
                        },
                        success: function(data){
                            if(data == 0)
                            {
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Something went wrong!'
                                    })  
                            }else{
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Added successfully!'
                                    })  
                                thistr.find('input').prop('disabled',true)
                                thistd.empty()
                                thistd.append(
                                    '<button type="button" class="btn btn-default text-warning btn-subjauto-edit btn-sm"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-default text-success btn-subjauto-update btn-sm" data-id="'+data+'" disabled><i class="fa fa-share"></i></button><button type="button" class="btn btn-default text-danger btn-subjauto-delete btn-sm" data-id="'+data+'" disabled><i class="fa fa-trash"></i></button>'
                                )
                            }                            

                        }
                    })

                }
            })
            $(document).on('click','.btn-subjauto-edit', function(){
                $(this).closest('tr').find('input,button').prop('disabled',false)
            })
            $(document).on('click','.btn-subjauto-update', function(){
                var subjdesc = $(this).closest('tr').find('.subjdesc').val();
                var subjq1 = $(this).closest('tr').find('.subjq1').val();
                var subjq2 = $(this).closest('tr').find('.subjq2').val();
                var subjq3 = $(this).closest('tr').find('.subjq3').val();
                var subjq4 = $(this).closest('tr').find('.subjq4').val();
                var subjfinalrating = $(this).closest('tr').find('.subjfinalrating').val();
                var subjremarks = $(this).closest('tr').find('.subjremarks').val();

                if(subjdesc.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('.subjdesc').css('border','1px solid red')
                }else{
                    $(this).closest('tr').find('.subjdesc').removeAttr('style');

                    var id          = $(this).attr('data-id');
                    var thisbutton  = $(this);
                    var thistr      = $(this).closest('tr')
                    $.ajax({
                        url: '/reports_schoolform10/updatesubjgradesinauto',
                        type:"GET",
                        dataType:"json",
                        data:{
                            studentid       :   '{{$studentid}}',
                            acadprogid      : '{{$acadprogid}}',
                            id              : id,
                            subjdesc        : subjdesc,
                            subjq1          : subjq1,
                            subjq2          : subjq2,
                            subjq3          : subjq3,
                            subjq4          : subjq4,
                            subjfinalrating : subjfinalrating,
                            subjremarks     : subjremarks
                        },
                        success: function(data){
                            if(data == 1)
                            {
                                    thistr.find('input,button').prop('disabled',true)
                                    thistr.find('.btn-subjauto-edit').prop('disabled',false)
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Added successfully!'
                                    })   
                            }else{
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Something went wrong!'
                                    }) 
                            }                            

                        }
                    })

                }
            })
            $(document).on('click','.btn-subjauto-delete', function(){
                    var id          = $(this).attr('data-id');
                    var thistr      = $(this).closest('tr')
                    Swal.fire({
                        title: 'Are you sure you want to delete this row?',
                        // text: "You won't be able to revert this!",
                        html:
                            "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: '/reports_schoolform10/deletesubjgradesinauto',
                                type:"GET",
                                dataType:"json",
                                data:{
                                    id: id
                                },
                                // headers: { 'X-CSRF-TOKEN': token },,
                                complete: function(){

                                            Toast.fire({
                                                type: 'success',
                                                title: 'Deletedd successfully!'
                                            })

                                            thistr.remove();
                                            

                                }
                            })
                        }
                    })
            })

        })
    </script>