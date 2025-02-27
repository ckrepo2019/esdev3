@extends('principalsportal.layouts.app2')


@section('pagespecificscripts')

    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">

    <style>

            #appadd {
                white-space: nowrap;
                overflow: hidden;
                width: 10px;
                height: 10px;
                text-overflow: ellipsis; 
            }
            td{
                padding: .4em !important;
            }
            th{
                padding: .4em !important;
            }
        
    </style>
    

@endsection

@section('modalSection')

@endsection

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fas fa-user-graduate nav-icon"></i> STUDENT PROMOTION</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Promotion</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col-md-2">
            <div class="callout callout-success text-danger">
                <h5 class="text-primary"><i class="fas fa-info"></i> Note:</h5>
                Students will not be promoted unless all grades are submitted.
            </div>
            <div class="card">
                <div class="card-header p-2 bg-info text-center d-flex justify-content-center"">
                    <h3 class="card-title">Legend</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <span class="nav-link">
                                <strong class="text-dark">S</strong>
                                <span class="float-right badge badge-dark mt-1">
                                    SUBJECTS
                                </span>
                            </span>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link">
                                <strong class="text-success">P</strong>
                                <span class="float-right badge badge-success mt-1">
                                    PASSED
                                </span>
                            </span>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link">
                                <strong class="text-danger">F</strong>
                                <span class="float-right badge badge-danger mt-1">
                                    FAILED
                                </span>
                            </span>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link">
                                <span class="badge badge-success w-100">
                                    PROMOTABLE
                                </span>
                            </span>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link">
                                <span class="badge badge-warning w-100">
                                    CONDITIONAL
                                </span>
                            </span>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link">
                                <span class="badge badge-danger w-100">
                                    RETAINABLE
                                </span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
           
        </div>
        <div class="col-md-8">
            <div class="card main-card principalsubject h-100">
                <div class="card-header bg-info">
                    <div class="form-group card-title acadid">
                        <select class="form-control form-control-sm" id="acadid">
                            <option selected value="Crypt::encrypt(0)" disabled>Select Academic Program</option>
                            @foreach (App\Models\Principal\SPP_AcademicProg::getPrincipalAcadProg(Session::get('prinInfo')->id) as $item)
                                @if($item->id == 2)
                                    {{$studcount = Session::get('psstudentcount')}}
                                @elseif($item->id == 3)
                                    {{$studcount = Session::get('gsstudentcount')}}
                                @elseif($item->id == 4)
                                    {{$studcount = Session::get('jhstudentcount')}}
                                @elseif($item->id == 5)
                                    {{$studcount = Session::get('shstudentcount')}}
                                @endif
                                <option class=" {{ $studcount > 0 ? 'text-success':'text-danger'}}" value="{{Crypt::encrypt($item->id)}}">{{$item->progname}} <p style="background-color:#FF0055;">( {{$studcount}} )</p></option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group input-group-sm float-right w-25 search">
                        <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    
                </div>
                <div class="card-body table-responsive pt-0 pb-0" id="promotions">
                    @include('search.principal.promotions')
                </div>
                <div class="card-footer p-2">
                    <div id="data-container"></div>
                </div>
            </div>
        </div>
        <div class="col-md-2" >
            <div class="card">
                <div class="card-header bg-gray p-1">
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                        <a href="#" class="nav-link">
                            Complete:
                            <span class="badge badge-success float-right mt-1 complete">0
                            </span>
                        </a>
                        </li>
                        <li class="nav-item">
                        <a href="#" class="nav-link">
                            Incomplete
                            <span class="badge badge-danger float-right mt-1 incomplete">0
                            
                            </span>
                        </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-gray p-1">
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                        <a href="#" class="nav-link">
                            
                            <span class="badge badge-success">Promotable :</span>
                            <span class="badge badge-success float-right mt-1 promotable">0
                            </span>
                        </a>
                        </li>
                        <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="badge badge-warning">Conditional :</span>
                            <span class="badge badge-warning float-right mt-1 conditionable">0
                            
                            </span>
                        </a>
                        </li>
                        <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="badge badge-danger">Retainable :</span>
                            <span class="badge badge-danger float-right mt-1 retainable">0
                            
                            </span>
                        </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-gray  p-1">
                
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="badge badge-success">Promoted :</span>
                            <span class="badge badge-success float-right mt-1 promoted">0
                            </span>
                        </a>
                        </li>
                        <li class="nav-item">
                        <a href="#" class="nav-link">

                            <span class="badge badge-warning">Conditional :</span>
                            <span class="badge badge-warning float-right mt-1 conditional">0
                            </span>
                        </a>
                        </li>
                        <li class="nav-item">
                        <a href="#" class="nav-link ">
                            <span class="badge badge-danger">Retained :</span>
                            <span class="badge badge-danger float-right mt-1 retained">0
                            </span>
                        </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-gray  p-1">
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <span class="badge badge-danger">Unpromoted :</span>
                                <span class="float-right text-danger unpromoted">0
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="butholder">
                <button hidden class="btn btn-primary" id="promoteallStudent">
                    PROMOTE ALL STUDENTS
                </button>
            </div>
           
          
           
        </div>
    </div>
