@extends(''.$extends.'')
@section('content')

  <!-- Ekko Lightbox -->
  <link rel="stylesheet" href="{{asset('plugins/ekko-lightbox/ekko-lightbox.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<style>
.gallery{margin: 10px 0;display: block;}
.imgdiv
{
    position: relative;
    width: 100px;
    height: 115px;
}
.imgdiv img{width: 100%;height: 115px;}
div.ekko-lightbox-container{height: 475px !important; }
.ekko-lightbox.modal.fade.in div.modal-dialog{
  max-width:40% !important;
  /* height: 475px */
;}
.img-fluid{
    height: 100% !important;
}
/* .div-only-mobile{
  width:400px;
  height:200px;
  background:orange;
} */
@media screen and (max-width : 1920px){
  .div-only-mobile{
  visibility:hidden;
  }
}
@media screen and (max-width : 906px){
 .desk{
  visibility:hidden;
  }
 .div-only-mobile{
  visibility:visible;
  }
  .viewprintbutton{
    width: 100%; display:block;
  }
}
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-chart-line nav-icon"></i> Daily Time Record</h4>
                <!-- <h1>Employee  Profile</h1> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Daily Time Record</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
@if(isset($message))
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Alert!</h5>
        {{$message}}
    </div>
@endif
@if(session()->has('messageAdd'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('messageAdd') }}
    </div>
