@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <? $c = 0; ?>
                    @foreach($books as $book)
                        @if($c == 0)
                            <? $opened = true; ?>
                            <div class="card-group">
                        @endif
                            <? $c++; ?>
                            <div class="card">
                                <img src="{{ asset('/images/book/' . $book->image) }}" class="card-img-top" alt="..." width="100" height="300">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $book->title }}</h5>
                                    <p class="card-text">{{ $book->description }}</p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            Author(s): 
                                            @foreach ($book->authors as $key => $item)
                                                {{ "{$item->name}" . ($item->surname ? " ({$item->surname})" : "") . ($key != count($book->authors) - 1 ? ',' : '') }}
                                            @endforeach
                                        </small>
                                    </p>
                                </div>
                                <div class="card-body">
                                    <a href={{ route("lending.index") }} class="card-link">Borrow this book</a>
                                </div>
                            </div>
                        @if($c == 0)
                            <? $opened = false; ?>
                            </div>
                        @endif
                        <?
                            if ($c == 3) {
                                $c = 0;
                            }
                        ?>
                    @endforeach
                    @if($opened)
                        <? $opened = false; ?>
                        </div>
                    @endif
            </div>
        </div>
    </div>
</div>
@endsection
