@extends('layouts.front.master')

@section('title')
    {{ trans('home.title') }}
@endsection

@section('page_level_css')

@endsection

@section('content')

    <div id="page-content">
        <div class="hero-section has-background height-350px">
            <div class="wrapper">
                <div class="inner">
                    <div class="center">
                        <div class="page-title">
                            <h1>Find yourself</h1>
                            <h2>Spotifrog exp</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="background-wrapper">
                <div class="bg-transfer opacity-30"><img src="/assets/img/background-03.jpeg" alt=""></div>
                <div class="background-color background-color-black"></div>
            </div>
            <!--end background-wrapper-->
        </div>
        <!--end hero-section-->

        <section class="block background-is-dark">
            <div class="form search-form">
                <div class="container">
                    <div class="section-title">
                        <h2 class="center">Find</h2>
                    </div>
                    <form method="POST" action="" id="search_form">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-4 col-sm-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="keyword" name="keyword"
                                           placeholder="Enter keyword">
                                </div>
                                <!--end form-group-->
                            </div>
                            <!--end col-md-4-->
                            <div class="col-md-2 col-sm-4">
                                <div class="form-group">
                                    <input type="number" class="form-control" id="min_followers" placeholder="min followers ">
                                </div>
                                <!--end form-group-->
                            </div>
                            <!--end col-md-4-->
                            <div class="col-md-2 col-sm-4">
                                <div class="form-group">
                                    <input type="number" class="form-control" id="max_followers" placeholder="max followers ">
                                </div>
                                <!--end form-group-->
                            </div>
                            <!--end col-md-4-->
                            <div class="col-md-3 col-sm-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="date" placeholder="exp= 2019 or 2015-2018">
                                </div>
                                <!--end form-group-->
                            </div>
                            <!--end col-md-4-->
                            <div class="col-md-1 col-sm-4">
                                <div class="form-group">
                                    <a href="javascript:void(0)" type="submit" class="btn btn-primary width-100 darker"
                                       onclick="search_func();"><i
                                            class="fa fa-search"></i></a>
                                </div>
                                <!--end form-group-->
                            </div>
                            <!--end col-md-4-->
                        </div>
                        <!--end row-->
                        <div class="row">
                            <div class="col-md-5 col-sm-6">
                                <ul class="checkboxes">
                                    <li>
                                        <div class="form-group">
                                            <label class="no-margin"><input type="checkbox" name="track" id="track"
                                                                            checked>Track</label>
                                        </div>
                                        <!--end form-group-->
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label class="no-margin"><input type="checkbox" name="artist" id="artist">Artist</label>
                                        </div>
                                        <!--end form-group-->
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label class="no-margin"><input type="checkbox" name="album" id="album">Album</label>
                                        </div>
                                        <!--end form-group-->
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label class="no-margin"><input type="checkbox" name="playlist"
                                                                            id="playlist">Playlist</label>
                                        </div>
                                        <!--end form-group-->
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-2 col-sm-2">
                                <ul class="checkboxes">
                                    <li>
                                        <div class="form-group">
                                            <label class="no-margin"><input type="checkbox" name="is_new" id="is_new"
                                                                            checked>Is New</label>
                                        </div>
                                        <!--end form-group-->
                                    </li>

                                </ul>
                            </div>
                            <div class="col-md-2">
                                <div class="ui-slider" id="popularity" data-value-min="0" data-value-max="100"
                                     data-value-type="number" data-currency="" data-currency-placement="before">
                                    <div class="values clearfix">
                                        <input class="value-min" name="value-min[]" readonly="">
                                        <input class="value-max" name="value-max[]" readonly="">
                                    </div>
                                    <div class="element"></div>
                                </div>
                                <!--end price-slider-->
                            </div>
                            <div class="col-md-2 col-sm-2">
                                <ul class="checkboxes">
                                    <li>
                                        <div class="form-group">
                                            <input type="number" id="limit" class="form-control" placeholder="Limit" max="50">
                                        </div>
                                        <!--end form-group-->
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </form>
                    <!--end form-hero-->
                </div>
                <!--end container-->
            </div>
            <!--end search-form-->
            <div class="background-wrapper">
                <div class="background-color background-color-default"></div>
                <div class="bg-transfer opacity-40"><img src="/assets/img/background-04.jpeg" alt=""></div>
            </div>
        </section>

        <section class="block">
            <div class="container" id="search_list">

            </div>
        </section>

    </div>

@endsection

@section('page_level_js')
    <script>

        function search_func() {

            let artist,track,album,playlist,is_new = false;
            if($('#artist').parent().hasClass('checked'))
                artist = true;
            if($('#track').parent().hasClass('checked'))
                track = true;
            if($('#album').parent().hasClass('checked'))
                album = true;
            if($('#playlist').parent().hasClass('checked'))
                playlist = true;
            if($('#is_new').parent().hasClass('checked'))
                is_new = true;

            let the_obj = {
                keyword: $('#keyword').val(),
                track: track,
                artist: artist,
                album: album,
                playlist: playlist,
                is_new: is_new,
                min_pop: $('.value-min').val(),
                max_pop: $('.value-max').val(),
                min_followers : $('#min_followers').val(),
                max_followers : $('#max_followers').val(),
                date : $('#date').val(),
                limit : $('#limit').val()
            };
            $.ajax({

                method: "POST",
                url: "/search_data",
                data: "the_obj=" + JSON.stringify(the_obj),
                success: function (return_text) {
                    $('#search_list').html("");
                    var tracks_arr = [];
                    var artists_arr = [];
                    var albums_arr = [];
                    var playlists_arr = [];
                    if(typeof  return_text.tracks !== "undefined")
                        tracks_arr = return_text.tracks.items;
                    if(typeof  return_text.artists !== "undefined")
                        artists_arr = return_text.artists.items;
                    if(typeof return_text.albums !== "undefined")
                        albums_arr = return_text.albums.items;
                    if(typeof return_text.playlists !== "undefined")
                        playlists_arr = return_text.playlists.items;
                    //return array merge
                    arr1=tracks_arr.concat(artists_arr);
                    arr1.sort(function(a,b) {return a.popularity - b.popularity} );
                    arr1.reverse();
                    arr1=arr1.concat(playlists_arr);
                    arr1=arr1.concat(albums_arr);
                    //merged array each and append page
                    arr1.forEach(function (element) {
                        if(element.type == "track"){
                            $('#search_list').append('<div class="col-md-4 col-sm-4">\n' +
                                '                                <div class="item" data-id="1">\n' +
                                '                                   <figure class="ribbon">Track</figure>' +
                                '                                    <a href="detail/' + element.type + '/' + element.id + '">\n' +
                                '                                        <div class="description">\n' +
                                '                                            <figure>' + element.album.name + '</figure>\n' +
                                '                                            <div class="label label-default">' + element.album.release_date + '</div>\n' +
                                '                                            <h3>' + element.name + '</h3>\n' +
                                '                                            <h4>' + element.artists[0].name + '</h4>\n' +
                                '                                        </div>\n' +
                                '                                        <!--end description-->\n' +
                                '                                        <div class="image bg-transfer">\n' +
                                '                                            <img src="' + element.album.images[1].url + '" alt="">\n' +
                                '                                        </div>\n' +
                                '                                        <!--end image-->\n' +
                                '                                    </a>\n' +
                                '                                    <div class="additional-info">\n' +
                                '                                        <div class="rating-passive" data-rating="4">\n' +
                                '                                        </div>\n' +
                                '                                        <div class="controls-more">\n' +
                                '                                            <ul>\n' +
                                '                                                <li><a href="#">Add to favorites</a></li>\n' +
                                '                                                <li><a href="#">Add to watchlist</a></li>\n' +
                                '                                                <li><a href="#" class="quick-detail">Quick detail</a></li>\n' +
                                '                                            </ul>\n' +
                                '                                        </div>\n' +
                                '                                        <!--end controls-more-->\n' +
                                '                                    </div>\n' +
                                '                                    <!--end additional-info-->\n' +
                                '                                </div>\n' +
                                '                                <!--end item-->\n' +
                                '                            </div>');
                        }
                        else if(element.type == "artist"){
                            if(typeof element.images[1] !== 'undefined')
                                image = element.images[1].url;
                            else
                                image = "";
                            $('#search_list').append('<div class="col-md-4 col-sm-4">\n' +
                                '                                <div class="item" data-id="1">\n' +
                                '                                   <figure class="ribbon">Artist</figure>' +
                                '                                    <a href="detail/' + element.type + '/' + element.id + '">\n' +
                                '                                        <div class="description">\n' +
                                '                                            <figure></figure>\n' +
                                '                                            <div class="label label-default">popularity: ' + element.popularity + '</div>\n' +
                                '                                            <h3>' + element.name + '</h3>\n' +
                                '                                            <h4>followers : ' + element.followers.total + '</h4>\n' +
                                '                                        </div>\n' +
                                '                                        <!--end description-->\n' +
                                '                                        <div class="image bg-transfer">\n' +
                                '                                            <img src="' + image+ '" alt="">\n' +
                                '                                        </div>\n' +
                                '                                        <!--end image-->\n' +
                                '                                    </a>\n' +
                                '                                    <div class="additional-info">\n' +
                                '                                        <div class="rating-passive" data-rating="4">\n' +
                                '                                        </div>\n' +
                                '                                        <div class="controls-more">\n' +
                                '                                            <ul>\n' +
                                '                                                <li><a href="#">Add to favorites</a></li>\n' +
                                '                                                <li><a href="#">Add to watchlist</a></li>\n' +
                                '                                                <li><a href="#" class="quick-detail">Quick detail</a></li>\n' +
                                '                                            </ul>\n' +
                                '                                        </div>\n' +
                                '                                        <!--end controls-more-->\n' +
                                '                                    </div>\n' +
                                '                                    <!--end additional-info-->\n' +
                                '                                </div>\n' +
                                '                                <!--end item-->\n' +
                                '                            </div>');
                        }
                        else if(element.type == "album"){
                            if(typeof element.artists[0] !== 'undefined')
                                artist_name = element.artists[0].name;
                            else
                                artist_name = "";
                            if(typeof element.images[1] !== 'undefined')
                                image = element.images[1].url;
                            else
                                image = "";
                            $('#search_list').append('<div class="col-md-4 col-sm-4">\n' +
                                '                                <div class="item" data-id="1">\n' +
                                '                                   <figure class="ribbon">Album</figure>' +
                                '                                    <a href="detail/' + element.type + '/' + element.id + '">\n' +
                                '                                        <div class="description">\n' +
                                '                                            <figure>' + element.album_type+ '</figure>\n' +
                                '                                            <div class="label label-default">' + artist_name + '</div>\n' +
                                '                                            <h3>' + element.name + '</h3>\n' +
                                '                                            <h4>release : ' + element.release_date + '</h4>\n' +
                                '                                        </div>\n' +
                                '                                        <!--end description-->\n' +
                                '                                        <div class="image bg-transfer">\n' +
                                '                                            <img src="' + image+ '" alt="">\n' +
                                '                                        </div>\n' +
                                '                                        <!--end image-->\n' +
                                '                                    </a>\n' +
                                '                                    <div class="additional-info">\n' +
                                '                                        <div class="rating-passive" data-rating="4">\n' +
                                '                                        </div>\n' +
                                '                                        <div class="controls-more">\n' +
                                '                                            <ul>\n' +
                                '                                                <li><a href="#">Add to favorites</a></li>\n' +
                                '                                                <li><a href="#">Add to watchlist</a></li>\n' +
                                '                                                <li><a href="#" class="quick-detail">Quick detail</a></li>\n' +
                                '                                            </ul>\n' +
                                '                                        </div>\n' +
                                '                                        <!--end controls-more-->\n' +
                                '                                    </div>\n' +
                                '                                    <!--end additional-info-->\n' +
                                '                                </div>\n' +
                                '                                <!--end item-->\n' +
                                '                            </div>');
                        }
                        else if(element.type == "playlist"){
                            if(typeof element.images[1] !== 'undefined')
                                image = element.images[1].url;
                            else
                                image = "";
                            $('#search_list').append('<div class="col-md-4 col-sm-4">\n' +
                                '                                <div class="item" data-id="1">\n' +
                                '                                   <figure class="ribbon">Playlist</figure>' +
                                '                                    <a href="detail/' + element.type + '/' + element.id + '">\n' +
                                '                                        <div class="description">\n' +
                                '                                            <figure></figure>\n' +
                                '                                            <div class="label label-default">' + element.owner.display_name + '</div>\n' +
                                '                                            <h3>' + element.name + '</h3>\n' +
                                '                                            <h4>total tracks : ' + element.tracks.total + '</h4>\n' +
                                '                                        </div>\n' +
                                '                                        <!--end description-->\n' +
                                '                                        <div class="image bg-transfer">\n' +
                                '                                            <img src="' + image+ '" alt="">\n' +
                                '                                        </div>\n' +
                                '                                        <!--end image-->\n' +
                                '                                    </a>\n' +
                                '                                    <div class="additional-info">\n' +
                                '                                        <div class="rating-passive" data-rating="4">\n' +
                                '                                        </div>\n' +
                                '                                        <div class="controls-more">\n' +
                                '                                            <ul>\n' +
                                '                                                <li><a href="#">Add to favorites</a></li>\n' +
                                '                                                <li><a href="#">Add to watchlist</a></li>\n' +
                                '                                                <li><a href="#" class="quick-detail">Quick detail</a></li>\n' +
                                '                                            </ul>\n' +
                                '                                        </div>\n' +
                                '                                        <!--end controls-more-->\n' +
                                '                                    </div>\n' +
                                '                                    <!--end additional-info-->\n' +
                                '                                </div>\n' +
                                '                                <!--end item-->\n' +
                                '                            </div>');
                        }


                    });
                }
            });
        }

    </script>
@endsection

@section('page_document_ready')

@endsection
