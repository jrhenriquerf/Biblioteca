@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Authors</div>

                <div class="card-body">
                    <form class="form-inline" action="{{ route('author.search') }}" method="GET">
                        {{ csrf_field() }}                      
                        <div class="form-group">
                            <input type="text" class="form-control" name="inputSearch" placeholder="Search your author"> 
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-search"></i> Search</button>
                        </div>
                        <div class="ml-auto">
                            <a href="{{route('author.create')}}" class="btn btn-info"><i class="fa fa-plus"></i> Add</a>
                        </div>
                    </form>
                    <br>
                    <table class="table table-hover">
                        <thead class="thead-dark">
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Surname</th>
                            <th scope="col" class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            ?>
                            @foreach ($authors as $author)
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $author->name }}</td>
                                    <td>{{ $author->surname }}</td>
                                    <td width="300" class="text-center">
                                        <a href="{{ route('author.edit', $author->id) }}" class="btn btn-outline-secondary btn-sm" style="width:50px"><i class="fa fa-pencil"></i></a>
                                        <form action="{{ route('author.destroy', $author->id) }}" method="POST" style="display: inline-block">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-outline-danger btn-sm" style="width:50px"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                                ?>
                            @endforeach
                        </tbody>
                    </table>               
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
