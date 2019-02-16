@extends('layouts.front.master')

@section('title')
    {{ trans('home.title') }}
@endsection

@section('page_level_css')

@endsection

@section('content')
    <?php
    $data = json_decode($data);
    ?>
    <div id="page-content">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Detail</li>
            </ol>

            <div class="row">
                <div class="col-md-9 col-sm-9">
                    <section class="page-title">
                        <div class="pull-left">
                            <h1>{{$data->track->name}}</h1>
                            <h3>{{$data->track->album->artists[0]->name}}</h3>
                            <div class="rating-passive" data-rating="{{($data->track->popularity/100)*5}}">
                                <span class="stars"></span>
                                <span class="reviews">Popularity: {{$data->track->popularity}}</span>
                            </div>
                        </div>
                        <a href="{{ $data->track->external_urls->spotify }}"
                           class="btn btn-primary btn-framed btn-rounded btn-light-frame icon scroll pull-right"><i
                                class="fa fa-star"></i>Show Spotify</a>
                    </section>

                    <div class="row">
                        <div class="col-md-8 col-sm-12">

                            <section>
                                <iframe src="https://open.spotify.com/embed/track/{{$data->track->id}}" width="540"
                                        height="400" frameborder="0" allowtransparency="true"
                                        allow="encrypted-media"></iframe>

                            </section>


                        </div>
                        <!--end col-md-6-->
                        <div class="col-md-4 col-sm-12">
                            <section>
                                <div class="detail-sidebar">
                                    <section class="shadow">

                                        <div class="content">
                                            <div class="vertical-aligned-elements">
                                                <div class="element"><img src="{{$data->artist->images[0]->url}}"
                                                                          width="90%" height="200px" alt=""></div>
                                            </div>
                                            <hr>
                                            <address>
                                                <figure><i class="fa fa-envelope"></i>{{$data->artist->name}} </figure>
                                                <figure><i
                                                        class="fa fa-envelope"></i>Followers: {{$data->artist->followers->total}}
                                                </figure>
                                            </address>
                                        </div>
                                    </section>
                                </div>
                                <!--end detail-sidebar-->
                            </section>
                            <section>
                                <h2>Genres</h2>
                                <ul class="tags">
                                    @foreach($data->artist->genres as $item)
                                        <li>{{$item}}</li>
                                    @endforeach
                                </ul>
                            </section>


                        </div>
                        <!--end col-md-3-->
                    </div>
                    <!--end row-->
                </div>
                <!--end col-md-9-->

                <div class="col-md-3 col-sm-3">
                    <aside class="sidebar">

                        <section>
                            <h2>Recent Items</h2>
                            @foreach($data->other_tracks->tracks as $item)
                                <div class="item">
                                    <a href="/detail/track/{{$item->id}}">
                                        <div class="description">

                                            <div class="label label-default">Popularite: {{$item->popularity}}</div>
                                            <h3>{{$item->name}}</h3>
                                            <h4>{{$item->artists[0]->name}}</h4>
                                        </div>
                                        <!--end description-->
                                        <div class="image bg-transfer">
                                            <img src="{{$item->album->images[1]->url}}" width="90%" height="90%">
                                        </div>
                                        <!--end image-->
                                    </a>
                                    <div class="controls-more">
                                        <ul>
                                            <li><a href="#">Add to favorites</a></li>
                                            <li><a href="#">Add to watchlist</a></li>
                                            <li><a href="#" class="quick-detail">Quick detail</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!--end item-->
                            @endforeach
                        </section>
                    </aside>
                    <!--end sidebar-->
                </div>
                <!--end col-md-3-->
            </div>
        </div>
        <!--end container-->
    </div>
    <!--end page-content-->


@endsection

@section('page_level_js')

@endsection

@section('page_document_ready')

@endsection
