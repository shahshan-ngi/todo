@extends('layouts.main')

@section('home-section')
    {!! $dataTable->table(['class' => 'table table-bordered']) !!}
@endsection
@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
