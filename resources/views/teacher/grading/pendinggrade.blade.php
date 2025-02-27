@php
      if(Session::get('currentPortal') == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }else if(Session::get('currentPortal') == 1){
            $extend = 'teacher.layouts.app';
      }else if(auth()->user()->type == 2){
            $extend = 'principalsportal.layouts.app2';
      }else if(auth()->user()->type == 1){
            $extend = 'teacher.layouts.app';
      }else if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }
@endphp

@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

      <style>
       
            .table                      {width:1500px; font-size:90%; text-transform: uppercase; }
    
            /* .table thead th:first-child { position: sticky; left: 0; background-color: #fff; } */
            .table thead th:last-child  { 
                position: sticky; 
                right: 0; 
                background-color: #fff; 
                outline: 2px solid #dee2e6;
                outline-offset: -1px;
            }
    
            .table tbody th:last-child  { 
                position: sticky; 
                right: 0; 
                background-color: #fff; 
                outline: 2px solid #dee2e6;
                outline-offset: -1px;
                }
    
            .table tbody th:first-child  {  
                position: sticky; 
                left: 0; 
                background-color: #fff; 
                width: 150px !important;
                background-color: #fff; 
                outline: 2px solid #dee2e6;
                outline-offset: -1px;
            }
    
            .table thead th:first-child  { 
                    position: sticky; left: 0; 
                    width: 150px !important;
                    background-color: #fff; 
                    outline: 2px solid #dee2e6;
                    outline-offset: -1px;
            }
    
      
           
            td{
                text-align: center;
                cursor: pointer;
                vertical-align: middle !important;
            }
            .toast-top-right {
                top: 20%;
                margin-right: 21px;
            }
    
            .tableFixHead {
                overflow: auto;
                height: 100px;
            }
    
            .tableFixHead thead th {
                position: sticky;
                top: 0;
                background-color: #fff;
                outline: 2px solid #dee2e6;
                outline-offset: -1px;
               
            }
    
            .isHPS {
    
                position: sticky;
                top: 55px !important;
                background-color: #fff;
                outline: 2px solid #dee2e6 ;
                outline-offset: -1px;
               
            }
           
        </style>
    
        <style>
            
            .loader {
                margin: auto;
                border: 16px solid #f3f3f3;
                border-radius: 50%;
                border-top: 16px solid blue;
                border-bottom: 16px solid blue;
                width: 120px;
                height: 120px;
                -webkit-animation: spin 2s linear infinite;
                animation: spin 2s linear infinite;
            }
    
            @-webkit-keyframes spin {
                0% { -webkit-transform: rotate(0deg); }
                100% { -webkit-transform: rotate(360deg); }
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
    
            .shadow {
                box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                border: 0 !important;
            }
        </style>


      <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px ;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }
            .no-border-col{
                  border-left: 0 !important;
                  border-right: 0 !important;
            }
      </style>
@endsection


@section('content')
@php
   $sy = DB::table('sy')->orderBy('sydesc')->get(); 
   $semester = DB::table('semester')->get(); 
@endphp


