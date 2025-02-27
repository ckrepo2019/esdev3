@php
      $detailcount = count($evaluationDetail );
      $witdh = 50 / $detailcount;
      $ratingValueCount = count($ratingvalue)
@endphp

<div class="row">
      <div class="col-md-12 table-responsive">
            <table class="table table-bordered table-sm" style="min-width:500px">
                  <thead>
                        <tr>
                              <th>Question</th>
                              <th colspan="{{count($ratingvalue)}}">Responses</th>
                              <th></th>
                        </tr>
                        <tr>
                              <th width="50%"></th>
                              @foreach ($ratingvalue as $ratingitem)
                                    <th width="{{$witdh}}" class="text-center align-middle dth"  data-rt="{{$ratingitem->id}}">{{$ratingitem->value}}</th>
                              @endforeach
                              <th width="10%" class="text-center">Total</th>
                        </tr>
                  </thead>
                  <tbody>
                        @foreach ($evaluationDetail as $item)
                              <tr class="dd" data-id="{{$item->id}}">
                                    <td width="50%" >{{$item->description}}</td>
                                    @foreach ($ratingvalue as $ratingitem)
                                          <td class="text-center align-middle dt" data-dd="{{$item->id}}" data-rt="{{$ratingitem->id}}"></td>
                                    @endforeach
                                    <td class="text-center align-middle dttotal" data-dd="{{$item->id}}"></td>
                              </tr>
                        @endforeach
                  </tbody>
            </table>
      </div>
</div>

{{-- <div class="row">
      <div class="col-md-12 table-responsive">
            <table class="table table-bordered" style="min-width:500px">
                  <thead>
                        <tr>
                              <th width="10%">Section</th>
                              <th width="20%">Subject</th>
                              <th width="5%" style="font-size:11px" class="text-center">Number of Student</th>
                              <th width="5%" style="font-size:11px" class="text-center">Number of Responses</th>
                              @foreach ($evaluationDetail as $item)
                                    <th style="font-size:10px" width="{{$witdh}}%" class="align-middle text-center" colspan="{{$ratingValueCount}}">{{$item->description}}</th>
                              @endforeach
                        </tr>
                        <tr>
                              <th colspan="4">Rating Value</th>
                              @foreach ($evaluationDetail as $item)
                                    @foreach ($ratingvalue as $ratingitem)
                                          <th>{{$ratingitem->description}}</th>
                                    @endforeach
                              @endforeach

                        </tr>
                      
                  </thead>
                  <tbody>
                        @php
                              $totalStudent = 0;
                        @endphp
                        @foreach($subjects as $key=>$item)
                              @php
                                    $totalStudent += $item->studcount;
                              @endphp

                              <tr class="assignment" data-subj="{{$item->subjid}}" data-section="{{$item->id}}" data-key="{{$key}}">
                                    <td data-key="{{$key}}">{{$item->sectionname}}</td>
                                    <td>{{Str::limit($item->subjdesc,'10','...')}}</td>
                                    <td class="text-center">{{$item->studcount}}</td>
                                    <td data-key="{{$key}}" class="repsondents text-center"></td>
                                    @foreach ($evaluationDetail as $item)
                                          @foreach ($ratingvalue as $ratingitem)
                                                <td class="rd" data-key="{{$key}}" data-ed="{{$item->id}}" data-rt="{{$ratingitem->id}}"></td>
                                          @endforeach
                                    @endforeach
                              </tr>
                        @endforeach
                        <tr>
                              <td colspan="2">TOTAL</td>
                              <td  class="tt text-center" data-ed="{{$item->id}}" data-rt="{{$ratingitem->id}}">{{$totalStudent}}</td>
                              <td  class="tt text-center" data-ed="{{$item->id}}" data-rt="{{$ratingitem->id}}"> </td>
                              @foreach ($evaluationDetail as $item)
                                    @foreach ($ratingvalue as $ratingitem)
                                          <td class="tt" data-ed="{{$item->id}}" data-rt="{{$ratingitem->id}}">0</td>
                                    @endforeach
                              @endforeach
                        </tr>
                  </tbody>
            </table>
      </div>
</div> --}}
{{-- <div class="row">
      <label for="">Summary</label>
</div> --}}
