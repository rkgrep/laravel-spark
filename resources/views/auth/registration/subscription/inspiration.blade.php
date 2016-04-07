<!-- Plan Has Not Been Selected -->
<div v-if="plans.length > 1 && ! forms.registration.plan">
    <div class="row spark-subscription-inspiration-single">
        Which plan is for you?
    </div>
</div>

<!-- Plan Is Selected Or There Is Only A Single Plan -->
<div class="row spark-subscription-inspiration-single" v-if="forms.registration.plan">
    Thanks for coming on board.
</div>
