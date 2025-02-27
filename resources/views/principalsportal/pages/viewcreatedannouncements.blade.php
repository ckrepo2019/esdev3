@extends('principalsportal.layouts.app2')

@section('pagespecificscripts')
    <style>

        #appadd {
            white-space: nowrap;
            overflow: hidden;
            width: 10px;
            height: 10px;
            text-overflow: ellipsis; 
        }
        td{
            padding: .4em !important;
        }
        th{
            padding: .4em !important;
        }

    </style>

@endsection


@section('content')
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>Title1</td>
                            <td>Content1</td>
                            <td width="15%">Date Posted</td>
                        </tr>
                        @foreach($announcements as $item)
                            
                            <tr>
                                <td>{{$item->title}}</td>
                                <td id="appadd">{{$item->content}} sdfsdfsdfdf</td>
                                <td>{{\Carbon\Carbon::create($item->created_at)->isoFormat('MMM DD, YYYY')}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
   
</section>
        
@endsection




