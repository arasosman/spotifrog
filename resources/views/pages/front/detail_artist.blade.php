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
                <li><a href="/">Home</a></li>
                <li class="active">Detail</li>
            </ol>
            <section class="page-title pull-left">
                <h1>{{$data->artist->name}}</h1>
                <h3>Followers : {{$data->artist->followers->total}}</h3>
                <div class="rating-passive" data-rating="{{ ($data->artist->popularity/100)*5 }}">
                    <span class="stars"></span>
                    <span class="reviews">Popularity</span>
                </div>
            </section>
            <!--end page-title-->
            <a href="#write-a-review" class="btn btn-primary btn-framed btn-rounded btn-light-frame icon scroll pull-right"><i class="fa fa-star"></i>Edit</a>
        </div>
        <!--end container-->

        <div class="container">
            <div class="row">
                <div class="col-md-7 col-sm-7">
                    <div id="gallery-nav"></div>
                    <section>
                        <h2>Biography</h2>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec efficitur tristique enim, ac tincidunt
                            massa pulvinar non. Donec scelerisque libero eu tincidunt cursus. Phasellus vel commodo nunc, nec suscipit
                            enim. Integer suscipit, mauris consectetur pharetra ultrices, neque sem malesuada mauris, id tristique
                            ante leo vel magna. Phasellus ac risus vel erat elementum fringilla et non massa. Pellentesque habitant
                            morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                        </p>
                    </section>

                    {{--Genres--}}
                    <section>
                        <h2>Genres</h2>
                        <ul class="tags">
                            @foreach($data->artist->genres as $item)
                            <li>{{$item}}</li>
                            @endforeach
                        </ul>
                    </section>


                    <section>
                        <h2>Albums</h2>
                        @foreach($data->albums->items as $item)
                            <div class="review">
                                <div class="image">
                                    <div class="bg-transfer"><img src="{{$item->images[1]->url}}" alt="" width="90%" height="90%"></div>
                                </div>
                                <div class="description">
                                    <figure>
                                        <span class="date">{{$item->release_date}}</span>
                                    </figure>
                                    <h3><a href="/detail/album/{{$item->id}}">{{$item->name}}</a></h3>
                                </div>
                            </div>
                        @endforeach
                    </section>
                    {{--relative artist--}}
                    <section>
                        <div class="row">
                            @foreach($data->relative_artists->artists as $item)
                            <div class="col-md-4 col-sm-3">
                                <div class="subject-list">
                                    <figure class="ribbon">Relative Artist</figure>
                                    <a href="/detail/artist/{{$item->id}}" class="image">
                                        <div class="bg-transfer disable-on-mobile">
                                            <img src="{{$item->images[1]->url}}" width="100%" height="100%">
                                        </div>
                                    </a>
                                    <!--end image-->
                                    <div class="description">
                                        <section class="name">
                                            <h3><a href="/detail/artist/{{$item->id}}">{{$item->name}}</a></h3>
                                            <h4>followers : {{$item->followers->total}}</h4>
                                        </section>

                                        <!--end social-->
                                    </div>
                                    <!--end description-->
                                </div>
                                <!--end subject-list-->
                            </div>
                            @endforeach
                        </div>
                        <!--end row-->
                    </section>

                </div>
                <!--end col-md-7-->
                <div class="col-md-5 col-sm-5">
                    <div class="detail-sidebar">
                        <section class="shadow">
                            <div class="height-250px center" id="artist_picture">
                                <img src="{{$data->artist->images[0]->url}}" width="250px" height="250px">
                            </div>
                            <!--end map-->
                            <div class="content">
                                <div class="vertical-aligned-elements">
                                    <div class="element"><img src="assets/img/logo-2.png" alt=""></div>
                                    <div class="element text-align-right"><a href="#" class="btn btn-primary btn-rounded btn-xs">Claim</a></div>
                                </div>
                            </div>
                        </section>
                        <section>
                            <h2>Top Tracks</h2>
                            <table style="width:100%">
                                <tr>
                                    <th>Name</th>
                                    <th>Popularity</th>
                                </tr>
                                @foreach($data->top_tracks->tracks as $item)
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->popularity}}</td>
                                </tr>
                                @endforeach
                            </table>
                        </section>
                    </div>
                    <!--end detail-sidebar-->
                </div>
                <!--end col-md-5-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </div>
    <!--end page-content-->

@endsection

@section('page_level_js')

@endsection

@section('page_document_ready')

@endsection
