@extends('layouts.master')
@section('title', 'Purchase Order History')
@section('content')
    <livewire:purchase-report/>

@endsection

@push('scripts')
    <script>
        $(function () {

        });
    </script>
@endpush
