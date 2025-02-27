<style>
    * { font-family: Arial, Helvetica, sans-serif;}
    @page { margin: 20px;}

    #table1 td{
        padding: 0px;
    }
    table {
        border-collapse: collapse;

    }
    #table2{
        margin-top: 2px;
        font-size: 11px;
    }

    input[type="checkbox"] {
    /* position: relative; */
    top: 2px;
    box-sizing: content-box;
    width: 14px;
    height: 14px;
    margin: 0 5px 0 0;
    cursor: pointer;
    -webkit-appearance: none;
    border-radius: 2px;
    background-color: #fff;
    border: 1px solid #b7b7b7;
    }

    input[type="checkbox"]:before {
    content: '';
    display: block;
    }

    input[type="checkbox"]:checked:before {
    width: 4px;
    height: 9px;
    margin: 0px 4px;
    border-bottom: 2px solid ;
    border-right: 2px solid ;
    transform: rotate(45deg);
    }
    .text-center{
        text-align: center;
    }
    table{
        page-break-inside: auto !important;
    }
    table tr{
        page-break-inside: auto !important;
    }
</style>

{{-- <table style="width: 100%" >
    <tr>
        <td width="5%" style="text-align: left;"></td>
        <td width="12%" rowspan="6" style="text-align: right;">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
        </td>
        <td style="text-align:center; font-size: 11px;">Republic of the Philippines</td>
        <td width="17%" style="text-align:left;" rowspan="6"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px"></td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:center; font-size: 11px;">Department of Education</td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:center; font-size: 11px;">Region X-Northern Mindanao</td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:center; font-size: 11px;">Division of Ozamiz City</td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:center; font-size: 11px; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:center; font-size: 12px; font-weight: bold;">Learner's Permanent Academic Record for Pre-school</td>
    </tr>
    <tr style="line-height: 10px;font-size: 11px;">
        <td style="text-align:center; font-weight: bold;" colspan="4">(Formerly Form 137)</td>
    </tr>
</table> --}}
<table class="table table-sm table-bordered" width="100%" style="font-size: 19px !important; margin-top:.5rem !important;">
    <tr>
        <td class="text-center" style="font-weight: bold; background-color: #8dcf5f; border: 1px solid black;">PUPIL'S PERMANENT RECORD</td>
    </tr>
</table>
<table class="table table-sm" width="100%" style="font-size: 11px !important; margin-top:.5rem !important;">
    <tr>
        <td style="width: 10%;">Name:</td>
        <td style="width: 25%; border-bottom: 1px solid black;">{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename}} {{$studinfo->suffix}}</td>
        <td style="width: 10%;">Date of Birth:</td>
        <td style="width: 25%; border-bottom: 1px solid black;">{{\Carbon\Carbon::create($studinfo->dob)->isoFormat('MMMM DD, YYYY')}}</td>
        <td style="width: 11%;">Sex:</td>
        <td style="width: 19%; border-bottom: 1px solid black;">{{$studinfo->gender}}</td>
    </tr>
    <tr>
        <td>Parent:</td>
        <td style="border-bottom: 1px solid black;">{{$guardianname}}</td>
        <td>Place of Birth:</td>
        <td style="border-bottom: 1px solid black;"></td>
        <td>LRN:</td>
        <td style="border-bottom: 1px solid black;">{{$studinfo->lrn}}</td>
    </tr>
    <tr>
        <td>Address:</td>
        <td style="border-bottom: 1px solid black;">{{$address}}</td>
        <td>Nationality:</td>
        <td style="border-bottom: 1px solid black;">{{$nationality}}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
{{-- <table class="table table-sm" width="100%" style="font-size: 11px !important;">
    <tr>
        <td style="width: 5%;">LRN:</td>
        <td style="width: 12%; border-bottom: 1px solid black;">{{$studinfo->lrn}}</td>
        <td style="width: 20%; text-align: right;">Date of Birth (MM/DD/YYYY):</td>
        <td style="width: 13%; border-bottom: 1px solid black;">{{\Carbon\Carbon::create($studinfo->dob)->isoFormat('MMMM DD, YYYY')}}</td>
        <td style="width: 5%; text-align: right;">Sex:</td>
        <td style="width: 7%; border-bottom: 1px solid black;">{{$studinfo->gender}}</td>
        <td style="width: 26%;">Date of SHS Admission (MM/DD/YYYY):</td>
        <td style="width: 12%; border-bottom: 1px solid black;">{{$studinfo->enlevelid == 15 ? 'April 19, 2019' : 'June 29, 2020'}}</td>
    </tr>
</table> --}}
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table class="table table-sm table-bordered" width="100%" style="font-size: 14px !important; margin-top:.5rem !important;">
    <tr>
        <td class="text-center" style="font-weight: bold; background-color: #8dcf5f; border: 1px solid black;">PRESCHOOL PROGRESS REPORT </td>
    </tr>
