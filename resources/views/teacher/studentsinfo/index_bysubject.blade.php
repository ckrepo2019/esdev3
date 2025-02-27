
@extends('teacher.layouts.app')

@section('content')
<div>
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="active breadcrumb-item">Students Information</li>
            <li class="active breadcrumb-item" aria-current="page">By Subject</li>
        </ol>
    </nav>
</div>
<div class="card" style="border: none;">
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-md-3">
                <label>Select S.Y.</label>
                <select class="form-control" id="select-syid">
                    @foreach(collect(DB::table('sy')->get())->sortByDesc('sydesc')->values() as $eachsy)
                        <option value="{{$eachsy->id}}" @if($eachsy->isactive == 1) selected @endif>{{$eachsy->sydesc}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Select Semester</label>
                <select class="form-control" id="select-semid">
                    @foreach(DB::table('semester')->get() as $eachsemester)
                        <option value="{{$eachsemester->id}}" @if($eachsemester->isactive == 1) selected @endif>{{$eachsemester->semester}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3" style="vertical-align: bottom;">
                <label>Select Grade Level</label>
                <select class="form-control" id="select-levelid">
                </select>
            </div>
            <div class="col-md-3" style="vertical-align: bottom;">
                {{-- <small style="font-size: 11px;"><br/>(<strong>Semester filter</strong> is for SHS Advisers only)</small> --}}
                <label>Select Section</label>
                <select class="form-control" id="select-sectionid">
                </select>
            </div>        
        </div>
        <div class="row">
            <div class="col-md-6">
                <label>Select Subject</label>
                <select class="form-control" id="select-subjectid">
                </select>
            </div>
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mhssi')
                <div class="col-md-3">
                    <label>Select Month</label>
                    <select id="selectmonth" class="form-control ">
                    </select>
                </div>
            @else
            <div class="col-md-3">
                <label>Select Month</label>
                <select id="selectmonth" class="form-control ">
                        @if(DB::table('monthsetup')->count()>0)
                            @foreach(DB::table('monthsetup')->get() as $eachmonth)
                                <option value="{{$eachmonth->id}}">{{$eachmonth->description}}</option>
                            @endforeach
                        @endif
                </select>
            </div>
            @endif
            <div class="col-md-3 text-right align-self-end">
                <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
            </div>
        </div>
    </div>
</div>
<div id="results-container"></div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
@endsection
@section('footerscripts')
<script>
    $(document).ready(function(){
                        $('#btn-generate').hide()
        function getsubjects(){
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            var levelid = $('#select-levelid').val();
            var sectionid = $('#select-sectionid').val();
            $.ajax({
                url: '/students/bysubjectgetsubjects',
                type: 'GET',
                dataType: 'json',
                data: {
                    syid                    : syid,
                    semid                    : semid,
                    gradelevelid                    : levelid,
                    sectionid               : sectionid
                },
                success:function(data){
                    $('#select-subjectid').empty();
                    if(data.length > 0)
                    {
                        $.each(data, function(key, value){
                            $('#select-subjectid').append(
                                '<option value="'+value.id+'">'+value.subjdesc+'</option>'
                            )
                        })
                        $('#btn-generate').show()
                    }else{
                        
                        $('#select-subjectid').append(
                                '<option value="0">No subjects assigned!</option>'
                            )
                        $('#btn-generate').hide()
                    }
                }
            })
        }
        function getsections(){
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            var levelid = $('#select-levelid').val();
            $.ajax({
                url: '/students/bysubjectgetsections',
                type: 'GET',
                dataType: 'json',
                data: {
                    syid                    : syid,
                    semid                    : semid,
                    gradelevelid                    : levelid
                },
                success:function(data){
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mhssi')
                        $.ajax({
                            url: '/students/bysubjectgetsections',
                            type:"GET",
                            dataType:"json",
                            data:{
                                action: 'getquartersetup',
                                gradelevelid: levelid
                            },
                            success:function(data) {
                                $('#selectmonth').empty();
                                $.each(data, function(key, value){
                                    $('#selectmonth').append('<option value="'+ value.id +'">' + value.monthname + '</option>');
                                });
                            },
                        });
                    @endif
                    $('#select-sectionid').empty();
                    if(data.length > 0)
                    {
                        $.each(data, function(key, value){
                            $('#select-sectionid').append(
                                '<option value="'+value.id+'">'+value.sectionname+'</option>'
                            )
                        })
                        getsubjects();
                    }else{
                        
                        $('#select-sectionid').append(
                                '<option value="0">No sections assigned!</option>'
                            )
                    }
                }
            })
        }
        function getlevels(){
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            $.ajax({
                url: '/students/bysubject',
                type: 'GET',
                dataType: 'json',
                data: {
                    action                  : 'getlevels',
                    syid                    : syid,
                    semid                    : semid
                },
                success:function(data){
                    $('#select-levelid').empty();
                    if(data.length > 0)
                    {
                        $.each(data, function(key, value){
                            $('#select-levelid').append(
                                '<option value="'+value.id+'">'+value.levelname+'</option>'
                            )
                        })
                        getsections();
                    }else{
                        
                        $('#select-levelid').append(
                                '<option value="0">No subject class assignment!</option>'
                            )
                    }
                }
            })
        }
        getlevels();
        $('#select-syid').on('change', function(){
            $('#results-container').empty()
            getlevels()
        })
        $('#select-semid').on('change', function(){
            getlevels()
            $('#results-container').empty()
        })
        $('#select-levelid').on('change', function(){
            $('#results-container').empty()
            getsections()
        })
        $('#select-sectionid').on('change', function(){
            getsubjects()
            $('#results-container').empty()
        })
        $('#select-subjectid').on('change', function(){
            $('#results-container').empty()
        })
        $('#btn-generate').on('click', function(){
            
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            var levelid = $('#select-levelid').val();
            var sectionid = $('#select-sectionid').val();
            var subjectid = $('#select-subjectid').val();
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })

            $.ajax({
                url: '/students/bysubjectgetstudents',
                type: 'GET',
                data: {
                    action                  : 'getstudents',
                    syid                    : syid,
                    semid                    : semid,
                    levelid                    : levelid,
                    sectionid                    : sectionid,
                    subjectid                    : subjectid,
                    setupid:                $('#selectmonth').val(),
                    semid                    : $('#select-semid').val(),
                },
                success:function(data){
                    $('#results-container').empty();
                    $('#results-container').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    
                }
            })
        })
        $(document).on('click', '#export-classlist', function(){
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            var levelid = $('#select-levelid').val();
            var sectionid = $('#select-sectionid').val();
            var subjectid = $('#select-subjectid').val();
            window.open('/students/bysubjectgetstudents?action=exportclasslist&exporttype=pdf&syid='+syid+'&semid='+semid+'&levelid='+levelid+'&sectionid='+sectionid+'&subjectid='+subjectid+'&setupid='+$('#selectmonth').val(),'_blank')
        })
    })
</script>
@endsection
