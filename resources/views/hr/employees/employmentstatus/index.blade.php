

@extends('hr.layouts.app')
@section('content')
<style>
    
.bg-success {
    color: #155724 !important;
    background-color: #d4edda !important;
    border-color: #c3e6cb !important;
}
</style>
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Employees</h1> -->
          <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
          <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
          Employment Status </h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Employment Status</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <div class="row">
      <div class="col-md-3">
        <div class="card card-success collapsed-card" style="border: none; font-size: 11px;">
            <div class="card-header p-0">
                <button type="button" class="btn btn-sm btn-primary btn-tools btn-block m-0" id="btn-empstatus-collapse" data-card-widget="collapse"><i class="fa fa-plus"></i> Add Employment Status</button>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-2">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label>Title</label>
                        <input type="text" class="form-control form-control-sm" id="input-title" placeholder="Ex. Regular/"/>
                    </div>
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-sm btn-primary" id="btn-submit-empstatus">Submit</button>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
          </div>
          <div id="container-empstatus" style="font-size: 14px;">
            @if(count($statustypes)>0)
              @foreach($statustypes as $statuskey => $statustype)
              <div class="info-box bg-success p-1 card collapsed-card" style="border: none; box-shadow: unset !important;">
                {{-- <span class="info-box-icon">{{$offensekey+1}}</span> --}}    
                <div class="info-box-content card-header">
                  <span class="info-box-number">{{$statustype->description}} <span class="badge badge-warning float-right">{{$statustype->count}}</span></span>
                  {{-- <span class="progress-description">
                      {{$statustype->count}}
                  </span> --}}
                  <div class="row mt-2">
                      <div class="col-md-12">
                          <button type="button" class="btn btn-sm btn-default btn-delete-status" data-id="{{$statustype->id}}" data-title="{{$statustype->description}}" ><i class="fa fa-trash-alt"></i></button>
                          <button type="button" class="btn btn-sm btn-default btn-edit-status" data-id="{{$statustype->id}}" data-title="{{$statustype->description}}"  data-card-widget="collapse"><i class="fa fa-edit"></i></button>
                      </div>
                  </div>
                </div>
                <div class="card-body p-1">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Title</label>
                            <input type="text" class="form-control form-control-sm" id="input-edit-title{{$statustype->id}}" value="{{$statustype->description}}"/>
                        </div>
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-sm btn-success btn-submit-edit-empstatus" data-id="{{$statustype->id}}">Save changes</button>
                        </div>
                    </div>
                </div>
                <!-- /.info-box-content -->
              </div>
              @endforeach
            @endif
          </div>
      </div>
      <div class="col-md-9">
        <div class="card" style="border: none;">
            <div class="card-body">
                <div class="row">
                      <div class="col-md-6">
                          <label for="week">Employment Status</label>
                          <select class="form-control form-control-sm" id="select-empstatus">
                            <option value="0">All</option>
                              @foreach($statustypes as $statustype)
                                <option value="{{$statustype->id}}">{{$statustype->description}}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-md-6 text-right">
                          <label>&nbsp;</label><br/>
                          <button type="button" class="btn btn-primary btn-sm" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                      </div>
                </div>
            </div>
        </div>
        <div id="container-results" class="pr-1 pl-1"></div>
    </div>
  </div>
  
      
