@php
      if(auth()->user()->type == 7){
            $extend = 'studentPortal.layouts.app2';
      }else if(auth()->user()->type == 9){
            $extend = 'parentsportal.layouts.app2';
      }
@endphp

@extends($extend)


@section('pagespecificscripts')
    <style>
        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            border: 0 !important;
        }
    </style>

@endsection


@section('content')


<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Class Schedule</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Class Schedule</li>
            </ol>
            </div>
        </div>
    </div>
</section>
<section class="content pt-0">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-filter"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">School Year</span>
                        <span class="info-box-number">
                            <select class="form-control form-control-sm" id="filter_sy" >
                                @php
                                    $sy = DB::table('sy')->orderBy('sydesc')->get();
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
                        </span>
                    </div>
                    <div class="info-box-content" hidden id="semester_holder">
                        <span class="info-box-text">Semester</span>
                        <span class="info-box-number">
                            <select class="form-control form-control-sm" id="filter_sem" >
                                {{-- <option value="" value="" selected>Select Semester</option> --}}
                                @php
                                    $sem = DB::table('semester')->where('deleted',0)->get();
                                @endphp
                                @foreach ($sem as $item)
                                    @php
                                        $selected = '';
                                        if($item->isactive == 1){
                                            $selected = 'selected="selected"';
                                        }
                                    @endphp
                                    <option value="{{$item->id}}" {{$selected}}  value="{{$item->id}}">{{$item->semester}}</option>
                            @endforeach
                            </select>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                  <div class="info-box-content">
                    <span class="info-box-text" >Grade Level - Section</span>
                    <span class="info-box-number" id="gradelevel_section" style="font-size:.9rem!important">
                    </span>
                  </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-5" id="strand_holder" hidden>
                <div class="info-box">
                  <div class="info-box-content">
                    <span class="info-box-text" >Strand</span>
                    <span class="info-box-number" id="strand_name">
                    </span>
                  </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header  bg-success">
                                <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Current</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-bordered  table-striped" style="font-size:.9rem">
                                    <thead>
                                        <tr>
                                            <th width="30%">Time</th>
                                            <th width="70%">Subject / Teacher</th>
                                        </tr>
                                    </thead>
                                    <tbody id="current_sched">
        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header  bg-primary">
                                <h3 class="card-title"><i class="fas fa-clipboard-list"></i> By Day</h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                          <li class="nav-item">
                                                <select class="form-control form-control-sm" name="" id="filter_day">
                                                      <option value="1">Monday</option>
                                                      <option value="2">Tuesday</option>
                                                      <option value="3">Wednesday</option>
                                                      <option value="4">Thursday</option>
                                                      <option value="5">Friday</option>
                                                      <option value="6">Saturday</option>
                                                </select>
                                          </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-bordered table-striped" style="font-size:.9rem">
                                    <thead>
                                        <tr>
                                            <th width="30%">Time</th>
                                            <th width="70%">Subject / Teacher</th>
                                        </tr>
                                    </thead>
                                    <tbody id="today_sched">
        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header  bg-secondary">
                        <h3 class="card-title"><i class="fas fa-clipboard-list"></i> All</h3>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-sm font-sm table-head-fixed table-bordered" width="100%"  style="font-size:.9rem; min-width:600px !important">
                            <thead>
                                  <tr>
                                        <th width="30%">Subject <span style="font-size:.7rem;" class="text-danger"><i id="total_unit" ></i></span></th>
                                        <th width="20%" class="text-center">Day</th>
                                        <th width="15%" class="text-center">Time</th>
                                        <th width="15%" class="text-center">Room</th>
                                        <th width="20%" class="text-center">Teacher</th>
                                  </tr>
                            </thead>
                            <tbody id="all_sched">
                                 
                            </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>



