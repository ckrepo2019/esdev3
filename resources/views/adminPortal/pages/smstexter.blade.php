@extends('adminPortal.layouts.app2')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
        <div class="col-md-9">
            <div class="card h-100">
                  <div class="card-header">
                        <div class="card-title">
                              SMS Composer
                        </div>
                  </div>
                  <div class="card-body">
                        <form action="/adminsendsmstext" method="GET">
                              <label>Text Message</label>
                              <div class="form-group">
                                    <textarea name="message" class="form-control" placeholder="Text message"></textarea>
                                    <p class="text-danger "><em>Accepts 160 characters only</em></p>
                              </div>
                             
                              <button type="submit" class="btn btn-primary">Send</button>
                        </form>
                  </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                  <div class="card-header">
                        SMS TEXTER
                  </div>
                  <div class="card-body ">
                        <strong><i class="fas fa-book mr-1"></i>Student Count</strong>
                        <p class="text-muted">
                              {{$summary[0]->studentcount}}
                        </p>
                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i>With Number</strong>
                        <p class="text-muted">
                              {{$summary[0]->withNum}}
                        </p> 
                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i>Without Number</strong>
                        <p class="text-muted">
                              {{$summary[0]->withoutNum}}
                        </p>
                    </div>
                    <!-- /.card-body -->
                  </div>
            </div>
        </div>
        </div>
    </div>
</section>
@endsection
