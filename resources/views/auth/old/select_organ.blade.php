<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <title>انتخاب سالن</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <style>
        .organ-card {
            cursor: pointer;
            transition: 0.3s ease;
            border: 2px solid #ccc;
            border-radius: 15px;
        }

        .organ-card:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body class="bg-light p-4">

    <div class="container">
        <h3 class="text-center mb-5">سالن مد نظر را انتخاب کنید</h3>

        <form method="post" action="{{ route('selectOrganStore') }}">
            @csrf
            <div class="row justify-content-center g-4">
                <!-- کارت سازمان اول -->
                @foreach ($organs as $organ)
                    <div class="col-md-3">
                        <button type="submit" name="organ" value="{{ $organ->id }}"
                            class="w-100 border-0 bg-transparent">
                            <div class="p-3 organ-card text-center" data-organ="organ1">
                                <h4>{{ $organ->name }}</h4>
                            </div>
                        </button>
                    </div>
                @endforeach
            </div>
        </form>
    </div>
</body>

</html>
