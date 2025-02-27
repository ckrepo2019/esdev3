
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
  
      <style>
            .dropdown-toggle::after {
                  display: none;
                  margin-left: .255em;
                  vertical-align: .255em;
                  content: "";
                  border-top: .3em solid;
                  border-right: .3em solid transparent;
                  border-bottom: 0;
                  border-left: .3em solid transparent;
            }
            .select2-container .select2-selection--single {
            height: 40px;
        }

      </style>

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
                              <li class="breadcrumb-item active">Password Generator</li>
                        </ol>
                        </div>
                  </div>
            </div>
      </section>
      <section class="content pt-0">
            <div class="container-fluid">
                  <div class="row">
                        <div class="col-12">
                              <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Select Usertype</label>
                                                <select id="selectusertypeid" class="form-control select2 select2-primary" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                    @foreach($usertypes as $usertype)
														@if($usertype->id == 7)
                                                        <option value="{{$usertype->id}}" selected>{{$usertype->utype}}</option>
														@endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-8 text-right">
                                                <label>&nbsp;</label><br/>
                                                <button type="button" class="btn btn-primary" id="btn-filter"><i class="fa fa-filter"></i> Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive" id="resultscontainer" style="min-height:539px">
                                        
                                    </div>
                                    {{-- <div class="card-footer">
                                          <div class="" id="data-container">
                                          </div>
                                    </div>  --}}
                              </div>   
                        </div>
                  </div>
            </div>
      </section>
@endsection

@section('footerjavascript')
      <script src="{{asset('js/pagination.js')}}"></script> 
      <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
      <!-- DataTables -->
      <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
      <!-- DataTables  & Plugins -->
      <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
      <!-- Select2 -->
      <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>

      <script>
          $('#resultscontainer').hide();
        $(function () {
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
            theme: 'bootstrap4'
            })

        });
        $(document).ready(function(){
            $('#btn-filter').on('click', function(){
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })

                $.ajax({
                    url: '/passwordgenerator',
                    type: 'GET',
                    data: {
                        usertypeid  : $('#selectusertypeid').val(),
                        action      : 'filter'
                    },
                    
                    success:function(data){
                        $('#resultscontainer').empty();
                        $('#resultscontainer').append(data)
                        $('#resultscontainer').show();
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        $("#userstable").DataTable({
                        aLengthMenu: [
                            [25, 50, 100, 200, -1],
                            [25, 50, 100, 200, "All"]
                        ],
                        iDisplayLength: -1,
                        "responsive": true, "lengthChange": false, "autoWidth": false,
                        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                        });
                        $('.passwordstr').prop('disabled',true)
                    }
                })
            })
            $(document).on('dblclick','.passwordstr',function () {
                $('.passwordstr').prop('disabled', true)
                $(this).removeAttr('disabled');
                $(this).focus()
            })
            $(document).on('click', '.generate', function(){
                var thiselement = $(this).closest('.form-group').find('input');
                var userid = thiselement.attr('data-id')
                $.ajax({
                    url: '/passwordgenerator',
                    type: 'GET',
                    data: {
                        action      : 'generatepassword',
                            userid      : userid
                    },
                    datatype        : 'json',
                    success:function(data){
                        thiselement.val(data.code)
                    }
                })
            })
            function generatePassword()
            {
                if($('.passwordstr[generated="0"]').length>0)
                {
                    var thiselement = $($('.passwordstr[generated="0"]')[0]);
                    var userid = thiselement.attr('data-id');
                    $.ajax({
                        url: '/passwordgenerator',
                        type: 'GET',
                        data: {
                            action      : 'generatepassword',
                            userid      : userid
                        },
                        datatype        : 'json',
                        success:function(data){
                            thiselement.val(data.code)
                            thiselement.attr('hashed', data.hash)
                            thiselement.attr('generated','1')
                            generatePassword()
                        }
                    })
                }else{
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                }
                // })
            }

            $(document).on('click','#btn-generateall', function(){
                generatePassword()
                Swal.fire({
                    title: 'Generating password...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
            })
            $(document).on('click', '#btn-export', function(){
                var usertypeid  = $('#selectusertypeid').val();
                
                window.open("/passwordgenerator?action=export&usertypeid="+usertypeid);
            })
        })
      </script>
  
    
    
@endsection

