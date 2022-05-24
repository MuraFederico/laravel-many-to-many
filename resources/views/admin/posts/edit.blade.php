@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
{{-- @dd(Auth::user()->id) --}}
    <div class="container">
        <div class="row">
            <div class="col">
                <form action="{{ route('admin.posts.update', $post->slug) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="d-none">
                        <input type="text" class="form-control" id="user_id" name="user_id" value="{{ Auth::user()->id }}">
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('Title') }}</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}">
                    </div>
                    @error('title')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="mb-3">
                        <label for="slug" class="form-label">{{ __('Slug') }}</label>
                        <input type="text" class="form-control" id="slug" name="slug" value="{{ $post->slug }}">
                    </div>
                    <input type="button" value="Generate slug" id="btn-slugger">
                    @error('slug')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="mb-3">
                        <label for="media" class="form-label">{{ __('Image Link') }}</label>
                        <input type="text" class="form-control" id="media" name="media" value="{{ $post->media }}">
                    </div>
                    @error('media')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="mb-3">
                        <label for="content" class="form-label">{{ __('Content') }}</label>
                        <textarea class="form-control" id="content" rows="10" name="content">{{ $post->content }}</textarea>
                    </div>
                    @error('content')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button>Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
