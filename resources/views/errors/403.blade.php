@extends('layouts.app')

@section('title', '403 Access Denied')
@section('page-title', 'Access Denied')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Animated Circle Background -->
                        <div class="position-relative mb-5 text-center">
                            <div class="animated-circle circle1"></div>
                            <div class="animated-circle circle2"></div>
                            <div class="animated-circle circle3"></div>

                            <h1 class="display-1 text-danger fw-bold position-relative animate__animated animate__bounceIn"
                                style="margin:0; z-index: 10;">
                                403
                            </h1>
                        </div>

                        <div class="text-center">
                            <h2 class="fw-bold mb-3 animate__animated animate__fadeInUp">
                                <i class="fas fa-lock me-2 text-danger"></i>Access Denied
                            </h2>

                            <p class="text-muted mb-4 animate__animated animate__fadeInUp animate__delay-1s"
                                style="max-width: 500px; margin: 0 auto;">
                                You do not have permission to view this page. Please contact your administrator if you
                                believe this is a mistake.
                            </p>

                            <div class="mt-4 animate__animated animate__fadeInUp animate__delay-2s">
                                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                                </a>

                                <button onclick="history.back()" class="btn btn-outline-secondary btn-lg ms-2">
                                    <i class="fas fa-undo-alt me-2"></i>Go Back
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Animated circles and fine-tuning */
        .position-relative {
            position: relative;
            min-height: 250px;
            isolation: isolate;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .animated-circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.2;
            animation: float 6s ease-in-out infinite alternate;
            z-index: 0;
        }

        .circle1 {
            width: 150px;
            height: 150px;
            background: #dc3545;
            /* red */
            top: 10%;
            left: 30%;
            animation-delay: 0s;
        }

        .circle2 {
            width: 200px;
            height: 200px;
            background: #ffc107;
            /* amber */
            bottom: 10%;
            right: 30%;
            animation-delay: 1s;
        }

        .circle3 {
            width: 250px;
            height: 250px;
            background: #0d6efd;
            /* blue */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 2s;
        }

        /* Floating keyframes */
        @keyframes float {
            0% {
                transform: translateY(0) translateX(0) scale(1);
            }

            33% {
                transform: translateY(-15px) translateX(10px) scale(1.05);
            }

            66% {
                transform: translateY(10px) translateX(-10px) scale(0.95);
            }

            100% {
                transform: translateY(0) translateX(0) scale(1);
            }
        }

        h1.display-1 {
            font-size: 8rem;
            text-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 10;
            margin: 0;
            line-height: 1;
        }

        /* Slight blur on background circles for depth */
        .animated-circle {
            filter: blur(8px);
            animation-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* button hover effect */
        .btn-primary,
        .btn-outline-secondary {
            transition: all 0.3s ease;
            padding: 12px 30px;
            border-radius: 50px;
        }

        .btn-primary {
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4);
        }

        .btn-outline-secondary:hover {
            transform: translateY(-3px);
            background-color: #6c757d;
            color: white;
            border-color: #6c757d;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            h1.display-1 {
                font-size: 6rem;
            }

            .circle1 {
                width: 100px;
                height: 100px;
            }

            .circle2 {
                width: 150px;
                height: 150px;
            }

            .circle3 {
                width: 180px;
                height: 180px;
            }

            .btn-lg {
                padding: 10px 20px;
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            h1.display-1 {
                font-size: 4rem;
            }

            .circle1,
            .circle2,
            .circle3 {
                opacity: 0.1;
            }

            .btn-group-mobile {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .btn-group-mobile .btn {
                margin: 5px 0 !important;
            }
        }

        /* Card customization */
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background: white;
        }

        .card-body {
            padding: 4rem 2rem;
        }

        /* Text styles */
        .text-muted {
            font-size: 1.1rem;
            line-height: 1.6;
        }

        /* Animation delays */
        .animate__delay-1s {
            animation-delay: 0.5s;
        }

        .animate__delay-2s {
            animation-delay: 1s;
        }
    </style>
@endsection

@push('styles')
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush