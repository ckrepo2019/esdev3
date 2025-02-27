@extends('principalsportal.layouts.app2')

@section('pagespecificscripts')
  
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<style>
      .table.table-head-fixed thead tr:nth-child(1) th {
            top: -1px !important;
      }
      #grade_status td{
            vertical-align: middle !important;
      }
</style>
    
@endsection

@section('modalSection')

@php
      $submittedGrades = array();
      $approvedGrades = array();
      $postedGrades = array();
      $pendingGrades = array();

      foreach ($sectionsubj as $item){
          
            if($item->q1status == 1 || $item->q2status == 1 || $item->q3status == 1 || $item->q4status == 1){
                  array_push($submittedGrades, $item);
            }
            
            if($item->q1status == 2 || $item->q2status == 2 || $item->q3status == 2 || $item->q4status == 2){
                  array_push($approvedGrades, $item);
            }
            
            if($item->q1status == 3 || $item->q2status == 3 || $item->q3status == 3 || $item->q4status == 3){
                  array_push($postedGrades, $item);
            }
            
            if($item->q1status == 4 || $item->q2status == 4 || $item->q3status == 4 || $item->q4status == 4){

                  array_push($pendingGrades, $item);
            }

      }
   
@endphp


<div class="modal fade" id="submitted_grades_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header bg-primary">
                  <h4 class="modal-title">Submitted Grades</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body">
                  <div class="row">
                        <div class="col-md-3 form-group">
                              <label for="">Section</label>
                              <select name="" data-id="f_s" data-filter="f_s" class="form-control select2">
                                    <option value="" >All</option>
                                    @foreach ($sections as $item)
                                          <option value="{{$item->id}}" >{{ $item->sectionname }}</option>
                                    @endforeach
                              </select>
                        </div>
                        <div class="col-md-3 form-group">
                              <label for="">Subject</label>
                              <select name="" data-id="f_s" data-filter="f_ss" class="form-control select2">
                                    <option value="" >All</option>
                                    @foreach ($subjects as $item)
                                          <option value="{{$item->subjid}}" >{{ $item->subjcode, 15}}</option>
                                    @endforeach
                              </select>
                        </div>
                        <div class="col-md-4 form-group">
                              <label for="">Teacher</label>
                              <select name="" data-id="f_s" data-filter="f_t" class="form-control select2">
                                    <option value="" >All</option>
                                    @foreach ($teachers as $item)
                                          <option value="{{$item->teacherid}}" >{{ $item->lastname.', '.$item->firstname, 15}}</option>
                                    @endforeach
                              </select>
                        </div>
                        <div class="col-md-2 form-group">
                              <label for="">Quarter</label>
                              <select name="" data-id="f_s" data-filter="f_q" class="form-control select2">
                                    <option value="" >All</option>
                                    <option value="1" >1st Quarter</option>
                                    <option value="2" >2nd Quarter</option>
                                    <option value="3" >3rd Quarter</option>
                                    <option value="4" >4th Quarter</option>
                              </select>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-3">
                              <button class="btn btn-primary filter" id="f_s"><i class="fas fa-filter"></i> FILTER</button>
                        </div>
                  </div>
                  <div class="row mt-3">
                        <div class="col-md-12 table-responsive" style="height: 400px">
                              <table class="table table-head-fixed" id="submitted_table" >
                                    <thead>
                                          <tr>
                                                <th width="15%">Grade Level</th>
                                                <th width="20%">Section</th>
                                                <th width="15%">Subject</th>
                                                <th width="22%">Teacher</th>
                                                <th width="10%">Quarter</th>
                                                <th width="18%"></th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          @if(count($submittedGrades) > 0)
                                                @foreach ($submittedGrades as $item)
                                                      @php
                                                            $quarter;
                                                            if($item->q1status == 1){$quarter=1;}
                                                            elseif($item->q2status == 1){$quarter=2;}
                                                            elseif($item->q3status == 1){$quarter=3;}
                                                            elseif($item->q4status == 1){$quarter=4;}
                                                      @endphp
                                                      <tr class="approve" data-gstatus="{{$item->gstatus}}" data-s="{{$item->id}}" data-ss="{{$item->subjid}}" data-q="{{$quarter}}" data-t="{{$item->teacherid}}" data-id="f_s">
                                                            <td>{{$item->levelname}}</td>
                                                            <td><a href="#" class="view_grade" data-s="{{$item->id}}" data-ss="{{$item->subjid}}" data-q="{{$quarter}}" data-t="{{$item->teacherid}}" data-acad="{{$item->acadprogid}}">{{Str::limit($item->sectionname, 15, '...')}}</a></td>
                                                            <td>{{$item->subjcode}}</td>
                                                            <td>{{Str::limit($item->lastname.', '.$item->firstname, 15, '...')}}</td>
                                                            <td>
                                                                  @if($quarter == 1)
                                                                        1st
                                                                  @elseif($quarter == 2)
                                                                        2nd
                                                                  @elseif($quarter== 3)
                                                                        3rd
                                                                  @elseif($quarter == 4)
                                                                        4th
                                                                  @endif
                                                            </td>
                                                            {{-- <td class="p-1 align-middle">
                                                                  <button class="btn btn-sm btn-primary">
                                                                        Approve
                                                                  </button>
                                                            
                                                            </td> --}}
                                                            <td class="p-1 align-middle">
                                                                  <button class="btn btn-sm btn-warning add_pending"  data-gstatus="{{$item->gstatus}}"  data-q="{{$quarter}}">
                                                                        Add to pending
                                                                  </button>
                                                            </td>
                                                      </tr>
                                                @endforeach
                                          @else
                                                <tr>
                                                      <td colspan="6" class="text-center" data-q="">No submitted grades</td>
                                                </tr>
                                          @endif
                                    </tbody>
                                    
                              </table>
                        </div>
                  </div>
              </div>
              <div class="card-footer">
                  <button class="btn btn-primary" id="approve_all"  {{count($submittedGrades)==0?'hidden':''}}><i class="fas fa-share-square"></i>APPROVE ALL</button>
              </div>
          </div>
      </div>
</div>


