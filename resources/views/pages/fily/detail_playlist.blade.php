@extends('layouts.fily.master')

@section('title')
    {{ trans('home.title') }}
@endsection

@section('page_level_css')

@endsection

@section('content')
    <?php
    $data = json_decode($data);
    ?>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="border-bottom text-center pb-4">
                                    <img src="{{$data->playlist->images[0]->url}}" alt="profile"
                                         class="img-lg rounded-circle mb-3"/>
                                    <div class="mb-3">
                                        <h3>{{$data->playlist->name}}</h3><br>
                                        Owner: {{$data->playlist->owner->display_name}} <br>
                                        Followers: {{ $data->playlist->followers->total }} <br>
                                    </div>
                                    <a href="{{$data->playlist->external_urls->spotify}}"
                                       class="btn btn-primary btn-block mb-2">Show in Spotify</a>
                                </div>
                            </div>
                            <div class="col-lg-8">

                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3>Playlist Detail</h3>
                                            <h2>{{$data->playlist->name}}</h2>
                                            <div class="row">
                                                <div class="col-md-10 mx-auto">
                                                    <ul class="nav nav-pills nav-pills-custom" id="pills-tab-custom"
                                                        role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="pills-home-tab-custom"
                                                               data-toggle="pill" href="#pills-tracks" role="tab"
                                                               aria-controls="pills-home" aria-selected="true">
                                                                Tracks
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content tab-content-custom-pill"
                                                         id="pills-tabContent-custom">
                                                        <div class="tab-pane fade show active" id="pills-tracks"
                                                             role="tabpanel" aria-labelledby="pills-home-tab-custom">
                                                            @foreach($data->playlist->tracks->items as $track)

                                                                <div class="media">
                                                                    <img class="mr-3 w-25 rounded"
                                                                         src="{{$track->track->album->images[0]->url}}"
                                                                         alt="sample image">

                                                                    <div class="media-body">
                                                                        <a href="/detail/track/{{$track->track->id}}">
                                                                            <h3>{{$track->track->name}}</h3></a>
                                                                        Artist
                                                                        <a href="/detail/artist/{{$track->track->artists[0]->id}}">{{$track->track->artists[0]->name}}</a>
                                                                        <p>
                                                                            Popularity {{$track->track->popularity}}
                                                                        </p>
                                                                    </div>

                                                                </div>

                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
@endsection

@section('page_level_js')
    <script>


    </script>

    <script src="/js/formpickers.js"></script>
    <script src="/js/form-addons.js"></script>
    <script src="/js/x-editable.js"></script>
    <script src="/js/dropify.js"></script>
    <script src="/js/dropzone.js"></script>
    <script src="/js/jquery-file-upload.js"></script>
    <script src="/js/formpickers.js"></script>
    <script src="/js/form-repeater.js"></script>
    <script src="/js/profile-demo.js"></script>
@endsection

@section('page_document_ready')
    $('body').addClass('sidebar-icon-only')
@endsection
