<table class="table">
      <thead>
            <tr>
                  <td width="20%">SECTION</td>
                  <td width="20%">SUBJECT</td>
                  <td width="15%">PRELIM</td>
                  <td width="15%">MIDTERM</td>
                  <td width="15%">SEMI</td>
                  <td width="15%">FINAL</td>
            </tr>
      </thead>
      <tbody>
            @foreach ($schedule as $item)
                  <tr>
                        <td class="align-middle">{{$item->sectionDesc}}</td>
                        <td class="align-middle">
                            
                              {{$item->subjDesc}}</td>
                        <td>
                              <a href="#" class="viewSetup" data-section="{{$item->sectionID}}" data-term="1" data-subj="{{$item->subjectID}}"><i class="fas fa-eye"></i> View</a><br>
                             
                              @if($item->prelem == 1)
                                    <span class="text-success"><i class="fas fa-thumbs-up"></i> Complete</span>
                              @else
                                    <span class="text-danger"><i class="fas fa-thumbs-down"></i> Incomplete</span>
                              @endif
                        </td>
                        <td>
                              <a href="#" class="viewSetup" data-section="{{$item->sectionID}}" data-term="2" data-subj="{{$item->subjectID}}"><i class="fas fa-eye" ></i> View</a><br>
                              
                              @if($item->midterm == 1)
                                    <span class="text-success"><i class="fas fa-thumbs-up"></i> Complete</span>
                              @else
                                    <span class="text-danger"><i class="fas fa-thumbs-down"></i> Incomplete</span>
                              @endif
                        </td>
                        <td>
                              <a href="#" class="viewSetup" data-section="{{$item->sectionID}}" data-term="3" data-subj="{{$item->subjectID}}"><i class="fas fa-eye"></i> View</a><br>
                              @if($item->prefi == 1)
                                    <span class="text-success"><i class="fas fa-thumbs-up"></i> Complete</span>
                              @else
                                    <span class="text-danger"><i class="fas fa-thumbs-down"></i> Incomplete</span>
                              @endif
                        </td>
                        <td>
                              <a href="#" class="viewSetup" data-section="{{$item->sectionID}}" data-term="4" data-subj="{{$item->subjectID}}"><i class="fas fa-eye"></i> View</a><br>
                         
                              @if($item->final == 1)
                                    <span class="text-success"><i class="fas fa-thumbs-up"></i> Complete</span>
                              @else
                                    <span class="text-danger"><i class="fas fa-thumbs-down"></i> Incomplete</span>
                                    
                              @endif
                        </td>

                  </tr>
            @endforeach
      </tbody>
</table>