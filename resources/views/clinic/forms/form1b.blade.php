
<style>
.table thead th:first-child  { 
    position: sticky; 
    left: 0; 
    background-color: #fff !important; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    z-index: 999 !important
}

.table tbody td:first-child  {  
    position: sticky; 
    left: 0; 
    background-color: #fff !important; 
    width: 150px !important;
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    z-index: 999 !important
}

.table thead th:first-child  { 
        position: sticky; left: 0; 
        width: 150px !important;
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
}

</style>
    <div class="card-header bg-warning">
        <h4>Medical/Nursing Findings</h4>
    </div>
    <div class="card-body" style="overflow-x: scroll;">
        <div class="row mb-2">
            <div class="col-md-8">
                <h5>{{$studentinfo->lastname}},{{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. {{$studentinfo->suffix}}</h5>
            </div>
            <div class="col-md-4">
                <h5>LRN: {{$studentinfo->lrn}}</h5>
            </div>
        </div>
        <div class="row mb-2">
            <div id="accordion" style="overflow-x: scroll;" class="col-md-12">
                <a class="d-block w-100 text-left" data-toggle="collapse" href="#collapseOne">
                  <strong>LEGEND</strong>
                </a>
                <div id="collapseOne" class="collapse" data-parent="#accordion">
                      <table class="table table-bordered" style="font-size: 11px;">
                          <thead class="text-center">
                              <tr>
                                  <th>NS</th>
                                  <th colspan="3">Vision/ Auditory<br/>Screening</th>
                                  <th>Skin/Scalp</th>
                                  <th>Eye/Ear/Nose</th>
                                  <th>Mouth/Neck/Throat</th>
                                  <th>Heart/Lungs</th>
                                  <th>Abdomen</th>
                                  <th>Deformities</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <td>
                                    a. Normal  Weight	
                                  </td>
                                  <td colspan="3">
                                    Vision 		                                      
                                  </td>
                                  <td>a. Normal		

                                  </td>
                                  <td>a. Normal				

                                  </td>
                                  <td>a. Normal						

                                  </td>
                                  <td>a. Normal					

                                  </td>
                                  <td>a. Normal				

                                  </td>
                                  <td rowspan="2">a. Acquired (Specify)</td>
                              </tr>
                              <tr>
                                <td>:</td>
                                <td>a. Passed
                                </td>
                                <td>L
                                </td>
                                <td>R
                                </td>
                                <td>b. Presence of Lice		
                                </td>
                                <td>b. Inflamed Eye Lid				
                                </td>
                                <td>b. Enlarged tonsils						
                                </td>
                                <td>b. Rales					
                                </td>
                                <td>b. Distended				
                                </td>
                              </tr>
                              <tr>
                                <td>c. Severely Wasted/Underwt	
                                </td>
                                <td>b. Failed

                                </td>
                                <td>L
                                </td>
                                <td>R
                                </td>
                                <td>c. Redness of Skin		

                                </td>
                                <td>c. Eye Redness				
		
                                </td>
                                <td>c. Presence of lesions						
					
                                </td>
                                <td>c. Wheeze					
					
                                </td>
                                <td>c. Abdominal Pain				
				
                                </td>
                                <td rowspan="2">b. Congenital (Specify)			
			
                                </td>
                              </tr>
                              <tr>
                                  <td>d. Overweight	

                                  </td>
                                  <td colspan="3">Auditory		
                                      
                                  </td>
                                  <td>d. White Spots		

                                  </td>
                                  <td>d. Ocular Misalignment				

                                  </td>
                                  <td>d. Inflamed pharynx						

                                  </td>
                                  <td>d. Murmur					

                                  </td>
                                  <td>d. Tenderness				

                                  </td>
                              </tr>
                              <tr>
                                <td>e. Obese	

                                </td>
                                <td>a. Passed

                                </td>
                                <td>L
                                </td>
                                <td>R
                                </td>
                                <td>e. Flaky Skin		

                                </td>
                                <td>e. Pale Conjunctiva				

                                </td>
                                <td>e. Enlarged lymphnodes						

                                </td>
                                <td>e. Irregular heart rate					

                                </td>
                                <td>e. Dysmenorrhea				

                                </td>
                                <td>
                                    
                                </td>
                              </tr>
                              <tr>
                                <td>f. Normal Height	

                                </td>
                                <td>b. Failed

                                </td>
                                <td>L
                                </td>
                                <td>R
                                </td>
                                <td>f. Impetigo/boil		

                                </td>
                                <td>f. Matted Eyelashes				

                                </td>
                                <td>f. Others , specify						

                                </td>
                                <td>f. colds

                                </td>
                                <td>f. Others, Specify				

                                </td>
                                <td>
                                    
                                </td>
                              </tr>
                              <tr>
                                  <td>g. Stunted	
                                </td>
                                  <td colspan="3"></td>
                                  <td>g. Hematoma		
                                </td>
                                  <td>g. Eye  Discharge				
                                </td>
                                  <td></td>
                                  <td>g. Cough					
                                </td>
                                  <td></td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td>h. Severely Stunted	
	
                                </td>
                                  <td colspan="3"></td>
                                  <td>h. Bruises/ Injuries		
                                </td>
                                  <td>h. Ear dischrage				
                                </td>
                                  <td></td>
                                  <td>h. Others, specify					
                                </td>
                                  <td></td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td>i. Tall	

                                </td>
                                  <td colspan="3"></td>
                                  <td>i. Itchiness		
                                </td>
                                  <td>i. Impacted  cerumen				
                                </td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td colspan="3"></td>
                                  <td>j. Skin Lessions</td>
                                  <td>j. Mucus discharge</td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td colspan="3"></td>
                                  <td>k. Acne/Pimple</td>
                                  <td>k. Nose Bleeding (Epistaxis)</td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td colspan="3"></td>
                                  <td>l. Capillary refill greater than 3 seconds</td>
                                  <td>l. Others, specify</td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td colspan="3"></td>
                                  <td>m. others, specify</td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                              </tr>
                          </tbody>
                      </table>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-8">
                <small><strong>Note: Use Letter to record ailments and Place X if not examined</strong></small>
            </div>
            <div class="col-md-4 text-right">
                <button type="button" class="btn btn-danger btn-sm" id="btn-emptyrecord-form" data-userid="{{$studentinfo->userid}}" data-formtype="form1b"><i class="fa fa-folder-open"></i> Empty Record</button>
            </div>
        </div>
        <div class="row table-responsive mb-2" style="height: 600px;">
            <table class="table table-bordered table-head-fixed text-nowrap" style="font-size: 12px;">
                <thead class="text-center">
                    <tr>
                        <th style="min-width: 300px !important;"></th>
                        @foreach($gradelevels as $gradelevel)
                            <th colspan="2" class="bg-info">{{$gradelevel->levelname}}/ SPED</th>
                        @endforeach
                        {{-- <th colspan="2">Kinder/ SPED</th>
                        <th colspan="2">Grade 1/ SPED</th>
                        <th colspan="2">Grade 2/ SPED</th>
                        <th colspan="2">Grade 3/ SPED</th>
                        <th colspan="2">Grade 4/ SPED</th>
                        <th colspan="2">Grade 5/ SPED</th>
                        <th colspan="2">Grade 6/ SPED</th>
                        <th colspan="2">Grade 7/ SPED</th>
                        <th colspan="2">Grade 8/ SPED</th>
                        <th colspan="2">Grade 9/ SPED</th>
                        <th colspan="2">Grade 10/ SPED</th>
                        <th colspan="2">Grade 11/ SPED</th>
                        <th colspan="2">Grade 12/ SPED</th> --}}
                    </tr>
                    <tr>
                        <th></th>
                        @foreach($gradelevels as $gradelevel)
                            <th colspan="2">Findings</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($findingdescs as $findingdesc)
                        <tr class="each-finding" data-id="{{$findingdesc->id}}">
                            <td>
                                <strong>{{$findingdesc->description}}</strong>
                                @if($findingdesc->desctype == 3 || $findingdesc->desctype == 4)
                                    (Uncheck if 'x')
                                @endif
                            </td>
                            @if($findingdesc->desctype == 4) {{--monthcheck--}}
                                @foreach($gradelevels as $gradelevel)
                                    <td class="m-0 p-0 text-center finding-monthcheck" style="vertical-align: middle;" data-levelid="{{$gradelevel->id}}" data-month="jul">
                                        <small>Jul</small><br/>
                                        <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="checkboxPrimaryjulid{{$findingdesc->id}}{{$gradelevel->id}}" @if(count(collect($findingdesc->answers)->where('levelid', $gradelevel->id)->where('monthchecked','jul')->where('finding',1))>0) checked @endif>
                                        <label for="checkboxPrimaryjulid{{$findingdesc->id}}{{$gradelevel->id}}">
                                        </label>
                                        </div>
                                    </td>
                                    <td class="m-0 p-0 text-center finding-monthcheck" style="vertical-align: middle;" data-levelid="{{$gradelevel->id}}" data-month="jan">
                                        <small>Jan</small><br/>
                                        <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="checkboxPrimaryjanid{{$findingdesc->id}}{{$gradelevel->id}}"@if(count(collect($findingdesc->answers)->where('levelid', $gradelevel->id)->where('monthchecked','jan')->where('finding',1))>0) checked @endif>
                                        <label for="checkboxPrimaryjanid{{$findingdesc->id}}{{$gradelevel->id}}">
                                        </label>
                                        </div>
                                    </td>
                                @endforeach
                            @elseif($findingdesc->desctype == 3) {{--check--}}
                                @foreach($gradelevels as $gradelevel)
                                    <td class="m-0 p-0 text-center finding-check" colspan="2" style="vertical-align: middle;" data-levelid="{{$gradelevel->id}}">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="checkboxPrimaryid{{$findingdesc->id}}{{$gradelevel->id}}" @if(count(collect($findingdesc->answers)->where('levelid', $gradelevel->id)->where('finding',1))>0) checked @endif>
                                            <label for="checkboxPrimaryid{{$findingdesc->id}}{{$gradelevel->id}}">
                                            </label>
                                        </div>
                                    </td>
                                @endforeach
                            @else
                                @foreach($gradelevels as $gradelevel)
                                    <td colspan="2" class="m-0 p-0  finding-input" data-levelid="{{$gradelevel->id}}" ><input type=@if($findingdesc->desctype == 1 || $findingdesc->desctype == 2)"text"@elseif($findingdesc->desctype == 5)"date"@endif class="form-control p-0 m-0" @if(count(collect($findingdesc->answers)->where('levelid', $gradelevel->id))>0) value="{{collect($findingdesc->answers)->where('levelid', $gradelevel->id)->first()->finding}}" @endif /></td>
                                @endforeach
                                {{-- <td colspan="2" class="m-0 p-0"><input type=@if($findingdesc->desctype == 1 || $findingdesc->desctype == 2)"text"@elseif($findingdesc->desctype == 5)"date"@endif class="form-control p-0 m-0"/></td>
                                <td colspan="2" class="m-0 p-0"><input type=@if($findingdesc->desctype == 1 || $findingdesc->desctype == 2)"text"@elseif($findingdesc->desctype == 5)"date"@endif class="form-control p-0 m-0"/></td>
                                <td colspan="2" class="m-0 p-0"><input type=@if($findingdesc->desctype == 1 || $findingdesc->desctype == 2)"text"@elseif($findingdesc->desctype == 5)"date"@endif class="form-control p-0 m-0"/></td>
                                <td colspan="2" class="m-0 p-0"><input type=@if($findingdesc->desctype == 1 || $findingdesc->desctype == 2)"text"@elseif($findingdesc->desctype == 5)"date"@endif class="form-control p-0 m-0"/></td>
                                <td colspan="2" class="m-0 p-0"><input type=@if($findingdesc->desctype == 1 || $findingdesc->desctype == 2)"text"@elseif($findingdesc->desctype == 5)"date"@endif class="form-control p-0 m-0"/></td>
                                <td colspan="2" class="m-0 p-0"><input type=@if($findingdesc->desctype == 1 || $findingdesc->desctype == 2)"text"@elseif($findingdesc->desctype == 5)"date"@endif class="form-control p-0 m-0"/></td>
                                <td colspan="2" class="m-0 p-0"><input type=@if($findingdesc->desctype == 1 || $findingdesc->desctype == 2)"text"@elseif($findingdesc->desctype == 5)"date"@endif class="form-control p-0 m-0"/></td>
                                <td colspan="2" class="m-0 p-0"><input type=@if($findingdesc->desctype == 1 || $findingdesc->desctype == 2)"text"@elseif($findingdesc->desctype == 5)"date"@endif class="form-control p-0 m-0"/></td>
                                <td colspan="2" class="m-0 p-0"><input type=@if($findingdesc->desctype == 1 || $findingdesc->desctype == 2)"text"@elseif($findingdesc->desctype == 5)"date"@endif class="form-control p-0 m-0"/></td>
                                <td colspan="2" class="m-0 p-0"><input type=@if($findingdesc->desctype == 1 || $findingdesc->desctype == 2)"text"@elseif($findingdesc->desctype == 5)"date"@endif class="form-control p-0 m-0"/></td>
                                <td colspan="2" class="m-0 p-0"><input type=@if($findingdesc->desctype == 1 || $findingdesc->desctype == 2)"text"@elseif($findingdesc->desctype == 5)"date"@endif class="form-control p-0 m-0"/></td>
                                <td colspan="2" class="m-0 p-0"><input type=@if($findingdesc->desctype == 1 || $findingdesc->desctype == 2)"text"@elseif($findingdesc->desctype == 5)"date"@endif class="form-control p-0 m-0"/></td>
                                <td colspan="2" class="m-0 p-0"><input type=@if($findingdesc->desctype == 1 || $findingdesc->desctype == 2)"text"@elseif($findingdesc->desctype == 5)"date"@endif class="form-control p-0 m-0"/></td> --}}
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-primary" id="btn-savechangesform1b"><i class="fa fa-share"></i> Save changes</button>
            </div>
        </div>
    </div>