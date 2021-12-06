@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.regions')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.regions.index') }}"> @lang('site.regions')</a></li>
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

                    <form action="{{ route('dashboard.regions.update', $regions->id) }}" method="post" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('put') }}

                        <div class="form-group">
                            <label>@lang('site.cities')</label>
                            <select name="cities_id" class="form-control">
                                <option value="">@lang('site.cities')</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" {{ $regions->cities_id == $city->id ? 'selected' : '' }}>{{ $city->name_ar }}</option>
                                @endforeach
                            </select>
                        </div>


                            <div class="form-group">
                                <label>@lang('site.name_ar')</label>
                                <input type="text" name="name_ar" class="form-control" value="{{ $regions->name_ar }}">
                            </div>

                            <div class="form-group">
                                <label>@lang('site.name_en')</label>
                                <input type="text" name="name_en" class="form-control" value="{{ $regions->name_en }}">
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