@endsection
@section('footerjavascript')
<script>
    
    $(document).ready(function(){
        // $('#btn-generate').hide()
        function getempstatustypes()
        {
            
            $.ajax({
                url: '/hr/employees/statusindex',
                type:"GET",
                data: {
                    action: 'getstatustypes'
                },
                success:function(data) {
                    $('#container-empstatus').empty()
                    $('#container-empstatus').append(data)
                }
            });
        }
        $('#btn-submit-empstatus').on('click', function(){
            var title = $('#input-title').val();
            var validation = 0;
            if(title.replace(/^\s+|\s+$/g, "").length == 0){
                $('#input-title').css('border','1px solid red')
                validation = 1;
            }else{
                $('#input-title').removeAttr('style');
            }
            if(validation == 0)
            {                
                $.ajax({
                        url: '/hr/employees/statustypes',
                        type:"GET",
                        data:{
                            action: 'addstatus',
                            title  : title
                        },
                        success:function(data) {
                            if(data == '1')
                            {
                                toastr.success('Added successfully!','Add Employment Status')
                                $('#input-title').val('')
                                $('#btn-empstatus-collapse').click()
                                getempstatustypes()
                                //window.location.reload()
                            }else{
                                toastr.error('Offense exists!','Add Employment Status')
                            }
                        }
                    });
            }else{
                toastr.warning('Field empty!','Add Employment Status')
            }
        })
        $('.btn-delete-status').on('click', function(){
            var statusid = $(this).attr('data-id')
            Swal.fire({
                title: 'Are you sure you want to delete this offense?',
                type: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Delete',
                showCancelButton: true,
                allowOutsideClick: false
            }).then((confirm) => {
                if (confirm.value) {

                    $.ajax({
                        url: '/hr/employees/statustypes',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            action: 'deletestatus',
                            statusid :   statusid
                        },
                        success: function(data){
                            if(data == 1)
                            {
                                getempstatustypes()
                                toastr.success('Deleted successfully!','Delete Employment Status')
								window.location.reload()
                            }else{
                                toastr.error('Something went wrong!','Delete Employment Status')
                            }
                        }
                    })
                }
            })
        })
        $(document).on('click','.btn-submit-edit-empstatus', function(){
            var statusid = $(this).attr('data-id')
            var title = $('#input-edit-title'+statusid).val();
            var validation = 0;
            if(title.replace(/^\s+|\s+$/g, "").length == 0){
                $('#input-edit-title'+statusid).css('border','1px solid red')
                validation = 1;
            }else{
                $('#input-edit-title'+statusid).removeAttr('style');
            }
            if(validation == 0)
            {                
                $.ajax({
                        url: '/hr/employees/statustypes',
                        type:"GET",
                        data:{
                            action: 'editstatus',
                            statusid  : statusid,
                            title  : title
                        },
                        success:function(data) {
                            if(data == '1')
                            {
                                toastr.success('Added successfully!','Edit Status')
                                $('#input-edit-title'+statusid).val('')
                                getempstatustypes()
                                $('.btn-edit-status[data-id="'+statusid+'"]').click()
                            }else{
                                toastr.error('Offense exists!','Edit Status')
                            }
                        }
                    });
            }else{
                toastr.warning('Field empty!','Edit Offense')
            }
        })
        $('#btn-generate').on('click', function(){
            var week = $('#select-week').val()
            
            $.ajax({
                url: '/hr/employees/empstatusgenerate',
                type:"GET",
                data:{
                    // action: 'editoffense',
                    statusid  : $('#select-empstatus').val()
                },
                success:function(data) {
                    $('#container-results').empty();
                    $('#container-results').append(data);
                }
            });
        })
        $(document).on('change','.select-change-empstatus', function(){
            var employeeid = $(this).attr('data-empid');
            var statusid   = $(this).val();
            $.ajax({
                url: '/hr/employees/statustypes',
                type:"GET",
                data:{
                    action: 'editempstatus',
                    statusid  : statusid,
                    employeeid  : employeeid
                },
                success:function(data) {
                    if(data == '1')
                    {
                        toastr.success('Updated successfully!','Edit Employment Status')
                    }else{
                        toastr.error('Something went wrong!','Edit Employment Status')
                    }
                }
            });
        })
        $(document).on('click','#btn-exporttopdf', function(){
            window.open('/hr/employees/empstatusgenerate?action=export&statusid='+$('#select-empstatus').val(),'_blank');
        })
        $(document).on("keyup","#input-search", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".card-each-employee").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                $(".card-group.card-group-filter:first-of-type").append($(this));
                $(this).hide();
                hiddenCards++;

                } else {

                $(".card-group.card-group-filter:last-of-type").prepend($(this));
                $(this).show();
                visibleCards++;

                if (((visibleCards % 4) == 0)) {
                    $(".container").append($("<div class='card-group card-group-filter'></div>"));
                }
                }
            });

        });
    })

</script>

@endsection
