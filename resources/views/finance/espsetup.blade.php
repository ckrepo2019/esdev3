@extends('finance.layouts.app')

@section('content')
  {{-- <style type="text/css">
    .table thead th  { 
                position: sticky !important; left: 0 !important; 
                width: 150px !important;
                background-color: #fff !important; 
                outline: 2px solid #fff !important;
                outline-offset: -1px !important;
            }
  </style> --}}
	{{-- <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Finance</h1> -->
          
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Payment Items</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section> --}}
  <section class="content">
  			<!-- Payment Items -->
        <div class="row mb-2 ml-2">
            <h1 class="m-0 text-dark">Special/Summer Class Setup</h1>
        </div>
        {{-- <div class="row">
            <div class="col-8">
            </div>
            <div class="col-4">
                <div class="input-group mb-3">
                    <input id="txtsearchitem" type="text" class="form-control" placeholder="Search Item" onkeyup="this.value = this.value.toUpperCase();">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-primary" id="item_create">Create</button>
                    </div>
                </div>
            </div>

        </div> --}}
        <div class="row form-group">
                <div class="col-md-3">
                    <select id="esp_level" class="select2" style="width: 100%;">
                        <option value="0">Grade Level</option>
                        @foreach(db::table('gradelevel')->where('deleted', 0)->orderBy('sortid')->get() as $level)
                            <option value="{{$level->id}}">{{$level->levelname}}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="col-md-3">
                    <select id="es_sy" class="select2" style="width: 100%;">
                        @foreach(db::table('sy')->get() as $sy)
                            @if($sy->isactive == 1)
                                <option value="{{$sy->id}}" selected>{{$sy->sydesc}}</option>
                            @else
                                <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="esp_sem" class="select2" style="width: 100%;">
                        @foreach(db::table('semester')->get() as $sem)
                            @if($sem->isactive == 1)
                                <option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
                            @else
                                <option value="{{$sem->id}}">{{$sem->semester}}</option>
                            @endif
                        @endforeach
                    </select>
                </div> --}}
            </div>
		<div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        
                    </div>
                    <div class="card-body">
                        <div id="main_table" class="table-responsive p-0">
                            <table class="table table-hover table-head-fixed table-sm text-sm">
                                <thead class="bg-warning p-0">
                                  <tr>
                                    <th>SUBJECTS</th>
                                    <th>AMOUNT</th>
                                    
                                  </tr>
                                </thead> 
                                <tbody id="esp_detail" style="cursor: pointer;"></tbody>             
                            </table>
                          <div id="#demo"></div>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
  </section>
@endsection

@section('modal')
  <div class="modal fade show" id="modal-esp_header" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title subjtitle"></h4>
          {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button> --}}
        </div>
        <div class="modal-body">
            
            <div class="row">
                <div class="col-md-8">
                    <select id="esp_class" class="select2" style="width: 100%;">
                        <option>
                            @foreach(db::table('itemclassification')->where('deleted', 0)->orderBy('description')->get() as $class)
                                <option value="{{$class->id}}">{{$class->description}}</option>
                            @endforeach
                        </option>
                    </select>
                </div>
                <div class="c col-md-4">
                    <input type="number" id="esp_amount" class="form-control text-right" placeholder="0.00">
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="esp_save" type="button" class="btn btn-primary" data-dismiss="modal" data-subj="">Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

    
    


  

  
@endsection

@section('js')
  
  <script type="text/javascript">
    
    $(document).ready(function(){
        var searchVal = $('#txtsearchitem').val();

        $('.select2').select2({
            theme: 'bootstrap4'
        });

        screenadjust();

        function screenadjust()
        {
            var screen_height = $(window).height();

            $('#main_table').css('height', screen_height - 300);
            // $('.screen-adj').css('height', screen_height - 223);
        }

        $(document).on('change', '#esp_level', function(){
            var levelid = $(this).val();
            // var syid = $('#esp_sy').val();
            // var semid = $('#esp_sem').val();

            $.ajax({
                url: '{{route('esp_loaddetail')}}',
                type: 'GET',
                // dataType: 'json',
                data: {
                    levelid:levelid
                },
                success:function(data)
                {
                    $('#esp_detail').html(data);
                }
            });
            
        });

        $(document).on('click', '#esp_detail tr', function(){
            $('#modal-esp_header').modal('show');
            setTimeout(function(){
                $('#esp_class').focus();
            }, 300)

            var subj = $(this).find('.subj').text();
            $('.subjtitle').text(subj);
            $('#esp_save').attr('data-subj', $(this).attr('data-id'));

        })

        $(document).on('click', '#esp_save', function(){
            var levelid = $('#esp_level').val();
            var subjid = $(this).attr('data-subj');
            var classid = $('#esp_class').val();
            var amount = $('#esp_amount').val();

            $.ajax({
                url: '{{route('esp_update')}}',
                type: 'GET',
                data: {
                    levelid:levelid,
                    subjid:subjid,
                    classid:classid,
                    amount:amount
                },
                success:function(data)
                {
                    $('#esp_level').trigger('change');
                }
            });
            
        });

        function getdata()
        {
            var table = $('.table').DataTable({
                paging: false,
                ordering: true,
                columns:[
                    {"data": 'su'}
                ]
            });
        }
        

    });

  </script>
  
@endsection