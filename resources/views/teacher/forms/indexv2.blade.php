
@extends('teacher.layouts.app')

@section('content')
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
<div class="card" style="border: none;">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <label>Select S.Y.</label>
                <select class="form-control select2" id="select-syid">
                    @foreach(DB::table('sy')->orderBy('sydesc','desc')->get() as $eachsy)
                        <option value="{{$eachsy->id}}" @if($eachsy->isactive == 1) selected @endif>{{$eachsy->sydesc}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Select Semester</label>
                <select class="form-control select2" id="select-semid">
                    @foreach(DB::table('semester')->get() as $eachsemester)
                        <option value="{{$eachsemester->id}}" @if($eachsemester->isactive == 1) selected @endif>{{$eachsemester->semester}}</option>
                    @endforeach
                </select>
                <small style="font-size: 11px;">(<strong>Semester filter</strong> is for SHS Advisers only)</small>
            </div>
            <div class="col-md-3" id="container-sections">
                <label>Select Section</label>
                <select class="form-control select2" id="select-sectionid">
                </select>
            </div>
            <div class="col-md-3" id="container-strands">
                <label>Select Strand</label>
                <select class="form-control select2" id="select-strandid">
                </select>
            </div>
            {{-- <div class="col-md-6 text-right">
                <label>&nbsp;</label><br/>
                <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
            </div> --}}
        
        </div>
    </div>
</div>
<div id="div-container"></div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
@endsection
@section('footerscripts')
<script>
    $(document).ready(function(){
        $('#container-sections').hide();
        $('#container-strands').hide();
        function changesyid()
        {           
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            Swal.fire({
                title: 'Fetching sections...',
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
                $('#container-sections').show();
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
            $('#select-syid').on('change', function(){
                changesyid()
            })
            changesyid()
        @endif
        @if($formtype == 'form1')
        $('#select-semid').on('change', function(){
            changesyid()
        })
        @else
        $('#select-semid').on('change', function(){
            getsections()
        })
        @endif
        function getsections()
        {
            var acadprogid = '{{$acadprogid}}';
            var semid = $('#select-semid').val();
            if('{{$formtype}}' == 'form5a' ||'{{$formtype}}' == 'form5b' )
            {
                $('#container-sections').show();
                $('#select-strandid').empty()
                $('#btn-generatesf5').hide()
                acadprogid = 5;
            }else if('{{$formtype}}' == 'form5')
            {
                $('#container-sections').show();
            }
            var syid = $('#select-syid').val();                
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
                    $('#select-sectionid').empty()
                    if(data.length == 0)
                    {
                        $('#select-sectionid').append('<option>No sections assigned</option>')
                        $('#btn-generatesf5').hide()
                    }else{
                        $('#btn-generatesf5').show()
                        $.each(data, function(key, value){
                            $('#select-sectionid').append(
                                '<option value="'+value.levelid+'-'+value.sectionid+'">'+value.levelname+' - '+value.sectionname+'</option>'
                            )
                        })
                        var thisvalue = $('#select-sectionid').val()
                        thisvalue = thisvalue.split('-');
                        
                        var levelid = thisvalue[0];
                        var sectionid = thisvalue[1];
                        if(levelid == 14 || levelid == 15)
                        {
                                            $('#container-strands').show();
                            getstrands()
                        }else{
                                            $('#container-strands').hide();
                                            
                            if('{{$formtype}}' == 'form5')
                                {
                            generatesf5()
                                }else{
                                    changesyid()
                                }
                        }

                    }
                }
            })
        }
        function getstrands(){
                                // $('#container-strands').hide();
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();   
            var thisvalue = $('#select-sectionid').val()
            thisvalue = thisvalue.split('-');
            
            var levelid = thisvalue[0];
            var sectionid = thisvalue[1];
            if(levelid == 14 || levelid == 15)
            {
                                $('#container-strands').show();
            }else{
                                $('#container-strands').hide();
            }
            Swal.fire({
                title: 'Fetching strands...',
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
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    $('#select-strandid').empty()
                    if(data.length == 0)
                    {
                        if('{{$formtype}}' == 'form5a' || '{{$formtype}}' == 'form5b' || '{{$formtype}}' == 'form5')
                        {
                            if('{{$formtype}}' != 'form5')
                            {
                                $('#container-strands').show();

                            }
                            $('#div-container').empty();
                            $('#div-container').append(`
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    No students enrolled!
                </div>
            </div>
            `)
                        }
                    }else{
                        $.each(data, function(key, value){
                            $('#select-strandid').append(
                                '<option value="'+value.id+'">'+value.strandcode+'</option>'
                            )
                        })
                        if('{{$formtype}}' == 'form5a')
                        {
                            $('#container-strands').show();
                        generatesf5a()
                        }
                        else if('{{$formtype}}' == 'form5b')
                        {        $('#container-strands').show();
                        generatesf5b()
                        }
                        else if('{{$formtype}}' == 'form5')
                        {
                        generatesf5()
                        }
                    }
                    $('#container-filter').empty()
                    $('#container-filter').append(data)
                }
            })
        }
        function generatesf5()
        {
            Swal.fire({
                title: 'Loading results...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            var syid = $('#select-syid').val();                
            var semid = $('#select-semid').val();     
            var thisvalue = $('#select-sectionid').val()
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
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        }
        function generatesf5a()
        {
            Swal.fire({
                title: 'Loading results...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            var syid = $('#select-syid').val();                
            var semid = $('#select-semid').val();     
            var strandid = $('#select-strandid').val();           
            var thisvalue = $('#select-sectionid').val()
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
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        }
        function generatesf5b()
        {
            Swal.fire({
                title: 'Loading results...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            var syid = $('#select-syid').val();                
            var semid = $('#select-semid').val();     
            var strandid = $('#select-strandid').val();           
            var thisvalue = $('#select-sectionid').val()
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
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        }
        if('{{$formtype}}' != 'form10')
        {
            $('#select-sectionid').on('change', function(){
                var thisvalue = $(this).val()
                thisvalue = thisvalue.split('-');
                var levelid = thisvalue[0];
                var sectionid = thisvalue[1];
                if(levelid == 14 || levelid == 15)
                {
                    $('#container-strands').show();
                    getstrands()
                }else{
                    $('#container-strands').hide();
                    if('{{$formtype}}' == 'form5')
                    {
                    generatesf5()
                    }
                }
            })
        }
        $('#select-strandid').on('change', function(){
            
            Swal.fire({
                title: 'Loading results...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            if('{{$formtype}}' == 'form5')
            {
                    generatesf5()
            }
            else if('{{$formtype}}' == 'form5a')
            {
                    generatesf5a()
            }
            else if('{{$formtype}}' == 'form5b')
            {
                    generatesf5b()
            }
                    // $(".swal2-container").remove();
                    // $('body').removeClass('swal2-shown')
                    // $('body').removeClass('swal2-height-auto')
        })
        $(document).on('click','#btn-saveactiontaken', function(){
            console.log('asdas')
            var syid = $('#select-syid').val();                
            var thisvalue = $('#select-sectionid').val()
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
            var _url = '/schoolform10/getrecordselem';
            // '/reports_schoolform10/view'
            if('{{$acadprogid}}' == 5)
            {
                var acadprogname = 'SENIOR HIGH SCHOOL';
                _url = '/schoolform10/getrecordssenior';
            }else if('{{$acadprogid}}' == 4)
            {
                var acadprogname = 'HIGH SCHOOL';
                _url = '/schoolform10/getrecordsjunior';
            }else{
                var acadprogname = 'ELEMENTARY';
            }
            
            $.ajax({
                url: _url,
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
                    $('.btn-exportform-elem').closest('.row').remove()
                    $('.btn-exportform-jhs').closest('.row').remove()
                    $('.btn-exportform-senior').closest('.row').remove()
                }
            })
        })
    })
</script>
@endsection