<div class="modal fade" id="approved_grades_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header bg-primary">
                  <h4 class="modal-title">Approved Grade</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body" >
                    
                  <div class="row">
                        <div class="col-md-3 form-group">
                              <label for="">Section</label>
                              <select name="" data-id="f_a" data-filter="f_s" class="form-control select2">
                                    <option value="" >All</option>
                                    @foreach ($sections as $item)
                                          <option value="{{$item->id}}" >{{ $item->sectionname }}</option>
                                    @endforeach
                              </select>
                        </div>
                        <div class="col-md-3 form-group">
                              <label for="">Subject</label>
                              <select name="" data-id="f_a" data-filter="f_ss" class="form-control select2">
                                    <option value="" >All</option>
                                    @foreach ($subjects as $item)
                                          <option value="{{$item->subjid}}" >{{ $item->subjcode, 15}}</option>
                                    @endforeach
                              </select>
                        </div>
                        <div class="col-md-4 form-group">
                              <label for="">Teacher</label>
                              <select name="" data-id="f_a" data-filter="f_t" class="form-control select2">
                                    <option value="" >All</option>
                                    @foreach ($teachers as $item)
                                          <option value="{{$item->teacherid}}" >{{ $item->lastname.', '.$item->firstname, 15}}</option>
                                    @endforeach
                              </select>
                        </div>
                        <div class="col-md-2 form-group">
                              <label for="">Quarter</label>
                              <select name="" data-id="f_a" data-filter="f_q" class="form-control select2">
                                    <option value="" >All</option>
                                    <option value="1" >1st Quarter</option>
                                    <option value="2" >2nd Quarter</option>
                                    <option value="3" >3rd Quarter</option>
                                    <option value="4" >4th Quarter</option>
                              </select>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-3">
                              <button class="btn btn-primary filter" id="f_a"><i class="fas fa-filter"></i> FILTER</button>
                        </div>
                  </div>
                  <div class="row mt-3">
                        <div class="col-md-12 table-responsive" style="height: 400px">
                              <table class="table table-head-fixed" id="approve_table">
                                    <thead>
                                          <tr>
                                                <th width="15%">Grade Level</th>
                                                <th width="20%">Section</th>
                                                <th width="15%">Subject</th>
                                                <th width="22%">Teacher</th>
                                                <th width="10%">Quarter</th>
                                                <th width="18%"></th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          @if(count($approvedGrades) > 0)
                                                @foreach ($approvedGrades as $item)
                                                      @php
                                                            $quarter;
                                                            if($item->q1status == 2){$quarter=1;}
                                                            elseif($item->q2status == 2){$quarter=2;}
                                                            elseif($item->q3status == 2){$quarter=3;}
                                                            elseif($item->q4status == 2){$quarter=4;}
                                                      @endphp
                                                      <tr class="post" data-gstatus="{{$item->gstatus}}" data-s="{{$item->id}}" data-ss="{{$item->subjid}}" data-q="{{$quarter}}" data-t="{{$item->teacherid}}" data-id="f_a">
                                                            <td>{{$item->levelname}}</td>
                                                            <td><a href="#" class="view_grade" data-s="{{$item->id}}" data-ss="{{$item->subjid}}" data-q="{{$quarter}}" data-t="{{$item->teacherid}}" data-acad="{{$item->acadprogid}}">{{Str::limit($item->sectionname, 15, '...')}}</a></td>
                                                            <td>{{$item->subjcode}}</td>
                                                            <td>{{Str::limit($item->lastname.', '.$item->firstname, 15, '...')}}</td>
                                                            <td class="text-center">
                                                                  @if($quarter == 1)
                                                                        1st
                                                                  @elseif($quarter == 2)
                                                                        2nd
                                                                  @elseif($quarter == 3)
                                                                        3rd
                                                                  @elseif($quarter == 4)
                                                                        4th
                                                                  @endif
                                                            </td>
                                                            <td class="p-1 align-middle">
                                                                  <button class="btn btn-sm btn-warning add_pending"  data-gstatus="{{$item->gstatus}}"  data-q="{{$quarter}}">
                                                                        Add to pending
                                                                  </button>
                                                            </td>
                                                      </tr>
                                                @endforeach
                                          @else
                                                <tr>
                                                      <td colspan="6" class="text-center" data-q="">All approved grades are posted</td>
                                                </tr>
                                          @endif
                                    </tbody>
                                    
                              </table>
                        </div>
                  </div>
              </div>
              <div class="card-footer">
                  <button class="btn btn-primary" id="post_all" {{count($approvedGrades) == 0 ? 'hidden':''}}><i class="fas fa-share-square"></i> POST ALL</button>
              </div>
          </div>
      </div>
</div>

<div class="modal fade" id="posted_grades_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header bg-primary">
                  <h4 class="modal-title">Posted Grades</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body">
                  <div class="row">
                        <div class="col-md-3 form-group">
                              <label for="">Section</label>
                              <select name="" data-id="f_p" data-filter="f_s" class="form-control select2">
                                    <option value="" >All</option>
                                    @foreach ($sections as $item)
                                          <option value="{{$item->id}}" >{{ $item->sectionname }}</option>
                                    @endforeach
                              </select>
                        </div>
                        <div class="col-md-3 form-group">
                              <label for="">Subject</label>
                              <select name="" data-id="f_p" data-filter="f_ss" class="form-control select2">
                                    <option value="" >All</option>
                                    @foreach ($subjects as $item)
                                          <option value="{{$item->subjid}}" >{{ $item->subjcode, 15}}</option>
                                    @endforeach
                              </select>
                        </div>
                        <div class="col-md-4 form-group">
                              <label for="">Teacher</label>
                              <select name="" data-id="f_p" data-filter="f_t" class="form-control select2">
                                    <option value="" >All</option>
                                    @foreach ($teachers as $item)
                                          <option value="{{$item->teacherid}}" >{{ $item->lastname.', '.$item->firstname, 15}}</option>
                                    @endforeach
                              </select>
                        </div>
                        <div class="col-md-2 form-group">
                              <label for="">Quarter</label>
                              <select name="" data-id="f_p" data-filter="f_q" class="form-control select2">
                                    <option value="" >All</option>
                                    <option value="1" >1st Quarter</option>
                                    <option value="2" >2nd Quarter</option>
                                    <option value="3" >3rd Quarter</option>
                                    <option value="4" >4th Quarter</option>
                              </select>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-3">
                              <button class="btn btn-primary filter" id="f_p"><i class="fas fa-filter"></i> FILTER</button>
                        </div>
                  </div>
                  <div class="row mt-3">
                        <div class="col-md-12 table-responsive" style="height: 400px">
                              <table class="table table-head-fixed" id="posted_table" >
                                    <thead>
                                        <tr>
                                                <th width="15%">Grade Level</th>
                                                <th width="23%">Section</th>
                                                <th width="15%">Subject</th>
                                                <th width="29%">Teacher</th>
                                                <th width="18%">Quarter</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($postedGrades) > 0)
                                              @foreach ($postedGrades as $item)
                                                    @php
                                                          $quarter;
                                                          if($item->q1status == 3){$quarter=1;}
                                                          elseif($item->q2status == 3){$quarter=2;}
                                                          elseif($item->q3status == 3){$quarter=3;}
                                                          elseif($item->q4status == 3){$quarter=4;}
                                                    @endphp
                                                    <tr class="posted" data-gstatus="{{$item->gstatus}}" data-q="{{$quarter}}" data-s="{{$item->id}}" data-ss="{{$item->subjid}}" data-q="{{$quarter}}" data-t="{{$item->teacherid}}" data-id="f_p">
                                                            <td>{{$item->levelname}}</td>
                                                            <td><a href="#" class="view_grade" data-s="{{$item->id}}" data-ss="{{$item->subjid}}" data-q="{{$quarter}}" data-t="{{$item->teacherid}}" data-acad="{{$item->acadprogid}}">{{Str::limit($item->sectionname, 15, '...')}}</a></td>
                                                          <td>{{$item->subjcode}}</td>
                                                          <td>{{Str::limit($item->lastname.', '.$item->firstname, 15, '...')}}</td>
                                                          <td>
                                                                @if($quarter == 1)
                                                                      1st
                                                                @elseif($quarter == 2)
                                                                      2nd
                                                                @elseif($quarter == 3)
                                                                      3rd
                                                                @elseif($quarter == 4)
                                                                      4th
                                                                @endif
                                                          </td>
                                                    </tr>
                                              @endforeach
                                        @else
                                              <tr>
                                                    <td colspan="5" class="text-center" data-q="">No posted grades</td>
                                              </tr>
                                        @endif
                                    </tbody>
                                  
                              </table>
                        </div>
                  </div>
                    
              </div>
          </div>
      </div>
</div>

