
<style>
    html{
        font-family: Arial, Helvetica, sans-serif;
    }
    table{
        border-collapse: collapse;
        width: 100%;
    }
    @page{
        margin: 20px;
    }
    td{
        padding-left: 2px;
        padding-right: 2px;
    }
</style>
<table style="font-size: 9px; text-align: center;">
    <tr>
        <th>COMMISSION ON HIGHER EDUCATION</th>
    </tr>
    <tr>
        <th>Higher Education Regional Office 10</th>
    </tr>
    <tr>
        <th>Archbishop Hayes St., Cagayan de Oro City</th>
    </tr>
    <tr>
        <th>&nbsp;</th>
    </tr>
    <tr>
        <th>REPLACEMENT BILLING STATEMENT OF UNPAID TD02 GRANTEES</th>
    </tr>
    <tr>
        <th>{{$semester->semester}}, SY {{$sydesc->sydesc}}</th>
    </tr>
    <tr>
        <th>&nbsp;</th>
    </tr>
</table>
<table style="width: 100%; font-size: 8px;">
    <tr>
        <td colspan="2" style="width: 9%;">SCHOOL</td>
        <td colspan="5" style="width: 40%; border-bottom: 1px solid black;"></td>
        <td colspan="8"></td>
    </tr>
    <tr>
        <td colspan="2">ADRESS</td>
        <td colspan="5" style="border-bottom: 1px solid black;"></td>
        <td colspan="8"></td>
    </tr>
    <tr>
        <td colspan="2">REGION</td>
        <td colspan="5" style="border-bottom: 1px solid black;"></td>
        <td colspan="8"></td>
    </tr>
    <tr>
        <td colspan="2">DATE</td>
        <td colspan="5" style="border-bottom: 1px solid black;"></td>
        <td colspan="8"></td>
    </tr>
    <tr>
        <td colspan="15">&nbsp;</td>
    </tr>
</table>
<table style="width: 100%; font-size: 8px;" border="1">
    <tr>
        <th rowspan="2" style="width: 3%;">SEQ. NO.</th>
        <th rowspan="2" style="width: 6%;">AWARD NO. PER NOA</th>
        <th rowspan="2" style="width: 7%;">TYPE OF StuFAP</td>
        <th colspan="3" style="width: 29%;">NAME</td>
        <th rowspan="2" style="width: 4%;">SEX<br/>(M/F)</th>
        <th rowspan="2" style="width: 17%;">COURSE</th>
        <th rowspan="2" style="width: 3%;">CURRICULUM YEAR/LEVEL</th>
        <th rowspan="2" style="width: 3%;">GENERAL WEGHTED AVERAGE (FROM THE PREVIOUS SEMESTER)</th>
        <th rowspan="2" style="width: 3%;">REMARKS (P/F)</th>
        <th rowspan="2" style="width: 3%;">NUMBER OF UNITS ENROLLED</th>
        <th rowspan="2" style="width: 6%;">ACTUAL TUITION AND OTHER SCHOOL FEES</th>
        <th rowspan="2" style="width: 6%;">CHED StuFAP's FINANCIAL BENEFITS PER SEMESTER</th>
        <th rowspan="2" style="width: 10%;">LANDBANK ACCOUNT NO.</th>
    </tr>
    <tr>
        <th>LAST</th>
        <th>FIRST</td>
        <th>MIDDLE</td>
    </tr>
    @if(count($students) == 0)
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
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    @else
        @foreach($students as $key => $student)
            <tr>
                <td style="text-align: center;">{{$key+1}}</td>
                <td style="text-align: left;">{{$student->tesno}}</td>
                <td style="text-align: center;">TULONG DUNONG 02</td>
                <td>{{$student->lastname}}</td>
                <td>{{$student->firstname}}</td>
                <td>{{$student->middlename}}</td>
                <td style="text-align: center;">{{$student->gender}}</td>
                <td>{{$student->coursename}}</td>
                <td style="text-align: center;">{{$student->yearid}}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="text-align: center;">{{$student->units}}</td>
                <td style="text-align: right;">{{number_format($student->overallfees,2,'.',',')}}</td>
                <td style="text-align: right;">@if($setup) {{number_format($setup->billedamount,2,'.',',')}} @endif</td>
                <td style="text-align: center;">@if($setup) {{$setup->bankacctno}} @endif</td>
            </tr>
        @endforeach
    @endif
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
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <th colspan="3">TOTAL BILLING FOR : {{$semester->semester}}, SY {{$sydesc->sydesc}}</th>
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
        <td style="text-align: right;"><span style="color: red;">@if($setup){{number_format(collect($students)->count()*$setup->billedamount,2,'.',',')}} @endif</span></td>
        <td>&nbsp;</td>
    </tr>
</table>
<br/>
<table style="width: 100%; font-size: 8px;">
    <tr>
        <td style="width: 5%;" rowspan="7"></td>
        <td style="width: 15%;">Prepared/Correct:</td>
        <td style="width: 23%;" rowspan="7"></td>
        <td style="width: 14%;">Certified Correct:</td>
        <td style="width: 23%;" rowspan="7"></td>
        <td style="width: 15%;">Approved:</td>
        <td style="width: 5%;" rowspan="7"></td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid black;"></td>
        <td style="border-bottom: 1px solid black;"></td>
        <td style="border-bottom: 1px solid black;"></td>
    </tr>
    <tr>
        <td style="text-align: center; font-weight: bold;">HEI StuFAP's Coordinator/Registrar</td>
        <td style="text-align: center; font-weight: bold;">HEI Finance Officer</td>
        <td style="text-align: center; font-weight: bold;">HEI President/Head/School Directress</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr style="font-size: 7px;">
        <td>NOTE:<br/>PREPARE FOUR (4) COPIES WITH SCHOOL SEAL</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>