@php

$check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

if(Session::get('currentPortal') == 3){
      $extend = 'registrar.layouts.app';
}else if(auth()->user()->type == 17){
      $extend = 'superadmin.layouts.app2';
}else if(auth()->user()->type == 10){
      $extend = 'hr.layouts.app';
}else if(Session::get('currentPortal') == 7){
      $extend = 'studentPortal.layouts.app2';
}else if(Session::get('currentPortal') == 6){
      $extend = 'adminPortal.layouts.app2';
}else if(Session::get('currentPortal') == 9){
      $extend = 'parentsportal.layouts.app2';
}else if(Session::get('currentPortal') == 2){
      $extend = 'principalsportal.layouts.app2';
}else if(Session::get('currentPortal') == 1){
      $extend = 'teacher.layouts.app';
}else if ( Session::get('currentPortal') == 14){
      $extend = 'deanportal.layouts.app2';
}else if ( Session::get('currentPortal') == 16){
      $extend = 'chairpersonportal.layouts.app2';
}else{
    if(isset($check_refid->refid)){
        if($check_refid->refid == 27){
                $extend = 'academiccoor.layouts.app2';
        }
    }else{
        $extend = 'general.defaultportal.layouts.app';
    }
}
@endphp

@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }
            .no-border-col{
                  border-left: 0 !important;
                  border-right: 0 !important;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }
            #datatable_1 tbody tr:hover {
                  background-color: rgb(233, 248, 255) !important;
            }
            .select2 ul {
                  
            }
            .select2 ul > .select2-selection__choice{
                  color: rgb(59, 59, 59) !important;
                  background-color: #fff !important;
            }
            .upload #file{
                  padding: 10px 0px 35px 10px!important;
            }
            .info-box {
                  box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
                  border-radius: 0.25rem;
                  background: #fff;
                  display: -ms-flexbox;
                  display: flex;
                  margin-bottom: 0 !important;
                  min-height: 0!important;
                  padding: 0.5rem;
                  position: relative;
            }
            .carddd:hover {
                  box-shadow: rgba(0, 0, 0, 0.18) 0px 2px 4px!important;
                  }     
      </style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')
            ->select(
                  'sy.sydesc',
                  'sy.id',
                  'sy.isactive'
            )
            ->orderBy('sydesc')
            ->get(); 

      $semester = DB::table('semester')
            ->select(
                  'semester.id',
                  'semester.semester',
                  'semester.isactive'
            )
            ->get(); 

      $strand = DB::table('sh_strand')
            ->orderBy('sh_strand.strandname')
            ->where('sh_strand.active',1)
            ->where('sh_strand.deleted',0)
            ->select(
                  'sh_strand.strandname',
                  'sh_strand.strandcode',
                  'sh_strand.id'
            )
            ->get(); 

      $subject_gradessetup = DB::table('subject_gradessetup')
            ->where('deleted',0)
            ->select(
                  'description as text',
                  'id'
            )
            ->get();

      $userid = auth()->user()->id;

      $teacherid = DB::table('teacher')
            ->select('id')
            ->where('userid', $userid)
            ->get();

      $otherportalid = DB::table('faspriv')
            ->select('usertype')
            ->where('userid', $userid)
            ->get();
      
     
      $usertype = DB::table('users')
            ->select('usertype.id')
            ->join('usertype', 'users.type', '=', 'usertype.id')
            ->where('users.id', $userid)
            ->where('users.deleted', 0)
            ->get();

      

      $opid = [$usertype[0]->id];

      foreach ($otherportalid as $otherportal) {
            array_push($opid, $otherportal->usertype);
      }
      
      $faqscount = DB::table('userguide_detail')
            ->join('userguide', 'userguide_detail.descriptionid', '=', 'userguide.id')
            ->whereIn('userguide_detail.utype', $opid)
            ->where('userguide_detail.deleted', 0)
            ->where('userguide.filetype', 1)
            ->count();

      $manualcount = DB::table('userguide_detail')
            ->join('userguide', 'userguide_detail.descriptionid', '=', 'userguide.id')
            // ->where('userguide_detail.utype', $usertype[0]->id)
            ->whereIn('userguide_detail.utype', $opid)
            ->where('userguide_detail.deleted', 0)
            ->where('userguide.filetype', 2)
            ->count();
@endphp


<section class="content-header p-0" style="padding-top: 15px!important;">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>User Guide</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">User Guide</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>

<section class="content  pt-0 container-fluid">

      <div class="card" style="box-shadow: none!important;">
            <div class="card-body">
                  <div class="row">
                        <div class="col-md-4 col-sm-6 col-12" style="display: flex; align-items: flex-end;">
                              {{-- <div class="form-group">
                                    <label for="">Select Filetype</label>
                                    <select name="ftype" class="form-control form-control-sm" id="select_filetype"  style="font-size: 16px!important;">
                                          <option value="0" selected="selected">Select File Type</option>
                                          <option value="1">Faqs</option>
                                          <option value="2">Manual</option>
                                    </select>
                              </div> --}}
                              {{-- <label for="">&nbsp;</label> --}}
                              <select class="custom-select custom-select-md"  id="select_filetype" >
                                    <option value="0" selected="selected">Select File Type</option>
                                    <option value="1">FAQs</option>
                                    <option value="2">Manual</option>
                              </select>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12" style="display: flex; align-items: flex-end;">
                              <table class="p-0" width="100%" class="table" >
                                    <tr>
                                          <td width="12.5%"></td>
                                          <td  width="35%">
                                                <a href="javascript:void(0)" id="btn_faqs">
                                                      <div class="info-box" style="height: 38px; padding: 0.3rem!important;">
                                                            
                                                            <div class="info-box-content" style="padding-top: 3px!important;">
                                                                  <span class="p-0 info-box-text"><span class="badge badge-info mr-2">{{$faqscount}}</span>FAQ's</span>
                                                            </div>
                                                            
                                                      </div>
                                                </a>
                                          </td>
                                          <td width="5%"></td>
                                          <td width="35%">
                                                <a href="javascript:void(0)" id="btn_manual">
                                                      <div class="info-box" style="height: 38px; padding: 0.3rem!important;">
                                                            <div class="info-box-content" style="padding-top: 3px!important;">
                                                                  <span class="p-0 info-box-text"><span class="badge badge-info mr-2">{{$manualcount}}</span>MANUAL</span>
                                                            </div>
                                                      </div>
                                                </a>
                                          </td>
                                          <td width="12.5%"></td>
                                    </tr>
                              </table>
                        </div>
            
                        
                        <div class="col-md-4 col-sm-6 col-12" style="display: flex; align-items: flex-end;">
                              <input type="text" class="form-control" id="search-input" placeholder="Search...">
                        </div>
            
                        
                        
                        {{-- <div class="col-md-2 col-sm-6 col-12">
                              <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-question"></i></span>
                                    <div class="info-box-content">
                                          <span class="info-box-text">FAQ's</span>
                                          <span class="info-box-number">1,410</span>
                                    </div>
                              </div>
                        </div>
                        
                        <div class="col-md-2 col-sm-6 col-12">
                              <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-paste"></i></span>
                                    <div class="info-box-content">
                                          <span class="info-box-text">Manuals</span>
                                          <span class="info-box-number">410</span>
                                    </div>
                              </div>
                        </div>      --}}
                  </div>
            
                  <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                              {{-- <ul id="data-list">
                                    <li><a href="#">Masaya</a></li>
                              </ul> --}}
                              <div class="row" id="data-list"></div>
                        </div>
                  </div>
            
                  {{-- modal for pdf viewing --}}
                  {{-- when pdf is click --}}
                  <div class="modal fade" id="modal_faqspdf">
                  <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                              <div class="modal-body" id="faqspdf">
                                    
                              </div>
                        </div>
                  </div>
                  </div>
                  {{-- <div class="row">
                        <div class="col-md-6">
                              <div class="card">
                                    <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12 mt-10" style="margin-top: 15px!important;">
                                                <table width="100%" class="table table-sm table-bordered table-head-fixed " id="datatable1_faqs"  style="font-size:16px">
                                                <thead>
                                                      <tr>
                                                            <th width="85%">Frequently Ask Questions</th>
                                                            <th class="text-center" width="15%"></th>
                                                      </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                </table>
                                          </div>
                                    </div>
                                    </div>
                        
                              </div>
                              
                        </div>
            
                        <div class="col-md-6">
                              <div class="card">
                                    <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12 mt-10" style="margin-top: 15px!important;">
                                                <table width="100%" class="table table-sm table-bordered table-head-fixed " id="datatable2_usersmanual"  style="font-size:16px">
                                                <thead>
                                                      <tr>
                                                            <th width="85%">User's Manual</th>
                                                            <th class="text-center" width="15%"></th>
                                                      </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                </table>
                                          </div>
                                    </div>
                                    </div>
                        
                              </div>
                              
                        </div>
                  </div> --}}
            </div>
      </div>


      

    
