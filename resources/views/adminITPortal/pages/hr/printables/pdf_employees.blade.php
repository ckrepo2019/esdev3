<style>
    *{
        
        font-family: Arial, Helvetica, sans-serif;
    }
    table td{
        padding: 2px;
    }
</style>
<table style="width: 100%;">
    <tr>
        <th style="text-align: center;">
            {{DB::table('schoolinfo')->first()->schoolname}}
        </th>
    </tr>
    <tr>
        <td style="text-align: center; font-size: 11px;">
            {{DB::table('schoolinfo')->first()->address}}
        </td>
    </tr>
</table>
<div style="width: 100%; font-size: 12px;">
    Male : {{collect($employees)->where('isactive','1')->where('gender','MALE')->count()}}<br/>
    Female : {{collect($employees)->where('isactive','1')->where('gender','FEMALE')->count()}}<br/>
    Unspecified : {{collect($employees)->where('isactive','1')->where('gender',null)->count()}}<br/>
    Total : {{collect($employees)->where('isactive','1')->count()}}
</div>
<br/>
<table style="width: 100%; font-size: 12px; table-layout: fixed; border-collapse: collapse;" border="1">
    <thead>
        <tr>
            <th style="width: 5%;">#</th>	
            <th style="width: 30%;">Name</th>
            <th style="width: 15%;">Main Portal</th>	
            <th>Date Hired</th>	
            <th>Worked for</th>	
            <th style="width: 12%;">Employment<br/>Status</th>	
            {{-- <th style="width: 10%;">Status</th>	 --}}
        </tr>
    </thead>
    @foreach(collect($employees)->where('isactive','1')->values() as $key => $employee)
        <tr @if($employee->isactive == '0') style="background-color: #ffad99;" @endif>
            <td style="text-align: center;">{{$key+1}}</td>
            <td style="padding-left: 3px;"><strong>{{ucwords(strtolower($employee->lastname))}}</strong>, {{ucwords(strtolower($employee->firstname))}} </td>
            <td style="text-align: center;">{{ucwords(strtolower($employee->designation))}}</td>
            <td style="text-align: center;">
              @if($employee->datehired == null)
              
              @else
                {{date('M d, Y', strtotime($employee->datehired))}}
              @endif
            </td>
            <td style="text-align: center;">{{$employee->worked}}</td>
            <td style="text-align: center;">
                @if($employee->employmentstatus == '1')
                  <span class="right badge" style="background-color: #ffff66;">Casual</span>
                @elseif($employee->employmentstatus == '2')
                  <span class="right badge" style="background-color: #b3ecff;">Provisionary</span>
                @elseif($employee->employmentstatus == '3')
                  <span class="right badge " style="background-color: #99ff99;">Regular</span>
                @elseif($employee->employmentstatus == '4')
                  <span class="right badge " style="background-color: #d9b3ff;">Part-time</span>
                @elseif($employee->employmentstatus == '5')
                  <span class="right badge " style="background-color: #ffc299;">Substitute</span>
                @else
                  <span class="right badge badge-danger">Not set</span>
                @endif
            </td>
            {{-- <td style="text-align: center;">
              @if($employee->isactive == '0')
                <span class="right badge badge-danger">Inactive</span>
              @else
                <span class="right badge badge-success">Active</span>
              @endif
            </td> --}}
        </tr>
    @endforeach
