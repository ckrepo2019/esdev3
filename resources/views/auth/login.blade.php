@extends('layouts.app')

@section('headerscript')
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
@endsection

@section('content')
    @php
        use Carbon\Carbon;

        $nowInManilaTz = now()->setTimezone('Asia/Manila');
        $schoolInfo = DB::table('schoolinfo')->first();
        $adImages = DB::table('adimages')->where('isactive', 1)->get();
    @endphp

    <style>
        .school-details {
            font-size: 14px;
        }
        .visit-website {
            font-size: 12px;
        }
        .tagline {
            font-size: 20px;
        }
        #thiscontainer .fxt-bg-color {
            margin: 0 auto;
            width: 60%;
        }
        @media only screen and (max-width: 600px) {
            .school-details, .visit-website {
                font-size: 14px;
            }
            .tagline {
                font-size: 18px;
            }
            #thiscontainer .fxt-bg-color {
                width: 100%;
            }
        }
    </style>

    <!-- Pre-registration Modal -->
    <div class="modal fade" id="preRegistrationModal" tabindex="-1" role="dialog" aria-labelledby="preRegistrationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pre-registration</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p>Select student type:</p>
                    <a href="/preregv2?type=old" class="btn btn-primary btn-lg">Old Student</a>
                    <a href="/preregv2?type=new" class="btn btn-success btn-lg">New Student</a>
                </div>
            </div>
        </div>
    </div>

    <section class="fxt-template-animation fxt-template-layout20 m-0">
        <div class="container" id="thiscontainer">
            <div class="fxt-bg-color pt-0" style="border-radius: 20px;">
                <div class="fxt-content text-center" style="padding: 20px 40px!important;">
                    @if ($schoolInfo)
                        <div class="fxt-header">
                            <div class="row mb-2">
                                <div class="col-12">
                                    @if (!empty($schoolInfo->websitelink))
                                        <a href="{{ Str::startsWith($schoolInfo->websitelink, ['http://', 'https://']) ? $schoolInfo->websitelink : 'https://' . $schoolInfo->websitelink }}"
                                        target="_blank" rel="noopener noreferrer">
                                            <img src="{{ $schoolInfo->picurl ?: asset('schoollogo/cklogo.png') }}"
                                                alt="School Logo" width="150">
                                        </a>
                                    @else
                                        <img src="{{ $schoolInfo->picurl ?: asset('schoollogo/cklogo.png') }}" alt="School Logo"
                                            width="150">
                                    @endif
                                </div>
                                <div class="col-12 school-details tagline">
                                    <strong>{{ $schoolInfo->schoolname ?? 'SCHOOL NAME' }}</strong><br>
                                    <small>{{ $schoolInfo->address ?? 'SCHOOL ADDRESS' }}</small>
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-12">
                                    <em>{{ $schoolInfo->tagline ?? 'School Tagline' }}</em>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <div class="fxt-form">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <input id="email" type="text"
                                       class="form-control @error('email') is-invalid @enderror" name="email"
                                       value="{{ old('email') }}" required autocomplete="email" autofocus
                                       placeholder="Username" style="border-radius: 25px;">
                                @error('email')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="current-password" placeholder="Password"
                                           style="border-top-left-radius: 25px; border-bottom-left-radius: 25px;">
                                    @error('password')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-default border" id="togglePassword"
                                                style="border-top-right-radius: 25px; border-bottom-right-radius: 25px;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="alert alert-warning text-justify" style="font-size: 12.5px; border: 1px solid gold;">
                                <strong>{{ $schoolInfo->abbreviation ?? '' }}'s SMS Privacy Notice</strong><br>
                                By clicking the Login button, I recognize the authority of the school and the third-party service provider
                                to process my personal information pursuant to the Data Privacy and Regulation of the school and applicable laws.
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-block text-white"
                                        style="background-color: {{ $schoolInfo->schoolcolor ?? '#007bff' }}; border-radius: 25px; font-size: 1.25rem;">
                                    {{ __('Login') }}
                                </button>
                            </div>

                            <a href="/coderecovery" class="btn btn-lg btn-primary btn-block"
                               style="border-radius: 25px; font-size: 1.25rem;">Get Username/Password</a>

                            <a class="btn btn-lg btn-danger btn-block text-white mt-2" style="border-radius: 25px; font-size: 1.25rem;"
                               data-toggle="modal" data-target="#preRegistrationModal">Pre-registration</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("togglePassword").addEventListener("click", function () {
                let passwordInput = document.getElementById("password");
                passwordInput.type = passwordInput.type === "password" ? "text" : "password";
            });
        });
    </script>
@endsection
