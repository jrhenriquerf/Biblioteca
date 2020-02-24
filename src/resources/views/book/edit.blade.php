@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('book.index') }}">Books</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add new book</li>
                    </ol>
                </nav>

                <div class="card-body">
                    <form action="{{ route('book.update', $book) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" aria-describedby="title" value="{{ $book->title }}">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control" id="description">{{ $book->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Athors</label>
                            <select name="authors[]" class="form-control selectpicker" multiple="" data-live-search="true" title="Authors">
                                @if(!empty($authors))
                                    @foreach($authors as $author)
                                        <option value="{{ $author->id }}" {{ in_array($author->id, $selectedAuthor) ? "selected" : NULL }}>{{ $author->name }} {{ $author->surname ? '(' . $author->surname . ')' : '' }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="help-block">Caso não encontre o author desejado, <a href="{{ route('author.create') }}" target="_blank">clique aqui</a> para cadastrar um novo</p>
                        </div>
                        @if(!empty($book->image))
                        <div class="form-group">
                            <img src="/images/book/{{ $book->image }}"  width="10%" />
                            <input type="hidden" name="deleteimage" value="{{ $book->image }}">
                        </div>
                        @endif
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                            </div>
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
