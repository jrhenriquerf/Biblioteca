@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Books</div>

                <div class="card-body">
                    <form class="form-inline" action="{{ route('book.search') }}" method="GET">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="text" class="form-control" name="inputSearch" placeholder="Search your book"> 
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <select name="author[]" class="form-control selectpicker" data-size="5" multiple="" data-live-search="true" title="Authors">
                                    @if(!empty($authors))
                                        @foreach($authors as $author)
                                            <option value="{{ $author->id }}" {{ in_array($author->id, $selectedAuthor) ? "selected" : NULL }}>{{ $author->name . ($author->surname ? " ({$author->surname})" : '') }}</option>
                                        @endforeach
                                    @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-search"></i> Search</button>
                        <div class="ml-auto">
                            <a href="{{route('book.create')}}" class="btn btn-info"><i class="fa fa-plus"></i> Add</a>
                        </div>
                    </form>
                    <br>
                    <table class="table table-hover">
                        <thead class="thead-dark">
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Author</th>
                            <th scope="col" class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($books as $book)
                                <tr>
                                    <th scope="row">{{ $book->id }}</th>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->description }}</td>
                                    <td>
                                    @foreach ($book->authors as $item)
                                        {{ "{$item->name}" . ($item->surname ? " ({$item->surname})" : "") }} <br />
                                    @endforeach
                                    </td>
                                    <td width="300" class="text-center">
                                        <a href="{{ route('book.edit', $book->id) }}" class="btn btn-outline-secondary btn-sm" style="width:50px"><i class="fa fa-pencil"></i></a>
                                        <form action="{{ route('book.destroy', $book->id) }}" method="POST" style="display: inline-block">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-outline-danger btn-sm" style="width:50px"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>               
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
