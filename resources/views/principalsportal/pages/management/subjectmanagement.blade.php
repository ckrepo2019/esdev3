@extends('principalsportal.layouts.app2')


@section('modalSection')

<div class="modal fade" id="modal-default" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Add Class Schedule</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="message">

            </div>
            <input id="si" type="hidden">
            <div class="form-group">
                <label>Subject</label>
                <input type="email" class="form-control" id="sn" placeholder="Subject Name">
            </div>
            <div class="form-group">
                <label>Subject Code</label>
                <input type="email" class="form-control" id="sc" placeholder="Subject Code">
            </div>
        </div>
        <div class="modal-footer justify-content-between sb">
            <button onClick="this.form.submit(); this.disabled=true;" type="button" class="btn btn-info savebutton">Save Subject</button>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection

@section('content')

    <section class="content-header">
    </section>

    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="card main-card">
                    <div class="card-header">
                        <div class="card-tools">
                            <button class="btn btn-sm btn-info" data-toggle="modal"  data-target="#modal-default" title="Contacts" data-widget="chat-pane-toggle">Add Subject</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Subject Name</th>
                                    <th>Subject Code</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subjects as $item)
                                    <tr>
                                        <td>{{$item->subjdesc}}</td>
                                        <td>{{$item->subjcode}}</td>
                                        <td>
                                            <button class="btn btn-xs btn-info edit" data-toggle="modal"  data-target="#modal-default" title="Contacts" data-widget="chat-pane-toggle" id="{{Crypt::encrypt($item->id)}}">EDIT</button>
                                            {{-- <button class="btn btn-xs btn-danger">REMOVE</button> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('footerjavascript')
    <script>
        $(document).on('click','.savebutton',function(){
            $.ajax({
                type:'GET',
                url:'/storesubjectajax',
                data:{sn:$('#sn').val(),sc:$('#sc').val()},
                success:function(data) {
                    if(data!=''){
                        $('.message').empty();
                        $('.message').append(data);
                    }
                    else{
                        location.reload();
                    }
                }
            })
        })
        $(document).on('click','.edit',function(){
            $.ajax({
                type:'GET',
                url:'/getsubjectajax',
                data:{i:$(this).attr('id')},
                success:function(data) {
                    $('#sn').val(data[0].subjdesc)
                    $('#sc').val(data[0].subjcode)
                   
                }
            })
            $('#si').val($(this).attr('id'));
            $('.sb').empty();
            $('.sb').append('<button type="button" class="btn btn-info us onClick="this.form.submit(); this.disabled=true;" ">Update Subject</button>');
        })
        $(document).on('click','.us',function(){
           
            $.ajax({
                type:'GET',
                url:'/updatesubjectajax',
                data:{i:$('#si').val(),sn:$('#sn').val(),sc:$('#sc').val()},
                success:function(data) {
                    if(data!=''){
                        $('.message').empty();
                        $('.message').append(data);
                    }
                    else{
                        location.reload();
                    }

                }
            })

         
          
        })
    </script>
@endsection

