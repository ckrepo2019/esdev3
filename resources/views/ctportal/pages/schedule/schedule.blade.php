
@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      

      <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
      </style>

@endsection



@section('content')

@php
   $sy = DB::table('sy')->orderBy('sydesc','desc')->get(); 
   $semester = DB::table('semester')->get(); 
@endphp

<section class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1>Class Schedule</h1>
              </div>
              <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="/home">Home</a></li>
                  <li class="breadcrumb-item active">Class Schedule</li>
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
                                                {{-- <span class="info-box-icon bg-primary"><i class="fas fa-calendar-check"></i></span> --}}
                                                <div class="info-box-content">
                                                      <div class="row">
                                                            <div class="col-md-6"></div>
                                                            <div class="col-md-2">
                                                                  <label for="">School Year</label>
                                                                  <select class="form-control form-control-sm select2" id="syid">
                                                                        @foreach ($sy as $item)
                                                                              @if($item->isactive == 1)
                                                                                    <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                                              @else
                                                                                    <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                                              @endif
                                                                        @endforeach
                                                                  </select>
                                                            </div>
                                                            <div class="col-md-2" >
                                                                  <label for="">Semester</label>
                                                                  <select class="form-control form-control-sm select2" id="semester">
                                                                        @foreach ($semester as $item)
                                                                              <option {{$item->isactive == 1 ? 'selected' : ''}} value="{{$item->id}}">{{$item->semester}}</option>
                                                                        @endforeach
                                                                  </select>
                                                            </div>
                                                            <div class="col-md-2" hidden>
                                                                  <label for="">Term</label>
                                                                  <select class="form-control form-control-sm select2" id="term">
                                                                        <option value="">All</option>
                                                                        <option value="Whole Sem">Whole Sem</option>
                                                                        <option value="1st Term">1st Term</option>
                                                                        <option value="2nd Term">2nd Term</option>
                                                                  </select>
                                                            </div>
                                                            <div class="col-md-2" style="padding-top: 29px;">
                                                                  <button class="btn btn-info btn-block btn-sm" id="filter_sched"><i class="fas fa-filter"></i> Filter</button>
                                                            </div>
                                                      </div>
                                                      {{-- <div class="row">
                                                            <div class="col-md-2">
                                                                  <button class="btn btn-info btn-block btn-sm" id="filter_sched"><i class="fas fa-filter"></i> Filter</button>
                                                            </div>
                                                      </div> --}}
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-5">
                              <div class="card shadow">
                                    <div class="card-header  bg-success">
                                          <h3 class="card-title"><i class="fas fa-clipboard-list"></i> By Day</h3>
                                          <div class="card-tools">
                                                <ul class="nav nav-pills ml-auto">
                                                      <li class="nav-item">
                                                            <select class="form-control form-control-sm" name="" id="filter_day">
                                                                  <option value="1">Monday</option>
                                                                  <option value="2">Tuesday</option>
                                                                  <option value="3">Wednesday</option>
                                                                  <option value="4">Thursday</option>
                                                                  <option value="5">Friday</option>
                                                                  <option value="6">Saturday</option>
                                                            </select>
                                                      </li>
                                                </ul>
                                          </div>
                                    </div>
                                    <div class="card-body p-0">
                                          <div class="row">
                                                <div class="col-md-12">
                                                      <table class="table table-sm table-striped mb-0 table-bordered" style="font-size:.8rem" width="100%">
                                                            <thead>
                                                                  <tr>
                                                                        <th width="25%" class="pl-2 pr-2">Time</th>
                                                                        <th width="25%">Section</th>
                                                                        <th width="50%">Subject</th>
                                                                  </tr>
                                                            </thead>
                                                            <tbody  id="table_1"></tbody>
                                                      </table>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="col-md-7">
                              <div class="card shadow">
                                    <div class="card-header  bg-primary">
                                          <h3 class="card-title"><i class="fas fa-clipboard-list"></i> All</h3>
                                    </div>
                                    <div class="card-body  p-2">
                                          <div class="row">
                                                <div class="col-md-12" style="font-size:.8rem" >
                                                      <table class="table table-sm table-striped display table-bordered" id="datatable_1" width="100%">
                                                            <thead>
                                                                  <tr>
                                                                        <th width="25%">Schedule</th>
                                                                        <th width="25%">Section</th>
                                                                        <th width="50%">Subject</th>
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

