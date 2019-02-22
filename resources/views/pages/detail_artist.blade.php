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
                                    <img src="{{ isset($data->artist->images[1]->url)?isset($data->artist->images[1]->url):"/images/not-found.png" }}" alt="profile"
                                         class="img-lg rounded-circle mb-3"/>
                                    <div class="mb-3">
                                        <h3>{{$data->artist->name}}</h3>
                                        Popularity: {{ $data->artist->popularity }}<br>
                                        Mountly Listeners: <button type="button" class="btn btn-warning btn-rounded btn-fw">{{$data->html->monthly_listeners}}</button> <br>
                                        Followers: <button type="button" class="btn btn-danger btn-rounded btn-fw">{{$data->html->followers}}</button> <br>
                                    </div>
                                    <a href="{{$data->artist->external_urls->spotify}}"
                                       class="btn btn-primary btn-block mb-2">Show In Spotify</a>
                                </div>
                                <div class="border-bottom py-4">
                                    <p>Genres</p>
                                    <div>
                                        @foreach($data->artist->genres as $item)
                                            <label class="badge badge-outline-light">{{$item}}</label>
                                        @endforeach
                                    </div>
                                    <div class="border-bottom py-4">
                                        <div class="d-flex">
                                            <div class="progress progress-md flex-grow">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                     aria-valuenow="{{ $data->artist->popularity }}"
                                                     style="width: {{ $data->artist->popularity }}%" aria-valuemin="0"
                                                     aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="py-4">
                                        <h2>Top Tracks</h2>
                                        @foreach($data->top_tracks->tracks as $item)
                                            <p class="clearfix">
                                              <span class="float-left col-md-10">
                                                {{$item->name}}
                                              </span>
                                                <span class="float-right">
                                                {{$item->popularity}}
                                              </span>
                                            </p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">

                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Artist Detail</h4>
                                            <div class="row">
                                                <div class="col-md-10 mx-auto">
                                                    <ul class="nav nav-pills nav-pills-custom" id="pills-tab-custom"
                                                        role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="pills-home-tab-custom"
                                                               data-toggle="pill" href="#pills-albums" role="tab"
                                                               aria-controls="pills-home" aria-selected="true">
                                                                Albums
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="pills-profile-tab-custom"
                                                               data-toggle="pill" href="#pills-relative" role="tab"
                                                               aria-controls="pills-profile" aria-selected="false">
                                                                Relative Artist
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="pills-profile-tab-custom"
                                                               data-toggle="pill" href="#pills-bio" role="tab"
                                                               aria-controls="pills-profile" aria-selected="false">
                                                                Biography
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content tab-content-custom-pill"
                                                         id="pills-tabContent-custom">
                                                        <div class="tab-pane fade show active" id="pills-albums"
                                                             role="tabpanel" aria-labelledby="pills-home-tab-custom">
                                                            @foreach($data->albums->items as $album)
                                                                <a href="/detail/album/{{$album->id}}">
                                                                    <div class="media">
                                                                        <img class="mr-3 w-25 rounded"
                                                                             src="{{$album->images[0]->url}}"
                                                                             alt="sample image">
                                                                        <div class="media-body">
                                                                            <h3>{{$album->name}}</h3>
                                                                            <p>Total
                                                                                tracks: {{$album->total_tracks}}</p>
                                                                            <p>Release
                                                                                date: {{$album->release_date}}</p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                        <div class="tab-pane fade" id="pills-relative" role="tabpanel"
                                                             aria-labelledby="pills-profile-tab-custom">
                                                            @foreach($data->relative_artists->artists as $artist)
                                                                <a href="/detail/artist/{{ $artist->id}}">
                                                                    <div class="media">
                                                                        <img class="mr-3 w-25 rounded"
                                                                             src="{{$artist->images[0]->url?? "/images/not-found.png"}}"
                                                                             alt="sample image">
                                                                        <div class="media-body">
                                                                            <h3>{{$artist->name}}</h3>
                                                                            <p>Popularity: {{$artist->popularity ?? "N/A" }}</p>
                                                                            <p>Followers : {{$artist->followers->total ?? "N/A"}}</p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                        <div class="tab-pane fade" id="pills-bio" role="tabpanel"
                                                             aria-labelledby="pills-profile-tab-custom">
                                                            <p>
                                                            <b>{!! $data->html->bio1 ?? ""  !!}</b>
                                                            </p>
                                                            <p>
                                                            {!! $data->html->bio2 ?? ""  !!}
                                                            </p>
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
