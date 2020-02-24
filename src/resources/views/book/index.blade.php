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
                            <input type="text" class="form-control" placeholder="Search your book"> 
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <select name="book[]" class="form-control selectpicker" data-size="5" multiple="" data-live-search="true" title="Books">
                                        <?php 
                                        if(!empty($books)){
                                            foreach($books as $book){ ?>
                                                <option value="<?= $book->id ?>" <?= in_array($book->id, $selectedBook) ? "selected" : NULL ; ?>><?= $book->name ?></option>
                                        <?php }
                                        } 
                                        ?>
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
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($books as $book)
                                <tr>
                                    <th scope="row">{{ $book->id }}</th>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->description }}</td>
                                    <td>{{ $book->author }}</td>
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