</section>

@endsection

@section('footerjavascript')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script>
            $(document).ready(function() {
                  
                  var usertype = @json($usertype)[0]
                  var otherportalid = @json($otherportalid);
                  
                  var portals = []

                  $(otherportalid).each(function(i , b) {
                       var id = b.usertype;

                       portals.push(id)
                  })


                  console.log(portals);
                  

                  
                  var usertypeid = usertype.id;
                  portals.push(usertypeid)
                  var loadedfaqs = [];
                  var loadedusersmanual = [];

                  loadblank()
                  // loadfaqs()
                  // loadusersmanual()

                  // EVENTS
                  $(document).on('change', '#select_filetype', function(){
                        var filetype = $('#select_filetype').val()
                        
                        if (filetype == 1) {
                              loadfaqs()
                        } else if (filetype == 2){
                              loadusersmanual()
                        } else {
                              loadblank()
                        }
                  })

                  $(document).on('click','.btn_viewfaqspdf',function(){
                        viewfaqs()
                  })

                  $(document).on('click','.btn_viewusersmanualpdf',function(){
                        var filepath = $(this).attr('filepath')

                        viewusersmanual(filepath)
                  })
                  
                  //click button faqs
                  $(document).on('click','#btn_faqs',function(){
                        var filetype = $('#select_filetype').val(1)
                        loadfaqs()
                  })
                  
                  //click button manual
                  $(document).on('click','#btn_manual',function(){
                        var filetype = $('#select_filetype').val(2)
                        loadusersmanual()
                  })

                  //  FUNCTIONS
                  function loadblank(){
                        $('#search-input').attr('disabled', 'disabled')
                        var html = '';
                        html += '<div class="col-md-12">' +
                                    '<div class="card mb-3 shadow-sm">' +
                                          '<div class="card-body text-center">' +
                                                '<span>No Data Show</span>' +
                                          '</div>' +
                                    '</div>' +
                              '</div>';
                        $('#data-list').html(html);
                  }
                  //  Load Data
                  function loadfaqs(){
                        $('#search-input').removeAttr('disabled', 'disabled')
                        var usertype = usertypeid
                        var filetype = 1

                        $.ajax({
                              type: "GET",
                              url: "/userguide/loadfaqs",
                              data: {
                                    usertype : usertype,
                                    portals : portals,
                                    filetype : filetype
                              },
                              success: function (data) {
                                    loadedfaqs = data

                                    $('#faqscount').val(loadedfaqs.length)

                                    if (loadedfaqs.length == 0) {
                                          loadblank()
                                    } else {
                                          // console.log(loadedfaqs.length);
                                          // datatable1_faqs()


                                          // Store the data in a variable
                                          var allData = data;

                                          // Display the data on the page
                                          displayData(allData);

                                          // Add a listener to the search input field
                                          $('#search-input').on('keyup', function() {
                                                // Get the search query from the input field
                                                var query = $(this).val().toLowerCase();

                                                // Filter the data based on the search query
                                                var filteredData = allData.filter(function(item) {
                                                      return item.description.toLowerCase().indexOf(query) !== -1;
                                                });

                                                // Display the filtered data on the page
                                                displayData(filteredData);
                                          });

                                    }

                                    

                                   
                              }
                        });
                  }

                  function loadusersmanual(){
                        $('#search-input').removeAttr('disabled', 'disabled')
                        var usertype = usertypeid
                        var filetype = 2

                        $.ajax({
                              type: "GET",
                              url: "/userguide/loadusersmanual",
                              data: {
                                    usertype : usertype,
                                    portals : portals,
                                    filetype : filetype
                              },
                              success: function (data) {
                                    loadedusersmanual = data
                                    // datatable2_usersmanual()


                                    if (loadedusersmanual.length == 0) {
                                          loadblank()
                                    } else {
                                          // Store the data in a variable
                                          var allData = data;

                                          // Display the data on the page
                                          displayData(allData);

                                          // Add a listener to the search input field
                                          $('#search-input').on('keyup', function() {
                                                // Get the search query from the input field
                                                var query = $(this).val().toLowerCase();

                                                // Filter the data based on the search query
                                                var filteredData = allData.filter(function(item) {
                                                      return item.description.toLowerCase().indexOf(query) !== -1;
                                                });

                                                // Display the filtered data on the page
                                                displayData(filteredData);
                                          });
                                    }
                                    
                              }
                        });
                  }

                  // Function to display the data on the page
                  function displayData(data) {
                        var html = '';
                        $.each(data, function(index, item) {
                              html += '<div class="col-md-12">' +
                                                      '<div class="card mb-2 shadow-sm">' +
                                                            '<div class="card-body carddd">' +
                                                                  '<a href="javascript:void(0)"  class="btn_viewusersmanualpdf" filepath="'+item.filepath+'">'+ item.description +'</a><br>' +
                                                                  // '<p class="card-title">' + item.name + '</p>' +
                                                            '</div>' +
                                                      '</div>' +
                                                '</div>';
                        });
                        $('#data-list').html(html);
                  }

                  // VIEW PDF

                  function viewfaqs(){
                        $('#faqspdf').html('<object data="" height="500px" width="100%"></object>');;
                        $('#modal_faqspdf').modal('show');
                  }

                  function viewusersmanual(filepath){

                        // $('#faqspdf').html('<object id="pdfview" data="/'+filepath+'#toolbar=0&navpanes=0&scrollbar=0" height="800px" width="100%"  frameborder="0" scrolling="auto"" height="800px" width="100%"></object>');
                        $('#faqspdf').html('<object id="pdfview" data="/'+filepath+'" height="800px" width="100%"  frameborder="0" scrolling="auto"" height="800px" width="100%"></object>');
                        $('#modal_faqspdf').modal('show');
                  }

                  // Datatables
                  // function datatable1_faqs(){

                  //       $('#datatable1_faqs').DataTable({
                  //             destroy: true,
                  //             lengthChange: false,
                  //             scrollX: true,
                  //             autoWidth: true,
                  //             order: false,
                  //             data: loadedfaqs,
                  //             columns : [
                  //                   {"data" : "description"},
                  //                   {"data" : null}
                  //             ], 
                  //             columnDefs : [
                  //                   {
                  //                         'targets': 0,
                  //                         'orderable': true, 
                  //                         'createdCell':  function (td, cellData, rowData, row, col) {
                  //                         $(td).addClass('align-middle p-1')
                  //                         var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important">'+rowData.description+'</a>';
                  //                         $(td)[0].innerHTML =  text
                  //                         }
                  //                   },
                  //                   {
                  //                         'targets': 1,
                  //                         'orderable': true, 
                  //                         'createdCell':  function (td, cellData, rowData, row, col) {
                  //                         $(td).addClass('align-middle p-1 text-center')
                  //                         var text = '<a href="javascript:void(0)" style="font-size: 14px!important;" class="btn_viewusersmanualpdf" filepath="'+rowData.filepath+'"><i style="font-size: 15px" class="far fa-file-pdf"></i></a>';
                  //                         $(td)[0].innerHTML =  text
                  //                         }
                  //                   }

                  //             ]
                  //       })
                  // }

                  // function datatable2_usersmanual(){
                        
                  //       $('#datatable2_usersmanual').DataTable({
                  //             destroy: true,
                  //             lengthChange: false,
                  //             scrollX: true,
                  //             autoWidth: true,
                  //             order: false,
                  //             data: loadedusersmanual,
                  //             columns : [
                  //                   {"data" : "description"},
                  //                   {"data" : null}
                  //             ], 
                  //             columnDefs : [
                  //                   {
                  //                         'targets': 0,
                  //                         'orderable': true, 
                  //                         'createdCell':  function (td, cellData, rowData, row, col) {
                  //                         $(td).addClass('align-middle p-1')
                  //                         var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important">'+rowData.description+'</a>';
                  //                         $(td)[0].innerHTML =  text
                  //                         }
                  //                   },
                  //                   {
                  //                         'targets': 1,
                  //                         'orderable': true, 
                  //                         'createdCell':  function (td, cellData, rowData, row, col) {
                  //                         $(td).addClass('align-middle p-1 text-center')
                  //                         var text = '<a href="javascript:void(0)" style="font-size: 14px!important;" class="btn_viewusersmanualpdf" filepath="'+rowData.filepath+'"><i style="font-size: 15px" class="far fa-file-pdf"></i></a>';
                  //                         $(td)[0].innerHTML =  text
                  //                         }
                  //                   }

                  //             ]
                  //       })
                  // }



                 
            });
      </script>
      
@endsection