</table>
<div style="width: 100%; line-height: 5px;">&nbsp;</div>
{{-- <table style="width: 100%; text-align: left !important; font-size: 11px ;">
    <thead>
        <tr>
            <th colspan="3">
                Marks for the Progress Report:
            </th>
        </tr>
    </thead>
    <tr>
        <td>A- Highly Advanced Development	</td>
        <td>B- Slightly Advanced Development </td>
        <td>B- Slightly Advanced Development </td>
    </tr>
    <tr>
        <td>C - Average Development		</td>
        <td>D- Slight Delay in Development</td>
        <td>E – Significant Delay in Development</td>
    </tr>
</table> --}}
{{-- <div style="width: 100%; page-break-inside: always; border: 1px solid black; vertical-align: top;"> --}}
    <table style="width: 100%; font-size: 10px;" border="1">
        <thead style="text-align: center;">
            <tr>
                <th style="width: 30%;">School:</th>
                @foreach($gradelevels as $key => $eachlevel)
                    <th colspan="5">{{$eachlevel->sf10schoolname}}</th>
                    @if($key != collect($gradelevels)->reverse()->keys()->first())
                    <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                    @endif
                @endforeach
            </tr>
            <tr>
                <th style="width: 30%;">Level:</th>
                @foreach($gradelevels as $key => $eachlevel)
                    <th colspan="5">{{$eachlevel->sf10levelname}}</th>
                    @if($key != collect($gradelevels)->reverse()->keys()->first())
                    <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                    @endif
                @endforeach
            </tr>
            <tr>
                <th rowspan="2">
                    LEARNING AREAS
                </th>
                @foreach($gradelevels as $key => $eachlevel)
                <th colspan="5">Periodic Rating</th>
                    @if($key != collect($gradelevels)->reverse()->keys()->first())
                    <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                    @endif
                @endforeach
            </tr>
            <tr>
                @foreach($gradelevels as $key => $eachlevel)
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>Action Taken</th>
                    @if($key != collect($gradelevels)->reverse()->keys()->first())
                    <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tr style="background-color:#8dcf5f; font-weight: bold;">
            <td style="padding-left:9px;">I. GROSS MOTOR</td>
            @foreach($gradelevels as $key => $eachlevel)
                <th colspan="5"></th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">1. Coordination of leg movements</td>
            @foreach($gradelevels as $key => $eachlevel)
                <td class="text-center">{{collect($eachlevel->grades)->where('sort','1B')->first()->q1eval}}</td>
                <td class="text-center">{{collect($eachlevel->grades)->where('sort','1B')->first()->q2eval}}</td>
                <td class="text-center">{{collect($eachlevel->grades)->where('sort','1B')->first()->q3eval}}</td>
                <td class="text-center">{{collect($eachlevel->grades)->where('sort','1B')->first()->q4eval}}</td>
                <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','1B')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">2. Coordination of arm movements</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','1C')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','1C')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','1C')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','1C')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','1C')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">3. Movement of body parts as instructed.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','1D')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','1D')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','1D')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','1D')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','1D')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr style="background-color:#8dcf5f; font-weight: bold;">
            <td style="padding-left:9px;">II. FINE MOTOR</td>
            @foreach($gradelevels as $key => $eachlevel)
                <th colspan="5"></th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">1. Coordination in the use of fingers in picking objects</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','2B')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','2B')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','2B')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','2B')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','2B')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">2. Coordination of fingers for scribbling and drawing.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','2C')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','2C')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','2C')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','2C')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','2C')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">3. Display of definite hand preference (either left or right)</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','2D')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','2D')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','2D')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','2D')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','2D')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr style="background-color:#8dcf5f; font-weight: bold;">
            <td style="padding-left:9px;">III. RECEPTIVE LANGUAGE</td>
            @foreach($gradelevels as $key => $eachlevel)
                <th colspan="5"></th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">1. Following instructions correctly.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','3B')->first()->q1eval}}</td>
             <td class="text-center">{{collect($eachlevel->grades)->where('sort','3B')->first()->q2eval}}</td>
             <td class="text-center">{{collect($eachlevel->grades)->where('sort','3B')->first()->q3eval}}</td>
             <td class="text-center">{{collect($eachlevel->grades)->where('sort','3B')->first()->q4eval}}</td>
             <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','3B')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">2. Pointing family members correctly when ask to do so</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','3C')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','3C')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','3C')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','3C')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','3C')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">3. Pointing named objects correctly when ask to do so</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','3D')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','3D')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','3D')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','3D')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','3D')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr style="background-color:#8dcf5f; font-weight: bold;">
            <td style="padding-left:9px;">IV. EXPRESSIVE LANGUAGE</td>
            @foreach($gradelevels as $key => $eachlevel)
                <th colspan="5"></th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">1. Using recognizable words correctly.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4B')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4B')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4B')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4B')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','4B')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">2. Naming objects and pictures correctly</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4C')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4C')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4C')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4C')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','4C')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">3. Asking questions appropriately (who, what, when, why, how?)</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4D')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4D')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4D')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4D')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','4D')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">4. Telling account of recent experiences.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4E')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4E')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4E')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','4E')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','4E')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr style="background-color:#8dcf5f; font-weight: bold;">
            <td style="padding-left:9px;">VI. COGNITIVE DEVELOPMENT</td>
            @foreach($gradelevels as $key => $eachlevel)
                <th colspan="5"></th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th></th>
                @endif
            @endforeach
        </tr>
        <tr style="background-color:#cbedaf; font-weight: bold;">
            <td style="padding-left:9px;">A. WRITING READINESS</td>
            @foreach($gradelevels as $key => $eachlevel)
                <th colspan="5"></th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">1. Exhibition of left to right progression.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA2')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA2')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA2')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA2')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FA2')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">2. Writing name correctly</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA3')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA3')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA3')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA3')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FA3')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">3. Writing upper case or lower case letters from memory</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA4')->first()->q1eval}}</td>
           <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA4')->first()->q2eval}}</td>
           <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA4')->first()->q3eval}}</td>
           <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA4')->first()->q4eval}}</td>
           <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FA4')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">4. Correctly copying shapes</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA5')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA5')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA5')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FA5')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FA5')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr style="background-color:#cbedaf; font-weight: bold;">
            <td style="padding-left:9px;">B. READING READINESS</td>
            @foreach($gradelevels as $key => $eachlevel)
                <th colspan="5"></th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">1. Correct Identification of objects and pictures</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB2')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB2')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB2')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB2')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FB2')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">2. Correctly identifies similarities and differences of objects and pictures</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB3')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB3')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB3')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB3')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FB3')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">3. Correct identification of upper or lower case letters from memory </td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB4')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB4')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB4')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB4')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FB4')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">4. Correctly matching objects or pictures with the alphabet</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB5')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB5')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB5')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB5')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FB5')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">5. Correctly sorting out pictures, alphabet, or shapes</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB6')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB6')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB6')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB6')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FB6')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">6. Correctly following signs and symbols</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB7')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB7')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB7')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FB7')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FB7')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
       
        <tr style="background-color:#cbedaf; font-weight: bold;">
            <td style="padding-left:9px;">C. LANGUAGE</td>
            @foreach($gradelevels as $key => $eachlevel)
                <th colspan="5"></th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">1. Listening attentively to someone who speaks.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC2')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC2')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC2')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC2')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FC2')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">2. Correctly distinguish different type of sounds.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC3')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC3')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC3')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC3')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FC3')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">3. Responding correctly to different type of sounds.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC4')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC4')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC4')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC4')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FC4')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">4. Recalling significant facts in a story.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC5')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC5')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC5')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC5')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FC5')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">5. Expressing own thoughts, feelings and ideas.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC6')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC6')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC6')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC6')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FC6')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">6. Exhibiting comprehension of learned concepts.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC7')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC7')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC7')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC7')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FC7')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">7. Responding correctly to questions asked.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC8')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC8')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC8')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC8')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FC8')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">8. Reciting poems and verses.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC9')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC9')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC9')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FC9')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FC9')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr style="background-color:#cbedaf; font-weight: bold;">
            <td style="padding-left:9px;">D. MATH AND SCIENCE</td>
            @foreach($gradelevels as $key => $eachlevel)
                <th colspan="5"></th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">1. Reciting correctly numbers 1 to 10.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD2')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD2')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD2')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD2')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FD2')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">2. Writing numerals 1 to 10.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD3')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD3')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD3')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD3')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FD3')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">3. Identifying correctly the number of animals, objects, or pictures</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD4')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD4')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD4')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD4')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FD4')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">4. Correct identification of shapes </td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD5')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD5')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD5')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD5')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FD5')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">5. Showing understanding on the concept of length, mass, volume/capacity</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD6')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD6')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD6')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD6')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FD6')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">6. Exhibiting interests and curiosity about the environment</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD7')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD7')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD7')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD7')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FD7')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">7. Showing interests and curiosity about living organism.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD8')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD8')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD8')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FD8')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FD8')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr style="background-color:#cbedaf; font-weight: bold;">
            <td style="padding-left:9px;">E. MUSIC AND ARTS</td>
            @foreach($gradelevels as $key => $eachlevel)
                <th colspan="5"></th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">1. Participation in music and art related activities</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE2')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE2')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE2')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE2')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FE2')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">2. Skill in drawing, singing, dancing, and/or acting.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE3')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE3')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE3')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE3')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FE3')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">3. Exhibiting interests in music and rhythm</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE4')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE4')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE4')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE4')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FE4')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">4. Exhibiting ideas and feelings through print or art media</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE5')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE5')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE5')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FE5')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FE5')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr style="background-color:#8dcf5f; font-weight: bold;">
            <td style="padding-left:9px;">VII. SOCIAL, EMOTIONAL AND SPIRITUAL DEVELOPMENT</td>
            @foreach($gradelevels as $key => $eachlevel)
                <th colspan="5"></th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">1.Exhibiting concepts and feelings about self, family, school and community</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG2')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG2')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG2')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG2')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FG2')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">2. Willingness to be with peers, adults and strangers.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG3')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG3')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG3')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG3')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FG3')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">3. Demonstration of courtesy and respect</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG4')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG4')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG4')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG4')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FG4')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">4. Correct identification of feelings of others</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG5')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG5')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG5')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG5')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FG5')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">5. Showing cooperation in group situations.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG6')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG6')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG6')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG6')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FG6')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">6. Expressing own feelings.</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG7')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG7')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG7')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FG7')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FG7')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr style="background-color:#8dcf5f; font-weight: bold;">
            <td style="padding-left:9px;">VIII. HEALTH AND SAFETY HABITS</td>
            @foreach($gradelevels as $key => $eachlevel)
                <th colspan="5"></th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th></th>
                @endif
            @endforeach
        </tr>
        
        <tr>
            <td style="padding-left:9px;">Keeping own self clean and tidy</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FI2')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FI2')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FI2')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FI2')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FI2')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr>
            <td style="padding-left:9px;">Exhibiting hygiene practices</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FI3')->first()->q1eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FI3')->first()->q2eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FI3')->first()->q3eval}}</td>
            <td class="text-center">{{collect($eachlevel->grades)->where('sort','FI3')->first()->q4eval}}</td>
            <th style="text-align: center;">{{collect($eachlevel->grades)->where('sort','FI3')->first()->remarks}}</th>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border-top: none; border-bottom: none;"></th>
                @endif
            @endforeach
        </tr>
        <tr style="border: none !important;">
            <td style="border: none !important;">&nbsp;</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td style="border: none !important;">&nbsp;</td>
            <td style="border: none !important;">&nbsp;</td>
            <td style="border: none !important;">&nbsp;</td>
            <td style="border: none !important;">&nbsp;</td>
            <td style="border: none !important;">&nbsp;</td>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border: none !important;"></th>
                @endif
            @endforeach
        </tr>
        <tr style="border: none !important;">
            <td  style="border: none !important;">Eligible for Admission to:</td>
            @foreach($gradelevels as $key => $eachlevel)
            <td colspan="5" style="border: none; border-bottom: 1px solid black; text-align: center;">{{$eachlevel->levelname}} </td>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border: none !important;"></th>
                @endif
            @endforeach
        </tr>
        <tr style="border: none !important;">
            <td  style="border: none !important;"></td>
            @foreach($gradelevels as $key => $eachlevel)
            <td colspan="5" style="border: none; border-bottom: 1px solid black; text-align: center;">{{$eachlevel->teachername}}</td>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border: none !important;"></th>
                @endif
            @endforeach
        </tr>
        <tr style="border: none !important;">
            <td  style="border: none !important;"></td>
            @foreach($gradelevels as $key => $eachlevel)
            <td colspan="5" style="border: none; text-align: center;">Adviser</td>
                @if($key != collect($gradelevels)->reverse()->keys()->first())
                <th style="width: 2%; border: none !important;"></th>
                @endif
            @endforeach
        </tr>
    </table>
    <br/>
    <table style="width: 70%; font-size: 10px;" border="1">
        <thead>
            <tr>
                <th>ATTENDANCE RECORD</th>
                <th>No. of School Days</th>
                <th>No. of School Days Absent</th>
                <th>No. of Times Tardy</th>
                <th>CAUSES</th>
            </tr>
        </thead>
        <tr>
            <td style="text-align: center;">LEVEL</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($gradelevels as $gradelevel)
            <tr>
                <td style="text-align: center;">{{$gradelevel->levelname}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    </table>
    <br/>
    <div style="border: 2px solid black; width: 100%; background-color: #8dcf5f; text-align: center; font-weight: bold;">
        CERTIFICATE OF TRANSFER
    </div>
    <br/>
    <table style="width: 100%; font-size: 12px;">
        <tr>
            <td >TO WHO IT MAY CONCERN:</td>
        </tr>
        <tr>
            <td >&nbsp;</td>
        </tr>
        <tr>
            <td >
                <div style="width: 30%; display: inline-block; line-height: 5px;" >This is to certify that this is a true reords of</div>
                &nbsp;
                <div style="width: 40%; border-bottom: 1px solid black; display: inline;" >{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename}} {{$studinfo->suffix}}</div>
                &nbsp;
                <div style="width: 20%; display: inline;" >He/She is eligible for admission to Grade</div>
                <div style="width: 10%; display: inline; border-bottom: 1px solid black;" >
                    
                </div>
                <div style="width: 100%;"> and has no property responsibility in this school.</div>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    <br/>
    <table style="width: 80%; border: 1px solid black; font-size: 12px;">
        <tr>
            <th colspan="2" style="text-align: left;">&nbsp;EVALUATION CODE:</th>
        </tr>
        <tr>
            <td>&nbsp;A - HIGHLY ADVANCED DEVELOPMENT</td>
            <td>&nbsp;C - AVERAGE DEVELOPMENT</td>
        </tr>
        <tr>
            <td>&nbsp;B - SLIGHTLY ADVANCED DEVELOPMENT</td>
            <td>&nbsp;D - SLIGHT DELAY IN DEVELOPMENT</td>
        </tr>
    </table>
{{-- <table style="width: 100%; font-size: 11px; border: 1px solid black;">
    <tr>
        <td colspan="10" style="border: 1px solid black; border-bottom: hidden; background-color: #d6d08b; font-weight: bold;" class="text-center">
            CERTIFICATION
        </td>
    </tr>
    <tr>
        <td style="width: 3%;"></td>
        <td colspan="8" style="text-align: justify;">
            I CERTIFY that this is a true record of <u>{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename[0]}}.</u> with LRN <u>{{$studinfo->lrn}}</u> and that he/she is eligible for admission to Grade <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>.
        </td>
        <td style="width: 3%;"></td>
    </tr>
    <tr>
        <td style="width: 3%;"></td>
        <td style="width: 10%;">School Name:</td>
        <td colspan="3" style="width: 45%; border-bottom: 1px solid black;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
        <td style="width: 7%;">School ID:</td>
        <td style="width: 10%; border-bottom: 1px solid black;">{{DB::table('schoolinfo')->first()->schoolid}}</td>
        <td style="width: 6%;">Division:</td>
        <td style="width: 14%; border-bottom: 1px solid black;">{{DB::table('schoolinfo')->first()->division}}</td>
        <td style="width: 3%;"></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td style="border-bottom: 1px solid black; text-align: center;">{{date('M d, Y')}}</td>
        <td>&nbsp;</td>
        <td colspan="3" style="border-bottom: 1px solid black; text-align: center;">{{strtoupper(DB::table('schoolinfo')->first()->authorized)}}</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td class="text-center">Date</td>
        <td>&nbsp;</td>
        <td colspan="3" class="text-center" style="font-size: 10px;">Name of Principal/School Head over Printed Name</td>
        <td colspan="2"style="text-align: right;">(Affix School Seal here)</td>
        <td></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
<span style="font-size: 11px;">
    May add Certification Box if needed
</span>
<span style="font-size: 11px; float: right; text-align: right; font-style: italic;">
    SFRT Revised 2017
</span> --}}