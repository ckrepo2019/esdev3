@php
      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
      if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
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
            .upload #image_logo{
                  padding: 7px 0px 30px 7px!important;
            }
            .image-container {
                  position: relative;
            }

            .camera-icon {
                  position: absolute;
                  top: 50%;
                  left: 52%;
                  transform: translate(-50%, -50%);
                  display: none;
            }

            .image-container:hover .camera-icon {
                  display: block;
                  transition: all 0.2s ease-in-out;
            }

            .fa-camera {
                  font-size: 70px;
                  color: rgb(255, 255, 255);
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
      // $usertype = DB::table('usertype')
      //       ->select('id', 'utype as text')
      //       ->where('deleted', 0)
      //       ->get();

      $usertype = DB::table('usertype')
            ->where('deleted', 0)
            ->get();
@endphp

<section class="content-header">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-sm-6">
                        <h1>School List</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">School List</li>
                  </ol>
                  </div>
            </div>
            {{-- <div class="row">
                  <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-right" style="display:flex!importanrt">
                              <li><span class=""><span class="badge badge-info">M</span> - <b>Manual</b></span></li> &nbsp;&nbsp;&nbsp;
                              <li><span class=""><span class="badge badge-secondary">T</span> - <b>FAQ's</b></span> </li>
                        </ol>
                  </div>
            </div> --}}
      </div>
</section>

<section class="content pt-0">

      {{-- ========================================================================================================= --}}
      {{-- Modal --}}
      <div class="modal fade" id="modal_schoollogo">
            <div class="modal-dialog modal-md">
                  <div class="modal-content">
                        <div class="modal-header pb-2 pt-2">
                              <h4 class="modal-title">School Logo</h4>
                              
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span></button>
                        </div>
                        <form id="submitlogo" enctype="multipart/form-data">
                        @csrf
                              <div class="modal-body">
                                    <input type="text" id="logoid" name="logoid" hidden>
                                    <div class="row" style="justify-content: center!important;">
                                          <div class="form-group" id="hiddenimage">
                                                <img src="" id="hidimage" alt="" width="250px" height="250px">
                                          </div>
                                    </div>
                              </div>
                              <div class="modal-footer">
                                    <div class="col-md-9">
                                          {{-- <button class="btn btn-info btn-md" id="btn_logo_update"> Update Image</button> --}}
                                          <div class="input-group input-group-sm upload">
                                                <input type="file" class="form-control" name="image_logo" id="image_logo" >
                                          </div>
                                    </div>
                                    <div class="col-md-3 text-right">
                                          <div class="input-group" style="justify-content: end!important;">
                                                <button type="submit" class="btn btn-success" id="btn_update_logo">Update</button>
                                          </div>
                                    </div>
                              </div>
                        </form>
                  </div>
            </div>
      </div>



      <div class="modal fade" id="modal_addschool">
            <div class="modal-dialog modal-md">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2" id="headadd">
                        <h4 class="modal-title">Add School</h4>
                        
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-header pb-2 pt-2" id="headupdate">
                        <h4 class="modal-title">Update School Info</h4>
                        
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body p-0">
                        <div class="row">
                              <div class="col-md-12" style="font-size: 15px">
                                    <form id="submitfile" enctype="multipart/form-data">
                                          @csrf
                                          <div class="modal-body">
                                                <div id="imgg"></div>
                                                <input type="text" id="schoolid" name="idschool" hidden>
                                                {{-- <div class="form-group" style="text-align: justify!important;">
                                                      <label for="textare_topicdesc">Topic / Description</label>
                                                      <textarea required name="topdesc" class="form-control" id="textare_topicdesc" rows="3" onkeyup="this.value = this.value.toUpperCase();"></textarea>
                                                </div> --}}
                                                <div class="form-group">
                                                      <label for="">School Name</label>&nbsp;&nbsp;&nbsp;<span style="font-size: 11px; color: red" id="er_schoolname" value=""></span>
                                                      <input name="schoolname" type="text" class="form-control" id="input_schoolname" autocomplete="off"  onkeyup="this.value = this.value.toUpperCase();">
                                                </div>
                                                <div class="form-group">
                                                      <label for="">Abbreviation</label>&nbsp;&nbsp;&nbsp;<span style="font-size: 11px; color: red" id="er_abbrv" value=""></span>
                                                      <input name="abbr" type="text" class="form-control" id="input_abbrv" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                                                </div>
                                                <div class="form-group">
                                                      <label for="">School ID</label>&nbsp;&nbsp;&nbsp;<span style="font-size: 11px; color: red" id="er_schoolid" value=""></span>
                                                      <input name="schoolid" type="text" class="form-control" id="input_schoolid" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                                                </div>
                                                <div class="form-group">
                                                      <label for="">Essentiel Link</label>&nbsp;&nbsp;&nbsp;<span style="font-size: 11px; color: red" id="er_eslink" value=""></span>
                                                      <input name="eslink" type="text" class="form-control" id="input_eslink" autocomplete="off">
                                                </div>
                                                <div class="form-group">
                                                      <label for="">DB</label>&nbsp;&nbsp;&nbsp;<span style="font-size: 11px; color: red" id="er_db" value=""></span>
                                                      <input name="db" type="text" class="form-control" id="input_db" autocomplete="off">
                                                </div>
                                                {{-- <div class="form-group" id="hiddenimage">
                                                      <img src="" id="hidimage" alt="">
                                                </div> --}}
                                                <div class="input-group input-group-sm upload"><span style="font-size: 11px; color: red" id="er_file" value=""></span>
                                                      <input type="file" class="form-control" name="file" id="file" >
                                                </div>
                                          </div>
                                          <div class="modal-footer border-0">
                                                <div class="col-md-6">
                                                      <button class="btn btn-primary btn-md" id="btn_add"> Create</button>
                                                      <button class="btn btn-success btn-md" id="btn_update"> Update</button>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                      <button class="btn btn-danger btn-md" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                                                </div>
                                          </div>
                                    </form>  
                              </div>
                        </div>
                        
                    </div>
            </div>
            </div>
      </div>
      {{-- ========================================================================================================= --}}



      <div class="row">
            <div class="col-md-12">
                  <div class="card">
                        <div class="card-body">
                              <div class="row">
                                    <div class="col-md-3 text-left">
                                          <input type="text" class="form-control" id="search-input" placeholder="Search...">
                                    </div>
                                    <div class="col-md-3 text-left">
                                    </div>
                                    <div class="col-md-6 text-right">
                                          <button type="button" class="btn btn-primary" id="btn_addschool"><i class="fas fa-plus"></i> Add School</button>
                                    </div>
                              </div>
                              <br>
                              <input type="text" id="schooldataid" hidden>
                              <div class="row" id="data-list">
                                    

                              </div>
                              {{-- <div class="row">
                                    <div class="col-3 each-school" data-string="HOLY SPIRIT ACADEMY OF LAOAG<">
                                          <a href="/viewschool/1" class="small-box-footer">
                                              <div class="card shadow">
                                                  <div class="card-header">
                                                      <center><img src="http://essentiel.ck/assets/images/department_of_Education.png" onerror="this.onerror = null, this.src='http://essentiel.ck/assets/images/department_of_Education.png'" alt="" width="50%"></center>
                                                      <center><h1 style="font-size: 30px;">HSAL</h1></center>
                                                      <center><p style="font-size: 15px;">HOLY SPIRIT ACADEMY OF LAOAG</p></center>
                                                  </div>
                                              </div>
                                          </a>
                                    </div>
                                    <div class="col-3 each-school" data-string="HOLY SPIRIT ACADEMY OF LAOAG<">
                                          <a href="/viewschool/1" class="small-box-footer">
                                              <div class="card shadow">
                                                  <div class="card-header">
                                                      <center><img src="http://essentiel.ck/assets/images/department_of_Education.png" onerror="this.onerror = null, this.src='http://essentiel.ck/assets/images/department_of_Education.png'" alt="" width="50%"></center>
                                                      <center><h1 style="font-size: 30px;">HSAL</h1></center>
                                                      <center><p style="font-size: 15px;">HOLY SPIRIT ACADEMY OF LAOAG</p></center>
                                                  </div>
                                              </div>
                                          </a>
                                    </div>
                                    
                              </div> --}}
                        </div>
            
                  </div>
                  
            </div>
      </div>
      <div id="toast-container" class="toast-top-right"></div>
</section>

@endsection

@section('footerjavascript')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script>
      const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
      })
