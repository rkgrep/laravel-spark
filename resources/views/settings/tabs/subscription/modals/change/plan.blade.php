<!-- Settings Subscription Plan Selector -> Single Plan Block -->
<label>
    <!-- Radio Button / Plan Name -->
    <input type="radio" name="plan" value="@{{ plan.id }}" v-model="forms.changePlan.plan">

    &nbsp;

    @{{ plan.name }}

            <!-- Plan Price / Interval -->
    (@{{ plan.currencySymbol }}@{{ plan.price }} / @{{ plan.interval | capitalize }})

    &nbsp;

    <!-- Plan Features Tooltip -->
    <i style="font-size: 16px;"
       class="fa fa-info-circle"
       data-toggle="tooltip"
       data-placement="right"
       title="@{{ getPlanFeaturesForTooltip(plan) }}">
    </i>
</label>
