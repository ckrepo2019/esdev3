
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

@extends('clinic.layouts.app')

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
            height: 40px;
        }
</style>
@section('content')
    @php
        use \Carbon\Carbon;
        $now = Carbon::now();
        $comparedDate = $now->toDateString();
    @endphp

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Create Appointment</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Create Appointment</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
        <!-- Info boxes -->
            <div class="row">
                <div class="col-md-4 text-left">
                    <div class="row">
                        <div class="col-md-12">
                            <label>&nbsp;</label><br/>
                            <button type="button" class="btn btn-info" id="btn-addexperience"><i class="fa fa-plus"></i> Add Experience Option</button>
                        </div>
                    </div>
                    <div class="row pt-2" id="container-experienceoptions">

                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row mb-4">
                        <div class="col-12 col-md-9">
                            <label>Applicant/Patient:</label><br/>
                            <select class="form-control select2" style="width: 100%;">
                            <option selected="selected">Alabama</option>
                            <option>Alaska</option>
                            <option>California</option>
                            <option>Delaware</option>
                            <option>Tennessee</option>
                            <option>Texas</option>
                            <option>Washington</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>MR #:</label><br/>
                            <input type="" class="form-control"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>What are you being seen for today?</label><br/>
                            <input type="text" class="form-control"/>
                        </div>
                        <div class="col-md-12">
                            <label><em>Please Check if You Have Experienced Any of the Following:</em></label>
                        </div>
                    </div>
                    <div class="row mb-4" id="container-options">
                        @if(count($experiences)>0)
                            @foreach($experiences as $experience)
                                <div class="col-sm-6" data-column="{{$experience->id}}">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="checkboxPrimary{{$experience->id}}">
                                            <label for="checkboxPrimary{{$experience->id}}">{{$experience->description}}</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary collapsed-card" style="border: none !important; box-shadow: unset;">
                                <div class="card-header">
                                <h3 class="card-title">Medical History</h3>
                
                                <div class="card-tools pt-2">
                                    <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-md-12 mb-3">
                                            <label><em>Please Answer the Following Medical History Questions:</em></label>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <span>List All Hospitalizations / Surgeries (include dates)</span><br/>
                                            <textarea class="form-control"></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <span>Family History of Illness</span><br/>
                                            <textarea class="form-control"></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    Do You or Have You Ever Smoked? <br/>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="smokedstatus">
                                                        <label for="smokedstatus">Yes</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    Packs Per Day: <input type="number" step="any" style="width: 50%;"/>
                                                </div>
                                                <div class="col-md-4">
                                                    Age Started: <input type="number" step="any" style="width: 50%;"/>
                                                </div>
                                                <div class="col-md-4">
                                                    Age You Quit: <input type="number" step="any" style="width: 50%;"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    Do You Drink Alcohol? <br/>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="drinkstatus">
                                                        <label for="drinkstatus">Yes</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    Average Drinks Per Day / Week: <input type="number" step="any" style="width: 30%;"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label>Current Medications (include strength & dosage)</label><br/>
                                            <textarea class="form-control"></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label>Allergies {{--to Medication--}}</label><br/>
                                            <textarea class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-12">
                            <label>Please Answer to the Best of Your Knowledge</label>
                        </div>
                        <div class="col-md-12 mb-2">
                            <span>Do You Take Aspirin of Blood Thinners? (Please List)</span><br/>
                            <textarea class="form-control"></textarea>
                        </div>
                        <div class="col-md-12">
                            Do you have a Family History of Osteoporosis? 
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="smokedstatus">
                                <label for="smokedstatus">Yes</label>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>
        <div class="modal fade" id="modal-addexperience">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Option</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <small class="badge badge-danger">Question: Please Check if You Have Experienced Any of the Following:</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Option</label><br/>
                        <input type="text" class="form-control" placeholder="Add option" id="input-addoption"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-closeexperience">Close</button>
                <button type="button" class="btn btn-primary" id="btn-addoption">Add</button>
            </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
        </div>
    @endsection
    @section('footerjavascript')
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
        
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        })  
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        $(document).ready(function(){
            function reloadExperiences()
            {
                $.ajax({
                    url:    '/clinic/appointment/getexperiences',
                    type:   'GET',
                    dataType:   'json',
                    success:function(data)
                    {
                        // $('#container-options').empty()
                        $('#container-experienceoptions').empty()
                        $.each(data, function(key, value){
                            $('#container-experienceoptions').append(
                                '<div class="col-md-12 text-left mb-2"><button type="button" class="btn btn-default btn-sm btn-deleteexperience" data-id="'+value.id+'"><i class="fa fa-trash"></i></button> &nbsp;&nbsp;&nbsp;'+value.description+'</div>'
                            )
                            // $('#container-options').append(
                            //     '<div class="col-sm-6">'+
                            //         '<div class="form-group clearfix">'+
                            //             '<div class="icheck-primary d-inline">'+
                            //                 '<input type="checkbox" id="checkboxPrimary'+value.id+'">'+
                            //                 '<label for="checkboxPrimary'+value.id+'">'+value.description+'</label>'+
                            //             '</div>'+
                            //         '</div>'+
                            //     '</div>'
                            // )
                        })
                    }
                })
            }

            reloadExperiences();

            $('#btn-createapp').on('click', function(){
                window.open("/clinic/appointment/index");
            })

            $('#btn-addexperience').on('click', function(){
                $('#modal-addexperience').modal('show')
            })

            $('#btn-addoption').on('click', function(){

                var newoption = $('#input-addoption').val();

                if(newoption.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $('#input-addoption').css('border','1px solid red')
                }else{
                    $('#input-addoption').removeAttr('style')
                    $.ajax({
                        url:    '/clinic/appointment/createexperience',
                        type:   'GET',
                        dataType:   'json',
                        data:   {
                            newoption   :   newoption
                        },
                        success:function(data)
                        {
                            if(data == 0)
                            {
                                Toast.fire({
                                    type: 'warning',
                                    title: 'Option already exist!'
                                })
                            }else{
                                Toast.fire({
                                    type: 'success',
                                    title: 'Option added successfully!'
                                })
                                $('#container-experienceoptions').prepend(
                                    '<div class="col-md-12 text-left mb-2"><button type="button" class="btn btn-default btn-sm btn-deleteexperience" data-id="'+data+'"><i class="fa fa-trash"></i></button> &nbsp;&nbsp;&nbsp;'+newoption+'</div>'
                                )
                                $('#container-options').append(
                                    '<div class="col-sm-6" data-column="'+data+'">'+
                                        '<div class="form-group clearfix">'+
                                            '<div class="icheck-primary d-inline">'+
                                                '<input type="checkbox" id="checkboxPrimary'+data+'">'+
                                                '<label for="checkboxPrimary'+data+'">'+newoption+'</label>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'
                                )
                            }
                            $('#btn-closeexperience').click()
                            reloadExperiences()
                        }
                    })
                }
            })

            $(document).on('click', '.btn-deleteexperience', function(){
                var experienceid = $(this).attr('data-id');
                Swal.fire({
                    title: 'Do you want to delete this option?',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete'
                })
                .then((result) => {
                    if (result.value) {
                        $.ajax({
                            url:'/clinic/appointment/deleteexperience',
                            type:'GET',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                id      :  experienceid
                            },
                            success:function(data) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Deleted successfully!'
                                })
                                $('[data-column="'+experienceid+'"]').remove()
                                reloadExperiences()
                            }
                        })
                    }
                })
            })

        })
    </script>
@endsection
