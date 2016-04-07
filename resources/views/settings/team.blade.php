@extends('layouts.app')

<!-- Scripts -->
@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/URI.js/1.15.2/URI.min.js"></script>
@append

<!-- Main Content -->
@section('content')
<spark-team-settings-screen :team-id="{{ $team->id }}" inline-template>
    <div id="spark-team-settings-screen" class="container spark-screen">
        <div class="row">
            <!-- Tabs -->
            <div class="col-md-4">
                <div class="panel panel-default panel-flush">
                    <div class="panel-heading" v-if="team">
                        Team Settings (@{{ team.name }})
                    </div>

                    <div class="panel-heading" v-else>
                        Loading &nbsp;&nbsp; <i class="fa fa-spinner fa-spin"></i>
                    </div>

                    <div class="panel-body">
                        <div class="spark-settings-tabs">
                            <ul class="nav spark-settings-tabs-stacked" role="tablist">
                                @foreach (Spark::teamSettingsTabs()->displayable($team, Auth::user()) as $tab)
                                    <li role="presentation"{!! $tab->key === $activeTab ? ' class="active"' : '' !!}>
                                        <a href="#{{ $tab->key }}" aria-controls="{{ $tab->key }}" role="tab" data-toggle="tab">
                                            <i class="fa fa-btn fa-fw {{ $tab->icon }}"></i>&nbsp;{{ $tab->name }}
                                        </a>
                                    </li>
                                @endforeach

                                <li role="presentation" role="tab">
                                    <a href="/settings?tab=teams">
                                        <i class="fa fa-btn fa-fw fa-search"></i>&nbsp;<strong>View All Teams</strong>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Panes -->
            <div class="col-md-8">
                <div class="tab-content">
                    @foreach (Spark::teamSettingsTabs()->displayable($team, Auth::user()) as $tab)
                        <div role="tabpanel" class="tab-pane{{ $tab->key == $activeTab ? ' active' : '' }}" id="{{ $tab->key }}">
                            @include($tab->view)
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</spark-team-settings-screen>
@endsection
