@extends(''.$extends.'')
@section('content')
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

<script>
    var $ = jQuery;
    $(document).ready(function(){
        $(".filter").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".card").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                $(".card-group.card-group-filter:first-of-type").append($(this));
                $(this).hide();
                hiddenCards++;

                } else {

                $(".card-group.card-group-filter:last-of-type").prepend($(this));
                $(this).show();
                visibleCards++;

                if (((visibleCards % 4) == 0)) {
                    $(".container").append($("<div class='card-group card-group-filter'></div>"));
                }
                }
            });

        });
    })
</script>
    <section class="content-header">
        <div class="callout callout-info">
            <form action="/database/import/import" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- <div class="col-md-6">
                        <label>Database Name</label>
                        <input type="text" class="form-control" name="databasename" placeholder="Database Name" required/>
                    </div> --}}
                    <div class="col-md-6">
                        {{-- <form> --}}
                            <div class="form-group">
                            <label for="exampleInputFile">Select a file</label>
                            <div class="input-group">
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" id="exampleInputFile" accept=".sql" name="file">
                                <label class="custom-file-label" for="exampleInputFile" style="overflow: hidden;" required="">Choose file</label>
                              </div>
                              {{-- <div class="input-group-append">
                                <span class="input-group-text">Upload</span>
                              </div> --}}
                            </div>
                          </div>
                        {{-- </form> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </form>
          </div>
    </section>
    
  <section class="content-body">
  
    {{-- <div class="card"> --}}
      <div class="row m-2">
        <div class="col-md-4">
          
          <input class="filter form-control" placeholder="Search database" />
        </div>
      </div>
      <div class="row d-flex align-items-stretch  m-2">
          @foreach($databases as $database)
              <div class="card col-md-3 text-center " style="border: none !important;box-shadow: none !important;" data-string="{{$database->Database}}}<">
                  <div class="card-body p-0 mb-2" style="border: 1px solid#ddd;background: #e8e8e8;">
                      <small>{{$database->Database}}</small>
                      <p class="card-text text-center m-0">
                      {{-- <div class="widget-user-image"> --}}
              
                          {{-- @php
                                  $number = rand(1,3);
                                  if(strtoupper($employee->gender) == 'FEMALE'){
                                      $avatar = 'avatar/T(F) '.$number.'.png';
                                  }
                                  else{
                                      $avatar = 'avatar/T(M) '.$number.'.png';
                                  }
                              @endphp
              
                          <img class="img-circle elevation-2" src="{{asset($employee->picurl)}}" 
                              onerror="this.onerror = null, this.src='{{asset($avatar)}}'"
                              alt="User Avatar" style="width: 40% !important"
                              >
                    
                              <a href="/hr/employeeprofile?employeeid={{$employee->id}}">
                                  <h6>{{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}}</h6>
                              </a> --}}
                      {{-- </div> --}}
                      </p>
                  </div>
              </div>
          @endforeach
      </div>
    {{-- </div> --}}
  </section>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- bs-custom-file-input -->
    <script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script type="text/javascript">
    $(document).ready(function () {
      bsCustomFileInput.init();
    });
    </script>
@endsection