@if(count($setupInfo) > 0)

      <table class="table table-bordered table-sm">
            <tr>
                  <td width="20%">Term</td>
                  <td  width="20%">Type</td>
                  <td  width="20%">Calculation</td>
                  <td  width="20%">Status</td>
                  <td  width="20%"></td>
            </tr>
            @if($college_gradestermsetup->withpre == 1)
                  <tr>
                        <td  class="align-middle">Prelim</td>
                        
                        @if($setupInfo[0]->pttype == 1)
                              <td class="align-middle">Average</td>
                        @elseif($setupInfo[0]->pttype == 2)
                              <td class="align-middle">Percentage</td>
                        @endif

                        <td>
                              @if($setupInfo[0]->ptptper != null)
                                    Prelim ( {{number_format($setupInfo[0]->ptptper)}} % )
                              @endif
                        </td>

                        @if($setupInfo[0]->prelimsubmit == 1)
                              <td  class="align-middle">Submitted</td>
                              <td></td>
                        @elseif($setupInfo[0]->prelimsubmit == 2)
                              <td  class="align-middle">Posted</td>
                              <td></td>
                        @elseif($setupInfo[0]->prelimsubmit == 0)
                              <td  class="align-middle">Not Submitted</td>
                              <td><button class="btn btn-success btn-sm  btn-block submitgrades text-left" data-id="1"><i class="fas fa-share-square"></i> Submit Prelim </button></td>
                        @endif
                  </tr>
            @endif

            @if($college_gradestermsetup->withmid == 1)
                  <tr>
                        <td  class="align-middle">Midterm</td>

                        @if($setupInfo[0]->mttype == 1)
                              <td class="align-middle">Average</td>
                        @elseif($setupInfo[0]->mttype == 2)
                              <td class="align-middle">Percentage</td>
                        @endif

                        <td>
                              @if($setupInfo[0]->mtptper != null)
                                    Prelim ( {{number_format($setupInfo[0]->mtptper)}} % ) + 
                              @endif

                              @if($setupInfo[0]->mtmtper != null)
                                    Midterm ( {{number_format($setupInfo[0]->mtmtper)}} % )
                              @endif
                        </td>

                        @if($setupInfo[0]->midtermsubmit == 1)
                              <td  class="align-middle">Submitted</td>
                              <td></td>
                        @elseif($setupInfo[0]->midtermsubmit == 2)
                              <td  class="align-middle">Posted</td>
                              <td></td>
                        @elseif($setupInfo[0]->midtermsubmit == 0)
                              <td  class="align-middle">Not Submitted</td>
                              <td><button class="btn btn-success btn-sm  btn-block submitgrades text-left" data-id="2"><i class="fas fa-share-square"></i> Submit Midterm</button></td>
                        @endif
                      
                  </tr>
            @endif

            @if($college_gradestermsetup->withsemi == 1)
                  <tr>
                        <td  class="align-middle">Semi</td>

                        @if($setupInfo[0]->sttype == 1)
                              <td class="align-middle">Average</td>
                        @elseif($setupInfo[0]->sttype == 2)
                              <td class="align-middle">Percentage</td>
                        @endif

                        <td>
                              @if($setupInfo[0]->stptper != null)
                                    Prelim ( {{number_format($setupInfo[0]->stptper)}} % ) + 
                              @endif
                              @if($setupInfo[0]->stmtper != null)
                                    Midter ( {{number_format($setupInfo[0]->stmtper)}} % ) +
                              @endif
                              @if($setupInfo[0]->ststper != null)
                                    Semi  ( {{number_format($setupInfo[0]->ststper)}} % )
                              @endif
                        </td>

                        @if($setupInfo[0]->prefisubmit == 1)
                              <td  class="align-middle">Submitted</td>
                              <td></td>
                        @elseif($setupInfo[0]->prefisubmit == 2)
                              <td  class="align-middle">Posted</td>
                              <td></td>
                        @elseif($setupInfo[0]->prefisubmit == 0)
                              <td  class="align-middle">Not Submitted</td>
                              <td><button class="btn btn-success btn-sm  btn-block submitgrades text-left" data-id="3"><i class="fas fa-share-square"></i> Submit Semi</button></td>
                        @endif
                      
                  </tr>
            @endif

            @if($college_gradestermsetup->withfinal == 1)
                  <tr>
                        <td  class="align-middle">Final Term</td>
                        
                        @if($setupInfo[0]->fgtype == 1)
                              <td class="align-middle">Average</td>
                        @elseif($setupInfo[0]->fgtype == 2)
                              <td class="align-middle">Percentage</td>
                        @endif

                        <td>
                              @if($setupInfo[0]->ftptper != null)
                                    Prelim ( {{number_format($setupInfo[0]->ftptper)}} % ) + 
                              @endif
                              @if($setupInfo[0]->ftmtper != null)
                                   Midterm ( {{number_format($setupInfo[0]->ftmtper)}} % ) +
                              @endif
                              @if($setupInfo[0]->ftstper != null)
                                    Semi  ( {{number_format($setupInfo[0]->ftstper)}} % ) +
                              @endif
                              @if($setupInfo[0]->ftftper != null)
                                   Final ( {{number_format($setupInfo[0]->ftftper)}} % )
                              @endif
                        </td>

                        @if($setupInfo[0]->finalsubmit == 1)
                              <td  class="align-middle">Submitted</td>
                              <td></td>
                        @elseif($setupInfo[0]->finalsubmit == 2)
                              <td  class="align-middle">Posted</td>
                              <td></td>
                        @elseif($setupInfo[0]->finalsubmit == 0)
                              <td  class="align-middle">Not Submitted</td>
                              <td><button class="btn btn-success btn-sm btn-block submitgrades text-left" data-id="4"><i class="fas fa-share-square"></i> Submit Final</button></td>
                        @endif
                       
                  </tr>
            @endif

           
            <tr>
                  <td  class="align-middle">Final Grade</td>
                  
                  @if($setupInfo[0]->fgtype == 1)
                        <td class="align-middle">Average</td>
                  @elseif($setupInfo[0]->fgtype == 2)
                        <td class="align-middle">Percentage</td>
                  @endif

                  <td>
                        @if($setupInfo[0]->fgptper != null)
                              Prelim ( {{number_format($setupInfo[0]->fgptper)}} % ) + 
                        @endif
                        @if($setupInfo[0]->fgmtper != null)
                             Midterm ( {{number_format($setupInfo[0]->fgmtper)}} % ) +
                        @endif
                        @if($setupInfo[0]->fgstper != null)
                              Semi  ( {{number_format($setupInfo[0]->fgstper)}} % ) +
                        @endif
                        @if($setupInfo[0]->fgftper != null)
                             Final ( {{number_format($setupInfo[0]->fgftper)}} % )
                        @endif
                  </td>

                 <td></td>
                 <td></td>
                 
            </tr>
                  
      </table>

      <table class="table table-bordered table-hover table-striped" style="font-size:12px">

            @php
                  $rawcolspan = 0;
                  if($setupInfo[0]->withpre == 1){
                        $rawcolspan += 1;
                  }

                  if($setupInfo[0]->withmid == 1){
                        $rawcolspan += 1;
                  }

                  if($setupInfo[0]->withsemi == 1){
                        $rawcolspan += 1;
                  }

                  if($setupInfo[0]->withfinal == 1){
                        $rawcolspan += 1;
                  }

                  $fgcolspan = 0;

                  if($setupInfo[0]->withpre == 1){
                        $fgcolspan += 1;
                  }

                  if($setupInfo[0]->withmid == 1){
                        $fgcolspan += 1;
                  }

                  if($setupInfo[0]->withsemi == 1){
                        $fgcolspan += 1;
                  }

                  if($setupInfo[0]->withfinal == 1){
                        $fgcolspan += 1;
                  }

                              
            @endphp

            <thead>
                  <tr>
                        <th></th>
                        <th colspan="{{$rawcolspan}}" class="text-center p-1  bg-info">Raw term grades in percentage</th>
                        <th colspan="{{$fgcolspan}}" class="text-center p-1  bg-secondary">Calculated Grade</th>
                        <th colspan="{{$fgcolspan}}" class="text-center p-1  bg-success">Transmuted Grade</th>
                        
                  </tr>
                  <tr>
                        <th width="30%" class="text-left p-1">Student</th>

                        @if($setupInfo[0]->withpre == 1)
                              <th width="10%" class="text-center p-1  bg-info">Prelim</th>
                        @endif

                        @if($setupInfo[0]->withmid == 1)
                              <th width="10%" class="text-center p-1  bg-info">Midterm</th>
                        @endif

                        @if($setupInfo[0]->withsemi == 1)
                              <th width="10%" class="text-center p-1  bg-info">Semi</th>
                        @endif

                        @if($setupInfo[0]->withfinal == 1)
                              <th width="10%" class="text-center p-1 bg-info">Final</th>
                        @endif

                        @if($setupInfo[0]->withpre == 1 && $setupInfo[0]->pttype != null)
                              <th width="10%" class="text-center p-1 bg-secondary">Prelim</th>
                        @endif

                        @if($setupInfo[0]->withmid == 1 && $setupInfo[0]->mttype != null)
                              <th width="10%" class="text-center p-1 bg-secondary">Midterm</th>
                        @endif

                        @if($setupInfo[0]->withsemi == 1 && $setupInfo[0]->sttype != null)
                              <th width="10%" class="text-center p-1 bg-secondary">Semi</th>
                        @endif

                        @if($setupInfo[0]->withfinal == 1 && $setupInfo[0]->fttype != null)
                              <th width="10%" class="text-center p-1 bg-secondary">Final</th>
                        @endif

                        @if($setupInfo[0]->withpre == 1 && $setupInfo[0]->pttype != null)
                              <th width="10%" class="text-center p-1 bg-success">Prelim</th>
                        @endif

                        @if($setupInfo[0]->withmid == 1 && $setupInfo[0]->mttype != null)
                              <th width="10%" class="text-center p-1 bg-success">Midterm</th>
                        @endif

                        @if($setupInfo[0]->withsemi == 1 && $setupInfo[0]->sttype != null)
                              <th width="10%" class="text-center p-1 bg-success">Semi</th>
                        @endif

                        @if($setupInfo[0]->withfinal == 1 && $setupInfo[0]->fttype != null)
                              <th width="10%" class="text-center p-1 bg-success">Final</th>
                        @endif

                  </tr>
            
            </thead>
            <tbody>
                  @php
                        $male = 0;
                        $female = 0;
                  @endphp
            

                  @foreach ($studentstermgrades as $item)
                        @if($male == 0 && collect($item)->first()->gender == 'MALE')
                              <tr class="bg-secondary">
                                    <th>MALE</th>
                                    @if($setupInfo[0]->withpre == 1)
                                          <th width="10%" class="text-center p-1  bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withmid == 1)
                                          <th width="10%" class="text-center p-1  bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withsemi == 1)
                                          <th width="10%" class="text-center p-1  bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withfinal == 1)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withpre == 1 && $setupInfo[0]->pttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withmid == 1 && $setupInfo[0]->mttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withsemi == 1 && $setupInfo[0]->sttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withfinal == 1 && $setupInfo[0]->fttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withpre == 1 && $setupInfo[0]->pttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withmid == 1 && $setupInfo[0]->mttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withsemi == 1 && $setupInfo[0]->sttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withfinal == 1 && $setupInfo[0]->fttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
                              </tr>
                              @php
                                    $male = 1;
                              @endphp
                        @elseif($female == 0  && collect($item)->first()->gender == 'FEMALE')
                              <tr class="bg-secondary">
                                    <th>FEMALE</th>
                                    @if($setupInfo[0]->withpre == 1)
                                          <th width="10%" class="text-center p-1  bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withmid == 1)
                                          <th width="10%" class="text-center p-1  bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withsemi == 1)
                                          <th width="10%" class="text-center p-1  bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withfinal == 1)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withpre == 1 && $setupInfo[0]->pttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withmid == 1 && $setupInfo[0]->mttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withsemi == 1 && $setupInfo[0]->sttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withfinal == 1 && $setupInfo[0]->fttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withpre == 1 && $setupInfo[0]->pttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withmid == 1 && $setupInfo[0]->mttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withsemi == 1 && $setupInfo[0]->sttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
            
                                    @if($setupInfo[0]->withfinal == 1 && $setupInfo[0]->fttype != null)
                                          <th width="10%" class="text-center p-1 bg-secondary"></th>
                                    @endif
                              </tr>
                              @php
                                    $female = 1;
                              @endphp
                        @endif
                        @php
                              $ptptper = number_format(0,2);
                              $mtmtper = number_format(0,2);
                              $ststper = number_format(0,2);
                              $ftftper = number_format(0,2);

                              $prelim = number_format(0,2);
                              $midterm = number_format(0,2);
                              $semi = number_format(0,2);
                              $final = number_format(0,2);
                              $finalgrade = number_format(0,2);

                              $calculatedpre = 0;
                              $calculatedmid = 0;
                              $calculatedsemi = 0;
                              $calculatedfinal = 0;

                              $trasmutation = DB::table('college_gradetransmutation')->get();

                              if($setupInfo[0]->withpre == 1){
                                    
                                    try{

                                          $prelim = collect($item)->where('term',1)->first()->ig;

                                    }catch (\Exception $e){

                                          $prelim = number_format(0,2);

                                    }

                                    $calculatedpre = number_format( $prelim * ( $setupInfo[0]->ptptper / 100 ),2);
                              }

                              if($setupInfo[0]->withmid == 1){

                                    try{

                                          $midterm = collect($item)->where('term',2)->first()->ig;

                                    }catch (\Exception $e){

                                          $midterm = number_format(0,2);
                                    }

                                    if($setupInfo[0]->mttype == 1){

                                          $itemcount = 0;

                                          if($setupInfo[0]->mtptper != null){
                                                $itemcount += 1;
                                          }
                                          if($setupInfo[0]->mtmtper != null){
                                                $itemcount += 1;
                                          }

                                          $mtmtper = number_format ( ( $prelim + $midterm ) / $itemcount , 2);


                                    }
                                    else{

                                          $calculatedpremt = 0;
                                          $calculatedmidmt = 0;

                                          if($setupInfo[0]->mtptper != null){

                                                $calculatedpremt = number_format( $calculatedpre * (  $setupInfo[0]->mtptper / 100 ),2);
                                          }
                                          if($setupInfo[0]->mtmtper != null){

                                                $calculatedmidmt = number_format( $midterm * ( $setupInfo[0]->mtmtper / 100 ),2);
                                          }

                                          // $mtptper = number_format( $ptptper * ( $setupInfo[0]->mtptper / 100 ),2);

                                          // $mtmtper = number_format( $midterm * ( $setupInfo[0]->mtmtper / 100 ),2);

                                          // $mtmtper = $mtmtper +  $mtptper;

                                          $calculatedmid = number_format($calculatedpremt +  $calculatedmidmt,2) ;

                                    }


                              }

                              if($setupInfo[0]->withsemi == 1){

                                    try{

                                          $semi = collect($item)->where('term',3)->first()->ig;

                                    }catch (\Exception $e){

                                          $semi = number_format(0,2);

                                    }

                                    if($setupInfo[0]->sttype == 1){

                                          $itemcount = 0;

                                          if($setupInfo[0]->mtptper != null){
                                                $itemcount += 1;
                                          }
                                          if($setupInfo[0]->mtmtper != null){
                                                $itemcount += 1;
                                          }

                                          $mtmtper = number_format ( ( $prelim + $midterm ) / $itemcount , 2);


                                    }

                                    else{

                                          $calculatedsemist = 0;
                                          $calculatedmidst = 0;
                                          $calculatedprefist = 0;

                                          if($setupInfo[0]->stptper != null){

                                                $calculatedsemist = number_format( $calculatedpre * (  $setupInfo[0]->stptper / 100 ),2);
                                          }
                                          if($setupInfo[0]->stmtper != null){

                                                $calculatedmidst = number_format( $calculatedmid * ( $setupInfo[0]->stmtper / 100 ),2);
                                          }
                                          if($setupInfo[0]->ststper != null){

                                                $calculatedprefist = number_format( $semi * ( $setupInfo[0]->ststper / 100 ),2);
                                          }

                                          $calculatedsemi = number_format($calculatedsemist +  $calculatedmidst + $calculatedprefist,2);

                                    }

                              }

                              if($setupInfo[0]->withfinal == 1){



                                    try{

                                          $final = collect($item)->where('term',4)->first()->ig;

                                    }catch (\Exception $e){

                                          $final = number_format(0,2);

                                    }

                                    $calculatedsemift = 0;
                                    $calculatedmidft = 0;
                                    $calculatedprefift = 0;
                                    $calculatedfinalft = 0;

                                    if($setupInfo[0]->ftptper != null){

                                          $calculatedsemift = number_format( $calculatedpre * (  $setupInfo[0]->ftptper / 100 ),2);
                                    }
                                    if($setupInfo[0]->ftmtper != null){

                                          $calculatedmidft = number_format( $calculatedmid * ( $setupInfo[0]->ftmtper / 100 ),2);
                                    }
                                    if($setupInfo[0]->ftstper != null){

                                          $calculatedprefift = number_format( $calculatedsemi * ( $setupInfo[0]->ftstper / 100 ),2);
                                    }
                                    if($setupInfo[0]->ftftper != null){

                                          $calculatedfinalft = number_format( $final * ( $setupInfo[0]->ftftper / 100 ),2);

                                    }

                                    $calculatedfinal = number_format($calculatedsemift +  $calculatedmidft + $calculatedprefift + $calculatedfinalft,2);


                              }

                              if($setupInfo[0]->withfinal == 1){

                                    $calculatedsemifg = 0;
                                    $calculatedmidfg = 0;
                                    $calculatedprefg = 0;
                                    $calculatedfinalfg = 0;


                                    if($calculatedsemi != null){

                                          $calculatedsemifg = number_format( $calculatedpre * (  $setupInfo[0]->fgptper / 100 ),2);

                                    }
                                    if($calculatedmid != null){

                                          $calculatedmidfg = number_format( $calculatedmid * (  $setupInfo[0]->fgmtper / 100 ),2);
                                          
                                    }
                                    if($calculatedsemi != null){

                                          $calculatedprefg = number_format( $calculatedsemi * (  $setupInfo[0]->fgstper / 100 ),2);
                                                                              
                                    }
                                    if($calculatedfinal != null){

                                          $calculatedfinalfg = number_format( $calculatedfinal * (  $setupInfo[0]->fgftper / 100 ),2);
                                                                              
                                    }


                                    $calculatedfinal = number_format($calculatedsemifg + $calculatedmidfg +  $calculatedprefg + $calculatedfinalfg,2);

                              }
                              
                        @endphp

                  
                        @php

                              $prefitransmuted = null;
                              $midtransmuted = null;
                              $semitransmuted = null;
                              $finaltransmuted = null;

                              if( $calculatedpre != 0 && $calculatedpre != null){

                                    $prefitransmuted = collect($trasmutation)
                                                        ->where('gfrom','<=',$calculatedpre)
                                                        ->where('gto','>=',$calculatedpre)
                                                        ->first();
                                    try{
                                          $prefitransmuted =  collect($prefitransmuted)['transmutation'];
                                    } catch (\Exception $e){ }

                              }
                              if($calculatedmid != 0 && $calculatedmid != null){

                                    $midtransmuted = collect($trasmutation)
                                                        ->where('gfrom','<=',$calculatedmid)
                                                        ->where('gto','>=',$calculatedmid)
                                                        ->first();
                                    try{
                                          $midtransmuted =  collect($midtransmuted)['transmutation'];
                                    } catch (\Exception $e){ }

                              }
                              if($calculatedsemi != 0 && $calculatedsemi != null){

                                    $semitransmuted = collect($trasmutation)
                                                        ->where('gfrom','<=',$calculatedsemi)
                                                        ->where('gto','>=',$calculatedsemi)
                                                        ->first();
                                    try{
                                          $semitransmuted =  collect($semitransmuted)['transmutation'];
                                    } catch (\Exception $e){ }

                              }
                              if($calculatedfinal != 0 && $calculatedfinal != null){

                                    $finaltransmuted = collect($trasmutation)
                                                        ->where('gfrom','<=',$calculatedfinal)
                                                        ->where('gto','>=',$calculatedfinal)
                                                        ->first();
                                    try{
                                          $finaltransmuted =  collect($finaltransmuted)['transmutation'];
                                    }catch (\Exception $e){}

                              }

                             

                           

                        @endphp

                        <tr>
                              <td>{{$item[0]->lastname.' ,'.$item[0]->firstname}}</td>
                              @if($setupInfo[0]->withpre == 1)
                                    <td class="text-center bg-info">{{$prelim}}</td>
                              @endif
                              @if($setupInfo[0]->withmid == 1)
                                    <td class="text-center bg-info">{{$midterm}}</td>
                              @endif
                              @if($setupInfo[0]->withsemi == 1)
                                    <td class="text-center bg-info">{{$semi}}</td>
                              @endif
                              @if($setupInfo[0]->withfinal == 1)
                                    <td class="text-center bg-info">{{$final}}</td>
                              @endif

                              @if($setupInfo[0]->withpre == 1)
                                    <td class="text-center bg-secondary">{{$calculatedpre}}</td>
                              @endif
                              @if($setupInfo[0]->withmid == 1 )
                                    <td class="text-center bg-secondary">{{number_format($calculatedmid,2)}}</td>
                              @endif
                              @if($setupInfo[0]->withsemi == 1)
                                    <td class="text-center bg-secondary">{{number_format($calculatedsemi,2)}}</td>
                              @endif
                              @if($setupInfo[0]->withfinal == 1)
                                    <td class="text-center bg-secondary">{{number_format($calculatedfinal,2)}}</td>
                              @endif

                              @if($setupInfo[0]->withpre == 1)
                                    <td class="text-center bg-success">{{$prefitransmuted}}</td>
                              @endif
                              @if($setupInfo[0]->withmid == 1 )
                                    <td class="text-center bg-success">{{$midtransmuted}}</td>
                              @endif
                              @if($setupInfo[0]->withsemi == 1)
                                    <td class="text-center bg-success">{{$semitransmuted}}</td>
                              @endif
                              @if($setupInfo[0]->withfinal == 1)
                                    <td class="text-center bg-success">{{$finaltransmuted}}</td>
                              @endif
                        </tr>
                  @endforeach
            </tbody>
      </table>
            
    


