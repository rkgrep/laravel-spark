<spark-settings-subscription-screen inline-template>
    <div id="spark-settings-subscription-screen">
        <div v-if="userIsLoaded && plansAreLoaded">

            <!-- Current Coupon -->
            @include('settings.tabs.subscription.coupon')

                    <!-- Subscribe -->
            @include('settings.tabs.subscription.subscribe')

                    <!-- Update Subscription -->
            @include('settings.tabs.subscription.change')

                    <!-- Update Credit Card -->
            @include('settings.tabs.subscription.card')

                    <!-- Resume Subscription -->
            @include('settings.tabs.subscription.resume')

                    <!-- Invoices -->
            @if (count($invoices) > 0)
            @include('settings.tabs.subscription.invoices.vat')

            @include('settings.tabs.subscription.invoices.history')
            @endif

                    <!-- Cancel Subscription -->
            @include('settings.tabs.subscription.cancel')
        </div>

        <!-- Change Subscription Modal -->
        @include('settings.tabs.subscription.modals.change')

                <!-- Cancel Subscription Modal -->
        @include('settings.tabs.subscription.modals.cancel')
    </div>
</spark-settings-subscription-screen>
