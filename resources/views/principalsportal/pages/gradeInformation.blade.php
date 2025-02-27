@php
    $refid = DB::table('usertype')->where('id',auth()->user()->type)->where('deleted',0)->select('refid')->first();
@endphp

@if(auth()->user()->type == 2)

    @php
        $xtend = 'principalsportal.layouts.app2';
    @endphp

@else
    
    @if( $refid->refid == 20)
        @php
            $xtend = 'principalassistant.layouts.app2';
        @endphp
    @elseif( $refid->refid == 22)
        @php
            $xtend = 'principalcoor.layouts.app2';
        @endphp
    @endif

@endif

@extends($xtend)


@section('pagespecificscripts')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css')}}">

    <style>
        .grade{
            text-align: center;
        }
        .highest{

            text-align: center !important;
            vertical-align: middle !important;
        }
    </style>

@endsection

@section('content')

{{-- @if($gradeInfo->syid == App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->id) --}}
    <section class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item"><a href="/principalPortalSchedule">Sections</a></li>
                <li class="breadcrumb-item"><a href="/principalPortalSectionProfile/{{Crypt::encrypt($gradeInfo->sectionid)}}">{{Str::limit(strtoupper($gradeInfo->levelname).' - '.strtoupper($gradeInfo->sectionname), $limit = 12, $end = '...')}} </a></li>
                <li class="breadcrumb-item"><a href="/principalPortalTeacherProfile/{{$gradeLogs[count($gradeLogs)-1]->tid}}"> {{ Str::limit($gradeLogs[count($gradeLogs)-1]->firstname.' , '.$gradeLogs[count($gradeLogs)-1]->lastname, $limit = 12, $end = '...')}}</a></li>
            </ol>
            </div>
        </div>
        </div>
    </section>

    <section class="content" >

        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                    <h3 class="card-title">Grade Information</h3>
                    <div class="card-tools">
                        @if(App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->id == Session::get('schoolYear')->id)

                                @if($gradeInfo->status == 0 || $gradeInfo->status == 1)

                                    <a class="btn btn-sm btn-success approvegrades" href="#"  role="button">
                                        <img src="{{asset('/gif/loading6.gif')}}" style="height:18px !important; margin-right:2px" class="loadimg1" hidden>
                                        Approve Grades
                                    </a>
                                    <a class="btn btn-sm btn-warning addtopending"  href="#" role="button">Add to Pending</a>
                                    <a class="btn btn-sm bg-info pull-right text-white postgrade disabled" role="button" href="#">Post Grades</a>
                                 
                                @elseif( $gradeInfo->status == 3 )

                                    <span class="badge bg-warning p-2"><p class="m-0">Added to pending</p></span>
                                    <a class="btn btn-sm bg-info pull-right text-white disabled" href="/principalPortalPostGrade/{{$gradeInfo->id}}/{{$gradeLogs[count($gradeLogs)-1]->userid}}" role="button">Post Grades</a>

                                @elseif($gradeInfo->status == 2)

                                    <span class="btn btn-sm bg-primary pull-right text-white approved" >Grades Approved</span>
                                    <a class="btn btn-sm bg-info pull-right text-white postgrade" href="#" role="button">Post Grades</a>

                                @elseif($gradeInfo->status == 4)
                                    <span class="badge bg-info p-2"><p class="m-0">Grades Posted</p></span>
                                @endif
                        @endif
                    </div>
                    </div>
                    <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped" style="font-size:12px">
                        <thead>
                                <tr>
                                    <th>LEARNERS NAME</th>
                                    <td class="text-center bg-dark text-white align-middle" colspan="13">WRITTEN WORKS {{$setup[0]->writtenworks}} %</td>
                                    <td class="text-center bg-dark text-white align-middle" colspan="13">PERFORMANCE TASK {{$setup[0]->performancetask}} %</td>
                                    <td class="text-center bg-dark text-white align-middle" colspan="3">QA {{$setup[0]->qassesment}} %</td>
                                    <td class="text-center bg-dark text-white align-middle" >IG</td>
                                    <td class="text-center bg-dark text-white align-middle" >QG</td>
                                </tr>
                                <tr>
                                    <th>&nbsp;</th>
                                        @for($x=0; $x<10 ;$x++)
                                            <td class="text-center bg-light">{{$x}}</td>
                                        @endfor
                                    <td class="text-center bg-light">T</td>
                                    <td  class="text-center bg-light">PS</td>
                                    <td class="text-center bg-light">WS</td>
                                        @for($x=0; $x<10 ;$x++)
                                            <td class="text-center bg-light">{{$x}}</td>
                                        @endfor
                                    <td class="text-center bg-light">T</td>
                                    <td class="text-center bg-light">PS</td>
                                    <td class="text-center bg-light"">WS</td>
                                    <td class="text-center bg-light">1</td>
                                    <td class="text-center bg-light">PS</td>
                                    <td class="text-center bg-light">WS</td>
                                    <td class="text-center bg-light">IG</td>
                                    <td class="text-center bg-light">QG</td>
                                </tr>
                                @php
                                    $wwT=$submittedGrades[0]->wwhr1+$submittedGrades[0]->wwhr2+$submittedGrades[0]->wwhr3+$submittedGrades[0]->wwhr4+$submittedGrades[0]->wwhr5+$submittedGrades[0]->wwhr6+$submittedGrades[0]->wwhr7+$submittedGrades[0]->wwhr8+$submittedGrades[0]->wwhr9+$submittedGrades[0]->wwhr0;
                                    $ptT=$submittedGrades[0]->pthr0+$submittedGrades[0]->pthr1+$submittedGrades[0]->pthr2+$submittedGrades[0]->pthr3+$submittedGrades[0]->pthr4+$submittedGrades[0]->pthr5+$submittedGrades[0]->pthr6+$submittedGrades[0]->pthr7+$submittedGrades[0]->pthr8+$submittedGrades[0]->pthr9;

                                @endphp
                                <tr class="align-middle">
                                    <th  style="font-size:11px">{{strtoupper('Highest Possible Score')}}</th>
                                    <td class="highest">{{$submittedGrades[0]->wwhr0}}</td>
                                    <td class="highest">{{$submittedGrades[0]->wwhr1}}</td>
                                    <td class="highest">{{$submittedGrades[0]->wwhr2}}</td>
                                    <td class="highest">{{$submittedGrades[0]->wwhr3}}</td>
                                    <td class="highest">{{$submittedGrades[0]->wwhr4}}</td>
                                    <td class="highest">{{$submittedGrades[0]->wwhr5}}</td>
                                    <td class="highest">{{$submittedGrades[0]->wwhr6}}</td>
                                    <td class="highest">{{$submittedGrades[0]->wwhr7}}</td>
                                    <td class="highest">{{$submittedGrades[0]->wwhr8}}</td>
                                    <td class="highest">{{$submittedGrades[0]->wwhr9}}</td>
                                    

                                    <td class="highest">{{$wwT}}</td>
                                    <td class="highest">100</td>
                                    <td class="highest">{{$setup[0]->writtenworks}}</td>
                                    <td class="highest">{{$submittedGrades[0]->pthr0}}</td>
                                    <td class="highest">{{$submittedGrades[0]->pthr1}}</td>
                                    <td class="highest">{{$submittedGrades[0]->pthr2}}</td>
                                    <td class="highest">{{$submittedGrades[0]->pthr3}}</td>
                                    <td class="highest">{{$submittedGrades[0]->pthr4}}</td>
                                    <td class="highest">{{$submittedGrades[0]->pthr5}}</td>
                                    <td class="highest">{{$submittedGrades[0]->pthr6}}</td>
                                    <td class="highest">{{$submittedGrades[0]->pthr7}}</td>
                                    <td class="highest">{{$submittedGrades[0]->pthr8}}</td>
                                    <td class="highest">{{$submittedGrades[0]->pthr9}}</td>
                                  
                                    <td class="highest">{{$ptT}}</td>
                                    <td class="highest">100</td>
                                    <td class="highest"">{{$setup[0]->performancetask}}</td>
                                    <td class="highest">{{$submittedGrades[0]->qahr1}}</td>
                                    <td class="highest">100</td>
                                    <td class="highest">{{$setup[0]->qassesment}}</td>
                                    <td class="highest">100</td>
                                    <td class="highest">100</td>
                                </tr>
                        </thead>
                        <tbody>
                            @php
                            $passingPercentage = .7;
                            @endphp
                            @foreach ($submittedGrades as $grade)
                                <tr>
                                    <th  class = "pr-0" style="font-size:12px ">{{Str::limit(strtoupper($grade->studname), $limit = 12, $end = '...')}}</th>
                                    <td class="grade">{{$grade->ww0}}</td>
                                    <td class="grade">{{$grade->ww1}}</td>
                                    <td class="grade">{{$grade->ww2}}</td>
                                    <td class="grade">{{$grade->ww3}}</td>
                                    <td class="grade">{{$grade->ww4}}</td>
                                    <td class="grade">{{$grade->ww5}}</td>
                                    <td class="grade">{{$grade->ww6}}</td>
                                    <td class="grade">{{$grade->ww7}}</td>
                                    <td class="grade">{{$grade->ww8}}</td>
                                    <td class="grade">{{$grade->ww9}}</td>

                                  
                                    <td class="grade">{{$grade->wwtotal}}</td>
                                    @php
                                        try{
                                            $wwTResult = number_format( ( $grade->wwtotal / $wwT ) * 100 ,2 );
                                        }catch (\Exception $e){
                                            $wwTResult = 0;
                                        }
                                    @endphp
                                    <td class="grade">{{$wwTResult}}</td>
                                    @php
                                        try{
                                            $wwwResult = number_format( ( ( $grade->wwtotal / $wwT ) * 100 ) * ( $setup[0]->writtenworks / 100 ) , 2);
                                        }catch (\Exception $e){
                                            $wwwResult = 0;
                                        }
                                    @endphp
                                    <td class="grade">{{$wwwResult}}</td>
                                    
                                    <td class="grade">{{$grade->pt0}}</td>
                                    <td class="grade">{{$grade->pt1}}</td>
                                    <td class="grade">{{$grade->pt2}}</td>
                                    <td class="grade">{{$grade->pt3}}</td>
                                    <td class="grade">{{$grade->pt4}}</td>
                                    <td class="grade">{{$grade->pt5}}</td>
                                    <td class="grade">{{$grade->pt6}}</td>
                                    <td class="grade">{{$grade->pt7}}</td>
                                    <td class="grade">{{$grade->pt8}}</td>
                                    <td class="grade">{{$grade->pt9}}</td>
                                  
                                    <td class="grade">{{$grade->pttotal}}</td>

                                    @php
                                        try{
                                            $ptTResult = number_format( ( $grade->pttotal / $ptT ) * 100 , 2);
                                        }catch (\Exception $e){
                                            $ptTResult = 0;
                                        }
                                    @endphp
                                    <td class="grade">{{$ptTResult}}</td>
                                    @php
                                        try{
                                            $ptResult = number_format( ( ( $grade->pttotal / $ptT ) * 100 ) * ( $setup[0]->performancetask / 100 ) , 2) ;
                                        }catch (\Exception $e){
                                            $ptResult = 0;
                                        }
                                    @endphp

                                    <td class="grade">{{ $ptResult }}</td>
                                    <td class="grade">{{$grade->qa1}}</td>

                                    @php
                                        try{
                                            $qa1Result =  number_format( ( $grade->qa1 / $submittedGrades[0]->qahr1 ) * 100 , 2);
                                        }catch (\Exception $e){
                                            $qa1Result = 0;
                                        }
                                    @endphp
                                    <td class="grade">{{$qa1Result}}</td>
                                    @php
                                        try{
                                            $qatotalResult = number_format( ( ( $grade->qa1 / $submittedGrades[0]->qahr1 ) * 100 ) * ( $setup[0]->qassesment / 100 ) , 2 ) ;
                                        }catch (\Exception $e){
                                            $qatotalResult = 0;
                                        }
                                    @endphp

                                    <td class="grade">{{ $qatotalResult }}</td>
                                    <td class="grade">{{$grade->ig}}</td>
                                    <td class="grade qg" aa="{{$grade->qg}}" bb="{{$grade->studid}}">{{$grade->qg}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <div class="timeline">
                        @foreach($gradeLogs as $item)
                            <div class="time-label">
                                @if($item->action == 1)
                                    <span class="bg-success">{{\Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY hh:ss a')}}</span>
                                @elseif($item->action == 2)
                                    <span class="bg-primary">{{\Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY hh:ss a')}}</span>
                                @elseif($item->action == 3)
                                    <span class="bg-warning">{{\Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY')}}</span>
                                @elseif($item->action == 4)
                                    <span class="bg-info">{{\Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY hh:ss a')}}</span>
                                @endif
                                
                            </div>
                            <div>
                            
                                <i class="fas fa-user bg-white"></i>
                            
                                <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> 
                                    {{date("M d, yy h:i:s a", strtotime($item->createddatetime))}}
                                    {{-- {{Carbon::create($item->createddatetime)}} --}}

                                </span>
                        
                                <h3 class="timeline-header no-border"><a href="/principalPortalTeacherProfile/{{$item->tid}}">{{$item->firstname}} {{$item->lastname}}</a>  
                                    @if($item->action == 1)
                                        submitted Grades
                                    @elseif($item->action == 2)
                                        approved Grades
                                    @elseif($item->action == 3)
                                        added Grades to pending
                                    @elseif($item->action == 4)
                                        posted Grades
                                    @endif
                                </h3>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-primary">
                    <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i>Grade Information</h3>
                    <br>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-layer-group mr-1"></i>Grade Level</strong>
                        <p class="text-muted small">
                            {{strtoupper($gradeInfo->levelname)}}
                        </p>
                        <hr>
                        <strong><i class="fas fa-signature mr-1"></i>Section Name</strong>
                        <p class="text-muted small">
                            {{strtoupper($gradeInfo->sectionname)}}
                        </p>
                        <hr>
                        <strong><i class="fas fa-user mr-1"></i>Adviser</strong>
                        <p class="text-muted small">
                            @if(isset($gradeInfo->firstname))
                                <a href="/principalPortalTeacherProfile/{{$gradeInfo->tid}}">{{strtoupper($gradeInfo->firstname)}} {{strtoupper($gradeInfo->lastname)}}</a>
                            @else
                                No Teacher Assigned
                            @endif
                        
                        </p>
                        <hr>
                        <strong><i class="fas fa-sticky-note mr-1"></i>Subject</strong>
                        <p class="text-muted small">
                            {{strtoupper($gradeInfo->subjdesc)}}
                        </p>
                        <hr>
                        <strong><i class="fas fa-user mr-1"></i>Subject Teacher</strong>
                        <p class="text-muted small">
                            <a href="#">{{$gradeLogs[count($gradeLogs)-1]->firstname}} {{$gradeLogs[count($gradeLogs)-1]->lastname}}</a>
                        </p>
                        <hr>
                        <strong><i class="fas fa-chart-pie mr-1"></i>Quarter</strong>
                        <p class="text-muted small">
                            {{strtoupper($gradeInfo->quarter)}}
                        </p>
                        <hr>
                        <strong><i class="fas fa-chart-pie mr-1"></i>Status</strong>
                        <p class="text-muted small">
                            @if($gradeInfo->status == 0 || $gradeInfo->status == 1)
                                Submitted
                            @elseif($gradeInfo->status == 2)
                                Approved
                            @elseif($gradeInfo->status == 4)
                                Posted
                            @elseif($gradeInfo->status == 3)
                                Pending
                            @endif
                        
                        </p>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @endsection

    @section('footerjavascript')
    <!-- DataTables -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-fixedcolumns/js/fixedColumns.bootstrap4.js')}}"></script>


    <script>

    

        $(document).ready(function() {

            var a = '{{$gradeInfo->status}}'
            var b = '{{$gradeInfo->status}}'

            if($(window).width()<1024){

                $('.btn').addClass('btn-xs');
                $('.btn').removeClass('btn-sm');
                $('.card-title').removeClass('card-title');
                $('.card-title').addClass('d-flex justify-content-center');
                $('.card-tools').removeClass('card-tools');
                $('.card-tools').addClass('d-flex justify-content-center');

                

            }

            var table = $('#example1').DataTable( {
                scrollY:        "200px",
                scrollX:        true,
                scrollCollapse: true,
                ordering: false,
                paging:         false,
                info:     false,
                fixedColumns:   {
                    leftColumns: 1,
                    rightColumns: 1
                },
                columnDefs: [
                    { width: 100, targets: 0 }
                ],
                fixedColumns: true,
                searching: false
            
            } );

            $('.content').css('visibility','visible')
            
            $('.grade').each(function(){


                if($('.highest')[($(this)[0].cellIndex)-1].innerHTML == 0){


                }

                else if( $(this).text() >= $('.highest')[($(this)[0].cellIndex)-1].innerHTML * '{{$passingPercentage}}'){

                    $(this).addClass('bg-success-light')
                    $(this).css('color','white')
                    
                }

                else if($(this).text() < $('.highest')[($(this)[0].cellIndex)-1].innerHTML * '{{$passingPercentage}}' && $(this).text() != 0){

                    $(this).addClass('bg-danger-light')
                    $(this).css('color','white')

                }
            })

            $(document).on('click','.approvegrades',function(){
                    Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Approve grades!'
                }).then((result) => {
                    if (result.value) {
                        if(a == 0 || b == 1){

                            $( document ).ajaxStart(function() {
                                $('.loadimg1').removeAttr('hidden')
                            });

                            $.ajax({
                                type:'GET',
                                url:'/principalPortalApproveGrade/'+'{{$gradeInfo->id}}'+'/'+'{{$gradeLogs[count($gradeLogs)-1]->userid}}',
                                success:function(data) { 
                                    Swal.fire({
                                        type: 'success',
                                        title: 'GRADES APPROVED!',
                                        // text: 'Grades has been approved!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                }

                            });

                            $( document ).ajaxComplete(function() {
                                    a = 2
                                    $('.loadimg1').attr('hidden','hidden')
                                    $('.approvegrades').remove();
                                    $('.postgrade').removeClass('disabled')
                                    $('.addtopending').removeClass('btn-warning')
                                    $('.addtopending').addClass('btn-primary')
                                    $('.addtopending').addClass('disabled')
                                    $('.addtopending').addClass('approved')
                                    $('.addtopending').text('Grades Approved')
                                    $('.addtopending').removeClass('addtopending')
                                });
                        }
                    }
                })
            });

            $(document).on('click','.addtopending',function(){
                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Add grades to pending!'
                }).then((result) => {
                if (result.value) {
                    if( a == 0 || b == 1){
                        $.ajax({
                            type:'GET',
                            url:'/principalPortalPeddingGrade/'+'{{$gradeInfo->id}}'+'/'+'{{$gradeLogs[count($gradeLogs)-1]->userid}}',
                            success:function(data) { 
                                Swal.fire({
                                    type: 'success',
                                    title: 'GRADES ADDED TO PENDING!!',
                                    // text: 'GRADES ADDED TO PENDING!',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            }
                        });
                        $( document ).ajaxComplete(function() {
                            $('.approvegrades').remove();
                            $('.addtopending').text('Added to pending');
                            $('.addtopending').addClass('disabled');
                            $('.addtopending').removeClass('addtopending');
                        });
                    }                
                }
                })
            });

            $(document).on('click','.postgrade',function(){

                var qg = []

                $('.qg').each(function(){
                    $(this).text();
                    var info = {
                        'qg':$(this).attr('aa'),
                        'id':$(this).attr('bb')
                    }

                    qg.push(info)
                })

                console.log(qg)

                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Post grades!'
                }).then((result) => {
                if (result.value) {
                    if(a == 2){
                        $.ajax({
                            type:'GET',
                            url:'/principalPortalPostGrade/'+'{{$gradeInfo->id}}'+'/'+'{{$gradeLogs[count($gradeLogs)-1]->userid}}',
                            data: {'qg':qg},
                            success:function(data) { 
                                Swal.fire({
                                    type: 'success',
                                    title: 'GRADES POSTED!',
                                    // text: 'Grade has been posted!!',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            }
                        });

                        $( document ).ajaxComplete(function() {
                            $('.approved').remove()
                            $('.postgrade').addClass('disabled')
                            $('.postgrade').removeClass('postgrade')
                          
                        });
                    }                
                }
                })
            });


        });
    </script>

{{-- @else

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
               
            </div>
        </div>
    </section>
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-danger">500</h2>

            <div class="error-content">
                <h3>
                    <i class="fas fa-exclamation-triangle text-danger"></i> Oops! Something went wrong.</h3>
                <p>
                We will work on fixing that right away.
                Meanwhile, you may <a href="/home">return to dashboard</a> or try using the search form.
                </p>

                <form class="search-form">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search">

                        <div class="input-group-append">
                        <button type="submit" name="submit" class="btn btn-danger"><i class="fas fa-search"></i>
                        </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section> --}}



{{-- @endif --}}

@endsection

