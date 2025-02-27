

@extends('layouts.app')

@section('headerscript')
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
@endsection

@section('content')
<section class="content ">
      <div class="row justify-content-center">
            <div class="col-md-6">
                  <div class="card">
                        <div class="card-header card-title" style="background-color: #88b14b; color: #fff">
                             Pre-registration Inquiry Form
                        </div>
                        <form action="/prereg/inquiry/form/proccess">
                              <div class="card-body">
                                    <div class="form-group">
                                          <label for="">Pre-registration Code</label>
                                          <input class="form-control" name="code" placeholder="000000" autocomplete="off">
                                    </div>
                                    <button type="submit" class="btn btn-success">Submit</button>
                              </div>
                        </form>
                  </div>
            </div>
      </div>
  </section>
@endsection


                        
            