@endif




{{-- @if(count($setupInfo) > 0)

      @if($setupInfo[0]->type == 1)

            <table class="table" style="font-size:12px">
                  <thead>
                        <tr>
                              <th></th>
                              <th colspan="4" class="text-center p-1">Raw term grades in percentage (%)</th>
                              <th></th>
                              <th></th>
                              <th class="p-1"></th>
                        </tr>
                        <tr>
                              <th width="30%" class="text-left p-1">Student</th>
                              <th width="10%" class="text-center p-1">Prelim Term</th>
                              <th width="10%" class="text-center p-1">Midterm Term</th>
                              <th width="10%" class="text-center p-1">Semi Term</th>
                              <th width="10%" class="text-center p-1">Final Term</th>
                              <th width="10%" class="text-center p-1">Final Grade</th>
                              <th width="10%" class="text-center p-1">Transmuted</th>
                              <th width="10%" class="text-center p-1">Remarks</th>
                        </tr>
                  
                  </thead>
                  <tbody>
                        <tr>
                              <td class="p-1"></td>
                              <td class="p-1">
                                    @if($setupInfo[0]->prelimsubmit != 1)
                                          <button class="btn btn-success btn-sm btn-block submitgrades" data-id="1">Submit</button>
                                    @endif
                              </td>
                              <td class="p-1">
                                    @if($setupInfo[0]->midtermsubmit != 1 && $setupInfo[0]->prelimsubmit == 1)
                                          <button class="btn btn-success btn-sm btn-block submitgrades" data-id="2">Submit</button>
                                    @endif
                              </td>
                              <td class="p-1">
                                    @if($setupInfo[0]->prefisubmit != 1 && $setupInfo[0]->midtermsubmit == 1)
                                          <button class="btn btn-success btn-sm btn-block submitgrades" data-id="3">Submit</button>
                                    @endif
                              </td>
                              <td class="p-1">
                                    @if($setupInfo[0]->finalsubmit != 1 && $setupInfo[0]->prefisubmit == 1)
                                          <button class="btn btn-success btn-sm btn-block submitgrades" data-id="4">Submit</button>
                                    @endif
                              </td>
                              <td class="p-1"></td>
                              <td class="p-1"></td>
                              <td class="p-1"></td>
                        </tr>
                        @foreach ($studentstermgrades as $item)
                              <tr>
                                    @php
                                          try{
                                                $prelim = collect($item)->where('term',1)->first()->termgrade;
                                          }catch (\Exception $e){
                                                $prelim = number_format(0,2);
                                          }
                                          try{
                                                $midterm = collect($item)->where('term',2)->first()->termgrade;
                                          }catch (\Exception $e){
                                                $midterm = number_format(0,2);
                                          }
                                          try{
                                                $semifi = collect($item)->where('term',3)->first()->termgrade;
                                          }catch (\Exception $e){
                                                $semifi = number_format(0,2);
                                          }
                                          try{
                                                $final = collect($item)->where('term',4)->first()->termgrade;
                                          }catch (\Exception $e){
                                                $final = number_format(0,2);
                                          }

                                          $transmutation = DB::table('college_gradetransmutation')->get();

                                          $total = number_format( ( $prelim + $midterm + $semifi + $final ) /  4  ,2);

                                          $finalgrade = collect($transmutation )
                                                      ->where('gfrom','<=',$total)
                                                      ->where('gto','>=',$total)
                                                      ->first();

                                    @endphp

                                    <td class="text-left">{{$item[0]->lastname.', '.$item[0]->firstname}}</td>
                                    <td class="text-center">{{$prelim}}</td>
                                    <td class="text-center">{{$midterm}}</td>
                                    <td class="text-center">{{$semifi}}</td>
                                    <td class="text-center">{{$final}}</td>
                                    <td class="text-center">{{$total}}</td>
                                    <td class="p-1 text-center align-middle">{{$finalgrade->transmutation}}</td>
                                    <td class="text-center"> 
                                          @if($total >= 75)
                                                Passed
                                          @else
                                                Failed
                                          @endif
                                    </td>
                              </tr>
                        
                        @endforeach
                  </tbody>
            </table>

      @elseif($setupInfo[0]->type == 2)

            <table class="table" style="font-size:12px">
                  <thead>
                        <tr>
                              <th></th>
                              <th colspan="4" class="text-center p-1">Raw term grades in percentage (%)</th>
                              <th colspan="2" class="text-center p-1">Trasmuted Term Grade</th>
                              <th class="text-center p-1"></th>
                        </tr>
                        <tr>
                              <th width="37%" class="text-left p-1 align-middle">Student</th>
                              <th width="9%" class="text-center p-1 align-middle">Prelim</th>
                              <th width="9%" class="text-center p-1 align-middle">Mid</th>
                              <th width="9%" class="text-center p-1 align-middle">Semi</th>
                              <th width="9%" class="text-center p-1 align-middle">Final</th>
                              <th width="9%" class="text-center p-1 align-middle">Mid Term ({{$setupInfo[0]->mid}})</th>
                              <th width="9%" class="text-center p-1 align-middle">Final Term ({{$setupInfo[0]->final}})</th>
                              <th width="9%" class="text-center p-1 align-middle">Final Grade</th>

                              
                        </tr>
                  
                  </thead>
                  <tbody>
                       
                        
                        @foreach ($studentstermgrades as $item)

                        <tr>
                              
                              @php

                                    $midper = number_format( $setupInfo[0]->mid / 100 ,2);
                                    $fiper = number_format( $setupInfo[0]->final / 100 ,2);
                                    $transmutation = DB::table('college_gradetransmutation')->get();
                                    $withmidGrades = false;
                                    $withfinalGrades = false;

                                    try{
                                          $prelim = collect($item)->where('term',1)->first()->termgrade;

                                    }catch (\Exception $e){
                                          $prelim = number_format(0,2);
                                    }


                                    try{
                                          $midterm = collect($item)->where('term',2)->first()->termgrade ;
                                          
                                    }catch (\Exception $e){

                                          $midterm = number_format(0,2) ;
                                    }
                                    try{

                                          $semifi = collect($item)->where('term',3)->first()->termgrade;

                                    }catch (\Exception $e){

                                          $semifi =  number_format(0,2);
                                    }
                                    try{

                                          $final = collect($item)->where('term',4)->first()->termgrade ;

                                    }catch (\Exception $e){

                                          $final =  number_format(0,2) ;

                                    }

                                    

                                    $midtermtotal = $prelim + $midterm;
                                    $finaltotal = $semifi + $final;

                                    $midtermtotal =  number_format( $midtermtotal / 2 , 2 ) ;
                                    $finaltotal =  number_format( $finaltotal / 2 , 2) ;


                                    if($midterm != 0.00 && $withmidGrades == false){

                                          $withmidGrades = true;

                                    }

                                    if($final != 0.00 && $withfinalGrades == false){

                                          $withfinalGrades = true;

                                    }

                                    $transmuttedmid = collect($transmutation)
                                                      ->where('gfrom','<=',$midtermtotal)
                                                      ->where('gto','>=',$midtermtotal)
                                                      ->first();

                                    $transmuttedfinal = collect($transmutation)
                                                      ->where('gfrom','<=',$finaltotal)
                                                      ->where('gto','>=',$finaltotal)
                                                      ->first();

                                    $midtermtotal = number_format($midtermtotal * $midper , 2);
                                    $finaltotal = number_format($finaltotal * $fiper , 2);

                                    $total =  $midtermtotal +  $finaltotal ;

                                    $finalgrade = collect($transmutation)
                                                      ->where('gfrom','<=',$total)
                                                      ->where('gto','>=',$total)
                                                      ->first();


                              @endphp

                              

                              <td class="text-left">{{$item[0]->lastname.', '.$item[0]->firstname}}</td>
                              <td class="p-1 text-center align-middle">{{$prelim}}</td>
                              <td class="p-1 text-center align-middle">{{$midterm}}</td>
                              <td class="p-1 text-center align-middle">{{$semifi}}</td>
                              <td class="p-1 text-center align-middle">{{$final}}</td>

                              @if( $withmidGrades && isset($transmuttedmid->transmutation))
                                    <td class="text-center align-middle">{{$transmuttedmid->transmutation}}</td>
                              @else
                                    <td></td>
                              @endif

                              @if($withfinalGrades  && isset($transmuttedfinal->transmutation))
                                    <td class="text-center align-middle">{{$transmuttedfinal->transmutation}}</td>
                              @else
                                    <td></td>
                              @endif

                              @if(isset($finalgrade->transmutation))
                                    <td class="text-center align-middle">{{$finalgrade->transmutation}}</td>
                              @else
                                    <td></td>
                              @endif
                            
                        </tr>
                        @endforeach
                        <tr>
                              <td class="p-1"></td>
                              <td class="p-1"></td>
                              <td class="p-1"></td>
                              <td class="p-1"></td>
                              <td class="p-1"></td>
                        
                              <td class="p-1 text-center">
                                    @if($setupInfo[0]->midtermsubmit != 1 &&  $withmidGrades)
                                          <button class="btn btn-success btn-sm submitgrades" data-id="2">Submit</button>
                                    @endif
                              </td>
                              <td class="p-1 text-center">
                                    @if($setupInfo[0]->finalsubmit != 1 && $setupInfo[0]->midtermsubmit == 1 )
                                          <button class="btn btn-sm btn-success btn-block submitgrades" data-id="4">Submit</button>
                                    @endif
                              </td>
                              <td></td>
                              
                        </tr>
                  </tbody>
            </table>


      @elseif($setupInfo[0]->type == 3)

            <table class="table table-bordered" style="font-size:11px">
                  <thead>
                        <tr>
                              <tr>
                                    <th></th>
                                    <th colspan="4" class="text-center">TERM</th>
                                  
                                    <th></th>
                                    <th></th>
                              </tr>
                        </tr>
                        <tr>
                              <th width="28%" class="text-left">Student</th>
                              <th width="12%" class="text-center">Prelim ( {{$setupInfo[0]->semi}} %)</th>
                              <th width="12%" class="text-center">Midterm ( {{$setupInfo[0]->mid}} %)</th>
                              <th width="12%" class="text-center">Semi ( {{$setupInfo[0]->pre}} %)</th>
                              <th width="12%" class="text-center">Final ( {{$setupInfo[0]->final}} %)</th>
                              <th width="12%" class="text-center">Total</th>
                              <th width="12%" class="text-center">Remarks</th>
                        </tr>
                  
                  </thead>
                  <tbody>
                        <tr>
                              <td class="p-1"></td>
                              <td class="p-1">
                                    @if($setupInfo[0]->prelimsubmit != 1)
                                          <button class="btn btn-success btn-sm btn-block submitgrades" data-id="1">Submit</button>
                                    @endif
                              </td>
                              <td class="p-1">
                                    @if($setupInfo[0]->midtermsubmit != 1 && $setupInfo[0]->prelimsubmit == 1)
                                          <button class="btn btn-success btn-sm btn-block submitgrades" data-id="2">Submit</button>
                                    @endif
                              </td>
                              <td class="p-1">
                                    @if($setupInfo[0]->prefisubmit != 1 && $setupInfo[0]->midtermsubmit == 1)
                                          <button class="btn btn-success btn-sm btn-block submitgrades" data-id="3">Submit</button>
                                    @endif
                              </td>
                              <td class="p-1">
                                    @if($setupInfo[0]->finalsubmit != 1 && $setupInfo[0]->prefisubmit == 1)
                                          <button class="btn btn-success btn-sm btn-block submitgrades" data-id="4">Submit</button>
                                    @endif
                              </td>
                              <td class="p-1"></td>
                              <td class="p-1"></td>
                        </tr>
                        @foreach ($studentstermgrades as $item)

                        <tr>
                              
                              @php
                                    $semi = number_format( $setupInfo[0]->pre / 100 ,2);
                                    $midper = number_format( $setupInfo[0]->mid / 100 ,2);
                                    $pre = number_format( $setupInfo[0]->semi / 100 ,2);
                                    $fiper = number_format( $setupInfo[0]->final / 100 ,2);

                                    try{
                                          $prelim = collect($item)->where('term',1)->first()->termgrade;

                                    }catch (\Exception $e){
                                          $prelim = number_format(0,2);
                                    }


                                    try{
                                          $midterm = collect($item)->where('term',2)->first()->termgrade ;
                                          
                                    }catch (\Exception $e){

                                          $midterm = number_format(0,2) ;
                                    }
                                    try{

                                          $semifi = collect($item)->where('term',3)->first()->termgrade;

                                    }catch (\Exception $e){

                                          $semifi =  number_format(0,2);
                                    }
                                    try{

                                          $final = collect($item)->where('term',4)->first()->termgrade ;

                                    }catch (\Exception $e){

                                          $final =  number_format(0,2) ;

                                    }

                                    $prelimper = number_format($prelim * $pre , 2);
                                    $midtermper = number_format($midterm * $midper , 2);
                                    $semifiper = number_format($semifi * $semi , 2);
                                    $finalper = number_format($final * $fiper , 2);

                                    $finalgrade = $prelimper + $midtermper +  $semifiper + $finalper ;

                                    $transmutation = DB::table('college_gradetransmutation')->get();

                                    $finalgrade = collect($transmutation)
                                                      ->where('gfrom','<=',$finalgrade)
                                                      ->where('gto','>=',$finalgrade)
                                                      ->first();

                              @endphp

                              <td class="text-left">{{$item[0]->lastname.', '.$item[0]->firstname}}</td>
                              <td class="text-center">{{$prelim}}</td>
                              <td class="text-center">{{$midterm}}</td>
                              <td class="text-center">{{$semifi}}</td>
                              <td class="text-center">{{$final}}</td>

                              @if(isset($finalgrade->transmutation))

                                    <td class="text-center">{{$finalgrade->transmutation}}</td>
                                    <td class="text-center"> 
                                          @if($finalgrade->transmutation <= 3)
                                                Passed
                                          @else
                                                Failed
                                          @endif
                                    </td>
                                    
                              @else
                                    <td></td>
                                    <td></td>
                              @endif
                        
                        </tr>
                        
                        @endforeach
                  </tbody>
            </table>

      @endif
@endif --}}
