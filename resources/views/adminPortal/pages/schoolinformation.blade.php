@extends('adminPortal.layouts.app2')


@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
       
        .shadow {
              box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
              border: 0;
        }
  </style>
@endsection

@section('content')
    <section class="content">
        <form action="{{isset($schoolInfo) ? '/updateschoolinfo' :'/insertinfo'}}" id="update_info"  method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
            <div class="col-md-8 container-fluid">
                <div class="card h-100 shadow">
                    <div class="card-header bg-info">
                    <h3 class="card-title"><i class="fas fa-coins"></i> <b>SCHOOL INFORMATION</b></h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="card-body ">
                            <div class="form-group">
                                <label><b>School Id</b></label>
                                <input placeholder="SCHOOL ID" value="{{isset($schoolInfo->schoolid) ? $schoolInfo->schoolid :''}}"  name="schoolid" class="form-control" onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                            <div class="form-group">
                                <label><b>School Name</b></label>
                                <input placeholder="SCHOOL NAME" value="{{isset($schoolInfo->schoolname) ? $schoolInfo->schoolname :''}}" name="schoolname" class="form-control" onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                            <div class="form-group">
                                <label><b>School Abbreviation</b></label>
                                <input placeholder="ABBREVIATION" value="{{isset($schoolInfo->abbreviation) ? $schoolInfo->abbreviation :''}}" name="abbreviation" class="form-control" onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                            <div class="form-group">
                                <label>Region</label>
                                <input type="text" class="form-control"  name="region" id="region" value="{{isset($schoolInfo->regiontext) ? $schoolInfo->regiontext :''}}" onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                            <div class="form-group">
                                <label>Division</label>
                                <input type="text" class="form-control"  name="division" id="division" value="{{isset($schoolInfo->divisiontext) ? $schoolInfo->divisiontext :''}}" onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                            <div class="form-group">
                                <label><b>District</b></label>
                                <input placeholder="SCHOOL DISTRICT"  value="{{isset($schoolInfo->district) ? $schoolInfo->district :''}}" type="text" name="district" class="form-control" onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                            <div class="form-group">
                                <label><b>Address</b></label>
                                <input placeholder="SCHOOL ADDRESS" value="{{isset($schoolInfo->address) ? $schoolInfo->address :''}}" name="address" class="form-control" onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button  type="submit" class="btn {{isset($schoolInfo) ? 'btn-success' :'btn-info'}}" ><i class="fas fa-paper-plane" ></i> 
                                {{isset($schoolInfo) ? 'UPDATE' :'SUBMIT'}}</button>
                    </div>
                </div>
                </div>
                <div class="col-md-4 ">
                    <div class="card h-100 shadow">
                        <div class="card-header bg-info">
                            <div class="card-title">
                            <h3 class="card-title"><b><i style="color: #ffc107" class="fab fa-pushed"></i>MORE INFO</b></h3>
                                
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                            </div>
                                <div class="form-group">
                                    <label for=""><b>School Tag Line</b></label>
                                    <textarea placeholder="SCHOOL TAGLINE" class="form-control" name="schooltagline" rows="3">{{$schoolInfo->tagline}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for=""><b>School Logo</b></label>
                                    <input type="file" name="schoollogo" id="schoollogo" class="form-control @error('schoollogo') is-invalid @enderror">
                                    @if($errors->has('schoollogo'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('schoollogo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                @if(isset($schoolInfo->picurl))
                                    <img id="logoDisplay" src="{{asset($schoolInfo->picurl)}}" alt="" class="w-100">
                                @else
                                    <img id="logoDisplay" src="{{asset($schoolInfo->picurl)}}" alt="" class="w-100">
                                @endif
                        </div>
                    </div>
                </div>
            </div>
      </form>
</section>
@endsection

@php
    $link = DB::table('schoolinfo')
            ->select('essentiellink')
            ->first()
            ->essentiellink;
@endphp

@section('footerjavascript')

    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script>
       $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
    </script>

    <script>

        const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000,
            })

        var link = @json($link)

        $(document).ready(function(){

            function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('#logoDisplay').attr('src', e.target.result);
                        }
                        
                        reader.readAsDataURL(input.files[0]);
                    }
            }

            $("#schoollogo").change(function(){
                    readURL(this);
            });

         
            
            $( '#update_info' )
                  .submit( function( e ) {

                    var inputs = new FormData(this)
                        $.ajax( {
                              url: '/updateschoolinfo',
                              type: 'POST',
                              data: inputs,
                              processData: false,
                              contentType: false,
                              success:function(data) {
								  console.log("sdfsf")
                                if(data[0].status == 1){
                                    Toast.fire({
                                            type: 'success',
                                            title: data[0].message
                                    })
                                }else{
                                    Toast.fire({
                                            type: 'error',
                                            title: data[0].message
                                    })
                                }
                              }
                        })

                        // inputs.append('_token', "{{csrf_token()}}");

                        // $.ajax( {
                               
                        //       url: link,
                        //       type: 'POST',
                        //       data: inputs,
                        //       processData: false,
                        //       contentType: false,
                        //       success:function(data) {

                        //       }
                        // })

                    e.preventDefault();
            })

        })


    </script>
@endsection