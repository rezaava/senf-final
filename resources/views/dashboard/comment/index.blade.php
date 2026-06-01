@extends('dashboard.layout.master')
@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
@endphp
@section('head')
    <title>دیدگاه ها</title>
@endsection

@section('main')
    <!-- main  -->
    <div class="col px-5">
        <!-- tarakonesh ha -->
        <div class="row mt-4 p-2 rounded-4 shadow bg-white">
            <div class="clearfix mt-2">
                <h3 class="float-end">دیدگاه ها</h3>
            </div>
            <div class="d-flex justify-content-center align-items-center gap-5 mt-2">
                <div class="float-start">
                    <a href="{{route('comments.organs')}}">
                        نظرات سالن ها ({{$organ_comments}})
                    </a>
                </div>
                <div class="float-end">
                    <a href="{{route('comments.services')}}">
                        نظرات خدمات ({{$service_comments}})
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
