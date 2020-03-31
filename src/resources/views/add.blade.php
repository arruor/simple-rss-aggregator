@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add new feed</div>

                <div class="card-body">
	                <form method="POST" action="{{url('/home/add')}}" autocomplete="off">
		            <input type="hidden" name="user_id" value="">
		            @if ($errors->any())
		                <div class="alert alert-danger">
		    	            <strong>Whoops!</strong> Please correct errors and try again!.
						            <br/>
		                    <ul>
		                        @foreach ($errors->all() as $error)
		                            <li>{{ $error }}</li>
		                        @endforeach
		                    </ul>
		                </div>
		            @endif

		            <!-- CSRF Token -->
		            @csrf
		            <div class="row">
			            <div class="col-md-6">
				            <div class="form-group @error('name') has-error @enderror">
					            <label for="name">Feed Name:</label>
					            <input type="text" id="name" name="name" class="form-control" placeholder="Enter Feed Name" value="{{ old('name') }}">
					            @error('name')
					            <span class="text-danger">{{ $message }}</span>
					            @enderror
				            </div>
			            </div>
                    </div>
                    <div class="row">
			            <div class="col-md-6">
				            <div class="form-group @error('url') has-error @enderror">
					            <label for="url">Feed Url:</label>
					            <input type="text" id="url" name="url" class="form-control" placeholder="Enter Feed Url" value="{{ old('url') }}">
					            @error('url')
					            <span class="text-danger">{{ $message }}</span>
					            @enderror
				            </div>
			            </div>
		            </div>
		            <div class="form-group">
			            <button class="btn btn-success">Submit</button>
		            </div>
	            </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
