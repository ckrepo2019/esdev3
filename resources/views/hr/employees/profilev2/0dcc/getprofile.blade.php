
    
    @php
        $number = rand(1,3);
        if($employeeinfo->gender == null){
            $avatar = 'assets/images/avatars/unknown.png';
        }
        else{
            if(strtoupper($employeeinfo->gender) == 'FEMALE'){
                $avatar = 'avatar/T(F) '.$number.'.png';
            }
            else{
                $avatar = 'avatar/T(M) '.$number.'.png';
            }
        }
    @endphp
    <style>
      
.g-3, .gy-3 {
    --bs-gutter-y: 1rem;
}
.g-3, .gx-3 {
    --bs-gutter-x: 1rem;
}
.w220 {
    width: 220px;
}
@media (min-width: 768px)
{
.pe-md-2 {
    padding-right: 0.5rem !important;
}
}
@media (min-width: 576px)
{
  
.pe-sm-4 {
    padding-right: 1.5rem !important;
}
.text-center {
    text-align: center !important;
}
.pe-4 {
    padding-right: 1.5rem !important;
}
}
*, *::before, *::after {
    box-sizing: border-box;
}
    </style>
    <div class="card card-nav-header" style="border-radius: 10px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
      <div class="card-header p-2" style="font-size: 14px;">
        <div class="row">
          <div class="col-md-6">
            <button type="button" class="btn btn-sm btn-default text-black" id="btn-back" style="border-radius: 10px;"><i class="fa fa-arrow-left text-black"></i> Back</button>            
          </div>
          <div class="col-md-6 text-right">
            <button type="button" class="btn btn-sm btn-default text-black" id="btn-reload-profile" style="border-radius: 10px;"><i class="fa fa-sync text-black"></i> Reload</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card teacher-card  mb-3" style=" box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important; border-radius: 10px;">
      <div class="card-body d-flex teacher-fulldeatil">
          <div class="profile-teacher pe-xl-4 pe-md-2 pe-sm-4 pe-4 text-center w220">
              <a href="#">
                <img class="elevation-2" src="{{asset($employeeinfo->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}" id="profilepic" style="width:100px;height:100px;"  onerror="this.onerror = null, this.src='{{asset($avatar)}}'" alt="User Avatar">
              </a>
              <div class="about-info d-flex align-items-center mt-3 justify-content-center flex-column">
                  <h6 class="mb-0 fw-bold d-block fs-6">{{$employeeinfo->isactive == 1 ? 'Active' : ''}}</h6>
                  <span class="text-muted small">Employee ID : &nbsp;{{$employeeinfo->tid}}</span>
                  <span class="text-muted small">RFID : &nbsp;{{$employeeinfo->rfid}}</span>
              </div>
          </div>
          <div class="teacher-info border-start ps-xl-4 ps-md-4 ps-sm-4 ps-4 w-100">
              <h6 class="mb-0 mt-2  fw-bold d-block fs-6">{{$employeeinfo->title != null ? $employeeinfo->title.'. ' : ''}}{{$employeeinfo->firstname}} {{$employeeinfo->middlename != null ? $employeeinfo->middlename.' ' : ''}} {{$employeeinfo->lastname}} {{$employeeinfo->suffix}}</h6>
              <span class="py-1 fw-bold small-11 mb-0 mt-1 text-muted">{{$employeeinfo->designation}}</span><br/>
              {{-- <small>Account Status</small> <span class="badge badge-success">{{$employeeinfo->isactive == 1 ? 'Active' : ''}}</span><br/> --}}
              <small class="text-muted">Employment Status</small> @if($employeeinfo->employmentstatus == null || $employeeinfo->employmentstatus == 0 || $employeeinfo->employmentstatus == "")<span class="badge badge-secondary">Unset</span> @else <span class="badge badge-primary">{{$employeeinfo->employmentstatus}}</span> @endif
              {{-- <p class="mt-2 small">The purpose of lorem ipsum is to create a natural looking block of text (sentence, paragraph, page, etc.) that doesn't distract from the layout. A practice not without controversy</p> --}}
              <div class="row g-2 pt-2">
                  <div class="col-xl-5">
                      <div class="d-flex align-items-center">
                          <i class="fa fa-phone"></i>&nbsp;&nbsp;
                          <span class="ms-2 small">{{$employeeinfo->contactnum}}</span>
                      </div>
                  </div>
                  <div class="col-xl-5">
                      <div class="d-flex align-items-center">
                          <i class="fa fa-envelope"></i>&nbsp;&nbsp;
                          <span class="ms-2 small">{{$employeeinfo->email}}</span>
                      </div>
                  </div>
                  <div class="col-xl-5">
                      <div class="d-flex align-items-center">
                          <i class="fa fa-birthday-cake"></i>&nbsp;&nbsp;
                          <span class="ms-2 small">{{$employeeinfo->dob != null ? date('m/d/Y', strtotime($employeeinfo->dob)) : ''}}</span>
                      </div>
                  </div>
                  <div class="col-xl-5">
                      <div class="d-flex align-items-center">
                          <i class="fa fa-address-book"></i>&nbsp;&nbsp;
                          <span class="ms-2 small">{{$employeeinfo->address}}</span>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
    {{-- <div class="row d-flex align-items-stretch">
        <div class="col-md-9">
              <div class="card h-100" style="background-color: #ababab; border-radius: 10px;">
                  <div class="card-body">
                      <div class="row">
                          <div class="col-3 pl-3 pr-3 align-self-center" style="text-align: center;">
                              <div id="upload-demo-i" style="width: 100px; border: 2px solid white;text-align: center;
                              display: block;">
                                  <img class="elevation-2" src="{{asset($employeeinfo->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}" id="profilepic" style="width:100px;height:100px;"  onerror="this.onerror = null, this.src='{{asset($avatar)}}'" alt="User Avatar">
                              </div>
                          </div>
                          <div class="col-9 text-white pl-3 pr-3">
                              <h4>{{$employeeinfo->title != null ? $employeeinfo->title.'. ' : ''}}{{$employeeinfo->firstname}} {{$employeeinfo->middlename != null ? $employeeinfo->middlename.' ' : ''}} {{$employeeinfo->lastname}} {{$employeeinfo->suffix}} </h4>
                              <h6>{{$employeeinfo->designation}}</h6>
                              <small class="text-white">Address: {{$employeeinfo->address}}</small><br/>
                              <small>Account Status</small> <span class="badge badge-success">{{$employeeinfo->isactive == 1 ? 'Active' : ''}}</span><br/>
                              <small>Employment Status</small> @if($employeeinfo->employmentstatus == null || $employeeinfo->employmentstatus == 0 || $employeeinfo->employmentstatus == "")<span class="badge badge-secondary">Unset</span> @else <span class="badge badge-primary">{{$employeeinfo->employmentstatus}}</span> @endif
                          </div>
                      </div>
                  </div>
              </div>
        </div>
        <div class="col-md-3">
          <div class="card h-100" style="background-color: white; border-radius: 10px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
            <div class="card-body">
              <div class="row">
                <div class="col-3 align-self-center" style="border: 1px solid #ddd; border-radius: 10px;">
                    
                    <i class="fa fa-envelope m-2"></i>
                </div>
                <div class="col-9">
                  <div><small class="text-muted">Email Address</small></div>
                <div><small class="text-bold text-primary">{{$employeeinfo->email}}</small></div>
                </div>
              </div>
              <div class="row">
                <div class="col-3 align-self-center" style="border: 1px solid #ddd; border-radius: 10px;">
                    
                    <i class="fa fa-phone m-2"></i>
  
                </div>
                <div class="col-9">
                  <div><small class="text-muted">Mobile number</small></div>
                <div><small class="text-bold text-primary">{{$employeeinfo->contactnum}}</small></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> --}}
      <div class="card mt-3" style="background-color: white; border-radius: 10px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
        <div class="card-header p-0" style="font-size: 14px;">
          <ul class="nav nav-pills ml-auto p-2">
          <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab" style="border-radius: 25px !important;">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab" style="border-radius: 25px !important;">DTR</a></li>
          {{-- <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab" style="border-radius: 25px !important;">Salary Information</a></li>
          <li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab" style="border-radius: 25px !important;">Deductions</a></li>
          <li class="nav-item"><a class="nav-link" href="#tab_5" data-toggle="tab" style="border-radius: 25px !important;">Allowances</a></li>
          <li class="nav-item"><a class="nav-link" href="#tab_6" data-toggle="tab" style="border-radius: 25px !important;">Documents</a></li> --}}
          <li class="nav-item"><a class="nav-link" href="#tab_7" data-toggle="tab" style="border-radius: 25px !important;">Personal Information</a></li>
          </ul>
        </div>
      </div>
      
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">            
          <div class="row d-flex align-items-stretch">
            <div class="col-md-4">
              <div class="card h-100" style="background-color: white; border-radius: 5px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
                <div class="card-header p-1"><small><i class="fa fa-clock"></i> TIME TRACKED</small></div>
                <div class="card-body p-1"></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card h-100" style="background-color: white; border-radius: 5px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
                <div class="card-header p-1"><small><i class="fa fa-clock"></i> DAY OFF</small></div>
                <div class="card-body p-1"></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card h-100" style="background-color: white; border-radius: 5px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
                <div class="card-header p-1"><small><i class="fa fa-clock"></i> LEAVE</small></div>
                <div class="card-body p-1"></div>
              </div>
            </div>
          </div>     
          <div class="row d-flex align-items-stretch mt-3">
            <div class="col-md-6">
              <div class="card h-100" style="background-color: white; border-radius: 5px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
                <div class="card-header p-1"><small>TIME LOGS</small></div>
                <div class="card-body p-1"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card h-100" style="background-color: white; border-radius: 5px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
                <div class="card-header p-1"><small>Time Off Requests</small></div>
                <div class="card-body p-1"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab_2">
          <div class="card" style="background-color: white; border-radius: 5px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
            <div class="card-header p-1">
              <div class="row">
                <div class="col-md-4">
                  <input type="text" name="period"  class="form-control" id="dtrdaterange" value="{{date('m-1-Y')}} - {{date('m-t-Y')}}">
                  {{-- <select class="form-control form-control-sm">
                    <option value=""></option>
                  </select> --}}
                </div>
                <div class="col-md-8 text-right align-self-end">
                    <h5 class="text-muted" id="date-string">{{date('F 01, Y')}} - {{date('F t, Y')}}</h5>
                </div>
              </div>
            </div>
            <div  id="container-dtr-results" class="card-body p-1"></div>
          </div>
        </div>
        {{-- <div class="tab-pane" id="tab_3"></div>
        <div class="tab-pane" id="tab_4"></div>
        <div class="tab-pane" id="tab_5"></div>
        <div class="tab-pane" id="tab_6"></div> --}}
        <div class="tab-pane" id="tab_7"></div>
      </div>
      <script>
        $('#dtrdaterange').daterangepicker({
            locale: {
                format: 'MM-DD-YYYY'
            }
          })
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
            $('#container-dtr-results').empty()
            $.ajax({
                url: "/dtr/attendance/index",
                type: "GET",
                data: {
                    dateperiod: _thisdateperiod,
                    employeeid: '{{$employeeid}}',
                    action: 'getattendance'
                },
                success: function (data) {
                  var dateAr = _thisdateperiod.split(' - ');
                  var datefrom = dateAr[0];
                  var dateto = dateAr[1];
                  var newdatefrom = new Date(datefrom);
                  var newdateto = new Date(datefrom);
                  newdatefrom = newdatefrom.toString('M d, yyyy')
                  console.log(newdatefrom)
                    thistable = $(data).find('table');
                    thistable = thistable.attr('id','table-dtr');
                    $('#container-dtr-results').append(thistable)
                    // $('#container-dtr-results').append(data)
                    // var thistable = $('#container-dtr-results').find('table').attr('id','table-dtr')
                    var thistrs = $('#table-dtr thead').find('tr')
                    thistrs.each(function(key, val){
                      if(key == 0)
                      {
                        var thisths = $(val).find('th');
                        thisths.each(function(thkey, thval){
                          if(thkey > 2)
                          {
                            $(thval).css('display','none')
                          }
                        })
                      }
                    })
                    // $('#table-dtr th:nth-child(4)').hide();
                    // $('#table-dtr th:nth-child(5)').hide();
                    $('#table-dtr th:first-child').css('width','40% !important');
                    $('#table-dtr td:nth-child(6)').hide();
                    $('#table-dtr td:nth-child(7)').hide();
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            });
        }
          $('a[href="#tab_2"]').on('click', function(){
            getAttendance($('#dtrdaterange').val())
            $('#dtrdaterange').on('change', function(){
                getAttendance($(this).val())
            })
          })
        $(document).on('click','#btn-exporttopdf', function(){
            window.open('/dtr/attendance/index?action=exporttopdf&dateperiod='+$('#dtrdaterange').val()+'&employeeid={{$employeeid}}','_blank')
        })
      </script>