@php
    $webColors = App\Helper\Helper::getWebColor();
@endphp
<style>
    :root {
        --color-primary: {{ $webColors->primary_color ?? '#8a2be2' }}; /* Default to #8a2be2 if not found */
        --color-secondary: {{ $webColors->secondary_color ?? '#9370db' }}; /* Default to #9370db if not found */
    }

    .page-list li a {
        color: #ffffff;
    }

    .page-list li.active {
        color: #ffffff;
        opacity: 0.8;
    }

    .page-list li a:hover {
        color: #ffffff;
    }

    .rbt-accordion-style.rbt-accordion-04 .card {
        border: 2px solid #f38b051f;
    }

    .rbt-accordion-style.rbt-accordion-04 .card .card-body {
        border-top: 2px solid #f38b051f;
    }

    body {
        color: #000000c9;
    }

    .page-list li .icon-right i {
        color: #ffffff;
    }

    .rbt-swiper-pagination .swiper-pagination-bullet {
        box-shadow: inset 0 0 0 5px #000000;
    }

    .rbt-swiper-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active {
        box-shadow: inset 0 0 0 1px #ffffff;
    }

    .rbt-author-meta .rbt-author-info a:hover {
        color: #000000;
        transition: all 0.3s ease;
        font-weight: bold;
        font-size: 1.1em;
    }

    @media screen and (max-width: 991px) {
        .hero-section-banner-area {
            /* height: 950px !important; */
        }

        /* .hero-section-row .col-lg-8 {
            flex: 0 0 auto;
            width: 66.66666667%;
        }

        .hero-section-row .col-lg-4 {
            flex: 0 0 auto;
            width: 33.33333333%;
        } */
    }

    @media screen and (max-width: 991px) and (min-width: 768px) {
        /* .hero-section-row .col-lg-8 {
            margin-top: 140px;
        }

        .hero-section-row .col-lg-4 {
            margin-bottom: 80px;
        } */
    }

    /* For screens between 460px and 767px */
    @media screen and (max-width: 767px) and (min-width: 460px) {
        /* .hero-section-row .col-lg-8 {
            margin-top: 100px;
        }

        .hero-section-row .col-lg-4 {
            margin-bottom: 80px;
        } */
    }

    /* For screens less than 460px */
    @media screen and (max-width: 459px) {
        /* .hero-section-row .col-lg-8 {
            margin-top: 100px;
        }

        .hero-section-row .col-lg-4 {
            margin-bottom: 80px;
        } */
    }
</style>
