
@extends('ctportal.layouts.app2')

@section('pagespecificscripts')

@endsection

@section('content')

      <section class="content">
            <div class="card col-md-12 container-fluid bg-primary">
                  <div class="card-body text-center ">
                        WELCOME TO COLLEGE TEACHER PORTAL
                  </div>
            </div>
			<div class="row">
                  <div class="col-md-12">
                    <div class="row">
                      <div id="terminalsetup" class="col-md-4" style="cursor: pointer;">
                        <div class="small-box bg-info shadow">
                          <div class="inner">
                            <h3 class="">Student <br>Information</h3>
                          </div>
                          <div class="icon">
                            <i class="fa fa-users"></i>
                          </div>
                          <a href="/college/teacher/student/information" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>
                      <div id="terminalsetup" class="col-md-4" style="cursor: pointer;">
                        <div class="small-box bg-success  shadow">
                          <div class="inner">
                            <h3 class="">Student<br>Grades</h3>
                          </div>
                          <div class="icon">
                            <i class="fas fa-chart-bar"></i>
                          </div>
                          <a href="/college/teacher/student/grades" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>
                      <div id="terminalsetup" class="col-md-4" style="cursor: pointer;">
                        <div class="small-box bg-secondary shadow">
                          <div class="inner">
                            <h3 class="">Class<br>Schedule</h3>
                          </div>
                          <div class="icon">
                            <i class="fa fa-clipboard-list"></i>
                          </div>
                          <a href="/college/teacher/sched?blade=blade" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>
					   <div id="terminalsetup" class="col-md-4" style="cursor: pointer;">
                        <div class="small-box bg-warning  shadow">
                          <div class="inner">
                            <h3 class="">My<br>Profile</h3>
                          </div>
                          <div class="icon">
                            <i class="fas fa-user"></i>
                          </div>
                          <a href="/user/profile" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
      </section>
@endsection

@section('footerscript')

@endsection

