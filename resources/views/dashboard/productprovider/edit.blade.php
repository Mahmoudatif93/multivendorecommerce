@extends('layouts.dashboard.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.productprovider')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.productprovider.index') }}"> @lang('site.productprovider')</a></li>
                <li class="active">@lang('site.edit')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.edit')</h3>
                </div><!-- end of box header -->
                <div class="box-body">

                    @include('partials._errors')

                    <form action="{{ route('dashboard.productprovider.update', $productprovider->id) }}" method="post" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('put') }}

                        <div class="form-group">
                            <label>@lang('site.categories')</label>
                            <select multiple data-live-search="true"   name="category_id[]"  class="selectpicker form-control">
                                <option readonly disabled value="">@lang('site.categories')</option>
                                     @foreach($categories as $city)
                        @if(in_array($city->id, $catesIds))
                        <option value="{{ $city->id }}" selected="true">{{ $city->name_ar }}</option>
                        @else
                        <option value="{{ $city->id }}">{{ $city->name_ar }}</option>
                        @endif 
                    @endforeach
                            </select>
                        </div>


                            <div class="form-group">
                                <label>@lang('site.name_ar')</label>
                                <input type="text" name="name_ar" class="form-control" value="{{ $productprovider->name_ar }}">
                            </div>

                            <div class="form-group">
                                <label>@lang('site.name_en')</label>
                                <input type="text" name="name_en" class="form-control" value="{{ $productprovider->name_en }}">
                            </div>
                            <div class="form-group">
                                <label>@lang('site.image')</label>
                                <input type="file" name="image" class="form-control image">
                            </div>
                            <div class="form-group">
                                <img src="{{ asset('uploads/'.$productprovider->image) }}" style="width: 100px" class="img-thumbnail image-preview" alt="">
                            </div>



                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.edit')</button>
                        </div>

                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
