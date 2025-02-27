

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
@extends('hr.layouts.app')
@section('content')
<style>
    .mobile{
        display: none;
    }
    @media only screen and (max-width: 600px) {
        .mobile {
            display: block;
        }
        .web {
            display: none;
        }
    }

</style>
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Salary setup</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Salary setup</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
        <!-- START ALERTS AND CALLOUTS -->
        <div class="row">
            <div class="col-md-4">
              <div class="card">
                  <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="card-header">
                    <button type="button" class="btn btn-sm text-success float-right" id="addsalarybutton" clicked="0"><i class="fa fa-plus"></i>&nbsp; Add salary type</button>
                </div>
            </div>
            <form action="/salary/{{Crypt::encrypt('addsalary')}}" method="get">
                <div id="addsalarycontainer">
                </div>
            </form>
              <!-- /.card -->
            </div>
          <div class="col-md-8">
            <div class="card">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="card-header">
                    &nbsp;
                </div>
                <div class="card-body">
                    {{-- <ul class="nav flex-column"> --}}
                        @foreach($salaries as $salary)
                        <form action="/updatedeductions/{{Crypt::encrypt('editdeduction')}}" class="m-0 formsalary" method="get">
                        {{-- <li class="nav-item"> --}}
                                {{-- <a href="#" class="nav-link"> --}}
                                    <div class="row mb-4 eachrow">
                                        <div class="col-md-5 col-12">
                                            <input type="hidden" class="form-control form-control-sm mb-2" name="salaryid" value="{{$salary->id}}" style="display: inline-block; position:relative" readonly required/>
                                            <input type="text" class="form-control form-control-sm mb-2" name="editsalarydescription" value="{{$salary->description}}" style="display: inline-block; position:relative" readonly required/>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                <span class="input-group-text p-0">
                                                    <i class="nav-icon">&nbsp;&#8369;&nbsp;</i>
                                                </span>
                                                </div>
                                                <input type="number" class="form-control form-control-sm" name="editsalaryamount" value="{{$salary->amount}}" style="display: inline-block; position:relative" readonly required/>
                                          </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="web buttonscontainer">
                                                <span class="btn btn-warning btn-sm editsalarydescriptionbutton ml-2">Edit</span> <span class="btn btn-sm btn-danger deletesalarydescriptionbutton" >Delete</span>
                                            </div>
                                            <span class="mobile buttonscontainer">
                                                <div class="row mobilecontainer">
                                                    <div class="col-6">
                                                        <span class="btn btn-warning btn-sm btn-block editsalarydescriptionbutton">Edit</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <span class="btn btn-sm btn-block btn-danger deletesalarydescriptionbutton" >Delete</span>
                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                {{-- </a> --}}
                        {{-- </li> --}}
                    </form>
                        @endforeach
                    {{-- </ul> --}}

                </div>
              </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
      </div>
  </section>
  <input type="hidden" name="deleteid" value="{{Crypt::encrypt('deletedeductiontype')}}"/>
  <input type="hidden" name="adddeductiondetail" value="{{Crypt::encrypt('adddeductiondetail')}}"/>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script>
    $(document).ready(function(){
        // ===============================================================================================
        // ===============================================================================================
        // ===============================================================================================

        var addsalarycard = 0;
        $(document).on('click','#addsalarybutton', function(){
            // console.log('ads');
            if(addsalarycard == 0){
                $('#addsalarycontainer').append(
                    '<div class="card">'+
                        '<button type="submit" class="btn btn-block btn-success savesalarybutton">Save</button>'+
                    '</div>'
                );
                $('#addsalarycontainer').prepend(
                    '<div class="card">'+
                        '<div class="card-header">'+
                            '<div class="card-tools">'+
                                // '<button type="button" class="btn btn-tool" data-card-widget="collapse">'+
                                //     '<i class="fas fa-minus"></i>'+
                                // '</button>'+
                                '<button type="button" class="btn btn-tool removesalarycard" data-card-widget="remove">'+
                                    '<i class="fas fa-times"></i>'+
                                '</button>'+
                            '</div>'+
                        '</div>'+
                        '<div class="card-body">'+
                            '<small><strong>Salary description</strong></small>'+
                            '<input type="text" name="salarydescription[]" class="form-control form-control-sm mb-2" placeholder="Salary description" required/>'+
                            '<small><strong>Salary per Month</strong></small>'+
                            '<input type="number" name="amount[]" class="form-control form-control-sm" placeholder="Amount" required/>'+
                        '</div>'+
                    '</div>'
                );
            }
            else if(addsalarycard > 0){
                $('#addsalarycontainer').prepend(
                    '<div class="card">'+
                        '<div class="card-header">'+
                            '<div class="card-tools">'+
                                '<button type="button" class="btn btn-tool removesalarycard" data-card-widget="remove">'+
                                    '<i class="fas fa-times"></i>'+
                                '</button>'+
                            '</div>'+
                        '</div>'+
                        '<div class="card-body">'+
                            '<small><strong>Salary description</strong></small>'+
                            '<input type="text" name="salarydescription[]" class="form-control form-control-sm mb-2" placeholder="Salary description" required/>'+
                            '<small><strong>Salary per Month</strong></small>'+
                            '<input type="number" name="amount[]" class="form-control form-control-sm" placeholder="Amount" required/>'+
                        '</div>'+
                    '</div>'
                );
            }
            addsalarycard+=1;
        });
        $(document).on('click','.removesalarycard', function(){
            addsalarycard-=1;
            if(addsalarycard == 0){
                $('#addsalarycontainer').empty();
            }
        });
        var thisrow ="";
        $(document).on('click','.editsalarydescriptionbutton', function(){
            $('.savebutton').remove();
            $('.editsalarydescriptionbutton').show();
            $('.deletesalarydescriptionbutton').show();
            $(this).closest('.eachrow').find('input').removeAttr('readonly');
            $(this).closest('.buttonscontainer').append(
                '<div class="col-12"><button type="submit" class="btn btn-sm btn-success savebutton btn-block m-0" >Update</button></div>'
            );
            // console.log()
            if($(this).closest('.buttonscontainer').hasClass('mobile') == true){
                $(this).closest('.web').remove();
                $(this).closest('.mobilecontainer').find('.deletesalarydescriptionbutton').hide();
            }else{
                $(this).closest('.mobilecontainer').remove();
                $(this).find('.deletesalarydescriptionbutton').hide();
                $(this).next('.deletesalarydescriptionbutton').hide();
            }
            $(this).hide();

        })
    })
  </script>
@endsection

