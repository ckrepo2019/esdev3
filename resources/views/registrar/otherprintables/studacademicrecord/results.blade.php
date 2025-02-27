
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-default" id="btn-export"><i class="fa fa-download"></i> Export to PDF</button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered" style="font-size: 12px;">
                    <thead class="text-center">
                        <tr>
                            <th style="width: 20%; vertical-align: middle;">Subject Code</th>
                            {{-- <th style="width: 7%;">No.</th> --}}
                            <th style="width: 47%; vertical-align: middle;">Descriptive Title</th>
                            <th style="width: 10%; vertical-align: middle;">Grade</th>
                            <th style="width: 10%; vertical-align: middle;">HPA Equiv</th>
                            <th style="width: 8%; vertical-align: middle;">CREDIT</th>
                            <th style="width: 15%; vertical-align: middle;">HPA/<br/>Weighted Average</th>
                        </tr>
                    </thead>
                    @if(count($records)>0)
                        @php
                            $totalcredits = 0;
                            $initschoolname = null;   
                            $initschoolname = null;   
                        @endphp

                        @foreach($records as $record)
                            @php
                            @endphp
                                {{-- @if($initschoolname != $record->schoolname)
                                
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>{{$initschoolname}}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @else
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>{{$initschoolname}}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endif --}}
                            <tr>
                                <td class="p-1"></td>
                                {{-- <td class="p-1"></td> --}}
                                <th class="p-1" colspan="5">{{$record->schoolname}} - {{$record->schooladdress}}</th>
                            </tr>
                            <tr>
                                <td class="p-1"></td>
                                {{-- <td class="p-1"></td> --}}
                                <th class="p-1 text-center">@if($record->semid == 1) 1st Semester @elseif($record->semid == 2) 2nd Semester @else Summer @endif - {{$record->sydesc}}</th>
                                <td class="p-1"></td>
                                <td class="p-1"></td>
                                <td class="p-1"></td>
                                <td class="p-1"></td>
                            </tr>
                            @if(count($record->subjdata)>0)
                                @foreach($record->subjdata as $eachsubject)
                                    {{-- @dd($eachsubject) --}}
                                    @php
                                        $hpaequiv = 0;
                                        if($eachsubject->subjgrade > 0)
                                        {

                                        }
                                    @endphp
                                    <tr>
                                        <td class="p-1">
                                            {{-- {{preg_replace("/[^a-zA-Z]+/", "", $eachsubject->subjcode)}} --}}
                                            {{ preg_replace("/[^a-zA-Z0-9]/", "", $eachsubject->subjcode) }}
                                        </td>
                                        {{-- <td class="p-1">{{filter_var($eachsubject->subjcode, FILTER_SANITIZE_NUMBER_INT)}}</td> --}}
                                        <td class="p-1">{{$eachsubject->subjdesc}}</td>
                                        <td class="p-1 text-center">{{$eachsubject->subjgrade}}</td>
                                        <td class="p-1 text-center">@if(count($transmutations) > 0) {{collect($transmutations)->where('hpaeqto','<=',$eachsubject->subjgrade)->where('hpaeq','>=',$eachsubject->subjgrade)->first()->honorpointeq ?? null}} 
                                            @php
                                            $hpaequiv =collect($transmutations)->where('hpaeqto','<=',$eachsubject->subjgrade)->where('hpaeq','>=',$eachsubject->subjgrade)->first()->honorpointeq ?? null;
                                            @endphp    
                                        @endif</td>
                                        <td class="p-1 text-center">{{$eachsubject->subjcredit}}</td>
                                        <td class="p-1 text-center">{{$hpaequiv > 0 ? $hpaequiv * $eachsubject->subjunit : null}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr>
                                <td class="p-1">&nbsp;</td>
                                {{-- <td class="p-1"></td> --}}
                                <td class="p-1"></td>
                                <td class="p-1 text-center"></td>
                                <td class="p-1 text-right">TOTAL</td>
                                <td class="p-1 text-center">
                                    @php
                                        $totalcredits += collect($record->subjdata)->sum('subjcredit');
                                        
                                    @endphp

                                    {{collect($record->subjdata)->sum('subjcredit')}}
                                </td>
                                <td class="p-1"></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="p-1">&nbsp;</td>
                            {{-- <td class="p-1"></td> --}}
                            <td class="p-1"></td>
                            <td class="p-1 text-center"></td>
                            <td class="p-1 text-right"><b>TOTAL</b></td>
                            <td class="p-1 text-center">
                                {{-- {{collect($record->subjdata)->sum('subjcredit')}} --}}
                                <b>{{$totalcredits}}</b>
                            </td>
                            <td class="p-1"></td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>