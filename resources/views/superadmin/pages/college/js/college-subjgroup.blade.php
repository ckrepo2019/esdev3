<script>

      var subjectgroup_datatable = []
      var subjectgroup_select = []
      var all_subjgroup = []
      var seleted_id = null

      function subjgroup(){

      var subjgroup_list = []

      $.ajax({
            type:'GET',
            url: '/setup/prospectus/subjgroup',
            async: false,
            success:function(data) {
            subjgroup_list = data
            }
      })

      return subjgroup_list

      }


      function subjgroup_select(select_id){

      $.ajax({
            type:'GET',
            url: '/setup/prospectus/subjgroup',
            async: false,
            success:function(data) {

            $(select_id).addClass('is_subjgroup_select')
            $(select_id).empty()
            $(select_id).append('<option value="">Select Subject Group</option>')
            // $(select_id).append('<option value="add">Add curriculum</option>')
            $(select_id).select2({
                  data: data,
                  allowClear: true,
                  placeholder: "Select Subject Group",
            })
            }
      })

      }



      function subjgroup_datatable(){

      
      $('#subjgroup_modal').modal()

      $("#subjgroup_datatable").DataTable({
            destroy: true,
            bInfo: false,
            autoWidth: false,
            lengthChange: false,
            stateSave: true,
            serverSide: true,
            processing: true,
            ajax:{
                  url: '/setup/prospectus/subjgroup/datatable',
                  type: 'GET',
                  dataSrc: function ( json ) {
                        all_subjgroup = json.data
                        return json.data;
                  }
            },
            columns: [
                        { "data": "sort" },
                        { "data": "sortnum" },
                        { "data": "description" },
                        { "data": null },
                        { "data": null },
                  ],
            order: [
            [ 0, "asc" ]
            ],
            columnDefs: [
            {
            'targets': 0,
            'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).addClass('text-center')
                  $(td).addClass('align-middle')
            }
            },
            {
            'targets': 1,
            'orderable': false, 
            'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).addClass('text-center')
                  $(td).addClass('align-middle')
            }
            },
            // {
            //   'targets': 2,
            //   'orderable': false, 
            //   'createdCell':  function (td, cellData, rowData, row, col) {
            //       $(td).addClass('text-center')
            //       $(td).addClass('align-middle')
            //   }
            // },
            {
            'targets': 3,
            'orderable': false, 
            'createdCell':  function (td, cellData, rowData, row, col) {
                  var buttons = '<a href="javascript:void(0)" class="subjgroup_edit" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                  $(td)[0].innerHTML =  buttons
                  $(td).addClass('text-center')
                  $(td).addClass('align-middle')
            }
            },
            {
            'targets': 4,
            'orderable': false, 
            'createdCell':  function (td, cellData, rowData, row, col) {
                  var buttons = '<a href="javascript:void(0)" class="subjgroup_delete" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                  $(td)[0].innerHTML =  buttons
                  $(td).addClass('text-center')
                  $(td).addClass('align-middle')
            }
            },
                  
            ]
      });

      var label_text = $($("#subjgroup_datatable_wrapper")[0].children[0])[0].children[0]
      $(label_text)[0].innerHTML = '<button class="btn btn-sm btn-primary" id="subjgroup_to_create_modal">Create Subject Group</button>'

      }


      function subjgroup_create(){


      if($('#subjgroup_input_numorder').val() == ""){
      Toast.fire({
            type: 'info',
            title: 'Num. Order is empty!',
      })
      return false
      }

      if($('#subjgroup_input_description').val() == ""){
            Toast.fire({
                  type: 'info',
                  title: 'Decription is empty!',
            })
            return false
      }

      $.ajax({
      type:'GET',
      url:'/setup/prospectus/subjgroup/create',
      data:{
            sort:$('#subjgroup_input_sort').val(),
            numorder:$('#subjgroup_input_numorder').val(),
            description:$('#subjgroup_input_description').val()
      },
      success:function(data) {
            if(data[0].status == 1){
                  subjgroup_select('.is_subjgroup_select')
                  subjgroup_datatable()
            }
            Toast.fire({
                  type: data[0].icon,
                  title: data[0].message
            })
      },
      })
      }



      function subjgroup_update(){

      if($('#subjgroup_input_numorder').val() == ""){
      Toast.fire({
            type: 'info',
            title: 'Num. Order is empty!',
      })
      return false
      }

      if($('#subjgroup_input_description').val() == ""){
            Toast.fire({
                  type: 'info',
                  title: 'Decription is empty!',
            })
            return false
      }

            $.ajax({
                  type:'GET',
                  url:'/setup/prospectus/subjgroup/update',
                  data:{
                        id:seleted_id,
                        sort:$('#subjgroup_input_sort').val(),
                        numorder:$('#subjgroup_input_numorder').val(),
                        description:$('#subjgroup_input_description').val()
                  },
                  success:function(data) {
                        if(data[0].status == 1){
                              subjgroup_select('.is_subjgroup_select')
                              subjgroup_datatable()
                              
                        }
                        Toast.fire({
                              type: data[0].icon,
                              title: data[0].message
                        })
                  },
            })

      }



      function subjgroup_delete(){

      $.ajax({
      type:'GET',
      url:'/setup/prospectus/subjgroup/delete',
      data:{
            id:seleted_id
      },
      success:function(data) {
            if(data[0].status == 1){
                  subjgroup_select('.is_subjgroup_select')
                  subjgroup_datatable()
            }
            Toast.fire({
                  type: data[0].icon,
                  title: data[0].message
            })
      },
      })
      
      }

      var modal_html = '<div class="modal fade" id="subjgroup_modal" style="display: none; " aria-hidden="true" data-backdrop="static" data-keyboard="false">'+
      '<div class="modal-dialog modal-lg">'+
            '<div class="modal-content" >'+
                  '<div class="modal-header pb-2 pt-2 border-0">'+
                        '<h4 class="modal-title" style="font-size: 1.1rem !important">Subject Group</h4>'+
                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                        '<span aria-hidden="true">×</span></button>'+
                  '</div>'+
                  '<div class="modal-body pt-0">'+
                        '<div class="row mt-2" style="font-size:.8rem !important">'+
                        '<div class="col-md-12">'+
                              '<table class="table-hover table table-striped table-sm table-bordered" id="subjgroup_datatable" width="100%" >'+
                              '<thead>'+
                                    '<tr>'+
                                          '<th width="8%" class="align-middle p-0 text-center">Sort</th>'+
                                          '<th width="8%"  class="align-middle p-0 text-center">Code</th>'+
                                          '<th width="74%" class="align-middle">Group Description</th>'+
                                          '<th width="5%" class="align-middle text-center p-0"></th>'+
                                          '<th width="5%" class="align-middle text-center p-0"></th>'+
                                    '</tr>'+
                              '</thead>'+
                              '</table>'+
                        '<div>'+
                        '</div>'+
                  '</div>'+
            '</div>'+
      '</div>'+
      '</div>'   

      $('#subjgroup_modal').remove();
      $('body').append(modal_html)


      var modal_html = '<div class="modal fade" id="subjgroup_form_modal" style="display: none; " aria-hidden="true" data-backdrop="static" data-keyboard="false">'+
      '<div class="modal-dialog modal-sm">'+
            '<div class="modal-content" >'+
                  '<div class="modal-header pb-2 pt-2 border-0">'+
                        '<h4 class="modal-title" style="font-size: 1.1rem !important">Subject Group Form</h4>'+
                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                        '<span aria-hidden="true">×</span></button>'+
                  '</div>'+
                  '<div class="modal-body pt-0">'+
                        '<div class="row">'+
                              '<div class="col-md-12 form-group">'+
                                    '<label for="">Sort</label>'+
                                    '<input class="form-control form-control-sm" id="subjgroup_input_sort" onkeyup="this.value = this.value.toUpperCase();" >'+
                              '</div>'+
                        '</div>'+
                        '<div class="row">'+
                              '<div class="col-md-12 form-group">'+
                                    '<label for="">Num. Order</label>'+
                                    '<input class="form-control form-control-sm" id="subjgroup_input_numorder">'+
                              '</div>'+
                        '</div>'+
                        '<div class="row">'+
                              '<div class="col-md-12 form-group">'+
                                    '<label for="">Group Description</label>'+
                                    '<input class="form-control form-control-sm" id="subjgroup_input_description">'+
                              '</div>'+
                        '</div>'+
                        '<div class="row">'+
                              '<div class="col-md-12">'+
                              '<button class="btn btn-sm btn-primary" id="subjgroup_create_button"><i class="fa fa-save"></i> Save</button>'+
                              '<button class="btn btn-success btn-success btn-sm" id="subjgroup_update_button" hidden><i class="fa fa-save"></i> Update</button>'+
                              '</div>'+
                        '</div>'+
                  '</div>'+
            '</div>'+
      '</div>'+
      '</div>'   

      $('#subjgroup_form_modal').remove();
      $('body').append(modal_html)

      $(document).on('click','#subjgroup_to_create_modal',function(){

      $('#subjgroup_input_sort').val("")
      $('#subjgroup_input_numorder').val("")
      $('#subjgroup_input_description').val("")

      $('#subjgroup_create_button').removeAttr('hidden')
      $('#subjgroup_update_button').attr('hidden','hidden')
      $('#subjgroup_form_modal').modal()
      })


      $(document).on('click','.subjgroup_edit',function(){
      seleted_id = $(this).attr('data-id')
      var temp_info = all_subjgroup.filter(x=>x.id == seleted_id)
      $('#subjgroup_input_sort').val(temp_info[0].sort)
      $('#subjgroup_input_numorder').val(temp_info[0].sortnum)
      $('#subjgroup_input_description').val(temp_info[0].description)

      $('#subjgroup_create_button').attr('hidden','hidden')
      $('#subjgroup_update_button').removeAttr('hidden')
      
      $('#subjgroup_form_modal').modal()
      })

      $(document).on('click','#subjgroup_create_button',function(){
      subjgroup_create()
      })

      $(document).on('click','#subjgroup_update_button',function(){
      subjgroup_update()
      })

      $(document).on('click','.subjgroup_delete',function(){
      seleted_id = $(this).attr('data-id')
      Swal.fire({
            text: 'Are you sure you want to remove Subject Group?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Remove'
      }).then((result) => {
            if (result.value) {
            subjgroup_delete()
            }
      })
      })

</script>