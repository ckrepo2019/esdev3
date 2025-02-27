
@extends('registrar.layouts.app')
@section('content')
    <style>
        .modal {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  overflow: hidden;
}

* {
  box-sizing: border-box;
}
.modal-dialog {
  position: fixed;
  margin: 0;
  width: 100%;
  height: 100%;
  padding: 0;
  max-width: unset !important;
}

.modal-content {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  border: 2px solid #3c7dcf;
  border-radius: 0;
  box-shadow: none;
}

.modal-header {
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  height: 50px;
  padding: 10px;
  background: #6598d9;
  border: 0;
}

.modal-title {
  font-weight: 300;
  font-size: 2em;
  color: #fff;
  line-height: 30px;
}

.modal-body {
  position: absolute;
  top: 50px;
  bottom: 60px;
  width: 100%;
  font-weight: 300;
  overflow: auto;
}

.modal-footer {
  position: absolute;
  right: 0;
  bottom: 0;
  left: 0;
  height: 60px;
  padding: 10px;
  background: #f1f3f5;
}

        .tableFixHead          { overflow-y: auto !important; height: 100px !important; }
        .tableFixHead thead th { position: sticky !important; top: 0 !important; background-color: white !important;}
    </style>
    <section class="content-header">
        <div class="col-12">
            <h4>College</h4>
        </div>
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a  href="/schoolforms/college/index">School Forms</a></li>
                    <li class="breadcrumb-item active">Student Permanent Record</li>
                </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row mt-2">
                        <div class="alert alert-warning alert-dismissible col-12">
                            <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                            Still working on this page.
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label>Select School Year</label>
                            <select class="form-control" id="selectedschoolyear">
                                @foreach($schoolyears as $schoolyear)
                                    <option value="{{$schoolyear->id}}" @if($schoolyear->isactive == 1) selected @endif>{{$schoolyear->sydesc}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <label>Select Semester</label>
                            <select class="form-control" id="selectedsemester">
                                @foreach($semesters as $semester)
                                    <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 text-right">
                            &nbsp;
                        </div>
                        <div class="col-3 text-right">
                            <label>&nbsp;</label><br/>
                            <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="resultscontainer">
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-record" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" >Student Permanent Record</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" >
                    
                </div>
                <div class="modal-footer">
                    {{-- <div class="row"> --}}
                        <div class="col-6">
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="submit-newclose">Close</button>
                        </div>
                        <div class="col-6 text-right">
                            <button type="button" class="btn btn-primary" id="btn-export-pdf">Export PDF</button>
                            <button type="button" class="btn btn-primary" id="btn-export-excel">Export Excel</button>
                        </div>
                    {{-- </div> --}}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script>
        var selectedschoolyear = $('#selectedschoolyear').val();
        var selectedsemester   = $('#selectedsemester').val();
        $('#selectedschoolyear').on('change', function(){
            selectedschoolyear = $(this).val();
        })
        $('#selectedsemester').on('change', function(){
            selectedsemester = $(this).val();
        })
        $('#btn-generate').on('click',function(){
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            $.ajax({
                url: '/schoolforms/college/permanentrecordfilter',
                type: 'GET',
                data: {
                    selectedschoolyear  : selectedschoolyear,
                    selectedsemester    : selectedsemester
                },
                success:function(data){
                    $('#resultscontainer').empty();
                    $('#resultscontainer').append(data)
                    
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    // var $rows = $('.studentscontainer tr');
                    // $('#input-search').on('keyup', function(){
                    //     var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                        
                    //     $rows.show().filter(function() {
                    //         var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                    //         return !~text.indexOf(val);
                    //     }).hide();
                    // })
                    var table = $("#studentstable").DataTable({
                        // pageLength : 10,
                        // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
                        "bPaginate": false,
                        "bInfo" : false,
                        "bFilter" : true,
                        "order": [[ 1, 'asc' ]]
                    });
                    table.on( 'order.dt search.dt', function () {
                        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();
                }
            })
        })
        
        $(document).on('click', '.btn-view', function(){
            $('#modal-record').modal('show')
            var id          = $(this).attr('data-id');
            var courseid    = $(this).attr('data-courseid');
            var levelid     = $(this).attr('data-levelid');
            var sectionid   = $(this).attr('data-sectionid');

            $.ajax({
                url: '/schoolforms/college/permanentrecordgetrecord',
                type: 'GET',
                data: {
                    id                  : id,
                    courseid            : courseid,
                    levelid             : levelid,
                    sectionid           : sectionid,
                    selectedschoolyear  : selectedschoolyear,
                    selectedsemester    : selectedsemester
                },
                success:function(data){
                    // $('#resultscontainer').empty();
                    // $('#resultscontainer').append(data)
                    
                }
            })
        })
    </script>
<!-- fullCalendar 2.2.5 -->
@endsection
