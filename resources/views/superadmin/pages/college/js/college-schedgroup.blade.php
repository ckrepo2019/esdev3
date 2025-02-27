<script>
    var all_schedgroup = []
    var seleted_id = null

    function schedgroup() {

        var schedgroup_list = []

        $.ajax({
            type: 'GET',
            url: routes.cllgschdgrpList,
            async: false,
            success: function(data) {
                schedgroup_list = data
            }
        })

        return schedgroup_list

    }


    function schedgroup_select(select_id) {

        $.ajax({
            type: 'GET',
            url: routes.cllgschdgrpSelect,
            async: false,
            success: function(data) {

                $(select_id).addClass('is_schedgroup_select')
                $(select_id).empty()
                $(select_id).append('<option value="">Select Section</option>')
                // $(select_id).append('<option value="add">Add curriculum</option>')
                $(select_id).select2({
                    data: data,
                    allowClear: true,
                    placeholder: "Select Schedule Group",
                })
            }
        })

    }



    function schedgroup_datatable() {


        $('#schedgroup_modal').modal()

        $("#schedgroup_datatable").DataTable({
            destroy: true,
            bInfo: false,
            autoWidth: false,
            lengthChange: false,
            stateSave: true,
            serverSide: true,
            processing: true,
            ajax: {
                url: routes.cllgschdgrpDatatable,
                type: 'GET',
                dataSrc: function(json) {
                    all_schedgroup = json.data
                    return json.data;
                }
            },
            columns: [{
                    "data": "text"
                },
                {
                    "data": "schedgroupdesc"
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
            order: [
                [0, "asc"]
            ],
            columnDefs: [{
                    'targets': 0,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 1,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 2,
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        $(td)[0].innerHTML = rowData.levelname.replace(' COLLEGE', '')
                        $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 3,
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        var text = ''
                        if (rowData.collegeid != null) {
                            text = rowData.collegeDesc
                        } else {
                            text = rowData.courseDesc
                        }

                        $(td)[0].innerHTML = text
                        $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 4,
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        var buttons = '<a href="javascript:void(0)" class="schedgroup_edit" data-id="' +
                            rowData.id + '"><i class="far fa-edit"></i></a>';
                        $(td)[0].innerHTML = buttons
                        $(td).addClass('text-center')
                        $(td).addClass('align-middle')
                    }
                },
                {
                    'targets': 5,
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        var buttons =
                            '<a href="javascript:void(0)" class="schedgroup_delete" data-id="' + rowData
                            .id + '"><i class="far fa-trash-alt text-danger"></i></a>';
                        $(td)[0].innerHTML = buttons
                        $(td).addClass('text-center')
                        $(td).addClass('align-middle')
                    }
                },

            ]
        });

        var label_text = $($("#schedgroup_datatable_wrapper")[0].children[0])[0].children[0]
        $(label_text)[0].innerHTML =
            '<button class="btn btn-sm btn-primary" id="schedgroup_to_create_modal"  style="font-size:.7rem !important">Create Section</button>'

    }

    $(document).on('change', '#schedgroup_input_college', function() {
        var collegeId = $(this).val()
        console.log(collegeId);

        $.ajax({
            type: 'GET',
            url: '/setup/course/select',
            data: {
                syid: schedgroupSY,
                semid: schedgroupSem,
                collegeid: collegeId,

            },
            success: function(data) {
                data = JSON.parse(data);
                console.log('fetching...', data.results);
                $('#schedgroup_input_course').empty()
                $('#schedgroup_input_course').append('<option value="">All</option>')
                $('#schedgroup_input_course').select2({
                    data: data['results'].filter(function(item) {
                        return item.collegeid == collegeId
                    }),
                    allowClear: true,
                    placeholder: "Select Course",
                })
            }
        })

        // ajax: {
        //         url: '/setup/course/select',
        //         data: function(params) {
        //             var query = {
        //                 syid: schedgroupSY,
        //                 semid: schedgroupSem,
        //                 collegeid: collegeId,
        //                 search: params.term,
        //                 page: params.page || 0
        //             }
        //             return query;
        //         },
        //         dataType: 'json',
        //         processResults: function(data, params) {
        //             console.log('data', data);
        //             params.page = params.page || 0;
        //             var filtered = data.results.filter(function(item) {
        //                 return item.collegeid == collegeId
        //             })
        //             return {
        //                 syid: schedgroupSY,
        //                 semid: schedgroupSem,
        //                 results: filtered,
        //                 pagination: {
        //                     more: data.pagination.more
        //                 }
        //             };
        //         }
        //     }


        // $('#schedgroup_input_course').empty()
        // $('#schedgroup_input_course').select2({
        //     placeholder: "All",
        //     allowClear: true,
        //     // delay: 250 ,         
        // });
        //   $('#schedgroup_input_course').empty();
    })


    // $(document).on('change','#schedgroup_input_course',function(){
    //       $('#schedgroup_input_college').empty();
    // })

    function schedgroup_create() {

        if ($('#schedgroup_input_description').val() == "") {
            Toast.fire({
                type: 'info',
                title: 'Decription is required!',
            })
            return false
        }

        if ($('#schedgroup_input_gradelevel').val() == "") {

            Toast.fire({
                type: 'info',
                title: 'Grade Level is required!',
            })

            return false
        }

        if (($('#schedgroup_input_college').val() == "" || $('#schedgroup_input_college').val() == null) && ($(
                '#schedgroup_input_course').val() == "" || $('#schedgroup_input_course').val() == null)) {

            Toast.fire({
                type: 'info',
                title: 'Course/College is required!',
            })

            return false
        }


        $.ajax({
            type: 'GET',
            url: routes.cllgschdgrpCreate,
            data: {
                schedgroupdesc: $('#schedgroup_input_description').val(),
                sglevelid: $('#schedgroup_input_gradelevel').val(),
                sgcollege: $('#schedgroup_input_college').val(),
                sgcourse: $('#schedgroup_input_course').val(),
            },
            success: function(data) {
                if (data[0].status == 1) {
                    schedgroup_select('.is_schedgroup_select')
                    schedgroup_datatable()
                }
                Toast.fire({
                    type: data[0].icon,
                    title: data[0].message
                })
            },
        })
    }



    function schedgroup_update() {

        if ($('#schedgroup_input_description').val() == "") {
            Toast.fire({
                type: 'info',
                title: 'Decription is empty!',
            })
            return false
        }


        $.ajax({
            type: 'GET',
            url: routes.cllgschdgrpUpdate,
            data: {
                id: seleted_id,
                schedgroupdesc: $('#schedgroup_input_description').val(),
                sglevelid: $('#schedgroup_input_gradelevel').val(),
                sgcollege: $('#schedgroup_input_college').val(),
                sgcourse: $('#schedgroup_input_course').val(),
            },
            success: function(data) {
                if (data[0].status == 1) {
                    schedgroup_select('.is_schedgroup_select')
                    schedgroup_datatable()
                    display_sched_collegesections()
                }
                Toast.fire({
                    type: data[0].icon,
                    title: data[0].message
                })
            },
        })

    }



    function schedgroup_delete() {

        $.ajax({
            type: 'GET',
            url: routes.cllgschdgrpDelete,
            data: {
                id: seleted_id
            },
            success: function(data) {
                if (data[0].status == 1) {
                    schedgroup_select('.is_subjgroup_select')
                    schedgroup_datatable()
                }
                Toast.fire({
                    type: data[0].icon,
                    title: data[0].message
                })
            },
        })

    }

    var modal_html =
        '<div class="modal fade" id="schedgroup_modal" style="display: none; " aria-hidden="true" data-backdrop="static" data-keyboard="false">' +
        '<div class="modal-dialog modal-lg">' +
        '<div class="modal-content" >' +
        '<div class="modal-header pb-2 pt-2 border-0">' +
        '<h4 class="modal-title" style="font-size: 1.1rem !important">Section</h4>' +
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
        '<span aria-hidden="true">×</span></button>' +
        '</div>' +
        '<div class="modal-body pt-0">' +
        '<div class="row mt-2" style="font-size:.7rem !important">' +
        '<div class="col-md-12">' +
        '<table class="table-hover table table-striped table-sm table-bordered" id="schedgroup_datatable" width="100%" >' +
        '<thead>' +
        '<tr>' +
        '<th width="25%" class="align-middle">Display</th>' +
        '<th width="20%" class="align-middle">Section Description</th>' +
        '<th width="12%" class="align-middle">Grade Level</th>' +
        '<th width="36%" class="align-middle">Course / College</th>' +
        '<th width="4%" class="align-middle text-center p-0"></th>' +
        '<th width="4%" class="align-middle text-center p-0"></th>' +
        '</tr>' +
        '</thead>' +
        '</table>' +
        '<div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>'

    $('#schedgroup_modal').remove();
    $('body').append(modal_html)


    var modal_html = `<div class="modal fade" id="schedgroup_form_modal" style="display: none; " aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
            <div class="modal-content" >
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Section Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0" style="font-size: .7rem !important">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Grade Level</label>
                                    <select name="schedgroup_input_gradelevel" id="schedgroup_input_gradelevel" class="form-control select2"></select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">College</label>
                                    <select name="schedgroup_input_college" id="schedgroup_input_college" class="form-control select2"></select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Course</label>
                                    <select name="schedgroup_input_course" id="schedgroup_input_course" class="form-control select2"></select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Section Description</label>
                                    <input class="form-control form-control-sm" id="schedgroup_input_description">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                              <button class="btn btn-sm btn-primary" id="schedgroup_create_button"><i class="fa fa-save"></i> Save</button>
                              <button class="btn btn-success btn-success btn-sm" id="schedgroup_update_button" hidden><i class="fa fa-save"></i> Update</button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
      </div>`

    $('#schedgroup_form_modal').remove();
    $('body').append(modal_html)

    $(document).on('click', '#schedgroup_to_create_modal', function() {

        $('#schedgroup_input_description').val("")
        $('#schedgroup_input_college').empty()
        $('#schedgroup_input_course').empty()
        $('#schedgroup_input_gradelevel').val("").change()

        $('#schedgroup_create_button').removeAttr('hidden')
        $('#schedgroup_update_button').attr('hidden', 'hidden')
        $('#schedgroup_form_modal').modal()

        //   schdgrpLoadResources()
    })


    $(document).on('click', '.schedgroup_edit', function() {
        seleted_id = $(this).attr('data-id')

        var temp_info = all_schedgroup.filter(x => x.id == seleted_id)
        $('#schedgroup_input_description').val(temp_info[0].schedgroupdesc)
        $('#schedgroup_input_gradelevel').val(temp_info[0].levelid).change()


        if (temp_info[0].courseid != null) {
            $('#schedgroup_input_course').empty()
            $('#schedgroup_input_course').append('<option value="' + temp_info[0].courseid + '">' + temp_info[0]
                .courseDesc + '</option>')
            $('#schedgroup_input_course').val(temp_info[0].courseid).change()
        } else {
            $('#schedgroup_input_course').empty()
        }

        if (temp_info[0].collegeid != null) {
            $('#schedgroup_input_college').empty()
            $('#schedgroup_input_college').append('<option value="' + temp_info[0].collegeid + '">' + temp_info[
                0].collegeDesc + '</option>')
            $('#schedgroup_input_college').val(temp_info[0].collegeid).change()
        } else {
            $('#schedgroup_input_college').empty()
        }


        //   schdgrpLoadResources()

        $('#schedgroup_create_button').attr('hidden', 'hidden')
        $('#schedgroup_update_button').removeAttr('hidden')

        $('#schedgroup_form_modal').modal()
    })

    $(document).on('click', '#schedgroup_create_button', function() {
        schedgroup_create()
    })

    $(document).on('click', '#schedgroup_update_button', function() {
        schedgroup_update()
    })

    var schedgroup_gradelevel = [{
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

    $("#schedgroup_input_gradelevel").empty()
    $("#schedgroup_input_gradelevel").append('<option value="">Select gradelevel</option>')
    $("#schedgroup_input_gradelevel").val("")
    $('#schedgroup_input_gradelevel').select2({
        data: schedgroup_gradelevel,
        placeholder: "Select gradelevel",
    })

    var schedgroupSY = null
    var schedgroupSem = null

    function schdgrpLoadResources(syid = null, semid = null) {

        if (schedgroupSY == null) {
            schedgroupSY = syid
        }
        if (schedgroupSem == null) {
            schedgroupSem = semid
        }

        $('#schedgroup_input_course').select2({
            placeholder: "All",
            allowClear: true,
            // delay: 250 ,
            ajax: {
                url: '/setup/course/select',
                data: function(params) {
                    var query = {
                        syid: schedgroupSY,
                        semid: schedgroupSem,
                        search: params.term,
                        page: params.page || 0
                    }
                    return query;
                },
                dataType: 'json',
                processResults: function(data, params) {
                    console.log('data', data);
                    params.page = params.page || 0;
                    return {
                        syid: schedgroupSY,
                        semid: schedgroupSem,
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                }
            }
        });

        // $('#schedgroup_input_course').on('select2:select', function(e) {
        //     console.log(e.params.data.id);
        // })

        // $('#schedgroup_input_college').on('select2:select', function(e) {
        //     console.log('clicking...', e.params.data.id);
        // })

        $('#schedgroup_input_college').select2({
            placeholder: "All",
            allowClear: true,
            // delay: 250 ,
            ajax: {
                url: '/setup/college/list/select2',
                data: function(params) {
                    var query = {
                        syid: schedgroupSY,
                        semid: schedgroupSem,
                        withfilter: false,
                        search: params.term,
                        page: params.page || 0
                    }
                    return query;
                },
                dataType: 'json',
                processResults: function(data, params) {
                    console.log('data', data);
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

    }

    $(document).on('click', '.schedgroup_delete', function() {
        seleted_id = $(this).attr('data-id')
        Swal.fire({
            text: 'Are you sure you want to remove Section?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Remove'
        }).then((result) => {
            if (result.value) {
                schedgroup_delete()
            }
        })
    })
</script>
