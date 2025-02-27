{{-- <style>
    @page{
        margin: 20px;;
    }
</style> --}}
<div style="width: 100%;"></div>
<table style="font-size: 11px; font-weight: bold;margin-right: 1000px;" border="1">
    <tr>
        <td width="60%">{{$levelname}} - {{$sectionname}}</td>
        <td style="text-align: right;">
            AS OF : 
            @if(count($dates) == 1)
                {{strtoupper(date('M d, Y', strtotime($dates[0])))}}
            @else
                {{strtoupper(date('M d, Y', strtotime(collect($dates)->first())))}} - {{strtoupper(date('M d, Y', strtotime(collect($dates)->last())))}}
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="2">{{$subjectcode}} - {{$subjectname}}</td>
    </tr>
</table>
<table style="width: 100%; font-size: 11px; font-weight: bold;margin-right: 1000px;" border="1">
    <tr>
        <td width="60%">{{$levelname}} - {{$sectionname}}</td>
        <td style="text-align: right;">
            AS OF : 
            @if(count($dates) == 1)
                {{strtoupper(date('M d, Y', strtotime($dates[0])))}}
            @else
                {{strtoupper(date('M d, Y', strtotime(collect($dates)->first())))}} - {{strtoupper(date('M d, Y', strtotime(collect($dates)->last())))}}
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="2">{{$subjectcode}} - {{$subjectname}}</td>
    </tr>
</table>