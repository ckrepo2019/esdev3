
<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

<table class="table">
    <thead>
        <tr>
            <th></th>
            <th>Notification</th>
            <th>Notification</th>
        </tr>
    </thead>
    @php

        $gradeLogs = '#';
        $annoucement = '#';
        $grades = '#';
        $viewAll = '#';

        if(auth()->user()->type == '2'){
            $gradelogs = '/principalgradeannouncement/';
            $annoucement = 'principalReadAnnouncement/';
            $grades = null;
        }

        else if(auth()->user()->type == '7'){
            $gradeLogs = null;
            $annoucement = '/viewAnnouncement/';
            $grades = '/gradeannouncement/';
            $viewAll = '/viewAllAnnouncement/';
        }

        else if(auth()->user()->type == '9'){
            $gradeLogs = null;
            $annoucement = '/parentviewAnnouncement/';
            $grades =  '/parentsPortalGrades/';
        }

    @endphp
    <tbody id="notificationholder">
        @foreach($data[0]->data as $item)

            <tr>
                @if($item->status == '0')
                    <td class="bg-success"></td>
                @else
                    <td class="bg-danger"></td>
                @endif

                @if($item->type == '3')

                    <td class="align-middle">
                        <a href="{{$gradelogs}}{{Crypt::encrypt($item->gradeid)}}/{{Crypt::encrypt($item->headerid)}}">{{$item->gradeLogTeacherFirstName}} {{$item->gradeLogTeacherLastName}}</a> 
                    </td>
                    <td>
                        @if($item->acadprogid == 5)
                            submitted {{Str::limit($item->shgradelogsubject, $limit = 30, $end = '...')}}  Quarter {{$item->quarter}} Grades for {{$item->levelname}} - {{$item->sectionname}} 
                        @else
                            submitted {{Str::limit($item->gradelogsubject, $limit = 30, $end = '...')}}  Quarter {{$item->quarter}} Grades for {{$item->levelname}} - {{$item->sectionname}} 
                        @endif
                    </td>
                        
                @elseif($item->type=='1')

                    <td><a href="{{$annoucement}}{{Crypt::encrypt($item->headerid)}}">{{$item->announcementTeacherFirstname}} {{$item->announcementTeacherLastName}}</a></td>
                    <td> posted an announcement "{{$item->announcementtitle}}"</td>

                @elseif($item->type == '2')

                    <td><a href="{{$grades}}/{{$item->headerid}}">{{Str::limit($item->studsubjdesc, $limit = 30, $end = '...')}}</a> Quarter {{$item->gradequarter}} grades was posted</td>
                    <td></td>
                    
                @endif

                
            </tr>
        @endforeach
    </tbody>
</table>