@extends('principalsportal.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
    <style>
        .page-link-active{
            background-color: #007bff !important; 
            color: white !important;
            pointer-events: none !important;
            cursor: default;
        }
    </style>

@endsection

@section('modalSection')

<div class="modal fade" id="modal-default" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Senior High Subject Form</h4>
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
                    <input class="form-control" id="sn"  name="sn" placeholder="Subject Name">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Subject Code</label>
                            <input class="form-control" id="sc"  name="sc" placeholder="Subject Code">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type</label>
                            <select id="type" name="type" class="form-control" >
                                <option value="" selected disabled>Select Type</option>
                                @foreach(App\Models\Principal\SPP_SubjectType::loadSubjectType() as $item)
                                    <option value="{{$item->id}}">{{$item->description}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Strand</label>
                    <select id="strand" name="strand" class="form-control" disabled>
                        <option value="" selected disabled>Select Strand</option>
                        @foreach(App\Models\Principal\SPP_Strand::loadSHStrands() as $item)
                            <option value="{{Crypt::encrypt($item->id)}}">{{$item->strandcode}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group prereq">
                </div>
            </div>
            <div class="modal-footer justify-content-between sb">
                <button type="submit" class="btn btn-info savebutton">Save Subject</button>
            </div>
    
        </div>
    </div>
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
                        <button class="btn btn-xs btn-info" data-toggle="modal"  data-target="#modal-default" title="Contacts" data-widget="chat-pane-toggle">Add Subject</button>
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">
                              <input type="text" id="search" class="form-control float-right" placeholder="Search">
            
                              <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table smfont">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>SUBJECT NAME</th>
                                    <th class="text-center">CODE</th>
                                    <th class="text-center">PRE-REQUISITE</th>
                                    <th class="text-center">STRAND</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="border-bottom" id="searchSHSubjects">
                                @foreach ($data[0]->data as $item)
                                    <tr>
                                        @if($item->type==1)
                                            <td>C</td>
                                        @else
                                            <td>SP</td>
                                        @endif
                                        <td>{{$item->subjtitle}}</td>
                                        <td class="text-center">{{$item->subjcode}}</td>
                                       
                                        <td class="text-center">
                                        @if($item->strandid!=NULL)
                                            @foreach (App\Models\Principal\SPP_Prerequisite::loadSHSubjectPrerequisiteBySubject($item->id) as $prereq)
                                                {{$prereq->subjcode}} 
                                            @endforeach
                                        @endif
                                        </td>
                                        <td class="text-center">{{$item->strandcode}}</td>
                                        <td class="text-center">
                                            <button class="btn btn-xs btn-info edit" data-toggle="modal"  data-target="#modal-default" title="Contacts" data-widget="chat-pane-toggle" id="{{Crypt::encrypt($item->id)}}">EDIT</button>
                                            {{-- <button class="btn btn-xs btn-danger">REMOVE</button> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3" id="data-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('footerjavascript')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('js/pagination.js')}}"></script>

<script>
    $(document).ready(function(){
      pagination('{{$data[0]->count}}',true);
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
                url:'/searchSHSubjects',
                data:{
                    data:$("#search").val(),
                    pagenum:pagination.pageNumber},
                success:function(data) {
                    $('#searchSHSubjects').empty();
                    $('#searchSHSubjects').append(data[0].data);
                }
                })
            }
            pagetype=true;
          }
        })
      }

      $("#search" ).keyup(function() {
        $.ajax({
          type:'GET',
          url:'/searchSHSubjects',
          data:{data:$(this).val(),pagenum:'1'},
          success:function(data) {
            $('#searchSHSubjects').empty();
            $('#searchSHSubjects').append(data[0].data);
            pagination(data[0].count,false)
          }
        })
      });
    })
</script>


<script>

    $(document).on('click','.savebutton',function(){
        $.ajax({
            type:'GET',
            url:'/storeSHSubject',
            data:{
                sn:$('#sn').val(),
                sc:$('#sc').val(),
                strand:$('#strand').val(),
                prereq:$('#prereq').val(),
                type:$('#type').val()
            },
            success:function(data) {

                if(data==''){
                    location.reload();
                }
                else{
                    $('.message').empty(data);
                    $('.message').append(data);
                }
               
            }
        })
    })

    $(document).on('change','#type',function(){
       if($(this).val()=='2'){
            $('#strand').removeAttr('disabled')
            $('#sem').removeAttr('disabled')
            $('#gradelevel').removeAttr('disabled')

       }
       else{
            $('#gradelevel').val('')
            $('#strand').val('')
            $('#sem').val('')
            $('#strand').prop('disabled',true)
            $('#sem').prop('disabled',true)
            $('#gradelevel').prop('disabled',true)
            $('.prereq').empty();
       }
    })
   

    $(document).on('change','#strand',function(){
        $('.prereq').empty()
        var datastring = '<label>Prerequisite</label> <select id="prereq" name="prereq" class="select2" multiple="multiple" data-placeholder="Select prerequisite subject" style="width: 100%;">';
        $.ajax({
            type:'GET',
            url:'/viewSHSubjectsbyStrand',
            data:{st:$('#strand').val()},
            success:function(data) {
                $.each(data,function(index, value){
                    datastring+='<option value="'+value.id+'">'+value.subjtitle+'</option>'
                })
                datastring+='</select>'
                $('.prereq').append(datastring)
                $(function () {
                    //Initialize Select2 Elements
                    $('.select2').select2()

                    //Initialize Select2 Elements
                    $('.select2bs4').select2({
                    theme: 'bootstrap4'
                    })
                })
            }
        })
    })

</script>

    
@endsection

