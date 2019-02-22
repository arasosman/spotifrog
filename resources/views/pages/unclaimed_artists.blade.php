@extends('layouts.master')

@section('title')
    {{ trans('home.title') }}
@endsection

@section('page_level_css')
    {!! $data_table->css() !!}

@endsection

@section('content')

    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        {!! $data_table->html() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page_level_js')
    {!! $data_table->js() !!}
@endsection

@section('page_document_ready')
    {!! $data_table->ready() !!}
@endsection