@endif
@if(session()->has('messageExists'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Alert!</h5>
        {{ session()->get('messageExists') }}
    </div>
@endif
<div class="card">
    {{-- <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <strong>Daily Time Record</strong>
            </div>
        </div>
    </div> --}}
    <div class="card-body">
        <label>DTR Period</label>
        <br>
        <form action="/employeedailytimerecord/print" method="GET" target="_blank">
            <input type="text" name="period"  class="form-control form-control-sm col-md-3 mb-2" id="dtrdaterange" style="display: inline; " value="{{$currentmonthfirstday}} - {{$currentmonthlastday}}">
            <button type="submit" class="btn btn-sm btn-primary viewprintbutton"><i class="fa fa-print"></i> Print</button>
        </form>
        {{-- <br>
        <span class="div-only-mobile bg-info row">Swipe Left to view tardiness column</span>
        <br> --}}
        <div class="row" style="overflow: scroll;">
            <table class="table table-bordered" style="table-layout: fixed">
                <thead class="text-center">
                    <tr>
                        <th rowspan="2" style="width: 25%;">Date</th>
                        <th colspan="2">AM</th>
                        <th colspan="2">PM</th>
                        <th rowspan="2"></th>
                        {{-- <th rowspan="2">Tardiness<br>(Minutes)</th>
                        <th rowspan="2">Hours<br>Rendered</th> --}}
                    </tr>
                    <tr>
                        <th>IN</th>
                        <th>OUT</th>
                        <th>IN</th>
                        <th>OUT</th>
                    </tr>
                </thead>
                <tbody id="timerecord">
                    @foreach($employeeattendance as $empattendance)
                        <tr>
                            <td>
                                {{$empattendance->daystring}}
                                @if(strtolower($empattendance->day) == 'saturday' || strtolower($empattendance->day) == 'sunday')
                                    <span class="right badge badge-secondary float-right">{{$empattendance->day}}</span>
                                @else
                                    <span class="right badge badge-default  float-right">{{$empattendance->day}}</span>
                                @endif
                            </td>
                            <td class="text-center">@if($empattendance->amtimein != null){{$empattendance->amtimein}}@endif</td>
                            <td class="text-center">@if($empattendance->amtimeout != null){{$empattendance->amtimeout}}@endif</td>
                            <td class="text-center">@if($empattendance->pmtimein != null){{date('h:i:s', strtotime($empattendance->pmtimein))}}@endif</td>
                            <td class="text-center">@if($empattendance->pmtimeout != null){{date('h:i:s', strtotime($empattendance->pmtimeout))}}@endif</td>
                            <td>
                                <button type="button" class="btn btn-default btn-sm btn-block"  data-toggle="modal" data-target="#modal-addremarks{{$empattendance->dayint}}" >Remarks</button>
                                <div class="modal fade" id="modal-addremarks{{$empattendance->dayint}}">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h4 class="modal-title">Remarks</h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">                    
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control employee-remarks" value="{{$empattendance->remarks}}" data-date="{{$empattendance->date}}"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                          <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
                                          {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                                        </div>
                                      </div>
                                      <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                  </div>
                            </td>
                            {{-- <td class="text-center">
                                @if($empattendance->undertime > 0)
                                    {{$empattendance->undertime}}
                                @endif
                            </td>
                            <td class="text-center">{{$empattendance->hoursrendered}}</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- Ekko Lightbox -->
<script src="{{asset('plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>
<script>
   
    // ------------------------------------------------------------------------------------ DAILY TIME RECORD


$(document).ready(function(){
    $('#dtrdaterange').daterangepicker({
        locale: {
            format: 'MM-DD-YYYY'
        }
    });
$('#dtrdaterange').on('change',function(){

    $.ajax({
        url: '/employeedailytimerecord/changeperiod',
        type:   "GET",
        dataType:"json",
        data:{
            employeeid  :'{{$myid}}',
            period      : $(this).val()
        },
        success:function(data) {
            var countrow = 1;
            $('#timerecord').empty();
            $.each(data, function(key, value){
                $('#timerecord').append(
                    '<tr>'+
                        '<td id="dtr'+countrow+'">'+
                            value.date +
                        '</td>'+
                        '<td class="text-center">'+value.amtimein+'</td>'+
                        '<td class="text-center">'+value.amtimeout+'</td>'+
                        '<td class="text-center">'+value.pmtimein+'</td>'+
                        '<td class="text-center">'+value.pmtimeout+'</td>'+
                        '<td><button type="button" class="btn btn-default btn-sm btn-block"  data-toggle="modal" data-target="#modal-addremarks'+value.dayint+'" >Remarks</button>'+
                            '<div class="modal fade" id="modal-addremarks'+value.dayint+'">'+
                                '<div class="modal-dialog">'+
                                    '<div class="modal-content">'+
                                        '<div class="modal-header">'+
                                            '<h4 class="modal-title">Remarks</h4>'+
                                            '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                                                '<span aria-hidden="true">&times;</span>'+
                                            '</button>'+
                                            ' </div>'+
                                            '<div class="modal-body">'+
                                                '<div class="row">'+
                                                    '<div class="col-md-12">'+
                                                        ' <input type="text" class="form-control employee-remarks" data-date="'+value.tdate+'"  value="'+value.remarks+'"/>'+
                                                    '</div>'+
                                            ' </div>'+
                                        '</div>'+
                                        '<div class="modal-footer justify-content-between">'+
                                            '<button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</td>'+
                        // '<td class="text-center">'+value.hoursrendered+'</td>'+
                    '</tr>'
                )
                if(value.day.toLowerCase() == 'sunday' || value.day.toLowerCase() == 'saturday'){
                    $('#dtr'+countrow).append(
                        ' <span class="right badge badge-secondary float-right">'+value.day+'</span>'
                    )
                }else{
                    $('#dtr'+countrow).append(
                        ' <span class="right badge badge-default float-right">'+value.day+'</span>'
                    )
                }
                countrow+=1;
            });
        }
    })
})
            $(document).on('keypress', '.employee-remarks',function (e) {
                if (e.which == 13) {
                    var thismodal = $(this).closest('.modal');
                    $.ajax({
                        url: "/empdtr/updateremarks",
                        type: "get",
                        data: {
                            id: '{{$myid}}',
                            selecteddate: $(this).attr('data-date'),
                            remarks  : $(this).val()
                        },
                        success: function (data) {
                            if(data == 1)
                            {
                                toastr.success('Updated successfully!')
                                thismodal.find('.btn-close').click();
                                $('.modal-backdrop').remove()
                            }
                        }
                    });
                    return false;    //<---- Add this line
                }
            });
})
  </script>
@endsection