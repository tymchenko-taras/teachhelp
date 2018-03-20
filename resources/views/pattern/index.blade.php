@extends('layout')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Laravel 5.5 CRUD Example from scratch</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('pattern.create') }}"> Create New Article</a>
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Body</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($items as $item)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $item->name}}</td>
                <td>{{ $item->value}}</td>
                <td>
                    <a class="btn btn-info" href="{{ route('pattern.show',$item->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('pattern.edit',$item->id) }}">Edit</a>


                    {!! Form::open(['method' => 'DELETE','route' => ['pattern.destroy', $item->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </table>


    {!! $items->links() !!}
@endsection