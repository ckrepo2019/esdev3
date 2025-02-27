                        &nbsp;
                        <br/>
                        <table style="width: 100%; font-size: 10px;table-layout: fixed;">
                            <thead>
                                <tr style="text-align: center;">
                                    <th width="15%;" style="border-bottom: 1px solid black; font-weight: bold;">Code</th>
                                    <th width="35%;" style="border-bottom: 1px solid black; font-weight: bold;">Account Name</th>
                                    <th width="10%;" style="border-bottom: 1px solid black; font-weight: bold;">Grade<br/>School</th>
                                    <th width="10%;" style="border-bottom: 1px solid black; font-weight: bold;">Junior<br/>High</th>
                                    <th width="10%;" style="border-bottom: 1px solid black; font-weight: bold;">Senior<br/>High</th>
                                    <th width="10%;" style="border-bottom: 1px solid black; font-weight: bold;">General Discount</th>
                                    <th width="10%;" style="border-bottom: 1px solid black; font-weight: bold;">Net</th>
                                </tr>
                            </thead>
                            <tr>
                                <td colspan="7"><strong>{{$coainfo[0]->group}}</strong></td>
                            </tr>
                            <tr>
                                <td width="15%;" style="border-bottom: 1px solid black;text-align: center;">{{$coainfo[0]->code}}</td>
                                <td width="35%;" style="border-bottom: 1px solid black;">{{$coainfo[0]->account}}</td>
                                <td width="10%;" style="border-bottom: 1px solid black;"></td>
                                <td width="10%;" style="border-bottom: 1px solid black;"></td>
                                <td width="10%;" style="border-bottom: 1px solid black;"></td>
                                <td width="10%;" style="border-bottom: 1px solid black;"></td>
                                <td width="10%;" style="border-bottom: 1px solid black;">{{$coainfo[0]->amount}}</td>
                            </tr>
                            <tr>
                                <td width="50%;" colspan="2"  style="text-align: center;">Total this {{$coainfo[0]->group}}</td>
                                <td width="10%;"></td>
                                <td width="10%;"></td>
                                <td width="10%;"></td>
                                <td width="10%;"></td>
                                <td width="10%;">{{$coainfo[0]->amount}}</td>
                            </tr>
                            <tr>
                                <td colspan="7">&nbsp;</td>
                            </tr>
                            @if(count($crsinfo)>0)
                                @php
                                    $grandtotalamount_gs = 0;
                                    $grandtotalamount_jh = 0;
                                    $grandtotalamount_sh = 0;
                                    $grandtotalamount = 0;
                                @endphp
                                @foreach($coagroups as $coagroup)
                                    @php
                                        $totalamount_gs = 0.00;
                                        $totalamount_jh = 0.00;
                                        $totalamount_sh = 0.00;
                                        $totalamount = 0.00;
                                    @endphp
                                    @if(collect($crsinfo)->contains('groupid',$coagroup->id))
                                        <tr>
                                            <td colspan="7"><strong>{{$coagroup->group}}</strong></td>
                                        </tr>
                                        @foreach($crsinfo as $eachcrs)
                                            @if($eachcrs->groupid == $coagroup->id)
                                                <tr>
                                                    <td width="15%;" style="text-align: center;">{{$eachcrs->code}}</td>
                                                    <td width="35%;">{{$eachcrs->account}}</td>
                                                    <td width="10%;" style="text-align: right;">
                                                        @if($eachcrs->levelid == 1 || $eachcrs->levelid == 2 ||$eachcrs->levelid == 3 ||$eachcrs->levelid == 4 || $eachcrs->levelid == 5 || $eachcrs->levelid == 6 || $eachcrs->levelid == 7 || $eachcrs->levelid == 16 || $eachcrs->levelid == 9)
                                                            {{number_format($eachcrs->amount,2)}}
                                                            @php
                                                                $totalamount_gs += $eachcrs->amount;
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td width="10%;" style="text-align: right;">
                                                        @if($eachcrs->levelid == 10 || $eachcrs->levelid == 11 || $eachcrs->levelid == 12 || $eachcrs->levelid == 13)
                                                            {{number_format($eachcrs->amount,2)}}
                                                            @php
                                                                $totalamount_jh += $eachcrs->amount;
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td width="10%;" style="text-align: right;">
                                                        @if($eachcrs->levelid == 14 || $eachcrs->levelid == 15)
                                                            {{number_format($eachcrs->amount,2)}}
                                                            @php
                                                                $totalamount_sh += $eachcrs->amount;
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td width="10%;"></td>
                                                    <td width="10%;"></td>
                                                </tr>
                                                @php
                                                    $totalamount += $eachcrs->amount;
                                                @endphp
                                            @endif
                                        @endforeach
                                        <tr>
                                            <td colspan="2" style="text-align: center;border-top: 1px solid black;" >Total this {{$coagroup->group}}</td>
                                            <td style="border-top: 1px solid black;text-align: right;">{{number_format($totalamount_gs,2)}}</td>
                                            <td style="border-top: 1px solid black;text-align: right;">{{number_format($totalamount_jh,2)}}</td>
                                            <td style="border-top: 1px solid black;text-align: right;">{{number_format($totalamount_sh,2)}}</td>
                                            <td style="border-top: 1px solid black;"></td>
                                            <td style="border-top: 1px solid black;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">&nbsp;</td>
                                        </tr>
                                    @endif
                                    @php
                                        $grandtotalamount_gs += $totalamount_gs;
                                        $grandtotalamount_jh += $totalamount_jh;
                                        $grandtotalamount_sh += $totalamount_sh;
                                        $grandtotalamount   += $totalamount;
                                    @endphp
                                @endforeach
                                
                                <tr>
                                    <td colspan="2" style="text-align: center;border-top: 1px solid black;border-bottom: 1px solid black;">Grand Total</td>
                                    <td style="text-align: right;border-top: 1px solid black;border-bottom: 1px solid black;">{{number_format($grandtotalamount_gs,2)}}</td>
                                    <td style="text-align: right;border-top: 1px solid black;border-bottom: 1px solid black;">{{number_format($grandtotalamount_jh,2)}}</td>
                                    <td style="text-align: right;border-top: 1px solid black;border-bottom: 1px solid black;">{{number_format($grandtotalamount_sh,2)}}</td>
                                    <td style="text-align: right;border-top: 1px solid black;border-bottom: 1px solid black;"></td>
                                    <td style="text-align: right;border-top: 1px solid black;border-bottom: 1px solid black;">{{number_format($grandtotalamount,2)}}</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="7"></td>
                            </tr>
                            <tr>
                                <td colspan="2">Prepared by</td>
                                <td colspan="3">Checked by</td>
                                <td colspan="2">Noted by</td>
                            </tr>
                            <tr>
                                <td colspan="2" ><u></u></td>
                                <td colspan="3" ><u></u></td>
                                <td colspan="2" ><u></u></td>
                                {{-- <td colspan="3" style="border-bottom: 1px solid black;">&nbsp;</td>
                                <td colspan="2" style="border-bottom: 1px solid black;">&nbsp;</td> --}}
                            </tr>
                        </table>