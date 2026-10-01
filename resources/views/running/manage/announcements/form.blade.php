@extends('layouts.app')

@php
    $editing = $announcement->exists;
    $pageTitle = $editing ? 'Edit announcement' : 'Write an announcement';
@endphp

@section('title', $pageTitle.' — Run club admin')

@push('styles')
    @include('running.partials.styles')
    @include('running.manage.partials.styles')
@endpush

@section('content')

<div class="rc-wrap">
    @include('running.manage.partials.header', ['title' => $pageTitle, 'active' => 'announcements'])

    @if ($errors->any())
        <div class="rc-error-summary" role="alert">
            Please fix the {{ \Illuminate\Support\Str::plural('field', $errors->count()) }} marked below.
        </div>
    @endif

    <form method="POST"
          action="{{ $editing ? route('running.manage.announcements.update', $announcement) : route('running.manage.announcements.store') }}"
          class="rc-form">
        @csrf
        @if ($editing)
            @method('PUT')
        @endif

        <div class="rc-form-grid">
            <div class="rc-field rc-field-wide">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $announcement->title) }}" maxlength="150" required
                       placeholder="New route this Saturday" @error('title') aria-invalid="true" @enderror>
                @error('title') <p class="rc-error">{{ $message }}</p> @enderror
            </div>

            <div class="rc-field rc-field-wide">
                <label for="description">Message</label>
                <textarea id="description" name="description" maxlength="5000" required
                          @error('description') aria-invalid="true" @enderror>{{ old('description', $announcement->description) }}</textarea>
                <span class="rc-hint">Line breaks are kept.</span>
                @error('description') <p class="rc-error">{{ $message }}</p> @enderror
            </div>

            <div class="rc-field rc-field-wide">
                <label class="rc-choice">
                    <input type="checkbox" name="is_pinned" value="1" @checked(old('is_pinned', $announcement->is_pinned))>
                    Pin to the top of the run club page
                </label>
                @error('is_pinned') <p class="rc-error">{{ $message }}</p> @enderror
            </div>
        </div>

        @error('action') <p class="rc-error">{{ $message }}</p> @enderror

        <div class="rc-form-buttons">
            <button type="submit" name="action" value="publish" class="rc-btn rc-btn-primary">
                {{ $editing && $announcement->isPublished() ? 'Save and keep published' : 'Publish' }}
            </button>
            <button type="submit" name="action" value="draft" class="rc-btn rc-btn-outline">
                {{ $editing && $announcement->isPublished() ? 'Unpublish and save as draft' : 'Save as draft' }}
            </button>
            <a href="{{ route('running.manage.announcements.index') }}" class="rc-link">Back to announcements</a>
        </div>
    </form>
</div>

@endsection
