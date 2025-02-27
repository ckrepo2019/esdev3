
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
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
            <li class="breadcrumb-item active">Room</li>
        </ol>
        </div>
    </div>
    </div>
    </section>
    
    <section class="content pt-0">
        <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card adminrooms">
              <div class="card-header bg-info">
                <h5>Truncanator</h5>
              </div>
              <div class="card-body">
                    <form action="/truncate">
                        <div class="form-group">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="grades" name="grades" value="true">
                                <label for="grades">GRADES</label>
                                <p class="ml-5 small mb-0">AFFECTED TABLES: <em class="text-danger">gradesdetail, grades, gradelogs, gradesspclass, tempgradesum, notifications[2,3]</em></p>
                                <p class="ml-5 small mt-0">FOR EVALUATION: <em class="text-primary"></em></p>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="sched" name="sched" value="true">
                                <label for="sched">CLASS SCHEDULES 
                                </label>
                                    <p class="ml-5 small mb-0">AFFECTED TABLES: <em class="text-danger">classsched, classscheddetail, assignedsubj, assignedsubjdetail, blocksched,sh_classsched, sh_classscheddetail, sh_blocksched, sh_blockscheddetail, GRADES</em></p>
                                    <p class="ml-5 small mt-0">FOR EVALUATION: <em class="text-primary"></em></p>
                            </div>
                        </div>
                        {{-- <hr>
                        <div class="form-group">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="enstud" name="enstud" value="true" >
                                <label for="enstud">STUDENT INFO</label>
                                <p class="ml-5 small mb-0">AFFECTED TABLES: <em class="text-danger">enrolledstud, sh_enrolledstud, notifications, observedvalues, sh_studentsched, studentattendance, observedvaluesdetails, studdiscounts, studentsubjectattendance, studinfo, studledger, studledgeritemized, studpaysched, studpayscheddetail, users[7,9], SF10, GRADES</em></p>
                                <p class="ml-5 small mt-0">FOR EVALUATION: <em class="text-primary"></em></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="humanresource" name="humanresource" value="true" >
                                <label for="humanresource">HUMAN RESOURCE</label>
                                <p class="ml-5 small mb-0">AFFECTED TABLES: <em class="text-danger"></em></p>
                                <p class="ml-5 small mt-0">FOR EVALUATION: <em class="text-primary"></em></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="finance" name="finance" value="true" >
                                <label for="finance">FINANCE</label>
                                <p class="ml-5 small mb-0">AFFECTED TABLES: <em class="text-danger"></em></p>
                                <p class="ml-5 small mt-0">FOR EVALUATION: <em class="text-primary"></em></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="admin" name="admin" value="true" >
                                <label for="admin">ADMIN</label>
                                <p class="ml-5 small mb-0">AFFECTED TABLES: <em class="text-danger"></em></p>
                                <p class="ml-5 small mt-0">FOR EVALUATION: <em class="text-primary"></em></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="cashier" name="cashier" value="true" >
                                <label for="cashier">CASHIER</label>
                                <p class="ml-5 small mb-0">AFFECTED TABLES: <em class="text-danger"></em></p>
                                <p class="ml-5 small mt-0">FOR EVALUATION: <em class="text-primary"></em></p>
                            </div>
                        </div> --}}
                        {{-- <div class="form-group">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="cashier" name="cashier" value="true" >
                                <label for="cashier"></label>
                                <p class="ml-5 small mb-0">AFFECTED TABLES: <em class="text-danger"></em></p>
                                <p class="ml-5 small mt-0">FOR EVALUATION: <em class="text-primary"></em></p>
                            </div>
                        </div> --}}

                        {{-- <div class="form-group">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="enstud" name="enstud" value="true" >
                                <label for="enstud">STUDENT INFO</label>
                                <p class="ml-5 small mb-0">AFFECTED TABLES: <em class="text-danger">enrolledstud, sh_enrolledstud, notifications, observedvalues, sh_studentsched, studentattendance, observedvaluesdetails, studdiscounts, studentsubjectattendance, studinfo, studledger, studledgeritemized, studpaysched, studpayscheddetail, users[7,9], SF10, GRADES</em></p>
                                <p class="ml-5 small mt-0">FOR EVALUATION: <em class="text-primary"></em></p>
                            </div>
                        </div> --}}
                        {{-- <div class="form-group">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="fas" name="fas" >
                                <label for="fas">FACULTY & STAFF</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="sections" name="sections" >
                                <label for="sections">SECTIONS / BLOCKS</label>
                            </div>
                        </div> --}}
                        {{-- <div class="form-group">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="all" name="all" >
                                <label for="all">ALL</label>
                            </div>
                        </div> --}}
                        <hr>
                        {{-- <div class="form-group">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="fortesting" name="fortesting" value="true">
                                <label for="fortesting">FOR TESTING</label>
                                <p class="ml-5 small mb-0">AFFECTED TABLES: <em class="text-danger">GRADES, STUDINFO, CLASS SCHEDULE, FINANCE SETUP, CACHIERING</em></p>
                                <p class="ml-5 small mt-0">FOR EVALUATION: <em class="text-primary"></em></p>
                            </div>
                        </div> --}}
                        <button type="submit" class="btn btn-danger">REMOVE DATA</button>
                    </form>
              </div>
              <div class="card-footer">
                <div class="mt-3" id="data-container">
              </div>
            </div>
          </div>
        </div>
      </div>
</section>
@endsection

@section('footerjavascript')

    
@endsection

