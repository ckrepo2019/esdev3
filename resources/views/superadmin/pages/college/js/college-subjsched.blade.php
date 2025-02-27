<script>
      $(document).ready(function(){

            var modal_form_html = 
            `<div class="modal fade" id="add_subjschedule_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                  <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                              <div class="modal-header pb-2 pt-2 border-0">
                                    <h4 class="modal-title" style="font-size: 1.1rem !important">Schedule Form</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                              </div>
                              <div class="modal-body pt-0">
                              <div class="row">
                                    <div class="col-md-6 border-right pr-3" style="font-size:12px !important">
                                                <div class="row form-group">
                                                      <div class="col-md-6">
                                                            <label>School Year</label>
                                                            <select disabled="disabled" class="form-control form-control-sm select2" id="subjsched_input_sy"></select>
                                                      </div>
                                                      <div class="col-md-6">
                                                            <label>Semster</label>
                                                            <select disabled="disabled" class="form-control form-control-sm select2" id="subjsched_input_sem"></select>
                                                      </div>
                                                </div>
                                                <div class="row form-group">
                                                      <div class="col-md-6">
                                                            <label>Class Type</label>
                                                            <select class="form-control form-control-sm select2" id="subjsched_input_classtype"></select>
                                                      </div>
                                                      <div class="col-md-6">
                                                            <label>Capacity</label>
                                                            <input class="form-control form-control-sm" id="input_subjsched_capacity" style="height: calc(1.619rem + 2px);" oninput="this.value=this.value.replace(/[^0-9]/g,'');" value="50">
                                                      </div>
                                                      <div class="col-md-6" hidden>
                                                            <label>Grade Level</label>
                                                            <select class="form-control form-control-sm select2" id="subjsched_forminput_gradelevel"></select>
                                                      </div>
                                                </div>
                                                <div class="row form-group">
                                                      <div class="col-md-12">
                                                            <label>Schedule Group</label>
                                                            <select class="form-control form-control-sm select2" id="input_subjsched_schedgroup" multiple></select>
                                                      </div>
                                                </div>
                                                <div class="row form-group">
                                                      <div class="col-md-12">
                                                            <label>Subject</label>
                                                            <select class="form-control form-control-sm select2" id="input_subjsched_subj"></select>
                                                      </div>
                                                </div>
                                                <div class="row form-group">
                                                      <div class="col-md-6">
                                                            <label class="mb-0">Lecture</label>
                                                            <p id="label-create-lecunits" class="mb-0">0.0</p>
                                                      </div>
                                                      <div class="col-md-6">
                                                            <label class="mb-0">Laboratory</label>
                                                            <p id="label-create-labunits"  class="mb-0">0.0</p>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <label for="">Instructor</label>
                                                            <select name="input_subjsched_teacher" id="input_subjsched_teacher" class="form-control"></select>
                                                      </div>
                                                </div>
                                          
                                                <hr>
                                    </div>
                                    <div class="col-md-6 pl-3" style="font-size:12px !important">
                                                <div class="row">
                                                      <div class="col-md-6 form-group">
                                                            <label for="">Classification</label>
                                                            <select name="input_subjsched_term" id="input_subjsched_term" class="form-control select2">
                                                            </select>
                                                      </div>
                                                      <div class="col-md-6 form-group">
                                                            <label for="">Room</label>
                                                            <select name="input_subjsched_room" id="input_subjsched_room" class="form-control select2"></select>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-6">
                                                            <div class="form-group">
                                                                  <label for="">Time</label>
                                                                  <input type="text" class="form-control form-control-sm" name="input_subjsched_time" id="input_subjsched_time" style="height: calc(1.619rem + 2px);">
                                                            </div>
                                                      </div>
                                                
                                                
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <label>Day</label>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-3">
                                                            <div class="icheck-primary d-inline mr-3">
                                                                  <input type="checkbox" id="Mon_sujdesc" class="day" value="1" >
                                                                  <label for="Mon_sujdesc">Mon</label>
                                                            </div>
                                                      </div>
                                                      <div class="col-md-3">
                                                            <div class="icheck-primary d-inline mr-3">
                                                                  <input type="checkbox" id="Tue_sujdesc" class="day" value="2" >
                                                                  <label for="Tue_sujdesc">Tue</label>
                                                            </div>
                                                      </div>
                                                      <div class="col-md-3">
                                                            <div class="icheck-primary d-inline mr-3">
                                                                  <input type="checkbox" id="Wed_sujdesc" class="day" value="3" >
                                                                  <label for="Wed_sujdesc">Wed</label>
                                                            </div>
                                                      </div>
                                                </div>
                                                <div class="row mt-1">
                                                      <div class="col-md-3">
                                                            <div class="icheck-primary d-inline">
                                                                  <input type="checkbox" id="Thu_sujdesc" class="day" value="4" >
                                                                  <label for="Thu_sujdesc">Thu
                                                                  </label>
                                                            </div>
                                                      </div>
                                                      <div class="col-md-3">
                                                            <div class="icheck-primary d-inline">
                                                                  <input type="checkbox" id="Fri_sujdesc" class="day" value="5" >
                                                                  <label for="Fri_sujdesc">Fri
                                                                  </label>
                                                            </div>
                                                      </div>
                                                      <div class="col-md-3">
                                                            <div class="icheck-primary d-inline">
                                                                  <input type="checkbox" id="Sat_sujdesc" class="day" value="6" >
                                                                  <label for="Sat_sujdesc">Sat
                                                                  </label>
                                                            </div>
                                                      </div>
                                                      <div class="col-md-3">
                                                            <div class="icheck-primary d-inline">
                                                                  <input type="checkbox" id="Sun_sujdesc" class="day" value="7" >
                                                                  <label for="Sun_sujdesc">Sun
                                                                  </label>
                                                            </div>
                                                      </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                      <div class="col-md-6">
                                                            <button class="btn btn-primary btn-sm" id="create_subjsched"> Create Schedule</button>
                                                      </div>
                                                       <div class="col-md-5" >
                                                            <button class="btn btn-info btn-sm btn-block cnflctBttn_newsched" >Conflict List</button>
                                                      </div>
                                                </div>
                                    </div>
                              </div>
                              </div>
                        </div>
                  </div>
            </div>`


            var conflict_modal = `
                  <div class="modal fade" id="conflict_modal_newsched" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog">
                              <div class="modal-content modal-sm">
                                    <div class="modal-header pb-2 pt-2 border-0" >
                                          <h4 class="modal-title" style="font-size: 1.1rem !important">Schedule Conflict</h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true" >×</span></button>
                                    </div>
                                    <div class="modal-body pt-1" style="font-size:.8rem !important" id="cnflctLstHldr_newsched">
                                          
                                    </div>
                              </div>
                        </div>
                  </div>`

            $('#add_subjschedule_modal').remove();
            $('body').append(modal_form_html)
            $('body').append(conflict_modal)

            var subjsched_gradelevel = [
                  {'id':'17','text':'1ST YEAR','levelname':'1ST YEAR'},
                  {'id':'18','text':'2ND YEAR','levelname':'2ND YEAR'},
                  {'id':'19','text':'3RD YEAR','levelname':'3RD YEAR'},
                  {'id':'20','text':'4TH YEAR','levelname':'4TH YEAR'},
                  {'id':'21','text':'5TH YEAR','levelname':'5TH YEAR'},
            ]


            var class_type = [
                  {'id':'1','text':'Regular class','selected':true},
                  {'id':'2','text':'Special Class'},
            ]

            if(school == 'HCCC'){
                  var sched_classification = [
                        {'id':'1st Term','text':'1st Term','selected':true},
                        {'id':'2nd Term','text':'2nd Term'},
                  ]
            }else{
                  var sched_classification = [
                        {'id':'Lecture','text':'Lecture','selected':true},
                        {'id':'Laboratory','text':'Laboratory'},
                  ]
            }

            



            subjsched_sy()
            subjsched_sem()
            update_input_time()

            function subjsched_sy(){
                  $.ajax({
                        type:'GET',
                        url: '/college/subject/schedule/sy',
                        async: false,
                        success:function(data) {

                        $('#subjsched_input_sy').empty()
                        $('#subjsched_input_sy').append('<option value="">Select School Year</option>')
                        $('#subjsched_input_sy').select2({
                              data: data,
                              placeholder: "Select School Year",
                        })
                        }
                  })
            }


            function subjsched_sem(){
                  $.ajax({
                        type:'GET',
                        url: '/college/subject/schedule/semester',
                        async: false,
                        success:function(data) {

                        $('#subjsched_input_sem').empty()
                        $('#subjsched_input_sem').append('<option value="">Select Semester</option>')
                        $('#subjsched_input_sem').select2({
                              data: data,
                              placeholder: "Select Semester",
                        })
                        }
                  })
            }


            function update_input_time(){

                  $('#input_subjsched_time').daterangepicker({
                        timePicker: true,
                        startDate: '07:00 AM',
                        endDate: '08:00 AM',
                        timePickerIncrement: 5,
                        locale: {
                              format: 'hh:mm A',
                              cancelLabel: 'Clear'
                        }
                  })

            }



            $(document).on('click','.subjsched_form',function(){
                  allowconflict = 0;
                  $('#subjsched_input_sem').val($('#filter_semester').val()).change()
                  $('#subjsched_input_sy').val($('#filter_sy').val()).change()

                  $('#create_subjsched').text('Create Schedule')
                  allowconflict = 0
                  $('#cnflctLstHldr_newsched').empty()
                  clear_form()
                  $('#add_subjschedule_modal').modal()
            })

            $(document).on('click','.edit_subjsched',function(){

                  var temp_sectionid = $(this).attr('data-id')
                  var temp_schedid = $(this).attr('data-schedid')

                  subjsched(temp_sectionid,temp_schedid)
            
                  $('#add_subjschedule_modal').modal()
            })

            $(document).on('click','.cnflctBttn_newsched',function(){
                  $('#conflict_modal_newsched').modal()
            })

            function subjsched(sectionid,schedid){

                  $.ajax({
                        type:'GET',
                        url: '/college/subject/schedule/detail',
                        data:{
                              sectionid:sectionid,
                              schedid:schedid,
                        },
                        success:function(data) {
                        

                        }
                  })



            }


            function clear_form(){
                  $('#subjsched_forminput_gradelevel').val("").change()
                  $('#subjsched_input_classtype').val(1).change()
                  $('#input_subjsched_schedgroup').val("").change()
                  $('#input_subjsched_capacity').val(50)
                  $('#input_subjsched_subj').val("").change()
                  $('#input_subjsched_term').val("Lecture").change()
                  $('#input_subjsched_room').val("").change()
                  update_input_time()
                  $('.day').prop('checked',false)
                  $('#input_subjsched_teacher').val("").change()
            }


            $("#input_subjsched_term").empty()
            $("#input_subjsched_term").append('<option value="">Select Class Type</option>')
            $("#input_subjsched_term").val("")
            $('#input_subjsched_term').select2({
                  data: sched_classification,
                  placeholder: "Select Class Type",
            })

            $("#subjsched_input_classtype").empty()
            $("#subjsched_input_classtype").append('<option value="">Select Class Type</option>')
            $("#subjsched_input_classtype").val("")
            $('#subjsched_input_classtype').select2({
                  data: class_type,
                  placeholder: "Select Class Type",
            })

            $("#subjsched_forminput_gradelevel").empty()
            $("#subjsched_forminput_gradelevel").append('<option value="">Select gradelevel</option>')
            $("#subjsched_forminput_gradelevel").val("")
            $('#subjsched_forminput_gradelevel').select2({
                  data: subjsched_gradelevel,
                  placeholder: "Select gradelevel",
            })

            $('#input_subjsched_schedgroup').select2({
                  placeholder: "Select Schedule Group",
                  theme: 'bootstrap4',
                  allowClear:true,
                  ajax: {
                        url: routes.cllgschdgrpSelect,
                        data: function (params) {
                              var query = {
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    search: params.term,
                                    page: params.page || 0
                              }
                              return query;
                        },
                        dataType: 'json',
                        processResults: function (data, params) {
                              params.page = params.page || 0;

                              return {
                                    results: data.results,
                                    pagination: {
                                          more: data.pagination.more
                                    }
                              };
                        }
                  }
            });

            var filteredsubjects = []


            $('#input_subjsched_subj').select2({
                  placeholder: "Select Subject",
                  ajax: {
                        url: '/college/subject/schedule/subjects',
                        data: function (params) {
                              var query = {
                                    search: params.term,
                                    page: params.page || 0
                              }
                              return query;
                        },
                        dataType: 'json',
                        processResults: function (data, params) {
                              params.page = params.page || 0;
                              return {
                                    results: data.results,
                                    pagination: {
                                          more: data.pagination.more
                                    }
                              };
                        },
                        templateSelection: function (data, container) {
                              $(data.element).attr('data-custom-attribute', 'sdfsdf');
                              return data.text;
                        }
                  }
            }).on('change', function(){
                  var data = $(this).select2('data')
                  if(data.length > 0){
                        $('#label-create-lecunits').text(data[0].lecunits)
                        $('#label-create-labunits').text(data[0].labunits)
                  }else{
                        $('#label-create-lecunits').text(0.0)
                        $('#label-create-labunits').text(0.0)
                  }
                  
            });

            $(document).on('change','#input_subjsched_schedgroup , #input_subjsched_subj , #input_subjsched_teacher, #input_subjsched_room, #input_subjsched_time , .day',function(){
                  $('#create_subjsched').text('Create Schedule')
                  allowconflict = 0
            })
            
           

            $('#input_subjsched_room').select2({
                  placeholder: "All",
                  allowClear:true,
                  ajax: {
                        url: '/college/subject/schedule/rooms',
                        data: function (params) {
                              var query = {
                                    search: params.term,
                                    page: params.page || 0
                              }
                              return query;
                        },
                        dataType: 'json',
                        processResults: function (data, params) {
                              params.page = params.page || 0;
                              return {
                                    results: data.results,
                                    pagination: {
                                          more: data.pagination.more
                                    }
                              };
                        }
                  }
            });


            $('#input_subjsched_teacher').select2({
                  placeholder: "All",
                  allowClear:true,
                  ajax: {
                        url: '/college/subject/schedule/teachers',
                        data: function (params) {
                              var query = {
                                    search: params.term,
                                    page: params.page || 0
                              }
                              return query;
                        },
                        dataType: 'json',
                        processResults: function (data, params) {
                              params.page = params.page || 0;
                              return {
                                    results: data.results,
                                    pagination: {
                                          more: data.pagination.more
                                    }
                              };
                        }
                  }
            });

            var allowconflict = 0
            $(document).on('click','#create_subjsched',function(){
                  create_sched()
            })


            function create_sched(){

                  var days = []

                  $('.day').each(function(){
                        if($(this).is(":checked")){
                              days.push($(this).val())
                        }
                  })

                  
                  // if($('#subjsched_forminput_gradelevel').val() == ""){
                  //       Toast.fire({
                  //             type: 'warning',
                  //             title: "No Grade Level Selected"
                  //       })
                  //       return false
                  // }

                  if($('#input_subjsched_subj').val() == ""){
                        Toast.fire({
                              type: 'warning',
                              title: "No Subject Selected"
                        })
                        return false
                  }

                  if(days.length == 0){
                        Toast.fire({
                              type: 'warning',
                              title: "No days selected"
                        })
                        return false
                  }
                  
                  $.ajax({
                        type:'GET',
                        url: '/college/subject/schedule/addsched',
                        data:{
                              levelid:$('#subjsched_forminput_gradelevel').val(),
                              syid:$('#subjsched_input_sy').val(),
                              semid:$('#subjsched_input_sem').val(),
                              term:$('#input_subjsched_term').val(),
                              room:$('#input_subjsched_room').val(),
                              time:$('#input_subjsched_time').val(),
                              days:days,
                              headerid:$('#input_subjsched_subj').val(),
                              teacherid:$('#input_subjsched_teacher').val(),
                              classtype:$('#subjsched_input_classtype').val(),
                              levelid:$('#subjsched_forminput_gradelevel').val(),
                              capacity:$('#input_subjsched_capacity').val(),
                              schedgroup:$('#input_subjsched_schedgroup').val(),
                              allowconflict:allowconflict
                        },
                        success:function(data) {

                         
                              
                              if(data[0].status == 1){
                                    clear_form()
                                    if (typeof display_sched_collegesections === 'function') {
                                          display_sched_collegesections()
                                    }
                                    if (typeof display_sched === 'function') {
                                          display_sched()
                                    }

                                    $('#create_subjsched').text('Create Schedule')

                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].message
                                    })
                              }
                              else if(data[0].data == 'Conflict'){
                                    $('#create_subjsched').text('Conflict : Proceed Create')
                                    


                                    if(data[0].data == 'Conflict'){
                                          // $('#btn-csl-create_subjsched-sched').text('Conflict : Proceed Update')
                                          allowconflict = 1

                                          $('#cnflctLstHldr_newsched').empty()
                                          $('.cnflctLstHldr_newsched').removeAttr('hidden')

                                    
                                          var text = ``

                                          $.each(data[0].conflict,function(a,b){
                                                text += `<div class="row">
                                                            <div class="col-md-12">
                                                                  <p class="mb-0">Type: `+b.type+`</p>
                                                                  <p class="mb-0">Group: `+b.group+`</p>
                                                                  <p class="mb-0">Subject: `+b.subject+`</p>      
                                                                  <p class="mb-0">Days: `+b.days+`</p>
                                                                  <p class="mb-0">Time: `+b.time+`</p>
                                                            </div>
                                                      </div>`

                                                if(a != ( data[0].conflict.length - 1 )){
                                                      text += '<hr>'
                                                }
                                          })

                                          $('#cnflctLstHldr_newsched')[0].innerHTML = text

                                    }

                                    Toast.fire({
                                          type: 'warning',
                                          title: data[0].message
                                    })

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
</script>