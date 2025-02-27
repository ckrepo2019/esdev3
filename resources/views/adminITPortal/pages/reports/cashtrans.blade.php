@extends('adminITPortal.layouts.app')


@section('pagespecificscripts')
<link rel="stylesheet" href="{{asset('css/pagination.css')}}">

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
                        <div class="cashtransholder col-md-9 row">
                              @include('adminITPortal.pages.reports.cashtranssection')
                        </div>
                        <div class="col-md-3">
                              <div class="card" >
                                    <form action="/filter" id="filterfrom" method="POST">
                                    
                                          <div class="card-header bg-success">
                                                <h3 class="card-title">FILTER</h3>
                                          </div>
                                          <div class="card-body" style="height: 588px">
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
                                                      <div class="form-group col-md-12">
                                                            <label for="">Page Number</label>
                                                            <div class="" id="data-container">
                                                            </div>
                                                      </div>
                                                </div>
                                          </div>
                                          <div class="card-footer text-right">
                                                <button class="btn btn-default" type="button" id="clear">Clear</button>
                                                <button class="btn btn-primary" type="button" id="filter">Filter</button>
                                          </div>
                                    </form>
                              </div>
                        </div>
                  </div>
            </div>
      </section>
@endsection


@section('footerjavascript')
<script src="{{asset('js/pagination.js')}}"></script> 
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
                        url: '/filtercashtrans',
                        type: 'GET',
                        data: {
                              '_token': '{{ csrf_token() }}',
                              'datefrom':$('#datefrom').val(),
                              'dateto':$('#dateto').val(),
                              'sy':$('#sy').val(),
                              'sem':$('#sem').val(),
                              'pagenum':1
                        },
                        success:function(data) {

                              $('.cashtransholder').empty()
                              $('.cashtransholder').append(data)
                              clearForm = 0;
                              pagination($('#pagecount').val())
                              
                        
                        },
                  });
            }
     

            pagination('{{$count[0]['count']}}',false);

            function pagination(itemCount,pagetype){


                  var result = [];
                  for (var i = 0; i < itemCount; i++) {
                  result.push(i);
                  }
                  
                  var pageNum = 1;

                  $('#data-container').pagination({
                        dataSource: result,
                        hideWhenLessThanOnePage: true,
                        pageNumber: pageNum,
                        pageRange: 1,
                        callback: function(data, pagination) {

                              if(pagetype){
                                    $.ajax({
                                          url: '/filtercashtrans',
                                          type: 'GET',
                                          data: {
                                                '_token': '{{ csrf_token() }}',
                                                'datefrom':$('#datefrom').val(),
                                                'dateto':$('#dateto').val(),
                                                'sy':$('#sy').val(),
                                                'sem':$('#sem').val(),
                                                'pagenum':pagination.pageNumber
                                          },
                                          success:function(data) {

                                                $('.cashtransholder').empty()
                                                $('.cashtransholder').append(data)
                                                clearForm = 0;
                                          
                                          },
                                          error:function(){
                                                console.log('error')
                                          }
                                    })
                              }

                              pagetype=true
                        }
                  })
            }

      

      
      })
 </script>


  
@endsection