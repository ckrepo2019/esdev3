@extends('adminPortal.layouts.app2')


@section('content')
@php
    $path = pathinfo($cidetails[0]->picurl);
    $file = $path['basename'];
    $x = substr($file, 0, strrpos($file, '.')); 
@endphp

<section class="content-header">
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item"><a href="/adminviewadvertisements">Advertisements</a></li>
            <li class="breadcrumb-item active">{{$x}}</li>
        </ol>
        </div>
    </div>
    </div>
</section>
  
<section class="content-header">
        <div class="container-fluid">
        <div class="row">
        <div class="col-lg-9">
        <div class="col-md-12">
                <div class="card">
                @foreach($cidetails as $item)
                    <div class="card-header bg-info">
                        <h3 class="card-title">
                            <i class="fas fa-edit" style="color:#ffc107"></i>
                           
                            <span style="text-transform: capitalize">{{$x}}</span>
                        </h3>
                    </div>
                    <table class="table table-hover">
                    <tbody>
                        <tr>
                            <td><img style="width: 100%; height: auto" src="{{ asset($item->picurl) }}" alt=""></td>
                        </tr>
                    </tbody>
                    </table>
                @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-3">
        <div class="col-md-12">
            <div class="card" >
                    <div class="card-header bg-info">
                        <span  style="font-size: 15px" class="card-title">
                            <i class="fas fa-question" style="color:#ffc107;"></i>
                            ADVERTISEMENT INFO
                        </span>
                    </div>
                    <div class="card-body">
                        @foreach($cidetails as $item)
                            @if($item->isactive == 1)
                                <a class="btn btn-sm btn-success btn-block" href="/setimageisactive/{{$item->id}}/0" >Active</a>
                            @else
                                <a class="btn btn-sm btn-danger btn-block" href="/setimageisactive/{{$item->id}}/1" >SET AS ACTIVE</a> 
                            @endif
                            {{-- <button type="button" class="btn btn-sm btn-outline-primary btn-block" data-toggle="modal" data-target="#modal-primary"><i class="far fa-edit"></i> EDIT</button> --}}
                               
                            <a href="/admin/remove/image/{{$cidetails[0]->id}}" type="button" class="btn btn-sm btn-outline-danger btn-block" ><i class="fa fa-trash"></i> DELETE</a>
                        @endforeach
                    
                    </div>
                </div>
            </div>
        </div>
        
            
        </div>
        </div>
</section>
@endsection