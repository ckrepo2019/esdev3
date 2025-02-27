
@section('content')
    <style>
        /* the interesting part */
        table {
        width: 100%;
        }
        td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        }
        td:nth-child(2) {
        width: 100%; 
        max-width: 0; 
        }
    </style>
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">

    <section class="content-header">
    </section>
    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="card main-card">
                    <div class="card-header bg-info">
                        <div class="card-tools">
                            <div class="input-group input-group-sm h-100" style="width: 300px;">
                                <img class="pr-3" hidden id="loadinggif" width="15%" src="{{asset('gif/loading.gif')}}">
                                <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="notificationholder">
                       @include('search.general.notification')
                    </div>
                    <div class="card-footer clearfix">
                        <div class="mt-3" id="data-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{asset('js/pagination.js')}}"></script>
    
    <script>
        $(document).ready(function(){
          pagination('{{$data[0]->count}}',false);
          function pagination(itemCount,pagetype){
            var result = [];
            for (var i = 0; i < itemCount; i++) {
              result.push(i);
            }
            $('#data-container').pagination({
              dataSource: result,
              callback: function(data, pagination) {
                if(pagetype){
                  $.ajax({
                    type:'GET',
                    url:'/generalFilterNotifications',
                    data:{
                      data:$("#search").val(),
                      pagenum:pagination.pageNumber},
                    success:function(data) {
                      $('#notificationholder').empty();
                      $('#notificationholder').append(data);
                    }
                  })
                }
                pagetype=true;
              },
                hideWhenLessThanOnePage: true,
                pageSize: 10,
            })
          }
          $("#search" ).keyup(function() {
            $.ajax({
              type:'GET',
              url:'/generalFilterNotifications',
              data:{data:$(this).val(),pagenum:'1'},
              success:function(data) {
                $('#notificationholder').empty();
                $('#notificationholder').append(data);
                pagination($('#searchCount').val())
              }
            })
          });
        })
    </script>
@endsection

