@extends('layouts.master')

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
                                    <img src="{{$data->album->images[1]->url}}" alt="profile"
                                         class="img-lg rounded-circle mb-3"/>
                                    <div class="mb-3">
                                        <h3>{{$data->album->name}}</h3>
                                        label: {{ $data->album->label }} <br>
                                        Artists: @foreach($data->album->artists as $item)
                                            <label class="badge badge-outline-light">{{$item->name}}</label>
                                        @endforeach
                                            <br>
                                        Popularity: {{ $data->album->popularity }} <br>
                                        Release Date: {{ $data->album->release_date }} <br>
                                        Total Tracks: {{ $data->album->total_tracks }} <br>
                                        Copyrights: {{ $data->album->copyrights[0]->text }}
                                    </div>
                                    <a href="{{$data->album->external_urls->spotify}}"
                                       class="btn btn-primary btn-block mb-2">Show in Spotify</a>
                                </div>
                                <div class="border-bottom py-4">
                                    <p>Genres</p>
                                    <div>
                                        @foreach($data->album->genres as $item)
                                            <label class="badge badge-outline-light">{{$item}}</label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">

                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3>Album Detail</h3>
                                            <h2>{{$data->album->name}}</h2>
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
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="pills-profile-tab-custom"
                                                               data-toggle="pill" href="#pills-markets" role="tab"
                                                               aria-controls="pills-profile" aria-selected="false">
                                                                Available Markets
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content tab-content-custom-pill"
                                                         id="pills-tabContent-custom">
                                                        <div class="tab-pane fade show active" id="pills-tracks"
                                                             role="tabpanel" aria-labelledby="pills-home-tab-custom">
                                                            @foreach($data->tracks->items as $track)
                                                                <a href="/detail/track/{{$track->id}}">
                                                                    <div class="media">
                                                                        <img class="mr-3 w-25 rounded"
                                                                             src="{{$data->album->images[0]->url}}"
                                                                             alt="sample image">
                                                                        <div class="media-body">
                                                                            <h3>{{$track->name}}</h3>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                        <div class="tab-pane fade" id="pills-markets" role="tabpanel"
                                                             aria-labelledby="pills-profile-tab-custom">
                                                            @foreach($data->album->available_markets as $item)
                                                                <label class="badge badge-outline-light">{{$item}}</label>
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
