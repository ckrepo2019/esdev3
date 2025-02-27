
<style>
    * { font-family: Arial, Helvetica, sans-serif; }
    .header{
        width: 100%;
        table-layout: fixed;
        /* border: 1px solid black; */
    }
    .header td {
        /* border: 1px solid black; */
    }
    .student{
        width: 75%;
        table-layout: fixed;
        font-size: 12px;
        border: 1px solid black;
        border-collapse: collapse;
    }
    .student td, .student th{
        /* border: 1px solid black; */
        padding: 2px;
    }
    .information{
        width: 75%;
        table-layout: fixed;
        
        float: right !important;
        font-size: 11px;
        
        /* border: 1px solid black;
        border-collapse: collapse; */
    }
    .information td, .information th{
        /* border: 1px solid black; */
        padding: 2px;
    }
    .informationtd { border-bottom: 1px solid black;}
    .clear:after {
        clear: both;
        content: "";
        display: table;
        border: 1px solid black;
    }
</style>
<table class="header">
    <tr>
        <td width="15%" rowspan="2"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px"></td>
        <td>
            <strong>{{$schoolinfo->schoolname}}</strong>
            <br>
            {{-- <small style="font-size: 12px;">{{$schoolinfo->address}} | {{$schoolinfo->division}} | {{$schoolinfo->region}}</small> --}}
            <small style="font-size: 12px;">{{$schoolinfo->address}} </small>
        </td>
        <td style="text-align:right;"><strong>STUDENT INFORMATION</strong></td>
    </tr>
    {{-- <tr>
        <td>
            <span style="font-size: 11px;">{{$schoolinfo[0]->address}}</span>
            <br>
            <span style="font-size: 11px;">{{$schoolinfo[0]->division}}</span>
        </td>
        <td style="text-align:right;vertical-align:bottom">
            <span style="font-size: 11px;">
                
            </span>
        </td>
    </tr> --}}
</table>
<br>
<div style="width: 25%; float: left;">
    <div style="font-size: 10px;border:1px solid black">
        @if($studinfo->picurl == null)
            @php
            
                if(strtoupper($studinfo->gender) == 'FEMALE'){
                    $avatar = 'avatar/S(F) 1.png';
                }
                else{
                    $avatar = 'avatar/S(M) 1.png';
                }
            @endphp
            <img src="{{base_path()}}/public/{{$avatar}}" alt="student" width="180px">
        @elseif (file_exists(base_path().'/public/'.$studinfo->picurl))
            <img src="{{base_path()}}/public/{{$studinfo->picurl}}" width="180px">
        @else
            @php
            
                if(strtoupper($studinfo->gender) == 'FEMALE'){
                    $avatar = '/avatar/S(F) 1.png';
                }
                else{
                    $avatar = '/avatar/S(M) 1.png';
                }
            @endphp
            <img src="/{{$avatar}}" alt="student" width="180px">
        @endif
        {{-- <img src="{{base_path()}}/public/{{$studentinfo[0]->picurl}}" alt="student" width="100%" > --}}
        {{-- <img src="{{base_path()}}/public/avatars/S(F) 1.png" alt="school" width="100%"> --}}
        {{-- <br>{{base_path()}}/public/{{$studentinfo[0]->picurl}} --}}
        <em >(This is just a temporary photo)</em>
        {{-- <br>
        <strong><center></center></strong> --}}
        {{-- <br>
        <em>Section: <strong>{{$studentinfo[0]->sectionname}}</strong></em> --}}
        {{-- <br>
        <em>Adviser: </em> --}}
    </div>
    <br>
    <em style="font-size: 11px;">Current Grade Level: <strong>{{$studinfo->levelname}}</strong></em>
</div>
<table class="information">
    <tbody>
        <tr>
            <td style="width: 25%;">
                LRN
            </td>
            <td class="informationtd">
                {{$studinfo->lrn}}
            </td>
        </tr>
        <tr>
            <td>
                LASTNAME
            </td>
            <td class="informationtd">
                {{$studinfo->lastname}}
            </td>
        </tr>
        <tr>
            <td>
                FIRSTNAME
            </td>
            <td class="informationtd">
                {{$studinfo->firstname}}
            </td>
        </tr>
        <tr>
            <td>
                MIDDLENAME
            </td>
            <td class="informationtd">
                {{$studinfo->middlename}}
            </td>
        </tr>
        <tr>
            <td>
                SUFFIX
            </td>
            <td class="informationtd">
                {{$studinfo->suffix}}
            </td>
        </tr>
        <tr>
            <td>
                DATE OF BIRTH
            </td>
            <td class="informationtd">
                {{$studinfo->dob}}
            </td>
        </tr>
        <tr>
            <td>
                GENDER
            </td>
            <td class="informationtd">
                {{$studinfo->gender}}
            </td>
        </tr>
        <tr>
            <td>
                CONTACT #
            </td>
            <td class="informationtd">
                {{$studinfo->contactno}}
            </td>
        </tr>
        <tr>
            <td>
                HOME ADDRESS
            </td>
            <td class="informationtd">
                {{$studinfo->street.', '}}
                {{$studinfo->barangay.', '}}
                {{$studinfo->city.', '}}
                {{$studinfo->province}}
            </td>
        </tr>
        <tr>
            <td>
                BLOOD TYPE
            </td>
            <td class="informationtd">
                {{$studinfo->bloodtype}}
            </td>
        </tr>
        <tr>
            <td>
                ALLERGIES
            </td>
            <td class="informationtd">
                {{$studinfo->allergy}}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr style="border: 1px solid #ddd"/>
            </td>
        </tr>
        <tr>
            <td>
                MOTHER'S NAME
            </td>
            <td class="informationtd">
                {{$studinfo->mothername}}
            </td>
        </tr>
        <tr>
            <td>
                CONTACT #
            </td>
            <td class="informationtd">
                {{$studinfo->mcontactno}}
            </td>
        </tr>
        <tr>
            <td>
                OCCUPATION
            </td>
            <td class="informationtd">
                {{$studinfo->moccupation}}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr style="border: 1px solid #ddd"/>
            </td>
        </tr>
        <tr>
            <td>
                FATHER'S NAME
            </td>
            <td class="informationtd">
                {{$studinfo->fathername}}
            </td>
        </tr>
        <tr>
            <td>
                CONTACT #
            </td>
            <td class="informationtd">
                {{$studinfo->fcontactno}}
            </td>
        </tr>
        <tr>
            <td>
                OCCUPATION
            </td>
            <td class="informationtd">
                {{$studinfo->foccupation}}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr style="border: 1px solid #ddd"/>
            </td>
        </tr>
        <tr>
            <td>
                GUARDIAN'S NAME
            </td>
            <td class="informationtd">
                {{$studinfo->guardianname}}
            </td>
        </tr>
        <tr>
            <td>
                CONTACT #
            </td>
            <td class="informationtd">
                {{$studinfo->gcontactno}}
            </td>
        </tr>
        <tr>
            <td>
                RELATION
            </td>
            <td class="informationtd">
                {{$studinfo->guardianrelation}}
            </td>
        </tr>
    </tbody>
</table>
            
