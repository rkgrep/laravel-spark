@extends('layouts.app')

<!-- Scripts -->
@section('scripts')
    <script src="https://js.stripe.com/v2/"></script>
@append

<!-- Main Content -->
@section('content')
<!-- Your Settings Dashboard -->
<spark-settings-screen inline-template>
    <div id="spark-settings-screen" class="container spark-screen">
        <div class="row">
            <!-- Tabs -->
            <div class="col-md-4">
                <div class="panel panel-default panel-flush">
                    <div class="panel-heading">
                        Settings
                    </div>

                    <div class="panel-body">
                        <div class="spark-settings-tabs">
                            <ul class="nav spark-settings-tabs-stacked" role="tablist">
                                @foreach (Spark::settingsTabs()->displayable() as $tab)
                                    <li role="presentation"{!! $tab->key === $activeTab ? ' class="active"' : '' !!}>
                                        <a href="#{{ $tab->key }}" aria-controls="{{ $tab->key }}" role="tab" data-toggle="tab">
                                            <i class="fa fa-btn fa-fw {{ $tab->icon }}"></i>&nbsp;{{ $tab->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Panes -->
            <div class="col-md-8">
                <div class="tab-content">
                    @foreach (Spark::settingsTabs()->displayable() as $tab)
                        <div role="tabpanel" class="tab-pane{{ $tab->key == $activeTab ? ' active' : '' }}" id="{{ $tab->key }}">
                            @include($tab->view)
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</spark-settings-screen>
@endsection
