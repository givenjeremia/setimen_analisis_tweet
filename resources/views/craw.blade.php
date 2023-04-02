{{-- <!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="crawling" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Sentimen Analisis</title>
 
    </script>
    <script src="https://code.jquery.com/jquery-3.6.4.js"
        integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <style>
        #overlay {
            position: fixed;
            display: none;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 10;
        }
    </style>
</head>

<body> --}}
    @extends('layouts.app')

@section('content')
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Crawling</span>
        </div>
    </nav>
    <div class="container-fluid p-3">
      
        <form>
            @csrf
            <div class="mb-3">
                <div class="row">
                    <div class="col">
                        <label for="exampleInputEmail1" class="form-label">Query</label>
                        <input type="text" name="query" id="query" class="form-control" required>
                    </div>
                    <div class="col">
                        <label for="exampleInputEmail1" class="form-label">Count</label>
                        <input type="number" name="count" id="count" class="form-control">
                    </div>

                </div>

            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Awal</label>
                        <input type="date" class="form-control" id="sebelum" name="sebelum">
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Akhir</label>
                        <input type="date" class="form-control" id="sesudah" name="sesudah">
                    </div>
                </div>
            </div>

            <button type="button" id="btnsubmit" class="btn btn-primary">Get Sentimen</button>
        </form>
        <br>
        <div id="hasil-crawling"></div>
    </div>

  

    {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js"
        integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous">
    </script>
    <script src="{{asset('js/crawling.js')}}"></script> --}}
    @endsection
{{-- </body>

</html> --}}