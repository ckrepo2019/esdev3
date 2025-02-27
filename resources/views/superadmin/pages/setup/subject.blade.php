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
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <style>

            [class*=icheck-]>input:first-child:checked+input[type=hidden]+label::after, [class*=icheck-]>input:first-child:checked+label::after {     
                  top: -2px;
                  left: -2px;
                  width: 5px;
                  height: 8px;
            }

            [class*=icheck-]>input:first-child+input[type=hidden]+label::before, [class*=icheck-]>input:first-child+label::before {
                  width: 15px;
                  height: 15px;
                  margin-left: -18px;
            }

            [class*=icheck-]>label {
                  padding-left: 18px !important;
                  line-height: 18px;
                  min-height: 4px;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
              margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0;
            }

            
          
      </style>
@endsection


@section('content')

@php
   
      $is_gshs = false;
      $is_sh = false;

      if(auth()->user()->type == 2){
            $acad = array();
            
            $teacherid = DB::table('teacher')
                              ->where('deleted',0)
                              ->where('tid',auth()->user()->email)
                              ->first();

            $temp_acad = DB::table('academicprogram')
                        ->where('principalid',$teacherid->id)
                        ->select('id')
                        ->get();

            foreach($temp_acad as $item){
                  if($item->id == 5){
                        $is_sh = true;
                  }else{
                        $is_gshs = true;
                  }
            }
      }else{
            $acad = array();
            $is_gshs = true;
            $is_sh = true;
            $temp_acad = DB::table('academicprogram')
                        ->select('id')
                        ->get();

            foreach($temp_acad as $item){
                  array_push($acad,$item->id);
            }
      }
@endphp

