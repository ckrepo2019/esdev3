<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

<table class="table border-bottom" style="min-width:300px; table-layout:fixed;">
    <thead class="bg-warning">
        <tr>
            <th width="30%">Title</th>
            <th width="50%">Content</th>
            <th width="20%" id="appadd">Date Posted</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data[0]->data as $item)
            <tr>
                <td class="appadd"><a href="principalReadAnnouncement/{{Crypt::encrypt($item->id)}}">{{$item->title}}</a></td>
                <td class="appadd content-td" >
                </td>
                <td class="appadd">{{\Carbon\Carbon::create($item->created_at)->isoFormat('MMM DD, YYYY')}}</td>
            </tr>
        @endforeach
    </tbody>
</table>

