
@extends('registrar.layouts.app')
@section('content')
<!-- DataTables -->
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
    <style>
        
        .select2-container .select2-selection--single {
            height: 40px !important;
        }
        
        #modal-adddata .modal-dialog{
            max-width: 800px
        }
        td, th {
            padding: 1px !important;
        }
    </style>
     <h3 class="m-0"><strong>Students' Display Photos</strong></h3>
     {{-- <div class="card" style="border:none !important; box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%) !important;">
         <div class="card-header">

         </div>
     </div> --}}
     <div class="card mt-2">
         <div class="card-header">
            <div class="row mb-2">
                <div class="col-md-6">
                    <label>Select Student</label>
                    <select class="form-control select2" id="select-studentid">
                        @foreach($students as $student)
                            <option value="{{$student->id}}">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
         </div>
     </div>
    <div id="div-results">
    </div>
    <div id="div-newcards">
    </div>
    
    <script>
        
        $(function () {
            $('.select2').select2()
            $('#example2').DataTable({
              "paging": false,
              "lengthChange": true,
              "searching": true,
              "ordering": false,
              "info": true,
              "autoWidth": false,
              "responsive": true,
            });
        });
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            function getphoto()
            {
                Swal.fire({
                    title: 'Fetching data...',
                    allowOutsideClick: false,
                    closeOnClickOutside: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                })
                $.ajax({
                    url: '/setup/studdisplayphoto/getphoto',
                    type: 'GET',
                    data: {
                        id: $('#select-studentid').val()
                    },
                    success:function(data)
                    {
                        $('#div-results').empty()
                        $('#div-results').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                }); 
            }
            getphoto();
            $('#select-studentid').on('change', function(){
                getphoto();
            })
            
        // ------------------------------------------------------------------------------------ CHANGE PROFILE PICTURE
        })
   
    </script>
@endsection

                                        

                                        
                                        