<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Pending Grades</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Pending Grades</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="info-box shadow-lg">
                                          <div class="info-box-content">
                                                <div class="row">
                                                      <div class="col-md-2  form-group mb-0">
                                                            <label for="">School Year</label>
                                                            <select class="form-control select2" id="filter_sy">
                                                                  @foreach ($sy as $item)
                                                                        @if($item->isactive == 1)
                                                                              <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                                        @else
                                                                              <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                                        @endif
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                      <div class="col-md-6 form-group mb-0" >
                                                            <label for="">Subjects</label>
                                                            <select class="form-control  select2" id="filter_subjects">
                                                                  <option value="">Select Subject</option>
                                                            </select>
                                                      </div>
                                                      <div class="col-md-4 form-group mb-0" >
                                                            <label for="">Section</label>
                                                            <select class="form-control  select2" id="filter_section">
                                                                  <option value="">Select Section</option>
                                                            </select>
                                                      </div>
                                                </div>
                                               
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-3">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="card shadow">
                                          <div class="card-body  p-2">
                                                <div class="row mt-3" style=" font-size:11px !important">
                                                      <div class="col-md-5">
                                                            <strong><i class="fas fa-book mr-1"></i> Grade Level</strong>
                                                            <p class="text-muted" id="label_gradelevel">
                                                                  --
                                                             </p>
                                                      </div>
                                                      <div class="col-md-7">
                                                            <strong><i class="fas fa-book mr-1"></i> Section</strong>
                                                            <p class="text-muted" id="label_section">
                                                                  --
                                                             </p>
                                                      </div>
                                                </div>
                                                <div class="row" style=" font-size:11px !important">
                                                      <div class="col-md-12">
                                                            <strong><i class="fas fa-book mr-1"></i> Subject</strong>
                                                            <p class="text-muted mb-0" id="label_subject">
                                                                  --
                                                            </p>
                                                            <p class="text-danger mb-0" >
                                                                  <i id="label_subjectcode"> -- </i>
                                                            </p>
                                                      </div>
                                                </div>
                                                <div class="row" hidden>
                                                      <div id="subject_sem"></div>
                                                      <div id="subject_levelid"></div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                      <div class="col-md-12 form-group">
                                                            <label for="">Quarter</label>
                                                            <select name="" id="filter_quarter" class="form-control form-control-sm select2">
                                                                  <option value="" selected>Select Quarter</option>
                                                                  {{-- <option value="1">1st Quarter</option>
                                                                  <option value="2">2nd Quarter</option>
                                                                  <option value="3">3rd Quarter</option>
                                                                  <option value="4">4th Quarter</option> --}}
                                                            </select>
                                                      </div>
                                                </div>
                                                
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <button class="btn btn-info btn-block btn-sm mt-2" id="filter_button_1"><i class="fas fa-filter"></i> View Pending Grades</button>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                                  
                              </div>
                        </div>
                        
                  </div>
                  <div class="col-md-9">
                        <div class="card shadow">
                              <div class="card-body p-2">
                                    <div class="row">
                                          <div class="col-md-3">
                                                <select class="form-control form-control-sm" id="strand_holder" hidden>

                                                </select>
                                          </div>
                                          <div class="col-md-3">

                                          </div>
                                          <div class="col-md-6">
                                                <form 
                                                      action="/ecr/upload" 
                                                      id="upload_ecr" 
                                                      method="POST" 
                                                      enctype="multipart/form-data"
                                                      >
                                                      @csrf
                                                      <div class="row">
                                                            <div class="input-group input-group-sm col-md-12">
                                                                  <input disabled type="file" class="form-control" name="input_ecr" id="input_ecr">
                                                                  <span class="input-group-append">
                                                                  <button  class="btn btn-info btn-flat" id="upload_ecr_button" >Update ECR</button>
                                                                  </span>
                                                            </div>
                                                      </div>
                                                </form>
                                          </div>
                                    </div>
                                    <div class="row mt-2">
                                          <div class="col-md-12" style="height: 500px;">
                                                <div id="students">
                                                     
                                                </div>
                                          </div>
                                    </div>
                                   
                                      <div class="row mt-4">
                                          <div class="col-md-6">
                                              <button class="btn btn-success btn-sm" id="updateGrade" disabled> UPDATE GRADES</button>
                                              <p class="mb-0" id="failed_update"></p>
                                          </div>
                                          <div class="col-md-6 text-right ">
                                                  <button class="btn btn-success  btn-sm" id="btnSubmit" disabled> SUBMIT GRADES</button>
                                          </div>
                                      </div>
                                    {{-- <div class="row mt-4">
                                          <div class="col-md-12">
                                                <button class="btn btn-primary btn-sm" id="save_button_1" disabled><i class="fas fa-save"></i> Save Grades</button>
                                          </div>
                                          <div class="col-md-12 mt-2">
                                                <div class="progress progress-xxs">
                                                      <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                                        <span class="sr-only">60% Complete (warning)</span>
                                                      </div>
                                                </div>
                                          </div>
                                    </div> --}}
                              </div>
                        </div>
                  </div>
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
            $(document).ready(function(){

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  window.onbeforeunload = function(){
                        var updated_length = $('.updated').length
                        if(updated_length){
                              return ""
                        }
                       
                  };

                  $('.select2').select2()
            
                  getload()

                  var all_sched = []

                  function getload(){
                        $.ajax({
                              type:'GET',
                              url: '/teacher/pending/grade/list',
                              data:{
                                    syid:$('#filter_sy').val(),
                              },
                              success:function(data) {
                                    all_sched = data
                                    load_subjects()
                              }
                        })
                  }

                  function load_subjects(){
                        var grade_subjects = []
                        $.each(all_sched,function(a,b){
                              var count = grade_subjects.filter(x=>x.subjcode == b.subjcode && x.id == b.subjid).length
                              var pending = '';
                              $.each(all_sched.filter(x=>x.subjcode == b.subjcode && x.subjid == b.subjid) , function(c,d){
                                    if(d.with_pending){
                                          pending = '<div class="badge badge-warning">Pending</div>'
                                    }
                              })
                              if(count == 0){
                                    grade_subjects.push({
                                          'subjcode':b.subjcode,
                                          'id':b.subjid,
                                          'text': b.subjdesc+' '+pending,
                                          'html': b.subjdesc+' '+pending,
                                          'data-value':'dv1',
                                    })
                              }
                        })
                        $("#filter_subjects").empty()
                        $("#filter_section").empty()
                        $("#filter_gradelevel").empty()
                        $("#filter_subjects").append('<option value="">Select Grade Level</option>')
                        $('#students').empty()
                        $("#filter_subjects").select2({
                              data: grade_subjects,
                              tags: "true",
                              allowClear: true,
                              placeholder: "Select Subject",
                              escapeMarkup: function(markup) {
                                    return markup;
                              }
                        })
                  }

                  $(document).on('change','#filter_subjects',function(){

                        var updated_length = $('.updated').length

                        if(updated_length){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'You have unsaved grades!'
                              })
                              return false;
                        }
                        $('#students').empty()
                        $("#filter_section").empty()
                        $("#filter_gradelevel").empty()

                        var sections = []
                        var subjid = $(this).val()
                        var subjdesc = $("#filter_subjects option:selected").text().replace(' <div class="badge badge-warning">Pending</div>','');

                        $.each(all_sched.filter(x=>x.subjid == subjid && x.subjdesc == subjdesc),function(a,b){
                              var count = sections.filter(x=>x.id == b.sectionid).length
                            
                              if(count == 0){
                                    var pending = b.with_pending ? '<div class="badge badge-warning">Pending</div>':'' 
                                    if(count == 0){
                                          sections.push({
                                                'id':b.sectionid,
                                                'text': b.sectionname+' '+pending,
                                                'html': b.sectionname+' '+pending,
                                          })
                                    }
                              }
                        })
                        $("#filter_section").empty()
                        $("#filter_section").append('<option value="">Select Section</option>')
                        $("#filter_section").select2({
                              data: sections,
                              allowClear: true,
                              placeholder: "Select Section",
                              escapeMarkup: function(markup) {
                                    return markup;
                              }
                        })
                        $('#filter_quarter').empty()
                        $('#input_ecr').attr('disabled','disabled')
                        $('#updateGrade').attr('disabled','disabled')
                        $('#btnSubmit').attr('disabled','disabled')
                       
                        
                        $('#students').empty()
                        clear_data()
                  })

                  $(document).on('change','#filter_sy',function(){
                        var updated_length = $('.updated').length
                        if(updated_length){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'You have unsaved grades!'
                              })
                              return false;
                        }
                        $('#filter_quarter').empty()
                        $('#input_ecr').attr('disabled','disabled')
                        $('#updateGrade').attr('disabled','disabled')
                        $('#btnSubmit').attr('disabled','disabled')
                        $('#students').empty()
                        $('#filter_subjects').empty()
                        $('#filter_section').empty()
                  
                        clear_data()
                        getload()
                  })

                  $(document).on('change','#filter_section',function(){
                     
                        var updated_length = $('.updated').length

                        if(updated_length){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'You have unsaved grades!'
                              })
                              return false;
                        }


                        
                        $('#filter_quarter').empty()
                        $('#input_ecr').attr('disabled','disabled')
                        $('#updateGrade').attr('disabled','disabled')
                        $('#btnSubmit').attr('disabled','disabled')
                        $('#students').empty()
                        $('#filter_quarter').empty()

                        $('#label_gradelevel').text('--')
                        $('#label_section').text('--')
                        $('#label_subject').text('--')
                        $('#label_subjectcode').text('--')

                        if($('#filter_section').val() == ""){
                              return false      
                        }

                      
                        clear_data()
                        var subjid = $('#filter_subjects').val()
                        var sectionid = $('#filter_section').val()
                        var selected = all_sched.filter(x=>x.subjid == subjid && x.sectionid == sectionid )

                        $('#label_gradelevel').text(selected[0].levelname)
                        $('#label_section').text(selected[0].sectionname)
                        $('#label_subject').text(selected[0].subjdesc)
                        $('#label_subjectcode').text(selected[0].subjcode)

                        var temp_quarter = []


                        $("#filter_quarter").empty()
                        $("#filter_quarter").append('<option value="">Select Quarter</option>')
                        for(var x = 1; x <=  4; x++){
                              var check = selected[0].pending_quarter.filter(quarter=>quarter==x)
                              var pending = check.length ? '<div class="badge badge-warning">Pending</div>':'' 
                              temp_quarter.push({
                                    'id':x,
                                    'text': 'Quarter '+x+' '+pending,
                                    'html': 'Quarter '+x+' '+pending,
                              })
                        }

                        $("#filter_quarter").select2({
                              data: temp_quarter,
                              allowClear: true,
                              placeholder: "Select Quarter",
                              escapeMarkup: function(markup) {
                                    return markup;
                              }
                        })
                  })



                  $(document).on('click','#filter_button_1',function(){

                        $('#strand_holder').empty()

                        $('#students').empty();
                        if($('#filter_quarter').val() == ""){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No quarter selected!'
                              })
                              return false
                        }

                        var subjid = $('#filter_subjects').val()
                        var sectionid = $('#filter_section').val()
                        var temp_syid = $('#filter_sy').val()
                        var selected = all_sched.filter(x=>x.subjid == subjid && x.sectionid == sectionid )

                        if(selected[0].levelid == 14 || selected[0].levelid == 15 ){
                              $.each(selected[0].strand,function(a,b){
                                    $('#strand_holder').append('<option value="'+b.strandid+'">'+b.strandcode+'</option>')
                              })
                              $('#strand_holder').removeAttr('hidden')
                        }else{
                              $('#strand_holder').attr('hidden','hidden')
                        }

                        load_grades()
                  })

                  $(document).on('change','#strand_holder',function(){
                        var updated_length = $('.edited').length

                        if(updated_length){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'You have unsaved grades!'
                              })
                              return false;
                        }
                        load_grades()
                  })

                  
                  $(document).on('change','#filter_quarter',function(){
                        $('#students').empty();
                        $('#updateGrade').attr('disabled','disabled')
                        $('.exportrecord').attr('disabled','disabled')
                        $('#btnSubmit').attr('disabled','disabled')
                        $('.exclude').attr('disabled','disabled')
                        $('#input_ecr').attr('disabled','disabled')
                  })

                  function load_grades(){

                        var subjid = $('#filter_subjects').val()
                        var sectionid = $('#filter_section').val()
                        var syid = $('#filter_sy').val()
                        var quarter = $('#filter_quarter').val()
                        var selected = all_sched.filter(x=>x.subjid == subjid && x.sectionid == sectionid )
                        var levelid = selected[0].levelid
                        var strandid = $('#strand_holder').val()
                        var semid = 1

                        if(selected[0].levelid == 14 || selected[0].levelid == 15){
                              semid = selected[0].semid
                        }
                        $.ajax({
                              url: '/teacher/pending/grade/list/getgrades/'+subjid,
                              type:"GET",
                              data:{
                                    strandid:strandid,
                                    syid: syid,
                                    gradelevelid:levelid,
                                    sectionid: sectionid,
                                    subjectid :subjid,
                                    quarter :quarter,
                                    semid:semid
                              },
                              success:function(data) {
                                    $('#students').empty();
                                    if(data == 'NYP'){
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'Quarter '+( parseInt(curQuarter)-1 )+' grades are not yet approved.'
                                          })
                                    }
                                    else if(data == 'NGS'){
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'Grade setup is not configured.'
                                          })
                                    }
                                    else if(data == 'NSE'){
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'No student enrolled for this subject.'
                                          })
                                    }
                                    else{
                                          $('#students').append(data)
                                          $('.isHPS[data-id="hps"]').each(function() {
                                                var temp_id = $(this).attr('id');
                                                var data_hidden = $(this).attr('hidden');
                                                if (temp_id == undefined) {
                                                      var temp_class = $(this).attr('class')
                                                      var data_field = $(this).attr('data-field')
                                                      $(this).replaceWith('<th class="'+temp_class+' text-center" data-field="'+data_field+'" data-id="hps" '+data_hidden+'>' + $(this).text() + '</th>');
                                                }
                                          });

                                          if( $('.exclude').length > 0){
                                               // $('#updateGrade').removeAttr('disabled')
                                                $('.exportrecord').removeAttr('disabled')
                                                $('#btnSubmit').removeAttr('disabled')
                                                $('.exclude').removeAttr('disabled')
                                                $('#input_ecr').removeAttr('disabled')
                                          }else{
                                                Toast.fire({
                                                      type: 'warning',
                                                      title: 'No student found.'
                                                })
                                                $('#students').empty();
                                                $('#updateGrade').attr('disabled','disabled')
                                                $('.exportrecord').removeAttr('disabled','disabled')
                                                $('#btnSubmit').removeAttr('disabled','disabled')
                                                $('.exclude').removeAttr('disabled','disabled')
                                                $('#input_ecr').removeAttr('disabled','disabled')
                                          }

                                         

                                         
                                          
                                    }
                              }
                        })
                  }



                  function clear_data(){
                        for(var x=1;x<=4;x++){
                              $('.submit_grades[data-quarter="'+x+'"]').attr('hidden','hidden')
                              $('.submit_grades[data-quarter="'+x+'"]').attr('disabled','disabled')
                              $('.label_date[data-quarter="'+x+'"]').text('--')
                              $('.grade_status[data-quarter="'+x+'"]').text('--')
                        }
                        $('#label_gradelevel').text('--')
                        $('#label_section').text('--')
                        $('#label_subject').text('--')
                        $('#label_subjectcode').text('--')
                        $('#students').empty()
                      
                        $('#dq1').removeAttr('hidden')
                        $('#dq2').removeAttr('hidden')
                        $('#dq3').removeAttr('hidden')
                        $('#dq4').removeAttr('hidden')
                  }


                  function updateAllGradesDetail(){
                  
                              $('#failed_update').empty()
                              $('#failed_update').attr('hidden','hidden')

                              var inputedData = [];
                              var inputedDataHPS = [];
                              var p_length = 0;
                              var edited_count = 0;
                              failed_count = 0;

                              $('.gradedetail').each(function(a,b){
                                    if($('.edited[data-id="'+$(b).attr('data-value')+'"]').length > 0 ){
                                          edited_count += 1
                                    }
                              })

                              if(edited_count == 0){
                                    Toast.fire({
                                          type: 'info',
                                          title: 'No available update!'
                                    })
                                    return false
                              }

                              $('.gradedetail').each(function(a,b){
                                    var student = [];
                                    if($('.edited[data-id="'+$(b).attr('data-value')+'"]').length > 0 ){
                                          $('.edited[data-id="'+$(b).attr('data-value')+'"]').each(function(c,d){
                                                var temp_cell = b
                                                var field = $(d).attr('data-field')
                                                var studid = $(d).attr('data-studid')
                                                var grade = $(d).text()
                                                var id = $(d).hasClass('isHPS') ? hps_check[0].id : $(d).attr('data-id')
                                                var data = {
                                                      field:field,
                                                      studid:studid,
                                                      grade:grade,
                                                      id:id
                                                }
                                                student.push(data)
                                          })
                                          var url = '/gradesdetail/update'
                                          var student = {
                                                'data':student
                                          }
                                          $.ajax({
                                                type:'GET',
                                                url:url,
                                                data:student,
                                                success:function(data){
                                                      if(data[0].status == 1){
                                                            p_length += 1
                                                            $('.edited[data-id="'+$(b).attr('data-value')+'"]').removeClass('edited')
                                                            if(p_length == edited_count){
                                                                  Toast.fire({
                                                                        type: 'success',
                                                                        title: 'Updated Successfully!'
                                                                  })
                                                                  $('#updateGrade').removeClass('btn-danger')
                                                                  $('#updateGrade').addClass('btn-success')
                                                                  $('#btnSubmit').removeAttr('disabled')
                                                            }
                                                      }else{
                                                            failed_count += 1;
                                                            display_failed()
                                                            Toast.fire({
                                                                  type: 'error',
                                                                  title: 'Something went wrong!'
                                                            })
                                                      }
                                                },
                                                error:function(){
                                                      failed_count += 1;
                                                      display_failed()
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: 'Something went wrong!'
                                                      })
                                                }
                                          })
                                    }
                              })

                        }

                        $( '#upload_ecr' )
                              .submit( function( e ) {

                                    if($('#filter_quarter').val() == ""){
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'Please select quarter'
                                          })
                                          return false;
                                    }
                                    else if($('#input_ecr').val() == ""){
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'Please attach a file'
                                          })
                                          return false;
                                    }
                                  
                                    var subjid = $('#filter_subjects').val()
                                    var sectionid = $('#filter_section').val()
                                    var syid = $('#filter_sy').val()
                                    var selected = all_sched.filter(x=>x.subjid == subjid && x.sectionid == sectionid )
                                    var levelid = selected[0].levelid

                                    var inputs = new FormData(this)

                                    inputs.append('input_ecr',$('#input_ecr').val())
                                    inputs.append('syid',$('#filter_sy').val())
                                    inputs.append('levelid',levelid)
                                    inputs.append('sectionid',sectionid)
                                    inputs.append('subjid',subjid)
                                    inputs.append('quarter',$('#filter_quarter').val())
                                    
                                    $('#upload_ecr_button').text('Uploading...')
                                    $('#upload_ecr_button').attr('disabled','disabled')

                                    $.ajax({
                                          url: '/ecr/upload',
                                          type: 'POST',
                                          data: inputs,
                                          processData: false,
                                          contentType: false,
                                          success:function(data) {
                                                if(data[0].status == 0){
                                                      Toast.fire({
                                                            type: 'warning',
                                                            title: data[0].message
                                                      })

                                                      $('#upload_ecr_button').text('Update ECR')
                                                      $('#upload_ecr_button').removeAttr('disabled')
                                                      $('#input_ecr').val("")
                                                }else{
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: data[0].message
                                                      })
                                                      $('#upload_ecr_button').text('Update ECR')
                                                      $('#upload_ecr_button').removeAttr('disabled')
                                                      $('#input_ecr').val("")
                                                      load_grades()
                                                }
                                          },error:function(){
                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'Something went wrong'
                                                });
                                                $('#upload_ecr_button').text('Update ECR')
                                                $('#upload_ecr_button').removeAttr('disabled')
                                                $('#input_ecr').val("")
                                          }
                                    })
                                    e.preventDefault();
                              })

                        function display_failed(){
                              $('#failed_update').empty()
                              $('#failed_update').removeAttr('hidden')
                              $('#failed_update').text('Failed Updates: ' +failed_count)
                        }

                        $(document).on('click','#updateGrade',function(){
                              updateAllGradesDetail()
                        })



                        $(document).on('click','#btnSubmit',function(){

                              var updated_length = $('.updated').length
                              if(updated_length){
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'You have unsaved grades!'
                                    })
                                    return false;
                              }

                              var subjid = $('#filter_subjects').val()
                              var sectionid = $('#filter_section').val()
                              var syid = $('#filter_sy').val()
                              var quarter = $('#filter_quarter').val()
                              var selected = all_sched.filter(x=>x.subjid == subjid && x.sectionid == sectionid )
                              var levelid = selected[0].levelid
                              var semid = selected[0].levelid == 14 || selected[0].levelid == 15 ?  selected[0].semid : 1

                              var include = []

                              $('.exclude').each(function(){
                                    if($(this).prop('checked') == true){
                                          include.push($(this).attr('data-studid'))
                                    }
                              })

                              var text = selected[0].subjdesc

                              
                              Swal.fire({
                                    title: 'Are you sure you want to<br>submit '+text+' grades?',
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Submit '+text+' grades'
                              }).then((result) => {
                                          if (result.value) {
                                                
                                                $('#btnSubmit').attr('disabled','disabled')
                                                $('#updateGrade').attr('disabled','disabled')
                                                $('.exclude').attr('disabled','disabled')

                                                $.ajax({
                                                      url: '/teacher/pending/grade/submit/grades',
                                                      type: 'GET',
                                                      data:{
                                                            syid: syid,
                                                            levelid:levelid,
                                                            sectionid: sectionid,
                                                            subjid :subjid,
                                                            quarter :quarter,
                                                            semid:semid,
                                                            include:include
                                                      },
                                                      success:function(data) {
                                                            if(data[0].status == 1){
                                                                  Toast.fire({
                                                                        type: 'success',
                                                                        title: data[0].data
                                                                  })

                                                                  var not_checked = 0

                                                                  $('.exclude').each(function(){
                                                                        if($(this).prop('checked') == false){
                                                                              not_checked += 1
                                                                        }
                                                                  })

                                                                  if(not_checked == 0){

                                                                        if(levelid == 14 || levelid == 15){

                                                                              var temp_index = all_sched.findIndex(x=>x.subjid == subjid && x.sectionid == sectionid)
                                                                              var new_strand = []
                                                                              var temp_strand = $('#strand_holder').val()

                                                                              $.each(all_sched[temp_index].strand, function(a,b){
                                                                                    if(b.strandid != temp_strand){
                                                                                          new_strand.push(b)
                                                                                    }
                                                                              })

                                                                              all_sched[temp_index].strand = new_strand

                                                                              $('#strand_holder').empty()
                                                                              $.each(new_strand,function(a,b){
                                                                                    $('#strand_holder').append('<option value="'+b.strandid+'">'+b.strandcode+'</option>')
                                                                              })

                                                                              if(new_strand.length == 0){
                                                                                    var temp_index = all_sched.findIndex(x=>x.subjid == subjid && x.sectionid == sectionid)
                                                                                    all_sched[temp_index].pending_quarter = all_sched     [temp_index].pending_quarter.filter(x=>x != quarter)
                                                                                    if(all_sched[temp_index].pending_quarter == 0 ){
                                                                                          all_sched[temp_index].with_pending = false
                                                                                          all_sched = all_sched.filter(x=>x.subjid != subjid || x.sectionid != sectionid)
                                                                                    }
                                                                                    load_subjects()
                                                                                    clear_data()
                                                                              }else{
                                                                                    load_grades()
                                                                              }


                                                                        }else{
                                                                              $('#filter_subjects').val("")
                                                                              $('#filter_quarter').empty()

                                                                              var temp_index = all_sched.findIndex(x=>x.subjid == subjid && x.sectionid == sectionid)
                                                                              all_sched[temp_index].pending_quarter = all_sched[temp_index].pending_quarter.filter(x=>x != quarter)

                                                                              if(all_sched[temp_index].pending_quarter == 0 ){
                                                                                    all_sched[temp_index].with_pending = false

                                                                                    all_sched = all_sched.filter(x=>x.subjid != subjid || x.sectionid != sectionid)
                                                                              }

                                                                              load_subjects()
                                                                              clear_data()
                                                                        }
                                                                        
                                                                  }else{
                                                                        load_grades()
                                                                  }
                                                            
                                                                  update_sidenav()
                                                                  
                                                                  $('#btnSubmit').attr('disabled','disabled')
                                                                  $('#updateGrade').attr('disabled','disabled')
                                                            }else{
                                                                  Toast.fire({
                                                                        type: 'error',
                                                                        title: 'Something went wrong!'
                                                                  })
                                                            }
                                                      },
                                                      error:function(){
                                                            Toast.fire({
                                                                  type: 'error',
                                                                  title: 'Something went wrong!'
                                                            })
                                                      }
                                                })
                                          }
                                    }
                              )
                        })

                        function update_sidenav(){
                              $.ajax({
                                    url: '/teacher/get/pending',
                                    type:"GET",
                                    success:function(data) {
                                          if(data[0].with_pending){
                                                $('.pending_status_holder').removeAttr('hidden')
                                                if(data[0].student_pending_count > 0 ){
                                                      $('.student_pending').removeAttr('hidden')
                                                      $('.student_pending').text(data[0].student_pending_count)
                                                }else{
                                                      $('#student_pending').attr('hidden','hidden')
                                                }
                                                if(data[0].section_pending_count > 0 ){
                                                      $('.section_pending').removeAttr('hidden')
                                                      $('.section_pending').text(data[0].section_pending_count)
                                                }else{
                                                      $('#section_pending').attr('hidden','hidden')
                                                }
                                          }else{
                                                $('.student_pending').attr('hidden','hidden')
                                                $('.section_pending').attr('hidden','hidden')
                                                $('.pending_status_holder').attr('hidden','hidden')
                                          }
                                    }
                              });
                        }
            })
      </script>


@endsection


