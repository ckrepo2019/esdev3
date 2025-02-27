
@if(count($firstemployee[0]->deductionstandards)>0)
{{-- {{$firstemployee[0]->deductionstandards[0]->payrollstatus}} --}}
 {{-- @if($firstemployee[0]->deductionstandards[0]->payrollstatus == 0) --}}

     <table class="table" width="100%" id="standarddeductionscontainer">
         @foreach($firstemployee[0]->deductionstandards[0]->standarddeductions as $standarddeductions)
             <tr>
                 <th colspan="4">
                     {{$standarddeductions->description}} (&#8369; {{$standarddeductions->eesamount}}) 
                 </th>
             </tr>
             
             @php
                if($standarddeductions->paymentoption == 1)
                {
                    $checked1 = 'checked';
                    $checked2 = '';
                    $paymenttype = 'half';
                }else{
                    $checked2 = 'checked';
                    $checked1 = '';
                    $paymenttype = 'full';
                }
            @endphp

             @if($standarddeductions->disabled == 0)
                <tr>
                    <td colspan="2" class="text-danger" amount="{{($standarddeductions->eesamount)/2}}"  payment="half">
                        <div class="icheck-primary d-inline">
                            <input type="hidden" name="standarddeductionids[]" value="{{$standarddeductions->id}}{{$paymenttype}}">
                            <div class="icheck-primary d-inline">
                            <input type="radio" id="1radiostandarddeduction{{$standarddeductions->id}}" class="radiostandarddeduction" name="standarddeduction{{$standarddeductions->id}}" value="{{($standarddeductions->eesamount)/2}}" {{$checked1}}>
                            <label for="1radiostandarddeduction{{$standarddeductions->id}}">
                                Half ( &#8369; {{($standarddeductions->eesamount)/2}} )
                            </label>
                        </div>
                    </td>
                    <td colspan="2" class="text-danger" amount="{{$standarddeductions->eesamount}}"  payment="full">
                        @if($firstemployee[0]->deductionstandards[0]->payrollstatus !=2)
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="2radiostandarddeduction{{$standarddeductions->id}}" class="radiostandarddeduction" name="standarddeduction{{$standarddeductions->id}}" value="{{$standarddeductions->eesamount}}"  {{$checked2}}>
                                <label for="2radiostandarddeduction{{$standarddeductions->id}}">
                                    Full ( &#8369; {{$standarddeductions->eesamount}} )
                                </label>
                            </div>
                        @endif
                    </td>
                </tr>
             @else
                <tr>
                    <td colspan="2" class="text-danger" amount="{{($standarddeductions->eesamount)/2}}"  payment="half">
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="1radiostandarddeduction{{$standarddeductions->id}}" disabled class="radiostandarddeduction"  {{$checked1}}>
                            <label for="1radiostandarddeduction{{$standarddeductions->id}}">
                                Half ( &#8369; {{($standarddeductions->eesamount)/2}} )
                            </label>
                        </div>
                    </td>
                    <td colspan="2" class="text-danger" amount="{{$standarddeductions->eesamount}}"  payment="full">
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="2radiostandarddeduction{{$standarddeductions->id}}"disabled  class="radiostandarddeduction"  {{$checked2}}>
                            <label for="2radiostandarddeduction{{$standarddeductions->id}}">
                                Full ( &#8369; {{$standarddeductions->eesamount}} )
                            </label>
                        </div>
                    </td>
                </tr>
             @endif
         @endforeach
     </table>
 {{-- @endif --}}
 {{-- @if($firstemployee[0]->deductionstandards[0]->payrollstatus == 1)

     <table class="table" width="100%" id="standarddeductionscontainer">
         @foreach($firstemployee[0]->deductionstandards[0]->standarddeductions as $standarddeductions)
             <tr>
                 <th colspan="4">
                     {{$standarddeductions->description}} (&#8369; {{$standarddeductions->eesamount}}) 
                 </th>
             </tr>
             <tr>
                 <td colspan="2" class="text-danger" amount="{{($standarddeductions->eesamount)/2}}"  payment="half">
                     @if($standarddeductions->paymentoption == 1)
                         <input type="hidden" name="standarddeductionids[]" value="{{$standarddeductions->id}}half">
                         <div class="icheck-primary d-inline">
                             <input type="radio" id="1radiostandarddeduction{{$standarddeductions->id}}" class="radiostandarddeduction" name="standarddeduction{{$standarddeductions->id}}" value="{{($standarddeductions->eesamount)/2}}" checked>
                             <label for="1radiostandarddeduction{{$standarddeductions->id}}">
                                 Half ( &#8369; {{($standarddeductions->eesamount)/2}} )
                             </label>
                         </div>
                     @else
                         <input type="hidden" name="standarddeductionids[]" value="{{$standarddeductions->id}}full">
                         <div class="icheck-primary d-inline">
                             <input type="radio" id="1radiostandarddeduction{{$standarddeductions->id}}" class="radiostandarddeduction" name="standarddeduction{{$standarddeductions->id}}" value="{{($standarddeductions->eesamount)/2}}">
                             <label for="1radiostandarddeduction{{$standarddeductions->id}}">
                                 Half ( &#8369; {{($standarddeductions->eesamount)/2}} )
                             </label>
                         </div>
                     @endif

                     
                 </td>
                 <td colspan="2" class="text-danger" amount="{{$standarddeductions->eesamount}}"  payment="full">
                     @if($standarddeductions->paymentoption == 1)
                         <div class="icheck-primary d-inline">
                             <input type="radio" id="2radiostandarddeduction{{$standarddeductions->id}}" class="radiostandarddeduction" name="standarddeduction{{$standarddeductions->id}}" value="{{$standarddeductions->eesamount}}">
                             <label for="2radiostandarddeduction{{$standarddeductions->id}}">
                                 Full ( &#8369; {{$standarddeductions->eesamount}} )
                             </label>
                         </div>
                     @else
                         <div class="icheck-primary d-inline">
                             <input type="radio" id="2radiostandarddeduction{{$standarddeductions->id}}" class="radiostandarddeduction" name="standarddeduction{{$standarddeductions->id}}" value="{{$standarddeductions->eesamount}}" checked="">
                             <label for="2radiostandarddeduction{{$standarddeductions->id}}">
                                 Full ( &#8369; {{$standarddeductions->eesamount}} )
                             </label>
                         </div>
                     @endif
                 </td>
             </tr>
         @endforeach
     </table>

 @endif --}}
 {{-- @if($firstemployee[0]->deductionstandards[0]->payrollstatus == 2 || $firstemployee[0]->deductionstandards[0]->payrollstatus == 3)

     <table class="table" width="100%" id="standarddeductionscontainer">
         @foreach($firstemployee[0]->deductionstandards[0]->standarddeductions as $standarddeductions)
             <tr>
                 <th colspan="4">
                     {{$standarddeductions->description}} (&#8369; {{$standarddeductions->eesamount}}) 
                 </th>
             </tr>
             <tr>
                 <td colspan="2" class="text-danger" amount="{{($standarddeductions->eesamount)/2}}"  payment="half">
                     @if($standarddeductions->paymentoption == 1)
                         <input type="hidden" name="standarddeductionids[]" value="{{$standarddeductions->id}}half">
                         <div class="icheck-primary d-inline">
                             <input type="radio" id="1radiostandarddeduction{{$standarddeductions->id}}" class="radiostandarddeduction" name="standarddeduction{{$standarddeductions->id}}" value="{{($standarddeductions->eesamount)/2}}" checked>
                             <label for="1radiostandarddeduction{{$standarddeductions->id}}">
                                 Half ( &#8369; {{($standarddeductions->eesamount)/2}} )
                             </label>
                         </div>
                     @else
                         @if(isset($standarddeductions->paid))
                             @if($standarddeductions->paid == 0)
                                 <input type="hidden" name="standarddeductionids[]" value="{{$standarddeductions->id}}full">
                             @endif
                         @else
                             <input type="hidden" name="standarddeductionids[]" value="{{$standarddeductions->id}}full">
                         @endif
                     @endif
                 </td>
                 <td colspan="2" class="text-danger" amount="{{$standarddeductions->eesamount}}"  payment="full">
                     @if($standarddeductions->paymentoption == 1)
                     @else
                         @if($standarddeductions->paid == 0)
                             <div class="icheck-primary d-inline">
                                 <input type="radio" id="2radiostandarddeduction{{$standarddeductions->id}}" class="radiostandarddeduction" name="standarddeduction{{$standarddeductions->id}}" value="{{$standarddeductions->eesamount}}" checked="">
                                 <label for="2radiostandarddeduction{{$standarddeductions->id}}">
                                     Full ( &#8369; {{$standarddeductions->eesamount}} )
                                 </label>
                             </div>
                         @elseif($standarddeductions->paid == 1)
                             P A I D
                         @endif
                     @endif
                 </td>
             </tr>
         @endforeach
     </table>

 @endif --}}
@endif


{{-- ===================== --}}


@if(count($firstemployee[0]->deductionothers)>0)
            @if($firstemployee[0]->deductionothers[0]->payrollstatus == 0)

                <table class="table" width="100%" id="otherdeductionscontainer">
                    @foreach($firstemployee[0]->deductionothers[0]->otherdeductions as $otherdeductions)
                        <tr>
                            <th colspan="4">
                                {{$otherdeductions->description}} (&#8369; {{$otherdeductions->amount}}) 
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-danger" amount="{{($otherdeductions->amount)/2}}"  payment="half">
                                <input type="hidden" name="otherdeductionids[]" value="{{$otherdeductions->id}}full">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="1radiootherdeduction{{$otherdeductions->id}}" class="radiootherdeduction" name="otherdeduction{{$otherdeductions->id}}" value="{{($otherdeductions->amount)/2}}">
                                    <label for="1radiootherdeduction{{$otherdeductions->id}}">
                                        Half ( &#8369; {{($otherdeductions->amount)/2}} )
                                    </label>
                                </div>
                            </td>
                            <td colspan="2" class="text-danger" amount="{{$otherdeductions->amount}}"  payment="full">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="2radiootherdeduction{{$otherdeductions->id}}" class="radiootherdeduction" name="otherdeduction{{$otherdeductions->id}}" value="{{$otherdeductions->amount}}" checked="">
                                    <label for="2radiootherdeduction{{$otherdeductions->id}}">
                                        Full ( &#8369; {{$otherdeductions->amount}} )
                                    </label>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif

            @if($firstemployee[0]->deductionothers[0]->payrollstatus == 1)

                <table class="table" width="100%" id="otherdeductionscontainer">
                    @foreach($firstemployee[0]->deductionothers[0]->otherdeductions as $otherdeductions)
                        <tr>
                            <th colspan="4">
                                {{$otherdeductions->description}} (&#8369; {{$otherdeductions->amount}}) 
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-danger" amount="{{($otherdeductions->amount)/2}}"  payment="half">
                                @if($otherdeductions->paymentoption == 1)
                                    <input type="hidden" name="otherdeductionids[]" value="{{$otherdeductions->id}}half">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="1radiootherdeduction{{$otherdeductions->id}}" class="radiootherdeduction" name="otherdeduction{{$otherdeductions->id}}" value="{{($otherdeductions->amount)/2}}" checked>
                                        <label for="1radiootherdeduction{{$otherdeductions->id}}">
                                            Half ( &#8369; {{($otherdeductions->amount)/2}} )
                                        </label>
                                    </div>
                                @else
                                    <input type="hidden" name="otherdeductionids[]" value="{{$otherdeductions->id}}full">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="1radiootherdeduction{{$otherdeductions->id}}" class="radiootherdeduction" name="otherdeduction{{$otherdeductions->id}}" value="{{($otherdeductions->amount)/2}}">
                                        <label for="1radiootherdeduction{{$otherdeductions->id}}">
                                            Half ( &#8369; {{($otherdeductions->amount)/2}} )
                                        </label>
                                    </div>
                                @endif
                            </td>
                            <td colspan="2" class="text-danger" amount="{{$otherdeductions->amount}}"  payment="full">
                                @if($otherdeductions->paymentoption == 1)
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="2radiootherdeduction{{$otherdeductions->id}}" class="radiootherdeduction" name="otherdeduction{{$otherdeductions->id}}" value="{{$otherdeductions->amount}}" >
                                        <label for="2radiootherdeduction{{$otherdeductions->id}}">
                                            Full ( &#8369; {{$otherdeductions->amount}} )
                                        </label>
                                    </div>
                                @else
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="2radiootherdeduction{{$otherdeductions->id}}" class="radiootherdeduction" name="otherdeduction{{$otherdeductions->id}}" value="{{$otherdeductions->amount}}" checked="">
                                        <label for="2radiootherdeduction{{$otherdeductions->id}}">
                                            Full ( &#8369; {{$otherdeductions->amount}} )
                                        </label>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif

            @if($firstemployee[0]->deductionothers[0]->payrollstatus == 2 || $firstemployee[0]->deductionothers[0]->payrollstatus == 3)

                <table class="table" width="100%" id="otherdeductionscontainer">
                    @foreach($firstemployee[0]->deductionothers[0]->otherdeductions as $otherdeductions)
                        <tr>
                            <th colspan="4">
                                {{$otherdeductions->description}} (&#8369; {{$otherdeductions->amount}}) 
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-danger" amount="{{($otherdeductions->amount)/2}}"  payment="half">
                                @if($otherdeductions->paymentoption == 1)
                                    <input type="hidden" name="otherdeductionids[]" value="{{$otherdeductions->id}}half">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="1radiootherdeduction{{$otherdeductions->id}}" class="radiootherdeduction" name="otherdeduction{{$otherdeductions->id}}" value="{{($otherdeductions->amount)/2}}" checked>
                                        <label for="1radiootherdeduction{{$otherdeductions->id}}">
                                            Half ( &#8369; {{($otherdeductions->amount)/2}} )
                                        </label>
                                    </div>
                                @else
                                    @if(isset($otherdeductions->paid))
                                        @if($otherdeductions->paid == 0)
                                            <input type="hidden" name="otherdeductionids[]" value="{{$otherdeductions->id}}full">
                                        @endif
                                    @else
                                        <input type="hidden" name="otherdeductionids[]" value="{{$otherdeductions->id}}full">
                                    @endif
                                @endif
                            </td>
                            <td colspan="2" class="text-danger" amount="{{$otherdeductions->amount}}"  payment="full">
                                @if($otherdeductions->paymentoption == 1)
                                @else
                                    @if($otherdeductions->paid == 0)
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="2radiootherdeduction{{$otherdeductions->id}}" class="radiootherdeduction" name="otherdeduction{{$otherdeductions->id}}" value="{{$otherdeductions->amount}}" checked="">
                                            <label for="2radiootherdeduction{{$otherdeductions->id}}">
                                                Full ( &#8369; {{$otherdeductions->amount}} )
                                            </label>
                                        </div>
                                    @elseif($otherdeductions->paid == 1)
                                        P A I D
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>

            @endif
@endif