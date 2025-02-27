
@extends('hr.layouts.app')

@section('pagespecificscripts')

      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">

@endsection
<style>
      .pagination{
            float: right;
      }
      #teacher_table tbody td:last-child{
            text-align: center;
      }
      #teacher_table_filter{
            float: right;
      }
</style>

{{-- @section('modalSection') --}}

<div class="modal fade" id="teacher_evaluation_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
              <div class="modal-header bg-primary">
                  <h4 class="modal-title">Teacher Evaluation</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body" id="subject_assignment_table">
                  
              </div>
              <div class="row m-3">
                    <div class="col-md-12">
                        <h4>COMMENTS (<strong id="commentscount"></strong>)</h4>
                    </div>
              </div>
              <div class="row m-3" id="commentscontainer" style="height: 200px; overflow: scroll;">
                    {{-- <div class="col-md-12">

                    </div> --}}
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-primary exporteval" data-et="pdf" disabled><i class="fa fa-file-pdf"></i> PDF</button>
                    <button type="button" class="btn btn-primary exporteval" data-et="excel"><i class="fa fa-file-excel"></i> EXCEL</button>
              </div>
          </div>
      </div>
</div>

{{-- <div class="modal fade" id="qurter_setup" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header bg-primary">
                  <h4 class="modal-title">Teacher Evaluation</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body">
                    <table class="table">
                        <tbody>
                              @foreach (DB::table('quarter_setup')->where('deleted',0)->get() as $item)
                                    <tr>
                                          <td width="50%">{{$item->description}}</td>
                                          <td width="50%">
                                                @if($item->teachereval == 0)      
                                                      <button class="btn btn-danger">Set as active</button>
                                                @else
                                                      <button class="btn btn-danger">Active</button>
                                                @endif
                                          </td>
                                    </tr>
                              @endforeach
                        </tbody>
                    </table>
                       
              </div>
          </div>
      </div>
</div> --}}


{{-- @endsection --}}

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
                                    <h5 class="card-title">TEACHERS EVALUATION</h5>
                              </div>
                              <div class="card-body">
                                    <div class="row">
                                          {{-- <button class="btn btn-primary" id="teqs_button">Teacher Evaluation Quarter Setup</button> --}}
                                    </div>
                                    <div class="row mt-5">
                                          <div class="col-md-12">
                                                <table class="table" id="teacher_table">
                                                      <thead>
                                                            <tr>
                                                                  <th>Lastname</th>
                                                                  <th>Firstname</th>
                                                                  <th></th>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
                                                       
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                                  
                              </div>
                        </div>   
                  </div>
            </div>
        </div>
</section>

<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

@include('superadmin.pages.gradingsystem.teacherEvaluation.teachereval_script')

<script>
      $(document).on('click','.vieweval', function(){
            var teacherid          = $(this).attr('data-id');
            $.ajax({
                  url: '/hrreports/viewcomments',
                  type: 'GET',
                  data: {
                        teacherid : teacherid
                  }, success:function(data)
                  {
                        $('#commentscount').text(data.length)
                        $('#commentscontainer').empty()

                        if(data.length>0)
                        {
                              $.each(data, function(key, value){
                                    $('#commentscontainer').append(
                                          '<div class="col-md-12">'+
                                                '<p>'+(key+1)+'. '+value+'</p>'+
                                          '</div>'
                                    )
                              })
                        }
                  }
            })

      })
      
      $('.exporteval').on('click', function(){
            var exporttype         = $(this).attr('data-et');
            var teacherid          = $(this).attr('data-id');
            var ddvalues           = [];

            $('.dd').each(function(){
                  ddvalues.push($(this).attr('data-id'));
                  // console.log(ddvalues)
            })
            var paramet = {
                  exporttype  : exporttype,
                  ddvalues    : ddvalues,
                  teacherid   : teacherid
            }
                  // console.log($.param(paramet))
            window.open("/hrreports/viewevaluation?"+$.param(paramet));
        })
</script>
@endsection

{{-- @section('footerjavascript') --}}

     
{{-- @endsection --}}

