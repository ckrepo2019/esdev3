
@extends('registrar.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">

<!-- Tempusdominus Bbootstrap 4 -->
<link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<!-- Bootstrap4 Duallistbox -->
<link rel="stylesheet" href="{{asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #006fe6;
        color: #fff;
        padding: 0 10px;
        margin-top: .31rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
    }
    .card-columns {
   column-count: 3;
   
}
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Scholars</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Scholars</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card card-primary collapsed-card">
            <div class="card-header">
              <h3 class="card-title">Scholarship Programs</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-plus m-2"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary" id="addprogram"><i class="fa fa-plus"></i> Program</button>
                    </div>
                </div>
                @if(count($programs)>0)
                <div class="row">
                    @foreach($programs as $program)
                        <div class="col-md-3">
                            <button type="button" class="btn btn-sm btn-default btn-block edit-program" data-id="{{$program->id}}">{{$program->program}}</button>
                        </div>
                    @endforeach
                    {{-- <div class="col-12">
                        <ul class="card-columns">
                            @foreach($programs as $program)
                                <li style="list-style-type: disc;"><a href="#" class="edit-program" style="width: 100%;" data-id="{{$program->id}}">{{$program->program}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-edit text-secondary"></i></a></li>
                            @endforeach
                        </ul>
                    </div> --}}
                </div>
                @endif
            </div>
            <!-- /.card-body -->
          </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row mb-2">
                    <div class="col-3">
                        <label>Select Grade Level</label>
                        <select class="form-control" id="gradelevelid">
                            <option value="">ALL</option>
                            @foreach($gradelevels as $gradelevel)                            
                                <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <label>Select Scholarship Programs</label>
                        <select class="form-control" id="select-programid">
                            <option value="0"></option>
                            @if(count($programs)>0)
                                @foreach($programs as $program)
                                    <option value="{{$program->id}}">{{$program->abbreviation}} - {{$program->program}}</option>
                                @endforeach
                            @endif
                            {{-- @if(count($programs)>0)
                                <div class="form-group clearfix">
                                    @foreach($programs as $program)
                                        <div class="icheck-primary d-inline mr-2">
                                            <input type="checkbox" class="filter-program" id="checkboxPrimary{{$program->id}}" value="{{$program->id}}">
                                            <label for="checkboxPrimary{{$program->id}}">{{$program->program}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif --}}
                        </select>
                    </div>
                    <div class="col-3">
                        <label>Select Schoolyear</label>
                        <select class="form-control" id="select-syid">
                            @if(count($schoolyears)>0)
                                @foreach($schoolyears as $schoolyear)
                                    <option value="{{$schoolyear->id}}" @if($schoolyear->isactive == 1) selected @endif>{{$schoolyear->sydesc}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-3">
                        <label>Select Semester</label>
                        <select class="form-control" id="select-semid">
                            @if(count($semesters)>0)
                                @foreach($semesters as $semester)
                                    <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6" id="filter-input-container">
                        <input type="text" class="form-control" id="input-filterstudents" placeholder="Search Student"/>
                    </div>
                    {{-- <div class="col-1">
                        <button type="button" class="btn btn-warning btn-block" id="count-results"></button>
                    </div> --}}
                    <div class="col-6 text-right">
                        <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                    </div>
                </div>
            </div>
            {{-- <div class="card-body" id="tablecontainer">
                <table id="studentstable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 20px;">#</th>    
                            <th>Students</th>       
                            <th>Grade Level</th>    
                            <th>Scholarships</th>    
                            <th></th>    
                        </tr>
                    </thead>
                    <tbody id="studentscontainer"  style="font-size: 12px;">
                        @if(count($students)>0)
                            @foreach($students as $student)
                                <tr>
                                    <td></td>
                                    <td>
                                        <div class="row">
                                            <div class="col-12">
                                                <label>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</label>
                                            </div>
                                            <div class="col-6">
                                                SID: {{$student->sid}}
                                            </div>
                                            <div class="col-6">
                                                LRN: {{$student->lrn}}
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{$student->levelname}}</td>
                                    <td id="stud{{$student->id}}">
                                        @if(count($student->scholarships)>0)
                                            <div class="row">
                                                @foreach ($student->scholarships as $scholarship)
                                                    <div class="col-12">{{$scholarship->program}}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-default btn-sm btn-addscholarship" data-id="{{$student->id}}" style="font-size: 10px;"><i class="fa fa-cogs"></i> Scholarship</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

            </div> --}}
        </div>
    </div>
</div>
<div class="row" id="tablecontainer">

</div>
<div class="modal fade" id="modal-program" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >New Scholarship Program</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" >
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <input type="text" class="form-control" placeholder="Program name" id="input-newprogram" required/>
                    </div>
                    <div class="col-md-12">
                        <input type="text" class="form-control" placeholder="Abbreviation" id="input-newprogramabbr"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="submit-newclose">Close</button>
                <button type="button" class="btn btn-primary" id="submit-newprogram">Submit</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-edit-program" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >Edit Scholarship Program</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" >
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <input type="text" class="form-control" placeholder="Program name" id="input-editprogram" required/>
                    </div>
                    <div class="col-md-12 mb-2">
                        <input type="text" class="form-control" placeholder="Abbreviation" id="input-editabbr"/>
                    </div>
                    <div class="col-md-12 mb-2">
                        <input type="number" class="form-control" placeholder="Full Scholarship amount" id="input-editfullamount"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="submit-editclose">Close</button>
                <div class="text-right">
                    <button type="button" class="btn btn-danger" id="submit-deleteprogram">Delete</button>
                    <button type="button" class="btn btn-primary" id="submit-editprogram">Update</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-add-scholarship" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >Scholarships Granted</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div id="selectcontainer">

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-edit-record" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" ></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" >
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <input type="text" class="form-control" placeholder="Amount" id="input-editamount"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="submit-editclose">Close</button>
                <button type="button" class="btn btn-primary" id="submit-editprogstud">Update</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- Toastr -->
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>
    $(document).ready(function(){
        $('.content-wrapper').removeAttr('style')
        var table = $("#studentstable").DataTable({
            pageLength : 20,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });
        table.on( 'order.dt search.dt', function () {
            table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
        $('.paginate_button').addClass('btn btn-default')
        $(document).on('click', '.paginate_button', function(){
            $('.paginate_button').removeClass('btn btn-default')
            $('.paginate_button').addClass('btn btn-default')
        })
        $(document).on('click','.btn-addscholarship', function(){
            var id = $(this).attr('data-id');
            $('#modal-add-scholarship').modal('show')
            $.ajax({
                url: '/registrar/scholars/programselect',
                type: 'GET',
                data: {
                    id : id
                },
                datatype: 'json',
                success:function(data){
                    $('#selectcontainer').empty()
                    $('#selectcontainer').append(data)
                }
            })
        })
        $(document).on('click','.selectedscholarhips', function(){
            if($('.selectedscholarhips:checked').length > 0)
            {
                $('#grant-scholarship-footer').removeAttr('hidden')
            }else{
                // $('#grant-scholarship-footer').prop('hidden', true)
            }
            if($(this).prop('checked'))
            {
                $(this).closest('.row').find('input[type="number"]').removeAttr('hidden')
                $(this).closest('.row').find('select').removeAttr('hidden')
            }else{
                $(this).closest('.row').find('select').prop('hidden',true)
                $(this).closest('.row').find('input[type="number"]').prop('hidden',true)
            }
        })
        $(document).on('click','#submit-scholarship', function(){
            var id = $(this).attr('data-id')
            var scholarships = [];
            $('.selectedscholarhips:checked').each(function(){
                obj = {
                    scholarshipid : $(this).val(),
                    type          : $(this).closest('.row').find('select').val(),
                    amount        : $(this).closest('.row').find('input[type="number"]').val()
                }
                scholarships.push(obj)
            })
            console.log($('#select-syid').val())
            $.ajax({
                url: '/registrar/scholars/programsubmitselect',
                type: 'GET',
                data: {
                    id : id,
                    scholarships: JSON.stringify(scholarships),
                    levelid     : $('#gradelevelid').val(),
                    syid        : $('#select-syid').val(),
                    semid       : $('#select-semid').val()
                },
                datatype: 'json',
                success:function(data){
                    $('#submit-scholarshipclose').click();
                    $('#btn-generate').click();
                    // $('#selectcontainer').empty()
                    // $('#selectcontainer').append(data)
                }
            })

        })
        const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
        });
        $('#addprogram').on('click', function(){
            $('#modal-program').modal('show')
            console.log('asda')
        })

        $('#submit-newprogram').on('click', function(){
            if($('#input-newprogram').val().replace(/^\s+|\s+$/g, "").length == 0){
                $('#input-newprogram').css('border','1px solid red')
            }else{
                $.ajax({
                    url: '/registrar/scholars/programadd',
                    type: 'GET',
                    data: {
                        newprogram  : $('#input-newprogram').val(),
                        abbreviation: $('#newprogramabbr').val()
                    },
                    datatype: 'json',
                    success:function(data){
                        if(data == 1)
                        {
                            toastr.warning('Already exists!', 'Scholarship Program')
                        }else if(data == 0){
                            toastr.success('Added successfully!', 'Scholarship Program')
                            $('#submit-newclose').click()
                            $('#input-newprogram').val('')
                            $('body').removeClass('modal-open')
                            // $('.modal-backdrop').remove()
                        }else{
                            toastr.error('Something went wrong!', 'Scholarship Program')
                        }
                    }
                })
            }
        })
        $('.edit-program').on('click', function(){
            $('#modal-edit-program').modal('show')
            var id = $(this).attr('data-id')
            $.ajax({
                url: '/registrar/scholars/programname',
                type: 'GET',
                data: {
                    id : id
                },
                datatype: 'json',
                success:function(data){
                    if(data.constant == 1)
                    {
                        $('#submit-deleteprogram').hide();
                        $('#input-editprogram').prop('disabled',true)
                        $('#input-editabbr').prop('disabled',true)
                    }else{
                        $('#submit-deleteprogram').show();
                        $('#input-editprogram').prop('disabled',false)
                        $('#input-editabbr').prop('disabled',false)
                    }
                    $('#input-editprogram').attr('data-id', id)
                    $('#input-editprogram').val(data.program)
                    $('#input-editabbr').val(data.abbreviation)
                    $('#input-editfullamount').val(data.fullamount)
                    $('#submit-deleteprogram').attr('data-id', id)
                }
            })
        })
        $('#submit-editprogram').on('click', function(){
            if($('#input-editprogram').val().replace(/^\s+|\s+$/g, "").length == 0){
                $('#input-editprogram').css('border','1px solid red')
            }else{
                var newname = $('#input-editprogram').val();
                $.ajax({
                    url: '/registrar/scholars/programedit',
                    type: 'GET',
                    data: {
                        id :  $('#input-editprogram').attr('data-id'),
                        programname : $('#input-editprogram').val(),
                        abbreviation : $('#input-editabbr').val(),
                        fullamount : $('#input-editfullamount').val()
                    },
                    datatype: 'json',
                    success:function(data){
                        if(data == 'exists')
                        {
                            toastr.warning('Already exists!', 'Scholarship Program')
                        }else if(data == 'error'){
                            toastr.error('Something went wrong!', 'Scholarship Program')
                            // $('.modal-backdrop').remove()
                        }else{
                            toastr.success('Updated successfully!', 'Scholarship Program')
                            $('a[data-id="'+data+'"]').empty();
                            $('a[data-id="'+data+'"]').append(newname+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-edit text-secondary"></i>');
                            $('#submit-editclose').click()
                            $('#input-editclose').val('')
                            $('body').removeClass('modal-open')
                            $('#btn-generate').click();
                        }
                    }
                })
            }
        })
        $('#submit-deleteprogram').on('click', function(){
            var id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure you want to delete this Scholarship Program?',
                text: 'You won\'t be able to revert this!',
                type: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes',
                showCancelButton: true,
                allowOutsideClick: false
            }).then((confirm) => {
                if (confirm.value) {
                    $.ajax({
                        url: '/registrar/scholars/programdelete',
                        type: 'GET',
                        data: {
                            id :  id
                        },
                        datatype: 'json',
                        success:function(data){
                            if(data == 'error')
                            {
                                toastr.error('Something went wrong!', 'Scholarship Program')
                            }else{
                                toastr.success('Deleted successfully!', 'Scholarship Program')
                                $('a[data-id="'+data+'"]').closest('li').remove();
                                $('#submit-editclose').click()
                                $('body').removeClass('modal-open')
                                $('#btn-generate').click();
                            }
                        }
                    })
                }
            })
                
        })
        $('#gradelevelid').on('change', function(){
            $('#input-filterstudents').hide()
            $('#tablecontainer').empty();
        })
        $('#select-programid').on('change', function(){
            $('#input-filterstudents').hide()
            $('#tablecontainer').empty();
        })
        $('#select-syid').on('change', function(){
            $('#input-filterstudents').hide()
            $('#tablecontainer').empty();
        })
        $('#select-semid').on('change', function(){
            $('#input-filterstudents').hide()
            $('#tablecontainer').empty();
        })

        $('#btn-generate').on('click', function(){
            
            
            var levelid = $('#gradelevelid').val()
            // var programs = [];
            // $('.filter-program:checked').each(function(){
            //     programs.push($(this).val())
            // })
            Swal.fire({
                title: 'Loading results...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            $.ajax({
                url: '/registrar/scholars/filter',
                type: 'GET',
                data: {
                    levelid     : levelid,
                    programid   : $('#select-programid').val(),
                    syid        : $('#select-syid').val(),
                    semid       : $('#select-semid').val()
                },
                success:function(data){
                    $('#input-filterstudents').show()
                    $('#tablecontainer').empty();
                    $('#tablecontainer').append(data)
                    // var table = $("#studentstable").DataTable({
                    //     pageLength : 20,
                    //     lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
                    // });
                    // table.on( 'order.dt search.dt', function () {
                    //     table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    //         cell.innerHTML = i+1;
                    //     } );
                    // } ).draw();
                    // $('.paginate_button').addClass('btn btn-default')
                    
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    $('.content-wrapper').removeAttr('style')

                }
            })
        })
        $(document).on('change','.scholarshiptype', function(){
            var inputamount = $(this).closest('.row').find('input[type="number"]');
            if($(this).val() == 0)
            {
                $(this).closest('.row').find('input[type="number"]').val('0.00')
                $(this).closest('.row').find('input[type="number"]').removeAttr('readonly')
            }else{
                $.ajax({
                    url: '/registrar/scholars/getamount',
                    type: 'GET',
                    data: {
                        id      :  $(this).closest('.row').find('input[type="checkbox"]').val(),
                        type    :  $(this).val()
                    },
                    datatype: 'json',
                    success:function(data){
                        inputamount.val(data)
                        inputamount.prop('readonly',true)
                    }
                })
            }
        })
        $("#input-filterstudents").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".eachstudent").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                $(".card-group.card-group-filter:first-of-type").append($(this));
                $(this).hide();
                hiddenCards++;

                } else {

                $(".card-group.card-group-filter:last-of-type").prepend($(this));
                $(this).show();
                visibleCards++;

                if (((visibleCards % 4) == 0)) {
                    $(".container").append($("<div class='card-group card-group-filter'></div>"));
                }
                }
            });

        });
        $(document).on('click', '.btn-edit', function(){
            $('#modal-edit-record').modal('show');
            var id = $(this).attr('data-id');
            $.ajax({
                url: '/registrar/scholars/getprogstud',
                type: 'GET',
                data: {
                    id :  id
                },
                datatype: 'json',
                success:function(data){
                    $('#input-editamount').val(data.amount);
                    $('#submit-editprogstud').attr('data-id',id)
                }
            })
        })
        $('#submit-editprogstud').on('click', function(){
            var id = $(this).attr('data-id');
            if($('#input-editamount').val().replace(/^\s+|\s+$/g, "").length == 0){
                $('#input-editamount').css('border','1px solid red');
            }else{
                $.ajax({
                    url: '/registrar/scholars/updateamount',
                    type: 'GET',
                    data: {
                        id      :  id,
                        amount  :  $('#input-editamount').val()
                    },
                    datatype: 'json',
                    success:function(data){
                        $('#input-editamount').val(data.amount);
                        $('#submit-editprogstud').attr('data-id',id)
                        if(data == 1)
                        {
                            toastr.warning('Updated successfully!', 'Scholarship')
                        }else{
                            toastr.error('Something went wrong!', 'Scholarship')
                        }
                    }
                })
            }
        })
        $(document).on('click', '.btn-delete', function(){
            var id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure you want to delete this?',
                text: 'You won\'t be able to revert this!',
                type: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes',
                showCancelButton: true,
                allowOutsideClick: false
            }).then((confirm) => {
                if (confirm.value) {
                    $.ajax({
                        url: '/registrar/scholars/deleteprogstud',
                        type: 'GET',
                        data: {
                            id      :  id
                        },
                        datatype: 'json',
                        success:function(data){
                            if(data == 1)
                            {
                                toastr.warning('Deleted successfully!', 'Scholarship')
                                $('#btn-generate').click();
                            }else{
                                toastr.error('Something went wrong!', 'Scholarship')
                            }
                        }
                    })
                }
            })
        })

    })
</script>
@endsection
