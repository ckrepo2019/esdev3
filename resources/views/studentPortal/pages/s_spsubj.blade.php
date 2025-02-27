@php
      if(auth()->user()->type == 7){
            $extend = 'studentPortal.layouts.app2';
      }else if(auth()->user()->type == 9){
            $extend = 'parentsportal.layouts.app2';
      }
@endphp

@extends($extend)
@php
    $sy = DB::table('sy')->orderBy('sydesc')->get();
@endphp

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            border: 0 !important;
        }
    </style>

    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
            font-weight: normal;
        }
     
    </style>

@endsection


@section('content')


<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Student Remedial Class</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Student Remedial Class</li>
            </ol>
            </div>
        </div>
    </div>
</section>
<section class="content pt-0">
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-12 col-sm-12 col-md-12">
                <div class="info-box">
                  <div class="info-box-content">
                    <span class="info-box-number">
                        <div class="row">
                            <div class="col-md-3">
                                 <h5><i class="fa fa-filter"></i> Filter</h5> 
                            </div>
                            <div class="col-md-9">
                                  <h5 class="float-right">Active S.Y.: {{collect($sy)->where('isactive',1)->first()->sydesc}}</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label for="" class="mb-1">School Year</label>
                                <select class="form-control form-control-sm" id="filter_sy" >
                                   
                                    @foreach ($sy as $item)
                                        @php
                                            $selected = '';
                                            if($item->isactive == 1){
                                                $selected = 'selected="selected"';
                                            }
                                        @endphp
                                        <option value="{{$item->id}}" {{$selected}} value="{{$item->id}}">{{$item->sydesc}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </span>
                  </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12" style="font-size:.8rem !important">
                                <table class="table-sm table" id="remedial_classtable">
                                    <thead>
                                        <tr>
                                            <th width="20%" style="font-size:.7rem !important">Section & Grade Level</th>
                                            <th width="25%">Subject</th>
                                            <th width="35%">Schedule</th>
                                            <th width="20%" class="text-center">Grades</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



    <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>


<script>

     $(document).ready(function(){

        $(document).on('change','#filter_sy',function(){
            getRemedialClass()
        })

        $('#filter_sy').select2()

        var remedialClass  = []
        getRemedialClass()
     

        function getRemedialClass(){
            $.ajax({
                  type:'GET',
                  url: '/api/student/remedialclass',
                  data:{
                        syid:$('#filter_sy').val(),
                  },
                  success:function(data) {
                        remedialClass = data
                        remedialClassDatatable()
                      
                  }
            })
        }
     
        function remedialClassDatatable(){

            $("#remedial_classtable").DataTable({
                        destroy: true,
                        data:remedialClass,
                        autoWidth: false,
                        stateSave: true,
                        lengthChange : false,
                        bPaginate: false,
                        bInfo : false,
                        columns: [
                                    { "data": null},
                                    { "data": null},
                                    { "data": null},
                                    { "data": null},
                              ],
                        columnDefs: [
                              {
                                    'targets': 0,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        var text = '<a class="mb-0">'+rowData.sectionname+'</a>';
                                            text += '<p class="mb-0" style="font-size:.7rem">'+rowData.levelname+'</p>'
                                        

                                        $(td)[0].innerHTML =  text
                                        $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 1,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        var text = '<a class="mb-0">'+rowData.subjdesc+'</a>';
                                            text += '<p class="mb-0" style="font-size:.7rem">'+rowData.subjcode+'</p>'
                                        

                                        $(td)[0].innerHTML =  text
                                        $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 2,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        
                                        var text = '';
                                        
                                        $.each(rowData.sched,function(a,b){
                                            text += '<a class="mb-0" style="font-size:.9rem">'+b.time + ' [ ' +b.day +' ] </a>'+'<p style="font-size:.8rem" class="mb-0">Teacher: ' + b.teacher+'</p>'
                                        })

                                        $(td)[0].innerHTML =  text
                                        $(td).addClass('align-middle')
                                        
                                    }
                              },
                              {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                         var text = `<table class="table table-sm mb-0" style="font-size:.8rem !important">
                                                            <tr>
                                                                <td width="50%" class="text-center  p-0" style="border-top: 0"><b>Q1</b> : `+rowData.q1+`</td>
                                                                <td width="50%" class="text-center  p-0" style="border-top: 0"><b>Q2</b> : `+rowData.q2+`</td>
                                                            </tr>
                                                            <tr>
                                                                <td width="50%" class="text-center p-0" style="border-top: 0"><b>Q3</b> : `+rowData.q3+`</td>
                                                                <td width="50%" class="text-center p-0" style="border-top: 0"><b>Q4</b> : `+rowData.q4+`</td>
                                                            </tr>
                                                        </table>`

                                        $(td)[0].innerHTML =  text
                                        $(td).addClass('align-middle')
                                        $(td).addClass('p-0')
                                    }
                              },
                            
                        ],
                        
                  });
        }
    })
</script>

@endsection
