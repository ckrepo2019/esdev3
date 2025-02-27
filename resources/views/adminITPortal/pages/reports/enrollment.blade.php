@extends('adminITPortal.layouts.app')


@section('pagespecificscripts')


@endsection


@section('content')
      <section class="content-header">
            <div class="container-fluid">
                  <div class="row mb-2">
                        <div class="col-sm-6">
                              <h1 class="m-0 text-dark">{{Session::get('schoolinfo')->schoolname}}</h1>
                        </div>
                        <div class="col-sm-6">
                        </div>
                  </div>
            </div>
      </section>
      <section class="content pt-0">
           <div class="container-fluid">
                  <div class="row">
                        <div class="col-md-12">
                        <div class="card" >
                              <form action="/filter" id="filterfrom" method="POST">
                              
                                    <div class="card-header bg-success">
                                          <h3 class="card-title">FILTER</h3>
                                    </div>
                                    <div class="card-body" >
                                          <div class="row">
                                                <div class="form-group col-md-3">
                                                      <label for="">Date From</label>
                                                      <input id="datefrom" name="datefrom" type="date" class="form-control" value="{{date('Y-m-d')}}">
                                                </div>
                                                <div class="form-group col-md-3">
                                                      <label for="">Date To</label>
                                                      <input  id="dateto" name="dateto" type="date" class="form-control" value="{{date('Y-m-d')}}">
                                                </div>
                                                <div class="form-group col-md-3">
                                                      <label for="">School Year</label>
                                                      <select name="sy" id="sy" class="form-control">
                                                            @foreach (DB::table('sy')->get() as $item)
                                                            <option value="{{$item->id}}" @if($item->isactive == 1) selected @endif>{{$item->sydesc}}</option>
                                                            @endforeach
                                                      </select>
                                                
                                                </div>
                                                <div class="form-group col-md-3">
                                                      <label for="">Semester</label>
                                                      <select name="sem" id="sem" class="form-control">
                                                            @foreach (DB::table('semester')->where('deleted',0)->get() as $item)
                                                            <option value="{{$item->id}}" @if($item->isactive == 1) selected @endif>{{$item->semester}}</option>
                                                            @endforeach
                                                      </select>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="card-footer text-right p-2">
                                          <button class="btn btn-default btn-sm" type="button" id="clear">Clear</button>
                                          <button class="btn btn-primary btn-sm" type="button" id="filter">Filter</button>
                                    </div>
                              </form>
                        </div>
                  </div>
                        <div class="sectionholder col-md-12 row">
                              @include('adminITPortal.pages.reports.enrolledsection')
                        </div>
                        {{-- <div class="col-md-3">
                              <div class="card" >
                                    <form action="/filter" id="filterfrom" method="POST">
                                    
                                          <div class="card-header bg-success">
                                                <h3 class="card-title">FILTER</h3>
                                          </div>
                                          <div class="card-body" style="height: 589px">
                                                <div class="row">
                                                      <div class="form-group col-md-12">
                                                            <label for="">Date From</label>
                                                            <input id="datefrom" name="datefrom" type="date" class="form-control">
                                                      </div>
                                                      <div class="form-group col-md-12">
                                                            <label for="">Date To</label>
                                                            <input  id="dateto" name="dateto" type="date" class="form-control">
                                                      </div>
                                                      <div class="form-group col-md-12">
                                                            <label for="">School Year</label>
                                                            <select name="sy" id="sy" class="form-control">
                                                                  @foreach (DB::table('sy')->get() as $item)
                                                                  <option value="{{$item->id}}" @if($item->isactive == 1) selected @endif>{{$item->sydesc}}</option>
                                                                  @endforeach
                                                            </select>
                                                      
                                                      </div>
                                                      <div class="form-group col-md-12">
                                                            <label for="">Semester</label>
                                                            <select name="sem" id="sem" class="form-control">
                                                                  @foreach (DB::table('semester')->where('deleted',0)->get() as $item)
                                                                  <option value="{{$item->id}}" @if($item->isactive == 1) selected @endif>{{$item->semester}}</option>
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                </div>
                                          </div>
                                          <div class="card-footer text-right p-2">
                                                <button class="btn btn-default btn-sm" type="button" id="clear">Clear</button>
                                                <button class="btn btn-primary btn-sm" type="button" id="filter">Filter</button>
                                          </div>
                                    </form>
                              </div>
                        </div>
                   --}}

                        
                  </div>
            </div>
      </section>
@endsection


@section('footerjavascript')

<script>

      $(document).ready(function(){
            
            var clearForm;

            $(document).on('click','#clear',function(){
                  clearForm = 1;
                  filter()
            })

            $(document).on('click','#filter',function(){
                
                  filter()
            })

            function filter(){

                  if(clearForm== 1){
                        
                        $('#filterfrom')[0].reset()

                  }

                  $.ajax( {
                        url: '/filterEnrollmentReport',
                        type: 'GET',
                        data: {
                              '_token': '{{ csrf_token() }}',
                              'datefrom':$('#datefrom').val(),
                              'dateto':$('#dateto').val(),
                              'sy':$('#sy').val(),
                              'sem':$('#sem').val(),
                        },
                        success:function(data) {

                              $('.sectionholder').empty()
                              $('.sectionholder').append(data)
                              clearForm = 0;
                        
                        
                        },
                  });
            }
      })

</script>

  
@endsection