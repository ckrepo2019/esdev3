
@extends('adminPortal.layouts.app2')

@section('pagespecificscripts')
  <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
  <link rel="stylesheet" href="../plugins/ekko-lightbox/ekko-lightbox.css">
  
@endsection

@section('modalSection')
<div class="modal fade" id="modal-primary" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header bg-info">
             
                  <h5 class="modal-title">Room Form</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
            </div>
            <form id="imageupload"  method="POST" action="/adminstoreadvertisements" enctype="multipart/form-data">
                  @csrf
                  <div class="modal-body">
                        <div class="form-group">
                          <input type="file" class="form-control  @error('adimage') is-invalid @enderror" name="adimage" id="adimage">
                              {{-- @if($errors->has('tapoff'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('tapoff') }}</strong>
                                </span>
                              @endif --}}
                              @if($errors->has('adimage'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('adimage') }}</strong>
                                </span>
                              @endif
                        </div>
                  </div>
                  <div class="modal-footer justify-content-between">
                        <button class="btn btn-info savebutton">UPLOAD</button>
                  </div>
          <form>
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
          <li class="breadcrumb-item active">Advertisement Images</li>
      </ol>
      </div>
  </div>
  </div>
</section>

 <!-- Main content -->
 <section class="content pt-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card card-primary">
              <div class="card-header bg-info">
              <span style="font-size: 16px"><b><i class="nav-icon fab fa-pushed"></i> ADVERTISEMENT IMAGES</b></span>
                <button class="btn btn-sm btn-primary float-right" data-toggle="modal"  data-target="#modal-primary" title="Contacts" data-widget="chat-pane-toggle" ><b>UPLOAD ADVERTISEMENT</b></button>
              </div>
              <div class="card-body">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
                  <div class="row">
                        @foreach ($adimages as $item)
                              <div class="col-sm-2 advertisementimage mt-3">
                                <a href="{{asset($item->picurl)}}" data-toggle="lightbox" data-gallery="gallery">
                                      <!-- @if($item->isactive == 1)
                                        <span style="position: absolute; text-shadow: 1px 1px 4px #000; color: #00f500;"><i class="fas fa-tags"></i></span>
                                      @else
                                        <span style="position: absolute;text-shadow: 1px 1px 4px #000; color: #fd0000"><i class="fas fa-tags"></i></span>

                                      @endif -->
                                      <img src="{{asset($item->picurl)}}" class="img-fluid mb-2" alt="white sample"/>
                                </a>
                                  <?php
                                    $path = pathinfo($item->picurl);
                                    $file = $path['basename'];

                                    $x = substr($file, 0, strrpos($file, '.')); 

                                    
                                  ?>
                                @if($item->isactive == 1)
                                <!-- <a class="btn btn-sm btn-success btn-block" href="/setimageisactive/{{$item->id}}/0">Active</a>
                               
                                  <a class="btn btn-sm btn-danger btn-block" href="/setimageisactive/{{$item->id}}/1">Inactive</a> -->
                                  <center>
                                    <a href="/calendarinfo/{{$item->id}}"><span class="text-success" style="text-transform: capitalize">{{$x}}</span></a>
                                  </center>
                                @else
                                 <center>
                                  <a href="/calendarinfo/{{$item->id}}"><span class="text-danger" style="text-transform: capitalize">{{$x}}</span></a>
                                 </center>
                                @endif
                              </div>
                        @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
@endsection

@section('footerjavascript')

<script src="../plugins/ekko-lightbox/ekko-lightbox.min.js"></script>

<script>
      $(document).ready(function(){
        $('.ribbon-wrapper').css('right','4px !important');

        @if ($errors->any())
          $('#modal-primary').modal('show')
        @endif








      })
      $(function () {
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
          event.preventDefault();
          $(this).ekkoLightbox({
            // alwaysShowClose: true
          });
        })
      })
</script>
@endsection

