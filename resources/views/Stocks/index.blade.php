@extends('layouts.master2')

@section('content')
    <div class="container py-4">

        <style>
            .section-title {
                font-weight: 700;
                font-size: 1.25rem;
                margin-bottom: 1rem;
                border-left: 4px solid #0d6efd;
                padding-left: 0.75rem;
                color: #343a40;
            }
            .info-box-icon {
                font-size: 1.5rem;
                height: 55px;
                width: 55px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        </style>

        @php
            $sections = [
                'Entrées de stock' => [
                    [
                        'route' => 'stock.entrerPoissonerie',
                        'icon' => 'fas fa-plus',
                        'color' => 'bg-info',
                        'text' => 'Entrées de stocks',
                        'sub' => '(Poissonnerie)'
                    ],
                    [
                        'route' => 'stock.entrer',
                        'icon' => 'fas fa-plus',
                        'color' => 'bg-info',
                        'text' => 'Entrées de stocks',
                        'sub' => '(Divers)'
                    ],
                ],
                'Stocks actuels' => [
                    [
                        'route' => 'stock.actuelPoissonerie',
                        'icon' => 'fas fa-box',
                        'color' => 'bg-success',
                        'text' => 'Stocks actuels',
                        'sub' => '(Poissonnerie)'
                    ],
                    [
                        'route' => 'stock.actuel',
                        'icon' => 'fas fa-box',
                        'color' => 'bg-success',
                        'text' => 'Stocks actuels',
                        'sub' => '(Divers)'
                    ],
                ],
                'Sorties de stock' => [
                    [
                        'route' => 'stock.sortiePoissonnerie',
                        'icon' => 'fas fa-minus',
                        'color' => 'bg-danger',
                        'text' => 'Sorties de stocks',
                        'sub' => '(Poissonnerie)'
                    ],
                    [
                        'route' => 'stock.sortie',
                        'icon' => 'fas fa-minus',
                        'color' => 'bg-danger',
                        'text' => 'Sorties de stocks',
                        'sub' => '(Divers)'
                    ],
                ],
            ];
        @endphp

        @foreach ($sections as $sectionTitle => $boxes)
            <div class="mb-4">
                <div class="section-title">{{ $sectionTitle }}</div>
                <div class="row g-4">
                    @foreach ($boxes as $box)
                        <div class="col-11 col-sm-5 col-lg-5">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="info-box-icon {{ $box['color'] }} rounded-circle">
                                            <i class="{{ $box['icon'] }} text-white"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <a href="{{ route($box['route']) }}" class="text-decoration-none text-dark">
                                            <h6 class="mb-1 fw-bold">{{ $box['text'] }}</h6>
                                            <span class="text-muted small">{{ $box['sub'] }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-1 col-sm-1 col-lg-1"></div>
                    @endforeach
                </div>
            </div>
        @endforeach

    </div>

     <!-- Loader pleine page -->
    <div id="pageLoader" class="page-loader d-none">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">APL_TRADING...</span>
        </div>
    </div>

    <style>
        .section-title {
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 1rem;
            border-left: 4px solid #0d6efd;
            padding-left: 0.75rem;
            color: #343a40;
        }
        .info-box-icon {
            font-size: 1.5rem;
            height: 55px;
            width: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .page-loader {
            position: fixed;
            inset: 0;
            background-color: rgba(255, 255, 255, 0.7);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loader = document.getElementById('pageLoader');

            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');

                    if (href && href !== '#' && !href.startsWith('javascript')) {
                        loader.classList.remove('d-none');
                    }
                });
            });
        });
    </script>

@endsection
