<script>
    var dean_selection_modal = `<div class="modal fade" id="dean_holder_modal" style="display: none; z-index:" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                  <div class="modal-content">
                        <div class="modal-header pb-2 pt-2 border-0">
                              <h4 class="modal-title"></h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body" style="font-size:.9rem">
                        <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Dean</label>
                                          <select class="form-control select2" id="printable_dean">

                                          </select>
                                    </div>
                        </div>
                        <div class="row">
                                    <div class="col-md-12">
                                          <button class="btn btn-primary btn-sm" id="print_grades">Print</button>
                                    </div>
                        </div>
                        </div>
                  </div>
            </div>
      </div>  
`



    var modal_schedule_information_form_html =
        `<div class="modal fade" id="csl-schedule-information-form" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-header pb-2 pt-2 border-0">
                              <h4 class="modal-title" style="font-size: 1.1rem !important">Subject Schedule Form</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body pt-0" style="font-size: .7rem !important">
                              <div class="row form-group">
                                    <div class="col-md-6">
                                          <label>Class Type</label>
                                          <select class="form-control form-control-sm select2" id="csl_input_classtype"></select>
                                    </div>
                                    <div class="col-md-6">
                                          <label>Capacity</label>
                                          <input class="form-control form-control-sm" id="csl_input_capacity" style="height: calc(1.619rem + 2px);" oninput="this.value=this.value.replace(/[^0-9]/g,'');" value="50">
                                    </div>
                                    <div class="col-md-6" hidden>
                                          <label>Grade Level</label>
                                          <select class="form-control form-control-sm select2" id="csl_input_gradelevel"></select>
                                    </div>
                              </div>
                              <div class="row form-group">
                                    <div class="col-md-12">
                                          <label>Section</label>
                                          <select class="form-control form-control-sm select2"   multiple="multiple" id="csl_input_schedgroup"></select>
                                    </div>
                                   
                              </div>
                              <div class="row form-group">
                                    <div class="col-md-12">
                                          <label>Subject</label>
                                          <select class="form-control form-control-sm select2" id="csl_input_subject"></select>
                                    </div>
                              </div>
                              <div class="row form-group">
                                    <div class="col-md-6">
                                          <label class="mb-0">Lecture</label>
                                          <p id="label-lecunits">0.0</p>
                                    </div>
                                    <div class="col-md-6">
                                          <label  class="mb-0">Laboratory</label>
                                          <p id="label-labunits">0.0</p>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-6">
                                          <button class="btn btn-success btn-sm" id="btn-csl-update-sched_detail">Update</button>
                                    </div>
                              </div>
                        </div>
                        
                  </div>
            </div>
      </div>`


    var add_schedlist_datatable = `
            <div class="modal fade" id="available_sched_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                  <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                              <div class="modal-header pb-2 pt-2 border-0">
                                    <h4 class="modal-title" style="font-size: 1.1rem !important">Schedule List</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                              </div>
                              <div class="modal-body pt-0">
                                    <div class="row"  style="font-size:.7rem !important">
                                          <div class="col-md-2" hidden="hidden">
                                                <label for="">School Year</label>
                                                <select class="form-control form-control-sm" id="filter_schedlist_sy" >

                                                </select>
                                          </div>
                                          <div class="col-md-2" hidden="hidden">
                                                <label for="">Semster</label>
                                                <select class="form-control form-control-sm" id="filter_schedlist_sem">

                                                </select>
                                          </div>
                                          <div class="col-md-2" hidden>
                                                <label for="">Subject Code</label>
                                                <select class="form-control form-control-sm" id="filter_sched_section">
            
                                                </select>
                                          </div>
                                          <div class="col-md-2">
                                                <label for="">Subject Code</label>
                                                <select class="form-control select2 form-control-sm" id="filter_sched_subjcode">
            
                                                </select>
                                          </div>
                                          <div class="col-md-2">
                                                <label for="">Subject Description</label>
                                                <select class="form-control form-control-sm select2" id="filter_sched_subjdesc">
            
                                                </select>
                                          </div>
                                          <div class="col-md-2">
                                                <label for="">Schedule Group</label>
                                                <select class="form-control form-control-sm select2" id="filter_sched_subjgroup">
            
                                                </select>
                                          </div>
                                    </div>
                                    <hr>
                                    <div class="row" >
                                          <div class="col-md-12" style="font-size:.75rem !important">
                                                <table class="table-hover table table-striped table-sm table-bordered" id="available_sched_datatable" width="100%" >
                                                      <thead>
                                                            <tr>
                                                                  <th rowspan="2" width="9%" class="text-center p-0 align-middle">Section / Block</th>
                                                                  <th rowspan="2" width="8%" class="p-0 align-middle pl-2" style="font-size:.65rem !important">Subject Code</th>
                                                                  <th rowspan="2" width="25%" class="align-middle text-center">Subject Description</th>
                                                                  <th class="p-0 align-middle text-center" width="4%" colspan="2">Units</th>
                                                                  <th rowspan="2" class="text-center p-0 align-middle" width="4%">Cap.</th>
                                                                  <th rowspan="2" class="text-center p-0 align-middle" width="6%">Students</th>
                                                                  <th rowspan="2" width="24%" class="align-middle text-center">Schedule</th>
                                                                  <th rowspan="2" width="10%" class="align-middle text-center">Instructor</th>
                                                                  <th rowspan="2" width="13%" id="addAllHolder">Action</th>
                                                            </tr>
                                                            <tr>
                                                                  <th class="text-center p-1 border-right-1" style="font-size:.6rem !important">Lec</th>
                                                                  <th class="text-center p-1" style="font-size:.6rem !important; border-right: 1px solid #dee2e6;font-size:.6rem !important">Lab</th>
                                                            </tr>
                                                      </thead>
                                                </table>
                                          </div>
                                    </div>
                                    
                              </div>
                        </div>
                  </div>
                              </div> `

    //college sched list = csl
    var add_sched_form = `<div class="modal fade" id="csl-schedule-form" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                        <div class="modal-header pb-2 pt-2 border-0">
                              <h4 class="modal-title" style="font-size: 1.1rem !important">Subject Schedule Form</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-7 border-right" style="font-size:12px !important">
                                    <div class="row form-group">
                                          <div class="col-md-12">
                                                <label class="mb-0">Subject</label>
                                                <p class="text-muted mb-0" id="input-csl-subject">1st Semester</p>
                                          </div>
                                    </div>
                                    <div class="row form-group">
                                          <div class="col-md-12">
                                                <label>Schedule List</label>
                                          </div>
                                          <div class="col-md-12">
                                                <table class="table table-sm table-bordered" width="100%" style="font-size:.7rem !important">
                                                      <thead>
                                                            <tr>
                                                                  <th width="20%">Classification</th>
                                                                  <th width="34%">Time</th>
                                                                  <th width="10%" class="text-center">Day</th>
                                                                  <th width="26%">Room</th>
                                                                  <th width="5%"></th>
                                                                  <th width="5%"></th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="csl-schedule-holder">
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                              <div class="col-md-5" style="font-size:12px !important">
                                          <div class="row">
                                          <div class="col-md-6 form-group">
                                                <label for="">Classification</label>
                                                <select name="input_subjsched_term" id="input-csl-classification" class="form-control select2">
                                                </select>
                                          </div>
                                          <div class="col-md-6 form-group">
                                                <label for="">Room</label>
                                                <select name="input_subjsched_room" id="input-csl-room" class="form-control select2"></select>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-6">
                                                <div class="form-group">
                                                      <label for="">Time</label>
                                                      <input type="text" class="form-control form-control-sm" name="" id="input-csl-time" style="height: calc(1.619rem + 2px);">
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
                                                      <input type="checkbox" id="Mon_csl" class="csl-day" value="1" >
                                                      <label for="Mon_csl">Mon</label>
                                                </div>
                                          </div>
                                          <div class="col-md-3">
                                                <div class="icheck-primary d-inline mr-3">
                                                      <input type="checkbox" id="Tue_csl" class="csl-day" value="2" >
                                                      <label for="Tue_csl">Tue</label>
                                                </div>
                                          </div>
                                          <div class="col-md-3">
                                                <div class="icheck-primary d-inline mr-3">
                                                      <input type="checkbox" id="Wed_csl" class="csl-day" value="3" >
                                                      <label for="Wed_csl">Wed</label>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="row mt-1">
                                          <div class="col-md-3">
                                                <div class="icheck-primary d-inline">
                                                      <input type="checkbox" id="Thu_csl" class="csl-day" value="4" >
                                                      <label for="Thu_csl">Thu
                                                      </label>
                                                </div>
                                          </div>
                                          <div class="col-md-3">
                                                <div class="icheck-primary d-inline">
                                                      <input type="checkbox" id="Fri_csl" class="csl-day" value="5" >
                                                      <label for="Fri_csl">Fri
                                                      </label>
                                                </div>
                                          </div>
                                          <div class="col-md-3">
                                                <div class="icheck-primary d-inline">
                                                      <input type="checkbox" id="Sat_csl" class="csl-day" value="6" >
                                                      <label for="Sat_csl">Sat
                                                      </label>
                                                </div>
                                          </div>
                                          <div class="col-md-3">
                                                <div class="icheck-primary d-inline">
                                                      <input type="checkbox" id="Sun_csl" class="csl-day" value="7" >
                                                      <label for="Sun_csl">Sun
                                                      </label>
                                                </div>
                                          </div>
                                    </div>
                                    <hr class="mb-1">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <p id="day-selection-message" style="font-size:.9rem !important"></p>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-8">
                                                <button class="btn btn-primary btn-sm" id="btn-csl-create-sched"> Create Schedule</button>
                                                <button class="btn btn-success btn-sm" id="btn-csl-update-sched" hidden> Update Schedule</button>
                                                <button hidden class="btn btn-info btn-sm  cnflctBttn cnflctButtonHolder" >Conflict List</button>
                                                <button class="btn btn-danger btn-sm" id="btn-csl-delete-sched" hidden> Delete Schedule</button>
                                          </div>
                                          <div class="col-md-4">
                                                <button class="btn btn-danger btn-sm float-right" id="btn-csl-cancel-form" hidden>Cancel</button>
                                          </div>
                                    </div>
                        </div>
                  </div>
                  </div>
            </div>
      </div>
                              </div>`

    var teacher_form_modal = `
            <div class="modal fade" id="modal-csl-update-instructor" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                  <div class="modal-dialog">
                        <div class="modal-content modal-sm">
                              <div class="modal-header pb-2 pt-2 border-0" >
                                    <h4 class="modal-title" style="font-size: 1.1rem !important">Schedule Instructor</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" >×</span></button>
                              </div>
                              <div class="modal-body pt-1" style="font-size:.8rem !important" >
                                    <div class="row">
                                          <div class="col-md-12">
                                                <strong>Subject</strong>
                                                <p class="text-muted label-csl-subject"></p>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12 form-group">
                                                <label for="">Instructor</label>
                                                <select name="csl_teacher" id="input-csl-teacher" class="form-control select2 form-control-sm"></select>
                                          </div>
                                    </div>
                                  
                                    <div class="row">
                                          <div class="col-md-7">
                                                <button class="btn btn-sm btn-primary btn-block" id="btn-csl-update-instructor">Update</button>
                                          </div>
                                          <div class="col-md-5 cnflctButtonHolder" hidden>
                                                <button class="btn btn-info btn-sm btn-block cnflctBttn" >Conflict List</button>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>`

    var conflict_modal = `
            <div class="modal fade" id="conflict_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                  <div class="modal-dialog">
                        <div class="modal-content modal-sm">
                              <div class="modal-header pb-2 pt-2 border-0" >
                                    <h4 class="modal-title" style="font-size: 1.1rem !important">Schedule Conflict</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" >×</span></button>
                              </div>
                              <div class="modal-body pt-1" style="font-size:.8rem !important" id="cnflctLstHldr">
                                    
                              </div>
                        </div>
                  </div>
            </div>`

    var capacity_form_modal = `
            <div class="modal fade" id="modal-csl-update-capacity" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                  <div class="modal-dialog">
                        <div class="modal-content modal-sm">
                              <div class="modal-header pb-2 pt-2 border-0" >
                                    <h4 class="modal-title" style="font-size: 1.1rem !important">Schedule Capacity</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" >×</span></button>
                              </div>
                              <div class="modal-body pt-1" style="font-size:.8rem !important" >
                                    <div class="row">
                                          <div class="col-md-12">
                                                <strong>Subject</strong>
                                                <p class="text-muted label-csl-subject"></p>
                                          </div>
                                    </div>
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Schedule Capacity</label>
                                          <input class="form-control form-control-sm" id="input-csl-capacity">
                                    </div>
                              </div>
                                    <div class="row">
                                          <div class="col-md-12">
                                                <button class="btn btn-sm btn-primary" id="btn-csl-update-capacity">Update</button>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                              </div>`





    var enrolled_form_modal = `<div class="modal fade" id="enrolled_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                  <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                              <div class="modal-header pb-2 pt-2 border-0">
                                    <h4 class="modal-title" style="font-size: 1.1rem !important">Student List <span id="student_list_type"></span></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                              </div>
                              <div class="modal-body pt-0">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <label for="" id="list_label"></label>
                                          </div>
                                    </div>
                              <div class="row">
                                    <div class="col-md-12" style="font-size:.7rem !important">
                                          <table class="table table-striped table-sm table-bordered table-head-fixed p-0" id="student_list" width="100%"  >
                                                <thead>
                                                      <tr>
                                                            <th width="50%">Students</th>
                                                            <th width="25%">Grade Level</th>
                                                            <th width="25%">Course</th>
                                                      </tr>
                                                </thead>
                                          </table>
                                    </div>
                              </div>
                              </div>
                        </div>
                  </div>
            </div>  `

    var class_type = [{
            'id': '1',
            'text': 'Regular class',
            'selected': true
        },
        {
            'id': '2',
            'text': 'Special Class'
        },

    ]

    var school  = "HCCC";


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
    
    var subjsched_gradelevel = [{
            'id': '17',
            'text': '1ST YEAR',
            'levelname': '1ST YEAR'
        },
        {
            'id': '18',
            'text': '2ND YEAR',
            'levelname': '2ND YEAR'
        },
        {
            'id': '19',
            'text': '3RD YEAR',
            'levelname': '3RD YEAR'
        },
        {
            'id': '20',
            'text': '4TH YEAR',
            'levelname': '4TH YEAR'
        },
        {
            'id': '21',
            'text': '5TH YEAR',
            'levelname': '5TH YEAR'
        },
    ]


    var allowconflict = 0;

    $('body').append(add_schedlist_datatable)
    $('body').append(capacity_form_modal)
    $('body').append(teacher_form_modal)
    $('body').append(add_sched_form)
    $('body').append(modal_schedule_information_form_html)

    $('body').append(enrolled_form_modal)
    $('body').append(conflict_modal)
    $('body').append(dean_selection_modal)


    $("#input-csl-classification").empty()
    $("#input-csl-classification").append('<option value="">Select Class Type</option>')
    $("#input-csl-classification").val("")
    $('#input-csl-classification').select2({
        data: sched_classification,
        placeholder: "Select Class Type",
    })

    update_input_time()

    $('#input-csl-room').select2({
        placeholder: "All",
        allowClear: true,
        ajax: {
            url: '/college/schedule/list/rooms',
            data: function(params) {
                var query = {
                    search: params.term,
                    page: params.page || 0
                }
                return query;
            },
            dataType: 'json',
            processResults: function(data, params) {
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

    $(document).on('change', '#filter_sched_section', function() {
        $('#filter_sched_subjdesc').empty();
        $('#filter_sched_subjcode').empty();
        if (typeof display_sched_collegesections === 'function') {
            display_sched_collegesections()
            return false
        }
        display_sched_csl(p_url, p_studid, p_entype)
    })

    $(document).on('change', '#filter_sched_subjdesc', function() {
        $('#filter_sched_section').empty();
        $('#filter_sched_subjcode').empty();
        if (typeof display_sched_collegesections === 'function') {
            display_sched_collegesections()
            return false
        }
        display_sched_csl(p_url, p_studid, p_entype)
    })

    $(document).on('change', '#filter_sched_subjgroup', function() {
        $('#filter_sched_subjdesc').empty();
        $('#filter_sched_subjcode').empty();
        if (typeof display_sched_collegesections === 'function') {
            display_sched_collegesections()
            return false
        }
        display_sched_csl(p_url, p_studid, p_entype)
    })

    $(document).on('change', '#filter_sched_subjcode', function() {
        $('#filter_sched_section').empty();
        $('#filter_sched_subjdesc').empty();
        if (typeof display_sched_collegesections === 'function') {
            display_sched_collegesections()
            return false
        }
        display_sched_csl(p_url, p_studid, p_entype)
    })

    $(document).on('click', '#btn-csl-update-capacity', function() {
        update_capacity()
    })

    $(document).on('click', '#btn-csl-update-instructor', function() {
        update_teacher()
    })

    $(document).on('click', '#btn-csl-update-sched_detail', function() {
        update_schedule_detail()
    })

    $(document).on('click', '.btn-csl-remove-schedule', function() {
        var tempid = $(this).attr('data-id')
        Swal.fire({
                title: 'Are you sure want to remove schedule?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete schedule!'
            })
            .then((result) => {
                if (result.value) {
                    seleted_id = tempid
                    remove_schedule(tempid)
                }

            })
    })

    $("#csl_input_classtype").empty()
    $("#csl_input_classtype").append('<option value="">Select Class Type</option>')
    $("#csl_input_classtype").val("")
    $('#csl_input_classtype').select2({
        data: class_type,
        placeholder: "Select Class Type",
    })

    $("#csl_input_gradelevel").empty()
    $("#csl_input_gradelevel").append('<option value="">Select gradelevel</option>')
    $("#csl_input_gradelevel").val("")
    $('#csl_input_gradelevel').select2({
        data: subjsched_gradelevel,
        placeholder: "Select gradelevel",
    })

    load_csl_resource()

    function load_csl_resource() {

        $('#csl_input_schedgroup').select2({
            placeholder: "Select Schedule Group",
            // allowClear:true,
            theme: 'bootstrap4',
            ajax: {
                url: routes.cllgschdgrpSelect,
                data: function(params) {
                    var query = {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        search: params.term,
                        page: params.page || 0
                    }
                    return query;
                },
                dataType: 'json',
                processResults: function(data, params) {
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

        $('#filter_sched_subjdesc').select2({
            placeholder: "All",
            allowClear: true,
            ajax: {
                url: '/student/loading/allsched/filter',
                data: function(params) {
                    var query = {
                        type: 'subjdesc',
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        search: params.term,
                        page: params.page || 0
                    }
                    return query;
                },
                dataType: 'json',
                processResults: function(data, params) {
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

        $('#filter_sched_section').select2({
            placeholder: "All",
            allowClear: true,
            ajax: {
                url: '/student/loading/allsched/filter',
                data: function(params) {
                    var query = {
                        type: 'section',
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        search: params.term,
                        page: params.page || 0
                    }
                    return query;
                },
                dataType: 'json',
                processResults: function(data, params) {
                    params.page = params.page || 0;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.more
                        }
                    };
                }
            }
        });


        $('#filter_sched_subjcode').select2({
            placeholder: "All",
            allowClear: true,
            ajax: {
                url: '/student/loading/allsched/filter',
                data: function(params) {
                    var query = {
                        type: 'subjcode',
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        search: params.term,
                        page: params.page || 0
                    }
                    return query;
                },
                dataType: 'json',
                processResults: function(data, params) {
                    params.page = params.page || 0;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.more
                        }
                    };
                }
            }
        });

        $('#filter_sched_subjgroup').select2({
            placeholder: "All",
            allowClear: true,
            ajax: {
                url: routes.cllgschdgrpSelect,
                data: function(params) {
                    var query = {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        search: params.term,
                        page: params.page || 0
                    }
                    return query;
                },
                dataType: 'json',
                processResults: function(data, params) {
                    params.page = params.page || 0;

                    // cosole.log(data.pagination.more)
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                }
            }
        });

        $('#csl_input_subject').select2({
            placeholder: "Select Subject",
            delay: 250,
            ajax: {
                url: '/setup/prospectus/subjects/select',
                data: function(params) {
                    var query = {
                        search: params.term,
                        page: params.page || 0
                    }
                    return query;
                },
                dataType: 'json',
                processResults: function(data, params) {
                    params.page = params.page || 0;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                }
            }
        }).on('change', function() {
            var data = $(this).select2('data')
            if (data.length > 0) {

                $('#label-lecunits').text(data[0].lecunits)
                $('#label-labunits').text(data[0].labunits)
            } else {
                $('#label-lecunits').text(0.0)
                $('#label-labunits').text(0.0)
            }
        });;

    }

    function update_input_time() {

        $('#input-csl-time').daterangepicker({
            timePicker: true,
            startDate: '07:30 AM',
            endDate: '08:30 AM',
            timePickerIncrement: 5,
            locale: {
                format: 'hh:mm A',
                cancelLabel: 'Clear'
            }
        })

    }

    function remove_scheduledetail(tempid) {

        var temp_info = all_sched.filter(x => x.id == tempid)

        var sched_tobe_deleted = []

        $('.csl-day').each(function() {
            if ($(this).is(":checked")) {
                var tempid = selected_detail.filter(x => x.day == $(this).val())
                sched_tobe_deleted.push(tempid[0].id)
            }
        })


        $.ajax({
            type: 'GET',
            url: '/college/schedule/list/remove',
            data: {
                syid: temp_info[0].syID,
                semid: temp_info[0].semesterID,
                sectionid: temp_info[0].sectionID,
                schedid: temp_info[0].id,
                tobe_deleted: sched_tobe_deleted
            },
            success: function(data) {
                //     console.log(data[0].creator);
                //     console.log({{ Auth::user()->id }});
                Toast.fire({
                    type: data[0].stat,
                    title: data[0].message
                })

                if (data[0].status == 1) {
                    $('#btn-csl-cancel-form').attr('hidden', 'hidden')
                    if (typeof display_sched_collegesections === 'function') {
                        display_sched_collegesections()
                        // Toast.fire({
                        //     type: 'success',
                        //     title: data[0].message
                        // })
                        return false
                    }
                    display_sched_csl(p_url, p_studid, p_entype)
                    //   Toast.fire({
                    //       type: 'success',
                    //       title: data[0].message
                    //   })
                }
            }
        })
    }



    function update_capacity() {
        $.ajax({
            type: 'GET',
            url: '/college/schedule/list/update/capacity',
            data: {
                capacity: $('#input-csl-capacity').val(),
                schedid: seleted_id
            },
            success: function(data) {
                if (data[0].status == 1) {
                    if (typeof display_sched_collegesections === 'function') {
                        display_sched_collegesections()
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                        return false
                    }
                    display_sched_csl(p_url, p_studid, p_entype)
                }

                Toast.fire({
                    type: 'success',
                    title: data[0].message
                })
            }
        })
    }

    function update_teacher() {
        $('#cnflctLstHldr').empty()
        $('.cnflctButtonHolder').attr('hidden', 'hidden')
        $.ajax({
            type: 'GET',
            url: '/college/schedule/list/update/teacher',
            data: {
                teacherid: $('#input-csl-teacher').val(),
                schedid: seleted_id,
                allowconflict: allowconflict
            },
            success: function(data) {
                if (data[0].status == 1) {
                    $('#btn-csl-update-instructor').text('Update')
                    allowconflict = 0
                    if (typeof display_sched_collegesections === 'function') {
                        display_sched_collegesections()
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                        return false
                    }
                    display_sched_csl(p_url, p_studid, p_entype)
                    Toast.fire({
                        type: 'success',
                        title: data[0].message
                    })
                } else {

                    if (data[0].data == 'Conflict') {
                        $('#btn-csl-update-instructor').text('Conflict : Proceed Create')
                        allowconflict = 1
                        $('.cnflctButtonHolder').removeAttr('hidden')


                        var text = ``

                        $.each(data[0].conflict, function(a, b) {
                            text += `<div class="row">
                                                      <div class="col-md-12">
                                                            <p class="mb-0">Type: ` + b.type + `</p>
                                                            <p class="mb-0">Group: ` + b.group + `</p>
                                                            <p class="mb-0">Subject: ` + b.subject + `</p>      
                                                            <p class="mb-0">Days: ` + b.days + `</p>
                                                            <p class="mb-0">Time: ` + b.time + `</p>
                                                      </div>
                                                </div>`

                            if (a != (data[0].conflict.length - 1)) {
                                text += '<hr>'
                            }
                        })

                        $('#cnflctLstHldr')[0].innerHTML = text
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }


            }
        })
    }

    function remove_schedule() {

        var sched_tobe_deleted = []

        var temp_info = all_sched.filter(x => x.id == seleted_id)

        $('.csl-day').each(function() {
            if ($(this).is(":checked")) {
                var tempid = selected_detail.filter(x => x.day == $(this).val())
                sched_tobe_deleted.push(tempid[0].id)
            }
        })


        $.ajax({
            type: 'GET',
            url: '/college/schedule/list/remove/sched',
            data: {
                syid: temp_info[0].syID,
                semid: temp_info[0].semesterID,
                sectionid: temp_info[0].sectionID,
                schedid: seleted_id,
                tobe_deleted: sched_tobe_deleted
            },
            success: function(data) {
                if (data[0].status == 1) {
                    if (typeof display_sched_collegesections === 'function') {
                        display_sched_collegesections()
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                        return false
                    }
                    display_sched_csl(p_url, p_studid, p_entype)
                }



                Toast.fire({
                    type: 'success',
                    title: data[0].message
                })
            }
        })

    }

    function update_schedule() {

        $('#cnflctLstHldr').empty()
        $('.cnflctButtonHolder').attr('hidden', 'hidden')

        var time = $('#input-csl-time').val()
        var days = []
        var sched_tobe_updated = []

        $('.csl-day').each(function() {
            if ($(this).is(":checked")) {
                days.push($(this).val())
            }
        })
        if (days.length == 0) {
            Toast.fire({
                type: 'warning',
                title: "No days selected"
            })
            return false
        }

        $('.csl-day').each(function() {
            if ($(this).is(":checked")) {
                var tempid = selected_detail.filter(x => x.day == $(this).val())
                if (tempid.length > 0) {
                    sched_tobe_updated.push(tempid[0].id)
                } else {
                    days.push($(this).val())
                }
            }
        })
        
        console.log(sched_tobe_updated,'masaya');
    
        $.ajax({
            type: 'GET',
            url: '/college/schedule/list/update/sched',
            data: {
                room: $('#input-csl-room').val(),
                time: time,
                schedotherclas: $('#input-csl-classification').val(),
                days: days,
                schedid: seleted_id,
                allowconflict: allowconflict,
                tobe_updated: sched_tobe_updated
            },
            success: function(data) {
                if (data[0].status == 1) {
                    $('#btn-csl-cancel-form').attr('hidden', 'hidden')
                    allowconflict = 0
                    if (typeof display_sched_collegesections === 'function') {
                        display_sched_collegesections()
                        Toast.fire({
                            type: 'success',
                            title: 'Schedule Updated!'
                        })
                        return false
                    }
                    display_sched_csl(p_url, p_studid, p_entype)
                    Toast.fire({
                        type: 'success',
                        title: 'Schedule Updated!'
                    })
                } else {

                    if (data[0].data == 'Conflict') {
                        $('#btn-csl-update-sched').text('Conflict : Proceed Update')
                        allowconflict = 1

                        $('#cnflctLstHldr').empty()
                        $('.cnflctButtonHolder').removeAttr('hidden')


                        var text = ``

                        $.each(data[0].conflict, function(a, b) {
                            text += `<div class="row">
                                                      <div class="col-md-12">
                                                            <p class="mb-0">Type: ` + b.type + `</p>
                                                            <p class="mb-0">Group: ` + b.group + `</p>
                                                            <p class="mb-0">Subject: ` + b.subject + `</p>      
                                                            <p class="mb-0">Days: ` + b.days + `</p>
                                                            <p class="mb-0">Time: ` + b.time + `</p>
                                                      </div>
                                                </div>`

                            if (a != (data[0].conflict.length - 1)) {
                                text += '<hr>'
                            }
                        })

                        $('#cnflctLstHldr')[0].innerHTML = text

                    }
                    Toast.fire({
                        type: 'warning',
                        title: data[0].message
                    })
                }




            }
        })
    }

    function update_schedule_detail() {

        if ($('#csl_input_gradelevel').val() == "") {
            Toast.fire({
                type: 'warning',
                title: "No Grade Level Selected"
            })
            return false
        }

        if ($('#csl_input_subject').val() == "") {
            Toast.fire({
                type: 'warning',
                title: "No Subject Selected"
            })
            return false
        }

        $.ajax({
            type: 'GET',
            url: '/college/schedule/list/update/scheddetail',
            data: {
                syid: $('#filter_sy').val(),
                semid: $('#filter_semester').val(),
                id: seleted_id,
                classtype: $('#csl_input_classtype').val(),
                levelid: $('#csl_input_gradelevel').val(),
                capacity: $('#csl_input_capacity').val(),
                schedgroup: $('#csl_input_schedgroup').val(),
                headerid: $('#csl_input_subject').val(),
            },
            success: function(data) {
                if (data[0].status == 1) {
                    if (typeof display_sched_collegesections === 'function') {
                        display_sched_collegesections()
                        Toast.fire({
                            type: 'success',
                            title: 'Detail Updated!'
                        })
                        return false
                    }
                    display_sched_csl(p_url, p_studid, p_entype)
                    Toast.fire({
                        type: 'success',
                        title: 'Detail Updated!'
                    })
                } else {


                    Toast.fire({
                        type: 'warning',
                        title: data[0].data
                    })
                }


            }
        })
    }

    function create_schedule() {

        var time = $('#input-csl-time').val()
        var days = []

        $('.csl-day').each(function() {
            if ($(this).is(":checked")) {
                days.push($(this).val())
            }
        })
        if (days.length == 0) {
            Toast.fire({
                type: 'warning',
                title: "No days selected"
            })
            return false
        }


        $.ajax({
            type: 'GET',
            url: '/college/schedule/list/create/sched',
            data: {
                room: $('#input-csl-room').val(),
                time: time,
                schedotherclas: $('#input-csl-classification').val(),
                days: days,
                schedid: seleted_id,
                allowconflict: allowconflict
            },
            success: function(data) {
                if (data[0].status == 1) {
                    if (typeof display_sched_collegesections === 'function') {
                        display_sched_collegesections()
                        Toast.fire({
                            type: 'success',
                            title: 'Schedule Created!'
                        })
                        return false
                    }
                    display_sched_csl(p_url, p_studid, p_entype)
                    Toast.fire({
                        type: 'success',
                        title: 'Schedule Created!'
                    })
                } else {
                    Toast.fire({
                        type: 'warning',
                        title: 'Something went wrong!'
                    })
                }
            }
        })
    }

    var all_sched = []
    var all_sched_section = []
    var all_sched_enrolled = []
    var all_sched_detail = []
    var all_sched_student = []
    var all_sched_groupdetail = []
    var seleted_id = null
    var selected_detail = []
    var p_url = null
    var p_studid = null
    var p_entype = null

    function display_sched_csl(url = '', studid = null, entype = 'REGULAR') {

        if (p_url == null) {
            p_url = url
        }

        // if(p_studid == null){
        p_studid = studid
        // }

        if (p_entype == null) {
            p_entype = entype
        }


        $("#available_sched_datatable").DataTable({
            destroy: true,
            autoWidth: false,
            stateSave: true,
            lengthChange: false,
            serverSide: true,
            processing: true,
            ajax: {
                url: '/student/loading/allsched',
                type: 'GET',
                data: {
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_semester').val(),
                    filtersection: $('#filter_sched_section').val(),
                    filtersubjcode: $('#filter_sched_subjdesc').val(),
                    filtersubjdesc: $('#filter_sched_subjcode').val(),
                    filtersubjgroup: $('#filter_sched_subjgroup').val(),
                    url: p_url,
                    studid: studid
                },
                dataSrc: function(json) {
                    all_sched = json.data[0].college_classsched
                    all_sched_section = json.data[0].section
                    all_sched_enrolled = json.data[0].enrolled
                    all_sched_detail = json.data[0].scheddetail
                    all_sched_student = json.data[0].all_stud_sched
                    all_sched_groupdetail = json.data[0].sched_group_detail

                    if (seleted_id != null) {
                        empty_form()
                        csl_sched_form_detail()
                    }

                    return all_sched;
                }
            },
            order: [
                [1, "asc"]
            ],
            columns: [

                {
                    "data": "schedgroupdesc"
                },
                {
                    "data": "subjCode"
                },
                {
                    "data": "subjDesc"
                },
                {
                    "data": "lecunits"
                },
                {
                    "data": "labunits"
                },
                {
                    "data": "capacity"
                },
                {
                    "data": null
                },
                {
                    "data": null
                },
                {
                    "data": null
                },
                {
                    "data": null
                },
            ],
            columnDefs: [{
                    'targets': 8,
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        var temp_data = all_sched_detail.filter(x => x.headerID == rowData.id)



                        if (rowData.lastname != null) {
                            $(td)[0].innerHTML = rowData.lastname + ', ' + rowData.firstname +
                                '<p class="mb-0" style="font-size:.7rem" data-se>' + rowData.tid +
                                '</p>';

                        } else {
                            $(td)[0].innerHTML = null
                            // $(td)[0].innerHTML = '<a style="font-size:.65rem !important" href="javascript:void(0)" class="add_teacher" data-id="'+rowData.id+'" data-subjdesc="Push-up"  data-text="'+rowData.subjCode+' : '+rowData.subjDesc+'">Add Teacher</a>'
                        }

                        $(td).addClass('align-middle')
                        $(td).attr('style', 'font-size:.6rem !important')
                    }
                },
                {
                    'targets': 0,
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {

                        if ($('#filter_sched_subjgroup').val() != null && $('#filter_sched_subjgroup')
                            .val() != undefined) {
                            var schedgroup_detail = all_sched_groupdetail.filter(x => x.schedid ==
                                rowData.id && x.id == $('#filter_sched_subjgroup').val())
                        } else {
                            var schedgroup_detail = all_sched_groupdetail.filter(x => x.schedid ==
                                rowData.id)
                        }

                        var text = '';

                        $.each(schedgroup_detail, function(a, b) {
                            text +=
                                '<span class="badge badge-primary btn-block mt-1" style="font-size:.65rem !important; white-space:normal">' +
                                b.text + '</span>'
                        })
                        // var sectiondesc = all_sched_section.filter(x=>x.id == rowData.sectionID)
                        // if(sectiondesc.length > 0){
                        //       $(td).text(sectiondesc[0].sectionDesc)
                        // }else{
                        //       $(td).text(null)
                        // }

                        $(td)[0].innerHTML = text
                        $(td).addClass('align-middle')

                    }
                },
                {
                    'targets': 1,
                    'orderable': true,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        // var text = '<span class="mb-0" style="font-size:.9rem">'+rowData.subjCode+'</span>';
                        // $(td)[0].innerHTML = text
                        $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 2,
                    'orderable': true,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        // var text = '<span class="mb-0" style="font-size:.8rem">'+rowData.subjDesc+'</span>';
                        // $(td)[0].innerHTML = text
                        $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 3,
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {


                        $(td).addClass('text-center')
                        $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 4,
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-center')
                        $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 5,
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-center')
                        $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 6,
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        var enrolled_count = all_sched_enrolled.filter(x => x.schedid == rowData.id)
                        var all_loaded_student_count = all_sched_student.filter(x => x.schedid ==
                            rowData.id)
                        var enrolled = 0
                        var loaded = 0
                        if (enrolled_count.length > 0) {
                            enrolled = enrolled_count[0].enrolled
                        }

                        if (all_loaded_student_count.length > 0) {
                            loaded = all_loaded_student_count[0].enrolled
                        }

                        $(td)[0].innerHTML = '<a href="javascript:void(0)" data-id="' + rowData.id +
                            '"' + '" class="sched_list_students" data-text="' + rowData.subjCode +
                            ' - ' + rowData.subjDesc +
                            '" data-toggle="tooltip" data-placement="top" title="" data-original-title="Enrolled Students">' +
                            enrolled + '</a>' + ' / ' + '<a href="javascript:void(0)" data-id="' +
                            rowData.id + '"' + '" class="sched_list_loaded_students" data-text="' +
                            rowData.subjCode + ' - ' + rowData.subjDesc +
                            '" data-toggle="tooltip" data-placement="top" title="" data-original-title="Loaded Students">' +
                            loaded + '</a>'

                        $(td).addClass('text-center')
                        $(td).addClass('align-middle')
                    }


                },
                {
                    'targets': 7,
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        var temp_data = all_sched_detail.filter(x => x.headerID == rowData.id)
                        var temp_sched = []
                        if (temp_data.length > 0) {
                            $.each(temp_data, function(a, b) {
                                var check = temp_sched.filter(x => x.stime == b.stime && x
                                    .etime == b.etime && x.schedotherclass == b
                                    .schedotherclass && x.roomid == b.roomid)
                                if (check.length == 0) {
                                    temp_sched.push({
                                        'schedotherclass': b.schedotherclass,
                                        'roomname': b.roomname,
                                        'etime': b.etime,
                                        'stime': b.stime,
                                        'days': [],
                                        'roomid': b.roomid
                                    });
                                    var get_index = temp_sched.findIndex(x => x.stime == b
                                        .stime && x.etime == b.etime && x.schedotherclass ==
                                        b.schedotherclass && x.roomid == b.roomid)
                                    if (get_index != -1) {
                                        temp_sched[get_index].days.push(b.day)
                                    }
                                } else {
                                    var get_index = temp_sched.findIndex(x => x.stime == b
                                        .stime && x.etime == b.etime && x.schedotherclass ==
                                        b.schedotherclass && x.roomid == b.roomid)
                                    if (get_index != -1) {
                                        temp_sched[get_index].days.push(b.day)
                                    }
                                }
                            })
                            var text = ''
                            $.each(temp_sched, function(a, b) {
                                var temp_stime = moment(b.stime, 'HH:mm a').format('hh:mm a')

                                if (b.schedotherclass != null) {
                                    text += b.schedotherclass.substring(0, 3) + '.: '
                                }

                                text += moment(b.stime, 'HH:mm a').format('hh:mm A') + ' - ' +
                                    moment(b.etime, 'HH:mm a').format('hh:mm A') + ' / '

                                var sorted_days = b.days.sort()

                                $.each(sorted_days, function(c, d) {
                                    text += d == 1 ? 'M' : ''
                                    text += d == 2 ? 'T' : ''
                                    text += d == 3 ? 'W' : ''
                                    text += d == 4 ? 'Th' : ''
                                    text += d == 5 ? 'F' : ''
                                    text += d == 6 ? 'Sat' : ''
                                    text += d == 7 ? 'Sun' : ''
                                })

                                if (b.roomname != null) {
                                    text += ' / ' + b.roomname
                                }

                                if (temp_sched.length != a + 1) {
                                    text += ' <br> '
                                }
                            })


                            $(td)[0].innerHTML = '<span style="font-size:.75rem !important">' + text +
                                '</span>'
                            // $(td)[0].innerHTML = text + '<br><a style="font-size:.75rem !important" href="#sched_plot_holder" class="add_sched " data-id="'+rowData.id+'" data-subjdesc="Push-up">Add Sched</a>'
                        } else {
                            $(td)[0].innerHTML = null
                            // $(td)[0].innerHTML = '<a style="font-size:.75rem !important" href="#sched_plot_holder" class="add_sched " data-id="'+rowData.id+'" data-subjdesc="Push-up">Add Sched</a>'
                        }
                        $(td).addClass('align-middle')
                    },

                },
                {
                    'targets': 9,
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {

                        if (currentPortal == 3) {
                            var text =

                                '<a style="font-size:.65rem !important" href="javascript:void(0)" class="btn-to-modal" data-id="' +
                                rowData.id +
                                '" data-subjdesc="Push-up" data-toggle="tooltip" data-placement="top" title="Assign Instructor" data-modal="modal-csl-update-instructor"><i class="nav-icon fas fa-user-plus"></i></a>' +

                                '<a style="font-size:.65rem !important" href="javascript:void(0)" class="btn-to-modal ml-2" data-id="' +
                                rowData.id +
                                '" data-subjdesc="Push-up" data-toggle="tooltip" data-placement="top" title="Update Schedule" data-modal="csl-schedule-form"><i class="nav-icon fa fa-calendar"></i></a>' +


                                '<a style="font-size:.65rem !important" href="javascript:void(0)" class="btn-to-modal ml-2" data-id="' +
                                rowData.id +
                                '" data-toggle="tooltip" data-placement="top" title="Update Schedule Information" data-modal="csl-schedule-information-form"><i class="nav-icon fas fa-edit" ></i></a>' +

                                '<a style="font-size:.65rem !important" href="javascript:void(0)" class="ml-2 btn-csl-remove-schedule" data-id="' +
                                rowData.id +
                                '" data-toggle="tooltip" data-placement="top" title="Remove Schedule" ><i class="nav-icon fas fa-trash-alt text-danger" ></i></a>'

                            text += '<br>'
                        } else {
                            text = ''
                        }

                        if (p_url == 'studentloading') {

                            if (rowData.selected == 0) {
                                text +=
                                    '<button class="btn btn-sm btn-primary btn-block add_sched" style="font-size:.6rem !important; padding:.10rem .25rem !important" data-id="' +
                                    rowData.dataid + '" >Add Sched</button>'
                            } else {

                                if (p_entype == 'REGULAR') {
                                    text +=
                                        '<button class="btn btn-sm btn-danger btn-block remove_schedule" style="font-size:.6rem !important; padding:.10rem .25rem !important" data-id="' +
                                        rowData.dataid + '">Remove Schedule</button>'
                                } else {
                                    text +=
                                        '<button class="btn btn-sm btn-danger btn-block remove_schedule" style="font-size:.6rem !important; padding:.10rem .25rem !important" data-id="' +
                                        rowData.dataid + '">Drop Schedule</button>'
                                }

                            }
                        }

                        $(td)[0].innerHTML = text
                        $(td).addClass('text-center')
                        $(td).addClass('align-middle')
                    }
                }
            ],
            "initComplete": function(settings, json) {
                $(function() {
                    $('[data-toggle="tooltip"]').tooltip()
                })
            }

        });

        $('#addAllHolder').empty()

        if (p_url == 'studentloading' && $('#filter_sched_subjgroup').val() != null) {

            $('#addAllHolder').append(
                '<button class="btn btn-primary btn-sm btn-block" id="addAllSched" style="font-size:.7rem !important; padding:.10rem .25rem !important">Add All</button>'
            )
            $('#addAllHolder').addClass('text-center')
            $('#addAllHolder').addClass('align-middle')
            $('#addAllHolder').addClass('p-0')
            $('#addAllHolder').addClass('p-1')

        }

        var label_text = $($("#available_sched_datatable_wrapper")[0].children[0])[0].children[0]
        if (currentPortal == 3) {
            $(label_text)[0].innerHTML =
                '<button class="btn btn-primary btn-sm subjsched_form" style="font-size:.7rem !important">Create Subject Sched</button><button class="btn btn-primary btn-sm ml-2" id="schedgroup_to_modal" style="font-size:.7rem !important">Schedule Group</button>'
        } else {
            $(label_text)[0].innerHTML = null
        }


    }

    function empty_form() {
        $('#input_subjsched_term').val("Lecture").change()
        $('#input-csl-room').empty()
        $('#input-csl-schedgroup').empty()
        update_input_time()
        $('.csl-day').prop('checked', false)
        $('#btn-csl-update-sched').attr('hidden', 'hidden')
        $('#btn-csl-delete-sched').attr('hidden', 'hidden')
        $('#btn-csl-create-sched').removeAttr('hidden')

        $('#input-csl-classification').removeAttr('disabled')
        $('#input-csl-room').removeAttr('disabled')
        $('#input-csl-time').removeAttr('readonly')
    }

    function csl_sched_form_detail() {

        var temp_data = all_sched_detail.filter(x => x.headerID == seleted_id)

        var temp_sched = []


        $.each(temp_data, function(a, b) {
            var check = temp_sched.filter(x => x.stime == b.stime && x.etime == b.etime && x.schedotherclass ==
                b.schedotherclass && x.roomid == b.roomid)
            if (check.length == 0) {
                temp_sched.push({
                    'schedotherclass': b.schedotherclass,
                    'roomname': b.roomname,
                    'etime': b.etime,
                    'stime': b.stime,
                    'days': [],
                    'roomid': b.roomid
                });
                var get_index = temp_sched.findIndex(x => x.stime == b.stime && x.etime == b.etime && x
                    .schedotherclass == b.schedotherclass && x.roomid == b.roomid)
                if (get_index != -1) {
                    temp_sched[get_index].days.push(b.day)
                }
            } else {
                var get_index = temp_sched.findIndex(x => x.stime == b.stime && x.etime == b.etime && x
                    .schedotherclass == b.schedotherclass && x.roomid == b.roomid)
                if (get_index != -1) {
                    temp_sched[get_index].days.push(b.day)
                }
            }
        })

        var text = ''
        $.each(temp_sched, function(a, b) {
            text += '<tr><td>' + b.schedotherclass
            var temp_stime = moment(b.stime, 'HH:mm a').format('hh:mm a')
            var time_text = moment(b.stime, 'HH:mm a').format('hh:mm A') + ' - ' + moment(b.etime, 'HH:mm a')
                .format('hh:mm A')
            var days_text = ''
            var room_text = ''

            var sorted_days = b.days.sort()

            $.each(sorted_days, function(c, d) {
                days_text += d == 1 ? 'M' : ''
                days_text += d == 2 ? 'T' : ''
                days_text += d == 3 ? 'W' : ''
                days_text += d == 4 ? 'Th' : ''
                days_text += d == 5 ? 'F' : ''
                days_text += d == 6 ? 'Sat' : ''
                days_text += d == 7 ? 'Sun' : ''
            })
            if (b.roomname != null) {
                room_text = b.roomname
            }
            text += '</a></td><td>' + time_text + '</td><td class="text-center">' + days_text + '</td><td >' +
                room_text +
                '</td><td class="text-center"><a href="javascript:void(0)" class="update_subjsched" data-start="' +
                b.stime + '" data-roomid="' + b.roomid + '" data-end="' + b.etime + '" data-classification="' +
                b.schedotherclass +
                '"><i class="far fa-edit text-primary" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Schedule Detail"></i></a></td><td class="text-center"><a href="javascript:void(0)" class="delete_subjsched" data-start="' +
                b.stime + '" data-roomid="' + b.roomid + '" data-end="' + b.etime + '" data-classification="' +
                b.schedotherclass +
                '"><i class="fas fa-trash-alt text-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Schedule Detail"></i></a></td></tr> '
        })

        text += '<tr><td colspan="5"><a href="javascript:void(0)" id="csl-new-schedule">New Schedule</a></td></tr>'

        $('#csl-schedule-holder')[0].innerHTML = text
    }

    $(document).on('click', '[data-toggle="tooltip"]', function() {
        $(this).tooltip('hide')
    })

    $(document).on('click', '.cnflctBttn', function() {
        $('#conflict_modal').modal()
    })



    $(document).on('click', '.btn-to-modal', function() {

        $('.cnflctButtonHolder').attr('hidden', 'hidden')
        empty_form()

        var temp_modal = $(this).attr('data-modal')
        var tempid = $(this).attr('data-id')
        var temp_info = all_sched.filter(x => x.id == tempid)
        seleted_id = tempid

        $('.label-csl-subject').text(temp_info[0].subjCode + ' - ' + temp_info[0].subjDesc)
        $('#input-csl-capacity').val(temp_info[0].capacity)
        $('#input-csl-teacher').empty()
        $('#input-csl-teacher').append('<option value="' + temp_info[0].teacherID + '">' + temp_info[0]
            .lastname + ', ' + temp_info[0].firstname + '</option>')
        $('#input-csl-teacher').val(temp_info[0].teacherID).change()
        $('#input-csl-subject').text(temp_info[0].subjCode + '-' + temp_info[0].subjDesc)
        csl_sched_form_detail()


        var schedgroup_detail = all_sched_groupdetail.filter(x => x.schedid == seleted_id)
        var temp_schedgroupid = []

        $('#csl_input_schedgroup').empty()
        $('#label-lecunits').text(0)
        $('#label-labunits').text(0)


        $.each(schedgroup_detail, function(a, b) {

            $('#csl_input_schedgroup').append('<option value="' + b.id + '">' + b.text + '</option>')
            temp_schedgroupid.push(b.id)
        })




        $('#csl_input_schedgroup').val(temp_schedgroupid).change()

        $('#csl_input_capacity').val(temp_info[0].capacity).change()

        $('#csl_input_subject').empty()
        $('#csl_input_subject').append('<option value="' + temp_info[0].prospectusSubj + '">' + temp_info[0]
            .subjCode + ' - ' + temp_info[0].subjDesc + '</option>')

        $('#label-lecunits').text(temp_info[0].lecunits)
        $('#label-labunits').text(temp_info[0].labunits)

        $('#csl_input_subject').val(temp_info[0].prospectusSubj).change()

        $('#csl_input_gradelevel').val(temp_info[0].yearID).change()
        $('#csl_input_classtype').val(temp_info[0].section_specification).change()


        $('#' + temp_modal).modal()
    })

    $(document).on('click', '#csl-new-schedule , #btn-csl-cancel-form', function() {
        $('#btn-csl-update-sched').attr('hidden', 'hidden')
        $('#btn-csl-create-sched').removeAttr('hidden')
        $('#btn-csl-delete-sched').attr('hidden', 'hidden')
        $('#cnflctButtonHolder').attr('hidden', 'hidden')
        $('#input_subjsched_term').val("Lecture").change()
        $('#btn-csl-cancel-form').attr('hidden', 'hidden')
        $('#day-selection-message').empty()



        empty_form()
    })

    $(document).on('click', '#btn-csl-update-sched', function() {
        update_schedule()
    })

    $(document).on('click', '#btn-csl-create-sched', function() {
        create_schedule()
    })


    $(document).on('click', '#btn-csl-delete-sched', function() {


        var sched_tobe_deleted = []
        $('.csl-day').each(function() {
            if ($(this).is(":checked")) {
                var tempid = selected_detail.filter(x => x.day == $(this).val())
                sched_tobe_deleted.push(tempid[0].id)
            }
        })

        if (sched_tobe_deleted.length == 0) {
            Toast.fire({
                type: 'warning',
                title: 'No days selected.'
            })
            return false
        }

        Swal.fire({
                title: 'Are sure you want to remove schedule detail?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove detail!'
            })
            .then((result) => {
                if (result.value) {
                    remove_scheduledetail(seleted_id)
                }

            })
    })

    $(document).on('click', '.update_subjsched', function() {
        empty_form()

        $('#day-selection-message')[0].innerHTML =
            'Select only the days you want to update. The unselected day will not be removed. If you want to remove a schedule click the delete button <i class="fas fa-trash-alt text-danger"></i> and select the days that you want to remove.'
        var stime = $(this).attr('data-start')
        var etime = $(this).attr('data-end')

        console.log(stime, etime);
        var classification = $(this).attr('data-classification')
        var roomid = $(this).attr('data-roomid')
        var temp_time = moment(stime, 'HH:mm a').format('hh:mm A') + ' - ' + moment(etime, 'HH:mm a').format(
            'hh:mm A')
        var temp_info = all_sched.filter(x => x.id == seleted_id)

        if (classification == null || classification == "null") {
            var temp_data = all_sched_detail.filter(x => x.etime == etime && x.stime == stime && x.schedotherclass == null && x.headerID == seleted_id)
        } else {
            var temp_data = all_sched_detail.filter(x => x.etime == etime && x.stime == stime && x.schedotherclass == classification && x.headerID == seleted_id)
        }
      
        if (classification != null && classification != "null") {
            temp_data = temp_data.filter(x => x.schedotherclass == classification)
        }

        if (roomid != null && roomid != "null") {
            temp_data = temp_data.filter(x => x.roomid == roomid)
        }

        selected_detail = temp_data

        $.each(temp_data, function(a, b) {
            $('.csl-day[value="' + b.day + '"]').prop('checked', true)
        })

        allowconflict = 0
        $('#btn-csl-update-sched').text('Update Schedule')

        $('#input-csl-classification').val(classification).change()
        $('#input-csl-time').val(temp_time)
        $('#btn-csl-delete-sched').attr('hidden', 'hidden')
        $('#btn-csl-update-sched').removeAttr('hidden')
        $('#btn-csl-cancel-form').removeAttr('hidden')
        $('#btn-csl-create-sched').attr('hidden', 'hidden')

        $('#input-csl-classification').removeAttr('disabled')
        $('#input-csl-room').removeAttr('disabled')
        $('#input-csl-time').removeAttr('readonly')

        if (temp_data[0].roomid != null) {
            $('#input-csl-room').empty()
            $('#input-csl-room').append('<option value="' + temp_data[0].roomid + '">' + temp_data[0].roomname +
                '</option>')
            $('#input-csl-room').val(temp_data[0].roomid).change()
        }

        $('#input-csl-schedgroup').empty()
        $('#input-csl-schedgroup').append('<option value="' + temp_info[0].schedgroup + '">' + temp_info[0]
            .schedgroupdesc + '</option>')
        $('#input-csl-schedgroup').val(temp_info[0].schedgroup).change()

    })

    $(document).on('click', '.delete_subjsched', function() {
        empty_form()
        $('#day-selection-message')[0].innerHTML =
            'Select only the days you want to update. The unselected day will not be removed. If you want to remove a schedule click the delete button <i class="fas fa-trash-alt text-danger"></i> and select the days that you want to remove.'
        var stime = $(this).attr('data-start')
        var etime = $(this).attr('data-end')
        var classification = $(this).attr('data-classification')
        var roomid = $(this).attr('data-roomid')
        var temp_time = moment(stime, 'HH:mm a').format('hh:mm A') + ' - ' + moment(etime, 'HH:mm a').format(
            'hh:mm A')
        var temp_info = all_sched.filter(x => x.id == seleted_id)

        var temp_data = all_sched_detail.filter(x => x.etime == etime && x.stime == stime && x
            .schedotherclass == classification && x.headerID == seleted_id)

        if (classification != null && classification != "null") {
            temp_data = temp_data.filter(x => x.schedotherclass == classification)
        }

        if (roomid != null && roomid != "null") {
            temp_data = temp_data.filter(x => x.roomid == roomid)
        }

        selected_detail = temp_data

        $.each(temp_data, function(a, b) {
            $('.csl-day[value="' + b.day + '"]').prop('checked', true)
        })

        allowconflict = 0
        $('#btn-csl-update-sched').text('Update Schedule')

        $('#input-csl-classification').val(classification).change()
        $('#input-csl-time').val(temp_time)
        $('#btn-csl-update-sched').attr('hidden', 'hidden')
        $('#btn-csl-cancel-form').removeAttr('hidden')
        $('#btn-csl-delete-sched').removeAttr('hidden')
        $('#btn-csl-create-sched').attr('hidden', 'hidden')



        if (temp_data[0].roomid != null) {
            $('#input-csl-room').empty()
            $('#input-csl-room').append('<option value="' + temp_data[0].roomid + '">' + temp_data[0].roomname +
                '</option>')
            $('#input-csl-room').val(temp_data[0].roomid).change()
        }

        $('#input-csl-schedgroup').empty()
        $('#input-csl-schedgroup').append('<option value="' + temp_info[0].schedgroup + '">' + temp_info[0]
            .schedgroupdesc + '</option>')
        $('#input-csl-schedgroup').val(temp_info[0].schedgroup).change()

        $('#input-csl-classification').attr('disabled', 'disabled')
        $('#input-csl-room').attr('disabled', 'disabled')
        $('#input-csl-time').attr('readonly', 'readonly')

    })

    $('#modal-csl-update-instructor').on('hidden.bs.modal', function() {
        $('#btn-csl-update-instructor').text('Update')
        $('.cnflctButtonHolder').attr('hidden', 'hidden')
        allowconflict = 0
    })

    $(document).on('change', '#input-csl-teacher  ', function() {
        allowconflict = 0
        $('#btn-csl-update-instructor').text('Update')
        $('.cnflctButtonHolder').attr('hidden', 'hidden')
    })

    $(document).on('change', '.csl-day, #input-csl-room, #input-csl-time, #input-csl-classification', function() {
        allowconflict = 0
        $('#btn-csl-update-sched').text('Update Schedule')
        $('.cnflctButtonHolder').attr('hidden', 'hidden')
    })



    $('#input-csl-teacher').select2({
        placeholder: "Select Teacher",
        allowClear: true,
        ajax: {
            url: '/college/schedule/list/teachers',
            data: function(params) {
                var query = {
                    search: params.term,
                    page: params.page || 0
                }
                return query;
            },
            dataType: 'json',
            processResults: function(data, params) {
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

    var printable_id = null

    $(document).ready(function() {
        $(document).on('click', '.sched_list_students', function() {
            $('#list_label').text('Subject : ' + $(this).attr('data-text'))
            $('#student_list_type').text('(Enrolled)')
            var temp_schedid = $(this).attr('data-id')
            printable_id = temp_schedid
            sched_enrolled_learners(temp_schedid)
        })

        $(document).on('click', '.sched_list_loaded_students', function() {
            $('#list_label').text('Subject : ' + $(this).attr('data-text'))
            $('#student_list_type').text('(Loaded)')
            var temp_schedid = $(this).attr('data-id')
            printable_id = temp_schedid
            sched_loaded_learners(temp_schedid)
        })

    })

    function sched_enrolled_learners(schedid) {

        $.ajax({
            type: 'GET',
            url: '/college/section/schedule/schedenrolledlearners',
            data: {
                schedid: schedid,
                syid: $('#filter_sy').val(),
                semid: $('#filter_semester').val()
            },
            success: function(data) {
                $('#enrolled_modal').modal()
                sched_list_table(data)
            }
        })
    }

    function sched_loaded_learners(schedid) {
        $.ajax({
            type: 'GET',
            url: '/college/section/schedule/schedloadedlearners',
            data: {
                schedid: schedid,
                syid: $('#filter_sy').val(),
                semid: $('#filter_semester').val()
            },
            success: function(data) {
                $('#enrolled_modal').modal()
                sched_list_table(data)

            }
        })
    }

    function sched_list_table(data) {
        $("#student_list").DataTable({
            destroy: true,
            lengthChange: false,
            data: data,
            columns: [{
                    "data": "student"
                },
                {
                    "data": "levelname"
                },
                {
                    "data": "courseabrv"
                },
            ],
            columnDefs: [{
                'targets': 0,
                'orderable': true,
                'createdCell': function(td, cellData, rowData, row, col) {
                    var enrolledstattus = ''
                    if (rowData.isenrolled == 1) {
                        enrolledstattus =
                            '<span class="badge badge-success float-right">Enrolled</span>'
                    }
                    $(td)[0].innerHTML = rowData.student + enrolledstattus
                    $(td).addClass('align-middle')
                }
            }, ]
        })

        // console.log(selected_id)

        var label_text = $($('#student_list_wrapper')[0].children[0])[0].children[0]
        $(label_text)[0].innerHTML =
            '<button class="btn btn-sm btn-default mr-2" onclick="print_student_excell()"><i class="fas fa-file-excel text-success"></i> Student List</button><button class="btn btn-sm btn-default mr-2" onclick="print_student()"><i class="fas fa-print"></i> Student List</button><button class="btn btn-sm btn-default" id="print_grade" onclick="todeanmodal()"><i class="fas fa-print"></i> Grading Sheet</button>'
    }


    $(document).on('click', '#print_grades', function() {
        print_grades()
    })

    function todeanmodal() {
        $('#dean_holder_modal').modal()
    }

    function print_grades() {

        var temp_info = all_sched.filter(x => x.id == printable_id)
        var pid = []
        var sectionid = []
        $.each(temp_info, function(a, b) {
            pid.push(b.subjectID)
        })
        $.each(temp_info, function(a, b) {
            sectionid.push(b.sectionID)
        })
        var temp_subjid = temp_info[0].subjectID
        var syid = $('#filter_sy').val()
        var semid = $('#filter_semester').val()
        var pid = pid
        var dean = $('#printable_dean').val()
        var schedid = temp_info[0].dataid
        window.open('/college/teacher/student/grades/print?&syid=' + syid + '&semid=' + semid + '&pid=' + pid +
            '&sectionid=' + sectionid + '&schedid=' + schedid + '&subjid=' + temp_subjid + '&dean=' + dean, '_blank'
        );
    }

    function print_student() {

        var temp_info = all_sched.filter(x => x.id == printable_id)
        var schedid = temp_info[0].dataid
        var syid = $('#filter_sy').val()
        var semid = $('#filter_semester').val()
        window.open('/college/teacher/student/information/pdf?syid=' + syid + '&semid=' + semid + '&schedid=' + schedid,
            '_blank');
    }

    function print_student_excell() {

    var temp_info = all_sched.filter(x => x.id == printable_id)
    var schedid = temp_info[0].dataid
    var syid = $('#filter_sy').val()
    var semid = $('#filter_semester').val()
    window.open('/college/teacher/student/information/excell?syid=' + syid + '&semid=' + semid + '&schedid=' + schedid,
        '_blank');
    }
</script>
