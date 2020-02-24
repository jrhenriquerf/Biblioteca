@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('author.index') }}">Authors</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add new author</li>
                    </ol>
                </nav>

                <div class="card-body">
                    <form action="{{ route('author.store') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" aria-describedby="title">
                        </div>
                        <div class="form-group">
                            <label for="surname">Surname</label>
                            <input type="text" class="form-control" name="surname" id="surname" aria-describedby="title">
                        </div>
                        <br />
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>     
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
