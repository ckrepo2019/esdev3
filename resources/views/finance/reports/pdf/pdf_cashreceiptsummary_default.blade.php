
                        &nbsp;
                        <br/>
                        &nbsp;
                        @php
                            $count = 2;
                        @endphp
                        <span style="font-size: 11px; font-weight: bold;">As of {{$strdtfrom}} to {{$strdtto}}</span>  &nbsp;
                        <br/>
                        <table style="width: 100%;font-size: 11px;" border="1">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="45%">Account</th>
                                    {{-- <th>Department</th> --}}
                                    <th width="25%">Debit</th>
                                    <th width="25%">Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr nobr="true">
                                    <td width="5%">1</td>
                                    <td width="45%">{{$coainfo[0]->code}} - {{$coainfo[0]->account}} </td>
                                    <td width="25%" style="text-align: right;">{{$coainfo[0]->amount}}</td>
                                    <td width="25%"></td>
                                </tr>
                                @if(count($crsinfo)>0)
                                    @foreach($crsinfo as $eachcrs)
                                        <tr nobr="true">
                                            <td width="5%">{{$count}}</td>
                                            <td width="45%">{{$eachcrs->code}} - {{$eachcrs->account}}</td>
                                            <td width="25%" style="text-align: right;"></td>
                                            <td width="25%" style="text-align: right;">{{number_format($eachcrs->amount,2)}}</td>
                                        </tr>
                                        @php
                                            $count += 1;
                                        @endphp
                                    @endforeach
                                @endif
                                <tr nobr="true">
                                    <th width="5%"></th>
                                    <th width="45%" style="text-align: center; font-weight: bold;">TOTAL</th>
                                    <th width="25%" style="text-align: right; font-weight: bold;">{{number_format($cash,2)}}</th>
                                    <th width="25%" style="text-align: right; font-weight: bold;">{{number_format($credit,2)}}</th>
                                </tr>
                            </tbody>
                        </table>