</table>
<br/>
<table style="width: 100%; border-collapse: collapse; font-size: 12px;" border="1">
    <thead>
        <tr style="background-color: #ddd;">
            <th colspan="10">Employment Status</th>
        </tr>
        <tr>
            <th colspan="2">Casual</th>
            <th colspan="2">Provisionary</th>
            <th colspan="2">Regular</th>
            <th colspan="2">Part-time</th>
            <th colspan="2">Substitute</th>
        </tr>
    </thead>
    <tr>
        <td>Male</td>
        <td style="text-align: right;">{{collect($employees)->where('isactive','1')->where('employmentstatus',1)->where('gender','MALE')->count()}}</td>
        <td>Male</td>
        <td style="text-align: right;">{{collect($employees)->where('isactive','1')->where('employmentstatus',2)->where('gender','MALE')->count()}}</td>
        <td>Male</td>
        <td style="text-align: right;">{{collect($employees)->where('isactive','1')->where('employmentstatus',3)->where('gender','MALE')->count()}}</td>
        <td>Male</td>
        <td style="text-align: right;">{{collect($employees)->where('isactive','1')->where('employmentstatus',4)->where('gender','MALE')->count()}}</td>
        <td>Male</td>
        <td style="text-align: right;">{{collect($employees)->where('isactive','1')->where('employmentstatus',5)->where('gender','MALE')->count()}}</td>
    </tr>
    <tr>
        <td>Female</td>
        <td style="text-align: right;">{{collect($employees)->where('isactive','1')->where('employmentstatus',1)->where('gender','FEMALE')->count()}}</td>
        <td>Female</td>
        <td style="text-align: right;">{{collect($employees)->where('isactive','1')->where('employmentstatus',2)->where('gender','FEMALE')->count()}}</td>
        <td>Female</td>
        <td style="text-align: right;">{{collect($employees)->where('isactive','1')->where('employmentstatus',3)->where('gender','FEMALE')->count()}}</td>
        <td>Female</td>
        <td style="text-align: right;">{{collect($employees)->where('isactive','1')->where('employmentstatus',4)->where('gender','FEMALE')->count()}}</td>
        <td>Female</td>
        <td style="text-align: right;">{{collect($employees)->where('isactive','1')->where('employmentstatus',5)->where('gender','FEMALE')->count()}}</td>
    </tr>
    <tr>
        <td>Unspecified</td>
        <td style="text-align: right;">{{collect($employees)->where('isactive','1')->where('employmentstatus',1)->where('gender',null)->count()}}</td>
        <td>Unspecified</td>
        <td style="text-align: right;">{{collect($employees)->where('isactive','1')->where('employmentstatus',2)->where('gender',null)->count()}}</td>
        <td>Unspecified</td>
        <td style="text-align: right;">{{collect($employees)->where('isactive','1')->where('employmentstatus',3)->where('gender',null)->count()}}</td>
        <td>Unspecified</td>
        <td style="text-align: right;">{{collect($employees)->where('isactive','1')->where('employmentstatus',4)->where('gender',null)->count()}}</td>
        <td>Unspecified</td>
        <td style="text-align: right;">{{collect($employees)->where('isactive','1')->where('employmentstatus',5)->where('gender',null)->count()}}</td>
    </tr>
    {{-- <tr>
        <td>Active</td>
        <td style="text-align: right;">{{collect($employees)->where('employmentstatus',1)->where('isactive',1)->count()}}</td>
        <td>Active</td>
        <td style="text-align: right;">{{collect($employees)->where('employmentstatus',2)->where('isactive',1)->count()}}</td>
        <td>Active</td>
        <td style="text-align: right;">{{collect($employees)->where('employmentstatus',3)->where('isactive',1)->count()}}</td>
        <td>Active</td>
        <td style="text-align: right;">{{collect($employees)->where('employmentstatus',4)->where('isactive',1)->count()}}</td>
        <td>Active</td>
        <td style="text-align: right;">{{collect($employees)->where('employmentstatus',5)->where('isactive',1)->count()}}</td>
    </tr>
    <tr>
        <td>Inactive</td>
        <td style="text-align: right;">{{collect($employees)->where('employmentstatus',1)->where('isactive',0)->count()}}</td>
        <td>Inactive</td>
        <td style="text-align: right;">{{collect($employees)->where('employmentstatus',2)->where('isactive',0)->count()}}</td>
        <td>Inactive</td>
        <td style="text-align: right;">{{collect($employees)->where('employmentstatus',3)->where('isactive',0)->count()}}</td>
        <td>Inactive</td>
        <td style="text-align: right;">{{collect($employees)->where('employmentstatus',4)->where('isactive',0)->count()}}</td>
        <td>Inactive</td>
        <td style="text-align: right;">{{collect($employees)->where('employmentstatus',5)->where('isactive',0)->count()}}</td>
    </tr> --}}
</table>