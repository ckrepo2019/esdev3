
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

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


@section('modalSection')
  <div class="modal fade" id="create_term_setup_modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title">GRADE TERM SETUP</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <form id="term_setup_form" autocomplete="off" enctype="multipart/form-data">
            <div class="modal-body">
                        @csrf
                        <div class="form-group">
                              <div class="icheck-success d-inline">
                                    <input type="checkbox" id="isactive" name="isactive" value="1">
                                    <label for="isactive">ACTIVE</label>
                              </div>
                        </div>
                        
                        <div class="form-group row">
                              <label for="">Description</label>
                              <input type="text" class="form-control" id="description" name="description">
                        </div>

                        <div class="form-group row">
                              <div class="icheck-success d-inline col-md-6">
                                    <input type="checkbox" id="withpre" name="withpre" value="1">
                                    <label for="withpre">WITH PRELIM</label>
                              </div>
                              <div class="icheck-success d-inline col-md-6">
                                    <input type="checkbox" id="withmid" name="withmid" value="1">
                                    <label for="withmid">WITH MIDTERM</label>
                              </div>
                              <div class="icheck-success d-inline col-md-6">
                                    <input type="checkbox" id="withsemi" name="withsemi" value="1">
                                    <label for="withsemi">WITH SEMI</label>
                              </div>
                              <div class="icheck-success d-inline col-md-6">
                                    <input type="checkbox" id="withfinal" name="withfinal" value="1">
                                    <label for="withfinal">WITH FINAL</label>
                              </div>
                        </div>
                        <hr>
                        <label for="">PRELIM TERM DETAILS</label>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Type</label>
                              <div class="col-md-8">
                                    <select name="pttype" id="pttype" class="form-control form-control-sm">
                                          <option value="">None</option>
                                          <option value="1">Averaging</option>
                                          <option value="2">Percentage</option>
                                    </select>
                              </div>
                        </div>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Prelim</label>
                              <div class="col-md-8">
                                    <input class="form-control form-control-sm" type="text" name="ptptper" id="ptptper" placeholder="%">
                              </div>
                        </div>
                        <hr>
                        <label for="">MID TERM DETAILS</label>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Type</label>
                              <div class="col-md-8">
                                    <select name="mttype" id="mttype" class="form-control form-control-sm">
                                          <option value="">None</option>
                                          <option value="1">Averaging</option>
                                          <option value="2">Percentage</option>
                                    </select>
                              </div>
                        </div>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Prelim</label>
                              <div class="col-md-8">
                                    <input class="form-control form-control-sm" type="text" name="mtptper" id="mtptper" placeholder="%">
                              </div>
                        </div>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Mid Term</label>
                              <div class="col-md-8">
                                    <input class="form-control form-control-sm" type="text" name="mtmtper" id="mtmtper" placeholder="%">
                              </div>
                        </div>
                        <hr>
                        <label for="">SEMI FINAL TERM DETAILS</label>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Type</label>
                              <div class="col-md-8">
                                    <select name="sttype" id="sttype" class="form-control form-control-sm">
                                          <option value="">None</option>
                                          <option value="1">Averaging</option>
                                          <option value="2">Percentage</option>
                                    </select>
                              </div>
                        </div>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Prelim</label>
                              <div class="col-md-8">
                                    <input class="form-control form-control-sm" type="text" name="stptper" id="stptper" placeholder="%">
                              </div>
                        </div>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Midterm</label>
                              <div class="col-md-8">
                                    <input class="form-control form-control-sm" type="text" name="stmtper" id="stmtper" placeholder="%">
                              </div>
                        </div>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Semi</label>
                              <div class="col-md-8">
                                    <input class="form-control form-control-sm" type="text" name="ststper" id="ststper" placeholder="%">
                              </div>
                        </div>
                        <hr>
                        <label for="">FINAL TERM DETAILS</label>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Type</label>
                              <div class="col-md-8">
                                    <select name="fttype" id="fttype" class="form-control form-control-sm">
                                          <option value="">None</option>
                                          <option value="1">Averaging</option>
                                          <option value="2">Percentage</option>
                                    </select>
                              </div>
                        </div>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Prelim</label>
                              <div class="col-md-8">
                                    <input class="form-control form-control-sm" type="text" name="ftptper" id="ftptper" placeholder="%">
                              </div>
                        </div>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Midterm</label>
                              <div class="col-md-8">
                                    <input class="form-control form-control-sm" type="text" name="ftmtper" id="ftmtper" placeholder="%">
                              </div>
                        </div>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Prelim</label>
                              <div class="col-md-8">
                                    <input class="form-control form-control-sm" type="text" name="ftstper" id="ftstper" placeholder="%">
                              </div>
                        </div>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Final</label>
                              <div class="col-md-8">
                                    <input class="form-control form-control-sm" type="text" name="ftftper" id="ftftper" placeholder="%">
                              </div>
                        </div>
                        <hr>
                        <label for="">FINAL GRADE</label>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Type</label>
                              <div class="col-md-8">
                                    <select name="fgtype" id="fgtype" class="form-control form-control-sm">
                                          <option value="">None</option>
                                          <option value="1">Averaging</option>
                                          <option value="2">Percentage</option>
                                    </select>
                              </div>
                        </div>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Prelim</label>
                              <div class="col-md-8">
                                    <input class="form-control form-control-sm" type="text" name="fgptper" id="fgptper" placeholder="%">
                              </div>
                        </div>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Midterm</label>
                              <div class="col-md-8">
                                    <input class="form-control form-control-sm" type="text" name="fgmtper" id="fgmtper" placeholder="%">
                              </div>
                        </div>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Semi</label>
                              <div class="col-md-8">
                                    <input class="form-control form-control-sm" type="text" name="fgstper" id="fgstper" placeholder="%">
                              </div>
                        </div>
                        <div class="form-group row">
                              <label for="" class="col-md-4 text-right">Final</label>
                              <div class="col-md-8">
                                    <input class="form-control form-control-sm" type="text" name="fgftper" id="fgftper" placeholder="%">
                              </div>
                        </div>
                        
                        


                        {{-- <div class="form-group row">
                              <div class="icheck-success d-inline col-md-6">
                                    <input type="checkbox" id="withmid" name="withmid" value="1">
                                    <label for="withmid">WITH MIDTERM</label>
                              </div>
                              <div class="col-md-6">
                                    <input class="form-control" type="text" name="midper" id="midper" placeholder="%">
                              </div>
                        </div>
                        <div class="form-group row">
                              <div class="icheck-success d-inline col-md-6">
                                    <input type="checkbox" id="withsemi" name="withsemi" value="1">
                                    <label for="withsemi">WITH SEMI</label>
                              </div>
                              <div class="col-md-6">
                                    <input class="form-control" type="text" name="semiper" id="semiper" placeholder="%">
                              </div>
                        </div>
                        <div class="form-group row">
                              <div class="icheck-success d-inline col-md-6">
                                    <input type="checkbox" id="withfinal" name="withfinal" value="1">
                                    <label for="withfinal">WITH FINAL</label>
                              </div>
                              <div class="col-md-6">
                                    <input class="form-control" type="text" name="finalper" id="finalper" placeholder="%">
                              </div>
                        </div> --}}
                        {{-- <hr>
                        <div class="form-group row">
                              <label for="">Final Grade Percentage</label>
                        </div>
                        <div class="form-group row">
                              <div class="col-md-6">
                                    <label for="">PRELIM</label>
                                    <input class="form-control" type="text" name="fprelimper" id="fprelimper" placeholder="%">
                              </div>
                              <div class="col-md-6">
                                    <label for="">Term Composition</label>
                                    <select id="prelimcomp" name="prelimcomp[]" class="select2 form-control"  multiple="multiple" data-placeholder="Select term" >
                                          <option value="P">Prelim</option>
                                          <option value="M">Midterm</option>
                                          <option value="S">Semi</option>
                                          <option value="F">Final</option>
                                    </select>
                              </div>
                        </div>
                        <div class="form-group row">
                              <div class="d-inline col-md-6">
                                    <label for="">MIDTERM</label>
                                    <input class="form-control" type="text" name="fmidper" id="fmidper" placeholder="%">
                              </div>
                              <div class="col-md-6">
                                    <label for="">Term Composition</label>
                                    <select id="midcomp" name="midcomp[]" class="select2 form-control"  multiple="multiple" data-placeholder="Select term" >
                                          <option value="P">Prelim</option>
                                          <option value="M">Midterm</option>
                                          <option value="S">Semi</option>
                                          <option value="F">Final</option>
                                    </select>
                              </div>
                        </div>
                        <div class="form-group row">
                              <div class="d-inline col-md-6">
                                    <label for="" class="p-0">SEMI</label>
                                    <input class="form-control" type="text" name="fsemiper" id="fsemiper" placeholder="%">
                              </div>
                              <div class="col-md-6">
                                    <label for="">Term Composition</label>
                                    <select id="semicomp" name="semicomp[]" class="select2 form-control"  multiple="multiple" data-placeholder="Select term" >
                                          <option value="P">Prelim</option>
                                          <option value="M">Midterm</option>
                                          <option value="S">Semi</option>
                                          <option value="F">Final</option>
                                    </select>
                              </div>
                        </div>
                        <div class="form-group row">
                              <div class="d-inline col-md-6">
                                    <label for="">FINAL</label>
                                    <input class="form-control" type="text" name="ffinalper" id="ffinalper" placeholder="%">
                              </div>
                              <div class="col-md-6">
                                    <label for="">Term Composition</label>
                                    <select id="finalcomp" name="finalcomp[]" class="select2 form-control"  multiple="multiple" data-placeholder="Select term" >
                                          <option value="P">Prelim</option>
                                          <option value="M">Midterm</option>
                                          <option value="S">Semi</option>
                                          <option value="F">Final</option>
                                    </select>
                              </div>
                        </div>
                        <hr>
                        <div class="form-group">
                              <div class="icheck-success d-inline">
                                    <input type="checkbox" id="activestatus" name="activestatus" value="1">
                                    <label for="activestatus">ACTIVE</label>
                              </div>
                        </div> --}}
             
            </div>
            <div class="modal-footer justify-content-between">
                <button  type="submit" class="btn btn-primary savebutton" data-id="1">SAVE</button>
            </div>
      </form>
        </div>
    </div>
  </div>
