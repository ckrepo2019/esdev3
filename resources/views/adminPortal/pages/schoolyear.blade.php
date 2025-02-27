
@extends('adminPortal.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <style>
        .smfont{
            font-size:14px;
        }
    </style>
@endsection

@section('modalSection')
    <div class="modal fade" id="modal-primary" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-info">
              <h4 class="modal-title">School Year Form</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <form action="/adminstoreschoolyear" method="GET" id="schoolyearform">
                <div class="modal-body">
                    <div class="row">
                        <input  class="form-control" name="si" id="si" hidden>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input  class="form-control" name="sdate"  id="sdate"   type="text"  value="" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>End Date</label>
                                <input  class="form-control" id="edate" name="edate" type="text" value="{{\Carbon\Carbon::now()->isoFormat('MM/DD/YYYY')}}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary savebutton" onClick="this.form.submit(); this.disabled=true;">Save</button>
                </div>
            </form>
          </div>
        </div>
    </div>
    <div class="modal fade" id="activateschoolyear" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="/setschoolyearactive" method="GET" id="schoolyearform">
              <input type="hidden" name="syid" id="syid">
              <div class="modal-body">
                  <h6>Copy the following data to next school year:</h6>
                  <br>
                  <div class="icheck-success d-inline">
                      <input type="checkbox" name="section_detail" id="section_detail">
                      <label for="section_detail">Section detail</label>
                  </div>
              </div>
              <div class="modal-footer justify-content-between">
                  <button type="submit" class="btn btn-primary savebutton">PROCEED</button>
              </div>
          </form>
        </div>
      </div>
  </div>

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
          <li class="breadcrumb-item active">School Year</li>
      </ol>
      </div>
  </div>
  </div>
</section>
<section class="content p-0">
    <div class="container-fluid">
        <div class="message">
            @if (\Session::has('error'))
                <div class="alert alert-danger alert-dismissible p-1 ">
                  <button type="button" class="close p-1 smfont" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5 class="mb-0"><i class="icon fas fa-ban"></i> {{\Session::get('error')[0]}}!</h5>
                </div>
            @endif
        </div>
      <!-- /.row -->
      <div class="row">
        <div class="col-12">
          <div class="card adminschoolyear">
            <div class="card-header bg-info">
            <span style="font-size: 16px"><b><i class="nav-icon fab fa-pushed"></i> SCHOOL YEAR</b></span>
              

              <div class="input-group input-group-sm float-right w-25 search" >
                <input type="text" name="table_search" class="form-control float-right" placeholder="Search" >

                <div class="input-group-append">
                  <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                </div>
              </div>
              <button class="btn btn-sm btn-primary float-right mb-2 mr-2" data-toggle="modal"  data-target="#modal-primary" title="Contacts" data-widget="chat-pane-toggle" ><b>ADD SCHOOL YEAR</b></button>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table class="table table-hover"  style="min-width:500px;">
                <thead>
                  <tr>
                    <th width="2%" class="p-0"></th>
                    <th width="28%" class="pl-4">School Year</th>
                    <th width="20%">Start Date</th>
                    <th width="20%">End Date</th>
                    <th width="40%" style="text-align: center">Status</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($schoolyear as $item)
                    <tr >
                      @if($item->isactive == 0)
                        <td class="bg-danger p-0"></td>
                      @else
                        <td class="bg-success p-0"></td>
                      @endif
                        <td class="align-middle p-1 pl-4">
                          {{-- <a href="javascript:void(0)" class="sy" id="{{Crypt::encrypt($item->id)}}"> --}}
                            {{$item->sydesc}}
                          {{-- </a> --}}
                        </td>
                        @if($item->sdate!='0000-00-00')
                            <td class="align-middle p-1">{{\Carbon\Carbon::create($item->sdate)->isoFormat('MMM DD, YYYY')}}</td>
                        @else
                            <td class="align-middle p-1">Not Set</td>
                        @endif

                        @if($item->edate!='0000-00-00')
                            <td class="align-middle p-1">{{\Carbon\Carbon::create($item->edate)->isoFormat('MMM DD, YYYY')}}</td>
                        @else 
                            <td class="align-middle p-1">Not Set</td>
                        @endif


                        <td>
                          @if(isset($latestRequest))
                            @if($latestRequest->reqid == $item->id && $item->isactive == 0)

                              <a href="/viewschoolyearinformation/{{Crypt::encrypt($item->id)}}" class="w-100 btn btn-sm btn-danger d-block d-block ">WITH PENDING REQUEST</a>

                              @else
                            

                              <a href="/viewschoolyearinformation/{{Crypt::encrypt($item->id)}}" class="w-100 btn btn-sm btn-primary d-block d-block ">VIEW INFORMATION</a>

                            @endif
                          @else
                          <a href="/viewschoolyearinformation/{{Crypt::encrypt($item->id)}}" class="w-100 btn btn-sm btn-primary d-block d-block ">VIEW INFORMATION</a>
                          @endif
                        </td>

                    </tr>   
                    @endforeach
                    
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
@endsection


@section('footerjavascript')

    <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

    <script>
      
      $(document).ready(function(){

        if($(window).width()<500){
            $('.search').addClass('w-50')
            $('.search').removeClass('w-25')
        }

        $(document).on('click','.syid-btn',function(){
            $('#syid').val($(this).attr('id'))
        })

      })
        $(function() {
            // console.log(moment().year());
            $('input[name="sdate"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: moment().year()-2,
                startDate: moment().add(5, 'day'),
                locale: {
                    format: 'MMM DD, YYYY'
                },
                maxYear: moment().add(1, 'years'),
            }, function(start, end, label) {
                var years = moment().diff(start, 'years');
            });
            
            $('input[name="edate"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: moment().year()-2,
                startDate: moment().add(5, 'day'),
                locale: {
                    format: 'MMM DD, YYYY'
                },
                maxYear: moment().add(1, 'years'),
            }, function(start, end, label) {
                var years = moment().diff(start, 'years');
            });
        });


        $(document).on('click','.sy',function(){

          $('#si').val($(this).attr('id'))

          if($(this).closest('tr')[0].children[2].innerHTML == 'Not Set'){
            d = new Date()
            $('#edate').val(moment(d).format('MMM DD, YYYY'))
            
          }
          else{
            $('#edate').val($(this).closest('tr')[0].children[3].innerHTML)
          }

          if($(this).closest('tr')[0].children[2].innerHTML == 'Not Set'){
            d = new Date()
            $('#sdate').val(moment(d).format('MMM DD, YYYY'))
          }
          else{
            $('#sdate').val($(this).closest('tr')[0].children[2].innerHTML)
          }
          
          var d = new Date($(this).closest('tr')[0].children[2].innerHTML)

          $('input[name="edate"]').data('daterangepicker').setStartDate($(this).closest('tr')[0].children[3].innerHTML )
          $('input[name="edate"]').data('daterangepicker').setEndDate($(this).closest('tr')[0].children[3].innerHTML )


          $('input[name="sdate"]').data('daterangepicker').setStartDate($(this).closest('tr')[0].children[2].innerHTML )
          $('input[name="sdate"]').data('daterangepicker').setEndDate($(this).closest('tr')[0].children[2].innerHTML )
        
          $('.savebutton').text('UPDATE')
          $('.savebutton').removeClass('btn-info')
          $('.savebutton').addClass('btn-success')
          $('#modal-primary').modal('show');
          $('#schoolyearform').attr('action', '/adminupdatesy');

      })
       
       
      $('#modal-primary').on('hidden.bs.modal', function () {

        $(function() {
            $('input[name="sdate"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: moment().year(),
                maxYear: moment().add(1, 'years'),
            }, function(start, end, label) {
                var years = moment().diff(start, 'years');
            });
            $('input[name="edate"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: moment().year(),
                maxYear: moment().add(1, 'years'),
            }, function(start, end, label) {
                var years = moment().diff(start, 'years');
            });
        });

        $(this).find('form').trigger('reset');
        $('.savebutton').text('SAVE');
        $("form").attr('action', '/adminstoreschoolyear');
      })

      // $(document).on('click','.ed',function(){
      //   console.log('hello');
      // })

      
    </script>

@endsection

