
<div class="modal-header">
        <h4 class="modal-title" >{{$fileinfo->filename}}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
</div>
<div class="modal-body">
    
    @if($fileinfo->extension == 'png' || $fileinfo->extension == 'jpg')
        <img  src="{{asset($fileinfo->filepath)}}" style="width: 100%;" draggable="false" style="pointer-events: none; border-radius: unset;"/>
        <br/>
        <br/>
    @elseif($fileinfo->extension == 'mp4' || $fileinfo->extension == 'mkv')
        <video style="width: 100%;"  height="400" controls draggable="false"  style="">
                <source src="{{asset($fileinfo->filepath)}}" type="video/mp4">
        </video>
    @elseif($fileinfo->extension == 'pdf')
        <div style="width: 100%;height: 90%;position: relative;">
            <div style=" position: absolute;
            top: 0;
            left: 0;
            width: 98%;
            height: 90%;"></div>
            <iframe id="iframe-{{$fileinfo->id}}" src="{{asset($fileinfo->filepath)}}#toolbar=0" style="width: 100%;height: 90%;"  ></iframe>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="info-box shadow">
                  <span class="info-box-icon bg-warning"><i class="far fa-file"></i></span>
        
                  <div class="info-box-content">
                    <span class="info-box-text">{{$fileinfo->filename}}</span>
                    <span class="info-box-number">{{$fileinfo->extension}}</span>
                  </div>
                </div>
            </div>
        </div>
    @endif
    @if($fileinfo->createdby == auth()->user()->id)
        <div class="row">
            <div class="col-12">
                <a href="{{asset($fileinfo->filepath)}}" download class="btn btn-default"><i class="fa fa-download"></i> Download</a>
            </div>
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-6">
            <label>Audience</label>
            <div class="form-group clearfix">
              <div class="icheck-primary d-inline">
                <input type="radio" id="audience11" name="file-audience" value="1"/>
                <label for="audience11">
                    Public
                </label>
              </div>&nbsp;&nbsp;
              <div class="icheck-primary d-inline">
                <input type="radio" id="audience22" name="file-audience" value="0"/>
                <label for="audience22">
                    Only Me
                </label>
              </div>
            </div>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-danger" id="btn-delete-file"><i class="fa fa-trash"></i> Delete</button>
        </div>
    </div>
</div>
<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<script>
    
    @if($fileinfo->visible == 1)
        $('#audience11').prop('checked',true)
    @elseif($fileinfo->visible == 0)
        $('#audience22').prop('checked',true)
    @endif
            
    $(document).on('click','input[name="file-audience"]', function(){
        $.ajax({
            url: '/mydocs/fileedit',
            type: 'GET',
            data: {
                visible      : $(this).val(),
                fileid     : '{{$fileinfo->id}}'
            },
            datatype        : 'json',
            complete:function(){
                // thiselement.val(data.code)
                toastr.success('Upadated successfully!');
            }
        })
    })
    $(document).on('click','#btn-delete-file', function(){
        var fileid = '{{$fileinfo->id}}';
        Swal.fire({
            title: 'Are you sure you want to delete this file?',
            // text: "You won't be able to revert this!",
            html: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/mydocs/filedelete',
                    type:"GET",
                    dataType:"json",
                    data:{
                        fileid   :  fileid,
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    complete: function(){
                        toastr.success('Deleted successfully!')
                        window.location.reload()
                    }
                })
            }
        })
    })
</script>