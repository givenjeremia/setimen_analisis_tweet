@extends('layouts.app')
@section('content')
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Cek Sentimen</span>
        </div>
    </nav>
    <div class="container-fluid p-3">
      
        <form>
            @csrf
            <div class="mb-3">
                <div class="row">
                    <label for="exampleInputEmail1" class="form-label">Masukan Kalimat</label>
                    <input type="text" name="kalimat" id="kalimat" class="form-control" required>
                </div>

            </div>
          

            <button type="button" id="btnsubmitcek" class="btn text-white" style="background-color: #858F75;">Get Sentimen</button>
        </form>
        <br>
        <div id="hasil-sentimen">
            
        </div>
    </div>

    
    @endsection