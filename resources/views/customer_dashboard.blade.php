
@extends('layouts.app')
@section('content')

    <div class="main-content mt-0 hor-content">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1 class="page-title">Welcome back, {{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</h1>
                    
                </div>


                <div class="side-app">

            <!-- CONTAINER -->
            

                
            
        </div>

            </div>
            <!-- CONTAINER END -->
        </div>
    </div>

@endsection
