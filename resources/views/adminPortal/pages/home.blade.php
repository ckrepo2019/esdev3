
@extends('adminPortal.layouts.app2')

@section('content')
<style>
.afinance .card-body, .aregistrar .card-body, .aprincipal .card-body{
  height: 120px;
  /* margin: auto; */
}
.card-header {
  text-shadow: 1px 1px 3px gray;
}
.afacstaff .card-body, .astudentlist .card-body{
  height: 158px;
  overflow-y: scroll;
  font-size: 14px;
}
.aschoolcalendar .card-body{
  height: 150px;
}
.afacstaff .card-header {
  background-color: #fd7e14;
  color: #fff;
}
.astudentlist .card-header {
  background-color: #d81b60;
  color: #fff;
}

.btn-app {
  color: #fff;
  border: none;
  width: 100px;
  border-radius: 3px;
  background-color: transparent!important;
  font-size: 12px;
  height: 60px;
  margin: 0 0 10px 10px;
  min-width: 80px;
  padding: 15px 5px;
  position: relative;
  text-align: center;
} 
.btn-app span{
  color: #fff;
  font-size: 13px;
  font-weight: 600;
}
.btn-app i {
  -webkit-text-stroke-width: .3px;
  -webkit-text-stroke-color: #713333;
  transition: .3s;
  color: #ffc107;
 
}
.btn-app:hover i{
  font-size: 35px;
  transition: .3s;
}
.small-box-footer {
  color: #fff;
}
</style>
<section class="content-header">
</section>
<section class="content p-0">
  <div class="row container">
    <!-- <div class="col-lg-5">
    <div class="col-md-12 afinance">
    <div class="card  bg-success">
        <div class="card-header bg-success">
        <h3 class="card-title"><i style="color: #ffc107" class="fas fa-coins"></i> <b>FINANCE</b></h3>
        </div>
        <div class="card-body" style="overflow-y: scroll">
          <a href="#" class="btn btn-app">
            <i class="fas fa-cubes"></i> <b><span>Account Recievables</span></b>
          </a>
          <a href="#" class="btn btn-app">
            <i class="fas fa-exchange-alt"></i> <b><span>Daily Transaction</span></b>
          </a>
          <a href="#" class="btn btn-app">
            <i class="fas fa-money-bill-wave"></i> <b><span>Cash Reciept Summary</span></b>
          </a>
        </div>
        <div class="card-footer">
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    </div>

    <div class="col-md-12 aregistrar">
    <div class="card bg-info">
        <div class="card-header bg-info">
        <h3 class="card-title"><i style="color: #ffc107" class="fas fa-user-edit"></i> <b>REGISTRAR</b></h3>
        </div>
        <div class="card-body" style="overflow-y: scroll">
          <a href="#" class="btn btn-app">
            <i class="fas fa-clipboard"></i> <b><span>Reports</span></b>
          </a>
          <a href="#" class="btn btn-app">
            <i class="fas fa-th-list"></i> <b><span>Strand List</span></b>
          </a>
          <a href="#" class="btn btn-app">
            <i class="fas fa-list-alt"></i> <b><span>Track List</span></b>
          </a>
        </div>
        <div class="card-footer">
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    </div>

    <div class="col-md-12 aprincipal">
    <div class="card bg-gray">
        <div class="card-header bg-gray">
        <h3 class="card-title"><i style="color: #ffc107" class="fas fa-users"></i> <b>PRINCIPAL</b></h3>
        </div>
        <div class="card-body" style="overflow-y: scroll">
          <a href="#" class="btn btn-app">
            <span class="badge bg-info">12</span>
            <i class="fas fa-chalkboard-teacher"></i> <b><span>Teachers</span></b>
          </a>

          <a href="#" class="btn btn-app">
            <span class="badge bg-info">12</span>
            <i class="fas fa-user"></i> <b><span>Students</span></b>
          </a>

          <a href="#" class="btn btn-app">
            <span class="badge bg-info">12</span>
            <i class="fas fa-file-alt"></i> <b><span>Subjects</span></b>
          </a>
        </div>
        <div class="card-footer">
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    </div>
    </div> -->
    <div class="col-lg-4">
        <div class="col-md-12">
          <div class="info-box mb-3" style="background:#1d8836">
              <span class="info-box-icon text-white"><b>SY</b></span>

              <div class="info-box-content">
                <h6 class="info-box-text"><i style="color: #ffc107" class="fas fa-tag"></i> <span  style="color: #ffc107; text-shadow: 1px 1px 4px #000">ACTIVE SCHOOLYEAR</span></h6>
                @foreach($sydetails as $items)
                <h2 class="info-box-number text-white">{{$items->sydesc}}</h2>
                @endforeach
              </div>
              <!-- /.info-box-content -->
            </div>
            
        </div>

        <div class="col-12 col-md-12">

        <div class="card">
        <div class="card-header" style="background-color: #0c7080">
        <h3 class="card-title"><i style="color: #ffc107" class="fas fa-list"></i> <b>LIST</b></h3>
        
        </div>
        <!-- /.card-header -->
        <div class="card-body pt-0">
        <table class="table table-hover table-sm" style="table-layout:fixed;">
          <tbody>
              <div class="col-12 col-sm-12 col-md-12 pt-3">
                <div class="info-box mb-3 bg-success">
                  <span class="info-box-icon bg-white elevation-1">{{$roomscount}}</span>

                  <div class="info-box-content">
                    <span class="info-box-number"><i style="color: #ffc107" class="fas fa-door-open"></i> Rooms</span>
                  </div>
                </div>
              </div>


              <div class="col-12 col-sm-12 col-md-12">
                <div class="info-box mb-3 bg-primary">
                  <span class="info-box-icon bg-white elevation-1">{{$scaldetailscount}}</span>

                  <div class="info-box-content">
                    <span class="info-box-number"><i style="color: #ffc107" class="fas fa-calendar-week"></i> School Events</span>
                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-12 col-md-12">
                <div class="info-box mb-3 bg-info">
                  <span class="info-box-icon bg-white elevation-1">{{$adsdetailscount}}</span>

                  <div class="info-box-content">
                    <span class="info-box-number"><i style="color: #ffc107" class="fas fa-bookmark"></i> Advertisement</span>
                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-12 col-md-12">
                <div class="info-box mb-3 bg-danger">
                  <span class="info-box-icon bg-white elevation-1">{{$fstafcount}}</span>

                  <div class="info-box-content">
                    <span class="info-box-number"><i style="color: #ffc107" class="fas fa-users"></i> Faculty and Staff</span>
                  </div>
                </div>
              </div>
          </tbody>
        </table>
        </div>
        <!-- /.card-body -->
        
    </div>
          <!-- <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1">{{$roomscount}}</span>

            <div class="info-box-content">
              <span class="info-box-text"><i style="color: #ffc107" class="fas fa-door-open"></i> ROOMS</span>
              <span class="info-box-number">GRADE SCHOOL</span>

            </div>
          </div> -->
        </div>

    </div>
    <!--  -->
    <div class="col-lg-8">
    <div class="col-md-12 afacstaff">
    <div class="card">
        <div class="card-header">
        <h3 class="card-title"><i style="color: #ffc107" class="fas fa-users"></i> <b>FACULTY AND STAFF</b></h3>
        <!-- <div class="input-group input-group-sm w-50 float-right search">
          <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search">
          <div class="input-group-append">
              <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
          </div>
        </div> -->
        </div>
        <!-- /.card-header -->
        <div class="card-body pt-0">
        <table class="table table-hover table-sm  p-0" style="min-width:500px; table-layout:fixed;">
          <thead>
            <tr>
              <th width="20%">Email</th>
              <th>Name</th>
              <th>Designation</th>
            </tr>
          </thead>
          <tbody>
              @foreach($fsinfo[0]->data as $item)
              <tr>
                <td>{{$item->email}}</td>
                <td><a href="#">{{$item->firstname}} {{$item->middlename}} {{$item->lastname}} {{$item->suffix}}</a></td>
                <td>{{$item->utype}}</td>
              </tr> 
              @endforeach 
          </tbody>
        </table>
        </div>
        <!-- /.card-body -->
        
    </div>
    <!-- /.card -->
    </div>
    <div class="col-md-12 astudentlist">
    <div class="card">
        <div class="card-header">
        <h3 class="card-title"><i style="color: #ffc107" class="fas fa-users"></i> <b>STUDENT LIST</b></h3>
        <!-- <div class="input-group input-group-sm w-50 float-right search">
          <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search">
          <div class="input-group-append">
              <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
          </div>
        </div> -->
        </div>
        <!-- /.card-header -->
        <div class="card-body pt-0">
        <table class="table table-hover table-sm" style="min-width:500px; table-layout:fixed;">
          <thead>
            <tr>
              <th>Level</th>
              <th>Total Numbers</th>
            </tr>
          </thead>
          <tbody>
              @foreach($glevel as $item)
              <tr>
                <td><a href="#">{{$item->levelname}}</a></td>
                <center><td>{{$item->studCount}}</td></center>
              </tr> 
              @endforeach
          </tbody>
        </table>
        </div>
        <!-- /.card-body -->
        
    </div>
    <!-- /.card -->
    </div>

    <div class="col-md-12 aschoolcalendar"><div class="card">
      <div class="card-header bg-primary">
      <h3 class="card-title"><i style="color: #ffc107" class="fas fa-calendar"></i> <b>School Calendar</b></h3>
        <!-- <div class="input-group input-group-sm w-50 float-right search">
          <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search">
          <div class="input-group-append">
              <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
          </div>
        </div> -->
      </div>
      <div class="card-body table-responsive p-0 " id="dataholder">
      <input type="hidden" value="0" id="searchCount">



      <table class="table table-hover smfont table-sm" style="min-width:500px; table-layout:fixed;">
        <thead>
          <tr>
            <!-- <th width="2%"></th>
            <th width="20%" class="appadd">DATE</th>
            <th width="10%" class="appadd">DAY</th> -->
            <th width="50%" class="appadd">DESCRIPTION</th>
            <th width="50%" class="appadd">TYPENAME</th>
            <!-- <th width="23%">TYPE</th>
            <th width="8%"></th>
            <th width="8%"></th> -->
          </tr>
        </thead>
        <tbody>
          @foreach($schoolcalendardetails as $item)
            <tr>
                <td class="text-primary">{{$item->description}}</td>
                <td>{{$item->typename}}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
          </div>
          <!-- <div class="card-footer">
            <div class="mt-3" id="data-container">
          <div class="paginationjs" style="display: none;"><div class="paginationjs-pages"><ul><li class="paginationjs-prev disabled"><a>«</a></li><li class="paginationjs-next disabled"><a>»</a></li></ul></div></div></div>
        </div> -->
      </div>
        
    <!-- /.card -->
    </div>
    </div>
    <!--  -->
    <div class="col-lg-12">
    

    <!-- <div class="col-md-12 anotification">
    <div class="card">
        <div class="card-header">
        <h3 class="card-title"><i style="color: #ffc107" class="fas fa-users"></i> <b>NOTIFICATION</b></h3>
        </div>
        <div class="card-body">
            <h6><b>REPORTS</b></h6>
        </div>
        
    </div>
    </div> -->
    </div>









  </div>
</section>
<script>
    $(document).ready(function(){
     
    });
</script>
@endsection

