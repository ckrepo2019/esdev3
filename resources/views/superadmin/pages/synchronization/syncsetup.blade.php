
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
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
                                    <h5 class="mb-0">Sync Setup</h5>
                              </div>
                              <div class="card-body ">
                                    <h5 class="border-top border-bottom pt-2 pb-2 bg-primary pl-2">Local to Cloud</h5>
                                    <div class="row pl-2 pr-2">
                                          <form class="form-group col-md-6" action="/syncsetup?update=update&synctype=ltc" method="GET" id="udpate_target_cloud_url">
                                                <label for="" class="text-primary">Target Cloud URL</label>
                                                <div class="input-group p-0">
                                                      <input 
                                                            class="form-control" 
                                                            id="target_cloud_url"
                                                            name="target_cloud_url"
                                                            >
                                                      <span class="input-group-append">
                                                            <button type="submit" class="btn btn-info" >UPDATE</button>
                                                      </span>
                                                </div>
                                          </form>
                                    </div>
                                    <h5 class="border-top border-bottom pt-2 pb-2 bg-primary pl-2">Cloud to Local</h5>
                                    <div class="row pl-2 pr-2">
                                          <form class="form-group col-md-6" action="/syncsetup?update=update" method="GET" id="udpate_target_local_url">
                                                <label for="" class="text-primary">Target Local URL</label>
                                                <div class="input-group p-0">
                                                      <input 
                                                            class="form-control" 
                                                            id="target_local_url"
                                                            name="target_local_url"
                                                            >
                                                      <span class="input-group-append">
                                                            <button type="submit" class="btn btn-info" id="updateschoolcolorbutton">UPDATE</button>
                                                      </span>
                                                </div>
                                          </form>
                                    </div>
                                  
                              </div>
                        
                        </div>
                  </div>
            </div>
      </section>
@endsection

@section('footerjavascript')
      <script>
            $(document).ready(function(){

                  load_sync_table()

                  function load_sync_table(){

                        $.ajax({
                              type:'GET',
                              url:'/syncsetup?info=info&synctype=ltc',
                              success:function(data) {
                                    $('#target_cloud_url').val(data[0].url)
                              }
                        })
                        
                        $.ajax({
                              type:'GET',
                              url:'/syncsetup?info=info&synctype=ctl',
                              success:function(data) {

                                   $('#target_local_url').val(data[0].url)
                              }
                        })

                  }

                  $('#udpate_target_cloud_url').submit(function(e){

                        var inputs = new FormData(this)

                        $.ajax({
                              type:'GET',
                              url:'/syncsetup?update=update&synctype=ltc&url='+$('#target_cloud_url').val(),
                              success:function(data) {
                                    load_sync_table()
                              }
                        })

                        e.preventDefault()

                  })


                  $('#udpate_target_local_url').submit(function(e){

                        var inputs = new FormData(this)

                        $.ajax({
                              type:'GET',
                              url:'/syncsetup?update=update&synctype=ctl&url='+$('#target_local_url').val(),
                              success:function(data) {
                                    load_sync_table()
                              }
                        })

                        e.preventDefault()

                  })


                
            })
      </script>
    
@endsection

