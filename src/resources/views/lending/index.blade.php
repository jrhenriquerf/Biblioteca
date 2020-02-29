@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Pendings</div>

                <div class="card-body">
                    <form class="form-inline" action="{{ route('lending.search') }}" method="GET">
                        {{ csrf_field() }}                      
                        <div class="form-group">
                            <input type="text" class="form-control" name="inputSearch" placeholder="Search your pending"> 
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </form>
                    <br>
                    <table class="table table-hover">
                        <thead class="thead-dark">
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Book</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            @if(Auth::user()->isAdmin())
                                <th scope="col">User</th>
                            @endif
                            <th scope="col" class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($lendings as $pending)
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $pending->books[0]->title }}</td>
                                    <td>{{ $pending->date_start }}</td>
                                    <td>{{ $pending->date_end }}</td>
                                    @if(Auth::user()->isAdmin())
                                        <td>{{ $pending->users->name }}</td>
                                    @endif
                                    <td width="100" class="text-center">
                                        @if($pending->user_id == Auth::id())
                                            <form action="{{ route('lending.update', $pending->id) }}" method="POST" style="display: inline-block">
                                                {{ method_field('PUT') }}
                                                {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" style="width:100px">Give back</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                        </tbody>
                    </table>               
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
