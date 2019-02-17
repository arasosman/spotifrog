@extends('layouts.fily.master')

@section('title')
    {{ trans('home.title') }}
@endsection

@section('page_level_css')

@endsection

@section('content')

    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Search Artist, Track, Album or Playlist.</h4>
                        <form class="form-sample">
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="keyword"
                                                   placeholder="Search Keywords"
                                                   aria-label="Keywords">
                                            <div class="input-group-append">
                                                <button class="btn btn-sm btn-primary" type="button"
                                                        onclick="search_func();">Search
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-check mx-sm-2" style="left: 20px;">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" id="artist" checked="">
                                        Artist
                                    </label>
                                </div>
                                <div class="form-check mx-sm-2" style="left: 20px;">
                                    <label class="form-check-label">
                                        <input type="checkbox" id="track" class="form-check-input">
                                        Track
                                    </label>
                                </div>
                                <div class="form-check mx-sm-2" style="left: 20px;">
                                    <label class="form-check-label">
                                        <input type="checkbox" id="album" class="form-check-input">
                                        Album
                                    </label>
                                </div>
                                <div class="form-check mx-sm-2" style="left: 20px;">
                                    <label class="form-check-label">
                                        <input type="checkbox" id="playlist" class="form-check-input">
                                        Playlist
                                    </label>
                                </div>
                                <div class="col-md-4" style="left: 50px;">
                                    <div class="form-group">
                                        <button class="btn btn-sm btn-primary" type="button" id="show_button"
                                                onclick="showMore();">Show More Options
                                        </button>
                                        <button class="btn btn-sm btn-primary" type="button" id="hide_button"
                                                onclick="hideOptions();" style="display: none">Hide Options
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <input type="hidden" id="hide_status" value="1"/>
                            <div id="more_options" style="display: none">
                                <p class="card-description">
                                    More Option
                                </p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Min Followers</label>
                                            <div class="col-sm-9">
                                                <input type="number" id="min_followers" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Max Followers</label>
                                            <div class="col-sm-9">
                                                <input type="number" id="max_followers" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Year</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="year" class="form-control" placeholder="exp: 2008 or 2000-2005"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Limit (max 50)</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="text" id="year"
                                                       placeholder=""/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-lg-6 grid-margin stretch-card">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Popularity</h4>
                                                <p class="card-description">Rating from 1 to 10</p>
                                                <div class="br-wrapper br-theme-bars-1to10">
                                                    <select id="example-1to10" name="rating" autocomplete="off"
                                                            class="d-none">
                                                        <option value="0">0</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <button class="btn btn-sm btn-primary" type="button" style="padding: 30px;"
                                                onclick="search_func();">Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row portfolio-grid" id="search_list">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page_level_js')
    <script>
        function showMore() {
            $('#more_options').show();
            $('#show_button').hide();
            $('#hide_button').show();
            $('#hide_status').val(0);
        }

        function hideOptions() {
            $('#more_options').hide();
            $('#show_button').show();
            $('#hide_button').hide();
            $('#hide_status').val(1);
        }

        function search_func() {
            let hide_stat = $('#hide_status').val();
            let artist, track, album, playlist = false;
            if ($('#artist').is(":checked"))
                artist = true;
            if ($('#track').is(":checked"))
                track = true;
            if ($('#album').is(":checked"))
                album = true;
            if ($('#playlist').is(":checked"))
                playlist = true;
            let the_obj = {};
            if (hide_stat == 0) {
                the_obj = {
                    hide_stat: hide_stat,
                    keyword: $('#keyword').val(),
                    track: track,
                    artist: artist,
                    album: album,
                    playlist: playlist,
                    popularity: $('.br-current-rating').text(),
                    min_followers: $('#min_followers').val(),
                    max_followers: $('#max_followers').val(),
                    year: $('#year').val(),
                    limit: $('#limit').val()
                };
            } else {
                the_obj = {
                    hide_stat: hide_stat,
                    keyword: $('#keyword').val(),
                    track: track,
                    artist: artist,
                    album: album,
                    playlist: playlist,
                };
            }

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
                    if (typeof return_text.tracks !== "undefined")
                        tracks_arr = return_text.tracks.items;
                    if (typeof return_text.artists !== "undefined")
                        artists_arr = return_text.artists.items;
                    if (typeof return_text.albums !== "undefined")
                        albums_arr = return_text.albums.items;
                    if (typeof return_text.playlists !== "undefined")
                        playlists_arr = return_text.playlists.items;
                    //return array merge
                    arr1 = tracks_arr.concat(artists_arr);
                    arr1.sort(function (a, b) {
                        return a.popularity - b.popularity
                    });
                    arr1.reverse();
                    arr1 = arr1.concat(playlists_arr);
                    arr1 = arr1.concat(albums_arr);
                    //merged array each and append page
                    arr1.forEach(function (element) {
                        if (element.type == "track") {
                            if (typeof element.album.images[1] !== 'undefined')
                                image = element.album.images[1].url;
                            else
                                image = "/images/not-found.png";
                            $('#search_list').append('<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">\n' +
                                '                                        <a href="detail/' + element.type + '/' + element.id + '"> <figure class="effect-text-in">\n' +
                                '                                            <img src="' + image + '" alt="image" height="200px" "/>\n' +
                                '                                            <figcaption>\n' +
                                '                                                <h6>' + element.name + '</h6>\n' +
                                '                                                <p>Track <br>Artist : ' + element.artists[0].name + '</p>\n' +
                                '                                            </figcaption>\n' +
                                '                                        </figure>\n' +
                                '                                   </a> </div>');
                        } else if (element.type == "artist") {
                            if (typeof element.images[1] !== 'undefined')
                                image = element.images[1].url;
                            else
                                image = "/images/not-found.png";
                            $('#search_list').append('<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">\n' +
                                '                                        <a href="detail/' + element.type + '/' + element.id + '"> <figure class="effect-text-in">\n' +
                                '                                            <img src="' + image + '" alt="image" height="200px" "/>\n' +
                                '                                            <figcaption>\n' +
                                '                                                <h4>' + element.name + '</h4>\n' +
                                '                                                <p>Artist <br>followers : ' + element.followers.total + '</p>\n' +
                                '                                            </figcaption>\n' +
                                '                                        </figure>\n' +
                                '                                   </a> </div>');
                        } else if (element.type == "album") {
                            if (typeof element.artists[0] !== 'undefined')
                                artist_name = element.artists[0].name;
                            else
                                artist_name = "N/A";
                            if (typeof element.images[0] !== 'undefined')
                                image = element.images[0].url;
                            else
                                image = "/images/not-found.png";
                            $('#search_list').append('<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">\n' +
                                '                                        <a href="detail/' + element.type + '/' + element.id + '"> <figure class="effect-text-in">\n' +
                                '                                            <img src="' + image + '" alt="image" height="200px" "/>\n' +
                                '                                            <figcaption>\n' +
                                '                                                <h4>' + element.name + '</h4>\n' +
                                '                                                <p>Album <br>' +
                                ' Artist: '+artist_name +
                                ' <br>release date: ' + element.release_date + '</p>\n' +
                                '                                            </figcaption>\n' +
                                '                                        </figure>\n' +
                                '                                   </a> </div>');
                        } else if (element.type == "playlist") {
                            if (typeof element.images[0] !== 'undefined')
                                image = element.images[0].url;
                            else
                                image = "/images/not-found.png";
                            $('#search_list').append('<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">\n' +
                                '                                        <a href="detail/' + element.type + '/' + element.id + '"> <figure class="effect-text-in">\n' +
                                '                                            <img src="' + image + '" alt="image" height="200px" "/>\n' +
                                '                                            <figcaption>\n' +
                                '                                                <h4>' + element.name + '</h4>\n' +
                                '                                                <p>Playlist' +
                                '                                            </figcaption>\n' +
                                '                                        </figure>\n' +
                                '                                   </a> </div>');
                        }


                    });
                }
            });
        }

    </script>

    <script src="/js/formpickers.js"></script>
    <script src="/js/form-addons.js"></script>
    <script src="/js/x-editable.js"></script>
    <script src="/js/dropify.js"></script>
    <script src="/js/dropzone.js"></script>
    <script src="/js/jquery-file-upload.js"></script>
    <script src="/js/formpickers.js"></script>
    <script src="/js/form-repeater.js"></script>
@endsection

@section('page_document_ready')
$('body').addClass('sidebar-icon-only')
@endsection
