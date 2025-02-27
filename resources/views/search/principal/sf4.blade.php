

<table class="mb-0 table-bordered table table-hover" style="min-width:300px">
    <thead>
        <tr class="text-center ">
            <td style="width:50px !important" class="t-sm align-middle text-center" rowspan="3">GRADE / YEAR LEVEL</td> 
            <td style=" width:80px !important;" class="align-middle" rowspan="3">SECTION</td> 
            <td style="width:130px !important" class="t-md align-middle" rowspan="3" >NAME OF ADVISER</td> 
           
            <td style="width:120px !important; " class="t-md align-middle" rowspan="2" colspan="3">REGISTERED<br>LEARNERS<br>(As of End of the Month)</td>

            <th style="width:200px !important; " colspan="6">ATTENDANCE</th>
            <th style="width:320px !important; " colspan="9">DROPPED OUT</th>
            <th style="width:315px !important; " colspan="9">TRANSFERRED OUT</th>
            <th style="width:315px !important; " colspan="9">TRANSFERRED IN</th>
        </tr>
        <tr class="text-center">
            <td class="align-middle"  colspan="3">Daily Average</td>
            <td class="align-middle"  colspan="3">Percentage for the Month</td>

            <td class="align-middle"  colspan="3">(A) Cumulative as of Previous Month</td>
            <td class="align-middle"  colspan="3">(B) For the Month</td>
            <td class="align-middle"  colspan="3">(A+B) Cumulative as of End of the Month
                </td>

            <td class="align-middle"  colspan="3">(A) Cumulative as of Previous Month</td>
            <td class="align-middle"  colspan="3">(B) For the Month</td>
            <td class="align-middle"  colspan="3">(A+B) Cumulative as of End of the Month
                </td>

            <td class="align-middle"  colspan="3">(A) Cumulative as of Previous Month</td>
            <td class="align-middle"  colspan="3">(B) For the Month</td>
            <td class="align-middle"  colspan="3">(A+B) Cumulative as of End of the Month
                </td>

        </tr>
        <tr class="text-center">
            <td>M</td>
            <td>F</td>
            <td>T</td>

            <td>M</td>
            <td>F</td>
            <td>T</td>
            <td>M</td>
            <td>F</td>
            <td>T</td>

            <td>M</td>
            <td>F</td>
            <td>T</td>
            <td>M</td>
            <td>F</td>
            <td>T</td>
            <td>M</td>
            <td>F</td>
            <td>T</td>

            <td>M</td>
            <td>F</td>
            <td>T</td>
            <td>M</td>
            <td>F</td>
            <td>T</td>
            <td>M</td>
            <td>F</td>
            <td>T</td>

            <td>M</td>
            <td>F</td>
            <td>T</td>
            <td>M</td>
            <td>F</td>
            <td>T</td>
            <td>M</td>
            <td>F</td>
            <td>T</td>
        </tr>
    </thead>
    <tbody>
            @if($sections[0]->count != 0)
                @foreach($sections[0]->data as $section)
                    <tr class="text-center">
                        <td class="text-center">{{$section->levelname}}</td>
                        <td>{{$section->sectionname}}</td>
                        <td  class="text-center">
                            @if($section->lastname != null)
                                {{$section->lastname}} , {{$section->firstname}}
                            @else
                                NO ADVICER
                            @endif
                        </td>
                        <td>{{$section->male}}</td>
                        <td>{{$section->female}}</td>
                        <td>{{$section->male + $section->female}}</td>

                        <td>{{$section->maleAtt}}</td>
                        <td>{{$section->femaleAtt}}</td>
                        <td>{{$section->maleAtt + $section->femaleAtt}}</td>

                        <td>
                            @if($section->male != 0)
                                {{round(($section->maleAtt / $section->male) * 100)}}%
                            @else
                                0%
                            @endif
                        </td>
                        <td>
                            @if($section->female != 0)
                                {{round(($section->femaleAtt / $section->female) * 100)}}%
                            @else
                                0%
                            @endif
                        </td>
                        <td>
                            @if($section->female != 0 || $section->male != 0)
                                {{
                                round((($section->femaleAtt + $section->maleAtt) / ($section->female +  $section->male)) * 100)
                                }}%
                            @else
                                0%
                            @endif

                        </td>

                        <td>{{$section->prevdropoutmale}}</td>
                        <td>{{$section->prevdropoutfemale}}</td>
                        <td>{{$section->prevdropoutmale + $section->prevdropoutfemale}}</td>
                        <td>{{$section->dropoutmale}}</td>
                        <td>{{$section->dropoutfemale}}</td>
                        <td>{{$section->dropoutmale + $section->dropoutfemale}}</td>
                        
                        <td>{{$section->dropoutmale + $section->prevdropoutmale}}</td>
                        <td>{{$section->dropoutfemale + $section->prevdropoutfemale}}</td>
                        <td>{{$section->prevdropoutmale + $section->prevdropoutfemale + $section->dropoutmale + $section->dropoutfemale}}</td>

                        <td>{{$section->prevtransoutmale}}</td>
                        <td>{{$section->prevtransoutfemale}}</td>
                        <td>{{$section->prevtransoutmale + $section->prevtransoutfemale}}</td>
                        <td>{{$section->transoutmale}}</td>
                        <td>{{$section->transoutfemale}}</td>
                        <td>{{$section->transoutmale + $section->transoutfemale}}</td>

                        <td>{{$section->transoutmale + $section->prevtransoutmale}}</td>
                        <td>{{$section->transoutfemale + $section->prevtransoutfemale}}</td>
                        <td>{{$section->prevtransoutmale + $section->prevtransoutfemale + $section->transoutmale + $section->transoutfemale}}</td>

                        <td>{{$section->prevtransinmale}}</td>
                        <td>{{$section->prevtransinfemale}}</td>
                        <td>{{$section->prevtransinmale + $section->prevtransinfemale}}</td>
                        <td>{{$section->transinmale}}</td>
                        <td>{{$section->transinfemale}}</td>
                        <td>{{$section->transinmale + $section->transinfemale}}</td>

                        <td>{{$section->transinmale + $section->prevtransinmale}}</td>
                        <td>{{$section->transinfemale + $section->prevtransinfemale}}</td>
                        <td>{{$section->transinmale + $section->transinfemale + $section->prevtransinmale + $section->prevtransinfemale}}</td>

                    </tr>
                @endforeach
            @endif
        <tr>
            <td colspan="3">ELEMENTARY/SECONDARY:</td>
            <td  colspan="36"></td>
        </tr>

        @for($x=0;$x<=8;$x++)
            <tr class="text-center">
                @if($x>0 && $x<7)
                    <td colspan="3">
                        GRADE{{$x}} / GRADE {{$x+6}}
                    </td>
                @elseif($x==7)
                    <td colspan="3">TOTAL FOR  NON-GRADED
                        </td>
                @elseif($x==8)
                    <td colspan="3">TOTAL</td>
                @else
                    <td colspan="3">KINDER</td>
                @endif



                <!-- Registered Male -->

                <td class="align-middle" style="font-size: 9px;">

                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('male')}} /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('male')}}
                    @elseif($x==8)
                        {{collect($sections[0]->data)->sum('male')}}
                    @endif

                </td>

                <!-- Registered Female -->

                <td class="align-middle" style="font-size: 9px;">

                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('female')}} /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('female')}}
                    @elseif($x==8)
                        {{collect($sections[0]->data)->sum('female')}}
                    @else
                        
                    @endif


                </td>

                <!-- Registered Total -->

                <td class="align-middle" style="font-size: 9px;">
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('male') + collect($sections[0]->data)->where('sortid',$x+3)->sum('female')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('male') + collect($sections[0]->data)->where('sortid',$x+6+3)->sum('female')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('female') + collect($sections[0]->data)->sum('male')}}
                    @endif
                </td>

                <!-- Daily Attendance Male -->

                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('maleAtt')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('maleAtt')}}
                    @endif
                </td>

                <!-- Daily Attendance Female -->

                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('femaleAtt')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('femaleAtt')}} 

                    @elseif($x==8)
                        {{collect($sections[0]->data)->sum('femaleAtt')}}
                    @endif
                </td>

                 <!-- Daily Attendance Female -->

                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('femaleAtt') + collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('femaleAtt') + collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt')}} 
                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('femaleAtt') + collect($sections[0]->data)->sum('maleAtt')}}

                    @endif

                </td>

                <!-- Percentage Daily Attendance Male -->

                <td>
                    @if($x>0 && $x<7)
                        @if(collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt') != 0)
                            {{round(collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt') / collect($sections[0]->data)->where('sortid',$x+3)->sum('male') * 100)}}%
                        @else
                            0%
                        @endif
                        /
                        @if(collect($sections[0]->data)->where('sortid',$x+6+3)->sum('maleAtt') != 0)
                            {{round(collect($sections[0]->data)->where('sortid',$x+6+3)->sum('maleAtt') / collect($sections[0]->data)->where('sortid',$x+6+3)->sum('male') * 100)}}%
                        @else
                            0%
                        @endif

                    @elseif($x==8)
                        @if($section->male != 0)
                            {{  (collect($sections[0]->data)->sum('maleAtt') / collect($sections[0]->data)->sum('male'))  * 100 }}%
                        @else
                            0%
                        @endif
                    @endif  
                </td>

                <!-- Percentage Daily Attendance Female -->

                <td>
                    @if($x>0 && $x<7)
                        @if(collect($sections[0]->data)->where('sortid',$x+3)->sum('femaleAtt') != 0)
                            {{collect($sections[0]->data)->where('sortid',$x+3)->sum('femaleAtt') / collect($sections[0]->data)->where('sortid',$x+3)->sum('female') * 100}}%
                        @else
                            0%
                        @endif
                        /
                        @if(collect($sections[0]->data)->where('sortid',$x+6+3)->sum('femaleAtt') != 0)
                            {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('femaleAtt') / collect($sections[0]->data)->where('sortid',$x+6+3)->sum('female') * 100}}%
                        @else
                            0%
                        @endif
                    @elseif($x==8)
                        @if($section->female != 0)
                            {{  (collect($sections[0]->data)->sum('femaleAtt') / collect($sections[0]->data)->sum('female'))  * 100 }}%
                        @else
                            0%
                        @endif
                    @endif
                </td>

                <!-- Percentage Daily Attendance Total -->
                <td>
                    {{-- {{collect($sections[0]->data)->sum('female') +  collect($sections[0]->data)->sum('male')}} --}}
                  
                    @if($x>0 && $x<7)
                    
                        @if( collect($sections[0]->data)->where('sortid',$x+3)->sum('female') +  collect($sections[0]->data)->where('sortid',$x+3)->sum('male') > 0)
                            {{
                                (
                                    (
                                        collect($sections[0]->data)->where('sortid',$x+3)->sum('femaleAtt') +
                                        collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt')
                                    )   /
                                    (
                                        collect($sections[0]->data)->where('sortid',$x+3)->sum('female') +
                                        collect($sections[0]->data)->where('sortid',$x+3)->sum('male')
                                    )

                                )   * 100
                            }}%
                        @else
                            0%
                        @endif
                        /
                        @if(collect($sections[0]->data)->where('sortid',$x+6+3)->sum('female') +  collect($sections[0]->data)->where('sortid',$x+6+3)->sum('male') > 0)
                            {{
                                (
                                    (
                                        collect($sections[0]->data)->where('sortid',$x+6+3)->sum('femaleAtt') +
                                        collect($sections[0]->data)->where('sortid',$x+6+3)->sum('maleAtt')
                                    )   /
                                    (
                                        collect($sections[0]->data)->where('sortid',$x+6+3)->sum('female') +
                                        collect($sections[0]->data)->where('sortid',$x+6+3)->sum('male')
                                    )

                                )   * 100
                            }}%
                        @else
                            0%
                        @endif


                    @elseif($x==8)
                        @if( (collect($sections[0]->data)->sum('female') + collect($sections[0]->data)->sum('male')) > 0)
                            {{  
                                (
                                    (   collect($sections[0]->data)->sum('femaleAtt') + collect($sections[0]->data)->sum('maleAtt') ) /
                                    (   collect($sections[0]->data)->sum('female') + collect($sections[0]->data)->sum('male') ) 
                                ) * 100 
                            }}%
                        @else
                            0
                        @endif
                    @endif
                </td>

                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutmale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutmale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('prevdropoutmale')}}
                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutfemale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutfemale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('prevdropoutfemale')}}

                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutfemale') + collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutmale') }} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutfemale') + collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutmale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('prevdropoutfemale') + collect($sections[0]->data)->sum('prevdropoutmale')}}
                        
                    @endif

                </td>

                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutmale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutmale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('dropoutmale')}}
                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutfemale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutfemale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('dropoutfemale')}}

                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutfemale') + 
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutmale') }} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutfemale') + 
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutmale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('dropoutfemale') + collect($sections[0]->data)->sum('dropoutmale')}}
                        
                    @endif
                </td>

                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutmale') + 
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutmale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutmale') + 
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutmale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('prevdropoutmale') +
                            collect($sections[0]->data)->sum('dropoutmale')}}

                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutfemale') + 
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutfemale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutfemale') +
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutfemale') }} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('prevdropoutfemale') +  collect($sections[0]->data)->sum('dropoutfemale')}}

                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{  collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutfemale') + 
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutmale') + 
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutfemale') +
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutmale')}} 
                        /
                        {{  collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutfemale') + 
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutmale') +
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutfemale') +
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutmale') }} 

                    @elseif($x==8)

                        {{  collect($sections[0]->data)->sum('prevdropoutfemale') + 
                        collect($sections[0]->data)->sum('prevdropoutmale') + 
                        collect($sections[0]->data)->sum('dropoutfemale') +
                        collect($sections[0]->data)->sum('dropoutmale')
                        }} 
                        
                    @endif
                </td>

                {{-- transout --}}

                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutmale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutmale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('prevtransoutmale')}}
                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutfemale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutfemale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('prevtransoutfemale')}}

                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutfemale') + collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutmale') }} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutfemale') + collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutmale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('prevtransoutfemale') + collect($sections[0]->data)->sum('prevtransoutmale')}}
                        
                    @endif

                </td>

                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutmale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutmale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('transoutmale')}}
                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutfemale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutfemale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('transoutfemale')}}

                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutfemale') + 
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutmale') }} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutfemale') + 
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutmale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('transoutfemale') + collect($sections[0]->data)->sum('transoutmale')}}
                        
                    @endif
                </td>

                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutmale') + 
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutmale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutmale') + 
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutmale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('prevtransoutmale') +
                            collect($sections[0]->data)->sum('transoutmale')}}

                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutfemale') + 
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutfemale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutfemale') +
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutfemale') }} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('prevtransoutfemale') + collect($sections[0]->data)->sum('transoutfemale')}}

                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{  collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutfemale') + 
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutmale') + 
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutfemale') +
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutmale')}} 
                        /
                        {{  collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutfemale') + 
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutmale') +
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutfemale') +
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutmale') }} 

                    @elseif($x==8)

                        {{  collect($sections[0]->data)->sum('prevtransoutfemale') + 
                            collect($sections[0]->data)->sum('prevtransoutmale') + 
                            collect($sections[0]->data)->sum('transoutfemale') +
                            collect($sections[0]->data)->sum('transoutmale')
                        }}                        
                    @endif
                </td>

                {{-- transin --}}


                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinmale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinmale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('prevtransinmale')}}
                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinfemale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinfemale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('prevtransinfemale')}}

                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinfemale') + collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinmale') }} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinfemale') + collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinmale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('prevtransinfemale') + collect($sections[0]->data)->sum('prevtransinmale')}}
                        
                    @endif

                </td>

                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transinmale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinmale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('transinmale')}}
                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transinfemale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinfemale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('transinfemale')}}

                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transinfemale') + 
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('transinmale') }} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinfemale') + 
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinmale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('transinfemale') + collect($sections[0]->data)->sum('transinmale')}}
                        
                    @endif
                </td>

                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinmale') + 
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('transinmale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinmale') + 
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinmale')}} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('prevtransinmale') +
                            collect($sections[0]->data)->sum('transinmale')}}

                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinfemale') + 
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('transinfemale')}} 
                        /
                        {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinfemale') +
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinfemale') }} 

                    @elseif($x==8)

                        {{collect($sections[0]->data)->sum('prevtransinfemale') + collect($sections[0]->data)->sum('transinfemale')}}

                    @endif
                </td>
                <td>
                    @if($x>0 && $x<7)
                        {{  collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinfemale') + 
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinmale') + 
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('transinfemale') +
                            collect($sections[0]->data)->where('sortid',$x+3)->sum('transinmale')}} 
                        /
                        {{  collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinfemale') + 
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinmale') +
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinfemale') +
                            collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinmale') }} 

                    @elseif($x==8)

                    {{  collect($sections[0]->data)->sum('prevtransinfemale') + 
                        collect($sections[0]->data)->sum('prevtransinmale') + 
                        collect($sections[0]->data)->sum('transinfemale') +
                        collect($sections[0]->data)->sum('transinmale')
                    }} 
                        
                    @endif
                </td>


                {{-- <td>9999 / 9999</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td> --}}





                {{-- <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td> --}}
                 
            </tr>


        @endfor

    </tbody>
   
</table>