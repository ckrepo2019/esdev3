
<style>
      .table.table-head-fixed thead tr:nth-child(1) th {
            top: -1px;
      }
</style>

{{-- <div class="modal fade" id="added_subjects" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header bg-primary">
                  <h4 class="modal-title">Added Subjects</h4>
                  <button type="button" class="close" id="added_subjects_close">
                  <span aria-hidden="true">×</span></button>
              </div>
              <div class="modal-body" >
                  <div class="row table-responsive  m-0" style="height: 539px;">
                        <table class="table table-head-fixed">
                              <thead>
                                    <tr>
                                          <th width="75%">Subject</th>
                                          <th width="15%" class="text-center">Date Added</th>
                                          <th width="10%" class="text-center">Status</th>
                                    </tr>
                              </thead>
                              <tbody>
                                    @if(count($subject_assignments) > 0)
                                          @foreach (collect($subject_assignments)->where('gsid',$gsid) as $item)
                                                <tr>
                                                      <td class="align-middle">{{$item->subjdesc}} ( {{$item->subjcode}} )</td>
                                                      <td class="text-center align-middle">
                                                            @if($item->gsid != null)
                                                                  {{\Carbon\Carbon::create($item->createddatetime)->isoFormat('MM/DD/YY hh:mm a')}}
                                                            @else 
                                                                  
                                                            @endif
                                                      </td>
                                                      <td class="text-center align-middle">
                                                            <button class="btn btn-sm btn-danger btn-block remove_subject_assignment" data-subj="{{$item->subjid}}">Remove</button>
                                                      </td>
                                                </tr>
                                          @endforeach
                                    @endif
                              </tbody>
                        </table>
                  </div>
                
              </div>
          </div>
      </div>
  </div> --}}







<div class="row">
      <div class="col-md-12">
            <table class="table">
                  <tr>
                        <th width="40%">Grading System</th>
                        <td width="40%">{{$grading_system_info[0]->description}}</td>
                        {{-- <td width="20%"><button class="btn btn-primary btn-sm" id="view_added_subjects">Added Subjects</button></td> --}}
                  </tr>
                  <tr>
                        <th>Assigned Subject</th>
                        <td>{{collect($subject_assignments)->where('gsid',$gsid)->count()}}</td>
                  </tr>
            </table>
      </div>
</div>

<script>
      $(document).ready(function(){
            $(document).on('click','#view_added_subjects',function(){
                  $('#added_subjects').modal();
            })
            $(document).on('click','#added_subjects_close',function(){
                  $('#added_subjects').modal('hide');
            })

            
      })
</script>

<div class="row table-responsive" style="height: 424px;" >
     
      <table class="table table-head-fixed">
            <thead>
                  <tr>
                        <th width="75%">Subject</th>
                        <th width="10%" class="text-center">Status</th>
                        <th width="15%" class="text-center"></th>
                  </tr>
            </thead>
            <tbody>
                  @if(count($subject_assignments) > 0)
                        @if(count($subject_assignments) > 0)
                              @foreach (collect($subject_assignments) as $item)
                                    <tr>
                                          <td class="align-middle">
                                                @if($grading_system_info[0]->acadprogid == 5)
                                                    
                                                      @if($item->type == 1)
                                                      <span class="badge badge-danger"> CORE
                                                      @elseif($item->type == 2)
                                                      <span class="badge badge-success"> SP
                                                      @elseif($item->type == 3)
                                                      <span class="badge badge-warning"> AP
                                                      @endif
                                                      </span>
                                                @endif
                                                {{$item->subjdesc}} 
                                                ( {{$item->subjcode}} )</td>
                                          <td class="text-center align-middle">
                                                @if($item->gsid == $gsid )
                                                      <button class="btn btn-sm btn-danger btn-block remove_subject_assignment" data-id="{{$item->gssid}}">Remove</button>
                                                @else 
                                                      <button class="btn btn-sm btn-primary btn-block add_subject_assignment" data-subj="{{$item->subjid}}">Add</button>
                                                @endif
                                          </td>
                                          <td class="text-center align-middle">
                                                @if($item->gsid != null)
                                                      {{\Carbon\Carbon::create($item->createddatetime)->isoFormat('MM/DD/YY hh:mm a')}}
                                                @else 
                                                @endif
                                          </td>
                                    </tr>
                              @endforeach
                        @endif
                  @endif
            </tbody>
      </table>
</div>


<script>
      $(document).ready(function(){

            var gsid = '{{$gsid}}'

            function loadsubjectassignment(){

                  $.ajax({
                        type:'GET',
                        url:'/grading/subject/assignment',
                        data:{
                              gsid:gsid,
                              
                        },
                        success:function(data) {

                              $('#subject_assignment_holder').empty()
                              $('#subject_assignment_holder').append(data)
                              
                        
                        }
                  })
            }

            $('.add_subject_assignment').unbind().click(function(){

                  var subjectid = $(this).attr('data-subj')
                  
                  $.ajax({
                        type:'GET',
                        url:'/grading/subject/assignment/add',
                        data:{
                              gsid:gsid,
                              subjectid:subjectid,
                        },
                        success:function(data) {
                            
                              loadsubjectassignment()

                        }
                  })

            })

            $('.remove_subject_assignment').unbind().click(function(){

                  var gssid = $(this).attr('data-id')

                  $.ajax({
                        type:'GET',
                        url:'/grading/subject/assignment/remove',
                        data:{
                              gssid:gssid,
                        },
                        success:function(data) {
                        
                              loadsubjectassignment()

                        }
                  })

            })

      })
</script>