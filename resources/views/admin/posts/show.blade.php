@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container posts-content">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card mb-4">
              <div class="card-body">
                <h2>{{ $post->title }}</h2>
                <div class="media mb-3">
                  <img src="{{ $post->media }}" class="d-block ui-w-40 img-fluid" alt="">
                  <div class="media-body ml-3">
                    {{ $post->user->name }}
                    <div class="text-muted small">{{date_format($post->created_at, 'd/m/y')}}</div>
                  </div>
                </div>

                <p>
                  {{ $post->content }}
                </p>
                <a href="javascript:void(0)" class="ui-rect ui-bg-cover" style="background-image: url('https://bootdey.com/img/Content/avatar/avatar3.png');"></a>
              </div>
              <div class="card-footer d-flex">
                <a href="javascript:void(0)" class="d-inline-block text-muted me-3">
                    <strong>{{ $post->likes }}</strong> Likes</small>
                </a>
                <a href="javascript:void(0)" class="d-inline-block text-muted me-3">
                    <strong>{{ $post->comments }}</strong> Comments</small>
                </a>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
