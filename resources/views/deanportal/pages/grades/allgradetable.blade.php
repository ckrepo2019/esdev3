@if($data[0]->count > 0)

      <input type="hidden" value="{{$data[0]->count}}" id="searchCount">

      @php
            $termCount = 0;
            $withPercent = 12;

            if($termSetupOrig->withpre == 1){ 
                  $termCount += 1;
            }
            if($termSetupOrig->withmid  == 1){
                  $termCount += 1;
            }
            if($termSetupOrig->withsemi  == 1){
                  $termCount += 1;
            }
            if($termSetupOrig->withfinal  == 1){
                  $termCount += 1;
            }

            if($termCount > 0 ){

                  $withPercent = 48 / $termCount;   

            }

           

      @endphp

      <table class="table table-striped">
            <thead>
                  <tr>
                        <th  width="28%" >Instruction</th>
                        <th width="12%">Subject</th>
                        <th width="12%">Section</th>
                        @if($termSetupOrig->withpre == 1)
                              <th width="{{$withPercent}}%" class="text-center">Prelim</th>
                        @endif
                        @if($termSetupOrig->withmid  == 1)
                              <th width="{{$withPercent}}%" class="text-center">Midterm</th>
                        @endif
                        @if($termSetupOrig->withsemi  == 1)
                              <th width="{{$withPercent}}%" class="text-center">Semi</th>
                        @endif
                        @if($termSetupOrig->withfinal  == 1)
                              <th width="{{$withPercent}}%" class="text-center">Final</th>
                        @endif
                  </tr>
            </thead>
            <tbody>
                  @foreach ($data[0]->data as $item)
                        <tr>
                              <td class="align-middle">{{$item->lastname.', '.$item->firstname}}</td>
                              <td class="align-middle">{{$item->subjCode}}</td>
                              <td class="align-middle">{{$item->sectionDesc}}</td>

                              @if($termSetupOrig->withpre == 1)
                                    @if($item->prelimsubmit == 1)
                                          <td> <button class="btn btn-sm btn-primary btn-block view_grade" data-id="{{$item->id}}" data-term="1">Submitted</button></td>
                                    @elseif($item->prelimsubmit == 2)
                                          <td> <button class="btn btn-sm btn-success btn-block view_grade" data-id="{{$item->id}}" data-term="1">Posted</button></td>
                                    @elseif($item->prelimsubmit == 0)
                                          <td> <button class="btn btn-sm btn-danger btn-block view_grade" disabled>Not Posted</button></td>
                                    @endif
                              @endif

                              @if($termSetupOrig->withmid == 1)
                                    @if($item->midtermsubmit == 1)
                                          <td> <button class="btn btn-sm btn-primary btn-block view_grade" data-id="{{$item->id}}" data-term="2">Submitted</button></td>
                                    @elseif($item->midtermsubmit == 2)
                                          <td> <button class="btn btn-sm btn-success btn-block view_grade" data-id="{{$item->id}}" data-term="2">Posted</button></td>
                                    @elseif($item->midtermsubmit == 0)
                                          <td> <button class="btn btn-sm btn-danger btn-block view_grade" disabled>Not Posted</button></td>
                                    @endif
                              @endif

                              @if($termSetupOrig->withsemi == 1)
                                    @if($item->prefisubmit == 1)
                                          <td> <button class="btn btn-sm btn-primary btn-block view_grade" data-id="{{$item->id}}" data-term="3">Submitted</button></td>
                                    @elseif($item->prefisubmit == 2)
                                          <td> <button class="btn btn-sm btn-success btn-block view_grade" data-id="{{$item->id}}" data-term="3">Posted</button></td>
                                    @elseif($item->prefisubmit == 0)
                                          <td> <button class="btn btn-sm btn-danger btn-block view_grade" disabled>Not Posted</button></td>
                                    @endif
                              @endif

                              @if($termSetupOrig->withfinal == 1)
                                    @if($item->finalsubmit == 1)
                                          <td> <button class="btn btn-sm btn-primary btn-block view_grade" data-id="{{$item->id}}" data-term="4">Submitted</button></td>
                                    @elseif($item->finalsubmit == 2)
                                          <td> <button class="btn btn-sm btn-success btn-block view_grade" data-id="{{$item->id}}" data-term="4">Posted</button></td>
                                    @elseif($item->finalsubmit == 0)
                                          <td> <button class="btn btn-sm btn-danger btn-block view_grade" disabled>Not Posted</button></td>
                                    @endif
                              @endif

                        </tr>
                  @endforeach
            </tbody>

      </table>

@else

      <table class="table table-striped">
            <thead>
                  <tr>
                        <th class="text-center">No Results found</th>
                  </tr>
            </thead>
           
      </table>
@endif