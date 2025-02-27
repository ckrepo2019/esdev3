@extends('principalsportal.layouts.app2')

{{-- @section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
@endsection --}}

@include('generalPages.viewAllNotifications')

{{-- @section('content')
    <section class="content-header">
    </section>
    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="card main-card">
                    <div class="card-header">
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
                    <div class="card-body subjects">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="90%"> Notification</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="notificationholder">
                                @foreach($data[0]->data as $item)
                                    <tr>
                                        @if($item->type == '3' )
                                          <td><a href="principalgradeannouncement/{{$item->gradeid}}/{{$item->headerid}}">{{$item->gradeteacherlastname}}, {{$item->gradeteacherfirstname}}</a> submitted {{$item->subjdesc}}  Quarter {{$item->quarter}} Grades for {{$item->levelname}} - {{$item->sectionname}}  </td>
                                          @elseif($item->type=='1')
                                            <td><a href="principalReadAnnouncement/{{$item->headerid}}">{{$item->announcementfirstname}}, {{$item->announcementlastname}}</a> posted an announcement "{{$item->title}}"</td>
                                          @endif
                                        @if($item->status == '0')
                                          <td>Unread</td>
                                        @else
                                          <td>View</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <div class="mt-3" id="data-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footerjavascript')

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
                    url:'/principalfilterNotifications',
                    beforeSend: function(){
                      $('#notificationholder').empty();
                        $('#loadinggif').removeAttr('hidden')
                    },
                    complete: function(){
                        $('#loadinggif').prop('hidden',true)
                    },
                    data:{
                      data:$("#search").val(),
                      pagenum:pagination.pageNumber},
                    success:function(data) {
                     
                      $('#notificationholder').append(data[0].data);
                    }
                  })
                }
                pagetype=true;
              },
                hideWhenLessThanOnePage: true,
            })
          }
          $("#search" ).keyup(function() {
            $.ajax({
              type:'GET',
              url:'/principalfilterNotifications',
              beforeSend: function(){
                    $('#loadinggif').removeAttr('hidden')
                },
                complete: function(){
                    $('#loadinggif').prop('hidden',true)
                },
              data:{data:$(this).val(),pagenum:'1'},
              success:function(data) {
                $('#notificationholder').empty();
                $('#notificationholder').append(data[0].data);
                pagination(data[0].count,false)
              }
            })
          });
        })
    </script>

@endsection
 --}}
