@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">List Feeds</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @forelse($feeds as $feed)
                        <p>Read this feed at <a href="{{ url('/home/feed') . '/' . $feed->id }}" target="_blank"> {{ $feed->name }} </a></p>
                        <br>
                    @empty
                        <p>There are no feeds found! Please add some.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
