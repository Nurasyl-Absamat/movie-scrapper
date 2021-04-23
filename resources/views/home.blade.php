@extends('app')

@section('content')
        @foreach($movies as $movie)
            <div class="col-sm-4 h-100">
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="{{ $movie->image_url }}" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">{{ $movie->title }}</h5>
                        <p class="card-text text-sm">{{ $movie->categories()->pluck('title')->implode(', ') }}</p>
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                 data-target="#watchModal__{{$movie->id}}">
                            Watch
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="watchModal__{{$movie->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <!-- 16:9 aspect ratio -->
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="{{ $movie->video_url }}" id="video" ></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
@endsection

