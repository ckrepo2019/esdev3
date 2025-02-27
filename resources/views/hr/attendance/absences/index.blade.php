

@extends('hr.layouts.app')
@section('content')
<!-- DataTables -->
{{-- <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}"> --}}

<style>
    table.table td h2.table-avatar {
    align-items: center;
    display: inline-flex;
    font-size: inherit;
    font-weight: 400;
    margin: 0;
    padding: 0;
    vertical-align: middle;
    white-space: nowrap;
}
.avatar {
    background-color: #aaa;
    border-radius: 50%;
    color: #fff;
    display: inline-block;
    font-weight: 500;
    height: 38px;
    line-height: 38px;
    margin: 0 10px 0 0;
    text-align: center;
    text-decoration: none;
    text-transform: uppercase;
    vertical-align: middle;
    width: 38px;
    position: relative;
    white-space: nowrap;
}
table.table td h2 span {
    color: #888;
    display: block;
    font-size: 12px;
    margin-top: 3px;
}
.avatar > img {
    border-radius: 50%;
    display: block;
    overflow: hidden;
    width: 100%;
}
img {
    vertical-align: middle;
    border-style: none;
}
.dataTables_filter, .dataTables_info { display: none; }
@media screen and (max-width : 1920px){
  .div-only-mobile{
  visibility:hidden;
  display: none;
  }
}
@media screen and (max-width : 906px){
 .desk{
  visibility:hidden;
  }
 .div-only-mobile{
  visibility:visible;
  display: block;
  }
  .viewtime{
      width: 200px !important;
  }
}
.timepickerinputs{cursor: pointer;}
.card-title{
    font-size: 13px;
}
.bg-success {
    color: #155724 !important;
    background-color: #d4edda !important;
    border-color: #c3e6cb !important;
}
.dropdown {
  /* position: relative;
  left: 50px;
  top: 50px; */
}
</style>
@php

date_default_timezone_set('Asia/Manila');
$ddate = date('Y-m-d');
$date = new DateTime($ddate);
$week = $date->format("W");
@endphp
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-chart-line nav-icon"></i> Absences</h4>
          <!-- <h1>Attendance</h1> -->
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Absences</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <div class="row">
      <div class="col-md-4">
        <div class="card card-success collapsed-card" style="border: none; font-size: 11px;">
            <div class="card-header p-0">
                <button type="button" class="btn btn-sm btn-primary btn-tools btn-block m-0" id="btn-offense-collapse" data-card-widget="collapse"><i class="fa fa-plus"></i> Add Offense</button>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-2">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label>Title</label>
                        <input type="text" class="form-control form-control-sm" id="input-title"/>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label>Descrption</label>
                        <input type="text" class="form-control form-control-sm" id="input-description"/>
                    </div>
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-sm btn-primary" id="btn-submit-offense">Submit</button>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
          </div>
          <div id="container-offense" style="font-size: 14px;">
            @if(count($offenses)>0)
              @foreach($offenses as $offensekey => $offense)
              <div class="info-box bg-success p-1 card collapsed-card" style="border: none; box-shadow: unset !important;">
                {{-- <span class="info-box-icon">{{$offensekey+1}}</span> --}}
    
                <div class="info-box-content card-header">
                  <span class="info-box-number">{{$offense->title}}</span>
                  <span class="progress-description">
                      {{$offense->description}}
                  </span>
                  <div class="row">
                      <div class="col-md-12">
                          <button type="button" class="btn btn-sm btn-default btn-delete-offense" data-id="{{$offense->id}}" data-title="{{$offense->title}}" data-description="{{$offense->description}}"><i class="fa fa-trash-alt"></i></button>
                          <button type="button" class="btn btn-sm btn-default btn-edit-offense" data-id="{{$offense->id}}" data-title="{{$offense->title}}" data-description="{{$offense->description}}" data-card-widget="collapse"><i class="fa fa-edit"></i></button>
                      </div>
                  </div>
                </div>
                <div class="card-body p-1">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Title</label>
                            <input type="text" class="form-control form-control-sm" id="input-edit-title{{$offense->id}}" value="{{$offense->title}}"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Description</label>
                            <input type="text" class="form-control form-control-sm" id="input-edit-description{{$offense->id}}" value="{{$offense->description}}"/>
                        </div>
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-sm btn-success btn-submit-edit-offense" data-id="{{$offense->id}}">Save changes</button>
                        </div>
                    </div>
                </div>
                <!-- /.info-box-content -->
              </div>
              @endforeach
            @endif
          </div>
      </div>
      <div class="col-md-8">
          <div class="card" style="border: none;">
              <div class="card-body">
                  <div class="row">
                        <div class="col-md-8">
                            <label for="week">Choose a week:</label>
<br/>
                            <input type="week" name="week" id="select-week" max="{{date('Y')}}-W{{$week}}" required>
                        </div>
                        <div class="col-md-4 text-right">
                            <label>&nbsp;</label><br/>
                            <button type="button" class="btn btn-primary btn-sm" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                        </div>
                  </div>
              </div>
          </div>
          <div id="container-results" class="pr-1 pl-1"></div>
      </div>
  </div>
@endsection
@section('footerscripts')
<script>
   $(function () {
    var table =  $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']],
        // scrollY:        "500px",
        // scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   true
        });
        // / #myInput is a <input type="text"> element
        $('#myInput').on( 'keyup', function () {
            table.search( this.value ).draw();
        } );
    });
    
    $(document).ready(function(){
        $('#btn-generate').hide()
        $('#select-week').on('change', function(){
            $('#btn-generate').show()
        })
        function getoffenses()
        {
            
            $.ajax({
                url: '/hr/absences/index',
                type:"GET",
                data: {
                    action: 'getoffenses'
                },
                success:function(data) {
                    $('#container-offense').empty()
                    $('#container-offense').append(data)
                }
            });
        }
        $('#btn-submit-offense').on('click', function(){
            var title = $('#input-title').val();
            var description = $('#input-description').val();
            var validation = 0;
            if(title.replace(/^\s+|\s+$/g, "").length == 0){
                $('#input-title').css('border','1px solid red')
                validation = 1;
            }else{
                $('#input-title').removeAttr('style');
            }
            if(title.replace(/^\s+|\s+$/g, "").length == 0){
                $('#input-description').css('border','1px solid red')
                validation = 1;
            }else{
                $('#input-description').removeAttr('style');
            }
            if(validation == 0)
            {                
                $.ajax({
                        url: '/hr/absences/offense',
                        type:"GET",
                        data:{
                            action: 'addoffense',
                            title  : title,
                            description  : description
                        },
                        success:function(data) {
                            if(data == '1')
                            {
                                toastr.success('Added successfully!','Add Offense')
                                $('#input-title').val('')
                                $('#input-description').val('')
                                $('#btn-offense-collapse').click()
                                getoffenses()
                            }else{
                                toastr.danger('Offense exists!','Add Offense')
                            }
                        }
                    });
            }else{
                toastr.warning('Field empty!','Add Offense')
            }
        })
        $('.btn-delete-offense').on('click', function(){
            var offenseid = $(this).attr('data-id')
            console.log(offenseid)
            Swal.fire({
                title: 'Are you sure you want to delete this offense?',
                type: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Delete',
                showCancelButton: true,
                allowOutsideClick: false
            }).then((confirm) => {
                if (confirm.value) {

                    $.ajax({
                        url: '/hr/absences/offense',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            action: 'deleteoffense',
                            offenseid :   offenseid
                        },
                        success: function(data){
                            if(data == 1)
                            {
                                getoffenses()
                                toastr.success('Deleted successfully!','Delete Offense')
                            }else{
                                toastr.error('Something went wrong!','Delete Offense')
                            }
                        }
                    })
                }
            })
        })
        $(document).on('click','.btn-submit-edit-offense', function(){
            var offenseid = $(this).attr('data-id')
            var title = $('#input-edit-title'+offenseid).val();
            var description = $('#input-edit-description'+offenseid).val();
            var validation = 0;
            if(title.replace(/^\s+|\s+$/g, "").length == 0){
                $('#input-edit-title'+offenseid).css('border','1px solid red')
                validation = 1;
            }else{
                $('#input-edit-title'+offenseid).removeAttr('style');
            }
            if(title.replace(/^\s+|\s+$/g, "").length == 0){
                $('#input-edit-description'+offenseid).css('border','1px solid red')
                validation = 1;
            }else{
                $('#input-edit-description'+offenseid).removeAttr('style');
            }
            if(validation == 0)
            {                
                $.ajax({
                        url: '/hr/absences/offense',
                        type:"GET",
                        data:{
                            action: 'editoffense',
                            offenseid  : offenseid,
                            title  : title,
                            description  : description
                        },
                        success:function(data) {
                            if(data == '1')
                            {
                                toastr.success('Added successfully!','Edit Offense')
                                $('#input-edit-title'+offenseid).val('')
                                $('#input-edit-description'+offenseid).val('')
                                getoffenses()
                                $('.btn-edit-offense[data-id="'+offenseid+'"]').click()
                            }else{
                                toastr.danger('Offense exists!','Edit Offense')
                            }
                        }
                    });
            }else{
                toastr.warning('Field empty!','Edit Offense')
            }
        })
        $('#btn-generate').on('click', function(){
            var week = $('#select-week').val()
            
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                closeOnClickOutside: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                }
            })  
            $.ajax({
                url: '/hr/absences/generate',
                type:"GET",
                data:{
                    // action: 'editoffense',
                    week  : week
                },
                success:function(data) {
                    $('#container-results').empty();
                    $('#container-results').append(data);
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    $('body').on("click", ".dropdown-menu", function (e) {
                        $(this).parent().is(".open") && e.stopPropagation();
                    });
                    $("input[type='checkbox'].justone").change(function(){
                        var empid = $(this).attr('data-empid');
                        var offenseid = $(this).attr('data-offenseid');
                        console.log(empid)
                        console.log(offenseid)
                        var a = $("input[type='checkbox'].justone");
                        if(a.length == a.filter(":checked").length){
                            $('.selectall').prop('checked', true);
                            $(".select-text").html(' Deselect');
                        }
                        else {
                            $('.selectall').prop('checked', false);
                            $(".select-text").html(' Select');
                        }
                        var total = $('input[name="options'+empid+'[]"]:checked').length;
                        var offensetext = '';
                        if(total == 0)
                        {
                        $(".dropdown-text"+empid).html('Mark Offense');
                        }
                        else if(total == 1)
                        {
                        $(".dropdown-text"+empid).html('(' + total + ') Offense');
                        }else if(total > 1)
                        {
                        $(".dropdown-text"+empid).html('(' + total + ') Offenses');
                        }
                        var offensestatus = 0;
                        if ($(this).is(':checked')) {
                            offensestatus = 1;
                        }
                        $.ajax({
                                url: '/hr/absences/markoffense',
                                type:"GET",
                                data:{
                                    empid  : empid,
                                    offenseid  : offenseid,
                                    weekid: $('#select-week').val(),
                                    status: offensestatus
                                },
                                success:function(data) {
                                    // if(data == '1')
                                    // {
                                    //     toastr.success('Added successfully!','Edit Offense')
                                    //     $('#input-edit-title'+offenseid).val('')
                                    //     $('#input-edit-description'+offenseid).val('')
                                    //     getoffenses()
                                    //     $('.btn-edit-offense[data-id="'+offenseid+'"]').click()
                                    // }else{
                                    //     toastr.danger('Offense exists!','Edit Offense')
                                    // }
                                }
                            });
                    });
                }
            });
        })
        $(document).on("keyup","#input-search", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".card-each-employee").each(function() {
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
        $(document).on('click','.btn-exporteachemp', function(){
            var employeeid = $(this).attr('data-id');
            window.open('/hr/absences/generate?export=1&exportclass=1&employeeid='+employeeid+'&week='+$('#select-week').val(),'_blank');
        })
        $(document).on('click','#btn-exporttopdf', function(){
            var employeeid = $(this).attr('data-id');
            window.open('/hr/absences/generate?export=1&exportclass=2&week='+$('#select-week').val(),'_blank');
        })
    })
  </script>
@endsection

