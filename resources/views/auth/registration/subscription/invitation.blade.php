<!-- Invitation Loaded -->
<div class="panel panel-default" v-if="invitation">
    <div class="panel-heading">Invitation From @{{ invitation.team.owner.name }}</div>

    <div class="panel-body bg-success">
        <div>
            We found your invitation to the <strong>@{{ invitation.team.name }}</strong> team!
        </div>
    </div>
</div>

<!-- Failed To Load Invitation -->
<div class="panel panel-default" v-if="failedToLoadInvitation">
    <div class="panel-heading">Invitation</div>

    <div class="panel-body bg-danger">
        <div>
            <strong>We couldn't find this invitation. It is either expired or invalid.</strong>
        </div>
    </div>
</div>

