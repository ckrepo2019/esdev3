<style>
    
    html{
        font-family: Arial, Helvetica, sans-serif;
    }
    table{
        border-collapse: collapse;
    }
    @page{
        margin: 50px 20px;
    }
</style>

<table style="width: 100%; font-size: 11px;" border="1">
    <tr>
        <th colspan="13"style="border-bottom: none !important;"></th>
        <th colspan="6" style="width: 25%; border: 1px solid black;">Billing Form 2</th>
    </tr>
    <tr>
        <th colspan="5" style="border-top: none !important; border-right: none !important; border-bottom: none !important;"></th>
        <th colspan="8" style="border: none !important;">
            <div style="font-size: 8px !important;">Republic of the Philippines</div>
            <div style="font-size: 8px !important; font-style: italic;"><u>{{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}}</u></div>
            <div style="font-size: 8px !important; font-style: italic;"><u>{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</u></div>
            <div style="margin-top: 5px;">CONSOLIDATED TES BILLING DETAILS</div>
        </th>
        <th colspan="6" style="border-top: none !important; border-left: none !important; border-bottom: none !important;"></th>
    </tr>
    <tr>
        <td colspan="13" rowspan="2" style="border-top: none !important; border-right: none !important;"></td>
        <td colspan="3" style="font-size: 8px !important; text-align: right; border: none !important;">
            TES Billing Details Reference Number:
        </td>
        <th colspan="3" style="font-size: 8px !important; text-align: left;border-left: none !important; border-top: none !important;">
            {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
            10-SAIT-2020-1-1
            @endif --}}
        </th>
    </tr>
    <tr>
        <td colspan="3" style="font-size: 8px !important; text-align: right; border: none !important;">
            Date:
        </td>
        <th colspan="3" style="font-size: 8px !important; text-align: left;border-left: none !important; border-top: none !important;">
            {{date('M d, Y')}}
        </th>
    </tr>
    <tr>
        <th style="width: 3%; font-size: 8px !important; text-align: left; border-right: none !important;">
            TO:
        </th>
        <th colspan="18" style="font-size: 8px !important; text-align: left; border-left: none !important;">
            CHED - Regional Office 10
        </th>
    </tr>
    <tr>
        <th style="width: 3%; font-size: 8px !important; text-align: left;">
            Address:
        </th>
        <th colspan="18" style="font-size: 8px !important; text-align: left;">
            Arch. hayes St., Brgy 40, Cagayan de Oro City
        </th>
    </tr>
    <tr>
        <th colspan="2" style="font-size: 8px !important; text-align: left; border-right: none !important;">
            Copy Furnished:
        </th>
        <th colspan="17" style="font-size: 8px !important; text-align: left; border-left: none !important;">
            UniFAST Secretarist
        </th>
    </tr>
    <tr style="font-size: 7px !important;">
        <th></th>
        <th></th>
        <th></th>
        <th colspan="3" style="width: 20%;">Student's Name</th>
        <th colspan="5">Student Profile</th>
        <th colspan="3">Contact Information</th>
        <th colspan="2">TES-1 (for private HEIs only)</th>
        <th>TES-2</th>
        <th>TES-3A</th>
        <th></th>
    </tr>
    <tr style="font-size: 7px !important; background-color: #c4f2c7;">
        <th>5-digit control Number</th>
        <th style="width: 5%;">Student Number</th>
        <th style="width: 8%;">TES Award number</th>
        <th>Last Name</th>
        <th>Given Name</th>
        <th>Middle Initial</th>
        <th style="width: 2%;">Sex at Birth (M/F)</th>
        <th style="width: 5%;">BIRTHDATE (mm/dd/yyyy)</th>
        <th style="width: 20%;">Degree Program</th>
        <th style="width: 2%;">Year Level</th>
        <th style="width: 2%;">Academic Units Enrolled (credit and non-credit courses)</th>
        <th style="width: 3%;">ZIP Code</th>
        <th style="width: 10%;">E-mail address</th>
        <th style="width: 6%;">Phone Number</th>
        <th style="width: 7%;">Actual Tuition and Other School Fees for 1st semester SY ({{$sydesc->sydesc}})</th>
        <th>Billed Amount</th>
        <th>Stipend</th>
        <th>Person with Disability</th>
        <th style="width: 8.5%;">TOTAL AMOUNT</th>
    </tr>
    @if(count($students) == 0)
    <tr style="font-size: 7px !important;">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    @else
        @foreach($students as $student)
            <tr style="font-size: 7px !important;">
                <td></td>
                <td style="text-align: center;">{{$student->sid}}</td>
                <td>{{$student->tesno}}</td>
                <td>{{$student->lastname}}</td>
                <td>{{$student->firstname}}</td>
                <td>@if($student->middlename != null){{$student->middlename}}@endif</td>
                <td style="text-align: center;">{{ucwords(strtolower($student->gender))}}</td>
                <td style="text-align: center;">{{date('M d, Y',strtotime($student->dob))}}</td>
                <td>{{ucwords(strtolower($student->coursename))}}</td>
                <td style="text-align: center;">{{DB::table('college_year')->where('levelid', $student->levelid)->first()->id}}</td>
                <td style="text-align: center;">{{$student->units}}</td>
                <td style="text-align: center;">{{$student->zipcode}}</td>
                <td style="text-align: center;">{{$student->emailaddress}}</td>
                <td style="text-align: center;">{{$student->contactno}}</td>
                <td style="text-align: center;">{{number_format($student->overallfees,2,'.',',')}}</td>
                <td style="text-align: center;">{{number_format($student->billedamount,2,'.',',')}}</td>
                <td style="text-align: center; font-style: italic;">{{number_format($student->stipend,2,'.',',')}}</td>
                <td style="text-align: center;">{{number_format($student->disabilityamount,2,'.',',')}}</td>
                <td style="text-align: center; font-style: italic;">{{number_format(($student->billedamount+$student->stipend+$student->disabilityamount),2,'.',',')}}</td>
            </tr>
        @endforeach
    @endif
</table>