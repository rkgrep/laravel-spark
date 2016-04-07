<!-- Laravel Spark Globals -->
<script>
    window.Spark = {
        // Laravel CSRF Token
        csrfToken: '{{ csrf_token() }}',

        // Current User ID
        userId: {!! Auth::user() ? Auth::id() : 'null' !!},

        // Current Team ID
        @if (Auth::user() && Spark::usingTeams() && Auth::user()->hasTeams())
            currentTeamId: {{ Auth::user()->currentTeam->id }},
        @else
            currentTeamId: null,
        @endif

        stripeKey: '{{ config('services.stripe.key') }}'
    }
</script>
