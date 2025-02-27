
<style>
    th, td {
        padding: 2px;
    }
</style>
@if($acadprogid == 5)  
<div class="card">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3">Select Templates</h3>
        <ul class="nav nav-pills ml-auto p-2">
            <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Template 1</a></li>
            <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Template 2</a></li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1" style="overflow-x: scroll;">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-primary" id="btn-export"><i class="fa fa-download"></i> Export to PDF</button>
                    </div>
                </div>
                <table class="table table-bordered" style="font-size: 12px;">
                    <thead class="text-center">
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;">Summary Table</th>
                            @foreach($gradelevels as $eachgradelevel)
                            <th colspan="3">{{$eachgradelevel->levelname}}<br/>({{$eachgradelevel->strandcode}})</th>
                            @endforeach
                            <th colspan="3" style="vertical-align: middle;">Total</th>
                        </tr>
                        <tr>
                            @foreach($gradelevels as $eachgradelevel)
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>TOTAL</th>
                            @endforeach
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>PROMOTED</th>
                            @foreach($gradelevels as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->promotedmale}}</td>
                            <td class="text-center">{{$eachgradelevel->promotedfemale}}</td>
                            <td class="text-center">{{$eachgradelevel->promoted}}</th>
                            @endforeach
                            <th class="text-center">{{collect($gradelevels)->sum('promotedmale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('promotedfemale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('promoted')}}</th>
                        </tr>
                        {{-- @if($acadprogid != 3) --}}
                        <tr>
                            <th>CONDITIONAL</th>
                            @foreach($gradelevels as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->irregularmale}}</td>
                            <td class="text-center">{{$eachgradelevel->irregularfemale}}</td>
                            <td class="text-center">{{$eachgradelevel->irregular}}</th>
                            @endforeach
                            <th class="text-center">{{collect($gradelevels)->sum('irregularmale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('irregularfemale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('irregular')}}</th>
                        </tr>
                        {{-- @endif --}}
                        <tr>
                            <th>RETAINED</th>
                            @foreach($gradelevels as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->retainedmale}}</td>
                            <td class="text-center">{{$eachgradelevel->retainedfemale}}</td>
                            <td class="text-center">{{$eachgradelevel->retained}}</th>
                            @endforeach
                            <th class="text-center">{{collect($gradelevels)->sum('retainedmale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('retainedfemale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('retained')}}</th>
                        </tr>
                        @if($acadprogid == 5)
                        <tr>
                            <th>LEVEL OF POFICIENCY (K to 12 Only)	</th>
                            @foreach($gradelevels as $eachgradelevel)
                            <th class="text-center" style="vertical-align: middle;">MALE</th>
                            <th class="text-center" style="vertical-align: middle;">FEMALE</th>
                            <th class="text-center" style="vertical-align: middle;">TOTAL</th>
                            @endforeach
                            <th class="text-center" style="vertical-align: middle;">MALE</th>
                            <th class="text-center" style="vertical-align: middle;">FEMALE</th>
                            <th class="text-center" style="vertical-align: middle;">TOTAL</th>
                        </tr>
                        @endif
                        <tr>
                            <th>BEGINNING (B: 74% and below)	</th>
                            @foreach($gradelevels as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->proficiencybmale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencybfemale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencyb}}</th>
                            @endforeach
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencybmale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencybfemale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencyb')}}</th>
                        </tr>
                        <tr>
                            <th>DEVELOPING (D: 75%-79%)	</th>
                            @foreach($gradelevels as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->proficiencydmale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencydfemale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencyd}}</th>
                            @endforeach
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencydmale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencydfemale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencyd')}}</th>
                        </tr>
                        <tr>
                            <th>APPROACHING PROFICIENCY (AP: 80%-84%)	</th>
                            @foreach($gradelevels as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->proficiencyapmale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencyapfemale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencyap}}</th>
                            @endforeach
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencyapmale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencyapfemale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencyap')}}</th>
                        </tr>
                        <tr>
                            <th>PROFICIENT (P: 85%-89%)	</th>
                            @foreach($gradelevels as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->proficiencypmale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencypfemale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencyp}}</th>
                            @endforeach
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencypmale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencypfemale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencyp')}}</th>
                        </tr>
                        <tr>
                            <th>ADVANCED (A: 90% and above)	</th>
                            @foreach($gradelevels as $eachgradelevel)
                            <td class="text-center">{{$eachgradelevel->proficiencyamale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencyafemale}}</td>
                            <td class="text-center">{{$eachgradelevel->proficiencya}}</th>
                            @endforeach
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencyamale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencyafemale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencya')}}</th>
                        </tr>
                        <tr>
                            <th>TOTAL	</th>
                            @foreach($gradelevels as $eachgradelevel)
                            <th class="text-center">{{$eachgradelevel->proficiencybmale+$eachgradelevel->proficiencydmale+$eachgradelevel->proficiencyapmale+$eachgradelevel->proficiencypmale+$eachgradelevel->proficiencyamale}}
                            </th>
                            <th class="text-center">{{$eachgradelevel->proficiencybfemale+$eachgradelevel->proficiencydfemale+$eachgradelevel->proficiencyapfemale+$eachgradelevel->proficiencypfemale+$eachgradelevel->proficiencyafemale}}</th>
                            <th class="text-center">{{$eachgradelevel->proficiencyb+$eachgradelevel->proficiencyd+$eachgradelevel->proficiencyap+$eachgradelevel->proficiencyp+$eachgradelevel->proficiencya}}</th>
                            @endforeach
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencybmale')+collect($gradelevels)->sum('proficiencydmale')+collect($gradelevels)->sum('proficiencyapmale')+collect($gradelevels)->sum('proficiencypmale')+collect($gradelevels)->sum('proficiencyamale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencybfemale')+collect($gradelevels)->sum('proficiencydfemale')+collect($gradelevels)->sum('proficiencyapfemale')+collect($gradelevels)->sum('proficiencypfemale')+collect($gradelevels)->sum('proficiencyafemale')}}</th>
                            <th class="text-center">{{collect($gradelevels)->sum('proficiencyb')+collect($gradelevels)->sum('proficiencyd')+collect($gradelevels)->sum('proficiencyap')+collect($gradelevels)->sum('proficiencyp')+collect($gradelevels)->sum('proficiencya')}}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="tab-pane" id="tab_2" style="overflow-x: scroll;">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-primary" id="btn-export-shs" data-template="2"><i class="fa fa-download"></i> Export to PDF</button>
                    </div>
                </div>
                <table class="table table-bordered" style="font-size: 12px; table-layout: fixed;">
                    <thead>
                        <tr>
                            <th rowspan="3" colspan="2" style="width: 20%; vertical-align: middle;">GRADE LEVEL</th>
                            <th colspan="9" style="width: 40%; vertical-align: middle;">END OF SEMESTER STATUS</th>
                            <th colspan="9" style="width: 40%; vertical-align: middle;">END OF SCHOOL YEAR<br/>(Fill up only at the end of the second semester)</th>
                        </tr>
                        <tr>
                            <th colspan="3">COMPLETE</th>
                            <th colspan="3">INCOMPLETE</th>
                            <th colspan="3">TOTAL</th>
                            <th colspan="3">COMPLETE</th>
                            <th colspan="3">INCOMPLETE</th>
                            <th colspan="3">TOTAL</th>
                        </tr>
                        <tr>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                            <th>M</th>
                            <th>F</th>
                            <th>T</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="20">GRADE 11</th>
                        </tr>
                        <tr>
                            <th colspan="20">TRACK/STRAND/COURSE</th>
                        </tr>
                        @foreach(collect($gradelevels)->where('id',14)->values() as $gradelevel)
                            <tr>
                                <td colspan="2">{{$gradelevel->trackname}} - {{$gradelevel->strandname}}</td>
                                <td style="text-align: center;">{{$gradelevel->promotedmale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promotedfemale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promoted}}</td>
                                <td style="text-align: center;">{{$gradelevel->irregularmale}}</td>
                                <td style="text-align: center;">{{$gradelevel->irregularfemale}}</td>
                                <td style="text-align: center;">{{$gradelevel->irregular}}</td>
                                <td style="text-align: center;">{{$gradelevel->promotedmale+$gradelevel->irregularmale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promotedfemale+$gradelevel->irregularfemale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promoted+$gradelevel->irregular}}</td>
                                @if($semid == 1)
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                @else
                                <td style="text-align: center;">{{$gradelevel->promotedmale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promotedfemale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promoted}}</td>
                                <td style="text-align: center;">{{$gradelevel->irregularmale}}</td>
                                <td style="text-align: center;">{{$gradelevel->irregularfemale}}</td>
                                <td style="text-align: center;">{{$gradelevel->irregular}}</td>
                                <td style="text-align: center;">{{$gradelevel->promotedmale+$gradelevel->irregularmale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promotedfemale+$gradelevel->irregularfemale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promoted+$gradelevel->irregular}}</td>
                                @endif
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2" class="text-right">SUB TOTAL</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('promotedmale')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('promotedfemale')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('promoted')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('irregularmale')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('irregularfemale')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('irregular')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('promotedmale') + collect($gradelevels)->where('id',14)->sum('irregularmale')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('promotedfemale') + collect($gradelevels)->where('id',14)->sum('irregularfemale')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('promoted') + collect($gradelevels)->where('id',14)->sum('irregular')}}</th>
                            @if($semid == 1)
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            @else
                            <th>{{collect($gradelevels)->where('id',14)->sum('promotedmale')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('promotedfemale')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('promoted')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('irregularmale')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('irregularfemale')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('irregular')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('promotedmale') + collect($gradelevels)->where('id',14)->sum('irregularmale')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('promotedfemale') + collect($gradelevels)->where('id',14)->sum('irregularfemale')}}</th>
                            <th>{{collect($gradelevels)->where('id',14)->sum('promoted') + collect($gradelevels)->where('id',14)->sum('irregular')}}</th>
                            @endif
                        </tr>
                        <tr>
                            <th colspan="20">GRADE 12</th>
                        </tr>
                        <tr>
                            <th colspan="20">TRACK/STRAND/COURSE</th>
                        </tr>
                        @foreach(collect($gradelevels)->where('id',15)->values() as $gradelevel)
                            <tr>
                                <td colspan="2">{{$gradelevel->trackname}} - {{$gradelevel->strandname}}</td>
                                <td style="text-align: center;">{{$gradelevel->promotedmale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promotedfemale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promoted}}</td>
                                <td style="text-align: center;">{{$gradelevel->irregularmale}}</td>
                                <td style="text-align: center;">{{$gradelevel->irregularfemale}}</td>
                                <td style="text-align: center;">{{$gradelevel->irregular}}</td>
                                <td style="text-align: center;">{{$gradelevel->promotedmale+$gradelevel->irregularmale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promotedfemale+$gradelevel->irregularfemale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promoted+$gradelevel->irregular}}</td>
                                @if($semid == 1)
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                @else
                                <td style="text-align: center;">{{$gradelevel->promotedmale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promotedfemale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promoted}}</td>
                                <td style="text-align: center;">{{$gradelevel->irregularmale}}</td>
                                <td style="text-align: center;">{{$gradelevel->irregularfemale}}</td>
                                <td style="text-align: center;">{{$gradelevel->irregular}}</td>
                                <td style="text-align: center;">{{$gradelevel->promotedmale+$gradelevel->irregularmale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promotedfemale+$gradelevel->irregularfemale}}</td>
                                <td style="text-align: center;">{{$gradelevel->promoted+$gradelevel->irregular}}</td>
                                @endif
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2" class="text-right">SUB TOTAL</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('promotedmale')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('promotedfemale')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('promoted')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('irregularmale')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('irregularfemale')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('irregular')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('promotedmale') + collect($gradelevels)->where('id',15)->sum('irregularmale')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('promotedfemale') + collect($gradelevels)->where('id',15)->sum('irregularfemale')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('promoted') + collect($gradelevels)->where('id',15)->sum('irregular')}}</th>
                            @if($semid == 1)
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            @else
                            <th>{{collect($gradelevels)->where('id',15)->sum('promotedmale')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('promotedfemale')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('promoted')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('irregularmale')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('irregularfemale')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('irregular')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('promotedmale') + collect($gradelevels)->where('id',15)->sum('irregularmale')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('promotedfemale') + collect($gradelevels)->where('id',15)->sum('irregularfemale')}}</th>
                            <th>{{collect($gradelevels)->where('id',15)->sum('promoted') + collect($gradelevels)->where('id',15)->sum('irregular')}}</th>
                            @endif
                        </tr>
                        <tr>
                            <th colspan="2" class="text-right">TOTAL</th>
                            <th>{{collect($gradelevels)->sum('promotedmale')}}</th>
                            <th>{{collect($gradelevels)->sum('promotedfemale')}}</th>
                            <th>{{collect($gradelevels)->sum('promoted')}}</th>
                            <th>{{collect($gradelevels)->sum('irregularmale')}}</th>
                            <th>{{collect($gradelevels)->sum('irregularfemale')}}</th>
                            <th>{{collect($gradelevels)->sum('irregular')}}</th>
                            <th>{{collect($gradelevels)->sum('promotedmale') + collect($gradelevels)->sum('irregularmale')}}</th>
                            <th>{{collect($gradelevels)->sum('promotedfemale') + collect($gradelevels)->sum('irregularfemale')}}</th>
                            <th>{{collect($gradelevels)->sum('promoted') + collect($gradelevels)->sum('irregular')}}</th>
                            @if($semid == 1)
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            @else
                            <th>{{collect($gradelevels)->sum('promotedmale')}}</th>
                            <th>{{collect($gradelevels)->sum('promotedfemale')}}</th>
                            <th>{{collect($gradelevels)->sum('promoted')}}</th>
                            <th>{{collect($gradelevels)->sum('irregularmale')}}</th>
                            <th>{{collect($gradelevels)->sum('irregularfemale')}}</th>
                            <th>{{collect($gradelevels)->sum('irregular')}}</th>
                            <th>{{collect($gradelevels)->sum('promotedmale') + collect($gradelevels)->sum('irregularmale')}}</th>
                            <th>{{collect($gradelevels)->sum('promotedfemale') + collect($gradelevels)->sum('irregularfemale')}}</th>
                            <th>{{collect($gradelevels)->sum('promoted') + collect($gradelevels)->sum('irregular')}}</th>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>     
        </div>        
    </div>
