@extends('layouts.dashboard')

@section('title', 'Page Sections')

@section('content')
    @livewire(\App\Domains\Website\Livewire\SectionEditor::class, ['pageId' => request()->route('pageId')])
@endsection
