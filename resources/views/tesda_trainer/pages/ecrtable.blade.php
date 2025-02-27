


<style>
    .bordered {
        border: 1px solid black!important;
    }
    .bordered td, .bordered th, .bordered tr {
        border: 1px solid black!important;
    }
    [contenteditable]{
        font-size: 9pt!important;
    }
    .total_score{
        font-size: 9pt!important;
        font-weight: bold;
    }
    .average_score{
        font-size: 9pt!important;
        font-weight: bold;
    }
    .total_average{
        font-size: 9pt!important;
        font-weight: bold;
    }
    .gen_average{
        font-size: 9pt!important;
        font-weight: bold;
    }
</style>


<div width="100%" class="mt-3 col-md-12 table-responsive">
    @php
        $columnCount = 0;
        foreach ($component as $item) {
            if (count($item['subgrading']) > 0) {
                foreach ($item['subgrading'] as $subitem) {
                    $columnCount += $subitem['subcomponent_column'] + 2; // Includes Total & Average
                }
            } else {
                $columnCount += $item['component_column'] + 2; // Includes Total & Average
            }
            $columnCount += 2; // For Gen. Ave & %
        }
        $dynamicWidth = 70 / $columnCount; // Distribute remaining 70% width evenly
    @endphp
    <table width="100%" class="table table-sm bordered" style="font-size: .6rem">
        <thead>
            <tr>
                <th rowspan="2" width="30%" class="text-center align-middle">STUDENT</th>
                    @foreach($component as $item)
                        @php
                            $hasSubcomponents = count($item['subgrading']) > 0;
                            $total = $hasSubcomponents ? collect($item['subgrading'])->sum('subcomponent_column') + (count($item['subgrading']) * 2) + 2 : $item['component_column'] + 4;
                        @endphp
                        <th colspan="{{$total}}" class="text-center align-middle">{{$item['componentname']}}</th>
                    @endforeach
                <th rowspan="4" width="{{$dynamicWidth}}%" class="align-middle text-center" style="writing-mode: vertical-rl; transform: rotate(180deg);">Total Average</th>
            </tr>
            <tr>
                @foreach($component as $item)
                    @if(count($item['subgrading']) > 0)
                        @foreach($item['subgrading'] as $subitem)
                            <th colspan="{{$subitem['subcomponent_column']}}" class="text-center align-middle">{{$subitem['subcompname']}}</th>
                            <th rowspan="3" class="align-middle text-center" style="writing-mode: vertical-rl; padding: -20px; transform: rotate(180deg); ">Total</th>
                            <th rowspan="3" class="align-middle text-center" style="writing-mode: vertical-rl; padding: -20px; transform: rotate(180deg);">Average</th>
                        @endforeach
                    @else
                        <th colspan="{{$item['component_column']}}" class="text-center align-middle">{{$item['componentname']}}</th>
                        <th width="{{$dynamicWidth}}%" rowspan="3"  class="align-middle text-center" style="writing-mode: vertical-rl; padding: -20px; transform: rotate(180deg);">Total</th>
                        <th width="{{$dynamicWidth}}%" rowspan="3"  class="align-middle text-center"  style="writing-mode: vertical-rl; padding: -20px; transform: rotate(180deg);">Average</th>
                    @endif
                    <th width="{{$dynamicWidth}}%" rowspan="3" class="align-middle text-center" style="writing-mode: vertical-rl; padding: -20px; transform: rotate(180deg);">Gen. Ave</th>
                    <th width="{{$dynamicWidth}}%" rowspan="3" class="align-middle text-center" style="">%</th>
                @endforeach
            </tr>
            <tr>
                <th rowspan="2" class="align-middle">HIGHEST POSSIBLE SCORE</th>
                @foreach($component as $item)
                    @if(count($item['subgrading']) > 0)
                        @foreach($item['subgrading'] as $subitem)
                            @php
                                $columns = $subitem['subcomponent_column'];
                                $subsorts = 0
                            @endphp
                            @foreach(range(1, $columns) as $column)
                                @php
                                    $subsorts += 1
                                @endphp
                                <th  class="text-center align-middle date_time text-secondary" data-sort-id="{{$subsorts}}" data-comp-id="{{$item['componentid']}}" data-sub-id="{{$subitem['subcompid']}}" contenteditable  style="writing-mode: vertical-rl; padding: -20px; transform: rotate(180deg);" >MM/DD/YYYY</th>
                            @endforeach
                        @endforeach
                    @else
                        @php
                            $compsort = 0
                        @endphp
                        @foreach(range(1, $item['component_column']) as $column)
                            @php
                                $compsort += 1
                            @endphp
                            <th  class="text-center align-middle date_time text-secondary" data-sort-id="{{$compsort}}" data-comp-id="{{$item['componentid']}}" contenteditable  style="writing-mode: vertical-rl; padding: -20px; transform: rotate(180deg);" >MM/DD/YYYY</th>
                        @endforeach
                    @endif
                @endforeach
            </tr>
            <tr>
                @foreach($component as $item)
                    @if(count($item['subgrading']) > 0)
                        @foreach($item['subgrading'] as $subitem)
                            @php
                                $columns = $subitem['subcomponent_column'];
                                $subsorts = 0
                            @endphp
                            @foreach(range(1, $columns) as $column)
                                @php
                                    $subsorts += 1
                                @endphp
                                <th  class="text-center align-middle highest_score" data-sort-id="{{$subsorts}}" data-comp-id="{{$item['componentid']}}" data-sub-id="{{$subitem['subcompid']}}" contenteditable>0</th>
                            @endforeach
                        @endforeach
                    @else
                        @php
                            $compsort = 0
                        @endphp
                        @foreach(range(1, $item['component_column']) as $column)
                            @php
                                $compsort += 1
                            @endphp
                            <th class="text-center align-middle highest_score"  data-sort-id="{{$compsort}}" data-comp-id="{{$item['componentid']}}" contenteditable>0</th>
                        @endforeach
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr class="bg-secondary">
                <td class="font-weight-bold align-middle">
                    <div class="d-flex flex-row justify-content-betweem">
                        <div class="mr-auto">Male</div>
                        <div><input type="checkbox" id="male_checkbox" class="gender_checkbox select ml-auto"></div>
                    </div>
                </td>
                @foreach($component as $item)
                    @if(count($item['subgrading']) > 0)
                        @foreach($item['subgrading'] as $subitem)
                            @for($i = 1; $i <= $subitem['subcomponent_column']; $i++)
                                <td width="{{$dynamicWidth}}%" class="text-center align-middle" ></td>
                            @endfor
                            <td width="{{$dynamicWidth}}%"></td>
                            <td width="{{$dynamicWidth}}%"></td>
                        @endforeach
                        
                    @else
                        @for($i = 1; $i <= $item['component_column']; $i++)
                            <td width="{{$dynamicWidth}}%" class="text-center align-middle" ></td>
                        @endfor
                        <td width="{{$dynamicWidth}}%"></td>
                        <td width="{{$dynamicWidth}}%"></td>
                    @endif
                    <td width="{{$dynamicWidth}}%"></td>
                    <td width="{{$dynamicWidth}}%"></td>
                @endforeach
                <td width="{{$dynamicWidth}}%"></td>
            </tr>
        
            @foreach($students->where('gender', 'Male') as $stud)
                <tr>
                    <td class="align-middle">
                        <div class="d-flex flex-row justify-content-betweem">
                            <div class="mr-auto">{{$stud->studname}}</div>
                            <div><input type="checkbox"  class="male_checkbox checkbox select ml-auto" data-stud-id="{{$stud->studid}}"></div>
                        </div>
                    </td>
                    @foreach($component as $item)
                        @if(count($item['subgrading']) > 0)
                            @foreach($item['subgrading'] as $subitem)
                                @php
                                    $sort = 0
                                @endphp
                                @for($i = 1; $i <= $subitem['subcomponent_column']; $i++)
                                    @php
                                        $sort += 1
                                    @endphp
                                    <td class="text-center align-middle score scores"  data-sort-id="{{$sort}}" data-comp-id="{{$item['componentid']}}" data-stud-id="{{$stud->studid}}" data-sub-id="{{$subitem['subcompid']}}"  contenteditable >0</td>
                                @endfor
                                <td class="text-center align-middle total_score" data-stud-id="{{$stud->studid}}" data-comp-id="{{$item['componentid']}}"  data-sub-id="{{$subitem['subcompid']}}">0</td>
                                <td class="text-center align-middle average_score" data-stud-id="{{$stud->studid}}" data-comp-id="{{$item['componentid']}}" data-sub-id="{{$subitem['subcompid']}}">0</td>
                            @endforeach
                        @else
                            @php
                                $sorted = 0
                            @endphp
                            @for($i = 1; $i <= $item['component_column']; $i++)
                            @php
                                $sorted += 1
                            @endphp
                                <td class="text-center align-middle score scores" data-sort-id="{{$sorted}}" data-stud-id="{{$stud->studid}}" data-comp-id="{{$item['componentid']}}" contenteditable>0</td>
                            @endfor
                            <td class="text-center align-middle total_score" data-stud-id="{{$stud->studid}}" data-comp-id="{{$item['componentid']}}">0</td>
                            <td class="text-center align-middle average_score"  data-stud-id="{{$stud->studid}}" data-comp-id="{{$item['componentid']}}">0</td>
                        @endif
                        <td class="text-center align-middle gen_average" data-stud-id="{{$stud->studid}}" data-comp-id="{{$item['componentid']}}">0</td>
                        <td></td>
                    @endforeach
                    <td class="total_average text-center align-middle" data-stud-id="{{$stud->studid}}">0</td>
                </tr>
            @endforeach
        
            <tr class="bg-secondary">
                <td class="font-weight-bold align-middle">
                    <div class="d-flex flex-row justify-content-betweem">
                        <div class="mr-auto">Female</div>
                        <div><input type="checkbox" id="female_checkbox" class="gender_checkbox select ml-auto"></div>
                    </div>
                </td>
                @foreach($component as $item)
                    @if(count($item['subgrading']) > 0)
                        @foreach($item['subgrading'] as $subitem)
                            @for($i = 1; $i <= $subitem['subcomponent_column']; $i++)
                                <td class="text-center align-middle" ></td>
                            @endfor
                            <td class=""></td>
                            <td class=""></td>
                        @endforeach
                    @else
                        @for($i = 1; $i <= $item['component_column']; $i++)
                            <td class="text-center align-middle" ></td>
                        @endfor
                        <td class=""></td>
                        <td class=""></td>
                    @endif
                    <td></td>
                    <td></td>
                @endforeach
                <td></td>
            </tr>
            @foreach($students->where('gender', 'Female') as $stud)
                <tr>
                    <td class="align-middle ">
                        <div class="d-flex flex-row justify-content-betweem">
                            <div class="mr-auto">{{$stud->studname}}</div>
                            <div><input type="checkbox"  class="female_checkbox checkbox select ml-auto" data-stud-id="{{$stud->studid}}"></div>
                        </div>
                    </td>
                    @foreach($component as $item)
                        @if(count($item['subgrading']) > 0)
                            @foreach($item['subgrading'] as $subitem)
                                @php
                                    $sort = 0
                                @endphp
                                @for($i = 1; $i <= $subitem['subcomponent_column']; $i++)
                                    @php
                                        $sort += 1
                                    @endphp
                                    <td class="text-center align-middle score scores"  data-sort-id="{{$sort}}" data-comp-id="{{$item['componentid']}}" data-stud-id="{{$stud->studid}}" data-sub-id="{{$subitem['subcompid']}}"  contenteditable >0</td>
                                @endfor
                                <td class="text-center align-middle total_score" data-stud-id="{{$stud->studid}}" data-comp-id="{{$item['componentid']}}"  data-sub-id="{{$subitem['subcompid']}}">0</td>
                                <td class="text-center align-middle average_score" data-stud-id="{{$stud->studid}}" data-comp-id="{{$item['componentid']}}" data-sub-id="{{$subitem['subcompid']}}">0</td>
                            @endforeach
                        @else
                            @php
                                $sorted = 0
                            @endphp
                            @for($i = 1; $i <= $item['component_column']; $i++)
                            @php
                                $sorted += 1
                            @endphp
                                <td class="text-center align-middle score scores" data-sort-id="{{$sorted}}" data-stud-id="{{$stud->studid}}" data-comp-id="{{$item['componentid']}}" contenteditable>0</td>
                            @endfor
                            <td class="text-center align-middle total_score" data-stud-id="{{$stud->studid}}" data-comp-id="{{$item['componentid']}}">0</td>
                            <td class="text-center align-middle average_score"  data-stud-id="{{$stud->studid}}" data-comp-id="{{$item['componentid']}}">0</td>
                        @endif
                        <td class="text-center align-middle gen_average" data-stud-id="{{$stud->studid}}" data-comp-id="{{$item['componentid']}}">0</td>
                        <td></td>
                    @endforeach
                    <td class="total_average text-center align-middle" data-stud-id="{{$stud->studid}}">0</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex flex-row justify-content-end">
        <button class="btn btn-success btn-sm d-none" id="save_grades">Save Grades</button>
        <button class="btn btn-primary btn-sm ml-2 d-none" id="submit_grades">Submit Grades</button>
    </div>
</div>