@extends('admin.layouts.app')
@section('title', 'Create Data')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('sampah-botol-kaca.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Botol Kaca</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Jenis Sampah</div>
                    <div class="breadcrumb-item">Botol Kaca</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card"></div>
                            <form method="POST" id="myForm" name="vform" onsubmit="return Validate()"
                                action="{{ route('sampah-botol-kaca.store') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                @csrf
                                <div class="form-group ml-4 mr-4">
                                    <label>Berat (gr)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                Gram
                                            </div>
                                        </div>
                                        <input type="text" name="berat" placeholder="Ketik disini"
                                            onclick="highlightInvalid(this)" required
                                            class="form-control phone-number" onchange="updateNominal()">
                                    </div>
                                </div>
                                <div class="form-group ml-4 mr-4">
                                    <label>Nominal per Gram (Rp)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                Rp.
                                            </div>
                                        </div>
                                        <input type="text" name="nominal_per_gram" placeholder="Ketik disini"
                                            onclick="highlightInvalid(this)" required
                                            class="form-control phone-number" onchange="updateNominal()">
                                    </div>
                                </div>
                                <div class="form-group ml-4 mr-4">
                                    <label>Total Nominal</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                Rp.
                                            </div>
                                        </div>
                                        <input type="text" name="nominal" required class="form-control phone-number currency" style="display: none">
                                        <input type="text" name="totalNominal" placeholder="Total Nominal" required
                                            value="{{ old('totalNominal') }}" class="form-control phone-number currency" disabled>
                                    </div>
                                </div>

                                <!-- Tombol Submit -->
                                <div class="form-group ml-4 mr-4">
                                    <div class="">
                                        <center><button type="submit" class="btn btn-primary">Submit</button></center>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </section>
    </div>
    <script>
        function updateNominal() {
            const beratInput = document.querySelector('input[name="berat"]');
            const nominalPerGramInput = document.querySelector('input[name="nominal_per_gram"]');
            const nominalInput = document.querySelector('input[name="nominal"]');
            const totalNominal = document.querySelector('input[name="totalNominal"]');
            const berat = parseFloat(beratInput.value);
            const nominalPerGram = parseFloat(nominalPerGramInput.value);

            if (!isNaN(berat) && !isNaN(nominalPerGram)) {
                const nominal = berat * nominalPerGram;
                nominalInput.value = nominal.toLocaleString('id-ID');
                totalNominal.value = nominal.toLocaleString('id-ID');
            }
        }

        //
        let inputFilled = {};

        function highlightInvalid(input) {
            if (inputFilled[input.id]) {
                return;
            }

            input.classList.add('invalid-input');

            input.addEventListener('input', function() {
                if (input.value.trim() !== '') {
                    input.classList.remove('invalid-input');
                    inputFilled[input.id] = true;
                }
            });
        }
    </script>

@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/upload-preview/upload-preview.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('library/cleave.js/dist/cleave.min.js') }}"></script>
    <script src="{{ asset('library/cleave.js/dist/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-post-create.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/forms-advanced-forms.js') }}"></script>
@endpush
