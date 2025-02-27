
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
@endsection

@section('modalSection')
  
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
                        <li class="breadcrumb-item active">Room</li>
                  </ol>
            </div>
      </div>
    </div>
</section>
    
    <section class="content pt-0">
            <div class="row">
                  <div class="col-12">
                        <div class="card">
                              <div class="card-header bg-info ">
                                    <span class="text-white h4"><b>Sync Modules Setup ( Cloud to Local )</b></span>
                                    <input type="text" id="search" name="search" class="form-control form-control-sm float-right w-25" placeholder="Search" >
                              </div>
                              <div class="card-body table-responsive p-0" 
                              id="syncmodule_table_holder" 
                              >
                              
                              
                              </div>
                              <div class="card-footer">
                                    <div class="" id="data-container">

                                    </div>
                              </div> 
                        
                        </div>
                  </div>
            </div>
      </section>
      <section class="content pt-0">
            <div class="row">
                  <div class="col-12">
                        <div class="card">
                              <div class="card-header bg-info ">
                                    <span class="text-white h4"><b>Modules Enabled</b></span>
                                    <input type="text" id="search" name="search" class="form-control form-control-sm float-right w-25" placeholder="Search" >
                              </div>
                              <div class="card-body table-responsive p-0" 
                              id="syncmodule_table_holder_enabled" 
                              >
                              
                              
                              </div>
                              <div class="card-footer">
                                    <div class="" id="data-container_enabled">

                                    </div>
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

                  // load_sync_table()

                  @php
                        $syncsetup = DB::table('syncsetup')->first();
                  @endphp

                  // function load_sync_table(){

                  //       $.ajax({
                  //             type:'GET',
                  //             url:'/syncmodules?table=table&synctype=ctl',
                  //             success:function(data) {
                  //                   $('#syncmodule_table_holder').empty()
                  //                   $('#syncmodule_table_holder').append(data)
                  //             }
                  //       })   

                  // }


                  processpaginate(0,10,null,true)

                  function processpaginate(skip = null,take = null ,search = null, firstload = true){

                        $.ajax({
                              type:'GET',
                              url:'/syncmodules?table=table&synctype=ctl&take='+take+'&skip='+skip+'&table=table'+'&search='+search+'&facultynstaff=facultynstaff',
                              success:function(data) {
                                    $('#syncmodule_table_holder').empty();
                                    $('#syncmodule_table_holder').append(data);
                                    pagination($('#searchCount').val(),false)
                              
                              }
                        })

                  }

                  var pageNum = 1;

                  function pagination(itemCount,pagetype){

                        var result = [];

                        for (var i = 0; i < itemCount; i++) {
                              result.push(i);
                        }

                        $('#data-container').pagination({
                              dataSource: result,
                              hideWhenLessThanOnePage: true,
                              pageNumber: pageNum,
                              pageRange: 1,
                              callback: function(data, pagination) {

                                          if(pagetype){

                                                processpaginate(pagination.pageNumber,10,$('#search').val(),false)

                                          }

                                          pageNum = pagination.pageNumber
                                          pagetype=true
                                    }
                              })
                  }

                  $(document).on('keyup','#search',function() {
                        pageNum = 1
                        processpaginate(0,10,$('#search').val(),null)
                        
                  });


                  processpaginateenabled(0,10,null,true)

                  function processpaginateenabled(skip = null,take = null ,search = null, firstload = true){

                        $.ajax({
                              type:'GET',
                              url:'/syncmodules?table=table&synctype=ctl&take='+take+'&skip='+skip+'&table=table'+'&search='+search+'&enabled=enabled',
                              success:function(data) {
                                    $('#syncmodule_table_holder_enabled').empty();
                                    $('#syncmodule_table_holder_enabled').append(data);
                                    paginationenabled($('#searchCountEnabled').val(),false)
                              
                              }
                        })

                  }

                  var pageNum = 1;

                  function paginationenabled(itemCount,pagetype){

                        var result = [];

                        for (var i = 0; i < itemCount; i++) {
                              result.push(i);
                        }

                        $('#data-container_enabled').pagination({
                              dataSource: result,
                              hideWhenLessThanOnePage: true,
                              pageNumber: pageNum,
                              pageRange: 1,
                              callback: function(data, pagination) {

                                          if(pagetype){

                                                processpaginateenabled(pagination.pageNumber,10,null,false)

                                          }

                                          pageNum = pagination.pageNumber
                                          pagetype=true
                                    }
                              })
                  }


                  $(document).on('click','input[type="checkbox"]',function(){
                        
                        var status = 0

                        if($(this).prop('checked')){
                              status = 1 
                        }
                        
                        if($(this).attr('data-value') == 'all' && $(this).prop('checked')){

                              $('#'+$(this).attr('data-table')+'create').prop('checked',true)
                              $('#'+$(this).attr('data-table')+'update').prop('checked',true)
                              $('#'+$(this).attr('data-table')+'delete').prop('checked',true)

                        }    
                        else if($(this).attr('data-value') == 'all' && !$(this).prop('checked')){
                           
                              $('#'+$(this).attr('data-table')+'create').prop('checked',false)
                              $('#'+$(this).attr('data-table')+'update').prop('checked',false)
                              $('#'+$(this).attr('data-table')+'delete').prop('checked',false)
                              
                        }
                        else if(( $(this).attr('data-value') != 'all' || $(this).attr('data-value') != 'all' ) && !$(this).prop('checked') && $(this).attr('data-value') != 'deleted'){
                           
                           $('#'+$(this).attr('data-table')+'all').prop('checked',false)
                           
                        }

                        $.ajax({
                              type:'GET',
                              url:'syncmodules?update=update&type='+$(this).attr('data-value')+'&tablename='+$(this).attr('data-table')+'&status='+status+'&synctype=ctl'+'&url='+'{{$syncsetup->url}}',
                              success:function(data) {

                                    processpaginateenabled(0,10,null,true)

                                   
                              }
                        })   

                  })
            })
      </script>
    
@endsection

