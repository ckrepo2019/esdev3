
@extends('teacher.layouts.app')

@section('content')
<style>
    .alert-primary {
    color: #004085;
    background-color: #cce5ff;
    border-color: #b8daff;
}
.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
</style>
<div>
    
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="active breadcrumb-item" aria-current="page">School Forms</li>
            @if($formtype == 'form1')
            <li class="active breadcrumb-item" aria-current="page">School Form 1</li>
            @elseif($formtype == 'form2')
            <li class="active breadcrumb-item" aria-current="page">School Form 2</li>
            @elseif($formtype == 'form5')
            <li class="active breadcrumb-item" aria-current="page">School Form 5</li>
            @elseif($formtype == 'form5a')
            <li class="active breadcrumb-item" aria-current="page">School Form 5A</li>
            @elseif($formtype == 'form5b')
            <li class="active breadcrumb-item" aria-current="page">School Form 5B</li>
            @elseif($formtype == 'form9')
            <li class="active breadcrumb-item" aria-current="page">School Form 9</li>
            @elseif($formtype == 'form10')
            <li class="active breadcrumb-item" aria-current="page">School Form 10</li>
            @endif
        </ol>
    </nav>
</div>
@if($formtype == 'form1' || $formtype == 'form2'  || $formtype == 'form9')
    <div class="card" style="box-shadow: none; border: none;">
        <div class="card-header">
            <div class="row">
                <div class="col-md-4">
                    <label>School Year</label>
                    <select class="form-control" id="selectedyear">
                        
                        @foreach(DB::table('sy')->orderByDesc('id')->get() as $sy)
                        <option value="{{$sy->id}}" @if($sy->isactive == 1)selected @endif>{{$sy->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div id="div-container">

    </div>
@elseif($formtype == 'form5')
    <div class="card" style="box-shadow: none; border: none;">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    <label>School Year</label>
                    <select class="form-control" id="selectedyear">
                        @foreach(DB::table('sy')->orderByDesc('id')->get() as $sy)
                            <option value="{{$sy->id}}" @if($sy->isactive == 1)selected @endif>{{$sy->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Semester</label>
                    <select class="form-control" id="selectsem">
                        <option value="0"></option>
                        @foreach(DB::table('semester')->get() as $semester)
                            <option value="{{$semester->id}}">{{$semester->semester}}</option>
                        @endforeach
                    </select>
                    <small><em>Note: For SHS filtering only</em></small>
                </div>
                <div class="col-md-3">
                    <label>Sections</label>
                    <select class="form-control" id="selectedsection">
                        
                    </select>
                </div>
                <div class="col-md-3 text-right">
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-primary" id="btn-generatesf5"><i class="fa fa-sync"></i> Generate SF5</button>
                </div>
            </div>
        </div>
    </div>
    <div id="div-container">

    </div>
@elseif($formtype == 'form5a')
    <div class="card" style="box-shadow: none; border: none;">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    <label>School Year</label>
                    <select class="form-control" id="selectedyear">
                        @foreach(DB::table('sy')->get() as $sy)
                            <option value="{{$sy->id}}" @if($sy->isactive == 1)selected @endif>{{$sy->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Semester</label>
                    <select class="form-control" id="selectsem">
                        @foreach(DB::table('semester')->get() as $semester)
                            <option value="{{$semester->id}}" @if($semester->isactive == 1)selected @endif>{{$semester->semester}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Sections</label>
                    <select class="form-control" id="selectedsection">
                        
                    </select>
                </div>
                <div class="col-md-3 mt-2">
                    <label>Strands</label>
                    <select class="form-control" id="selectedstrand">
                        
                    </select>
                </div>
                <div class="col-md-9 text-right mt-2">
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-primary" id="btn-generatesf5a"><i class="fa fa-sync"></i> Generate SF5A</button>
                </div>
            </div>
        </div>
    </div>
    <div id="div-container">

    </div>
@elseif($formtype == 'form5b')
    <div class="card" style="box-shadow: none; border: none;">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    <label>School Year</label>
                    <select class="form-control" id="selectedyear">
                        @foreach(DB::table('sy')->get() as $sy)
                            <option value="{{$sy->id}}" @if($sy->isactive == 1)selected @endif>{{$sy->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Semester</label>
                    <select class="form-control" id="selectsem">
                        @foreach(DB::table('semester')->get() as $semester)
                            <option value="{{$semester->id}}" @if($semester->isactive == 1)selected @endif>{{$semester->semester}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Sections</label>
                    <select class="form-control" id="selectedsection">
                        
                    </select>
                </div>
                <div class="col-md-3 mt-2">
                    <label>Strands</label>
                    <select class="form-control" id="selectedstrand">
                        
                    </select>
                </div>
                <div class="col-md-9 text-right mt-2">
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-primary" id="btn-generatesf5b"><i class="fa fa-sync"></i> Generate SF5B</button>
                </div>
            </div>
        </div>
    </div>
    <div id="div-container">

    </div>
@elseif($formtype == 'form10')
    @php    
        $students = array();
        if(count($sections)>0)
        {
            foreach($sections as $section)
            {
                if(count($section->students)>0)
                {
                    foreach($section->students as $sectionstudent)
                    {
                        array_push($students, $sectionstudent);
                    }
                }
            }
        }
        $students = collect($students)->unique('id');
        $students = collect($students)->sortBy('firstname')->sortBy('lastname')->values()->all();
    @endphp
    <div class="card" style="box-shadow: none; border: none;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label>Select a student</label>
                    
                    <select class="form-control  select2" id="select-studentid">
                        @foreach($students as $student)
                            <option value="{{$student->id}}">{{$student->lastname}}, {{ucwords($student->firstname)}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 text-right">
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-primary" id="btn-getrecords"><i class="fa fa-sync"></i> Generate</button>
                </div>
            </div>
        </div>
    </div>
    <div id="div-container">

    </div>
@else
    <div class="row">
        @if(count($sections) > 0)
            @foreach($sections as $section)
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <span style="font-size: 15px"><strong>{{$section->numberofstudents}}</strong></span>  Students

                            <p>{{$section->levelname}} - {{$section->sectionname}}</p>
                            <div class="row">
                                <div class="col-6">
                                    <small>Enrolled: {{$section->numberofenrolled}}</small><br/>
                                    <small>Late Enrolled: {{$section->numberoflateenrolled}}</small><br/>
                                    <small>Transferred In: {{$section->numberoftransferredin}}</small><br/>
                                </div>
                                <div class="col-6">
                                    <small>Transferred Out: {{$section->numberoftransferredout}}</small><br/>
                                    <small>Dropped Out: {{$section->numberofdroppedout}}</small><br/>
                                    <small>Withdrawn: {{$section->numberofwithdraw}}</small>
                                </div>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        @if($formtype == 'form1')
                            <form action="/forms/{{$formtype}}" method="get" class="small-box-footer" target="_blank">
                                <input type="hidden" name="action" value="show"/>
                                <input type="hidden" name="exporttype"/>
                                @csrf
                                <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                                <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                                <input type="hidden" name="currentmonth" value="{{\Carbon\Carbon::now()->month}}"/>
                                <button type="button" class=" btn btn-sm btn-block dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">Export As <span class="sr-only">Toggle Dropdown</span>
                                    <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="#" data-id="exportpdf">PDF</a>
                                    <a class="dropdown-item" href="#" data-id="exportexcel">EXCEL</a>
                                    </div>
                                </button>
                            </form>
                        @elseif($formtype == 'form2')
                            @if(strtolower($section->acadprogcode) == 'shs')
                                <form action="/forms/{{$formtype}}shsindex" method="get" class="small-box-footer">
                                    <input type="hidden" name="action" value="index"/>
                                    <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                                    <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                                    <input type="hidden" name="formtype" value="{{$formtype}}"/>
                                    <input type="hidden" name="selectedmonth" value="{{date('m')}}"/>
                                    <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                                </form>
                            @else
                                <form action="/forms/{{$formtype}}" method="get" class="small-box-footer">
                                    <input type="hidden" name="action" value="index"/>
                                    <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                                    <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                                    <input type="hidden" name="selectedmonth" value="{{date('m')}}"/>
                                    <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                                </form>
                            @endif
                        @elseif($formtype == 'form5')
                            <form action="/forms/{{$formtype}}" method="get" class="small-box-footer" target="_blank">
                                <input type="hidden" name="action" value="export"/>
                                @csrf
                                <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                                <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                                <input type="hidden" name="exporttype"/>
                                <input type="hidden" name="currentmonth" value="{{\Carbon\Carbon::now()->month}}"/>
                                {{-- <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button> --}}
                                <button type="button" class=" btn btn-sm btn-block dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">Export As <span class="sr-only">Toggle Dropdown</span>
                                    <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="#" data-id="exportpdf">PDF</a>
                                    <a class="dropdown-item" href="#" data-id="exportexcel">EXCEL</a>
                                    </div>
                                </button>
                            </form>
                        @elseif($formtype == 'form5a')
                            <form action="/forms/{{$formtype}}index" method="get" class="small-box-footer">
                                <input type="hidden" name="action" value="show"/>
                                @csrf
                                <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                                <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                                <input type="hidden" name="currentmonth" value="{{\Carbon\Carbon::now()->month}}"/>
                                <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                            </form>
                        @elseif($formtype == 'form5b')
                            <form action="/forms/{{$formtype}}index" method="get" class="small-box-footer">
                                <input type="hidden" name="action" value="show"/>
                                @csrf
                                <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                                <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                                <input type="hidden" name="currentmonth" value="{{\Carbon\Carbon::now()->month}}"/>
                                <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                            </form>
                        @elseif($formtype == 'form9')
                            <form action="/forms/{{$formtype}}" method="get" class="small-box-footer">
                                <input type="hidden" name="action" value="show"/>
                                @csrf
                                <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                                <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                                <input type="hidden" name="currentmonth" value="{{\Carbon\Carbon::now()->month}}"/>
                                <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                            </form>
                        @elseif($formtype == 'form10')
                            <form action="/forms/{{$formtype}}" method="get" class="small-box-footer">
                                <input type="hidden" name="action" value="show"/>
                                @csrf
                                <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                                <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                                {{-- <input type="hidden" name="currentmonth" value="{{\Carbon\Carbon::now()->month}}"/> --}}
                                <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                            </form>
                        @endif
                    </div>
                    
                </div>
            @endforeach
        @endif
    </div>
@endif
@endsection
@section('footerjavascript')
<script>    
    $('.select2').select2({
      theme: 'bootstrap4'
    })
        $('[data-id="exportpdf"]').on('click', function(){
            $(this).closest('form').find('input[name="exporttype"]').val('pdf')
            $(this).closest('form').submit();
        })
        $('[data-id="exportexcel"]').on('click', function(){
            $(this).closest('form').find('input[name="exporttype"]').val('excel')
            $(this).closest('form').submit();
        })
        $(document).ready(function(){
            function changesyid()
            {               
                var syid = $('#selectedyear').val();
                var semid = $('#selectsem').val();
                Swal.fire({
                    title: 'Fetching data...',
                    allowOutsideClick: false,
                    closeOnClickOutside: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                }) 
                if('{{$formtype}}' == 'form1')
                {
                    var urlval = '/forms/index/form1?action=filter';
                }
                else if('{{$formtype}}' == 'form2')
                {
                    var urlval = '/forms/index/form2?action=filter';
                }
                else if('{{$formtype}}' == 'form5')
                {
                    var urlval = '/forms/index/form5?action=filter';
                }
                else if('{{$formtype}}' == 'form5a')
                {
                    var urlval = '/forms/index/form5a?action=filter';
                }
                else if('{{$formtype}}' == 'form5b')
                {
                    var urlval = '/forms/index/form5b?action=filter';
                }
                else if('{{$formtype}}' == 'form9')
                {
                    var urlval = '/forms/index/form9?action=filter';
                }

                $.ajax({
                    url: urlval,
                    type:"GET",
                    // dataType:"json",
                    data:{
                        syid    :  syid,
                        semid    :  semid,
                        formtype: '{{$formtype}}'
                    },
                    success: function(data){
                        $('#div-container').empty();
                        if('{{$formtype}}' == 'form5' ||'{{$formtype}}' == 'form5a' || '{{$formtype}}' == 'form5b')
                        {
                            getsections()
                        }else{
                        $('#div-container').append(data)
                        }
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        
                    }
                })
            }
            @if($formtype != 'form10')
                $('#selectedyear').on('change', function(){
                    changesyid()
                })
                changesyid()
            @endif
            function getsections()
            {
                var acadprogid = '{{$acadprogid}}';
                var semid = 0;
                if('{{$formtype}}' == 'form5a' ||'{{$formtype}}' == 'form5b' )
                {
                    $('#selectedstrand').empty()
                    $('#btn-generatesf5').hide()
                    semid = $('#selectsem').val();
                    acadprogid = 5;
                }else if('{{$formtype}}' == 'form5')
                {
                    semid = $('#selectsem').val();
                    if(semid > 0)
                    {
                        acadprogid = 5;
                    }
                }
                var syid = $('#selectedyear').val();                
                var urlval = '/forms/index/{{$formtype}}?action=getsections';
                $.ajax({
                    url: urlval,
                    type:"GET",
                    // dataType:"json",
                    data:{
                        syid    :  syid,
                        semid    :  semid,
                        acadprogid    :  acadprogid,
                    },
                    success: function(data){
                        $('#selectedsection').empty()
                        if(data.length == 0)
                        {
                            $('#selectedsection').append('<option>No sections assigned</option>')
                            $('#btn-generatesf5').hide()
                        }else{
                            $('#btn-generatesf5').show()
                            $.each(data, function(key, value){
                                $('#selectedsection').append(
                                    '<option value="'+value.levelid+'-'+value.sectionid+'">'+value.levelname+' - '+value.sectionname+'</option>'
                                )
                            })
                            if(acadprogid == 5)
                            {
                            getstrands()
                            }

                        }
                    }
                })
            }
            function getstrands(){
                var syid = $('#selectedyear').val();
                var semid = $('#selectsem').val();   
                var thisvalue = $('#selectedsection').val()
                thisvalue = thisvalue.split('-');
                
                var levelid = thisvalue[0];
                var sectionid = thisvalue[1];
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/forms/index/{{$formtype}}',
                    type:'GET',
                    dataType: 'json',
                    data: {
                        action        :  'getstrands',
                        syid        :  syid,
                        semid       :  semid,
                        levelid       :  levelid,
                        sectionid       :  sectionid
                    },
                    success:function(data) {
                        console.log(data)
                        $('#selectedstrand').empty()
                        if(data.length == 0)
                        {
                            if('{{$formtype}}' == 'form5a')
                            {
                            $('#btn-generatesf5a').hide();
                            }
                            else if('{{$formtype}}' == 'form5b')
                            {
                            $('#btn-generatesf5b').hide();
                            }
                        }else{
                            $.each(data, function(key, value){
                                $('#selectedstrand').append(
                                    '<option value="'+value.id+'">'+value.strandcode+'</option>'
                                )
                            })
                            if('{{$formtype}}' == 'form5a')
                            {
                            $('#btn-generatesf5a').show();
                            }
                            else if('{{$formtype}}' == 'form5b')
                            {
                            $('#btn-generatesf5b').show();
                            }
                        }
                        $('#container-filter').empty()
                        $('#container-filter').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                })
            }
            if('{{$formtype}}' != 'form10')
            {
                $('#selectedsection').on('change', function(){
                    getstrands()
                })
            }
            $('#selectsem').on('change', function(){
                getsections()
            })
            // getsections()
            $('#btn-generatesf5').on('click', function(){
                var syid = $('#selectedyear').val();                
                var semid = $('#selectsem').val();     
                var thisvalue = $('#selectedsection').val()
                thisvalue = thisvalue.split('-');
                if(semid == 0)
                {
                    var acadprogid = '{{$acadprogid}}';
                }else{
                    var acadprogid = 5;
                }
                var levelid = thisvalue[0];
                var sectionid = thisvalue[1];
                $.ajax({
                    url: '/forms/form5?action=show',
                    type:"GET",
                    // dataType:"json",
                    data:{
                        acadprogid    :  acadprogid,
                        syid    :  syid,
                        semid    :  semid,
                        levelid    :  levelid,
                        sectionid    :  sectionid
                    },
                    success: function(data){
                        $('#div-container').empty();
                        $('#div-container').append(data)
                    }
                })
            })
            $('#btn-generatesf5a').on('click', function(){
                var syid = $('#selectedyear').val();                
                var semid = $('#selectsem').val();     
                var strandid = $('#selectedstrand').val();           
                var thisvalue = $('#selectedsection').val()
                thisvalue = thisvalue.split('-');
                
                var levelid = thisvalue[0];
                var sectionid = thisvalue[1];
                $.ajax({
                    url: '/forms/form5a?action=show',
                    type:"GET",
                    // dataType:"json",
                    data:{
                        acadprogid    :  '{{$acadprogid}}',
                        syid    :  syid,
                        semid    :  semid,
                        levelid    :  levelid,
                        strandid    :  strandid,
                        sectionid    :  sectionid
                    },
                    success: function(data){
                        $('#div-container').empty();
                        $('#div-container').append(data)
                    }
                })
            })
            $('#btn-generatesf5b').on('click', function(){
                var syid = $('#selectedyear').val();                
                var semid = $('#selectsem').val();     
                var strandid = $('#selectedstrand').val();           
                var thisvalue = $('#selectedsection').val()
                thisvalue = thisvalue.split('-');
                
                var levelid = thisvalue[0];
                var sectionid = thisvalue[1];
                $.ajax({
                    url: '/forms/form5b?action=show',
                    type:"GET",
                    // dataType:"json",
                    data:{
                        acadprogid    :  '{{$acadprogid}}',
                        syid    :  syid,
                        semid    :  semid,
                        levelid    :  levelid,
                        strandid    :  strandid,
                        sectionid    :  sectionid
                    },
                    success: function(data){
                        $('#div-container').empty();
                        $('#div-container').append(data)
                    }
                })
            })
            $(document).on('click','#btn-saveactiontaken', function(){
                console.log('asdas')
                var syid = $('#selectedyear').val();                
                var thisvalue = $('#selectedsection').val()
                thisvalue = thisvalue.split('-');
                var levelid = thisvalue[0];
                var sectionid = thisvalue[1];
                var actiontakens = [];
                $('tr.eachstudent').each(function(){                    
                    if($('input[name="actiontaken'+ $(this).attr('id')+'"]:checked').length > 0)
                    {
                        obj = {
                            studid  : $(this).attr('id'),
                            actiontaken : $('input[name="actiontaken'+ $(this).attr('id')+'"]:checked').val()
                        }
                        actiontakens.push(obj)

                    }
                })
                console.log(actiontakens)
                if(actiontakens.length == 0)
                {
                    toastr.warning('No changes made!')
                }else{
                    
                    $.ajax({
                        url: '/forms/form5?action=updateactiontaken',
                        type:"GET",
                        // dataType:"json",
                        data:{
                            syid    :  syid,
                            levelid    :  levelid,
                            sectionid    :  sectionid,
                            actiontakens    :  JSON.stringify(actiontakens)
                        },
                        success: function(data){
                            toastr.success('Updated successfully!')
                        }
                    })
                }
            })
            $(document).on('click','#btn-getrecords', function(){      

                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                var studentid = $('#select-studentid').val();
                
                if('{{$acadprogid}}' == 5)
                {
                    var acadprogname = 'SENIOR HIGH SCHOOL';
                }else if('{{$acadprogid}}' == 4)
                {
                    var acadprogname = 'HIGH SCHOOL';
                }else{
                    var acadprogname = 'ELEMENTARY';
                }
                $.ajax({
                    url: '/reports_schoolform10/view',
                    type:'GET',
                    data: {
                        studentid        :  studentid,
                        acadprogid       :  '{{$acadprogid}}',
                        acadprogname       :  acadprogname
                    },
                    success:function(data) {                        
                        $('#div-container').empty()
                        $('#div-container').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                })
            })
        })
</script>
@endsection
