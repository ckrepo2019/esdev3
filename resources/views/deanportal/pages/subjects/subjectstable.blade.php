@if(count($collegesubjects) > 0)
      <table class="table @if($status == 1 || $status == 3)table-sm @endif table-head-fixed">
            <thead>
                  <tr>
                        @if($status == 2)
                              <th width="5%" class="text-center"></th>
                        @endif
                        <th width="20%">Code</th>
                        <th width="60%">Subject Description</th>
                        <th width="5%" class="text-center">Lec.</th>
                        <th width="5%" class="text-center">Lab.</th>
                        <th width="5%" class="text-center">Total</th>
                        @if($status == 1 || $status == 3)
                              <th width="10%" class="text-center"></th>
                        @endif
                  </tr>
            </thead>
            <tbody>

                  @foreach ($collegesubjects as $item)
                        <tr>
                              @if($status == 2)
                                    <td>
                                          <div class="dropdown">
                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                      <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a 
                                                      class="dropdown-item editSubject" 
                                                      href="#" 
                                                      data-id="{{$item->id}}"
                                                >Edit Subject</a>
                                                {{-- <a class="dropdown-item removeSubject" href="#" 
                                                      data-id="{{$item->id}}" 
                                                >Remove Subject</a> --}}
                                                </div>
                                          </div>
                                    </td>
                              @endif
                         
                              <td class="align-middle "> {{$item->subjCode}}</td>
                              <td class="align-middle">
                                    @if($item->subjClass == 2)
                                          <span class="badge badge-pill badge-primary">&nbsp;</span>
                                    @elseif($item->subjClass == 1)
                                          <span class="badge badge-pill badge-success">&nbsp;</span>
                                    @endif
                                    {{$item->subjDesc}}
                              </td>
                            

                              <td class="text-center align-middle">{{$item->lecunits}}</td>
                              <td class="text-center align-middle">{{$item->labunits}}</td>
                              <td class="text-center align-middle">{{$item->labunits + $item->lecunits}}</td>
                              @if($status == 1)
                                    @if($item->prosid != null)
                                          <td><button class="btn btn-danger btn-sm btn-block removesubjectfromprospectus" data-id="{{$item->prosid}}" id="">Remove</button></td>
                                    @else
                                          <td><button class="btn btn-success btn-sm btn-block addsubjecttoprospectus" data-id="{{$item->id}}" >Add</button></td>
                                    @endif
                              @elseif($status == 3)

                                    @if($item->prereqid != null)
                                          <td><button class="btn btn-danger btn-sm btn-block removetoprereq" data-prereqid="{{$item->prereqid}}" data-subjid="{{$item->id}}">Remove</button></td>
                                    @else
                                          <td><button class="btn btn-success btn-sm btn-block addtoprereq" 
                                                data-subjid="{{$item->id}}">Add</button></td>
                                    @endif
                             
                              @endif
                        
                        </tr>
                  @endforeach
            
            </tbody>

      </table>
@else

      <table class="table table-bordered">
            <thead>
                <tr>
                      <th>NO SUBJECTS ADDED</th>
                </tr>
            </thead>
           
      </table>


@endif