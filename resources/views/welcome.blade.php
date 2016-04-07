<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css' rel='stylesheet'
          type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lato:100,300,400,700' rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="{{ elixir('css/app.css') }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<!-- Main Content -->
<div class="container spark-splash-screen">
    <!-- Branding / Navigation -->
    <div class="row splash-nav">
        <div class="col-md-10 col-md-offset-1">
            <div class="pull-left splash-brand">
                <i class="fa fa-btn fa-sun-o"></i>Laravel Spark
            </div>

            <div class="navbar-header">
                <button type="button" class="splash-nav-toggle navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#primary-nav" aria-expanded="false" aria-controls="primary-nav">
                    <span class="sr-only">Toggle navigation</span>
                    MENU
                </button>
            </div>

            <div id="primary-nav" class="navbar-collapse collapse splash-nav-list">
                <ul class="nav navbar-nav navbar-right inline-list">
                    <!--
                        <li class="splash-nav-link active"><a href="/features">Features</a></li>
                        <li class="splash-nav-link"><a href="/support">Support</a></li>
                    -->
                    @if(Auth::guest())
                        <li class="splash-nav-link splash-nav-link-highlight"><a href="/login">Login</a></li>
                        <li class="splash-nav-link splash-nav-link-highlight-border"><a href="/register">Register</a></li>
                    @else
                        <li class="splash-nav-link splash-nav-link-highlight-border"><a href="/home">Dashboard</a></li>
                    @endif
                </ul>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>

    <!-- Inspiration -->
    <div class="row splash-inspiration-row">
        <div class="col-md-4 col-md-offset-1">
            <div id="splash-inspiration-heading">
                Create Amazing Experiences.
            </div>

            <div id="splash-inspiration-text">
                Spark provides the perfect starting point for your next big idea. Forget all the
                boilerplate and focus on what matters: your application.
            </div>
        </div>

        <!-- Browser Window -->
        <div class="col-md-6" class="splash-browser-window-container">
            <div class="splash-browser-window">
                <div class="splash-browser-dots-container">
                    <ul class="list-inline splash-browser-dots">
                        <li><i class="fa fa-circle red"></i></li>
                        <li><i class="fa fa-circle yellow"></i></li>
                        <li><i class="fa fa-circle green"></i></li>
                    </ul>
                </div>
                <div>
                    <img src="https://placehold.co/550x400" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>

    <!-- Features Heading -->
    <div class="row">
        <div class="col-md-10 col-md-offset-1 splash-row-heading">
            Features You'll Adore
        </div>
    </div>

    <!-- Feature Icons -->
    <div class="row splash-features-icon-row">
        <div class="col-md-10 col-md-offset-1 text-center">
            <div class="col-md-4 splash-features-feature">
                <div class="splash-feature-icon">
                    <i class="fa fa-lock"></i>
                </div>

                <div class="splash-feature-heading">
                    Authentication Included
                </div>

                <div class="splash-feature-text">
                    No sweat authentication. Authentication and registration views are included out of the box.
                </div>
            </div>

            <div class="col-md-4 splash-features-feature">
                <div class="splash-feature-icon">
                    <i class="fa fa-money"></i>
                </div>

                <div class="splash-feature-heading">
                    Painless Subscriptions
                </div>

                <div class="splash-feature-text">
                    Just define subscriptions and promotions for your application and Spark will do the rest.
                </div>
            </div>

            <div class="col-md-4 splash-features-feature">
                <div class="splash-feature-icon">
                    <i class="fa fa-phone"></i>
                </div>

                <div class="splash-feature-heading">
                    Two-Factor Security
                </div>

                <div class="splash-feature-text">
                    Enable two-factor authentication with a single line of code. It really doesn't get any easier.
                </div>
            </div>
        </div>
    </div>

    <!-- Feature Icons -->
    <div class="row splash-features-icon-row">
        <div class="col-md-10 col-md-offset-1 text-center">
            <div class="col-md-4 splash-features-feature">
                <div class="splash-feature-icon">
                    <i class="fa fa-users"></i>
                </div>

                <div class="splash-feature-heading">
                    Join Your Team
                </div>

                <div class="splash-feature-text">
                    Spark provides simple, built-in support for creating and managing teams and invitations.
                </div>
            </div>

            <div class="col-md-4 splash-features-feature">
                <div class="splash-feature-icon">
                    <i class="fa fa-cubes"></i>
                </div>

                <div class="splash-feature-heading">
                    Make It Your Own
                </div>

                <div class="splash-feature-text">
                    Customize or replace any of the views provided with Spark and make the design your own.
                </div>
            </div>

            <div class="col-md-4 splash-features-feature">
                <div class="splash-feature-icon">
                    <i class="fa fa-clock-o"></i>
                </div>

                <div class="splash-feature-heading">
                    Save Weeks Of Work
                </div>

                <div class="splash-feature-text">
                    Get started writing what's unique: your application. Don't worry about subscription and
                    authentication logic.
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Variables -->
    <?php $plans = Spark::plans()->monthly()->active(); ?>

    <?php
    switch (count($plans)) {
        case 0:
        case 1:
            $columns = 'col-md-6 col-md-offset-3';
            break;
        case 2:
            $columns = 'col-md-6';
            break;
        case 3:
            $columns = 'col-md-4';
            break;
        case 4:
            $columns = 'col-md-3';
            break;
        default:
            throw new Exception("Unsupported number of plans. Please customize view.");
    }
    ?>

            <!-- Pricing Heading -->
    @if (count($plans) > 0)
        <div class="row">
            <div class="col-md-10 col-md-offset-1 splash-row-heading">
                Simple Pricing
            </div>
        </div>

        <!-- Pricing Table -->
        <div class="row splash-pricing-table-row text-center">
            <div class="col-md-10 col-md-offset-1">
                @foreach ($plans as $plan)
                    @if ($plan->isActive())
                        <div class="{{ $columns }}">
                            <div class="panel panel-default spark-plan-{{ $plan->id }}">
                                <div class="panel-heading splash-plan-heading">
                                    {{ $plan->name }}
                                </div>

                                <div class="panel-body">
                                    <ul class="splash-plan-feature-list">
                                        @foreach ($plan->features as $feature)
                                            <li>{{ $feature }}</li>
                                        @endforeach
                                    </ul>

                                    <hr>

                                    <div class="splash-plan-price">
                                        {{ $plan->currencySymbol }}{{ $plan->price }}
                                    </div>

                                    <div class="splash-plan-interval">
                                        {{ $plan->interval }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Call To Action Button -->
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <a href="/register">
                    <button class="btn btn-primary splash-get-started-btn">
                        Get Started!
                    </button>
                </a>
            </div>
        </div>
        @endif

                <!-- Customers Heading -->
        <div class="row">
            <div class="col-md-10 col-md-offset-1 splash-row-heading">
                What Our Customers Say
            </div>
        </div>

        <!-- Customer Testimonials -->
        <div class="row splash-customer-row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <div class="col-md-4 splash-customer">
                    <div class="splash-customer-avatar">
                        <img src="https://s3.amazonaws.com/uifaces/faces/twitter/msurguy/128.jpg">
                    </div>

                    <div class="splash-customer-quote">
                        This is an inspiring testimonial about your application.
                    </div>

                    <div class="splash-customer-identity">
                        <div class="splash-customer-name">Maksim Surguy</div>
                        <div class="splash-customer-title">CEO, Company</div>
                    </div>
                </div>

                <div class="col-md-4 splash-customer">
                    <div class="splash-customer-avatar">
                        <img src="https://s3.amazonaws.com/uifaces/faces/twitter/allisongrayce/128.jpg">
                    </div>

                    <div class="splash-customer-quote">
                        This is an inspiring testimonial about your application.
                    </div>

                    <div class="splash-customer-identity">
                        <div class="splash-customer-name">Allison Grayce</div>
                        <div class="splash-customer-title">CEO, Company</div>
                    </div>
                </div>

                <div class="col-md-4 splash-customer">
                    <div class="splash-customer-avatar">
                        <img src="https://s3.amazonaws.com/uifaces/faces/twitter/richcsmith/128.jpg">
                    </div>

                    <div class="splash-customer-quote">
                        This is an inspiring testimonial about your application.
                    </div>

                    <div class="splash-customer-identity">
                        <div class="splash-customer-name">Rich Smith</div>
                        <div class="splash-customer-title">CEO, Company</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="row">
            <!-- Company Information -->
            <div class="col-md-10 col-md-offset-1 splash-footer">
                <div class="pull-left splash-footer-company">
                    Copyright © {{ Spark::company() }} - <a href="/terms">Terms Of Service</a>
                </div>

                <!-- Social Icons -->
                <div class="pull-right splash-footer-social-icons">
                    <a href="http://facebook.com">
                        <i class="fa fa-btn fa-facebook-square"></i>
                    </a>
                    <a href="http://twitter.com">
                        <i class="fa fa-btn fa-twitter-square"></i>
                    </a>
                    <a href="http://github.com">
                        <i class="fa fa-github-square"></i>
                    </a>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
</div>

<!-- Footer Scripts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
