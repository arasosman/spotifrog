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
    <script src="/js/formpickers.js"></script>
    <script src="/js/form-addons.js"></script>
    <script src="/js/x-editable.js"></script>
    <script src="/js/dropify.js"></script>
    <script src="/js/dropzone.js"></script>
    <script src="/js/jquery-file-upload.js"></script>
    <script src="/js/formpickers.js"></script>
    <script src="/js/form-repeater.js"></script>
    <script src="/js/profile-demo.js"></script>
    <script src="/js/data-table.js"></script>
@endsection

@section('page_document_ready')
    {!! $data_table->ready() !!}
@endsection
