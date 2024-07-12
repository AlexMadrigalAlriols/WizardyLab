<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css" />
    <title>{{ trans('global.site_title') }}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('img/favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/favicons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('img/favicons/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('img/favicons/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('img/favicons/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <meta name="description" content="Wizardylab ofrece soluciones innovadoras de ERP, gestor de empresas y PIM para optimizar la gestión y productividad de su negocio.">
    <meta name="keywords" content="ERP, CRM, Gestor de Empresas, PIM, Software de Gestión, Productividad Empresarial, Soluciones Empresariales, Wizardylab">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Wizardylab">
    <meta property="og:title" content="Wizardylab - Soluciones ERP, Gestor de Empresas y PIM">
    <meta property="og:description" content="Descubra cómo Wizardylab puede mejorar la eficiencia y gestión de su negocio con nuestras soluciones ERP, gestor de empresas y PIM.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.wizardylab.com">
    <meta property="og:image" content="{{asset('img/favicons/favicon-32x32.png')}}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Wizardylab - Soluciones ERP, Gestor de Empresas y PIM">
    <meta name="twitter:description" content="Optimice la gestión de su negocio con las soluciones de ERP, gestor de empresas y PIM de Wizardylab.">
</head>

<body class="body-class" style=" background-repeat: no-repeat; height: 100vh">
    @include('sweetalert::alert')
    <nav class="navbar bg-transparent fixed-top">
        <div class="container-fluid p-0" id="navbar">
            <a class="navbar-brand d-flex justify-content-center align-items-center ms-3 fs-3" href="#">
                <img src="{{ asset('img/LogoLetters.png') }}" id="LogoNav" alt="Logo" width="225" height="70"
                    class="d-inline-block align-text-top">
            </a>

            <div class="d-flex flex-column justify-content-center gap-4">
                <div class="links gap-5 nav-system rubik-font d-xl-flex d-none">
                    <div class="link">
                        <a class="" href="#home">{{__("crud.landing.home")}}</a>
                        <div class="circle"></div>
                    </div>
                    <div>
                        <a href="#product">{{__("crud.landing.product")}}</a>
                        <div class="circle"></div>
                    </div>
                    <div>
                        <a href="#pricing">{{__("crud.landing.pricing")}}</a>
                        <div class="circle"></div>
                    </div>
                    <div>
                        <a href="#faq">{{__("crud.landing.faq")}}</a>
                        <div class="circle"></div>
                    </div>
                    <div>
                        <a href="#contact">{{__("crud.landing.contact_menu")}}</a>
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <div class="buttons d-flex gap-3 me-5">
                <div class="dropdown">
                    <button class="dropdown-toggle text-capitalize" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="fi fi-{{$lang == "en"?"us":$lang}}"></span> {{$lang}}
                    </button>
                    <ul class="dropdown-menu">
                        @foreach ($langList as $langl)
                            <li><a class="dropdown-item text-capitalize" href="?lang={{$langl}}"><span class="fi fi-{{$langl == "en"?"us":$langl}}"></span> {{$langl}}</a></li>
                        @endforeach

                    </ul>
                </div>
            </div>

            <div class="system-nav-bar d-xl-block d-none">
                <div class="system-nav-bar-color">
                </div>
            </div>
    </nav>

    <div class="body d-flex flex-column align-items-center justify-content-start rubik-font">
        <div class="row div-1 mb-5 section" id="home" bar-width="620px">
            <div class="col-12 col-xl-6 d-flex justify-content-center align-items-center flex-column align-items-xl-start mt-20">
                <div class="p-4">
                    <h1 class="fs-2 d-none d-xl-block rubik-font"><span class="title-color">{{ __('crud.landing.seamless') }} </span>{{ __('crud.landing.integration') }}</h1>
                    <h1 class="fs-4 d-xl-none rubik-font"><span class="title-color">{{ __('crud.landing.seamless') }} </span>{{ __('crud.landing.integration') }}</h1>
                    <div class="fs-1 d-none d-xl-block" style="text-wrap:wrap">{{ __('crud.landing.elevate_enterprise') }}</div>
                    <div class="fs-3 d-xl-none text-center" style="text-wrap:wrap">{{ __('crud.landing.elevate_enterprise') }}</div>
                    <p class="rubik-font mt-2 text-wrap">{{ __('crud.landing.digital_reports') }}</p>
                </div>
                <div class="buttons d-flex gap-3 me-3">
                    <a href="#contact" class="ctm-button-secondary rubik-font">{{ __('crud.landing.request_demo') }}</a>
                    <a href="#contact" class="ctm-button rubik-font">{{ __('crud.landing.try_14_days') }}</a>
                </div>
            </div>
            <div class="col-12 col-xl-6 col-img">
                <img class="shadow-lg mt-5 img" src="{{ asset('img/dashboard-1.png') }}" alt="dashboard photo" width="1000px"
                    class="mt-5">
            </div>
        </div>
    </div>


        <div class="row product-div section d-flex flex-column align-items-center" id="product" bar-width="780px">

            <div class="row w-75 d-flex justify-content-center align-items-center product">
                <div class="col-12 col-xl-6 d-flex flex-column justify-content-start align-items-center gap-4 h-50 align-items-xl-start sel-pad">
                    <div class="title fs-2  ">Mobile Time tracking</div>
                    <div class="funcionality fs-4">Time tracking can be easily recorded using a mobile app. You get an up-to-date overview and  the managers are able to approve people's time.</div>
                    <class class="row w-100 d-flex justify-content-left align-items-start mt-5" style="transform: translateX(25px);">
                        <div class="col-md-6 d-flex align-items-start flex-column h-100">
                            <div class="row d-flex gap-1 w-75 d-flex justify-content-start">
                                    <div class="fs-5" style="font-weight: 600; position: relative;"> <i class='bx bx-time-five fs-5' style="position:absolute; top:5px; left:-25px"></i> Start - Stop</div>
                                    <p class="text-muted">Starting and ending the work activity measurement.</p>
                            </div>
                            <div class="row d-flex gap-1 w-75">
                                <div class="fs-5" style="font-weight: 600; position: relative;"> <i class='bx bx-time-five fs-5' style="position:absolute; top:5px; left:-25px"></i> Quick overview</div>
                                <p class="text-muted">Employees track their working time.</p>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-start flex-column">
                            <div class="row d-flex gap-1 w-75 d-flex justify-content-start">
                                <div class="fs-5" style="font-weight: 600; position: relative;"> <i class='bx bx-time-five fs-5' style="position:absolute; top:5px; left:-25px"></i> Tasks</div>
                                <p class="text-muted">Check your current tasks and add hours to it.</p>
                            </div>
                            <div class="row d-flex gap-1 w-75 d-flex justify-content-end">
                                <div class="fs-5" style="font-weight: 600; position: relative;"> <i class='bx bx-time-five fs-5' style="position:absolute; top:5px; left:-25px"></i> Tasks Configuration</div>
                                <p class="text-muted">Add, edit and comment on tasks just like in the web format.</p>
                            </div>
                        </div>
                    </class>
                </div>
                <div class="col-12 col-xl-6 d-flex justify-content-center">
                    <img class=" h-50 d-flex justify-content-start align-items-center"
                        id="" src="{{ asset('img/movile.png') }}" alt="" width="250px">
                </div>
            </div>

                <div class="separator"></div>

            <div class="row w-100 d-flex justify-content-center align-items-center product">
                <div class="col-12 col-xl-6 d-flex flex-column justify-content-start align-items-center gap-4 h-50 align-items-xl-start sel-pad">
                    <div class="title fs-5">{{ __('crud.landing.clients.title') }}</div>
                    <div class="funcionality fs-2">{{ __('crud.landing.clients.funcionality') }}</div>
                    <div class="selectors" id="selector-1">
                        <div class="selector" image="{{ asset('img/clients-img.png') }}">
                            <div>{{ __('crud.landing.clients.selector_1') }}</div>
                        </div>
                        <div class="ctm-card">
                            <div><b>{{ __('crud.landing.clients.selector_1') }}</b></div>
                            {{ __('crud.landing.clients.selector_1_desc') }}
                        </div>
                        <div class="selector" image="{{ asset('img/companies-img.png') }}">
                            <div>{{ __('crud.landing.clients.selector_2') }}</div>
                        </div>
                        <div class="ctm-card">
                            <div><b>{{ __('crud.landing.clients.selector_2') }}</b></div>
                            {{ __('crud.landing.clients.selector_2_desc') }}
                        </div>
                        <div class="selector" image="{{ asset('img/invoices-img.png') }}">
                            <div>{{ __('crud.landing.clients.selector_3') }}</div>
                        </div>
                        <div class="ctm-card">
                            <div><b>{{ __('crud.landing.clients.selector_3') }}</b></div>
                            {{ __('crud.landing.clients.selector_3_desc') }}
                            <ul>
                                <li>{{ __('crud.landing.clients.selector_3_ul_1') }}</li>
                                <li>{{ __('crud.landing.clients.selector_3_ul_2') }}</li>
                                <li>{{ __('crud.landing.clients.selector_3_ul_3') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-6 d-flex justify-content-center">
                    <img class="img-border shadow-lg h-50 d-flex justify-content-start align-items-center"
                        id="selector-1-img" src="{{ asset('img/clients-img.png') }}" alt="" width="900px">
                </div>
            </div>

            <div class="separator"></div>

            <div class="row w-100 d-flex flex-column-reverse gap-5 gap-xl-0 flex-xl-row justify-content-center align-items-center product" id="p2">
                <div class="col-12 col-xl-6 d-flex justify-content-center">
                    <img class="img-border shadow-lg h-50 d-flex justify-content-start align-items-center"
                        id="selector-2-img" src="{{ asset('img/leaves-img.png') }}" alt="" width="900px">
                </div>
                <div class="col-12 col-xl-6 d-flex flex-column justify-content-start align-items-center gap-4 h-50 align-items-xl-start sel-pad">
                    <div class="title fs-5">{{ __("crud.landing.hr.title") }}</div>
                    <div class="funcionality fs-2">{{ __("crud.landing.hr.funcionality") }}</div>
                    <div class="selectors" id="selector-2">
                        <div class="selector" image="{{ asset('img/leaves-img.png') }}">
                            <div>{{ __("crud.landing.hr.selector_1") }}</div>
                        </div>
                        <div class="ctm-card">
                            <div><b>{{ __("crud.landing.hr.selector_1") }}</b></div>
                            {{ __("crud.landing.hr.selector_1_desc") }}
                        </div>
                        <div class="selector" image="{{ asset('img/attendances-img.png') }}">
                            <div>{{ __("crud.landing.hr.selector_2") }}</div>
                        </div>
                        <div class="ctm-card">
                            <div><b>{{ __("crud.landing.hr.selector_2") }}</b></div>
                            {{ __("crud.landing.hr.selector_2_desc") }}
                        </div>
                        <div class="selector" image="{{ asset('img/users-img.png') }}">
                            <div>{{ __("crud.landing.hr.selector_3") }}</div>
                        </div>
                        <div class="ctm-card">
                            <div><b>{{ __("crud.landing.hr.selector_3") }}</b></div>
                            {{ __("crud.landing.hr.selector_3_desc") }}
                            <ul>
                                <li>{{ __("crud.landing.hr.selector_3_ul_1") }}</li>
                                <li>{{ __("crud.landing.hr.selector_3_ul_2") }}</li>
                                <li>{{ __("crud.landing.hr.selector_3_ul_3") }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="separator"></div>

            <div class="row w-100 d-flex justify-content-center align-items-center product" id="3">
                <div class="col-12 col-xl-6 d-flex flex-column justify-content-start align-items-center gap-4 h-50 align-items-xl-start sel-pad">
                    <div class="title fs-5">{{ __("crud.landing.working.title") }}</div>
                    <div class="funcionality fs-2">{{ __("crud.landing.working.funcionality") }}</div>
                    <div class="selectors" id="selector-3">
                        <div class="selector" image="{{ asset('img/proyects-img.png') }}">
                            <div>{{ __("crud.landing.working.selector_3_1") }}</div>
                        </div>
                        <div class="ctm-card">
                            <div><b>{{ __("crud.landing.working.selector_3_1") }}</b></div>
                            {{ __("crud.landing.working.selector_3_1_desc") }}
                            <ul>
                                <li>{{ __("crud.landing.working.selector_3_1_ul_1") }}</li>
                                <li>{{ __("crud.landing.working.selector_3_1_ul_2") }}</li>
                                <li>{{ __("crud.landing.working.selector_3_1_ul_3") }}</li>
                            </ul>
                        </div>
                        <div class="selector" image="{{ asset('img/tasks-img.png') }}">
                            <div>{{ __("crud.landing.working.selector_3_2") }}</div>
                        </div>
                        <div class="ctm-card">
                            <div><b>{{ __("crud.landing.working.selector_3_2") }}</b></div>
                            <ul>
                                <li>{{ __("crud.landing.working.selector_3_2_ul_1") }}</li>
                                <li>{{ __("crud.landing.working.selector_3_2_ul_2") }}</li>
                                <li>{{ __("crud.landing.working.selector_3_2_ul_3") }}</li>
                            </ul>
                        </div>
                        <div class="selector" image="{{ asset('img/canva-img.png') }}">
                            <div>{{ __("crud.landing.working.selector_3_3") }}</div>
                        </div>
                        <div class="ctm-card">
                            <div><b>{{ __("crud.landing.working.selector_3_3") }}</b></div>
                            {{ __("crud.landing.working.selector_3_3_desc") }}
                        </div>
                        <div class="selector" image="{{ asset('img/rules-img.png') }}">
                            <div>{{ __("crud.landing.working.selector_3_4") }}</div>
                        </div>
                        <div class="ctm-card">
                            <div><b>{{ __("crud.landing.working.selector_3_4") }}</b></div>
                            {{ __("crud.landing.working.selector_3_4_desc") }}
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-6 d-flex justify-content-center">
                    <img class="img-border shadow-lg h-50 d-flex justify-content-start align-items-center"
                        id="selector-3-img" src="{{ asset('img/proyects-img.png') }}" alt=""
                        width="900px">
                </div>
            </div>

            <div class="separator"></div>

            <div class="row w-100 d-flex justify-content-center align-items-center product flex-column-reverse gap-5 gap-xl-0 flex-xl-row" id="5">
                <div class="col-12 col-xl-6 d-flex justify-content-center">
                    <img class="img-border shadow-lg h-50 d-flex justify-content-start align-items-center"
                        id="selector-5-img" src="{{ asset('img/items-img.png') }}" alt=""
                        width="900px">
                </div>
                <div class="col-12 col-xl-6 d-flex flex-column justify-content-start align-items-center gap-4 h-50 align-items-xl-start sel-pad">
                    <div class="title fs-5">{{ __("crud.landing.inventory.title") }}</div>
                    <div class="funcionality fs-2">{{ __("crud.landing.inventory.funcionality") }}</div>
                    <div class="selectors" id="selector-5">
                        <div class="selector" image="{{ asset('img/items-img.png') }}">
                            <div>{{ __("crud.landing.inventory.selector_5_1") }}</div>
                        </div>
                        <div class="ctm-card">
                            <div><b>{{ __("crud.landing.inventory.selector_5_1") }}</b></div>
                            <ul>
                                <li>{{ __("crud.landing.inventory.selector_5_1_ul_1") }}</li>
                                <li>{{ __("crud.landing.inventory.selector_5_1_ul_2") }}</li>
                            </ul>
                        </div>
                        <div class="selector" image="{{ asset('img/assignments-img.png') }}">
                            <div>{{ __("crud.landing.inventory.selector_5_2") }}</div>
                        </div>
                        <div class="ctm-card">
                            <div><b>{{ __("crud.landing.inventory.selector_5_2") }}</b></div>
                            {{ __("crud.landing.inventory.selector_5_2_desc") }}
                        </div>
                        <div class="selector" image="{{ asset('img/expenses-img.png') }}">
                            <div>{{ __("crud.landing.inventory.selector_5_3") }}</div>
                        </div>
                        <div class="ctm-card">
                            <div><b>{{ __("crud.landing.inventory.selector_5_3") }}</b></div>
                            {{ __("crud.landing.inventory.selector_5_3_desc") }}
                        </div>
                    </div>
                </div>

            </div>


        </div>
        <div class="row section d-flex flex-column align-items-center" id="pricing" bar-width="950px">
            <section id="pricing" class="pricing-content section-padding">
                <div class="container">
                    <div class="section-title text-center">
                        <h2>{{__("crud.landing.pricing-f.title")}}</h2>
                        <p>{{__("crud.landing.pricing-f.description")}}</p>
                    </div>
                    <div class="row text-center d-flex justify-content-center align-items-center gap-3 gap-xl-0">
                        <div class="col-lg-4 col-sm-6 col-xs-12 wow fadeInUp p-0" data-wow-duration="1s"
                            data-wow-delay="0.1s" data-wow-offset="0"
                            style="visibility: visible; animation-duration: 1s; animation-delay: 0.1s; animation-name: fadeInUp;">
                            <div class="pricing_design">
                                <div class="single-pricing">
                                    <div class="price-head">
                                        <h2>BASIC</h2>
                                        <span class="h1">80€</span><br>
                                        <span>/{{trans('plans.monthly')}}</span>
                                    </div>
                                    <ul class="ps-0">
                                        <li>{!! trans('plans.employee_accounts', ['count' => 15]) !!}</li>
                                        <li>{!! trans('plans.disk_space', ['size' => '10gb']) !!}</li>
                                        <li>{!! trans('plans.onboarding') !!}</li>
                                        <li>{!! trans('plans.access_to_all_basic_modules') !!}</li>
                                        <li>{!! trans('plans.unlimited_support') !!}</li>
                                    </ul>
                                    <div class="pricing-price mt-auto">
                                        <a href="#contact" class="price_btn">START TRIAL</a>
                                    </div>
                                </div>
                            </div>
                        </div><!--- END COL -->
                        <div class="col-lg-4 col-sm-6 col-xs-12 wow fadeInUp p-0" data-wow-duration="1s"
                            data-wow-delay="0.2s" data-wow-offset="0"
                            style="visibility: visible; animation-duration: 1s; animation-delay: 0.2s; animation-name: fadeInUp;">
                            <div class="pricing_design">
                                <div class="pricing-badge">
                                    <span class="badge">Popular</span>
                                </div>
                                <div class="single-pricing">
                                    <div    >
                                        <div class="price-head">
                                            <h2>PROFESSIONAL</h2>
                                            <span class="original-price">140€</span>
                                            <span class="h1">120€</span><br>
                                            <span>/{{trans('plans.monthly')}}</span>
                                        </div>
                                        <ul class="ps-0">
                                            <li>{!! trans('plans.employee_accounts', ['count' => 60]) !!}</li>
                                            <li>{!! trans('plans.disk_space', ['size' => '30gb']) !!}</li>
                                            <li>{!! trans('plans.onboarding') !!}</li>
                                            <li>{!! trans('plans.access_to_all_basic_modules') !!}</li>
                                            <li>{!! trans('plans.custom_migration') !!}</li>
                                            <li>{!! trans('plans.unlimited_support') !!}</li>
                                        </ul>
                                    </div>
                                    <div class="pricing-price mt-auto">
                                        <a href="#contact" class="price_btn">CONTACT US</a>
                                    </div>
                                </div>
                            </div>
                        </div><!--- END COL -->
                        <div class="col-lg-4 col-sm-6 col-xs-12 wow fadeInUp p-0" data-wow-duration="1s"
                            data-wow-delay="0.3s" data-wow-offset="0"
                            style="visibility: visible; animation-duration: 1s; animation-delay: 0.3s; animation-name: fadeInUp;">
                            <div class="pricing_design">

                                <div class="single-pricing">
                                    <div class="price-head">
                                        <h2>ENTERPRISE</h2>
                                        <span class="original-price">200€</span>
                                        <span class="h1">160€</span><br>
                                        <span>/{{trans('plans.monthly')}}</span>
                                    </div>
                                    <ul class="ps-0">
                                        <li>{!! trans('plans.employee_accounts', ['count' => 'Unlimited']) !!}</li>
                                        <li>{!! trans('plans.disk_space', ['size' => '100gb']) !!}</li>
                                        <li>{!! trans('plans.onboarding') !!}</li>
                                        <li>{!! trans('plans.access_to_all_basic_modules') !!}</li>
                                        <li>{!! trans('plans.custom_migration') !!}</li>
                                        <li>{!! trans('plans.hiring_module') !!}</li>
                                        <li>{!! trans('plans.route_module') !!}</li>
                                        <li>{!! trans('plans.custom_modules') !!}</li>
                                        <li>{!! trans('plans.unlimited_support') !!}</li>
                                    </ul>
                                    <div class="pricing-price mt-auto">
                                        <a href="#contact" class="price_btn">CONTACT US</a>
                                    </div>
                                </div>
                            </div>
                        </div><!--- END COL -->
                    </div><!--- END ROW -->
                </div><!--- END CONTAINER -->
            </section>
        </div>
        <div class="row section" id="faq" bar-width="1090px">
            <h2 class="text-center mb-5">{{__("crud.landing.faq_title")}}</h2>
            <div class="accordion" id="accordionExample">
                @foreach ($faqs[ $lang ] as $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $loop->iteration }}" aria-expanded="true"
                                aria-controls="collapse{{ $loop->iteration }}">
                                {{ $faq['question'] }}
                            </button>
                        </h2>
                        <div id="collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {{ $faq['answer'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="row section w-100 pb-5" id="contact" bar-width="1250px">
            <div class="container mt-5 pt-5">
                <div class="row justify-content-md-center">
                    <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6">
                        <h2 class="mb-4 display-5 text-center">{{ __("crud.landing.contact.title") }}</h2>
                        <p class="text-secondary mb-5 text-center">{{ __("crud.landing.contact.description") }}</p>
                        <hr class="w-50 mx-auto mb-5 mb-xl-9 border-dark-subtle">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-9">
                        <div class="bg-white border rounded shadow-sm overflow-hidden">

                            <form action="{{ route('landing.store') }}" method="POST">
                                @csrf
                                <div class="row gy-4 gy-xl-5 p-4 p-xl-5">
                                    <div class="col-12">
                                        <label for="name" class="form-label">{{ __("crud.landing.contact.full_name") }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="{{ __("crud.landing.contact.full_name") }}" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="email" class="form-label">{{ __("crud.landing.contact.email") }} <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group pe-lg-5">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                                    <path
                                                        d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z" />
                                                </svg>
                                            </span>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('crud.landing.contact.email') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="phone_number" class="form-label">{{ __("crud.landing.contact.phone_number") }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                                                    <path
                                                        d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
                                                </svg>
                                            </span>
                                            <input type="tel" class="form-control" id="phone_number"
                                                name="phone_number" placeholder="{{ __("crud.landing.contact.phone_number") }}">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="message" class="form-label">{{ __("crud.landing.contact.message") }} <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" id="message" name="message" rows="3" placeholder="{{ __("crud.landing.contact.message") }}" required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn btn-lg" type="submit"
                                                style="background-color:#374df1; color:white"><i class='bx bx-mail-send me-1 mt-1' ></i> {{ __("crud.landing.contact.submit_button") }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
<script>
    $(document).ready(function() {

        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('#navbar').addClass('blur');
            } else {
                $('#navbar').removeClass('blur');
            }
        });
        // Función para comprobar qué sección está visible
        function checkSectionInView() {
            var scrollPos = $(window).scrollTop();
            var windowHeight = $(window).height();

            $('.section').each(function() {
                var sectionTop = $(this).offset().top;
                var sectionBottom = sectionTop + $(this).outerHeight();

                if (scrollPos >= sectionTop-100 && scrollPos < sectionBottom-100) {
                    // Esta sección está en vista
                    var actualindex = $('a[href="#' + $(this).attr('id') + '"]')
                    var indexposition = actualindex.next().offset();
                    var actualindex = $('.links a').index(actualindex);
                    var actualcolor = $(this).attr('color-bar');
                    var actualwidth = $(this).attr('bar-width');
                    var bgcolor = $(this).attr('bg-color');

                    $('.links a').each(function() {
                        if ($('.links a').index($(this)) > actualindex) {
                            $(this).next().css("border-color", "#cfdbc8")
                            return false;
                        }
                        $(this).next().css("border-color", "#374df1")
                        $('.system-nav-bar-color').css('width', indexposition.left)

                    })
                    $('.system-nav-bar-color').css("background-color", $(this).attr('color-bar'))

                }
            });
        }

        $('.selector').on('click', function() {
            $(".ctm-card").each(function() {
                $(this).css('display', "none")
            });
            $(".selector").each(function() {
                $(this).css('display', "flex")
            });
            $(this).next('.ctm-card').css('display', 'flex').hide().slideDown();
            $(this).css("display", "none");
            $('#' + $(this).parent().attr('id') + '-img').fadeOut("fast")
            var image = $(this).attr("image")
            $('#' + $(this).parent().attr('id') + '-img').fadeOut("fast", function() {
                $(this).attr("src", image).fadeIn("fast")
            })
        })
        checkSectionInView();
        $(window).on('scroll', checkSectionInView);
    });
</script>

</html>
