@extends('layout')

@section('content')
    @livewire('admin.course-editor', ['course' => $course ?? null])
@endsection
