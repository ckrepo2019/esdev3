

    @php
    $now = \Carbon\Carbon::now();
    $comparedDate = $now->toDateString();
    $refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->first();
        
    if($refid->refid == '23')
    {
        $extends = 'clinic';
    }elseif($refid->refid == '24'){

        $extends = 'clinic_nurse';
    }elseif($refid->refid == '25'){

        $extends = 'clinic_doctor';
    }

@endphp
@extends($extends.'.layouts.app')

<style>
    .dataTable                  { font-size:80%; }
    .tschoolschedule .card-body { height:250px; }
    .tschoolcalendar            { font-size: 12px; }
    .tschoolcalendar .card-body { height: 250px; overflow-x: scroll; }
    .teacherd ul li a           { color: #fff; -webkit-transition: .3s; }
    .teacherd ul li             { -webkit-transition: .3s; border-radius: 5px; background: rgba(173, 177, 173, 0.3); margin-left: 2px; }
    .sf5                        { background: rgba(173, 177, 173, 0.3)!important; border: none!important; }
    .sf5menu a:hover            { background-color: rgba(173, 177, 173, 0.3)!important; }
    .teacherd ul li:hover       { transition: .3s; border-radius: 5px; padding: none; margin: none; }

    .small-box                  { box-shadow: 1px 2px 2px #001831c9; overflow-y: auto scroll; }

    .small-box h5               { text-shadow: 1px 1px 2px gray; }

    img{
        border-radius: unset !important;
    }

    .select2-container .select2-selection--single {
        height: 40px !important;
    }
    table th, table td {
        padding: 2px !important;
    }
</style>
@section('content')

@php
	
	
	$teachers = DB::table('teacher')
					->where('isactive',1)
					->where('deleted',0)
					->select(
						'id',
						 DB::raw("CONCAT(teacher.tid,' - ',teacher.lastname,' ',teacher.firstname) as name_showlast")
					)
					->get();
					
	$students = DB::table('studinfo')
					->where('studisactive',1)
					->where('deleted',0)
					->select(
						'id',
						 DB::raw("CONCAT(studinfo.sid,' - ',studinfo.lastname,' ',studinfo.firstname) as name_showlast")
					)
					->get();

	$patients = $teachers->merge($students);
					

   
@endphp

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Medical History</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Medical History</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <!-- Default box -->
        <div class="card">
          <div class="card-header">
              <div class="row mb-2">
                <div class="col-md-5">
                    <label>Select SHD Form</label>
                    <select class="form-control select2" style="width: 100%;" id="select-form">
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                        <option value="formpcc_healthrec" data-formtype="1">Health Record</option>
                        @endif
                        <option disabled="disabled">Form 1</option>
                        <option value="form1p1" data-formtype="1">1 P1 (School Health Examination Card)</option>
                        <option value="form1a" data-formtype="1">1 A (Medical History (For Learners)</option>
                        <option value="form1b" data-formtype="1">1 B (Medical/Nursing Findings)</option>
                        <option value="form1c" data-formtype="1">1 C (Medical Treatment Record)</option>
                        <option value="form1d" data-formtype="1">1 D (Dental Findings)</option>
                        <option value="form1da" data-formtype="1">1 Da (Cont...)</option>
                        <option value="form1db" data-formtype="1">1 Db (Intervention/Treatment Record)</option>
                        <option disabled="disabled">Form 2 A&B</option>
                        <option value="form2a" data-formtype="2">Form 2 A (Medical)</option>
                        <option value="form2b" data-formtype="2">Form 2 B (Dental)</option>
                        <option disabled="disabled">Form 3 Referral</option>
                        <option value="form3a" data-formtype="1">3 A (Medical Referral Slip)</option>
                        <option value="form3b" data-formtype="1">3 B (Dental Referral Form)</option>
                        <option disabled="disabled" data-formtype="1">Form 4 T&NTP</option>
                        <option value="form4" data-formtype="1">Teacher's Health Card</option>
                        <option value="form4a" data-formtype="1">4 A (HEALTH EXAMINATION RECORD)</option>
                    </select>
                </div>
                <div class="col-md-7" id="container-selectuser">
                    <label>Student / Faculty & Staff</label>
                    <select class="form-control select2" style="width: 100%;" id="select-user">
						<option value=""><span class="badge badge-info">Select Patient</option>
                        @foreach($patients as $user)
                            <option value="{{$user->id}}"><span class="badge badge-info">{{$user->name_showlast}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-7" id="container-selectdate">
                    <label>Date</label>
                    <input class="form-control" style="width: 100%;" id="select-daterange"/>
                </div>
              </div>
              <div class="row mb-2">
                  {{-- <div class="col-md-12">
                      <em>Note: Save changes if sa iyang existing kay complete tapos iuncheck niya ang isa dapat pag reload unchecked na</em>
                  </div> --}}
                  <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-primary" id="button-generate"><i class="fa fa-sync"></i> Generate</button>
                  </div>
              </div>
          </div>
          <!-- /.card-body -->
        </div>
        
        <div class="card" id="card-result">
            
        </div>
        <!-- /.card -->
    </section>
    @endsection
    @section('footerjavascript')
    <script>
        //$('body').addClass('sidebar-collapse');
        $('#card-result').hide();
        $('#container-selectdate').hide()
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
        
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
            $('#select-daterange').daterangepicker()
            // $('#input-daterange').daterangepicker()
        })  
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        
        function getrecord(userid,selectedform,daterange)
        {
            
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })

            $.ajax({
                url: '/clinic/records/getform',
                type: 'GET',
                data: {
                    userid          : userid,
                    selectedform    : selectedform,
                    daterange    : daterange
                },
                success:function(data){
                    $('#card-result').show();
                    $('#card-result').empty();
                    $('#card-result').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        }
        $(document).ready(function(){
            $('#select-form').on('change', function(){
                var option = $('option:selected', this).attr('data-formtype');
                console.log(option)
                if(option==1)
                {
                console.log('one')
                    $('#container-selectuser').show();
                    $('#container-selectdate').hide()
                }else{
                console.log('two')
                    $('#container-selectuser').hide();
                    $('#container-selectdate').show()
                }
            })
            $('#button-generate').on('click', function(){
                getrecord($('#select-user').val(),$('#select-form').val(),$('#select-daterange').val())
            })
            
            $(document).on('click','#btn-savechangesform1a', function(){
                var userid = $('#select-user').val();
                
                var answervalues = [];
                var yesornovalues = [];
                var descriptionvalues = [];

                $('.questionid').each(function(){
                    var questionid = $(this).attr('data-id');
                    $('.choices[data-questionid='+questionid+']:checked').each(function(){
                        obj = {
                            questionid      : questionid,
                            choiceid      : $(this).val()
                        };
                        answervalues.push(obj);
                    })
                    
                    if($('[name="yesorno'+questionid+'"]').length > 0)
                    {
                        obj = {
                                questionid      : questionid,
                                yesorno       : $('[name="yesorno'+questionid+'"]:checked').val()
                            };

                        yesornovalues.push(obj);
                    }
                    if($('[name="choice-description'+questionid+'"]').length > 0)
                    {
                        $('[name="choice-description'+questionid+'"]').each(function(){

                            // if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
                            // {

                                obj = {
                                    questionid      : questionid,
                                    choiceid       : $(this).attr('data-choiceid'),
                                    description       : $(this).val()
                                };

                                descriptionvalues.push(obj);  
                            // }                      
                        })
                    }
                })
                
                Swal.fire({
                    title: 'Saving changes...',
                    allowOutsideClick: false,
                    closeOnClickOutside: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                })  
                $.ajax({
                    url: '/clinic/records/submitform1a',
                    type: 'GET',
                    data: {
                        userid              : userid,
                        answervalues        : answervalues,
                        yesornovalues       : yesornovalues,
                        descriptionvalues   : descriptionvalues
                    },
                    success:function(data){
                        
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        if(data == 1)
                        {
                            toastr.success('Updated successfully!', 'SHD Form 1A')
                        }else{
                            toastr.error('Something went wrong!', 'SHD Form 1A')
                        }
                        // toastr.success('Updated successfully!', 'Class Attendance')
                        // $('#btn-generate').click()
                    }
                    // , error:function()
                    // {
                    //     saveattendance(selectedschoolyear,selectedsemester,dataobj)
                    // }
                })
            })
            var eachfindingdesccounter = 0;
            function saveform1b(userid,dataobj)
            {
                var firstobj = [dataobj[0]];
                if(dataobj.length != 0)
                {
                    // console.log(dataobj.length)
                    // console.log(firstobj[0].descid)
                    // console.log(firstobj[0].descid)
                    // console.log(firstobj[0].inputvalues)
                    $.ajax({
                            url: '/clinic/records/submitform1b',
                            type: 'GET',
                            data: {
                                userid                : userid,
                                descid             : firstobj[0].descid,
                                monthchecks        :JSON.stringify(firstobj[0].monthchecks),
                                checkboxes         :JSON.stringify(firstobj[0].checkboxes),
                                inputs             : JSON.stringify(firstobj[0].inputs)
                                // datavalues   : firstobj
                            },
                            success:function(data){
                                eachfindingdesccounter+=1;
                                $('#attcounting').text(eachfindingdesccounter);
                                dataobj     = dataobj.filter(x=> x.descid != firstobj[0].descid)
                                saveform1b(userid,dataobj)
                                // $(".swal2-container").remove();
                                // $('body').removeClass('swal2-shown')
                                // $('body').removeClass('swal2-height-auto')
                                // toastr.success('Updated successfully!', 'Class Attendance')
                                // $('#btn-generate').click()
                            }, error:function()
                            {
                                saveform1b(userid,dataobj)
                            }
                        })
                }else{
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    toastr.success('Updated successfully!', 'Class Attendance')
                    $('#btn-generate').click()
                }
            }
            var totalchanges;
            $(document).on('click','#btn-savechangesform1b', function(){
                Swal.fire({
                    title: 'Saving changes...',
                    allowOutsideClick: false,
                    closeOnClickOutside: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                })  
                var alldata = [];
                var validateresponse = 0;
                $('.each-finding').each(function(){
                    var monthcheck = []
                    if($(this).find('.finding-monthcheck').length>0)
                    {
                        $(this).find('.finding-monthcheck').each(function(){
                            if($(this).find('input[type="checkbox"]:checked').length>0)
                            {
                                var monthcheckstatus = 1;
                            }else{
                                var monthcheckstatus = 0;
                            }
                            obj = {
                                levelid           : $(this).attr('data-levelid'),
                                monthchecked      : $(this).attr('data-month'),
                                monthcheckstatus  : monthcheckstatus
                            };
                            monthcheck.push(obj);  
                        })
                    }
                    var checkbox = [];
                    if($(this).find('.finding-check').length>0)
                    {
                        $(this).find('.finding-check').each(function(){
                            if($(this).find('input[type="checkbox"]:checked').length>0)
                            {
                                var checkstatus = 1;
                            }else{
                                var checkstatus = 0;
                            }
                            obj = {
                                levelid           : $(this).attr('data-levelid'),
                                checkstatus       : checkstatus
                            };
                            checkbox.push(obj);  
                        })
                    }
                    var inputvalues = [];
                    if($(this).find('.finding-input').length>0)
                    {
                        $(this).find('.finding-input').each(function(){
                            obj = {
                                levelid           : $(this).attr('data-levelid'),
                                inputvalue        : $(this).find('input').val()
                            };
                            inputvalues.push(obj);  
                        })
                    }
                    obj = {
                        descid             : $(this).attr('data-id'),
                        monthchecks        :monthcheck,
                        checkboxes         :checkbox,
                        inputs             : inputvalues
                    };
                    
                    alldata.push(obj);  

                })
                eachfindingdesccounter = 0;
                totalchanges = alldata.length;
                Swal.fire({
                    title: 'Saving changes...',
                    html:'<span id="attcounting"></span>/'+totalchanges,
                    allowOutsideClick: false,
                    closeOnClickOutside: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                })  
                saveform1b($('#select-user').val(),alldata)
            })
            $(document).on('click', '#btn-emptyrecord-form', function(){
                var userid = $(this).attr('data-userid');
                var formtype = $(this).attr('data-formtype');
                Swal.fire({
                    title: 'You are going to empty this record.',
                    html: 'You won\'t be able to revert this!<br/>Would you like to continue?',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Continue'
                })
                .then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/clinic/records/emptyform',
                            type:'GET',
                            dataType: 'json',
                            data: {
                                userid      :  userid,
                                formtype    :  formtype
                            },
                            success:function(data) {
                                if(data == 1)
                                {
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Record emptied successfully!'
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
            })
        })
    </script>
@endsection
