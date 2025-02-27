@extends('teacher.layouts.app')


@section('content')

<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
          margin-top: -9px;
    }
</style>

<style>
    .ribbon-wrapper .ribbon {
        box-shadow: 0 0 3px rgb(0 0 0 / 30%);
        font-size: 0.7rem;
        line-height: 100%;
        padding: 0.375rem 0;
        position: relative;
        right: -1px;
        text-align: center;
        text-shadow: 0 -1px 0 rgb(0 0 0 / 40%);
        text-transform: uppercase;
        top: 2px;
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg);
        width: 100px;
    }
</style>

<section class="content-header">
    <div class="container-fluid">
          <div class="row mb-2">
                <div class="col-sm-6">
                      <h1>System Grading</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="/home">Home</a></li>
                      <li class="breadcrumb-item active">System Grading</li>
                </ol>
                </div>
          </div>
    </div>
</section>


<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="row">
                        <div class="col-md-4">
                            <h5><i class="fa fa-filter"></i> Filter</h5> 
                        </div>
                        <div class="col-md-8">
                            <h5 class="float-right">Active S.Y.: {{collect($schoolyears)->where('isactive',1)->first()->sydesc}}</h5>
                        </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <label>School Year</label>
                        <select class="form-control select2" id="selectedschoolyear">
                            @foreach(collect($schoolyears)->sortByDesc('sydesc')->values() as $schoolyear)
                                <option value="{{$schoolyear->id}}" @if($schoolyear->isactive == 1) selected @endif>{{$schoolyear->sydesc}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">
                        <label>Semester</label>
                        <select class="form-control select2" id="selectedsemester">
                            @foreach($semesters as $semester)
                                <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" id="sections-container">
</div>

<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    var $ = jQuery;
    $(document).ready(function() {

        $('.select2').select2()

        var selectedschoolyear = $('#selectedschoolyear').val();
        var selectedsemester = $('#selectedsemester').val();
        
        Swal.fire({
            title: 'Fetching data...',
            onBeforeOpen: () => {
                Swal.showLoading()
            },
            allowOutsideClick: false
        })
        view_sections()

        $(document).on('change','#selectedsemester , #selectedschoolyear',function(){
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            view_sections()
        })

        function view_sections(){
            $.ajax({
                url: '/grades/getsections',
                type: 'GET',
                data: {
                    selectedschoolyear  :  selectedschoolyear,
                    selectedsemester    :  selectedsemester
                },
                success:function(data){

                    if(data.filter(x=>x.levelid == 14 || x.levelid == 15).length > 0){
                        $('#strand_holder').removeAttr('hidden')
                    }else{
                        if(selectedsemester != 2){
                            $('#strand_holder').attr('hidden','hidden')
                        }
                    }

                    $('#sections-container').empty()
                    if(data.length > 0)
                    {
                        $.each(data,function(key, value){

                            var with_pending = '<div class="ribbon-wrapper">'+
                                                '<div class="ribbon bg-warning">'+
                                                    'With <br>Pending'+
                                                '</div>'+
                                            '</div>'

                            if(!value.with_pending){
                                with_pending = ''
                            }

                            $('#sections-container').append(
                            '<div class="col-lg-3 col-3 mb-2 ">'+
                                '<div class="small-box bg-info  h-100 shadow"  style="width: 100%; display: grid;">'+with_pending+
                                '<div class="inner" >'+
                                    '<p>'+value.sectionname+'</p>'+
                                    '<sup>'+value.levelname+'</sup>'+
                                '</div>'+
                                '<a href="/grades/getsubjects?selectedschoolyear='+selectedschoolyear+'&selectedsemester='+selectedsemester+'&selectedlevelid='+value.levelid+'&selectedsectionid='+value.sectionid+'" class="small-box-footer">'+
                                    'Select <i class="fas fa-arrow-circle-right"></i>'+
                                '</a>'+
                                '</div>'+
                            '</div>'
                            )
                        })
                    }else{
                        $('#sections-container').append('<div class=" col-md-12"><div class="card shadow"><div class="card-body p-2">No Records Found.</div></div></div>')
                    }
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        }

        $('#selectedschoolyear').on('change', function(){
            selectedschoolyear = $(this).val();
        })

        $('#selectedsemester').on('change', function(){
            selectedsemester = $(this).val();
        })
    });
</script>
@endsection