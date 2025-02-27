

@extends($extends)
@section('content')
<style>
    
    .select2-container .select2-selection--single {
        height: 40px !important;
    }
    /* Results "Dropdown/DropUp" */
.select2-container--default .select2-results>.select2-results__options {
    /* background-color: #ddd; */
    /* color: #eeeeee; */
}

/* Clear "X" */
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #cccccc;
}

/* Clear "X" Hover */
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #aa0000;
}
/* Each Result */
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #679ae6;
}
</style>


<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
      <h1>Export Logs</h1>
        <!-- <h1>Attendance</h1> -->
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item active">Export Logs</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

  <div class="card shadow" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; border: none !important;">
      <div class="card-header">
          <div class="row">
              <div class="col-md-3">
                  <label>Date Range</label>
                <input type="text" class="form-control" id="input-date">
              </div>
              <div class="col-md-4">
                  <label>Department</label>
                  <select class="form-control form-control" id="select-department">
                    <option value="0"></option>
                    @if(count($departments)>0)
                      @foreach($departments as $department)
                      <option value="{{$department->id}}">{{ucwords($department->department)}}</option>
                      @endforeach
                    @endif
                  </select>
              </div>
              {{-- <div class="col-md-6">
                <div class="form-group">
                <label>Select Employee</label>
                
                <select class="form-control select2"  multiple="multiple" id="select-id" style="width: 100%;">
                    @foreach($employees as $employee)
                        <option value="{{$employee->id}}">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}}</option>
                    @endforeach
                </select>
                </div>
              </div> --}}
              <div class="col-md-5 text-right align-self-end">
                
                  <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
              </div>
          </div>
      </div>
  </div>
  <div id="results-container"></div>
@endsection
@section('footerscripts')
<script>
    $(document).ready(function(){
        $('.select2').select2()
            
        $('#input-date').daterangepicker()
        $('#input-date').on('change', function(){
          $('#results-container').empty()
        })
        $('#select-id').on('change', function(){
          $('#results-container').empty()
        })
        $('#btn-generate').on('click', function(){
            
            Swal.fire({
                    title: 'Generating...',
                    allowOutsideClick: false,
                    closeOnClickOutside: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                })  
                $.ajax({
                    url: '/hr/attendance/summarygenerate',
                    type: 'GET',
                    data: {
                        id              : $('#select-id').val(),
                        departmentid    : $('#select-department').val(),
                        dates           : $('#input-date').val()
                    },
                    success:function(data){
                        $('#results-container').empty()
                        $('#results-container').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                    // , error:function()
                    // {
                    //     saveattendance(selectedschoolyear,selectedsemester,dataobj)
                    // }
                })
        })
        $(document).on('click','#btn-exporttopdf', function(){
          window.open('/hr/attendance/summarygenerate?exporttype=pdf&id='+JSON.stringify($('#select-id').val())+'&dates='+$('#input-date').val()+'&departmentid='+$('#select-department').val(),'_blank')
        })
    })
</script>
@endsection

