@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <? $c = 0; ?>
                    <? $opened = false; ?>
                    @foreach($books as $book)
                        @if($c == 0)
                            <? $opened = true; ?>
                            <div class="card-group">
                        @endif
                            <? $c++; ?>
                            <div class="card">
                                <img src="{{ asset('/images/book/' . $book->image) }}" class="card-img-top" alt="..." width="100" height="300">
                                <div class="card-body" style="margin-bottom: 50px">
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
                                <div class="card-footer position-absolute" align="center" style="bottom: 0; width: 100%;">
                                    <? $index = count($book->lendings) - 1 ?>

                                    @if ($book->lendings                                       &&
                                        !empty($book->lendings[$index])                        &&
                                        $book->lendings[$index]->pivot->book_id == $book->id   &&
                                        empty($book->lendings[$index]->date_finish)            &&
                                        $book->lendings[$index]->user_id == Auth::id()
                                    )
                                        <form action={{ route('lending.update', $book->lendings[$index]->id) }} method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('PUT') }}
                                            <input type="hidden" name="home" value="1">
                                            <button class="btn btn-danger" align="center">
                                                Give back this book
                                            </button>
                                        </form>
                                        @else
                                        <form action={{ route('lending.store') }} method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="book_id" value={{ $book->id }} />
                                            <button class="btn btn-primary" align="center">
                                                Borrow this book
                                            </button>
                                        </form>
                                    @endif
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