</script>
<script>
      $(document).ready(function() {

            $('#headadd').show()
            $('#headupdate').hide()
            $('#btn_add').add()
            $('#btn_update').hide()
            

            $('#modal_addschool').on('hidden.bs.modal', function () {
                  $('#er_schoolname').text('');
                  $('#input_schoolname').css('border', '1px solid #ced4da')
                  $('#updatesubmitfile').attr('id', 'submitfile');
                  $('#input_schoolname').val('')
                  $('#input_abbrv').val('')
                  $('#input_eslink').val('')
                  $('#input_db').val('')
                  $('#file').val('')
                  $('#input_schoolid').val('')
            })
            $('#modal_schoollogo').on('hidden.bs.modal', function () {
                  $('#image_logo').val('')
            })

            // Variable declaration



            // Calling Functions
            loadschools()


            // Events 
            
            // + Add School button
            $(document).on('click', '#btn_addschool', function() {
                  $('#updatesubmitfile').attr('id', 'submitfile');
                  $('#file').show();
                  $('#headadd').show()
                  $('#headupdate').hide()
                  $('#btn_add').show()
                  $('#btn_update').hide()
                  $('#modal_addschool').modal('show')
            })

            // button create is click inside Add School Modal
            $(document).on('click', '#btn_add', function() {
                  addschooldetails()
            })

            // click edit icon
            $(document).on('click', '#btn_edit_school', function(){
                  var schooldata_id = $(this).attr('schooldata_id')
                  $('#submitfile').attr('id', 'updatesubmitfile');
                  $('#schooldataid').val(schooldata_id)
                  $('#file').hide();
                  $('#headadd').hide()
                  $('#headupdate').show()
                  $('#btn_add').hide()
                  $('#btn_update').show()
                  edit_school(schooldata_id)
            })

            // click delete icon
            $(document).on('click', '#btn_delete_school', function(){
                  var schooldata_id = $(this).attr('schooldata_id')

                  $('#schooldataid').val(schooldata_id)

                  delete_school(schooldata_id)
            })
            

            // click update button
            $(document).on('click', '#btn_update', function(){
                 update_school()
            })
            
            // click logo
            $(document).on('click', '#logo', function(){
                  var logopath = $(this).attr('schoollogo')
                  var logo_id = $(this).attr('logoid')

                  $('#logoid').val(logo_id)
                  // console.log(logopath);
                  $('#hidimage').attr('src', '/'+logopath)
                  $('#modal_schoollogo').modal('show')
            })
            
            // update logo button
            $(document).on('click', '#btn_update_logo', function(){
                  update_logo()
            })
            
            // load data functions
            // load schools

            function loadschools() {

                  $.ajax({
                        type: "GET",
                        url: "/schoollist/setup/loadschools",
                        // data: "data",
                        // dataType: "dataType",
                        success: function (data) {
      
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
                                          // return item.schoolname.toLowerCase().indexOf(query) !== -1;
                                          return item.abbr.toLowerCase().indexOf(query) !== -1 || item.schoolname.toLowerCase().indexOf(query) !== -1;
                                    });

                                    // Display the filtered data on the page
                                    displayData(filteredData);
                              });
                        }
                  });
            }


            // ajax functions
            function edit_school(schooldata_id){

                  $.ajax({
                        type: "GET",
                        url: "/schoollist/setup/edit_school",
                        data: {
                              schooldata_id : schooldata_id
                        },
                        success: function (data) {
                              console.log(data);

                              $('#input_schoolname').val(data[0].schoolname)
                              $('#input_abbrv').val(data[0].abbr)
                              $('#input_eslink').val(data[0].eslink)
                              $('#input_db').val(data[0].db)
                              $('#input_schoolid').val(data[0].schoolid)
                              $('#schoolid').val(data[0].id)


                              $('#modal_addschool').modal('show')
                        }
                  });

                  
            }   
            
            // update school

            function update_school(){
                  var schoolid = $('#schoolid').val()

                  $('#updatesubmitfile').submit( function(e){
                        var valid_data = true;
                        var inputs = new FormData(this)


                        $.ajax({
                              url: '/schoollist/setup/update_schoolinfo',
                              type:'POST',
                              data: inputs,
                              processData: false,
                              contentType: false,
                              success:function(data) {

                                    console.log(data);
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                          
                                    }else{
                                          $('#modal_addschool').modal('hide')
                                          loadschools()
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                    }
                              }
                        })

                        e.preventDefault();

                  })
            }

            // update logo

            function update_logo(){

                  // $('#submitlogo').submit( function(e){
                  //       var valid_data = true;
                  //       var inputs = new FormData(this)

                  //       // Swal.fire({
                  //       //       text: 'Are you sure you want to Update School Logo?',
                  //       //       type: 'warning',
                  //       //       showCancelButton: true,
                  //       //       confirmButtonColor: '#3085d6',
                  //       //       cancelButtonColor: '#d33',
                  //       //       confirmButtonText: 'Update'
                  //       // }).then((result) => {
                  //       //       if (result.value) {
                                    
                  //       //       }     
                  //       // })

                  //       $.ajax({
                  //             url: '/schoollist/setup/update_logo',
                  //             type:'POST',
                  //             data: inputs,
                  //             processData: false,
                  //             contentType: false,
                  //             success:function(data) {

                  //                   if(data[0].status == 0){
                  //                         Toast.fire({
                  //                               type: 'error',
                  //                               title: data[0].message
                  //                         })
                                          
                  //                   }else{
                  //                         $('#modal_schoollogo').modal('hide')
                  //                         loadschools()
                  //                         Toast.fire({
                  //                               type: 'success',
                  //                               title: data[0].message
                  //                         })
                  //                   }
                  //             },
                  //             error: function (response) {
                  //                   var errors = response.responseJSON.errors;

                  //                   if(errors) {
                  //                         var errorString = '<ul class="text-left">';
                  //                         $.each(errors, function(key, value) {
                  //                               errorString += '<li>' + value + '</li>';
                  //                         });
                  //                         errorString += '</ul>';
                  //                         Swal.fire({
                  //                               type: 'error',
                  //                               title: 'Oppss..',
                  //                               html: errorString,
                  //                               toast: true,
                  //                               position: 'top-end',
                  //                               timer: 3000,
                  //                               showConfirmButton: false,
                  //                               showCloseButton: true
                  //                         });
                  //                   }
                  //             }
                  //       })

                  //       e.preventDefault();

                  // })

                  $('#submitlogo').submit( function(e){
                        var inputs = new FormData(this)

                        
                        Swal.fire({
                              text: 'Are you sure you want to Update School Logo?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Update'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          url: '/schoollist/setup/updatelogo',
                                          type:'POST',
                                          data: inputs,
                                          // dataType: 'json',
                                          processData: false,
                                          contentType: false,
                                          success:function(data) {
                                                if(data[0].status == 0){
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: data[0].message
                                                      })
                                                      
                                                }else{
                                                      $('#modal_schoollogo').modal('hide')
                                                      loadschools()
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: data[0].message
                                                      })
                                                }
                                          },
                                          error: function (response) {
                                                var errors = response.responseJSON.errors;

                                                if(errors) {
                                                      var errorString = '<ul class="text-left">';
                                                      $.each(errors, function(key, value) {
                                                            errorString += '<li>' + value + '</li>';
                                                      });
                                                      errorString += '</ul>';
                                                      Swal.fire({
                                                            type: 'error',
                                                            title: 'Oppss..',
                                                            html: errorString,
                                                            toast: true,
                                                            position: 'top-end',
                                                            timer: 3000,
                                                            showConfirmButton: false,
                                                            showCloseButton: true
                                                      });
                                                }
                                          }
                                    })
                              }     
                        })
                        e.preventDefault();

                  })
            }
            

            // Click Add School
            function addschooldetails(){
                  // var valid_data = true
                  // var schoolname = $('#input_schoolname').val()
                  // var abbr = $('#input_abbrv').val()
                  // var eslink = $('#input_eslink').val()
                  // var db = $('#input_db').val()

                  $('#submitfile').submit( function(e){
                        var valid_data = true;
                        var inputs = new FormData(this)


                        $.ajax({
                              url: '/schoollist/setup/addschoolinfo',
                              type:'POST',
                              data: inputs,
                              processData: false,
                              contentType: false,
                              success:function(data) {

                                    console.log(data);
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                          
                                    }else{
                                          $('#modal_addschool').modal('hide')
                                          loadschools()
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                    }
                              },
                              error: function (response) {
                                    var errors = response.responseJSON.errors;
                                    // if (errors.schoolname) {
                                    //       $('#er_schoolname').text('School Name Field is Required');
                                    //       $('#input_schoolname').css('border', '1px solid red')
                                    // } else {
                                    //       $('#er_schoolname').text('');
                                    //       $('#input_schoolname').css('border', '1px solid #ced4da')
                                    // }
                                    // if (errors.abbr) {
                                    //       $('#er_abbrv').text('Abbreviation Field is Required');
                                    //       $('#input_abbrv').css('border', '1px solid red')
                                    // } 

                                    if(errors) {
                                          var errorString = '<ul class="text-left">';
                                          $.each(errors, function(key, value) {
                                                errorString += '<li>' + value + '</li>';
                                          });
                                          errorString += '</ul>';
                                          Swal.fire({
                                                type: 'error',
                                                title: 'Oppss..',
                                                html: errorString,
                                                toast: true,
                                                position: 'top-end',
                                                timer: 3000,
                                                showConfirmButton: false,
                                                showCloseButton: true
                                          });
                                    }
                              }
                        })

                        e.preventDefault();

                  })
                  
            }


            // delete school

            function delete_school(schooldata_id){

                  Swal.fire({
                        // text: '',
                        html: 'Are you sure you want to remove School?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                    type: "GET",
                                    url: "/schoollist/setup/delete_school",
                                    data: {
                                          schooldata_id : schooldata_id
                                    },
                                    success: function (data) {
                                          if(data[0].status == 0){
                                                      Toast.fire({
                                                      type: 'error',
                                                      title: data[0].message
                                                })
                                                
                                          }else{
                                                loadschools()
                                                Toast.fire({
                                                      type: 'success',
                                                      title: data[0].message
                                                })
                                          }
                                    }
                              });
                        }
                  })
  
            }

            // display functions

            function displayData(data) {
                  var html = '';
                  $.each(data, function(index, item) {
                        html += '<div class="col-3 each-school">' +
                              // ' <a href="/viewschool/1" class="small-box-footer">' +
                              ' <div class="card shadow">'+
                                          ' <div class="card-header"  style="height: 300px;">'+
                                                '<div style="">'+
                                                      '<a href="javascript:void(0)" class="text-left" tooltip="Edit Info"  schooldata_id="'+item.id+'" id="btn_edit_school"><i class="fas fa-edit"></i></a>&nbsp;&nbsp;' +
                                                      '<a href="javascript:void(0)" tooltip="Remove School" schooldata_id="'+item.id+'" id="btn_delete_school"><i class="text-danger fas fa-trash"></i></a>' +
                                                '</div>' +
                                                '<a href="javascript:void(0)"  id="logo" logoid="'+item.id+'" schoollogo="'+item.schoollogo+'"><div class="image-container">'+
                                                      '<center><img src="/'+item.schoollogo+'" alt="" width="150px" height="150px"></center>'+
                                                      '<span class="camera-icon"><i class="fa fa-camera"></i></span>'+
                                                '</div> </a>'+
                                                ' <center><h1 style="font-size: 30px;">'+item.abbr+'</h1></center>'+
                                                ' <center><p style="font-size: 15px;">'+item.schoolname+'</p></center>'+
                                          ' </div>'+
                                    ' </div>'+
                              // ' </a>'+
                        ' </div>';
                  });
                  $('#data-list').html(html);






                  


            }

            
      });
</script>
     

@endsection


