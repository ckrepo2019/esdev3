
<style>
    .clear:after {
        clear: both;
        content: "";
        display: table;
        border: 1px solid black;
    }
</style>
<h4>Subject: {{$message[0]->title}}</h4>
<h5>Date: {{$message[0]->created_at}}</h5>
<h4>From: {{$message[0]->lastname}}, {{$message[0]->firstname}} {{$message[0]->middlename[0]}}. {{$message[0]->suffix}}</h4>
<h4>Content: </h4>
{{$message[0]->content}}
<div style="clear: both;"></div>