<div class="modal fade" id="pending_grades_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header bg-primary">
                  <h4 class="modal-title">Pending Grades</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body">
                  <div class="row">
                        <div class="col-md-3 form-group">
                              <label for="">Section</label>
                              <select name="" data-id="f_pp" data-filter="f_s" class="form-control select2">
                                    <option value="" >All</option>
                                    @foreach ($sections as $item)
                                          <option value="{{$item->id}}" >{{ $item->sectionname }}</option>
                                    @endforeach
                              </select>
                        </div>
                        <div class="col-md-3 form-group">
                              <label for="">Subject</label>
                              <select name="" data-id="f_pp" data-filter="f_ss" class="form-control select2">
                                    <option value="" >All</option>
                                    @foreach ($subjects as $item)
                                          <option value="{{$item->subjid}}" >{{ $item->subjcode, 15}}</option>
                                    @endforeach
                              </select>
                        </div>
                        <div class="col-md-4 form-group">
                              <label for="">Teacher</label>
                              <select name="" data-id="f_pp" data-filter="f_t" class="form-control select2">
                                    <option value="" >All</option>
                                    @foreach ($teachers as $item)
                                          <option value="{{$item->teacherid}}" >{{ $item->lastname.', '.$item->firstname, 15}}</option>
                                    @endforeach
                              </select>
                        </div>
                        <div class="col-md-2 form-group">
                              <label for="">Quarter</label>
                              <select name="" data-id="f_pp" data-filter="f_q" class="form-control select2">
                                    <option value="" >All</option>
                                    <option value="1" >1st Quarter</option>
                                    <option value="2" >2nd Quarter</option>
                                    <option value="3" >3rd Quarter</option>
                                    <option value="4" >4th Quarter</option>
                              </select>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-3">
                              <button class="btn btn-primary filter" id="f_pp"><i class="fas fa-filter"></i> FILTER</button>
                        </div>
                  </div>
                  <div class="row mt-3">
                        <div class="col-md-12 table-responsive p-0" style="height: 400px">
                              <table class="table table-head-fixed" id="pending_table">
                                    <thead>
                                    <tr>
                                          <th width="15%">Grade Level</th>
                                          <th width="23%">Section</th>
                                          <th width="15%">Subject</th>
                                          <th width="29%">Teacher</th>
                                          <th width="18%">Quarter</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($pendingGrades) > 0)
                                          @foreach ($pendingGrades as $item)
                                                @php
                                                      $quarter;
                                                      if($item->q1status == 4){$quarter=1;}
                                                      elseif($item->q2status == 4){$quarter=2;}
                                                      elseif($item->q3status == 4){$quarter=3;}
                                                      elseif($item->q4status == 4){$quarter=4;}
                                                @endphp
                                                <tr class="pending" data-gstatus="{{$item->gstatus}}" data-q="{{$quarter}}" data-s="{{$item->id}}" data-ss="{{$item->subjid}}" data-q="{{$quarter}}" data-t="{{$item->teacherid}}" data-id="f_pp">
                                                      <td>{{$item->levelname}}</td>
                                                      <td><a href="#" class="view_grade" data-s="{{$item->id}}" data-ss="{{$item->subjid}}" data-q="{{$quarter}}" data-t="{{$item->teacherid}}" data-acad="{{$item->acadprogid}}">{{Str::limit($item->sectionname, 15, '...')}}</a></td>
                                                      <td>{{$item->subjcode}}</td>
                                                      <td>{{Str::limit($item->lastname.', '.$item->firstname, 15, '...')}}</td>
                                                      <td>
                                                            @if($quarter == 1)
                                                                  1st
                                                            @elseif($quarter == 2)
                                                                  2nd
                                                            @elseif($quarter == 3)
                                                                  3rd
                                                            @elseif($quarter == 4)
                                                                  4th
                                                            @endif
                                                      </td>
                                                </tr>
                                          @endforeach
                                    @else
                                          <tr>
                                                <td colspan="5" class="text-center" data-q="">No pending grades</td>
                                          </tr>
                                    @endif
                                    </tbody>
                              </table>
                        </div>
                  </div>
              </div>
          </div>
      </div>
</div>

<div class="modal fade" id="view_grade_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
              <div class="modal-header bg-success">
                  <h4 class="modal-title">Grade Information</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body" id="grade_holder">
                 
              </div>
          </div>
      </div>
</div>

<div class="modal fade" id="proccess_count_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
              <div class="modal-header bg-success">
                  <h4 class="modal-title">Proccessing ...</h4>
              </div>
              <div class="modal-body">
                  <div class="row">
                     <div class="col-md-6"><label>Process : </label></div>
                     <div class="col-md-6"><span id="proccess_count"></span></div>
                  </div>
                  <div class="row">
                      <div class="col-md-6"><label>Success : </label></div>
                      <div class="col-md-6"><span id="save_count"></span></div>
                  </div>
                  <div class="row">
                      <div class="col-md-6"><label>Failed : </label></div>
                      <div class="col-md-6"><span id="not_saved_count"></span></div>
                  </div>
              </div>
              <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-primary" data-dismiss="modal" id="proccess_done" hidden>Done</button>
                </div>
          </div>
      </div>
</div>

<script>



      $(document).ready(function(){

            var filter_section
            var filter_quarter
            var filter_teacher
            var filter_subject
            var selectedfilter

            function filterGrades(){
                  
                  $('tr[data-id="'+selectedfilter+'"]').attr('hidden','hidden')

                  $('tr[data-id="'+selectedfilter+'"]').each(function(){

                        var filterCount = 0;
                        var validcount = 0;

                        if(filter_section != null && filter_section != ''){
                              filterCount+=1;
                              if($(this).attr('data-s') == filter_section){
                                    validcount += 1
                              }
                        }
                        if(filter_subject != null && filter_subject != ''){
                              filterCount+=1;
                              if($(this).attr('data-ss') == filter_subject){
                                    validcount += 1
                              }
                        }
                        if(filter_teacher != null && filter_teacher != ''){
                              filterCount+=1;
                              if($(this).attr('data-t') == filter_teacher){
                                    validcount += 1
                              }
                        }
                        if(filter_quarter != null && filter_quarter != ''){
                              filterCount+=1;
                              if($(this).attr('data-q') == filter_quarter){
                                    validcount += 1
                              }
                        }

                        if(filterCount == validcount){

                              $(this).removeAttr('hidden')

                        }


                         
                      
                  })


            } 

            $(document).on('click','.filter',function(){


                  selectedfilter = $(this).attr('id')
                  filter_section = $('select[data-id="'+selectedfilter+'"][data-filter="f_s"]').val()
                  filter_quarter = $('select[data-id="'+selectedfilter+'"][data-filter="f_q"]').val()
                  filter_teacher = $('select[data-id="'+selectedfilter+'"][data-filter="f_t"]').val()
                  filter_subject = $('select[data-id="'+selectedfilter+'"][data-filter="f_ss"]').val()
                  console.log('calling filter')
                  filterGrades()

              

                  // console.log(filter_section)
                  // console.log(filter_quarter)
                  // console.log(filter_teacher)
                  // console.log(filter_subject)

            })

           


            // var teachers = @json($teachers);
            // var subjects = @json($subjects);
            // var sections = @json($sections);
            // var sched = @json($sectionsubj);




            
  

      })

</script>

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
            <li class="breadcrumb-item active">Report Card</li>
        </ol>
        </div>
    </div>
    </div>
</section>
@php

      $totalitems = count($sectionsubj);
      $postedq1 = collect($sectionsubj)->where('q1status',3)->count();
      $postedq2 = collect($sectionsubj)->where('q2status',3)->count();
      $postedq3 = collect($sectionsubj)->where('q3status',3)->count();
      $postedq4 = collect($sectionsubj)->where('q4status',3)->count();


      $submitted1 = collect($sectionsubj)->where('q1status',1)->count();
      $submitted2 = collect($sectionsubj)->where('q2status',1)->count();
      $submitted3 = collect($sectionsubj)->where('q3status',1)->count();
      $submitted4 = collect($sectionsubj)->where('q4status',1)->count();


      $approved1 = collect($sectionsubj)->where('q1status',2)->count();
      $approved2 = collect($sectionsubj)->where('q2status',2)->count();
      $approved3 = collect($sectionsubj)->where('q3status',2)->count();
      $approved4 = collect($sectionsubj)->where('q4status',2)->count();


      $pending1 = collect($sectionsubj)->where('q1status',4)->count();
      $pending2 = collect($sectionsubj)->where('q2status',4)->count();
      $pending3 = collect($sectionsubj)->where('q3status',4)->count();
      $pending4 = collect($sectionsubj)->where('q4status',4)->count();