@section('footerscript')
      
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>

      <script>
            $('#syid').select2()
            $('#semester').select2()
      </script>

      <script>
            $(document).ready(function(){

                  get_schedule()


                  $('#filter_sched').on('click',function(){
                        get_schedule()
                  })
                  var all_sched = []
                  
                  function get_schedule(){

                        $.ajax({
                              type:'GET',
                              url:'/college/teacher/schedule/get',
                              data:{
                                    'syid':$('#syid').val(),
                                    'semid':$('#semester').val(),
                              },
                              success:function(data) {
                                    all_sched = data
                                    
                                    var d = new Date();
                                    var today = d.getDay()
                                    $('#filter_day').val(today).change()
                                    datatable_1()
                              }
                        })

                  }

                  $(document).on('change','#term',function(){
                        datatable_1()
                        byday_sched()
                  })

                  
                  $(document).on('change','#filter_day',function(){
                        byday_sched()
                       
                  })

                  $(document).on('change','#filter_day',function(){
                        byday_sched()
                  })

                  function byday_sched(){

                        $('#table_1').empty()
                        
                        var today = $('#filter_day').val()
                        var day_sched = []

                        var temp_sched = all_sched

                        if($('#term').val() != ""){
                              if($('#term').val() == "Whole Sem"){
                                    temp_sched = all_sched.filter(x=>x.schedotherclass == null)
                              }else{
                                    temp_sched = all_sched.filter(x=>x.schedotherclass == $('#term').val())
                              }
                        }


                        $.each(temp_sched,function(a,b){
                              $.each(b.schedule,function(c,d){
                                    if(d.days.filter(x=>x == today).length > 0){
                                          day_sched.push(b)
                                    }
                              })
                        })

                        day_sched.sort(function(a, b){
                              return ((a.sort < b.sort) ? -1 : ((a.sort > b.sort) ? 1 : 0));
                        });

                        if(day_sched.length == 0){
                              $('#table_1').append('<tr><td colspan="3" class="pl-2 pr-2"><i>No available class.</i></td></tr>')
                        }else{
                              $.each(day_sched,function(a,b){

                                    var schedotherclass = b.schedotherclass != null ? b.schedotherclass : 'Whole Semester'
                                    
                                    $('#table_1').append('<tr><td class="pl-2 pr-2 align-middle"><a class="mb-0">'+b.schedule[0].start+'<br>'+b.schedule[0].end+'</a></td><td>'+'<a class="mb-0">'+b.sectionDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+b.levelname+' <br> '+b.courseabrv+'</p>'+'</td><td>'+'<a class="mb-0">'+b.subjDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+b.subjCode+'</p><i class="mb-0 text-danger" style="font-size:.7rem">'+schedotherclass+'</i>'+'</td></tr>')
                              })
                        }

                      
                  }

                  function datatable_1(){

                        var temp_sched = all_sched
                        if($('#term').val() != ""){
                              if($('#term').val() == "Whole Sem"){
                                    temp_sched = all_sched.filter(x=>x.schedotherclass == null)
                              }else{
                                    temp_sched = all_sched.filter(x=>x.schedotherclass == $('#term').val())
                              }
                        }

                        $("#datatable_1").DataTable({
                              destroy: true,
                              data:temp_sched,
                              lengthChange: false,
                              scrollX: true,
                              autoWidth: false,
                              columns: [
                                    { "data": "sort"},
                                    { "data": "search" },
                                    { "data": null }
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                              
                                                var temp_room = '<p class="mb-0">Room: Not Assigned</p>'
                                                
                                                if(rowData.roomname != null){
                                                    temp_room = '<p class="mb-0">Room: '+rowData.roomname + '</p>'
                                                }
                                              
                                                

                                                var text = '<a class="mb-0">'+rowData.schedule[0].start+' - '+rowData.schedule[0].end+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.schedule[0].day+'</p>';
                                                $(td)[0].innerHTML =  text + temp_room
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.sectionDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.levelname+'<br>'+rowData.courseabrv+'</p>';
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                var schedotherclass = rowData.schedotherclass != null ? rowData.schedotherclass : 'Whole Semester'

                                                var text = '<a class="mb-0">'+rowData.subjDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.subjCode+'</p><i class="mb-0 text-danger" style="font-size:.7rem">'+schedotherclass+'</i>';
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                          }
                                    }
                              ]
                        })
                  }
            })
      </script>

@endsection