@endsection

@section('content')


<section class="content-header">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-sm-6">
                  
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">School Info</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
        <div class="container-fluid">
            <div class="row">
                  <div class="col-12">
                        <div class="card">
                              <div class="card-header bg-primary">
                                    <h5 class="card-title">GRADE SETUP TERM</h5>
                                    <button class="btn btn-sm btn-default float-right" id="create_term_setup_button"><b>ADD GRADE TERM SETUP</b></button>
                              </div>
                              <div class="card-body table-responsive p-0 " id="dataholder" >
                                 
                              </div>
                              <div class="card-footer">
                                    <div class="" id="data-container">
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
      <script>

            $(document).ready(function(){
                  $(function () {
                        $('.select2').select2({
                              theme: 'bootstrap4'
                        })
                  })
                  $(document).on('click','#create_term_setup_button',function(){
                        
                        $('#create_term_setup_modal').modal('show')

                  })

                  $('#term_setup_form').submit( function(e){
                        

                        var inputs = new FormData(this)
                        

                        console.log(inputs)

                        $.ajax({
                              type:'POST',
                              data: {'_token': '{{ csrf_token() }}'},
                              // url: '/gradetermsetup?withprelim='+withpre+'&withmid='+withmid+'&withsemi='+withsemi+'&withfinal='+withfinal+'&description='+$('#description').val()+'&isactive='+active+'&create=create',
                              url:'/gradetermsetup?create=create',
                              data: inputs,
                              processData: false,
                              contentType: false,
                              success:function(){

                                    // $('#term_setup_form')[0].reset()
                                    // $('#create_term_setup_modal').modal('hide')
                                    loadgradetermsetup()

                              }
                        })

                        e.preventDefault();

                  })

                  loadgradetermsetup()

                  function loadgradetermsetup(){

                        $.ajax({
                              type:'POST',
                              data: {'_token': '{{ csrf_token() }}'},
                              url: '/gradetermsetup?table=table',
                              success:function(data){
                                   $('#dataholder').empty()
                                   $('#dataholder').append(data)
                              }
                        })

                  }

            })
      
      </script> 
@endsection

