
@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
     <style>
           .dropdown-toggle::after {
                  display: none;
                  margin-left: .255em;
                  vertical-align: .255em;
                  content: "";
                  border-top: .3em solid;
                  border-right: .3em solid transparent;
                  border-bottom: 0;
                  border-left: .3em solid transparent;
            }
     </style>
         
@endsection



@section('content')

      <div class="modal fade" id="quarter_setup_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                  <div class="modal-content">
                  <div class="modal-header bg-primary">
                        <h5 class="modal-title">Quarter Setup</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                  </div>
                  <div class="modal-body">
                        <form id="createQuarterSetup"  enctype="multipart/form-data">
                              @csrf
                              <div class="form-group">
                                    <label for="">Quarter Setup Description</label>
                                    <input class="form-control" name="qsDesc" id="qsDesc" withvalidation="withvalidation">
                                    <span class="invalid-feedback" role="alert">
                                          <strong>Description is required</strong>
                                    </span>
                              </div>
                              <div class="form-group">
                                    <label for="">Quarter Setup Type</label>
                                    <select  class="form-control" name="type" id="type" withvalidation="withvalidation">
                                         
                                    </select>
                                    <span class="invalid-feedback" role="alert">
                                          <strong>Type is required</strong>
                                    </span>
                              </div>
                              <div class="form-group" hidden>
                                    <label for="">Prelim Percentage</label>
                                    <input class="form-control" name="semi" >
                                    <span class="invalid-feedback" role="alert">
                                          <strong>Perlim percentage is required</strong>
                                    </span>
                              </div>
                              <div class="form-group" hidden>
                                    <label for="">Midterm Percentage</label>
                                    <input class="form-control" name="mid" >
                                    <span class="invalid-feedback" role="alert">
                                          <strong>Midterm percentage  is required</strong>
                                    </span>
                              </div>
                              <div class="form-group" hidden>
                                    <label for="">Prefi Percentage</label>
                                    <input class="form-control" name="prefi">
                                    <span class="invalid-feedback" role="alert">
                                          <strong>Prefinal percentage  is required</strong>
                                    </span>
                              </div>
                              <div class="form-group" hidden>
                                    <label for="">Final Percentage</label>
                                    <input class="form-control" name="final">
                                    <span class="invalid-feedback" role="alert">
                                          <strong>Final percentage  is required</strong>
                                    </span>
                              </div>
                              <div class="form-group">
                                    <input class="form-control" id="total" hidden>
                                    <span class="invalid-feedback" role="alert">
                                          <strong>Total percentage should equal to 100</strong>
                                    </span>
                              </div>
                     
                        <div class="modal-footer">
                              <button class="btn btn-primary float-left" >CREATE QUARTER SETUP</button>
                        </div>
                  </form>
                  </div>
                  </div>
            </div>
      </div>

      <section class="content">
      
                  <div class="card">
                        <div class="card-header bg-primary">
                              <h5>QUARTER SETUP TABLE</h5>
                        </div>
                        <div class="card-body" id="quarter_setup_table_holder">

                        </div>
                  </div>
       
      </section>

@endsection

