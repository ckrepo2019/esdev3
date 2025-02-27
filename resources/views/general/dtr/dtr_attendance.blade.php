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
                <h4 >Daily Time Record</h4>
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
<div class="card shadow" style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
    <div class="card-header">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="period"  class="form-control" id="dtrdaterange" value="{{date('m-1-Y')}} - {{date('m-t-Y')}}">
            </div>
            <div class="col-md-8 text-right">
                <h4 class="text-bold" id="date-string">{{date('F 01, Y')}} - {{date('F t, Y')}}</h4>
            </div>
        </div>
    </div>
</div>
<div id="container-results"></div>
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

        function getAttendance(_thisdateperiod)
        {
            Swal.fire({
                    title: 'Loading attendance...',
                    allowOutsideClick: false,
                    closeOnClickOutside: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
            })
            $('#container-results').empty()
            $.ajax({
                url: "/dtr/attendance/index",
                type: "GET",
                data: {
                    dateperiod: _thisdateperiod,
                    action: 'getattendance'
                },
                success: function (data) {
                    $('#container-results').append(data)
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                }
            });
        }
        getAttendance($('#dtrdaterange').val())
        $('#dtrdaterange').on('change', function(){
            getAttendance($(this).val())
        })
        $(document).on('click','#btn-exporttopdf', function(){
            window.open('/dtr/attendance/index?action=exporttopdf&dateperiod='+$('#dtrdaterange').val(),'_blank')
        })
            // $(document).on('keypress', '.employee-remarks',function (e) {
            //     if (e.which == 13) {
            //         var thismodal = $(this).closest('.modal');
            //         $.ajax({
            //             url: "/empdtr/updateremarks",
            //             type: "get",
            //             data: {
            //                 selecteddate: $(this).attr('data-date'),
            //                 remarks  : $(this).val()
            //             },
            //             success: function (data) {
            //                 if(data == 1)
            //                 {
            //                     toastr.success('Updated successfully!')
            //                     thismodal.find('.btn-close').click();
            //                     $('.modal-backdrop').remove()
            //                 }
            //             }
            //         });
            //         return false;    //<---- Add this line
            //     }
            // });
        })
  </script>
@endsection