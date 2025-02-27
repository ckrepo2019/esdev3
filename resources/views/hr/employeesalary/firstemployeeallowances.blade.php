
@if(count($firstemployee[0]->allowancestandards)>0)
           {{-- {{$firstemployee[0]->allowancestandards[0]->payrollstatus}} --}}
            @if($firstemployee[0]->allowancestandards[0]->payrollstatus == 0)

                <table class="table" width="100%" id="standardallowancescontainer">
                    @foreach($firstemployee[0]->allowancestandards[0]->standardallowances as $standardallowances)
                        <tr>
                            <th colspan="4">
                                {{$standardallowances->description}} (&#8369; {{$standardallowances->amount}}) 
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-success" amount="{{($standardallowances->amount)/2}}"  payment="half">
                                <input type="hidden" name="standardallowanceids[]" value="{{$standardallowances->id}}full">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="1radiostandardallowance{{$standardallowances->id}}" class="radiostandardallowance" name="standardallowance{{$standardallowances->id}}" value="{{($standardallowances->amount)/2}}">
                                    <label for="1radiostandardallowance{{$standardallowances->id}}">
                                        Half ( &#8369; {{($standardallowances->amount)/2}} )
                                    </label>
                                </div>
                            </td>
                            <td colspan="2" class="text-success" amount="{{$standardallowances->amount}}"  payment="full">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="2radiostandardallowance{{$standardallowances->id}}" class="radiostandardallowance" name="standardallowance{{$standardallowances->id}}" value="{{$standardallowances->amount}}" checked="">
                                    <label for="2radiostandardallowance{{$standardallowances->id}}">
                                        Full ( &#8369; {{$standardallowances->amount}} )
                                    </label>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif
            @if($firstemployee[0]->allowancestandards[0]->payrollstatus == 1)

                <table class="table" width="100%" id="standardallowancescontainer">
                    @foreach($firstemployee[0]->allowancestandards[0]->standardallowances as $standardallowances)
                        <tr>
                            <th colspan="4">
                                {{$standardallowances->description}} (&#8369; {{$standardallowances->amount}}) 
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-success" amount="{{($standardallowances->amount)/2}}"  payment="half">
                                @if($standardallowances->paymentoption == 1)
                                    <input type="hidden" name="standardallowanceids[]" value="{{$standardallowances->id}}half">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="1radiostandardallowance{{$standardallowances->id}}" class="radiostandardallowance" name="standardallowance{{$standardallowances->id}}" value="{{($standardallowances->amount)/2}}" checked>
                                        <label for="1radiostandardallowance{{$standardallowances->id}}">
                                            Half ( &#8369; {{($standardallowances->amount)/2}} )
                                        </label>
                                    </div>
                                @else
                                    <input type="hidden" name="standardallowanceids[]" value="{{$standardallowances->id}}full">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="1radiostandardallowance{{$standardallowances->id}}" class="radiostandardallowance" name="standardallowance{{$standardallowances->id}}" value="{{($standardallowances->amount)/2}}">
                                        <label for="1radiostandardallowance{{$standardallowances->id}}">
                                            Half ( &#8369; {{($standardallowances->amount)/2}} )
                                        </label>
                                    </div>
                                @endif

                                
                            </td>
                            <td colspan="2" class="text-success" amount="{{$standardallowances->amount}}"  payment="full">
                                @if($standardallowances->paymentoption == 1)
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="2radiostandardallowance{{$standardallowances->id}}" class="radiostandardallowance" name="standardallowance{{$standardallowances->id}}" value="{{$standardallowances->amount}}">
                                        <label for="2radiostandardallowance{{$standardallowances->id}}">
                                            Full ( &#8369; {{$standardallowances->amount}} )
                                        </label>
                                    </div>
                                @else
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="2radiostandardallowance{{$standardallowances->id}}" class="radiostandardallowance" name="standardallowance{{$standardallowances->id}}" value="{{$standardallowances->amount}}" checked="">
                                        <label for="2radiostandardallowance{{$standardallowances->id}}">
                                            Full ( &#8369; {{$standardallowances->amount}} )
                                        </label>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>

            @endif
            @if($firstemployee[0]->allowancestandards[0]->payrollstatus == 2 || $firstemployee[0]->allowancestandards[0]->payrollstatus == 3)

                <table class="table" width="100%" id="standardallowancescontainer">
                    @foreach($firstemployee[0]->allowancestandards[0]->standardallowances as $standardallowances)
                        <tr>
                            <th colspan="4">
                                {{$standardallowances->description}} (&#8369; {{$standardallowances->amount}}) 
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-success" amount="{{($standardallowances->amount)/2}}"  payment="half">
                                @if($standardallowances->paymentoption == 1)
                                    <input type="hidden" name="standardallowanceids[]" value="{{$standardallowances->id}}half">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="1radiostandardallowance{{$standardallowances->id}}" class="radiostandardallowance" name="standardallowance{{$standardallowances->id}}" value="{{($standardallowances->amount)/2}}" checked>
                                        <label for="1radiostandardallowance{{$standardallowances->id}}">
                                            Half ( &#8369; {{($standardallowances->amount)/2}} )
                                        </label>
                                    </div>
                                @else
                                    @if(isset($standardallowances->paid))
                                        @if($standardallowances->paid == 0)
                                            <input type="hidden" name="standardallowanceids[]" value="{{$standardallowances->id}}full">
                                        @endif
                                    @else
                                        <input type="hidden" name="standardallowanceids[]" value="{{$standardallowances->id}}full">
                                    @endif
                                @endif
                            </td>
                            <td colspan="2" class="text-success" amount="{{$standardallowances->amount}}"  payment="full">
                                @if($standardallowances->paymentoption == 1)
                                @else
                                    @if($standardallowances->paid == 0)
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="2radiostandardallowance{{$standardallowances->id}}" class="radiostandardallowance" name="standardallowance{{$standardallowances->id}}" value="{{$standardallowances->amount}}" checked="">
                                            <label for="2radiostandardallowance{{$standardallowances->id}}">
                                                Full ( &#8369; {{$standardallowances->amount}} )
                                            </label>
                                        </div>
                                    @elseif($standardallowances->paid == 1)
                                        P A I D
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>

            @endif
@endif




            {{-- ================================================================================================ --}}

@if(count($firstemployee[0]->allowanceothers)>0)
            @if($firstemployee[0]->allowanceothers[0]->payrollstatus == 0)

                <table class="table" width="100%" id="otherallowancescontainer">
                    @foreach($firstemployee[0]->allowanceothers[0]->otherallowances as $otherallowances)
                        <tr>
                            <th colspan="4">
                                {{$otherallowances->description}} (&#8369; {{$otherallowances->amount}}) 
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-success" amount="{{($otherallowances->amount)/2}}"  payment="half">
                                <input type="hidden" name="otherallowanceids[]" value="{{$otherallowances->id}}full">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="1radiootherallowance{{$otherallowances->id}}" class="radiootherallowance" name="otherallowance{{$otherallowances->id}}" value="{{($otherallowances->amount)/2}}">
                                    <label for="1radiootherallowance{{$otherallowances->id}}">
                                        Half ( &#8369; {{($otherallowances->amount)/2}} )
                                    </label>
                                </div>
                            </td>
                            <td colspan="2" class="text-success" amount="{{$otherallowances->amount}}"  payment="full">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="2radiootherallowance{{$otherallowances->id}}" class="radiootherallowance" name="otherallowance{{$otherallowances->id}}" value="{{$otherallowances->amount}}" checked="">
                                    <label for="2radiootherallowance{{$otherallowances->id}}">
                                        Full ( &#8369; {{$otherallowances->amount}} )
                                    </label>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif

            @if($firstemployee[0]->allowanceothers[0]->payrollstatus == 1)

                <table class="table" width="100%" id="otherallowancescontainer">
                    @foreach($firstemployee[0]->allowanceothers[0]->otherallowances as $otherallowances)
                        <tr>
                            <th colspan="4">
                                {{$otherallowances->description}} (&#8369; {{$otherallowances->amount}}) 
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-success" amount="{{($otherallowances->amount)/2}}"  payment="half">
                                @if($otherallowances->paymentoption == 1)
                                    <input type="hidden" name="otherallowanceids[]" value="{{$otherallowances->id}}half">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="1radiootherallowance{{$otherallowances->id}}" class="radiootherallowance" name="otherallowance{{$otherallowances->id}}" value="{{($otherallowances->amount)/2}}" checked>
                                        <label for="1radiootherallowance{{$otherallowances->id}}">
                                            Half ( &#8369; {{($otherallowances->amount)/2}} )
                                        </label>
                                    </div>
                                @else
                                    <input type="hidden" name="otherallowanceids[]" value="{{$otherallowances->id}}full">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="1radiootherallowance{{$otherallowances->id}}" class="radiootherallowance" name="otherallowance{{$otherallowances->id}}" value="{{($otherallowances->amount)/2}}">
                                        <label for="1radiootherallowance{{$otherallowances->id}}">
                                            Half ( &#8369; {{($otherallowances->amount)/2}} )
                                        </label>
                                    </div>
                                @endif
                            </td>
                            <td colspan="2" class="text-success" amount="{{$otherallowances->amount}}"  payment="full">
                                @if($otherallowances->paymentoption == 1)
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="2radiootherallowance{{$otherallowances->id}}" class="radiootherallowance" name="otherallowance{{$otherallowances->id}}" value="{{$otherallowances->amount}}" >
                                        <label for="2radiootherallowance{{$otherallowances->id}}">
                                            Full ( &#8369; {{$otherallowances->amount}} )
                                        </label>
                                    </div>
                                @else
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="2radiootherallowance{{$otherallowances->id}}" class="radiootherallowance" name="otherallowance{{$otherallowances->id}}" value="{{$otherallowances->amount}}" checked="">
                                        <label for="2radiootherallowance{{$otherallowances->id}}">
                                            Full ( &#8369; {{$otherallowances->amount}} )
                                        </label>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif

            @if($firstemployee[0]->allowanceothers[0]->payrollstatus == 2 || $firstemployee[0]->allowanceothers[0]->payrollstatus == 3)

                <table class="table" width="100%" id="otherallowancescontainer">
                    @foreach($firstemployee[0]->allowanceothers[0]->otherallowances as $otherallowances)
                        <tr>
                            <th colspan="4">
                                {{$otherallowances->description}} (&#8369; {{$otherallowances->amount}}) 
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-success" amount="{{($otherallowances->amount)/2}}"  payment="half">
                                @if($otherallowances->paymentoption == 1)
                                    <input type="hidden" name="otherallowanceids[]" value="{{$otherallowances->id}}half">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="1radiootherallowance{{$otherallowances->id}}" class="radiootherallowance" name="otherallowance{{$otherallowances->id}}" value="{{($otherallowances->amount)/2}}" checked>
                                        <label for="1radiootherallowance{{$otherallowances->id}}">
                                            Half ( &#8369; {{($otherallowances->amount)/2}} )
                                        </label>
                                    </div>
                                @else
                                    @if(isset($otherallowances->paid))
                                        @if($otherallowances->paid == 0)
                                            <input type="hidden" name="otherallowanceids[]" value="{{$otherallowances->id}}full">
                                        @endif
                                    @else
                                        <input type="hidden" name="otherallowanceids[]" value="{{$otherallowances->id}}full">
                                    @endif
                                @endif
                            </td>
                            <td colspan="2" class="text-success" amount="{{$otherallowances->amount}}"  payment="full">
                                @if($otherallowances->paymentoption == 1)
                                @else
                                    @if($otherallowances->paid == 0)
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="2radiootherallowance{{$otherallowances->id}}" class="radiootherallowance" name="otherallowance{{$otherallowances->id}}" value="{{$otherallowances->amount}}" checked="">
                                            <label for="2radiootherallowance{{$otherallowances->id}}">
                                                Full ( &#8369; {{$otherallowances->amount}} )
                                            </label>
                                        </div>
                                    @elseif($otherallowances->paid == 1)
                                        P A I D
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>

            @endif
@endif