<script>
     $(document).ready(function(){

        var all_sched
        var levelid = null;
        var semid = $('#filter_sem').val()
        var studinfo = @json(Session::get('studentInfo'))


        get_gradelevel_section()

        $(document).on('change','#filter_sy',function(){
            $('#today_sched').empty()
            $('#all_sched').empty()
            $('#current_sched').empty()
            levelid = null;
            get_gradelevel_section()
        })

        $(document).on('change','#filter_sem',function(){
            $('#today_sched').empty()
            $('#all_sched').empty()
            $('#current_sched').empty()
            if(levelid == 14 || levelid == 15){
                semid = $('#filter_sem').val()
                get_gradelevel_section()
            }
            else if(levelid >= 17){
                semid = $('#filter_sem').val()
                get_gradelevel_section()
            }
            else{
                $('#semester_holder').attr('hidden','hidden')
                semid = null
                get_all_schedule()
                get_current_schedule()
            }
            
        })


        

        function get_gradelevel_section(){
            $.ajax({
                type:'GET',
                url: '/student/enrollment/record/gradelevelsection',
                data:{
                    syid:$('#filter_sy').val(),
                    semid:semid
                },
                success:function(data) {
                    $('#strand_holder').attr('hidden','hidden')
                    $('#strand_name').text('')
                    $('#total_unit').text('')
                    if(data.length > 0){
                        $('#gradelevel_section')[0].innerHTML =data[0].courseabrv + ' ' + data[0].levelname +'  - '+ data[0].sectionname
                        levelid = data[0].levelid
                        if(levelid == 14 || levelid == 15){
                            $('#strand_holder').removeAttr('hidden')
                            $('#strand_name')[0].innerHTML = data[0].strandname
                            $('#semester_holder').removeAttr('hidden')
                            semid = $('#filter_sem').val()
                        }
                        else if(levelid >= 17){
                            $('#semester_holder').removeAttr('hidden')
                            semid = $('#filter_sem').val()
                        }
                        else{
                            $('#semester_holder').attr('hidden','hidden')
                            semid = null
                        }
                    }else{

                        levelid = studinfo.levelid
                        if(studinfo.levelid == 14 || studinfo.levelid == 15){
                            $('#semester_holder').removeAttr('hidden')
                        }
                        else if(studinfo.levelid >= 17){
                            $('#semester_holder').removeAttr('hidden')
                            $('#gradelevel_section').text('No record found.')
                        }
                        else{
                            $('#semester_holder').attr('hidden','hidden')
                            $('#gradelevel_section').text('No record found.')
                        }
                       
                    }
                    get_all_schedule()
                    get_sched_data()
                    get_current_schedule()
                }
            })
        }

        function get_sched_data(){

            $.ajax({
                type:'GET',
                url: '/student/enrollment/record/classschedule/list',
                data:{
                    syid:$('#filter_sy').val(),
                    semid:semid,
                    type:'today'
                },
                success:function(data) {
                    all_sched = data
                    byday_sched()
                }
            })
        }

        var d = new Date();
        var current_day = d.getDay()
        $('#filter_day').val(current_day).change()

        function get_all_schedule(){
            $.ajax({
                type:'GET',
                url: '/student/enrollment/record/classschedule/list',
                data:{
                    syid:$('#filter_sy').val(),
                    semid:semid,
                    type:'all'
                },
                success:function(data) {
                    $('#all_sched').append(data)
                   
                }
            })
        }

        function get_current_schedule(){
            $.ajax({
                type:'GET',
                url: '/student/enrollment/record/classschedule/list',
                data:{
                    syid:$('#filter_sy').val(),
                    semid:semid,
                    type:'current'
                },
                success:function(data) {
                    if(data.length > 0){
                        if(data.filter(x=>x.sched == 'current').length > 0){
                            $.each(data.filter(x=>x.sched == 'current'),function (a,b){
                                $('#current_sched').append('<tr><td class="align-middle">'+b.start+'<br>'+b.end+'</td><td><a class="mb-0" style="font-size:.8rem">'+b.subject+' <p class="text-muted mb-0" style="font-size:.7rem">'+b.teacher+'</p></td><tr>')
                            })
                        }else{
                            $('#current_sched').append('<tr><td colspan="2">No available schedule for this hour.</td><tr>')
                        }
                    }else{
                        $('#current_sched').append('<tr><td colspan="2"><i>No available class for this hour.</i></td><tr>')
                    }
                }
            })
        }

        function byday_sched(){

            $('#today_sched').empty()
            
            var today = $('#filter_day').val()
            var day_sched = []
            var temp_sched = all_sched
            console.log(temp_sched,'wewew')
            $.each(temp_sched,function(a,b){
                if (b.days.includes(parseInt(today))) {
                        day_sched.push({
                            start: b.start,
                            end: b.end,
                            subjdesc: b.subjdesc,
                            teacher: b.teacher
                        });
                    }

            })
            
            day_sched.sort(function(a, b){
                return ((a.sort < b.sort) ? -1 : ((a.sort > b.sort) ? 1 : 0));
            });
           
            if(day_sched.length > 0){
                $.each(day_sched,function(c,d){
                    $('#today_sched').append('<tr><td class="align-middle">'+d.start+'<br>'+d.end+'</td><td><a class="mb-0" style="font-size:.8rem">'+d.subjdesc+' <p class="text-muted mb-0" style="font-size:.7rem">'+d.teacher+'</p></td></tr>')
                })
            }else{
                $('#today_sched').append('<tr><td colspan="2"><i>No available class for today.</i></td></tr>')
            }
        }

        $(document).on('change','#filter_day',function(){
            byday_sched()
        })

        
       
    })
</script>

@endsection
