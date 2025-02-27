
@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
         
@endsection



@section('content')

      <div class="modal fade" id="setupModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content ">
            <div class="modal-header bg-success">
                  <h5 class="modal-title">GRADE SETUP</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
            </div>
                  <div class="modal-body p-0 table-responsive" style="height: 471px" id="setupTable">
                        
                  </div>
            </div>
            </div>
      </div>
      <div class="modal fade" id="createSetupModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog  modal-lg">
            <div class="modal-content ">
            <div class="modal-header bg-success">
                  <h5 class="modal-title">CREATE SETUP</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
            </div>
                  <form id="createSetup"  enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body table-responsive" style="height: 471px">
                              <div class="form-group">
                                    <label for="">Setup Description</label>
                                    <input type="text" class="form-control" name="setupDesc">
                              </div>
                              <div class="form-group">
                                    <label for="">Percentage</label>
                                    <input class="form-control" name="percentage">
                              </div>
                              <div class="form-group">
                                    <label for="">Number of Columns</label>
                                    <input class="form-control" name="items">
                              </div>
                              <button class="btn btn-primary">CREATE</button>
                        </div>
                  </form>
            </div>
            </div>
      </div>

      <section class="content">
      
                  <div class="card">
                        <div class="card-header bg-primary">
                              <h5>GRADE SETUP TABLE</h5>
                        </div>
                        <div class="card-body" id="grade_setup_table_holder">

                        </div>
                  </div>
       
      </section>

@endsection

@section('footerscript')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>

<script>
      $(document).ready(function(){


            // loaddatatable(students)



            // var selectedSubj
            // var selectedSection
            // var selectedterm
            // var setupId
            // var setupstatus

            // $(document).on('input','input[name="items"]',function(){

            //       if($(this).val() > 10){
            //             $('input[name="items"]').val(10)
            //       }

            // })

            
            // const Toast = Swal.mixin({
            //             toast: true,
            //             position: 'top-end',
            //             showConfirmButton: false,
            //             timer: 3000
            //       });

            // viewGradeSetupTable()

            // function viewGradeSetupTable(){

            //       $.ajax({
            //             type:'GET',
            //             url:'/college/teacher/gradesetuptable',
            //             success:function(data) {
                        
            //                   $('#grade_setup_table_holder').empty()
            //                   $('#grade_setup_table_holder').append(data)
            //             }
            //       })

            // }

            

            // function viewSetup(){

            //       $.ajax({
            //             type:'GET',
            //             url:'/schedule?student=student&setuptable=setuptable&select=firstname,lastname,middlename&subject='+selectedSubj+'&section='+selectedSection+'&term='+selectedterm,
            //             success:function(data) {
            //                   $('#setupTable').empty()
            //                   $('#setupTable').append(data)
            //             }
            //       })
            // }

            // $(document).on('click','.viewSetup',function(){

            //       $('#setupModal').modal()
            //       selectedSubj = $(this).attr('data-subj')
            //       selectedSection = $(this).attr('data-section')
            //       selectedterm = $(this).attr('data-term')

            //       viewSetup()

            // })

            // $(document).on('click','.editSetup',function(){

            //       $('#createSetupModal').modal();

            //       setupStatus = 1;
            //       setupId = $(this).attr('data-id');

            //       showInputs()

            //       $('#createSetupModal .modal-title').text('UPDATE SETUP')

            //       modalHeader = $('#createSetupModal .modal-header')
            //       modalHeader.removeClass()
            //       modalHeader.attr('class','modal-header btn-info')

            //       actionButton = $('#createSetupModal .btn');
            //       actionButton.text('UPDATE SETUP')
            //       actionButton.removeClass()
            //       actionButton.attr('class','btn btn-info')

            //       })
            
            // $(document).on('click','#duplicate_from_current_subject',function(){

            //       $.ajax({
            //             type:'POST',
            //             data: {'_token': '{{ csrf_token() }}'},
            //             url:'/college/teacher/createsetup?duplicate=duplicate&sectionid='+selectedSection+'&subjid='+selectedSubj+'&term='+selectedterm,
            //             success:function(data) {

            //                   originalSetup()
            //                   viewSetup()
            //                   viewGradeSetupTable()
                             
            //             }
            //       })
            // })

            // $(document).on('click','.removeSetup',function(){

            //       $('#createSetupModal').modal();

            //       setupStatus = 2;
            //       setupId = $(this).attr('data-id');

            //       showInputs()
            //       $('#createSetupModal .modal-title').text('REMOVE SETUP')

            //       modalHeader = $('#createSetupModal .modal-header')
            //       modalHeader.removeClass()
            //       modalHeader.attr('class','modal-header btn-danger')

            //       actionButton = $('#createSetupModal .btn');
            //       actionButton.text('REMOVE SETUP')
            //       actionButton.removeClass()
            //       actionButton.attr('class','btn btn-danger')

            // })

            // function showInputs(){

            //       $('input[name="setupDesc"]').val($('#setupTable tbody tr[data-id="'+setupId+'"]')[0].children[0].innerText)

            //       $('input[name="percentage"]').val($('#setupTable tbody tr[data-id="'+setupId+'"]')[0].children[1].innerText)

            //       $('input[name="items"]').val($('#setupTable tbody tr[data-id="'+setupId+'"]')[0].children[2].innerText)

            // }


            // $(document).on('click','#showCreateSetupModal',function(){
            //       setupId = null
            //       setupStatus = 0
            //       $('#createSetupModal').modal();

            //       originalSetup()
            // })

            // function originalSetup(){

            //       $('#createSetupModal .modal-title').text('CREATE SETUP')

            //       modalHeader = $('#createSetupModal .modal-header')
            //       modalHeader.removeClass()
            //       modalHeader.attr('class','modal-header btn-primary')

            //       actionButton = $('#createSetupModal .btn');
            //       actionButton.text('CREATE SETUP')
            //       actionButton.removeClass()
            //       actionButton.attr('class','btn btn-primary')

            //       $('#createSetup')[0].reset();

            // }


            // $('#createSetup').submit( function( e ) {

            //       var inputs = new FormData(this)
            //       inputs.append('subjID',selectedSubj)
            //       inputs.append('setupstatus',setupStatus)
            //       inputs.append('setupId',setupId)
            //       inputs.append('sectionid',selectedSection)
            //       inputs.append('term',selectedterm)

            //       $.ajax( {
            //             url: '/college/teacher/createsetup',
            //             type: 'POST',
            //             data: inputs,
            //             processData: false,
            //             contentType: false,
            //             success:function(data) {
                             
            //                   if(data == 0){

            //                         $('#createSetupModal').modal('hide')
            //                         Toast.fire({
            //                               type: 'success',
            //                               title: 'Created successfully!'
            //                         })
            //                         originalSetup()
            //                         viewSetup()
            //                         viewGradeSetupTable()

            //                   }else if(data == 1){
                                    
                                    
            //                         $('#createSetupModal').modal('hide')
            //                         Toast.fire({
            //                               type: 'success',
            //                               title: 'Updated successfully!'
            //                         })
                                  
            //                         originalSetup()
            //                         viewSetup()
            //                         viewGradeSetupTable()

            //                   }else if(data == 2){
                                          
                                    
            //                         $('#createSetupModal').modal('hide')
            //                         Toast.fire({
            //                               type: 'success',
            //                               title: 'Deleted successfully!'
            //                         })
            //                         originalSetup()
            //                         viewSetup()
            //                         viewGradeSetupTable()
            //                   }

                           
            //             },
            //       } );
            //       e.preventDefault();

            //       })
            })
</script>

      

@endsection

