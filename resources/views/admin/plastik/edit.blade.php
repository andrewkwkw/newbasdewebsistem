@extends('admin.layouts.app')

@section('title', 'Edit Data Plastik')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('sampah-plastik.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Edit Data Plastik</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Jenis Sampah</div>
                    <div class="breadcrumb-item">Edit Data</div>
                </div>
            </div>

            <div class="section-body">

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>ID Data - {{ $plastik->id }}</h4>
                            </div>
                            <form method="POST" id="myForm" name="vform" onsubmit="return Validate()"
                                action="{{ route('sampah-plastik.update', $plastik->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group ml-4 mr-4">
                                    <label>Berat (gr)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                Gram
                                            </div>
                                        </div>
                                        <input type="text" name="berat" placeholder="Ketik disini"
                                            onclick="highlightInvalid(this)" required class="form-control phone-number"
                                            onchange="updateNominal()" value="{{ $plastik->berat }}">
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
                                            class="form-control phone-number"
                                            onchange="updateNominal()" value="{{ $plastik->nominal_per_gram }}">
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
                                        <input type="text" name="nominal" required class="form-control phone-number currency" style="display: none" value="{{ $plastik->nominal }}">
                                        <input type="text" name="totalNominal" placeholder="Total Nominal" required
                                            class="form-control phone-number currency"
                                            value="{{ number_format($plastik->nominal, 0, ',', '.') }}" disabled>
                                    </div>
                                </div>

                                <!-- Tombol Submit -->
                                <div class="form-group ml-4 mr-4">
                                    <div class="">
                                        <center><button type="submit" class="btn btn-primary">Update</button></center>
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

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-post-create.js') }}"></script>
@endpush
