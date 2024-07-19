<!DOCTYPE html>
<html lang="es">

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <title>Wizardylab - Soluciones ERP y Gestor de Empresas</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('img/favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/favicons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('img/favicons/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('img/favicons/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('img/favicons/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <link rel="alternate" hreflang="es" href="https://wizardylab.com/es">
    <link rel="alternate" hreflang="en" href="https://wizardylab.com/en">

    <meta name="description" content="Wizardylab ofrece soluciones innovadoras de ERP, gestor de empresas y PIM para optimizar la gestión y productividad de su negocio.">
    <meta name="keywords" content="ERP, CRM, Gestor de Empresas, PIM, Software a medida, Desarrollo web, Desarrollo interfaz, Programador a medida, Software de Gestión, Productividad Empresarial, Soluciones Empresariales, Wizardylab, ERP Customizable, ERP Personalizable, WizardyLab ERP">
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
</head>

<body class="body-class" style=" background-repeat: no-repeat; height: 100vh">
    @include('sweetalert::alert')
    <nav class="navbar bg-transparent fixed-top">
        <div class="container-fluid p-0" id="navbar">
            <a class="navbar-brand d-flex justify-content-center align-items-center ms-3 fs-3" href="{{route('landing', $lang)}}">
                <img src="{{ asset('img/LogoLetters.png') }}" id="LogoNav" alt="Logo" width="225" height="70"
                    class="d-inline-block align-text-top">
            </a>

            <div class="d-flex flex-column justify-content-center gap-4">
                <div class="links gap-5 nav-system rubik-font d-xl-flex d-none">
                    <div class="link">
                        <a href="#sliderHome">{{__("crud.landing.home")}}</a>
                        <div class="circle"></div>
                    </div>
                    <div>
                        <a href="#services">{{__("crud.landing.services")}}</a>
                        <div class="circle"></div>
                    </div>
                    <div>
                        <a href="#about">{{__("crud.landing.about")}}</a>
                        <div class="circle"></div>
                    </div>
                    <div>
                        <a href="#contactus">{{__("crud.landing.contact_menu")}}</a>
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
                            <li><a class="dropdown-item text-capitalize" href="{{route('landing', $langl)}}"><span class="fi fi-{{$langl == "en" ? "us" : $langl}}"></span> {{$langl}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="system-nav-bar d-xl-block d-none">
                <div class="system-nav-bar-color">
                </div>
            </div>
    </nav>

    <main>
        <div class="owl-carousel owl-theme owl-drag" id="sliderHome">
            <div class="item" style="background-image: url('{{asset('img/landing/img-1.jpg')}}')">
                <a href="/erp" class="text-white">
                    <div class="container" class="text-white">
                        <div class="f3 bolder">{{__('crud.landing.slider.custom_software')}}</div>
                        <div class="f15 lighter">{{__('crud.landing.slider.custom_software_desc')}}</div>
                        <a class="boton mt-5" href="/erp">{{__('crud.landing.slider.discover_our_erp')}}</a>
                    </div>
                </a>
            </div>
            <div class="item" style="background-image: url('{{asset('img/landing/img-3.jpg')}}')">
                <div class="container text-white">
                    <div class="f3 bolder">{{__('crud.landing.slider.personalizable_software')}}</div>
                    <div class="f15 lighter">{{__('crud.landing.slider.personalizable_software_desc')}}</div>
                    <a class="boton mt-5" href="#contactus">{{__('crud.landing.slider.discover')}}</a>
                </div>
            </div>
            <div class="item" style="background-image: url('{{asset('img/landing/img-2.jpg')}}')">
                <div class="container text-white">
                    <div class="f3 bolder">{{__('crud.landing.slider.upgrade_your_business')}}</div>
                    <div class="f15 lighter">{{__('crud.landing.slider.upgrade_your_business_desc')}}</div>
                    <a class="boton mt-5" href="#contactus">{{__('crud.landing.slider.contactus')}}</a>
                </div>
            </div>
        </div>

        <div class="container services-container" id="services">
            <div class="row row-services d-flex mt-5">
                <div class="col-md-3">
                    <a href="erp" class="text-dark">
                        <img src="{{asset('img/landing/services/rrhh.svg')}}" alt="RRHH">
                        <h4 class="f15 bold nm">{{__('crud.landing.services-info.rrhh')}}</h4>
                        <p class="text-muted">{{__('crud.landing.services-info.rrhh_desc')}}</p>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="erp" class="text-dark">
                        <img src="{{asset('img/landing/services/crm.svg')}}" alt="ERP">
                        <h4 class="f15 bold nm">{{__('crud.landing.services-info.erp')}}</h4>
                        <p class="text-muted">{{__('crud.landing.services-info.erp_desc')}}</p>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="#contactus" class="text-dark">
                        <img src="{{asset('img/landing/services/rrh.svg')}}" alt="CRM">
                        <h4 class="f15 bold nm">{{__('crud.landing.services-info.crm')}}</h4>
                        <p class="text-muted">{{__('crud.landing.services-info.crm_desc')}}</p>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="#contactus" class="text-dark">
                        <img src="{{asset('img/landing/services/ecommerce.svg')}}" alt="E-commerce">
                        <h4 class="f15 bold nm">{{__('crud.landing.services-info.e-commerce')}}</h4>
                        <p class="text-muted">{{__('crud.landing.services-info.e-commerce_desc')}}</p>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="#contactus" class="text-dark">
                        <img src="{{asset('img/landing/services/personalized.svg')}}" alt="Desarrollo web">
                        <h4 class="f15 bold nm">{{__('crud.landing.services-info.custom_software')}}</h4>
                        <p class="text-muted">{{__('crud.landing.services-info.custom_software_desc')}}</p>
                    </a>
                </div>
            </div>
        </div>

        <div class="product-div mt-5" id="about">
            <div class="container about-row">
                <div class="row">
                    <div class="col-md-6 col-sm-12 py-5">
                        <h2 class="mb-3 h1">{{__('crud.landing.about-us.title')}} <b>Wizardy</b></h2>
                        <p class="text-muted">{{__('crud.landing.about-us.p-1')}}</p>

                        <p class="text-muted">{{__('crud.landing.about-us.p-2')}}</p>

                        <p class="text-muted">{{__('crud.landing.about-us.p-3')}}</p>
                    </div>
                    <div class="col-md-6 col-sm-12 text-center">
                        <img src="{{asset('img/Logo.png')}}" class="img-fluid" alt="">
                    </div>
                </div>
            </div>
        </div>

        <div class="row w-100 pb-5" id="contactus" bar-width="1250px">
            <div class="container mt-1 pt-1">
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
                                        <input type="text" autocomplete="cc-name" class="form-control" id="name" name="name"
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
                                            <input type="email" autocomplete="email" class="form-control" id="email" name="email" placeholder="{{ __('crud.landing.contact.email') }}" required>
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
                                                name="phone_number" autocomplete="tel" placeholder="{{ __("crud.landing.contact.phone_number") }}">
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
    </main>

    <div class="container">
        <footer class="row row-cols-1 row-cols-sm-2 row-cols-md-5 py-5 my-5 border-top">
          <div class="col mb-3">
            <a href="/" class="d-flex align-items-center mb-3 link-dark text-decoration-none">
                <img src="{{ asset('img/LogoLetters.png') }}" alt="logo" width="220">
            </a>
            <p class="text-muted">© {{ now()->format('Y') }} WizardyLab</p>
          </div>

          <div class="col mb-3">

          </div>

          <div class="col mb-3">
            <h5>Contact</h5>
            <ul class="nav flex-column">
              <li class="nav-item mb-2"><span class="nav-link p-0 text-muted">info@wizardylab.com</span></li>
              <li class="nav-item mb-2"><span class="nav-link p-0 text-muted"><b>+34 675 612 529</b></span></li>
              <li class="nav-item mb-2"><span class="nav-link p-0 text-muted">M-F 09:00-14:00 and 15:00-18:00 CET</span></li>
            </ul>
          </div>

          <div class="col mb-3">

          </div>

          <div class="col mb-3">
            <h5>Section</h5>
            <ul class="nav flex-column">
              <li class="nav-item mb-2"><a href="#home" class="nav-link p-0 text-muted">Home</a></li>
              <li class="nav-item mb-2"><a href="#services" class="nav-link p-0 text-muted">Services</a></li>
              <li class="nav-item mb-2"><a href="#about" class="nav-link p-0 text-muted">About</a></li>
              <li class="nav-item mb-2"><a href="#contactus" class="nav-link p-0 text-muted">Contact</a></li>
            </ul>
          </div>
        </footer>
    </div>
</body>
<script>
    $(document).ready(function() {
        $('#sliderHome').owlCarousel({
            loop:true,
            margin:10,
            nav:false,
            dots:true,
            autoplay:true,
            autoplayHoverPause:true,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1000:{
                    items:1
                }
            }
        });

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
        checkSectionInView();
        $(window).on('scroll', checkSectionInView);
    });
</script>

</html>