</div>

@else
    <div class="card">   
        <div class="card-header">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-primary" id="btn-export"><i class="fa fa-download"></i> Export to PDF</button>
                </div>
            </div>
        </div>
        <div class="card-body p-0" style="overflow: scroll;">
            <table class="table table-bordered" style="font-size: 12px;">
                <thead class="text-center">
                    <tr>
                        <th rowspan="2">Summary Table</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <th colspan="3">{{$eachgradelevel->levelname}}</th>
                        @endforeach
                        <th colspan="3">Total</th>
                    </tr>
                    <tr>
                        @foreach($gradelevels as $eachgradelevel)
                        <th>MALE</th>
                        <th>FEMALE</th>
                        <th>TOTAL</th>
                        @endforeach
                        <th>MALE</th>
                        <th>FEMALE</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>PROMOTED</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->promotedmale}}</td>
                        <td class="text-center">{{$eachgradelevel->promotedfemale}}</td>
                        <td class="text-center">{{$eachgradelevel->promoted}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('promotedmale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('promotedfemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('promoted')}}</th>
                    </tr>
                    {{-- @if($acadprogid != 3) --}}
                    <tr>
                        <th style="text-align: left;">CONDITIONAL</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->irregularmale}}</td>
                        <td class="text-center">{{$eachgradelevel->irregularfemale}}</td>
                        <td class="text-center">{{$eachgradelevel->irregular}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('irregularmale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('irregularfemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('irregular')}}</th>
                    </tr>
                    {{-- @endif --}}
                    <tr>
                        <th>RETAINED</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->retainedmale}}</td>
                        <td class="text-center">{{$eachgradelevel->retainedfemale}}</td>
                        <td class="text-center">{{$eachgradelevel->retained}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('retainedmale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('retainedfemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('retained')}}</th>
                    </tr>
                    {{-- @if($acadprogid == 5) --}}
                    <tr>
                        <th>LEARNING PROGRESS & ACHIEVEMENT (based on Learners' General Average)</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <th class="text-center">MALE</th>
                        <th class="text-center">FEMALE</th>
                        <th class="text-center">TOTAL</th>
                        @endforeach
                        <th class="text-center">MALE</th>
                        <th class="text-center">FEMALE</th>
                        <th class="text-center">TOTAL</th>
                    </tr>
                    {{-- @endif --}}
                    <tr>
                        <th>
                            {{-- BEGINNING (B: 74% and below)	 --}}
                            Did Not Meet Expectation<br/>( 74 and below)
                        </th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->proficiencybmale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencybfemale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencyb}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencybmale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencybfemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyb')}}</th>
                    </tr>
                    <tr>
                        <th>
                            {{-- DEVELOPING (D: 75%-79%) --}}
                            Fairly Satisfactory<br/>( 75-79)	
                        </th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->proficiencydmale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencydfemale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencyd}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencydmale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencydfemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyd')}}</th>
                    </tr>
                    <tr>
                        <th>
                            {{-- APPROACHING PROFICIENCY (AP: 80%-84%)	 --}}
                            Satisfactory<br/> ( 80-84)	
                        </th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->proficiencyapmale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencyapfemale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencyap}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyapmale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyapfemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyap')}}</th>
                    </tr>
                    <tr>
                        <th>
                            {{-- PROFICIENT (P: 85%-89%) --}}
                            Very Satisfactory<br/>( 85-89)	
                            </th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->proficiencypmale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencypfemale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencyp}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencypmale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencypfemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyp')}}</th>
                    </tr>
                    <tr>
                        <th>
                            {{-- ADVANCED (A: 90% and above) --}}
                            Outstanding<br/>(90-100)	
    

                            </th>
                        @foreach($gradelevels as $eachgradelevel)
                        <td class="text-center">{{$eachgradelevel->proficiencyamale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencyafemale}}</td>
                        <td class="text-center">{{$eachgradelevel->proficiencya}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyamale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyafemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencya')}}</th>
                    </tr>
                    <tr>
                        <th>TOTAL	</th>
                        @foreach($gradelevels as $eachgradelevel)
                        <th class="text-center">{{$eachgradelevel->proficiencybmale+$eachgradelevel->proficiencydmale+$eachgradelevel->proficiencyapmale+$eachgradelevel->proficiencypmale+$eachgradelevel->proficiencyamale}}
                        </th>
                        <th class="text-center">{{$eachgradelevel->proficiencybfemale+$eachgradelevel->proficiencydfemale+$eachgradelevel->proficiencyapfemale+$eachgradelevel->proficiencypfemale+$eachgradelevel->proficiencyafemale}}</th>
                        <th class="text-center">{{$eachgradelevel->proficiencyb+$eachgradelevel->proficiencyd+$eachgradelevel->proficiencyap+$eachgradelevel->proficiencyp+$eachgradelevel->proficiencya}}</th>
                        @endforeach
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencybmale')+collect($gradelevels)->sum('proficiencydmale')+collect($gradelevels)->sum('proficiencyapmale')+collect($gradelevels)->sum('proficiencypmale')+collect($gradelevels)->sum('proficiencyamale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencybfemale')+collect($gradelevels)->sum('proficiencydfemale')+collect($gradelevels)->sum('proficiencyapfemale')+collect($gradelevels)->sum('proficiencypfemale')+collect($gradelevels)->sum('proficiencyafemale')}}</th>
                        <th class="text-center">{{collect($gradelevels)->sum('proficiencyb')+collect($gradelevels)->sum('proficiencyd')+collect($gradelevels)->sum('proficiencyap')+collect($gradelevels)->sum('proficiencyp')+collect($gradelevels)->sum('proficiencya')}}</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endif