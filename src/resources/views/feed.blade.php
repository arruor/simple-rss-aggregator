@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Displaying items from {{ $feed->name }}</div>

                    <div class="card-body">
                        @forelse ($rss->item as $item)
                            <div class="card-header">
                                <h5>{{ $item->title }} </h5>
                            </div>
                            <br>
                            <div class="card-block">
                                <div>{!! \Illuminate\Support\Str::words($item->description, 50, ' ...') !!}<br></div>
                                <div>
                                    <div class="text-justify">
                                        Published at {{  date('d/m/Y H:i:s', (int)$item->timestamp) }} by {{ $item->author }} &nbsp;
                                        <a href="{{ $item->link }}" target="_blank">Read more</a>
                                    </div>
                                </div>
                            </div>
                            <br>&nbsp;<br>
                        @empty
                            <p>No items in this feed</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
