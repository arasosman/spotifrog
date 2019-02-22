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
                                    <img src="{{$data->artist->images[1]->url}}" alt="profile"
                                         class="img-lg rounded-circle mb-3"/>
                                    <div class="mb-3">
                                        <h3>{{$data->artist->name}}</h3>
                                        Popularity: {{ $data->artist->popularity }} <br>
                                        Followers:
                                        <button type="button"
                                                class="btn btn-inverse-danger btn-fw btn-rounded">{{$data->artist->followers->total}}</button>
                                    </div>
                                    <a href="{{$data->artist->external_urls->spotify}}"
                                       class="btn btn-primary btn-block mb-2">Show Profile in Spotify</a>
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
                                        @foreach($data->other_tracks->tracks as $item)
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
                                            <h3>Track Detail</h3>
                                            <span class="float-right">  <a
                                                    href="{{$data->track->external_urls->spotify}}"
                                                    class="btn btn-warning btn-rounded btn-block mb-2">Show In Spotify</a></span>
                                            <h2>{{$data->track->name}}</h2>
                                            <div class="row">
                                                <div class="col-md-10 mx-auto">
                                                    <ul class="nav nav-pills nav-pills-custom" id="pills-tab-custom"
                                                        role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="pills-home-tab-custom"
                                                               data-toggle="pill" href="#pills-track" role="tab"
                                                               aria-controls="pills-home" aria-selected="true">
                                                                Track
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="pills-profile-tab-custom"
                                                               data-toggle="pill" href="#pills-others" role="tab"
                                                               aria-controls="pills-profile" aria-selected="false">
                                                                Other Tracks
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content tab-content-custom-pill"
                                                         id="pills-tabContent-custom">
                                                        <div class="tab-pane fade show active" id="pills-track"
                                                             role="tabpanel" aria-labelledby="pills-home-tab-custom">
                                                            <p>
                                                                Popularity: {{$data->track->popularity}}
                                                            </p>
                                                            <p>
                                                                Album Name: {{$data->track->album->name}}
                                                            </p>
                                                            <iframe
                                                                src="https://open.spotify.com/embed/track/{{$data->track->id}}"
                                                                width="350"
                                                                height="300" frameborder="0" allowtransparency="true"
                                                                allow="encrypted-media"></iframe>
                                                        </div>
                                                        <div class="tab-pane fade" id="pills-others" role="tabpanel"
                                                             aria-labelledby="pills-profile-tab-custom">
                                                            @foreach($data->other_tracks->tracks as $track)
                                                                <a href="/detail/track/{{$track->id}}">
                                                                    <div class="media">
                                                                        <img class="mr-3 w-25 rounded"
                                                                             src="{{$track->album->images[0]->url}}"
                                                                             alt="sample image">
                                                                        <div class="media-body">
                                                                            <h3>{{$track->name}}</h3>
                                                                            <p>Popularity {{$track->popularity}}</p>

                                                                        </div>
                                                                    </div>
                                                                </a>
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
