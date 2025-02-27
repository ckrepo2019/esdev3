@php
      if(auth()->user()->type == 7){
            $extend = 'studentPortal.layouts.app2';
      }else if(auth()->user()->type == 9){
            $extend = 'parentsportal.layouts.app2';
      }
@endphp

@extends($extend)


@section('pagespecificscripts')

    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            border: 0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
    </style>

@endsection


@section('content')


<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Grades</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Grades</li>
            </ol>
            </div>
        </div>
    </div>
</section>
<section class="content pt-0">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12">
                <div class="info-box">
                  <div class="info-box-content">
                    <span class="info-box-number">
                        <div class="row">
                            <div class="col-md-2 form">
                                <label for="">School Year</label>
                                <select class="form-control form-control-sm select2" id="filter_sy" >
                                    @php
                                        $sy = DB::table('sy')->orderBy('sydesc','desc')->get();
                                    @endphp
                                    @foreach ($sy as $item)
                                        @php
                                            $selected = '';
                                            if($item->isactive == 1){
                                                $selected = 'selected="selected"';
                                            }
                                        @endphp
                                        <option value="{{$item->id}}" {{$selected}} value="{{$item->id}}">{{$item->sydesc}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-md-2" id="filter_sem_holder" hidden>
                                <label for="">Semester</label>
                                <select class="form-control form-control-sm select2" id="filter_sem">
                                    @php
                                        $sy = DB::table('semester')->get();
                                    @endphp
                                    @foreach ($sy as $item)
                                        @php
                                            $selected = '';
                                            if($item->isactive == 1){
                                                $selected = 'selected="selected"';
                                            }
                                        @endphp
                                        <option value="{{$item->id}}" {{$selected}} value="{{$item->id}}">{{$item->semester}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                
                            </div>
                            <div class="col-md-2">
                                <span class="info-box-text">Grade Level</span>
                                <p class="text-muted" id="gradelevel_info">--</p>
                            </div>
                            <div class="col-md-4">
                                <span class="info-box-text">Section</span>
                                <p class="text-muted" id="section_info">--</p>
                            </div>
                        </div>
                    </span>
                  </div>
                </div>
            </div>
        </div>
        <div class="row college_grades" hidden>
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Grades</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="mb-0 table table-bordered table-sm font-sm college_grades" hidden style="font-size:.8rem" width="100%">
                            <thead>
                                <tr>
                                    <th class="align-middle" width="10%">Code</th>
                                    <th class="align-middle" width="30%">Subject Description</th>
                                    <th class="align-middle text-center term_holder" width="10%" data-term="1" hidden>Pelim</th>
                                    <th class="align-middle text-center term_holder" width="10%" data-term="2" hidden>Midterm</th>
                                    <th class="align-middle text-center term_holder" width="10%" data-term="3" hidden>PreFinal</th>
                                    <th class="align-middle text-center term_holder" width="10%" data-term="4" hidden>Final Term</th>
                                    <th class="align-middle text-center term_holder" width="10%" data-term="5">Final Grade</th>
                                    <th class="align-middle text-center term_holder" width="10%" >Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="college_grade_table">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body p-1 pl-2">
                         <i class="text-danger">Contact your instructor for failed and INC grades.</i>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="row basic_ed" hidden>
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-header  bg-secondary">
                        <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Grades</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="mb-0 table table-bordered table-sm font-sm nursery_grades" hidden style="font-size:.7rem" width="100%">
                            <thead>
                                <tr>
                                    <td class="p-1 align-middle text-center" width="60%"></td>
                                    <td class="p-1 align-middle text-center" width="10%">1</td>
                                    <td class="p-1 align-middle text-center" width="10%">2</td>
                                    <td class="p-1 align-middle text-center" width="10%">3</td>
                                    <td class="p-1 align-middle text-center" width="10%" hidden id="q4_kinder">4</td>
                                </tr>
                            </thead>
                            <tbody id="nursery_setup">
                            </tbody>
                        </table>
                        <table class="mb-0 table table-bordered table-sm font-sm not_sh_grades" hidden style="font-size:.8rem" width="100%">
                            <thead>
                                  <tr>
                                        <td class="p-1 align-middle text-center" rowspan="2" width="55%"><small>SUBJECTS</small></td>
                                        <td class="p-1 align-middle pr" align="center" colspan="4" width="20%" id="pr"><small>PERIODIC RATINGS</small></td>
                                        <td class="p-1 align-middle" align="center" rowspan="2" width="10%"><small>Final<br>Rating</small></td>
                                        <td class="p-1 align-middle" align="center" rowspan="2" width="15%"><small>Action<br>Taken</small></td>
    
                                  </tr>
                                    <tr align="center">
                                            <td class="p-1" id="q1_fg" width="5%"><small>1</small></td>
                                            <td class="p-1" id="q2_fg" width="5%"><small>2</small></td>
                                            <td class="p-1" id="q3_fg" width="5%"><small>3</small></td>
                                            <td class="p-1" id="q4_fg" width="5%"><small>4</small></td>
                                    </tr>
                            </thead>
                            <tbody id="enrollment_history_grade">
                            </tbody>
                        </table>

                        <table class="mb-0 table table-bordered table-sm font-sm sh_grades" hidden style="font-size:.7rem" width="100%">
                            <thead>
                                <tr>
                                    <td class="p-1 align-middle text-center" rowspan="1" width="55%">1st Semester</td>
                                    <td class="p-1 align-middle text-center" colspan="2" width="20%">Periodic Ratings</td>
                                    <td class="p-1 align-middle text-center" rowspan="2" width="10%">Final<br>Rating</td>
                                    <td class="p-1 align-middle text-center" rowspan="2" width="15%">Action<br>Taken</td>

                                </tr>
                                <tr>
                                    <td class="p-1 text-center">Subjects</td>
                                    <td class="p-1 text-center" id="q1_fg">1</td>
                                    <td class="p-1 text-center" id="q2_fg">2</td>
                                </tr>
                                
                            </thead>
                            <tbody id="enrollment_history_grade_sem1">
                            </tbody>
                        </table>

                   
                        <table class="mb-0 table table-bordered table-sm font-sm sh_grades mt-3" style="font-size:.7rem" width="100%">
                            <thead>
                                <tr>
                                    <td class="p-1 align-middle text-center" rowspan="1" width="55%">2nd Semester</td>
                                    <td class="p-1 align-middle text-center" colspan="2" width="20%">Periodic Ratings</td>
                                    <td class="p-1 align-middle text-center" rowspan="2" width="10%">Final<br>Rating</td>
                                    <td class="p-1 align-middle text-center" rowspan="2" width="15%">Action<br>Taken</td>

                                </tr>
                                <tr>
                                    <td class="p-1 text-center">Subjects</td>
                                    <td class="p-1 text-center" id="q1_fg">3</td>
                                    <td class="p-1 text-center" id="q2_fg">4</td>
                                </tr>
                                
                            </thead>
                            <tbody id="enrollment_history_grade_sem2">
                            </tbody>
                        </table>

                        

                        <table class="mb-0 table table-bordered table-sm font-sm no_grades" style="font-size:.7rem">
                            <tr>    
                                <td>
                                    <i>No grades available</i>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5" >
                <div class="row">
                    <div class="col-md-12 nursery_side" hidden>
                        <div class="card shadow">
                            <div class="card-header bg-primary">
                                <h3 class="card-title"><i class="fas fa-clipboard-list"></i> 1st Administration</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-bordered display p-0" width="100%" style="font-size:.8rem">
                                    <thead>
                                        <tr>
                                                <th width="40%" class="align-middle">Domains</th>
                                                <th width="30%" class="text-center">RAW SCORE</th>
                                                <th width="30%" class="text-center">SCALED SCORE</th>
                                        </tr>
                                    </thead>
                                    <tbody id="admin_1">

                                    </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 nursery_side" hidden>
                        <div class="card shadow">
                            <div class="card-header bg-primary">
                                <h3 class="card-title"><i class="fas fa-clipboard-list"></i> 2nd Administration</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-bordered display p-0" width="100%" style="font-size:.8rem">
                                    <thead>
                                        <tr>
                                                <th width="40%" class="align-middle">Domains</th>
                                                <th width="30%" class="text-center">RAW SCORE</th>
                                                <th width="30%" class="text-center">SCALED SCORE</th>
                                        </tr>
                                    </thead>
                                    <tbody id="admin_2">

                                    </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 nursery_side" hidden>
                        <div class="card shadow">
                            <div class="card-header bg-primary">
                                <h3 class="card-title"><i class="fas fa-clipboard-list"></i> 3rd Administration</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-bordered display p-0" width="100%" style="font-size:.8rem">
                                    <thead>
                                        <tr>
                                                <th width="40%" class="align-middle">Domains</th>
                                                <th width="30%" class="text-center">RAW SCORE</th>
                                                <th width="30%" class="text-center">SCALED SCORE</th>
                                        </tr>
                                    </thead>
                                    <tbody id="admin_3">

                                    </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 nursery_side" hidden>
                        <div class="card shadow">
                            <div class="card-body p-0">
                                <table class="table table-bordered table-sm " width="100%" style="font-size:.8rem">
                                    <tr>
                                        <td width="30%" class="text-center">Standard Score</td>
                                        <td width="70%">Interpretation</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">69 and below</td>
                                        <td >Suggest significat delay in overall development</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">70 - 79</td>
                                        <td>Suggest slight delay in overall development</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">80 - 119</td>
                                        <td>Average overall development</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">120 - 129</td>
                                        <td>Suggest slightly advance development</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">130 and above</td>
                                        <td>Suggest highly advanced development</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 nursery_side" hidden>
                        <div class="card shadow">
                            <div class="card-header  bg-primary">
                                <h3 class="card-title"><i class="fas fa-clipboard-list"></i> CHRISTIAN LIVING EDUCATION</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-bordered display p-0" width="100%" style="font-size:.8rem">
                                    <thead>
                                        <tr>
                                                <th width="55%" class="align-middle"></th>
                                                <th width="15%" class="text-center">1</th>
                                                <th width="15%" class="text-center">2</th>
                                                <th width="15%" class="text-center">3</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cl_educ">

                                    </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 nursery_side" hidden>
                        <div class="card shadow">
                            <div class="card-body p-0">
                                <table class="table  table-sm " width="100%" style="font-size:.8rem">
                                    <tr>
                                        <td width="50%">Always Observed</td>
                                        <td width="50%"><i class="fas fa-star"></i></td>
                                    </tr>
                                    <tr>
                                        <td width="50%">Sometimes observed/td>
                                        <td width="50%"><i class="fas fa-heart"></i></td>
                                    </tr>
                                    <tr>
                                        <td width="50%">Rarely observed</td>
                                        <td width="50%"><i class="fas fa-minus"></i></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 kinder_side" hidden>
                        <div class="card shadow">
                            <div class="card-header  bg-primary">
                                <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Rating Scale</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table  table-bordered table-sm " width="100%" style="font-size:.8rem">
                                    <tr>
                                        <th width="30%" class="text-center">Rating</th>
                                        <th width="70%">Indicators</th>
                                    </tr>
                                    <tr>
                                        <td class="text-center align-middle" rowspan="3">Beginning (B)</td>
                                        <td class="p-1">Rarely demonstrates the expected competency</td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">Rarely participates in class activities and /or initiates independent works</td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">Shows interest in doing tasks but needs close supervision</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center align-middle" rowspan="3">Developing (D)</td>
                                        <td class="p-1">Sometimes demonstrates the competency</td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">Sometimes participates, minimal supervision</td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">Progresses continuously in doing assigned tasks</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center align-middle" rowspan="3">Consistent (C)</td>
                                        <td class="p-1">Always demonstrates the expected competency</td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">Always participates in the different activities, works independently</td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">Always performs tasks, advanced in some aspects</td>
                                    </tr>
                                    
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 kinder_side" hidden>
                        <div class="card shadow">
                            <div class="card-header  bg-primary">
                                <h3 class="card-title"><i class="fas fa-clipboard-list"></i> TEACHER’S COMMENTS/REMARKS</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-bordered display p-0" width="100%" style="font-size:.8rem">
                                    <tbody id="kinder_comments">

                                    </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                  
                    <div class="col-md-12 basic_ed" hidden>
                        <div class="card shadow">
                            <div class="card-header  bg-success">
                                <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Attendance</h3>
                            </div>
                            <div class="card-body p-0 table-responsive" >
                                <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" width="100%" style="font-size:.8rem; min-width:400px !important;">
                                    <tbody id="data_2">

                                    </tbody>
                              </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 basic_ed" hidden>
                        <div class="card shadow">
                            <div class="card-header  bg-primary">
                                <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Core Values</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped table-sm table-bordered table-head-fixed nowrap display p-0" width="100%" style="font-size:.8rem">
                                    <thead>
                                          <tr>
                                                <th width="68%" class="align-middle"></th>
                                                <th width="8%" class="text-center">Q1</th>
                                                <th width="8%" class="text-center">Q2</th>
                                                <th width="8%" class="text-center">Q3</th>
                                                <th width="8%" class="text-center pr-2">Q4</th>
                                          </tr>
                                    </thead>
                                    <tbody id="data">

                                    </tbody>
                              </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>

<script>
    $('#filter_sy').select2()
    $('#filter_sem').select2()
</script>
<script>
    
        ob_setup_data = []
        function ob_setup() {
            $.ajax({
                type:'GET',
                url: '/student/enrollment/record/reportcard/observedvalues',
                data:{
                    syid:$('#filter_sy').val()
                },
                success:function(data) {
                    ob_setup_data = data
                    if(ob_setup_data[0].ob_setup.length == 0){
                        $('#data').empty();
                        $('#data').append('<tr><td colspan="5"><i>No Core Values available</i></td></tr>')
                    }else{
                        plot_setup()
                    }
                   
                }
            })
        }
        function plot_setup() {
            $('#data').empty();
            $.each(ob_setup_data[0].ob_setup,function(a,b){
                $('#data').append('<tr><td class="p-2">'+b.description+'</td><td  data-id="'+b.id+'" quarter="1" class="student_ob"></td><td  data-id="'+b.id+'" quarter="2" class="student_ob"></td><td  data-id="'+b.id+'" quarter="3" class="student_ob"></td><td class="pr-2 student_ob"  data-id="'+b.id+'" quarter="4"></td></tr>')
            })

            $('.student_ob').addClass('text-center')
            $('.student_ob').addClass('align-middle')

            var rv = ob_setup_data[0].ob_rv
            $.each(ob_setup_data[0].student_ob,function(a,b){
                
                var q1text = rv.filter(x=>x.id == b.q1eval)
                var q2text = rv.filter(x=>x.id == b.q2eval)
                var q3text = rv.filter(x=>x.id == b.q3eval)
                var q4text = rv.filter(x=>x.id == b.q4eval)

                $('.student_ob[data-id="'+b.gsdid+'"][quarter="1"]').text(q1text.length > 0 ? q1text[0].value : '')
                $('.student_ob[data-id="'+b.gsdid+'"][quarter="2"]').text(q2text.length > 0 ? q2text[0].value : '')
                $('.student_ob[data-id="'+b.gsdid+'"][quarter="3"]').text(q3text.length > 0 ? q3text[0].value : '')
                $('.student_ob[data-id="'+b.gsdid+'"][quarter="4"]').text(q4text.length > 0 ? q4text[0].value : '')
            })
        }
    
</script>


<script>
        attendance_data = []
        function attendance() {
            $.ajax({
                type:'GET',
                url: '/student/enrollment/record/reportcard/attendance',
                data:{
                    syid:$('#filter_sy').val()
                },
                success:function(data) {

                    if(data[0].att_setup.length == 0){
                        $('#data_2').empty();
                        $('#data_2').append('<tr><td colspan="2"><i>No Attendance avaiable</i></td></tr>')
                    }else{
                       

                        $('#data_2').empty();
                        attendance_data = data
                        
                        var numberofdays_string = ''
                        var numberofpresent_string = ''
                        var numberofabsent_string = ''
                        var days_string = ''
                        var width = 75 / attendance_data[0].att_setup
                        var tolal_schooldays = 0;
                        var tolal_present = 0;
                        var tolal_absent = 0;

                        $.each(attendance_data[0].att_setup,function(a,b){
                            days_string += '<td class="text-center align-middle" width="'+width+'">'+b.monthdesc.slice(0, 3)+'</td>'
                            numberofdays_string += '<td class="text-center align-middle" width="'+width+'">'+b.days+'</td>'
                            numberofpresent_string += '<td class="text-center align-middle">'+b.present+'</td>'
                            numberofabsent_string += '<td class="text-center align-middle">'+b.absent+'</td>'
                            tolal_schooldays += parseInt(b.days)
                            tolal_present += parseInt(b.present)
                            tolal_absent += parseInt(b.absent)
                        })

                        $('#data_2').append('<tr><td class="pl-2" width="15%"</td>'+days_string+'<td width="10%" class="text-center align-middle  pr-2">TOTAL</td></tr>')
                        $('#data_2').append('<tr><td class="pl-2"  width="15%">No. of School Days</td>'+numberofdays_string+'<td class="text-center align-middle pr-2">'+tolal_schooldays+'</td></tr>')
                        $('#data_2').append('<tr><td class="pl-2"  width="15%">No. of Days Present</td>'+numberofpresent_string+'<td class="text-center align-middle pr-2">'+tolal_present+'</td></tr>')
                        $('#data_2').append('<tr><td class="pl-2"  width="15%">No. of Days Absent</td>'+numberofabsent_string+'<td class="text-center align-middle pr-2">'+tolal_absent+'</td></tr>')
                    }
                }
            })
        }
</script>


<script>
     $(document).ready(function(){

        $(document).on('change','#filter_sy',function(){
            var temp_syid = $('#filter_sy').val()
            var check_enrollment = all_enrollmentinfo.filter(x=>x.syid ==  temp_syid)

            if(check_enrollment.length == 0){
                $('#filter_sem_holder').attr('hidden','hidden')
                $('#gradelevel_info').text('No Record')
                $('#section_info').text('--')
                get_grades()
                ob_setup()
                attendance()
                return false;
            }

            if(check_enrollment[0].acadprogid == 6 ){
                $('#filter_sem_holder').removeAttr('hidden')
                $('#section_info').text(check_enrollment[0].sectionname)
            }else{
                ob_setup()
                attendance()
                $('#filter_sem_holder').attr('hidden','hidden')
                $('#section_info').text(check_enrollment[0].sectionname)
            }
            $('#gradelevel_info').text(check_enrollment[0].levelname)
            get_grades()
        })

        $(document).on('change','#filter_sem',function(){
            var temp_syid = $('#filter_sy').val()
            var check_enrollment = all_enrollmentinfo.filter(x=>x.syid ==  temp_syid)
            if(check_enrollment.length == 0){
                get_grades()
                return false
            }
            if(check_enrollment[0].acadprogid == 6 ){
                get_grades()
            }
            
        })

        var school = @json(DB::table('schoolinfo')->first()->abbreviation)

        // get_grades()
        get_enrollment()

        var all_enrollmentinfo = []

        function get_enrollment(){
            var temp_syid = $('#filter_sy').val()
            $.ajax({
                type:'GET',
                url: '/current/enrollment',
                data:{
                    all:'all'
                },
                success:function(data) {
                    all_enrollmentinfo = data
                    var check_enrollment = all_enrollmentinfo.filter(x=>x.syid ==  temp_syid)
                    if(check_enrollment.length == 0){
                        $('#filter_sem_holder').attr('hidden','hidden')
                        $('#gradelevel_info').text('No Record')
                        $('#section_info').text('--')
                        get_grades()
                        ob_setup()
                        attendance()
                        return false;
                    }
                    if(check_enrollment[0].acadprogid == 6 ){
                        $('#section_info').text(check_enrollment[0].sectionname)
                        $('#filter_sem_holder').removeAttr('hidden')
                    }else{
                        $('#section_info').text(check_enrollment[0].sectionname)
                        ob_setup()
                        attendance()
                    }
                    $('#gradelevel_info').text(check_enrollment[0].levelname)
                    get_grades()
                }
            })
        }

        function get_grades(){

            $('.sh_grades').attr('hidden','hidden')
            $('.not_sh_grades').attr('hidden','hidden')
            $('.no_grades').attr('hidden','hidden')
            
            $('#enrollment_history_grade').empty()
            $('#enrollment_history_grade_sem1').empty()
            $('#enrollment_history_grade_sem2').empty()
            $.ajax({
                type:'GET',
                url: '/student/enrollment/record/reportcard/grades',
                data:{
                    syid:$('#filter_sy').val(),
                    semid:$('#filter_sem').val(),
                    type:'today'
                },
                success:function(data) {

                    $('.college_grades').attr('hidden','hidden')
                    $('.basic_ed').attr('hidden','hidden')

                    if(data[0].grades.filter(x=>x.id != 'G1').length == 0){
                        $('.no_grades').removeAttr('hidden')
                        return false
                    }

                    var levelid = data[0].levelid;

                    

                    if(levelid == 14 || levelid == 15){

                        $('.sh_grades').removeAttr('hidden')
                        $('.basic_ed').removeAttr('hidden')
                        
                        for(var sem=1;sem<=2;sem++){
                            var subjgrades = data[0].grades.filter(x=>x.id != 'G1' && x.semid == sem)
                            $.each(subjgrades,function (a,b){
                                var padding = b.subjCom != null ? 'pl-5':''
                                var quarter1_grade = b.q1 != null ? b.q1 : ''
                                var quarter2_grade = b.q2 != null ? b.q2 : ''
                                var quarter3_grade = b.q3 != null ? b.q3 : ''
                                var quarter4_grade = b.q4 != null ? b.q4 : ''
                                var finalrating = b.finalrating != null ? b.finalrating : ''
                                var actiontaken = b.actiontaken != null ? b.actiontaken : ''
                                if(sem == 1){
                                    $('#enrollment_history_grade_sem1').append('<tr><td class="p-1 '+padding+'" >'+b.subjdesc+'</td><td class="text-center align-middle">'+quarter1_grade+'</td><td class="text-center align-middle">'+quarter2_grade+'</td></td><td class="text-center align-middle">'+finalrating+'</td><td class="text-center align-middle p-1">'+actiontaken+'</td></tr>')
                                }else{
                                    $('#enrollment_history_grade_sem2').append('<tr><td class=" p-1 '+padding+'" >'+b.subjdesc+'</td><td class="text-center align-middle">'+quarter3_grade+'</td><td class="text-center align-middle">'+quarter4_grade+'</td><td class="text-center align-middle">'+finalrating+'</td><td class="text-center align-middle p-1">'+actiontaken+'</td></tr>')
                                }
                            })
                            
                            var finalgrade = data[0].grades.filter(x=>x.id == 'G1'  && x.semid == sem)
                            var colspan = 5;
                            $.each(finalgrade,function (a,b){
                                var finalrating = b.finalrating != null ? b.finalrating : ''
                                var actiontaken = b.actiontaken != null ? b.actiontaken : ''

                                if(sem == 1){
                                    $('#enrollment_history_grade_sem1').append('<tr><td  colspan="3" class="text-right p-1">'+b.subjdesc+'</td><td class="text-center align-middle">'+finalrating+'</td><td class="text-center align-middle">'+actiontaken+'</td></tr>') 
                                }else{
                                    $('#enrollment_history_grade_sem2').append('<tr><td  colspan="3" class="text-right p-1">'+b.subjdesc+'</td><td class="text-center align-middle">'+finalrating+'</td><td class="text-center align-middle p-1">'+actiontaken+'</td></tr>') 
                                }
                            })
                        }
                    }else if(levelid >= 17 && levelid <= 20){

                        // console.log(data[0].setup)
                        var setup = data[0].setup

                        var withPrelem = setup[0].isPrelimDisplay == 1 ? true :false
                        var withMidterm =  setup[0].isMidtermDisplay == 1 ? true :false
                        var withpreFinal =  setup[0].isPrefiDisplay == 1 ? true :false
                        var withFinalterm =  setup[0].isFinalDisplay == 1 ? true :false
                        var withFinalGrade = true

                        var fgtype = 'finalterm'
                        var pointScale = 1

                        var term_count = 0;

                        var prelemDisplay =  'hidden'  
                        var midTermDisplay =  'hidden' 
                        var preFiTermDisplay = 'hidden'  
                        var finalTermDisplay =  'hidden' 
                        var finalGradeDisplay =  'hidden' 

                        if(withPrelem){
                            term_count += 1
                            prelemDisplay = ''
                            $('.term_holder[data-term="1"]').removeAttr('hidden')
                        }

                        if(withMidterm){
                            term_count += 1
                            midTermDisplay = ''
                            $('.term_holder[data-term="2"]').removeAttr('hidden')
                        }

                        if(withpreFinal){
                            term_count += 1
                            preFiTermDisplay = ''
                            $('.term_holder[data-term="3"]').removeAttr('hidden')
                        }

                        if(withFinalterm){
                            term_count += 1
                            finalTermDisplay = ''
                            $('.term_holder[data-term="4"]').removeAttr('hidden')
                        }

                        if(withFinalGrade){
                            finalGradeDisplay = ''
                            $('.term_holder[data-term="5"]').removeAttr('hidden')
                        }
                       
                        var subjgrades = data[0].grades

                        $('.college_grades').removeAttr('hidden')
                        $('#college_grade_table').empty()

                        var temp_setup =  data[0].setup

                        $.each(subjgrades,function (a,b){

                            b.prelemgrade = b.prelemgrade != null && withPrelem ? b.prelemgrade : ''
                            b.midtermgrade = b.midtermgrade != null && withMidterm ?   b.midtermgrade : ''
                            b.prefigrade = b.prefigrade != null && withpreFinal ?  b.prefigrade : ''
                            b.finalgrade = b.finalgrade != null && withFinalterm ? b.finalgrade : ''
                            b.fg = b.fg != null && withFinalGrade ? b.fg : ''
                            b.fgremarks = b.fgremarks != null && withFinalGrade ?  b.fgremarks : ''

                            var bgrow = b.fgremarks != '' && withFinalterm ?  b.fgremarks == 'PASSED' ? 'bg-success':'bg-danger' : ''
                        

                            $('#college_grade_table').append('<tr class="'+bgrow+'"><td>'+b.subjCode+'</td><td>'+b.subjDesc+'</td><td class="text-center align-middle term_holder" data-term="1" '+prelemDisplay+'  data-id="'+b.id+'">'+b.prelemgrade+'</td><td class="text-center align-middle term_holder" data-term="2"  '+midTermDisplay+'  data-id="'+b.id+'">'+b.midtermgrade+'</td><td class="text-center align-middle term_holder" data-term="3"  '+preFiTermDisplay+'  data-id="'+b.id+'">'+b.prefigrade+'</td><td class="text-center align-middle term_holder" data-term="4" '+finalTermDisplay+'  data-id="'+b.id+'">'+b.finalgrade+'</td><td class="term_holder text-center align-middle">'+b.fg+'</td><td  class="term_holder  text-center align-middle" data-term="remarks"  data-id="'+b.id+'">'+b.fgremarks+'</td></tr>') 



                        })

                       

                        // $.each(temp_setup,function(a,b){
                        //     if(b.termactive == 1){
                        //         $('.term_holder[data-term="'+b.term+'"]').removeAttr('hidden')
                        //     }
                        // })

                    }
                    else if(school == 'SPCT' && levelid == 2){

                        $('.nursery_grades').removeAttr('hidden')
                        $('.nursery_side').removeAttr('hidden')
                      

                        $('#nursery_setup').empty()
                        var next_group = false;
                        var group = ""

                        $.each(data[0].setup,function(a,b){

                              var padding = ""
                              var header = ""
                              var button = ""
                              var option = ""

                              if(b.value == 0){

                                var q1total = data[0].setup.filter(x=>x.group == group).reduce(function(sum, current) { return sum + parseInt(current.q1grade); }, 0);
                                var q2total = data[0].setup.filter(x=>x.group == group).reduce(function(sum, current) { return sum + parseInt(current.q2grade); }, 0);
                                var q3total = data[0].setup.filter(x=>x.group == group).reduce(function(sum, current) { return sum + parseInt(current.q3grade); }, 0);

                                q1total = q1total == 0 ? '':q1total
                                q2total = q2total == 0 ? '':q2total
                                q3total = q3total == 0 ? '':q3total

                                if(next_group){
                                    $('#nursery_setup').append('<tr><td class="align-middle text-right pr-4"><b>TOTAL</b></td><i class="fas fa-edit text-primary"></i></a></td><td class="align-middle text-center">'+q1total+'</td><td class="text-center">'+q2total+'</td><td class="text-center">'+q3total+'</td><tr>')
                                }

                              }

                              if(b.value == 0){
                                    next_group = true
                                    header = 'font-weight-bold'
                                    if(b.sort.length > 1){
                                          padding = (b.group.length*2)+"rem;"
                                    }
                                    $('#nursery_setup').append('<tr class="'+header+' "><td class="align-middle bg-secondary" style="padding-left:'+padding+'" colspan="4">'+b.description+'</td></tr>')

                              }else{

                                    group = b.group

                                    var q1grade = b.q1grade == 1 ? '<i class="fas fa-check"></i>': ''
                                    var q2grade = b.q2grade == 1 ? '<i class="fas fa-check"></i>': ''
                                    var q3grade = b.q3grade == 1 ? '<i class="fas fa-check"></i>': ''

                                    padding = (b.group.length*2)+"rem;"
                                    $('#nursery_setup').append('<tr class="'+header+' "><td class="align-middle" style="padding-left:'+padding+'">'+b.description+'</td><i class="fas fa-edit text-primary"></i></a></td><td class="align-middle text-center">'+q1grade+'</td><td class="text-center aling-middle">'+q2grade+'</td><td class="text-center align-middle">'+q3grade+'</td></tr><')

                              }

                        })

                        var q1total = data[0].setup.filter(x=>x.group == group).reduce(function(sum, current) { return sum + parseInt(current.q1grade); }, 0) ;
                        var q2total = data[0].setup.filter(x=>x.group == group).reduce(function(sum, current) { return sum + parseInt(current.q2grade); }, 0);
                        var q3total = data[0].setup.filter(x=>x.group == group).reduce(function(sum, current) { return sum + parseInt(current.q3grade); }, 0);

                        q1total = q1total == 0 ? '':q1total
                        q2total = q2total == 0 ? '':q2total
                        q3total = q3total == 0 ? '':q3total

                        $('#nursery_setup').append('<tr><td class="align-middle text-right pr-4"><b>TOTAL</b></td><i class="fas fa-edit text-primary"></i></a></td><td class="align-middle text-center">'+q1total+'</td><td class="text-center">'+q2total+'</td><td class="text-center">'+q3total+'</td><tr>')

                        $.each(data[0].sumsetup,function(a,b){
                            var group = b.group
                            var qtotal = data[0].setup.filter(x=>x.group == group).reduce(function(sum, current) { return sum + parseInt(current.q1grade); }, 0) == 0 ? '' : data[0].setup.filter(x=>x.group == group).reduce(function(sum, current) { return sum + parseInt(current.q1grade); }, 0);
                            $('#admin_1').append('<tr><td class="align-middle text-center pr-4">'+b.description+'</td><td class="align-middle text-center">'+qtotal+'</td><td class="text-center">'+b.q1grade+'</td><tr>')
                        })

                        $.each(data[0].agevaldate,function(a,b){
                            var temp_grade = b.q1grade != null ?  b.q1grade : '';
                            $('#admin_1').append('<tr><td class="align-middle text-center pr-4">'+b.description+'</td><td class="align-middle text-center" colspan="2">'+temp_grade+'</td><tr>')
                        })

                        $.each(data[0].sumsetup,function(a,b){
                            var group = b.group
                            var temp_grade = b.q2grade == 0 ? '' : b.q2grade
                            var qtotal = data[0].setup.filter(x=>x.group == group).reduce(function(sum, current) { return sum + parseInt(current.q2grade); }, 0)  == 0 ? '' : data[0].setup.filter(x=>x.group == group).reduce(function(sum, current) { return sum + parseInt(current.q2grade); }, 0);
                            $('#admin_2').append('<tr><td class="align-middle text-center pr-4">'+b.description+'</td><td class="align-middle text-center">'+qtotal+'</td><td class="text-center">'+temp_grade+'</td><tr>')
                        })
                        $.each(data[0].agevaldate,function(a,b){
                            var temp_grade = b.q2grade != null ?  b.q2grade : '';
                            $('#admin_2').append('<tr><td class="align-middle text-center pr-4">'+b.description+'</td><td class="align-middle text-center" colspan="2">'+temp_grade+'</td><tr>')
                        })

                        $.each(data[0].sumsetup,function(a,b){
                            var group = b.group
                            var temp_grade = b.q3grade == 0 ? '' : b.q3grade
                            var qtotal = data[0].setup.filter(x=>x.group == group).reduce(function(sum, current) { return sum + parseInt(current.q3grade); }, 0) == 0 ? '' : data[0].setup.filter(x=>x.group == group).reduce(function(sum, current) { return sum + parseInt(current.q3grade); }, 0);
                            $('#admin_3').append('<tr><td class="align-middle text-center pr-4">'+b.description+'</td><td class="align-middle text-center">'+qtotal+'</td><td class="text-center">'+temp_grade+'</td><tr>')
                        })
                        $.each(data[0].agevaldate,function(a,b){
                            var temp_grade = b.q3grade != null ?  b.q3grade : '';
                            $('#admin_3').append('<tr><td class="align-middle text-center pr-4">'+b.description+'</td><td class="align-middle text-center" colspan="2">'+temp_grade+'</td><tr>')
                        })

                        $.each(data[0].clsetup,function(a,b){
                            var q1grade = ''
                            var q2grade = ''
                            var q3grade = ''

                            if(b.q1grade == 'AO'){
                                q1grade = '<i class="fas fa-star"></i>'
                            }else if(b.q1grade == 'SO'){
                                q1grade = '<i class="fas fa-heart"></i>'
                            }else if(b.q1grade == 'RO'){
                                q1grade = '<i class="fas fa-minus"></i>'
                            }

                            if(b.q2grade == 'AO'){
                                q2grade = '<i class="fas fa-star"></i>'
                            }else if(b.q2grade == 'SO'){
                                q2grade = '<i class="fas fa-heart"></i>'
                            }else if(b.q2grade == 'RO'){
                                q2grade = '<i class="fas fa-minus"></i>'
                            }

                            if(b.q3grade == 'AO'){
                                q3grade = '<i class="fas fa-star"></i>'
                            }else if(b.q3grade == 'SO'){
                                q3grade = '<i class="fas fa-heart"></i>'
                            }else if(b.q3grade == 'RO'){
                                q3grade = '<i class="fas fa-minus"></i>'
                            }

                            $('#cl_educ').append('<tr><td class="align-middle">'+b.description+'</td><td class="align-middle text-center">'+q1grade+'</td><td class="text-center align-middle">'+q2grade+'</td><td class="text-center  align-middle">'+q3grade+'</td><tr>')
                        })

                    }  
                    else if(school == 'SPCT' && levelid == 3){
                       
                        $('.nursery_grades').removeAttr('hidden')
                        $('.kinder_side').removeAttr('hidden')
                        
                        all_setup = data
                        $('#nursery_setup').empty()
                        $.each(data[0].setup,function(a,b){
                                var padding = ""
                                var header = ""
                                if(b.value == 0){
                                    header = 'font-weight-bold'
                                    if(b.sort.length > 1){
                                            padding = (b.group.length*2)+"rem;"
                                    }
                                    $('#nursery_setup').append('<tr class="'+header+' "><td class="align-middle" style="padding-left:'+padding+'">'+b.description+'</td><i class="fas fa-edit text-primary"></i></a></td><td class="align-middle text-center"></td><td class="text-center"></td><td class="text-center"></td><td class="text-center"></td></tr><')
                                }else{
                                    padding = (b.group.length*2)+"rem;"
                                    $('#nursery_setup').append('<tr class="'+header+' "><td class="align-middle" style="padding-left:'+padding+'">'+b.description+'</td><td class="align-middle text-center">'+b.q1grade+'</td><td class="text-center align-middle">'+b.q2grade+'</td><td class="text-center align-middle">'+b.q3grade+'</td><td class="text-center align-middle">'+b.q4grade+'</td></tr><')
                                }
                        })

                        $.each(data[0].remarks_setup,function(a,b){
                            $('#kinder_comments').append('<tr class="bg-secondary"><th>First Quarter (Weeks 1-10)</th></tr>')
                            $('#kinder_comments').append('<tr><td><i>'+b.q1grade+'</i></td></tr>')
                            $('#kinder_comments').append('<tr class="bg-secondary"><th>Second Quarter (Weeks 11-20)</th></tr>')
                            $('#kinder_comments').append('<tr><td><i>'+b.q2grade+'</i></td></tr>')
                            $('#kinder_comments').append('<tr class="bg-secondary"><th>Second Quarter (Weeks 11-20)</th></tr>')
                            $('#kinder_comments').append('<tr><td><i>'+b.q3grade+'</i></td></tr>')
                            $('#kinder_comments').append('<tr class="bg-secondary"><th>Fourth Quarter (Weeks 31-40)</th></tr>')
                            $('#kinder_comments').append('<tr><td><i>'+b.q4grade+'</i></td></tr>')
                        })
                        
                    }
                    else if( ( school == 'BCT' && levelid == 2 ) || ( school == 'BCT' && levelid == 3 ) || ( school == 'BCT' && levelid == 4 )){
                        $('.nursery_grades').removeAttr('hidden')
                        $('#q4_kinder').removeAttr('hidden')
                        
                        all_setup = data
                        $('#nursery_setup').empty()
                        $.each(data[0].setup,function(a,b){
                                var padding = ""
                                var header = ""
                                if(b.value == 0){
                                    header = 'font-weight-bold'
                                    if(b.sort.length > 1){
                                            padding = (b.group.length*2)+"rem;"
                                    }
                                    $('#nursery_setup').append('<tr class="'+header+' "><td class="align-middle" style="padding-left:'+padding+'">'+b.description+'</td><i class="fas fa-edit text-primary"></i></a></td><td class="align-middle text-center"></td><td class="text-center"></td><td class="text-center"></td><td class="text-center"></td></tr><')
                                }else{
                                    padding = (b.group.length*2)+"rem;"
                                    $('#nursery_setup').append('<tr class="'+header+' "><td class="align-middle" style="padding-left:'+padding+'">'+b.description+'</td><td class="align-middle text-center">'+b.q1grade+'</td><td class="text-center align-middle">'+b.q2grade+'</td><td class="text-center align-middle">'+b.q3grade+'</td><td class="text-center align-middle">'+b.q4grade+'</td></tr><')
                                }
                        })

                       

                    }
                    else{

                        $('.not_sh_grades').removeAttr('hidden')
                        $('.basic_ed').removeAttr('hidden')

                        var subjgrades = data[0].grades.filter(x=>x.id != 'G1')

                        $.each(subjgrades,function (a,b){
                            var padding = b.subjCom != null ? 'pl-5':''
                            var quarter1_grade = b.q1 != null ? b.q1 : ''
                            var quarter2_grade = b.q2 != null ? b.q2 : ''
                            var quarter3_grade = b.q3 != null ? b.q3 : ''
                            var quarter4_grade = b.q4 != null ? b.q4 : ''
                            var finalrating = b.finalrating != null ? b.finalrating : ''
                            var actiontaken = b.actiontaken != null ? b.actiontaken : ''
                            $('#enrollment_history_grade').append('<tr><td class="p-1 '+padding+'" >'+b.subjdesc+'</td><td class="text-center align-middle">'+quarter1_grade+'</td><td class="text-center align-middle">'+quarter2_grade+'</td></td><td class="text-center align-middle">'+quarter3_grade+'</td><td class="text-center align-middle">'+quarter4_grade+'</td><td class="text-center align-middle">'+finalrating+'</td><td class="text-center align-middle p-1">'+actiontaken+'</td></tr>') 
                        })
                        
                        var finalgrade = data[0].grades.filter(x=>x.id == 'G1')
                        var colspan = 5;
                        $.each(finalgrade,function (a,b){
                            var finalrating = b.finalrating != null ? b.finalrating : ''
                            var actiontaken = b.actiontaken != null ? b.actiontaken : ''
                            $('#enrollment_history_grade').append('<tr><td  colspan="'+colspan+'" class="text-right p-1">'+b.subjdesc+'</td><td class="text-center align-middle">'+finalrating+'</td><td class="text-center align-middle p-1">'+actiontaken+'</td></tr>') 
                        })

                    }

                    
                }
            })
        }

        
       
    })
</script>

@endsection