@section('footerscript')


      <script>
            $(document).ready(function(){


                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                  });

                  loadquartersetup()
                  var actionType;
                  var selectedSetup;

                  function loadquartersetup(){
                        
                        $.ajax({
                              type:'POST',
                              data: {'_token': '{{ csrf_token() }}'},
                              url:'/quartersetup?teacher='+'{{DB::table('teacher')->where("userid",auth()->user()->id)->first()->id}}'+'&table=table',
                              success:function(data) {
                                   
                                    $('#quarter_setup_table_holder').empty()
                                    $('#quarter_setup_table_holder').append(data)
                              }
                        })


                  }

                  $(document).on('change','select[name="type"]',function(){

                        $('input[name="semi"]').closest('.form-group').attr('hidden','hidden')
                        $('input[name="mid"]').closest('.form-group').attr('hidden','hidden')
                        $('input[name="prefi"]').closest('.form-group').attr('hidden','hidden')
                        $('input[name="final"]').closest('.form-group').attr('hidden','hidden')

                       
                        $('input[name="semi"]').removeAttr('withvalidation',)
                        $('input[name="mid"]').removeAttr('withvalidation')
                        $('input[name="prefi"]').removeAttr('withvalidation')
                        $('input[name="final"]').removeAttr('withvalidation')

                        $('input[per=per]').removeAttr('per')

                        $.ajax({
                              type:'GET',
                              data: {'_token': '{{ csrf_token() }}'},
                              url:'/gradetermsetup?detail=detail&id='+$(this).val(),
                              success:function(data) {

                                    if(data.length > 0){

                                          if(data[0].withpre == 1){
                                                $('input[name="semi"]').closest('.form-group').removeAttr('hidden')
                                                $('input[name="semi"]').attr('withvalidation','withvalidation')
                                                $('input[name="semi"]').attr('per','per')
                                          }

                                          if(data[0].withmid == 1){
                                                $('input[name="mid"]').closest('.form-group').removeAttr('hidden')
                                                $('input[name="mid"]').attr('withvalidation','withvalidation')
                                                $('input[name="mid"]').attr('per','per')
                                          }
                                          if(data[0].withsemi == 1){
                                                $('input[name="prefi"]').closest('.form-group').removeAttr('hidden')
                                                $('input[name="prefi"]').attr('withvalidation','withvalidation')
                                                $('input[name="prefi"]').attr('per','per')
                                          }
                                          if(data[0].withfinal == 1){
                                                $('input[name="final"]').closest('.form-group').removeAttr('hidden')
                                                $('input[name="final"]').attr('withvalidation','withvalidation')
                                                $('input[name="final"]').attr('per','per')
                                          }

                                    }
                              }
                       
                             
                             
                        })

                       
                  })

                  $(document).on('click','#showCreateSetupModal',function(){

                        actionType = 2

                        $.ajax({
                              type:'GET',
                              data: {'_token': '{{ csrf_token() }}'},
                              url:'/gradetermsetup?info=info',
                              success:function(data) {
                                    $('#type').empty()

                                    $('#type').append('<option value="">Select Setup Type</option>')

                                    $.each(data,function(a,b){

                                          $('#type').append('<option value="'+b.id+'">'+b.description+'</option>')

                                    })

                                    $('#type').val("").change()
                              }
                        })

                        $('#createQuarterSetup')[0].reset();

                        $('input[name="semi"]').closest('.form-group').attr('hidden','hidden')
                        $('input[name="mid"]').closest('.form-group').attr('hidden','hidden')
                        $('input[name="prefi"]').closest('.form-group').attr('hidden','hidden')
                        $('input[name="final"]').closest('.form-group').attr('hidden','hidden')

                        $('#quarter_setup_modal').modal()

                  })

                  $(document).on('click','.removeSetup',function(){
    
                        $.ajax({
                              type:'POST',
                              data: {'_token': '{{ csrf_token() }}'},
                              url:'/quartersetup?remove=remove&setup='+$(this).attr('data-id'),
                              success:function() {
                                    loadquartersetup()
                              }
                        })

                  })

                  $(document).on('click','.editSetup',function(){


                        $.ajax({
                              type:'GET',
                              data: {'_token': '{{ csrf_token() }}'},
                              url:'/gradetermsetup?info=info',
                              success:function(data) {
                                    $('#type').empty()

                                    $('#type').append('<option value="">Select Setup Type</option>')

                                    $.each(data,function(a,b){

                                          $('#type').append('<option value="'+b.id+'">'+b.description+'</option>')

                                    })

                                    $('#type').val("").change()
                              }
                        })

                        actionType = 1

                        $('#quarter_setup_modal .modal-footer button').removeClass('btn-primary')
                        $('#quarter_setup_modal .modal-footer button').addClass('btn-success')
                        $('#quarter_setup_modal .modal-footer button').text('UPDATE QUARTER SETUP')

                        $('#quarter_setup_modal .modal-header').removeClass('bg-primary')
                        $('#quarter_setup_modal .modal-header').addClass('bg-success')

                        $('#createQuarterSetup')[0].reset()
                       
                        selectedSetup = $(this).attr('data-id')

                        $('#quarter_setup_modal').modal()
                        $.ajax({
                              type:'POST',
                              data: {'_token': '{{ csrf_token() }}'},
                              url:'/quartersetup?info=info&setup='+$(this).attr('data-id'),
                              success:function(data) {
                                    
                                    $('input[name="qsDesc"]').val(data[0].qsDesc)
                                    $('select[name="type"]').val(data[0].type).change()
                                    $('input[name="mid"]').val(data[0].mid)
                                    $('input[name="final"]').val(data[0].final)
                                    $('input[name="semi"]').val(data[0].semi)
                                    $('input[name="prefi"]').val(data[0].pre)

                              }
                        })

                  })


                  $('#createQuarterSetup').submit(function(e){


                        var validinputs = true;

                        $('input[withvalidation="withvalidation"]').each(function(){
                              
                              if($(this).val() == ''){

                                   $(this).addClass('is-invalid')
                                   var validinputs = false;

                              }
                              else{

                                    $(this).removeClass('is-invalid')

                              }
                             
                        })

                         $('select[withvalidation="withvalidation"]').each(function(){
                              
                              if($(this).val() == ''){

                                   $(this).addClass('is-invalid')
                                   var validinputs = false;

                              }
                              else{

                                    $(this).removeClass('is-invalid')

                              }
                             
                        })

                        var totalPercentage = parseInt(0);

                        $('input[per="per"]').each(function(){
                  
                              if($(this).val() != ''){

                                    totalPercentage  += parseInt($(this).val())
                                  
                              }

                        })

                        if(totalPercentage == 100 || totalPercentage == 0){

                              $('#total').removeClass('is-invalid')
                        }
                        else{

                              $('#total').addClass('is-invalid')
                              var validinputs = false;
                            

                        }


                        if(!validinputs){
                              Toast.fire({
                                    type: 'error',
                                    title: 'Invalid Inputs!'
                              })
                        }

                        if(validinputs){

                              var inputs = new FormData(this)

                              if(actionType == 2){

                                    url = '/quartersetup?create=create'

                              }
                              else{
                                    
                                    url = '/quartersetup?update=update&setup='+selectedSetup
                                    
                              }

                              $.ajax({
                                    type:'POST',
                                    data: inputs,
                                    url:url,
                                    processData: false,
                                    contentType: false,
                                    success:function(data) {

                                          if(data[0].status == 0){

                                                $.each(data[0].errors,function(a,b){

                                                      $('#'+a).addClass('is-invalid')
                                                })

                                          }
                                          else{

                                                $('#total').removeClass('is-invalid')
                                                $('#qsDesc').removeClass('is-invalid')
                                                $('#tyoe').removeClass('is-invalid')
                                                if(actionType == 2){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: 'Created Successfully!'
                                                      })
                                                }
                                                else{
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: 'Updated Successfully!'
                                                      })
                                                }

                                                


                                                loadquartersetup()

                                          }
                                          
                                    }
                              })
                        }

                        e.preventDefault();
                  })

            })


      </script>
      

@endsection

