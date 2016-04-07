@extends('layouts.app')

<!-- Scripts -->
@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/URI.js/1.15.2/URI.min.js"></script>
@endsection

<!-- Main Content -->
@section('content')
<spark-simple-registration-screen inline-template>
    <div id="spark-register-screen" class="container-fluid spark-screen">
        <!-- Invitation -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @include('auth.registration.subscription.invitation')
            </div>
        </div>

        <!-- Basic Information -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @include('auth.registration.simple.basics')
            </div>
        </div>
    </div>
</spark-simple-registration-screen>
@endsection
