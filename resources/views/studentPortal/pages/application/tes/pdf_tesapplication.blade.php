<style>
    
    * { 
        font-family: Arial, Helvetica, sans-serif;
     }
    table{
        border-collapse: collapse;
    }
    table td{
        padding: 2px;
    }
</style>
<div style="width: 100%; text-align: center; font-weight: bold; font-size: 13px;">TERTIARY EDUCATION SUBSIDY (TES)</div>
<table style="width: 100%; font-size: 11px !important;" border="1">
    <tr>
        <td style="width: 20%;"></td>
        <td style="width: 30%; font-weight: bold;">LEARNER'S REFERENCE NO.</td>
        <td style="width: 50%;">&nbsp;{{$studinfo->lrn}}</td>
    </tr>
    <tr>
        <td style="width: 20%;"></td>
        <td style="width: 30%; font-weight: bold;">STUDENT ID</td>
        <td style="width: 50%;">&nbsp;{{$studinfo->sid}}</td>
    </tr>
    <tr>
        <td rowspan="3" style="text-align: center; font-weight: bold;">
            Student's Name
        </td>
        <td style="font-weight: bold;">LAST NAME</td>
        <td>&nbsp;{{$studinfo->lastname}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">GIVEN NAME</td>
        <td>&nbsp;{{$studinfo->firstname}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">MIDDLE NAME</td>
        <td>&nbsp;{{$studinfo->middlename}}</td>
    </tr>
    <tr>
        <td rowspan="4" style="text-align: center; font-weight: bold;">
            Student's Data
        </td>
        <td style="font-weight: bold;">SEX</td>
        <td>&nbsp;{{$studinfo->gender}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">BIRTHDATE</td>
        <td>&nbsp;{{date('F d, Y', strtotime($studinfo->dob))}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">COMPLETE PROGRAM NAME</td>
        <td>&nbsp;{{$tesinfo->coursename}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">YEAR LEVEL</td>
        <td>&nbsp;{{$tesinfo->levelname}}</td>
    </tr>
    <tr>
        <td rowspan="3" style="text-align: center; font-weight: bold;">
            Father's Name
        </td>
        <td style="font-weight: bold;">LAST NAME</td>
        <td>&nbsp;{{$studinfo->fatherlastname}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">GIVEN NAME</td>
        <td>&nbsp;{{$studinfo->fatherfirstname}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">MIDDLE NAME</td>
        <td></td>
    </tr>
    <tr>
        <td rowspan="3" style="text-align: center; font-weight: bold;">
            Kompletong Pangalan sa Mama sa Dalaga pa siya
        </td>
        <td style="font-weight: bold;">LAST NAME</td>
        <td>&nbsp;{{$tesinfo->mmlastname}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">GIVEN NAME</td>
        <td>&nbsp;{{$tesinfo->mmfirstname}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">MIDDLE NAME</td>
        <td>&nbsp;{{$tesinfo->mmmiddlename}}</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-weight: bold;">DSWD HOUSEHOLD NO.</td>
        <td>&nbsp;{{$tesinfo->dswdhno}}</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-weight: bold;">HOUSEHOLD PER CAPITA INCOME</td>
        <td>&nbsp;{{$tesinfo->hpcincome}}</td>
    </tr>
    <tr>
        <td rowspan="4" style="text-align: center; font-weight: bold;">
            Permanent Address
        </td>
        <td style="font-weight: bold;">STREET & BARANGAY</td>
        <td>&nbsp;{{$tesinfo->street}}, &nbsp;{{$tesinfo->barangay}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">TOWN/CITY/MUNICIPALITY</td>
        <td>&nbsp;{{$tesinfo->city}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">PROVINCE</td>
        <td>&nbsp;{{$tesinfo->province}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">ZIP CODE</td>
        <td>&nbsp;{{$tesinfo->zipcode}}</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-weight: bold;">CONTACT NUMBER</td>
        <td>&nbsp;{{$tesinfo->contactno}}</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-weight: bold;">EMAIL ADDRESS</td>
        <td>&nbsp;{{$tesinfo->emailaddress}}</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-weight: bold;">DISABILITY</td>
        <td>&nbsp;{{$tesinfo->disability}}</td>
    </tr>
</table>