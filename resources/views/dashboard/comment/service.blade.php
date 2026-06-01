@extends('dashboard.layout.master')
@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
@endphp
@section('head')
    <title>نظرات خدمات</title>
@endsection

@section('main')
    <!-- main  -->
    <div class="col px-5">
        <!-- tarakonesh ha -->
        <div class="row mt-4 p-2 rounded-4 shadow bg-white">
            <div class="clearfix mt-2">
                <h5 class="float-end">نظرات خدمات</h5>
            </div>
            <div class="table-responsive-sm">
                <table class="table mt-2 text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>کاربر</th>
                            <th>خدمت</th>
                            <th>سالن</th>
                            <th>متن دیدگاه</th>
                            <th>امتیاز</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comments as $key => $comment)
                            <tr>
                                <td class="align-middle">{{ $key + 1 }}</td>
                                <td class="align-middle">{{ $comment->user->name ?? $comment->user->mobile }}</td>
                                <td class="align-middle">{{ $comment->commentable->name }}</td>
                                <td class="align-middle">{{ $comment->commentable->organ->name }}</td>
                                <td class="align-middle">{{ $comment->text }}</td>
                                <td class="align-middle">{{ $comment->score }}</td>
                                @if ($comment->is_approved == 1)
                                    <td class="align-middle text-success">تایید شده</td>
                                @elseif ($comment->is_approved == 0)
                                    <td class="align-middle text-warning">در انتظار</td>
                                @else
                                    <td class="align-middle text-danger">رد شده</td>
                                @endif
                                <td class="align-middle">
                                    @if ($comment->is_approved != 1)
                                        <a href="{{ route('comments.approve', $comment) }}" class="text-success mx-1"><i
                                                class="fa-solid fa-check"></i> تایید</a>
                                    @endif
                                    <a href="{{ route('comments.delete', $comment) }}" class="text-danger mx-1"><i
                                            class="fa-regular fa-trash-can"></i> حذف</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