</section>
@endsection


@section('footerjavascript')

<script src="{{asset('js/pagination.js')}}"></script>


<script>
    $(document).ready(function(){

        if($(window).width()<500){
            $('.search').addClass('w-100 mt-2')
            $('.acadid').addClass('w-100')
            $('.col-md-2, .col-md-8').addClass('mb-3')
            $('.search').removeClass('w-25')
        }

        var promote = false;
  
        pagination('{{$count}}',false);


        $(document).on('click','#promoteallStudent',function(){

            console.log(promote)
            if($('#acadid').val() == null){

                Swal.fire({
                    title: 'Please select academic program.',
                    type: 'info',
                })

            }
            else if(promote){

                Swal.fire({
                        title: 'Are you sure you want to promote students?',
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes Promote'
                })
                .then((result) => {
                        if (result.value) {
                            $.ajax({
                                    type:'GET',
                                    url:'/promoteallstudents/'+$("#acadid").val(),
                                    success:function(data) {

                                        if(data == 1){

                                            getStudentPromotion()
                                            
                                        }
                                        
                                    },
                            })
                        }
                })

            }
            else{

                Swal.fire({
                    title: 'Students are not qualified for promotion!',
                    type: 'error',
                })

            }

            

        })
         
        function pagination(itemCount,pagetype){
            var result = [];
            for (var i = 0; i < itemCount; i++) {
                result.push(i);
            }
            $('#data-container').pagination({
            dataSource: result,
            callback: function(data, pagination) {
                if(pagetype){
                $.ajax({
                    type:'GET',
                    url:'/searchstudentpromotion',
                    data:{
                        data:$("#search").val(),
                        pagenum:pagination.pageNumber,
                        apid:$('#acadid').val()
                    },
                    success:function(data) {
                    $('#promotions').empty();
                    $('#promotions').append(data);
                    }
                })
                }
                pagetype=true;
            },
                hideWhenLessThanOnePage: true,
                pageSize: 10,
                // pageRange: 1,
            })
        }

        $("#search" ).keyup(function() {
            $.ajax({
                type:'GET',
                url:'/searchstudentpromotion',
                data:{
                    data:$(this).val(),
                    pagenum:'1',
                    apid:$('#acadid').val()
                },
                success:function(data) {
                    $('#promotions').empty();
                    $('#promotions').append(data);
                    pagination($('#searchCount').val())
                }
            })
        });

        var acadValue;

        function getStudentPromotion(){

            $.ajax({
                type:'GET',
                url:'/searchstudentpromotion',
                data:{
                    data:$("#search").val(),
                    pagenum:'1',
                    apid:acadValue
                },
                success:function(data) {

                    $('#promotions').empty();
                    $('#promotions').append(data);
                    
                    pagination($('#searchCount').val())

                    @if(App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->id == Session::get('schoolYear')->id)

                    @endif

                    getSum();
                }
            })
            
        }

        $(document).on('change','#acadid',function() {

            acadValue = $(this).val()

            getStudentPromotion()

        });

        function getSum(){

            $.ajax({
                    type:'GET',
                    url:'/promSum',
                    data:{
                        apid:$('#acadid').val()
                    },
                    success:function(data) {
                        $('.retainable').text(data[0].ratainable)
                        $('.conditionable').text(data[0].conditionable)
                        $('.promotable').text(data[0].promotable)
                        $('.retained').text(data[0].retained)
                        $('.conditional').text(data[0].conditional)
                        $('.promoted').text(data[0].promoted)
                        $('.complete').text(data[0].complete)
                        $('.incomplete').text(data[0].incomplete)
                        $('.unpromoted').text(data[0].unpromoted)

                        if(data[0].unpromoted == 0 || data[0].incomplete != 0){
                            
                                $('#promoteallStudent').remove();

                        }else{

                         

                            if(data[0].promoted == 0 && data[0].promotable >= 0){

                                $('#promoteallStudent').removeAttr('hidden')

                                $('#promstud').removeAttr('hidden');

                                promote = true

                            }
                           
                            
                        }
                    }
                })
            }

        

    })
</script>


@endsection