<div class="modal fade" id="subject_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Subject Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        {{-- <div class="row" id="error_messag_holder" >
                              <div class="col-md-12">
                                    <div class="card shadow bg-danger">
                                          <div class="card-body p-1" id="">
                                                You are not assigned to a course or college.
                                          </div>
                                    </div>
                              </div>
                        </div> --}}
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label>Subject Description <i class="text-danger text-sm">*</i></label>
                                    <input type="text" class="form-control" id="input_subjdesc" autocomplete="off">
                                    <ul id="same_subj" class="mb-0"></ul>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label>Subject Code <i class="text-danger text-sm">*</i></label>
                                    <input type="text" class="form-control" id="input_subjcode" autocomplete="off">
                              </div>
                        </div>
                        <!-- JAM START: add subjunit input-->
                        <div class="row">
                              <div class="col-md-6 form-group">
                                    <label>Subject Unit</label>
                                    <input type="text" class="form-control" id="input_subjunit" autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                              </div>
                              <div class="col-md-6 form-group">
                                    <label>Hours</label>
                                    <input type="text" class="form-control" id="input_hours" autocomplete="off" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Term</label>
                                    <select name="" id="input_term" class="form-control">
                                          <option value="0" selected>Select Term</option> 
                                          <option value="1">First Term</option> 
                                          <option value="2">Second Term</option>
                                    </select>
                              </div>
                        </div>
                        <!-- JAM END: add subjunit input-->
                        <div class="row not_sh" hidden>
                              <div class="col-md-12 form-group">
                                    <label class=" mb-0">Percentage </label>
                                    <p class="text-danger text-sm"><i>*A percentage is only applicable for T.L.E. subjects</i></p>
                                    <input class="form-control" id="per" oninput="this.value=this.value.replace(/[^0-9]/g,'');" autocomplete="off">
                              </div>
                        </div>
                        <div class="row not_sh" hidden>
                              <div class="col-md-12 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="isSP" >
                                        <label for="isSP"> SPECIALIZATION
                                        </label>
                                    </div>
                              </div>
                        </div>
                        <div class="row not_sh" hidden>
                              <div class="col-md-12 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="isCon" >
                                        <label for="isCon"> CONSOLIDATED SUBJECT</label>
                                    </div>
                                    <p class="text-danger text-sm mb-0"><i>*Mark subject as consolidated if it contains components, ex. MAPEH and TLE.</i></p>
                              </div>
                        </div>
                        <div class="row not_sh" hidden>
                              <div class="col-md-12 form-group" id="comp_holder" hidden>
                                    <label for="isCon">COMPONENTS</label>
                                    <select class="select2 form-control"  multiple="multiple" id="comp_subjects" style="width: 100%;"></select>
                              </div>
                        </div>
                        <div class="row is_sh" hidden>
                              <div class="col-md-12 form-group">
                                    <label for="">Type</label>
                                    <select name="" id="input_type" class="form-control">
                                          <option value="1">Core</option> 
                                          <option value="2">Specialized</option>
                                          <option value="3">Applied</option>
                                          <option value="4">Academic</option>
                                          <option value="5">Institutional</option>
                                    </select>
                              </div>
                        </div>
                        <hr class="mt-0">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="isVisible" checked>
                                        <label for="isVisible">Display on report card.
                                        </label>
                                    </div>
                                    <p class="text-danger text-sm mb-0"><i>*Uncheck "visible on report card" if a subject is not visible on Report Card.</i></p>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="isInSF9" checked>
                                        <label for="isInSF9">Include in computation.
                                        </label>
                                    </div>
                                    <p class="text-danger text-sm mb-0"><i>*Uncheck "include in computation" if a subject is not included in the computation on Report Card.</i></p>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="subject_to_create">CREATE</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>   

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Subjects</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Subjects</li>
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
                                                      <div class="col-md-3 ">
                                                            <label for="">Academic Program</label>
                                                            <select class="form-control form-control-sm select2" id="filter_type" >
                                                                  <option value="">Academic Program</option>
                                                            @if($is_gshs)
                                                                  <option value="1">PS / GS / HS</option>
                                                            @endif
                                                            @if($is_sh)
                                                                  <option value="2">Senior High</option>
                                                            @endif
                                                            </select>
                                                      </div>
                                                </div>
                                                {{-- <div class="row">
                                                      <div class="col-md-12">
                                                            <button class="btn btn-success btn-sm mt-2 btn-block" id="subject_to_modal" disabled style="font-size:.7rem !important"><i class="fas fa-plus"></i> Add Subject</button>
                                                      </div>
                                                </div> --}}
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow" style="">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12"  style="font-size:.8rem">
                                                <table class="table-hover table table-sm mb-0" id="subject_table" width="100%" >
                                                      <thead class="thead-light">
                                                            <tr>
                                                                  <th width="4%" class="p-0 align-middle text-center" >#</th>
                                                                  <th width="38%" >Description</th>
                                                                  <th width="7%" class="p-0 align-middle text-center" style="font-size:.65rem">Term</th>
                                                                  <th width="6%" class="p-0 align-middle text-center" style="font-size:.65rem" id="subj_th0">Units</th>
                                                                  <th width="6%" class="p-0 align-middle text-center" style="font-size:.65rem" id="subj_th1">Consolidated</th>
                                                                  <th width="6%" class="p-0 align-middle text-center" style="font-size:.65rem" id="subj_th2">Component</th>
                                                                  <th width="6%" class="p-0 align-middle text-center" style="font-size:.65rem" id="subj_th3">Specialized</th>
                                                                  <th width="6%" class="p-0 align-middle text-center" style="font-size:.65rem">Display</th>
                                                                  <th width="5%" class="p-0 align-middle text-center" style="font-size:.65rem">Computation</th>
                                                                  <th width="5%" class="p-0 align-middle text-center" style="font-size:.65rem" id="subj_th4">%</th>
                                                                  <th width="5%" class="p-0 align-middle text-center" style="font-size:.65rem" >Hrs</th>
                                                                  <th width="3%"></th>
                                                                  <th width="3%"></th>
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

                  $('.select2').select2()

                  $("#filter_type").select2({
                        placeholder: "Select Academic Program",
                  })

                  var all_subject = []
                  var subjplot = []
                  var selected_subject

                  process = 'create'
      
                  var table = subject_datatable()
                  table.state.clear();
                  table.destroy();

                  $(document).on('change','#filter_type',function(){
                        $('#subject_to_modal').attr('disabled','disabled')
                        $('#comp_holder').attr('hidden','hidden')
                        $('#comp_subjects').val("").change()
                        if($(this).val() == ""){
                              all_subject = []
                              subject_datatable()
                        }else{
                              filter_change()
                        }
                  })

                  $(document).on('click','#isSP',function(){
                        if($(this).prop('checked') == true){
                              $('#isCon').prop('checked',false)
                              $('#comp_subjects').val([]).change()
                              $('#comp_holder').attr('hidden','hidden')
                        }
                  })

                  $(document).on('click','#isCon',function(){
                        if($(this).prop('checked') == true){
                              $('#isSP').prop('checked',false)
                              if(selected_subject != null){
                                    var temp_subjcom = all_subject.filter(x=>x.subjCom == selected_subject)
                                    var temp_comp = []
                                    $.each(temp_subjcom,function(a,b){
                                          temp_comp.push(b.id)
                                    })
                                    $("#comp_subjects").val(temp_comp).change()
                              }
                              $('#comp_holder').removeAttr('hidden')
                        }else{
                              $('#comp_holder').attr('hidden','hidden')
                              $('#comp_subjects').val("").change()
                        }
                  })

                  function filter_change(){
                        if($('#filter_type').val() == 2){
                              $('.is_sh').removeAttr('hidden')
                              $('.not_sh').attr('hidden','hidden')
                              
                        }else{
                              $('.is_sh').attr('hidden','hidden')
                              $('.not_sh').removeAttr('hidden')
                        }
                        $('#subject_to_modal').removeAttr('disabled')
                        
                        get_subjects()
                  }

                  function get_subjects(){
                        $.ajax({
					type:'GET',
					url: '/superadmin/setup/subject/list',
                              data:{
                                    stage:$('#filter_type').val()
                              },
					success:function(data) {

                                    if(data.length > 0){
                                          Toast.fire({
                                                type: 'info',
                                                title: data.length + ' subject(s) found!'
                                          })
                                          all_subject = data
                                          subject_datatable()
                                    }else{
                                          Toast.fire({
                                                type: 'info',
                                                title: 'No subject found!'
                                          })
                                          all_subject = []
                                          subject_datatable()
                                    }

                                   
					}
				})
                  }

                  function clear_fields(){
                        $('#comp_holder').attr('hidden','hidden')
                        $('#input_subjdesc').val("")
                        $('#input_subjcode').val("")
                        // JAM START: include subjunit to clear input
                        $('#input_subjunit').val("")
                        $('#input_hours').val("")
                        // JAM END: include subjunit to clear input
                        $('#per').val("")
                        $('#isCon').prop('checked',false)
                        $("#comp_subjects").val([]).change()
                  }

                  // dynamic form validation for subjdesc and subjcode
                  dynamic_validate(['#input_subjdesc', '#input_subjcode'], '#subject_to_create', (isValid) => {
                        return isValid
                  })

                  $(document).on('input','#input_subjdesc',function(){
                        var text = $(this).val()
                        var check_dup = all_subject.filter(x=>x.subjdesc.includes(text.toUpperCase()) && x.id != selected_subject)
                        $('#same_subj').empty()
                        if(check_dup.length > 0 && $(this).val() != ""){
                              var duplicate = ''
                              $.each(check_dup,function(a,b){
                                    duplicate += '<li>'+b.text+'</li>'

                              })
                              $('#same_subj')[0].innerHTML = duplicate

                              Toast.fire({
                                    type: 'warning',
                                    title: 'Subject already exist!'
                              })
                        }
                  })


                  // JAM START: added functions

                  function dynamic_validate(selectors, btnSel, callback) {
                        // This function accepts mutltiple 'selectors'
                        // Check each selector for valid input
                        // If the first selector is invalid it will
                        // not check the nex selector

                        function validateInput(input) {
                              if (!input.val().trim()) {
                                    // disable add button when error input
                                    $(btnSel).prop("disabled", true);
                                    input.removeClass("is-valid").addClass("is-invalid");
                                    return false;
                              } else {
                                    // disable add button when error input
                                    $(btnSel).prop("disabled", false);
                                    input.removeClass("is-invalid").addClass("is-valid");
                                    return true;
                              }
                        }

                        var isValid = false

                        // dynamic validation check
                        selectors.forEach(function(selector) {

                              // observe input field changes
                              $(selector).on("input", function() {

                                    // validate input and update isValid
                                    isValid = selectors.every(function(s) {
                                          return validateInput($(s));
                                    });

                                    // invoke callback with current validation state
                                    callback(isValid);
                              });
                        });

                        // initial validation check
                        isValid = selectors.every(function(s) {
                              return validateInput($(s));
                        });

                        callback(isValid);
                  }

                  function dynamic_validate_reset(selectors) {
                        selectors.forEach(function(selector) {
                              $(selector).removeClass('is-valid')
                              $(selector).removeClass('is-invalid');
                        });
                        $('#subject_to_create').removeAttr('disabled')
                  }

                  function update_pagination(options) {
                        // note that options.selector must be a parent
                        // of the .dataTables_info

                        var text = $(`${options.selector} .dataTables_info`).text()
                        const match = text.match(/\d+/g);

                        if (match) {
                              const entries = parseInt(match[2]);
                              const maxItemsPerPage = 10

                              if (options) {
                                    if (options.initialState) {
                                          const prevSelector = $(`${options.selector} .page-link:contains("Previous")`)
                                          const pageOneSelector = $(`${options.selector} .page-link:contains("1")`)

                                          if (pageOneSelector.text() === '1') {
                                                pageOneSelector.click()
                                          } else {
                                                prevSelector.click()
                                          }
                                          
                                    } else {
                                          var page = String(Math.ceil(entries / maxItemsPerPage))
                                          otherPageSelector = $(`${options.selector} .page-link:contains("${page}")`)

                                          console.log(otherPageSelector.text())

                                          
                                          if (otherPageSelector.text() === page) {
                                                otherPageSelector.click()
                                          }
                                    }
                              }
                        }
                  }

                  // JAM END:

                  function validate_input(){
                        var valid = true;

                        if($('#input_subjdesc').val() == ""){
                              valid = false
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Description is required!'
                              })
                        }
                        
                        else if($('#input_subjcode').val() == ""){
                              valid = false
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Code is required!'
                              })
                        }

                        // JAM START: add filter for subjunit
                        // else if($('#input_subjunit').val() == ""){
                        //       valid = false
                        //       Toast.fire({
                        //             type: 'warning',
                        //             title: 'Unit is required!'
                        //       })
                        // }

                        else if($('#input_subjunit').val() != ""){

                              var subjUnitRegex = /^\d+(\.\d)?$/;
                              var userInputValue = $('#input_subjunit').val()

                              // Check if input matches the pattern
                              if (!subjUnitRegex.test(userInputValue)) {
                                    valid = false;
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'Subject Unit Error: Only numbers with one decimal place is allowed.',
                                          timer: 5000
                                    });
                              }

                        }
                        // JAM END: add filter for subjunit

                        if($('#filter_type').val() == 1){
                              if($('#per').val() != ""){
                                    if($('#per').val() > 100){
                                          valid = false
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'Percentage exceeded 100!'
                                          })
                                    }
                                    if($('#per').val() == 0){
                                          valid = false
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'Percentage is 0!'
                                          })
                                    }
                              }
                        }

                        if($('#isCon').prop('checked') == true){
                                    if($('#comp_subjects').val().length == 0){
                                          valid = false
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'No component selected!'
                                          })
                                    }
                              }

                        return valid;

                  }

                  $(document).on('click','#subject_to_create',function(){

                        if(process == 'create'){
                              selected_subject = null
                              var valid = validate_input();
                              if(valid){
                                    subject_create()   
                              }

                        }else if(process == 'edit'){
                              var valid = validate_input()
                              if(valid){
                                    subject_update() 
                              }
                        
                        }
                  })

                  $(document).on('click','.subject_to_info',function(){
                        $('#subject_info_modal').modal()
                  })

                  $(document).on('click','#subject_to_modal',function(){

                        $('#subject_modal').modal({
                              backdrop: 'static',
                              keyboard: false,
                              show: true,
                        })
                        $('#same_subj').empty()
                        $('#isSP').prop('checked',false)
                        $('#isCon').prop('checked',false)

                        $('#subject_to_create').text('Create')   
                        process = 'create'
                        $('#subject_to_create')[0].innerHTML = '<i class="fas fa-plus"></i> Add'
                        $('#subject_to_create').removeClass('btn-primary')
                        $('#subject_to_create').addClass('btn-success')

                        var com_subj = all_subject.filter(x=>x.subjCom == null && x.isCon == 0)

                        $("#comp_subjects").empty()
                        $("#comp_subjects").select2({
                              data: com_subj,
                              placeholder: "Select a component",
                              theme: 'bootstrap4'
                        })
                        
                        clear_fields()
                        dynamic_validate_reset(['#input_subjcode', '#input_subjdesc'])
                  })

                  $(document).on('change','.acad',function(){
                        if($(this).prop('checked')){
                              $(this).prop('checked',false)
                        }
                        else if(!$(this).prop('checked')){
                              $(this).prop('checked',true)
                        }
                  })

                  $(document).on('click','.edit',function(){
                        
                        dynamic_validate_reset(['#input_subjcode', '#input_subjdesc'])

                        selected_subject = $(this).attr('data-id')
                        temp_subj = all_subject.filter(x=>x.id == selected_subject)

                        $('#subject_to_create')[0].innerHTML = '<i class="far fa-edit"></i> Update'
                        $('#subject_to_create').addClass('btn-primary')
                        $('#subject_to_create').removeClass('btn-success')

                        $('#input_subjdesc').val(temp_subj[0].subjdesc)
                        $('#input_subjcode').val(temp_subj[0].subjcode)
                        // JAM START: add subjunit value in displaying when edit modal is dislpayed
                        $('#input_subjunit').val(temp_subj[0].subjunit)
                        $('#input_hours').val(temp_subj[0].hours)
                        console.log(temp_subj[0].term,'---');
                        $('#input_term').val(temp_subj[0].term).trigger('change')
                        // JAM END

                        if(temp_subj[0].isCon == 1){
                              $('#isCon').prop('checked',true)
                              $('#comp_holder').removeAttr('hidden')
                        }else{
                              $('#isCon').prop('checked',false)
                              $('#comp_holder').attr('hidden','hidden')
                        }

                        if(temp_subj[0].isSP == 1){
                              $('#isSP').prop('checked',true)
                        }else{
                              $('#isSP').prop('checked',false)
                        }

                        // if(temp_subj[0].subjCom != null){
                        //       $('#isCon').attr('disabled','disabled')
                        // }else{
                        //       $('#isCon').removeAttr('disabled')
                        // }

                        if(temp_subj[0].isVisible == 1){
                              $('#isVisible').prop('checked',true)
                        }else{
                              $('#isVisible').prop('checked',false)
                        }

                        if(temp_subj[0].inSF9 == 1){
                              $('#isInSF9').prop('checked',true)
                        }else{
                              $('#isInSF9').prop('checked',false)
                        }
                       
                        var com_subj = all_subject.filter( x=> ( x.subjCom == null || x.subjCom == selected_subject ) && x.id != selected_subject)
                        $("#comp_subjects").empty()
                        $("#comp_subjects").select2({
                              data: com_subj,
                              placeholder: "Select a component",
                              theme: 'bootstrap4'
                        })

                        var temp_subjcom = all_subject.filter(x=>x.subjCom == selected_subject)

                        var temp_comp = []

                        $.each(temp_subjcom,function(a,b){
                              temp_comp.push(b.id)
                        })

                        if(temp_subj[0].subj_per == 0){
                              $('#per').val("")
                        }else{
                              $('#per').val(temp_subj[0].subj_per)
                        }

                     
                        $("#comp_subjects").val(temp_comp).change()

                        $('#input_type').val(temp_subj[0].type).change()

                        process = 'edit'
                        $('#subject_modal').modal({
                              backdrop: 'static',
                              keyboard: false,
                              show: true
                        })   
                        $('#same_subj').empty()
                          
                  })

                  $(document).on('click','.delete',function(){
                        selected_subject = $(this).attr('data-id')
                        subject_delete()
                  })

                  function subject_delete(){
                        Swal.fire({
                              title: 'Do you want to remove subject?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'GET',
                                          url: '/superadmin/setup/subject/delete',
                                          data:{
                                                id:selected_subject,
                                                stage:$('#filter_type').val()
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: 'Deleted Successfully!'
                                                      })
                                                      all_subject = data[0].info
                                                      subject_datatable()
                                                
                                                }else{
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: data[0].message
                                                      })
                                                }
                                          }
                                    })
                              }
                        })
                  }

                  function subject_create(){

                        $('#subject_to_create').attr('disabled','disabled')


                        var isCon = 0
                        var isSP = 0
                        var comps = []
                        var per = 0
                        var isVisible = 1
                        var isInSF9 = 1

                        if($('#isCon').prop('checked')==true){
                              isCon = 1
                              comps = $('#comp_subjects').val()
                        }
                        if($('#isSP').prop('checked')==true){
                              isSP = 1
                        }
                        if($('#per').val() != ""){
                              per = $('#per').val()
                        }
                        if($('#isVisible').prop('checked')==false){
                              isVisible = 0
                        }
                        if($('#isInSF9').prop('checked')==false){
                              isInSF9 = 0
                        }
                        
                        $.ajax({
					type:'GET',
					url: '/superadmin/setup/subject/create',
                              data:{
                                    subjdesc:$('#input_subjdesc').val(),
                                    subjcode:$('#input_subjcode').val(),
                                    // JAM START: send subjunit
                                    subjunit:$('#input_subjunit').val(),
                                    hours:$('#input_hours').val(),
                                    // JAM END: send subjunit
                                    stage:$('#filter_type').val(),
                                    isCon:isCon,
                                    isSP:isSP,
                                    comp:comps,
                                    per:per,
                                    type:$('#input_type').val(),
                                    isVisible:isVisible,
                                    isInSF9:isInSF9
                              },
					success:function(data) {

                                    $('#subject_to_create').removeAttr('disabled')

                                    if(data[0].status == 1){
                                          clear_fields()
                                          all_subject = data[0].info
                                          subject_datatable()
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Create Successfully!'
                                          })
                                          $('#subject_modal').modal('hide')
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                    }
					}
				})

                  }

                  function subject_update(){
                        $('#subject_to_create').attr('disabled','disabled')
                        var isCon = 0
                        var isSP = 0
                        var comps = []
                        var per = 0
                        var isVisible = 1
                        var isInSF9 = 1
                        var term = $('#input_term').val()
                        
                        if($('#isCon').prop('checked')==true){
                              isCon = 1
                              comps = $('#comp_subjects').val()
                        }
                        if($('#isSP').prop('checked')==true){
                              isSP = 1
                        }
                        if($('#per').val() != ""){
                              per = $('#per').val()
                        }
                        if($('#isVisible').prop('checked')==false){
                              isVisible = 0
                        }

                        if($('#isInSF9').prop('checked')==false){
                              isInSF9 = 0
                        }
                        $.ajax({
					type:'GET',
					url: '/superadmin/setup/subject/update',
                              data:{
                                    id:selected_subject,
                                    subjdesc:$('#input_subjdesc').val(),
                                    subjcode:$('#input_subjcode').val(),
                                    // JAM START: send subjunit
                                    subjunit:$('#input_subjunit').val(),
                                    hours:$('#input_hours').val(),
                                    // JAM END: send subjunit
                                    isCon:isCon ,
                                    isSP:isSP,
                                    comp:comps,
                                    per:per,
                                    type:$('#input_type').val(),
                                    stage:$('#filter_type').val(),
                                    isVisible:isVisible,
                                    isInSF9:isInSF9,
                                    term:term
                              },
					success:function(data) {
                                    $('#subject_to_create').removeAttr('disabled')
                                    if(data[0].status == 1){
                                          clear_fields()
                                          all_subject = data[0].info
                                          subject_datatable()
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Updated Successfully!'
                                          })
                                          $('#subject_modal').modal('hide')
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
					}
				})
                  }

                  subject_datatable()

                  function subject_datatable(){

                        var dtDeferred = $.Deferred();

                        $.each(all_subject,function(a,b){
                              var subj_num = 'S'+('000'+b.id).slice (-3)
                              b.text = subj_num + ' - ' + b.text
                        })

                        var com_subj = all_subject.filter(x=>x.subjCom == null && x.isCon == 0)

                        $("#comp_subjects").empty()
                        $("#comp_subjects").select2({
                              data: com_subj,
                              placeholder: "Select a component",
                              theme: 'bootstrap4'
                        })

                        // Change DataTable columns base on filter_type value
                        if($('#filter_type').val() == 2){
                              $('#subj_th1').text('Type')
                              $('#subj_th2').attr('hidden','hidden')
                              $('#subj_th3').attr('hidden','hidden')
                              $('#subj_th4').attr('hidden','hidden')
                        }else if($('#filter_type').val() == 1){
                              $('#subj_th1').text('Consolidated')
                              $('#subj_th2').removeAttr('hidden')
                              $('#subj_th3').removeAttr('hidden')
                              $('#subj_th4').removeAttr('hidden')

                              $('#subj_th2').addClass('pl-2 pr-2')
                              $('#subj_th3').addClass('pl-2 pr-2')
                              $('#subj_th4').addClass('pl-3 pr-3')
                        }

                        var temp_table = $("#subject_table").DataTable({
                              destroy: true,
                              data:all_subject,
                              lengthChange: false,
                              stateSave: true,
                              search:{
                                    "search": " "
                              },
                              order: [
                                          [ 1, "asc" ]
                                    ],
                              columns: [
                                    { "data": "subjcode" },
                                    { "data": "search" },
                                    { "data": "term" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": "hours" },
                                    { "data": null },
                                    { "data": null }
                              ],
                              initComplete: function(settings, json) {
                                    dtDeferred.resolve();
                              },
                              columnDefs: [
                                    // # column
                                    {
                                          'targets': 0,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                var subj_num = 'S'+('000'+rowData.id).slice (-3)
                                                $(td)[0].innerHTML =  subj_num
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                                
                                          }
                                    },
                                    {
                                          'targets': 10,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    // description column
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var comp = '';
                                                var consolidate = ''
                                                var spec = ''
                                                var type = ''
                                                var percentage = ''
                                                var visDis = ''
                                                var inSF9 = ''
                                                
                                                if($('#filter_type').val() == 1){
                                                      if(rowData.isCon == 1){
                                                            // consolidate = '- <i class="text-success">Consolidated</i>'
                                                            var temp_subjcom = all_subject.filter(x=>x.subjCom == rowData.id)
                                                            if(temp_subjcom.length > 0){
                                                                  comp += '<span class="text-danger"><i>[ '
                                                                  $.each(temp_subjcom,function(a,b){
                                                                        comp += b.subjcode + ', '
                                                                  })
                                                                  comp = comp.slice(0,-2)
                                                                  comp += ' ]<i/i></span>'
                                                            }
                                                      }

                                                      if(rowData.isSP == 1){
                                                            // spec = '-  <i class="text-danger"> Specialization </i>'
                                                      }

                                                      if(rowData.subjCom != null){
                                                            var comp_subj = all_subject.filter(x=>x.id == rowData.subjCom)
                                                            if(comp_subj.length > 0){
                                                                  spec = '-  <i class="text-danger">'+comp_subj[0].subjcode+'  Component </i>'
                                                            }
                                                      }

                                                      // if(rowData.subj_per != 0){
                                                      //       percentage = '<i class="text-danger">( '+rowData.subj_per+'% )</i> '
                                                      // }

                                                      var visDis = '<span class="badge badge-success mr-1">V</span>'
                                                      if(rowData.isVisible == 0){
                                                            visDis = '<span class="badge badge-danger badge-danger mr-1">NV</span>'
                                                      }

                                                      var inSF9 = '<span class="badge badge-success">IC</span>'
                                                      if(rowData.inSF9 == 0){
                                                            inSF9 = '<span class="badge badge-danger badge-danger">NIC</span>'
                                                      }

                                                }else{
                                                      if(rowData.type == 1){
                                                            type = '-  <i class="text-danger">Core</i>'
                                                      }else if(rowData.type == 2){
                                                            type = '-  <i class="text-danger">Specialized</i>'
                                                      }else if(rowData.type == 3){
                                                            type = '-  <i class="text-danger">Applied</i>'
                                                      }else if(rowData.type == 4){
                                                            type = '-  <i class="text-danger">Academic</i>'
                                                      }else if(rowData.type == 4){
                                                            type = '-  <i class="text-danger">Institutional</i>'
                                                      }

                                                      type = ''

                                                      var inSF9 = '<span class="badge badge-success">IC</span>'
                                                      if(rowData.inSF9 == 0){
                                                            inSF9 = '<span class="badge badge-danger badge-danger">NIC</span>'
                                                      }

                                                }

                                                var subj_num = 'S'+('000'+rowData.id).slice (-3)+' - '
                                                subj_num = ''

                                                $(td).addClass('pl-3')
                                                var text = '<a class="mb-0">'+rowData.subjdesc+' '+comp+' </a><p class="text-muted mb-0" style="font-size:.7rem"> '+subj_num+rowData.subjcode+' '+consolidate+' '+spec+' '+type+' '+percentage+'</p>';

                                                // var text = '<a class="mb-0">'+rowData.subjdesc+' '+comp+ ' '+percentage+' </a>';
                                                // $(td).addClass('align-middle')
                                                $(td)[0].innerHTML =  text
                                                
                                          }


                                    },
                                    // units column
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var term = rowData.term
                                                if (term == 1) {
                                                      term = 'First Term'
                                                } else if (term == 2) {
                                                      term = 'Second Term'
                                                } else {
                                                      term = ''
                                                }

                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                                $(td).text(term)
                                                
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                                $(td).text(rowData.subjunit)
                                                
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                var ischecked = 'checked="checked"'
                                                var disabled = ''

                                                if(rowData.isCon == 0){
                                                      ischecked = ''
                                                }

                                                if($('#filter_type').val() == 2){
                                                      disabled = 'disabled="disabled"'
                                                      ischecked = ''

                                                      if(rowData.type == 1){
                                                            type = 'Core'
                                                      }else if(rowData.type == 2){
                                                            type = 'Specialized'
                                                      }else if(rowData.type == 3){
                                                            type = 'Applied'
                                                      }else if(rowData.type == 4){
                                                            type = 'Academic'
                                                      }else if(rowData.type == 5){
                                                            type = 'Institutional'
                                                      }

                                                      var buttons = type;
                                                }else{
                                                      // var buttons = `<div class="icheck-success d-inline"><input data-id="`+rowData.id+`" type="checkbox" class="acad"  id="consolidated_`+rowData.id+`" `+ischecked+` `+disabled+`> <label for="consolidated_`+rowData.id+`" style="font-size:.65rem !important">&nbsp;</label>`;
                                                      if(ischecked == ''){
                                                            var buttons = '<i style="font-size:15px" class="fa text-danger">&#xf00d;</i>'
                                                      }else{
                                                            var buttons = '<i class="fa fa-check text-success"></i>'
                                                      }
                                                }


                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    },
                                    {
                                          'targets': 5,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                var ischecked = 'checked="checked"'
                                                var disabled = ''


                                                if(rowData.subjCom == null){
                                                      ischecked = ''
                                                }

                                                if($('#filter_type').val() == 2){
                                                      disabled = 'disabled="disabled"'
                                                      ischecked = ''
                                                      $(td).attr('hidden','hidden')
                                                }else{
                                                      $(td).removeAttr('hidden')
                                                }


                                                // var buttons = `<div class="icheck-success d-inline"><input  type="checkbox" id="component_`+rowData.id+`" class="acad" data-id="`+rowData.id+`" `+ischecked+` `+disabled+` > <label for="component_`+rowData.id+`" style="font-size:.65rem !important">&nbsp;</label>`;

                                                if(ischecked == ''){
                                                      var buttons = '<i style="font-size:15px" class="fa text-danger">&#xf00d;</i>'
                                                }else{
                                                      var buttons = '<i class="fa fa-check text-success"></i>'
                                                }

                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 6,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                var ischecked = 'checked="checked"'
                                                var disabled = ''


                                                if(rowData.isSP == 0){
                                                      ischecked = ''
                                                }

                                                if($('#filter_type').val() == 2){
                                                      disabled = 'disabled="disabled"'
                                                      ischecked = ''
                                                      $(td).attr('hidden','hidden')
                                                }else{
                                                      $(td).removeAttr('hidden')
                                                }

                                                // var buttons = `<div class="icheck-success d-inline"><input  type="checkbox" id="specialized_`+rowData.id+`" class="acad" data-id="`+rowData.id+`" `+ischecked+` `+disabled+` > <label for="specialized_`+rowData.id+`" style="font-size:.65rem !important">&nbsp;</label>`;
                                                if(ischecked == ''){
                                                      var buttons = '<i style="font-size:15px" class="fa text-danger">&#xf00d;</i>'
                                                }else{
                                                      var buttons = '<i class="fa fa-check text-success"></i>'
                                                }
                                          

                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 7,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                var ischecked = 'checked="checked"'
                                                var disabled = ''

                                                if(rowData.isVisible == 0){
                                                      ischecked = ''
                                                }

                                                // if($('#filter_type').val() == 2){
                                                //       disabled = 'disabled="disabled"'
                                                // }

                                                // var buttons = `<div class="icheck-success d-inline"><input data-id="`+rowData.id+`" type="checkbox" class="acad"  id="visible_`+rowData.id+`" `+ischecked+` `+disabled+`> <label for="visible_`+rowData.id+`" style="font-size:.65rem !important">&nbsp;</label>`;

                                                if(ischecked == ''){
                                                      var buttons = '<i style="font-size:15px" class="fa text-danger">&#xf00d;</i>'
                                                }else{
                                                      var buttons = '<i class="fa fa-check text-success"></i>'
                                                }
                                          

                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    },
                                    {
                                          'targets': 8,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                var ischecked = 'checked="checked"'

                                                if(rowData.inSF9 == 0){
                                                      ischecked = ''
                                                }


                                                // var buttons = `<div class="icheck-success d-inline"><input  type="checkbox" id="computation_`+rowData.id+`" class="acad" data-id="`+rowData.id+`" `+ischecked+` > <label for="computation_`+rowData.id+`" style="font-size:.65rem !important">&nbsp;</label>`;

                                                if(ischecked == ''){
                                                      var buttons = '<i style="font-size:15px" class="fa text-danger">&#xf00d;</i>'
                                                }else{
                                                      var buttons = '<i class="fa fa-check text-success"></i>'
                                                }
                                                

                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 9,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '';

                                                if($('#filter_type').val() == 2){
                                                      disabled = 'disabled="disabled"'
                                                      ischecked = ''
                                                      $(td).attr('hidden','hidden')
                                                }else{
                                                      $(td).removeAttr('hidden')
                                                }
                                                
                                                if(rowData.subj_per != 0){
                                                      $(td)[0].innerHTML =  '<span style="font-size:.7rem !important">'+rowData.subj_per+'</span>'
                                                }else{
                                                      $(td)[0].innerHTML =  buttons
                                                }

                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    },

                                    {
                                          'targets': 11,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="#" class="edit" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    },
                                    {
                                          'targets': 12,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="#" class="delete" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },

                                    
                              ],
                              initComplete:function( settings, json){
                                    $('.edit').attr('data-toggle', 'popover').attr('data-html', 'true');
                                    $('.edit').popover({
                                          trigger: 'hover',
                                          offset: '0 5',
                                          content: `<span>Edit Subject</span>`,
                                    });

                                    $('.delete').attr('data-toggle', 'popover').attr('data-html', 'true');
                                    $('.delete').popover({
                                          trigger: 'hover',
                                          offset: '0 5',
                                          content: `<span>Delete Subject</span>`,
                                    });
                              }
                              
                        });

                        
                        var label_text = $($('#subject_table_wrapper')[0].children[0])[0].children[0]

                        if($('#filter_type').val() != ""){
                              $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm" id="subject_to_modal" style="font-size:.8rem !important"><i class="fas fa-plus"></i> Add Subject</button>'
                        }else{
                              $(label_text)[0].innerHTML = ''
                        }

                        // dtDeferred.promise().then(function() {
                        //       update_pagination({
                        //             selector: '#subject_table_wrapper',
                        //             initialState: false
                        //       });
                        // });
                        

                        return temp_table

                  }

                  $(document).on('click','.button_to_gradesetup_modal',function(){
                        subjid = $(this).attr('data-id')
                        var subj_info = all_subject.filter(x=>x.id == subjid)
                     
                        $('#subject_holder').text(subj_info[0].text)
                  })

            })
      </script>
      
      {{-- IU --}}
      <script>
            $(document).ready(function(){

                  var keysPressed = {};

                  document.addEventListener('keydown', (event) => {
                        keysPressed[event.key] = true;
                        if (keysPressed['p'] && event.key == 'v') {
                              Toast.fire({
                                          type: 'warning',
                                          title: 'Date Version: 07/26/2021 16:34'
                                    })
                        }
                  });

                  document.addEventListener('keyup', (event) => {
                        delete keysPressed[event.key];
                  });

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })
            })
      </script>

@endsection


