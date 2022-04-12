@extends('admin.layouts.master')
@section('menu', 'branches')
@section('title', 'Şube Ekle')
@section('content')

    @include('admin.layouts.success')
    @include('admin.layouts.error')

    <form id="form" action="{{ route('dashboard.branches.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header card-header-stretch justify-content-end">
                        <div class="card-toolbar">
                            <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                @foreach ($languages as $key => $language)
                                    <li class="nav-item">
                                        <a class="nav-link @if ($key == 0) active @endif"
                                            data-bs-toggle="tab"
                                            href="#lang_{{ $language->code }}">{{ $language->title }}</a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            @foreach ($languages as $key => $language)
                                <div class="tab-pane fade @if ($key == 0) show active @endif"
                                    id="lang_{{ $language->code }}" role="tabpanel">
                                    <div class="row mb-7 align-items-center">
                                        <label class="required fs-6 fw-bold form-label mb-2 col-lg-2 text-end">Başlık</label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control form-control-solid title" name="title[{{ $language->id }}]"
                                                data-language="{{ $language->id }}" placeholder="Başlık giriniz." value="@isset($description){{ $description->title }}@else{{ old('title.' . $language->id) }}@endif">
                                        </div>
                                    </div>
                                    <div class="row mb-7">
                                        <label class="required fs-6 fw-bold form-label mb-2 col-lg-2 text-end">Detay</label>
                                        <div class="col-lg-10">
                                            <textarea name="description[{{ $language->id }}]" class="tox-target tinymce">
                                                @isset($description){{ $description->description }}@else{{ old('description.' . $language->id) }}@endif
                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="accordion" id="seo_accordion">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="seo_accordion_header">
                                                <button class="accordion-button fs-4 fw-bold" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#seo_accordion_body" aria-expanded="true" aria-controls="seo_accordion_body">
                                                    SEO Ayarları
                                                </button>
                                            </h2>
                                            <div id="seo_accordion_body" class="accordion-collapse collapse" aria-labelledby="seo_accordion_header"
                                                data-bs-parent="#seo_accordion">
                                                <div class="accordion-body">
                                                    <div class="row mb-7 align-items-center">
                                                        <label class="fs-6 fw-bold form-label mb-2 col-lg-2 text-end">Seo Url</label>
                                                        <div class="col-lg-10">
                                                            <input type="text" class="form-control form-control-solid" name="seo_url[{{ $language->id }}]"
                                                                id="seo_url" placeholder="SEO url giriniz." value="@isset($description){{ $description->seo_url }}@else{{ old('seo_url.' . $language->id) }}@endif">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7 align-items-center">
                                                        <label class="fs-6 fw-bold form-label mb-2 col-lg-2 text-end">Seo Başlık</label>
                                                        <div class="col-lg-10">
                                                            <input type="text" class="form-control form-control-solid seo_title"
                                                                name="seo_title[{{ $language->id }}]" data-language="{{ $language->id }}"
                                                                value="@isset($description){{ $description->seo_title }}@else{{ old('seo_title.' . $language->id) }}@endif" placeholder="SEO başlığı giriniz.">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <label class="fs-6 fw-bold form-label mb-2 col-lg-2 text-end">Seo Açıklama</label>
                                                        <div class="col-lg-10">
                                                            <textarea name="seo_description[{{ $language->id }}]"
                                                                class="form-control form-control-solid seo_description" rows="3"
                                                                data-language="{{ $language->id }}"
                                                                placeholder="Seo açıklaması giriniz.">@isset($description){{ $description->seo_description }}@else{{ old('seo_description.' . $language->id) }}@endif</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="col-lg-12">
                            <label class="fs-6 fw-bold form-label mb-2">Görsel</label>
                            <button id="image" type="button"
                                class="btn btn-primary btn-sm modal_button d-flex align-items-center justify-content-center w-100 mb-3 text-center"
                                name="image" data-bs-toggle="modal" data-bs-target="#file_modal">
                                <i class="m-r-10 mdi mdi-folder-multiple-outline mr-3"></i>
                                <span>Sunucudan Seç</span>
                            </button>
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <input type="file" name="image" id="image" data-filepond="true"
                                    value="@if(isset($data)){{ $data->default_photo->slug }}@else{{ old('image') }}@endif"
                                    label-idle="Dosyalarınızı Sürükleyip Bırakın veya <span class='filepond--label-action'> Dosya Seçin </span>"
                                    accepted-file-types="image/*" accept="image/*">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <label class="required fs-6 fw-bold form-label mb-2">Renk Kodu</label>
                            <input type="color" id="head" name="color" class="form-control form-control-solid"
                                value="@if (isset($data)){{ $data->color }}@else{{ old('color') }}@endif"
                                placeholder="Renk kodunu seçiniz.">
                        </div>
                        <div class="col-lg-12">
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <label class="required fs-6 fw-bold form-label mb-2">Durum</label>
                                <select class="form-select form-select-solid fw-bolder" name="status" data-control="select2"
                                    data-placeholder="Seçim Yap" data-hide-search="true">
                                    @php $status_value = isset($data) ? $data->status : old('status') @endphp
                                    <option value="0" @if ($status_value == 0) selected @endif>Pasif</option>
                                    <option value="1" @if ($status_value == 1) selected @endif>Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <label class="required fs-6 fw-bold form-label mb-2">Telefon Numarası (Her satıra 1
                                    numara)</label>
                                <textarea name="phone" rows="3" class="form-control form-control-solid title"
                                    placeholder="Telefon Numarası giriniz.">{{ old('phone') }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <label class="required fs-6 fw-bold form-label mb-2">Enlem</label>
                                <input type="text" name="lat" class="form-control form-control-solid title"
                                    placeholder="Enlem giriniz." value="{{ old('lat') }}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <label class="required fs-6 fw-bold form-label mb-2">Boylam</label>
                                <input type="text" name="lng" class="form-control form-control-solid title"
                                    placeholder="Boylam giriniz." value="{{ old('lng') }}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <label class="required fs-6 fw-bold form-label mb-2">Adres</label>
                                <textarea name="address" rows="3" class="form-control form-control-solid title"
                                    placeholder="Adres giriniz.">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-5">
                    <div class="card-body p-4">
                        <div class="d-block text-center">
                            <a href="{{ route('dashboard.branches.index') }}" class="btn btn-light me-3"><i
                                    class="fas fa-arrow-left"></i> Geri Dön</a>
                            <button form="form" type="submit" class="btn btn-light-success">
                                <i class="far fa-save"></i>
                                <span class="indicator-label">Kaydet</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