@endphp

    <section>

      <div class="row">
            <div class="col-md-3">
                  <div class="card">
                        <div class="card-body">
                              <p class="text-center">
                                    <strong>Submitted</strong>
                              </p>
                              <div class="progress-group">
                                    <span class="progress-text">Quarter 1</span>
                              <span class="float-right"><b id="q1_s">{{$submitted1}}</b>/{{$totalitems}}</span>
                              <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: {{ ( $submitted1/$totalitems ) * 100  }}%" id="q1_s_p" ></div>
                              </div>
                              </div>
                              <div class="progress-group">
                                    <span class="progress-text">Quarter 2</span>
                              <span class="float-right"><b id="q1_s">{{$submitted2}}</b>/{{$totalitems}}</span>
                              <div class="progress progress-sm">
                                    <div class="progress-bar bg-danger" style="width: {{ ( $submitted2/$totalitems ) * 100  }}%" id="q2_s_p"></div>
                              </div>
                              </div>
                              <div class="progress-group">
                              <span class="progress-text">Quarter 3</span>
                              <span class="float-right"><b id="q1_s">{{$submitted3}}</b>/{{$totalitems}}</span>
                              <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: {{ ( $submitted3/$totalitems ) * 100  }}%" id="q3_s_p"></div>
                              </div>
                              </div>
                              <div class="progress-group">
                                    <span class="progress-text">Quarter 4</span>
                                    <span class="float-right"><b id="q1_s">{{$submitted4}}</b>/{{$totalitems}}</span>
                              <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning" style="width: {{ ( $submitted4/$totalitems ) * 100  }}%" id="q4_s_p"></div>
                              </div>
                              </div>
                              <button class="btn btn-success btn-block mt-4 btn-sm view_grade_status_button" data-id="1"><i class="nav-icon fas fa-layer-group"></i> View Submitted Grades</button>
                        </div>
                  </div>
            </div>
            <div class="col-md-3">
                  <div class="card">
                        <div class="card-body">
                              <p class="text-center">
                                    <strong>Approved</strong>
                              </p>
                              <div class="progress-group">
                                    <span class="progress-text">Quarter 1</span>
                              <span class="float-right"><b id="q1_a">{{$approved1}}</b>/{{$totalitems}}</span>
                              <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: {{ ( $approved1/$totalitems ) * 100  }}%" id="q1_a_p"></div>
                              </div>
                              </div>
                              <div class="progress-group">
                                    <span class="progress-text">Quarter 2</span>
                              <span class="float-right"><b id="q2_a">{{$approved2}}</b>/{{$totalitems}}</span>
                              <div class="progress progress-sm">
                                    <div class="progress-bar bg-danger" style="width: {{ ( $approved2/$totalitems ) * 100  }}%" id="q2_a_p"></div>
                              </div>
                              </div>
                              <div class="progress-group">
                              <span class="progress-text">Quarter 3</span>
                              <span class="float-right"><b id="q3_a">{{$approved3}}</b>/{{$totalitems}}</span>
                              <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: {{ ( $approved3/$totalitems ) * 100  }}%" id="q3_a_p"></div>
                              </div>
                              </div>
                              <div class="progress-group">
                                    <span class="progress-text">Quarter 4</span>
                                    <span class="float-right"><b id="q4_a">{{$approved4}}</b>/{{$totalitems}}</span>
                              <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning" style="width: {{ ( $approved4/$totalitems ) * 100  }}%" id="q4_a_p"></div>
                              </div>
                              </div>
                              <button class="btn btn-primary btn-block mt-4 btn-sm view_grade_status_button" data-id="2"><i class="nav-icon fas fa-layer-group"></i> View Approved Grades</button>
                        </div>
                  </div>
            </div>
            <div class="col-md-3">
                  <div class="card">
                        <div class="card-body">
                              <p class="text-center">
                                    <strong>Posted</strong>
                              </p>
                              <div class="progress-group">
                                    <span class="progress-text">Quarter 1</span>
                                    <span class="float-right" "><b  id="q1_p">{{$postedq1}}</b>/{{$totalitems}}</span>
                                    <div class="progress progress-sm">
                                          <div class="progress-bar bg-primary" style="width: {{ ( $postedq1/$totalitems ) * 100  }}%" id="q1_p_p"></div>
                                    </div>
                              </div>
                              <div class="progress-group">
                                    <span class="progress-text">Quarter 2</span>
                                    <span class="float-right" ><b  id="q3_p">{{$postedq2}}</b>/{{$totalitems}}</span>
                                    <div class="progress progress-sm">
                                          <div class="progress-bar bg-danger" style="width: {{ ( $postedq2/$totalitems ) * 100  }}%" id="q2_p_p"></div>
                                    </div>
                              </div>
                              <div class="progress-group">
                              <span class="progress-text">Quarter 3</span>
                              <span class="float-right" ><b  id="q3_p">{{$postedq3}}</b>/{{$totalitems}}</span>
                              <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: {{ ( $postedq3/$totalitems ) * 100  }}%" id="q3_p_p"></div>
                              </div>
                              </div>
                              <div class="progress-group">
                                    <span class="progress-text">Quarter 4</span>
                                    <span class="float-right"><b  id="q4_p">{{$postedq4}}</b>/{{$totalitems}}</span>
                              <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning" style="width: {{ ( $postedq4/$totalitems ) * 100  }}%" id="q4_p_p"></div>
                              </div>
                              </div>
                              <button class="btn btn-secondary btn-block mt-4 btn-sm view_grade_status_button" data-id="3"><i class="nav-icon fas fa-layer-group"></i> View Posted Grades</button>
                        </div>
                  </div>
            </div>
            <div class="col-md-3">
                  <div class="card">
                        <div class="card-body">
                              <p class="text-center">
                                    <strong>Pending</strong>
                              </p>
                              <div class="progress-group">
                                    <span class="progress-text">Quarter 1</span>
                              <span class="float-right"><b id="q1_pp">{{$pending1}}</b>/{{$totalitems}}</span>
                              <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: {{ ( $pending1/$totalitems ) * 100  }}%" id="q1_pp_p"></div>
                              </div>
                              </div>
                              <div class="progress-group">
                                    <span class="progress-text">Quarter 2</span>
                              <span class="float-right"><b id="q2_pp">{{$pending2}}</b>/{{$totalitems}}</span>
                              <div class="progress progress-sm">
                                    <div class="progress-bar bg-danger" style="width: {{ ( $pending2/$totalitems ) * 100  }}%" id="q2_pp_p"></div>
                              </div>
                              </div>
                              <div class="progress-group">
                              <span class="progress-text">Quarter 3</span>
                              <span class="float-right"><b id="q3_pp">{{$pending3}}</b>/{{$totalitems}}</span>
                              <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: {{ ( $pending3/$totalitems ) * 100  }}%" id="q3_pp_p"></div>
                              </div>
                              </div>
                              <div class="progress-group">
                                    <span class="progress-text">Quarter 4</span>
                                    <span class="float-right"><b id="q4_pp">{{$pending4}}</b>/{{$totalitems}}</span>
                              <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning" style="width: {{ ( $pending4/$totalitems ) * 100  }}%" id="q4_pp_p"></div>
                              </div>
                              </div>
                              <button class="btn btn-warning btn-block mt-4 btn-sm view_grade_status_button" data-id="4"><i class="nav-icon fas fa-layer-group"></i> View Pending Grades</button>
                        </div>
                  </div>
            </div>
      </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header bg-primary p-1">
                        <h4 class="card-title"></h4>
                    </div>
                    <div class="card-body">
                       
                        <div class="row">
                              <div class="col-md-12">
                                    <h4>FILTER</h4>
                              </div>
                             
                              <div class="col-md-4">
                                    <div class="form-group">
                                          <label for="">Section</label>
                                          <select name="" id="sect" class="form-control select2">
                                                <option value="">All</option>
                                                @foreach (collect($sectionsubj)->unique('sectionname') as $item)
                                                      <option value="{{$item->id}}">{{$item->sectionname}}</option>
                                                @endforeach
                                          </select>
                                    </div>
                              </div>
                              <div class="col-md-4">
                                    <div class="form-group">
                                          <label for="">Generated Status</label>
                                          <select name="" id="genstat" class="form-control select2">
                                                <option value="">All</option>
                                                <option value="1">Not Generated</option>
                                                <option value="2">Generated</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="col-md-4">
                                    <div class="form-group">
                                          <label for="">Grade Status</label>
                                          <select name="" id="gradestat" class="form-control select2">
                                                <option value="">All</option>
                                                <option value="1">Submitted</option>
                                                <option value="2">Approved</option>
                                                <option value="3">Posted</option>
                                                <option value="4">Pending</option>
                                          </select>
                                    </div>
                              </div>
                        </div>
                       <div class="row">
                              <div class="col-md-3">
                                    <button class="btn btn-primary" id="filter"><i class="fas fa-filter"></i> FILTER</button>
                              </div>
                        </div>
                        <div class="row mt-4">
                              <div class="col-md-12">
                                    <table class="table table-bordered table-head-fixed" id="grade_status">
                                          <thead>
                                                <tr>
                                                      <th width="10%">Section</th>
                                                      <th width="13%">Section</th>
                                                      <th width="15%">Subject</th>
                                                      <th width="18%">Teacher</th>
                                                      <th width="11%">Q1</th>
                                                      <th width="11%">Q2</th>
                                                      <th width="11%">Q3</th>
                                                      <th width="11%">Q4</th>
                                                </tr>
                                          </thead>
                                          <tbody>
                                           
                                          </tbody>
                                    </table>
                              </div>
                        </div>
                        <div class="" id="data-container">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-md-12">
                  <div class="card">
                        <div class="card-header p-1 bg-secondary">
                            
                        </div>
                        <div class="card-body">
                              <label for="">Grade Logs</label>
                              <ul>      
                                    @foreach (DB::table('grading_system_gradestatus_logs')
                                                ->join('grading_sytem_gradestatus',function($join){
                                                      $join->on('grading_system_gradestatus_logs.headerid','=','grading_sytem_gradestatus.id');
                                                      $join->where('grading_sytem_gradestatus.deleted',0);
                                                })
                                                ->join('sections',function($join){
                                                      $join->on('grading_sytem_gradestatus.sectionid','=','sections.id');
                                                      $join->where('sections.deleted',0);
                                                })
                                                ->join('gradelevel',function($join){
                                                      $join->on('grading_sytem_gradestatus.sectionid','=','sections.id');
                                                      $join->where('sections.deleted',0);
                                                })
                                                ->get(); as $item)
                                          <li>  
                                                {{$item->sectionname}} - 
                                                @if($item->status == 1)
                                                      <span class="badge badge-success">Submitted</span>
                                                @elseif($item->status == 2)
                                                      <span class="badge badge-primary">Approved</span>
                                                @elseif($item->status == 3)
                                                      <span class="badge badge-secondary">Posted</span>
                                                @elseif($item->status == 4)
                                                      <span class="badge badge-warning">Added Pending</span>
                                                @endif
                                          </li>
                                    @endforeach
                              </ul>
                        </div>
                  </div>
                 
                  
            </div>
        </div> --}}
        <div class="row">
              <div class="col-md-12">
                  <div class="card">
                        <div class="card-header p-1 bg-secondary">
                        </div>
                        <div class="card-body">
                              <h4>Updates:</h4>
                              <hr>
                              <div class="row">
                                    <div class="col-md-6">
                                          <ol style="list-style: circle;">
                                                <li><b>+</b> Approve all grades at the same time</li>
                                                <li><b>+</b> Post all grades at the same time</li>
                                                <li><b>+</b> Submitted grades summary</li>
                                                <li><b>+</b> Approved grades summary</li>
                                                <li><b>+</b> Posted grades summary</li>
                                                <li><b>+</b> Pending grades summary</li>
                                                <li><b>+</b> Submitted grades summary filter</li>
                                                <li><b>+</b> Approved grades summary filter</li>
                                                <li><b>+</b> Posted grades summary filter</li>
                                          </ol>
                                    </div>
                                    <div class="col-md-6">
                                          <ol style="list-style: circle;">
                                                <li><b>+</b> Pending grades summary filter</li>
                                                <li><b>+</b> Add grades to pending</li>
                                                <li><b>+</b> Able to update more than 10 proccess for posting</li>
                                                <li><b>+</b> Able to update more than 10 proccess for aproving</li>
                                                <li><b>+</b> Proccess alert</li>
                                                <li><b>+</b> Grade proccess logs</li>
                                                <li><b>+</b> Display submitted grades detail</li>
                                                <li><b>+</b> View grade logs</li>  
                                                <li><b>+</b> Filter displayed grades by principal</li> 
                                          </ol>
                                    </div>
                              </div>
                              <h4>For update:</h4>
                              <hr>
                              <ul>
                                    
                                    <li><b>+</b> View grade notification</li>
                                   
                              </ul>
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

      <script>


           
      </script>

    <script>
            $(document).ready(function(){

                  var dataArray = []

                  @foreach ($sectionsubj as $item)

                        var itemizeddata = {
                              'sectionname':'{{$item->sectionname}}',
                              'subjcode':'{{$item->subjcode}}',
                              'name':'{{$item->lastname}}'+', '+'{{$item->firstname}}',
                              'q1':null,
                              'q2':null,
                              'q3':null,
                              'q4':null,
                              'gstatus':'{{$item->gstatus}}',
                              'q1stat':'{{$item->q1status}}',
                              'q2stat':'{{$item->q2status}}',
                              'q3stat':'{{$item->q3status}}',
                              'q4stat':'{{$item->q4status}}',
                              'sectid':'{{$item->id}}',
                              'levelname':'{{$item->levelname}}'
                            
                        }
                        
                        dataArray.push(itemizeddata)

                  @endforeach

                  loaddatatable(dataArray)

                  function loaddatatable(data){
                        
                        $("#grade_status").DataTable({
                              destroy: true,
                              data:data,
                              "columns": [
                                          { "data": "levelname" },
                                          { "data": "sectionname" },
                                          { "data": "subjcode" },
                                          { "data": "name" },
                                          { "data": "q1stat" },
                                          { "data": "q2stat" },
                                          { "data": "q3stat" },
                                          { "data": "q4stat" },
                                    ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'width':'10%',
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                      $(td).attr('data-gstatus', rowData.gstatus);
                                                      $(td).attr('data-q1stat', rowData.q1stat);
                                                      $(td).attr('data-q2stat', rowData.q2stat);
                                                      $(td).attr('data-q3stat', rowData.q3stat);
                                                      $(td).attr('data-q4stat', rowData.q4stat);

                                                      $(td).text(cellData.slice(0, 10) + ( cellData.length > 20 ? "..." : "" ))
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'width':'17%',
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                      $(td).attr('data-gstatus', rowData.gstatus);
                                                      $(td).attr('data-q1stat', rowData.q1stat);
                                                      $(td).attr('data-q2stat', rowData.q2stat);
                                                      $(td).attr('data-q3stat', rowData.q3stat);
                                                      $(td).attr('data-q4stat', rowData.q4stat);

                                                      $(td).text(cellData.slice(0, 10) + ( cellData.length > 20 ? "..." : "" ))
                                          }
                                    },{
                                          'targets': 2,
                                          'width':'15%',
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).text(cellData.slice(0, 10) + ( cellData.length > 10 ? "..." : "" ))
                                          }
                                    },

                                    {
                                          'targets': 3,
                                          'width':'18%',
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).text(cellData.slice(0, 10) + ( cellData.length > 10 ? "..." : "" ))
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'width':'10%',
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.q1stat == 1){
                                                            $(td)[0].innerHTML = '<span class="badge badge-success btn-block">Submitted</span>'
                                                      }
                                                      else if(rowData.q1stat == 2){
                                                            $(td)[0].innerHTML = ' <span class="badge badge-primary btn-block" >Approved</span>'
                                                      }
                                                      else if(rowData.q1stat == 3){
                                                            $(td)[0].innerHTML = ' <span class="badge badge-secondary btn-block">Posted</span>'
                                                      }
                                                      else if(rowData.q1stat == 4){
                                                            $(td)[0].innerHTML = '<span class="badge badge-warning btn-block">Pending</span>'
                                                      }
                                          }
                                    },
                                    {
                                          'targets': 5,
                                          'width':'10%',
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.q2stat == 1){
                                                            $(td)[0].innerHTML = '<span class="badge badge-success btn-block">Submitted</span>'
                                                      }
                                                      else if(rowData.q2stat == 2){
                                                            $(td)[0].innerHTML = ' <span class="badge badge-primary btn-block" >Approved</span>'
                                                      }
                                                      else if(rowData.q2stat == 3){
                                                            $(td)[0].innerHTML = ' <span class="badge badge-secondary btn-block">Posted</span>'
                                                      }
                                                      else if(rowData.q2stat == 4){
                                                            $(td)[0].innerHTML = '<span class="badge badge-warning btn-block">Pending</span>'
                                                      }
                                          }
                                    },
                                    {
                                          'targets': 6,
                                          'width':'10%',
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.q3stat == 1){
                                                            $(td)[0].innerHTML = '<span class="badge badge-success btn-block">Submitted</span>'
                                                      }
                                                      else if(rowData.q3stat == 2){
                                                            $(td)[0].innerHTML = ' <span class="badge badge-primary btn-block" >Approved</span>'
                                                      }
                                                      else if(rowData.q3stat == 3){
                                                            $(td)[0].innerHTML = ' <span class="badge badge-secondary btn-block">Posted</span>'
                                                      }
                                                      else if(rowData.q3stat == 4){
                                                            $(td)[0].innerHTML = '<span class="badge badge-warning btn-block">Pending</span>'
                                                      }
                                          }
                                    },
                                    {
                                          'targets': 7,
                                          'width':'10%',
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.q4stat == 1){
                                                            $(td)[0].innerHTML = '<span class="badge badge-success btn-block">Submitted</span>'
                                                      }
                                                      else if(rowData.q4stat == 2){
                                                            $(td)[0].innerHTML = ' <span class="badge badge-primary btn-block" >Approved</span>'
                                                      }
                                                      else if(rowData.q4stat == 3){
                                                            $(td)[0].innerHTML = ' <span class="badge badge-secondary btn-block">Posted</span>'
                                                      }
                                                      else if(rowData.q4stat == 4){
                                                            $(td)[0].innerHTML = '<span class="badge badge-warning btn-block">Pending</span>'
                                                      }
                                          }
                                    },
                              ]
                        });

                  }
               
                  $('.select2').select2()

                  var firstIndex = 0;
                  var lastIndex = 0;
                  var checkedGrades =  0;
                  var saveCount = 0;
                  var unSavedCount = 0;
                  var proccessCount =  0;
                  var checkedCount = 0;

                 

                  function isEmpty(value){
                        return (value == null || value.length === 0);
                  }


                  $(document).on('click','#filter',function(){

                        var genstat = $('#genstat').val()
                        var sectionid = $('#sect').val()
                        var gradestat = $('#gradestat').val()

                        gradestat

                        $('.item').removeAttr('hidden')
                        var newdataArray = []

                        $.each(dataArray,function(a,b){

                              var valid = false;
                              var trueCount = 0;
                              var filterCount = 0;

                              if(genstat != null && genstat != ''){
                                    filterCount += 1
                                    if( !isEmpty(b.gstatus)  && genstat == 2){
                                          valid = true
                                          trueCount += 1;
                                    }
                                    else if( isEmpty(b.gstatus) && genstat == 1){
                                          valid = true
                                          trueCount += 1;
                                    }    
                                    else{
                                          valid = false
                                    }
                              }
                              
                              if(sectionid != null && sectionid != ''){
                                    filterCount += 1
                                    if( b.sectid == sectionid ){
                                          valid = true
                                          trueCount += 1;
                                    }else{
                                          valid = false
                                    }
                              }

                              if(gradestat != null && gradestat != ''){
                                    filterCount += 1
                                    if( b.q1stat == gradestat ){
                                          valid = true
                                          trueCount += 1;
                                    }
                                    else if(b.q2stat == gradestat ){
                                          valid = true
                                          trueCount += 1;
                                    }
                                    else if( b.q3stat == gradestat ){
                                          valid = true
                                          trueCount += 1;
                                    }
                                    else if( b.q4stat == gradestat ){
                                          valid = true
                                          trueCount += 1;
                                    }
                                    else{
                                          valid = false
                                    }
                              }

                              if( trueCount == filterCount){
                      
                                    newdataArray.push(b)

                              }

                        })

                        loaddatatable(newdataArray)

                  })

                  
                  var toApproved = [];
                  var totalItems = '{{$totalitems}}'
                  var addedPending


                  function pendingGrades(tabltr){

                        $.ajax({
                              type:'GET',
                              url: '/reportcard/grade/status/pending',
                              data:{
                                    'gstatusid': gstatusid,
                                    'quarter': quarter
                              },
                              success:function(data) {

                                    if(data == 1){
                                    
                                          $(addedPending)[0].deleteCell(4)
                                          $('#pending_table').append(addedPending)
                                          $(addedPending).attr('data-id','f_pp')

                                          $(addedPending).removeClass('approve')
                                          $(addedPending).removeClass('post')
                                          $(addedPending).addClass('pending')

                                          var result  = dataArray.findIndex(element => element.gstatus == gstatusid )

                                          if(quarter == 1){
                                                dataArray[result].q1stat = 3
                                          }else if(quarter == 2){
                                                dataArray[result].q2stat = 3 
                                          }
                                          else if(quarter == 3){
                                                dataArray[result].q3stat = 3 
                                          }
                                          else if(quarter == 4){
                                                dataArray[result].q4stat = 3 
                                          }

                                          updatePendingInfo()
                                          updateSubmittedInfo()
                                          updateApprovedInfo()
                                          loaddatatable(dataArray)

                                          Swal.fire({
                                                type: 'success',
                                                title: 'Added to Pending!',
                                          });
                                    }
                              
                              }
                        })

                  }


                  function postGrades(){

                        var counter = 0;

                        $('.post').slice(firstIndex,lastIndex).each(function(){

                              var gstatusid = $(this).attr('data-gstatus')
                              var quarter = $(this).attr('data-q')
                              var datatd = $(this)

                              $.ajax({
                                    type:'GET',
                                    url: '/reportcard/grade/status/post',
                                    data:{
                                          'gstatusid': gstatusid,
                                          'quarter': quarter
                                    },
                                    success:function(data) {
                                          
                                          if(data == 1){

                                                saveCount += 1
                                                $('#save_count').text(saveCount)
                                                toApproved.push(datatd)
                                                $(datatd).attr('hidden','hidden')

                                          }
                                          else if(data == 0){

                                                unSavedCount += 1
                                                $('#not_saved_count').text(unSavedCount)

                                          }

                                          counter += 1;
                                          proccessCount += 1;

                                          if(counter == 9 && checkedGrades != 0){

                                                firstIndex  += 10;
                                                lastIndex += 10;
                                                checkedGrades -= 1
                                                postGrades()
                                          }

                                          if(  checkedCount  == proccessCount){

                                                $('#proccess_count_modal .modal-title').text('Complete')
                                                $('#proccess_done').removeAttr('hidden')
                                                $('.checked_grade').removeClass('checked_grade')
                                                $('#generate_status').removeAttr('disabled')

                                                $.each(toApproved, function(a,b){

                                                      $(b).remove()
                                                      var gstatusid = $(b).attr('data-gstatus')
                                                      var result  = dataArray.findIndex(element => element.gstatus == gstatusid )

                                                      if(quarter == 1){
                                                            dataArray[result].q1stat = 3
                                                      }else if(quarter == 2){
                                                            dataArray[result].q2stat = 3 
                                                      }
                                                      else if(quarter == 3){
                                                            dataArray[result].q3stat = 3 
                                                      }
                                                      else if(quarter == 4){
                                                            dataArray[result].q4stat = 3 
                                                      }

                                                      $(b).removeAttr('hidden')
                                                      $(b)[0].deleteCell(4)
                                                      $('#posted_table').append(b)
                                                      $(b).removeClass('post')
                                                      $(b).addClass('posted')
                                                      $(b).attr('data-id','f_p')
                                                     
                                                    

                                                })

                                                updateApprovedInfo()
                                                updatePostedInfo()
                                                loaddatatable(dataArray)

                                                Swal.fire({
                                                      type: 'success',
                                                      title: 'Posted Successfully!',
                                                });
                                             
                                          }

                                          $('#proccess_count').text(proccessCount+' / '+checkedCount)
                                    
                                    }
                              })
                                    

                        })

                  }


                  function approveGrades(){

                        var counter = 0;
                       
                        $('.approve').slice(firstIndex,lastIndex).each(function(){

                              var gstatusid = $(this).attr('data-gstatus')
                              var quarter = $(this).attr('data-q')
                              var datatd = $(this)
                              
                              $.ajax({
                                    type:'GET',
                                    url: '/reportcard/grade/status/approve',
                                    data:{
                                          'gstatusid': gstatusid,
                                          'quarter': quarter
                                    },
                                    success:function(data) {
                                          
                                          if(data == 1){

                                                saveCount += 1
                                                $('#save_count').text(saveCount)
                                                toApproved.push(datatd)
                                                $(datatd).attr('hidden','hidden')
                                             

                                          }
                                          else if(data == 0){

                                                unSavedCount += 1
                                                $('#not_saved_count').text(unSavedCount)

                                          }

                                          counter += 1;
                                          proccessCount += 1;

                                          if(counter == 9 && checkedGrades != 0){

                                                firstIndex  += 10;
                                                lastIndex += 10;
                                                checkedGrades -= 1
                                                approveGrades()
                                          }

                                         

                                          if(  checkedCount  == proccessCount){

                                                $('#proccess_count_modal .modal-title').text('Complete')
                                                $('#proccess_done').removeAttr('hidden')
                                                $('.checked_grade').removeClass('checked_grade')
                                                $('#generate_status').removeAttr('disabled')
                                        
                                                $.each(toApproved, function(a,b){
                                                  
                                                      $(b).remove()
                                                      var gstatusid = $(b).attr('data-gstatus')
                                                      var result  = dataArray.findIndex(element => element.gstatus == gstatusid )
                                                      

                                                      if(quarter == 1){
                                                            dataArray[result].q1stat = 2 
                                                      }else if(quarter == 2){
                                                            dataArray[result].q2stat = 2 
                                                      }
                                                      else if(quarter == 3){
                                                            dataArray[result].q3stat = 2 
                                                      }
                                                      else if(quarter == 4){
                                                            dataArray[result].q4stat = 2 
                                                      }
                                                      
                                                      $(b).removeAttr('hidden')
                                                      $('#approve_table').append(b)
                                                      $(b).removeClass('approve')
                                                      $(b).addClass('post')
                                                      $(b).attr('data-id','f_a')
                                                   
                                                     
                                                })

                                                updateSubmittedInfo()
                                                updateApprovedInfo()
                                                loaddatatable(dataArray)

                                                Swal.fire({
                                                      type: 'success',
                                                      title: 'Approved Successfully!',
                                                });

                                          }

                                          $('#proccess_count').text(proccessCount+' / '+checkedCount)
                                    
                                    }
                              })
                                    

                        })

                  }

                  function updateSubmittedInfo(){

                        $q1count = $('.approve[data-q="1"]').length
                        $q2count = $('.approve[data-q="2"]').length
                        $q3count = $('.approve[data-q="3"]').length
                        $q4count = $('.approve[data-q="4"]').length

                        $('#q1_s').text($q1count)
                        $('#q2_s').text($q2count)
                        $('#q3_s').text($q3count)
                        $('#q4_s').text($q4count)

                        $('#q1_s_p').css('width', ( parseInt($q1count) / parseInt(totalItems) ) * 100 )
                        $('#q2_s_p').css('width', ( parseInt($q2count) / parseInt(totalItems) ) * 100 )
                        $('#q3_s_p').css('width', ( parseInt($q3count) / parseInt(totalItems) ) * 100 )
                        $('#q4_s_p').css('width', ( parseInt($q4count) / parseInt(totalItems) ) * 100 )
                      
                        if( $('.approve').length == 0){
                              $('#approve_all').attr('hidden','hidden')
                              $('#submitted_table tbody').empty()
                              $('#submitted_table tbody').append('<td colspan="6" class="text-center">No submitted grades</td>')
                        }
                        else{
                              $('#submitted_all').removeAttr('hidden')
                        }

                  }

                  function updateApprovedInfo(){

                        $q1count = $('.post[data-q="1"]').length
                        $q2count = $('.post[data-q="2"]').length
                        $q3count = $('.post[data-q="3"]').length
                        $q4count = $('.post[data-q="4"]').length

                        $('#q1_a').text($q1count)
                        $('#q2_a').text($q2count)
                        $('#q3_a').text($q3count)
                        $('#q4_a').text($q4count)

                        $('#q1_a_p').css('width', ( parseInt($q1count) / parseInt(totalItems) ) * 100 )
                        $('#q2_a_p').css('width', ( parseInt($q2count) / parseInt(totalItems) ) * 100 )
                        $('#q3_a_p').css('width', ( parseInt($q3count) / parseInt(totalItems) ) * 100 )
                        $('#q4_a_p').css('width', ( parseInt($q4count) / parseInt(totalItems) ) * 100 )

                        if( $('.post').length == 0){
                              $('#post_all').attr('hidden','hidden')
                              $('#approve_table tbody').empty()
                              $('#approve_table tbody').append('<td colspan="6" class="text-center">All approved grades are posted</td>')
                        }
                        else{

                              $('#approve_table tbody td[data-q=""]').remove()
                              $('#post_all').removeAttr('hidden')
                        }


                  }

                  
                  function updatePostedInfo(){

                        $q1count = $('.posted[data-q="1"]').length
                        $q2count = $('.posted[data-q="2"]').length
                        $q3count = $('.posted[data-q="3"]').length
                        $q4count = $('.posted[data-q="4"]').length

                        $('#q1_p').text($q1count)
                        $('#q2_p').text($q2count)
                        $('#q3_p').text($q3count)
                        $('#q4_p').text($q4count)

                        $('#q1_p_p').css('width', ( parseInt($q1count) / parseInt(totalItems) ) * 100 )
                        $('#q2_p_p').css('width', ( parseInt($q2count) / parseInt(totalItems) ) * 100 )
                        $('#q3_p_p').css('width', ( parseInt($q3count) / parseInt(totalItems) ) * 100 )
                        $('#q4_p_p').css('width', ( parseInt($q4count) / parseInt(totalItems) ) * 100 )

                        if( $('.posted').length == 0){
                              $('#posted_table tbody').empty()
                              $('#posted_table tbody').append('<td colspan="5" class="text-center">No approved grades</td>')
                        }
                        else{
                              $('#posted_table tbody td[data-q=""]').remove()
                        }


                  }


                  function updatePendingInfo(){

                        $q1count = $('.pending[data-q="1"]').length
                        $q2count = $('.pending[data-q="2"]').length
                        $q3count = $('.pending[data-q="3"]').length
                        $q4count = $('.pending[data-q="4"]').length

                        $('#q1_pp').text($q1count)
                        $('#q2_pp').text($q2count)
                        $('#q3_pp').text($q3count)
                        $('#q4_pp').text($q4count)

                        $('#q1_pp_p').css('width', ( parseInt($q1count) / parseInt(totalItems) ) * 100 )
                        $('#q2_pp_p').css('width', ( parseInt($q2count) / parseInt(totalItems) ) * 100 )
                        $('#q3_pp_p').css('width', ( parseInt($q3count) / parseInt(totalItems) ) * 100 )
                        $('#q4_pp_p').css('width', ( parseInt($q4count) / parseInt(totalItems) ) * 100 )

                        if( $('.pending').length == 0){
                              $('#pending_table tbody').empty()
                              $('#pending_table tbody').append('<td colspan="5" class="text-center">No pending grades</td>')
                        }
                        else{
                              $('#pending_table tbody td[data-q=""]').remove()
                        }


                  }



                  $(document).on('click','#approve_all',function(){

                        toApproved = [];
                        firstIndex = 0;
                        lastIndex = 10;
                        checkedGrades =  parseInt( $('.approve').length / 10 )  + 1;
                        saveCount = 0;
                        unSavedCount = 0;
                        proccessCount =  0;
                        checkedCount =  $('.approve').length;

                        console.log(checkedGrades)

                        if(checkedCount == 0){

                              Swal.fire({
                                    type: 'info',
                                    title: 'All submitted grades are approved!',
                              });

                        }else{

                              $('#proccess_count_modal .modal-title').text('Processing ...')
                              $('#proccess_done').attr('hidden','hidden')
                              $('#proccess_count_modal').modal()
                              $('#save_count').text(saveCount)
                              $('#not_saved_count').text(unSavedCount)
                              $('#proccess_count').text(proccessCount)
                              $('#generate_status').attr('disabled','disabled')

                              approveGrades()

                        }

                  })
                  
                  $(document).on('click','#post_all',function(){

                        toApproved = [];
                        firstIndex = 0;
                        lastIndex = 10;
                        checkedGrades =  parseInt( $('.post').length / 10 )  + 1;
                        saveCount = 0;
                        unSavedCount = 0;
                        proccessCount =  0;
                        checkedCount =  $('.post').length;

                        if(checkedCount == 0){

                              Swal.fire({
                                    type: 'info',
                                    title: 'All approved grades are posted!',
                              });

                        }else{
                              
                              $('#proccess_count_modal .modal-title').text('Processing ...')
                              $('#proccess_done').attr('hidden','hidden')
                              $('#proccess_count_modal').modal()
                              $('#save_count').text(saveCount)
                              $('#not_saved_count').text(unSavedCount)
                              $('#proccess_count').text(proccessCount)
                              $('#generate_status').attr('disabled','disabled')
                              postGrades()
                        
                        }

                  })

                  $(document).on('click','.add_pending',function(){

                        gstatusid = $(this).attr('data-gstatus')
                        quarter = $(this).attr('data-q')
                        addedPending = $('tr[data-gstatus="'+gstatusid+'"][data-q="'+quarter+'"]')
                        pendingGrades()

                  })

                  $(document).on('click','.view_grade_status_button',function(){
                        
                        var title;
                        
                        $('#view_grade_status_modal').modal()

                        if($(this).attr('data-id') == 1){

                              $('#submitted_grades_modal').modal()
                        }
                        else if($(this).attr('data-id') == 2){

                              $('#approved_grades_modal').modal()

                        }
                        else if($(this).attr('data-id') == 3){

                              $('#posted_grades_modal').modal()

                        }
                        else if($(this).attr('data-id') == 4){

                              $('#pending_grades_modal').modal()

                        }



                       
                  })

                  var view_subj
                  var view_sect
                  var view_quarter
                  var view_teacher
                  var t_s_gsid
                  var t_acad
                  var view_url
                  var selectedStudent

                  // function view_grade(){

                  //     $('#view_grade_modal').modal();

                     
                  // }


                  $(document).on('click','.view_grade',function(){

                        view_subj = $(this).attr('data-ss')
                        view_sect = $(this).attr('data-s')
                        view_teacher = $(this).attr('data-teacherid')
                        view_quarter = $(this).attr('data-q')
                        t_acad = $(this).attr('data-acad')

                        if(t_acad == 3){

                              view_url = '/reportcard/grades/gradeschool';

                        }else if(t_acad == 4){

                              view_url = '/reportcard/grades/highschool';

                        }else if(t_acad == 5){

                              view_url = '/reportcard/grades/seniorhigh';

                        }
                        $('#view_grade_modal').modal();
                        loadGradesDetail()

                  })

               
                  function loadGradesDetail(){

                        $.ajax({
                              type:'GET',
                              url:view_url,
                              data:{
                                    evaluate:'evaluate',
                                    studid: selectedStudent,
                                    section:view_sect,
                                    subject:view_subj,
                                    // evalaction:evalaction,
                                    quarter:view_quarter,
                                    gsid:t_s_gsid

                              },
                              success:function(data) {
                                    
                                    if(data[0].status == 0){

                                          Swal.fire({
                                                type: 'info',
                                                text:data[0].data,
                                          
                                          });

                                    
                                    }
                                    else if(data == 0){

                                          Swal.fire({
                                                      type: 'error',
                                                      title: 'No grades detail!',
                                                      text:'Student grades detail is not yet generated',
                                                      showConfirmButton: false,
                                                      timer: 1500
                                                });

                                          $('#generate_grade_holder').removeAttr('hidden')
                                          $('#pstable_holder').empty()
                                          $('#save_grades_gs').attr('hidden','hidden')


                                    }
                                    else{

                                          $('#grade_holder').empty()
                                          $('#grade_holder').append(data)
                                          $('#save_grades_gs').removeAttr('hidden')
                                          t_s_gsid = $('#grade_info_gs').attr('data-id')

                                    }

                              }
                        })

                  }


            })
        
    </script>

@endsection

