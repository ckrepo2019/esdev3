
@extends('deanportal.layouts.app2')

@section('pagespecificscripts')
	 <style>
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }
      </style>
	
@endsection

@section('content')
	@php
		$schoolinfo = DB::table('schoolinfo')->first();
	@endphp
      <section class="content">
            <div class="row">
                  <div class="col-md-12">
                        <div class="card container-fluid bg-primary shadow">
                              <div class="card-body text-center ">
                                    <b>WELCOME TO DEAN'S PORTAL</b>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                    <div class="row">
					@if($schoolinfo->projectsetup == 'offline' || $schoolinfo->processsetup == 'all')
                      <div id="terminalsetup" class="col-md-4" style="cursor: pointer;">
                        <div class="small-box bg-info shadow">
                          <div class="inner">
                            <h3 class="">Prospectus <br>Setup</h3>
                          </div>
                          <div class="icon">
                            <i class="fa fa-book"></i>
                          </div>
                          <a href="/setup/prospectus" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>
                      <div id="terminalsetup" class="col-md-4" style="cursor: pointer;">
                        <div class="small-box bg-success  shadow">
                          <div class="inner">
                            <h3 class="">College<br>Sections</h3>
                          </div>
                          <div class="icon">
                            <i class="fas fa-cubes"></i>
                          </div>
                          <a href="/college/section" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>
                      <div id="terminalsetup" class="col-md-4" style="cursor: pointer;">
                        <div class="small-box bg-secondary shadow">
                          <div class="inner">
                            <h3 class="">Student<br>Loading</h3>
                          </div>
                          <div class="icon">
                            <i class="fas fa-truck-loading"></i>
                          </div>
                          <a href="/student/loading" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>
					@endif
					   @if(($schoolinfo->projectsetup == 'online' && $schoolinfo->processsetup == 'hybrid1' ) || $schoolinfo->processsetup == 'all')
						  <div id="terminalsetup" class="col-md-4" style="cursor: pointer;">
							<div class="small-box bg-danger  shadow">
							  <div class="inner">
								<h3 class="">Grade<br>Teacher</h3>
							  </div>
							  <div class="icon">
								<i class="fas fa-chart-bar"></i>
							  </div>
							  <a href="/college/grade/monitoring/teacher" class="small-box-footer">
								More info <i class="fas fa-arrow-circle-right"></i>
							  </a>
							</div>
						  </div>
						@endif
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

@section('footerjavascript')

      

@endsection

