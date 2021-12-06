@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.products')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.products.index') }}"> @lang('site.products')</a></li>
                <li class="active">@lang('site.add')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.add')</h3>
                </div><!-- end of box header -->
                <div class="box-body">

                    @include('partials._errors')

                    <form action="{{ route('dashboard.products.store') }}" method="post" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('post') }}
   <div class="form-group">
                            <label>@lang('site.productprovider')</label>
                            <select name="product_providers_id" id="product_providers_id" class="form-control">
                                <option value="">@lang('site.productprovider')</option>
                               @foreach ($productproviders as $row)
                                    <option value="{{ $row->id }}" {{ old('product_providers_id') == $row->id ? 'selected' : '' }}>{{ $row->name_ar }}</option>
                                @endforeach 
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('site.categories')</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">@lang('site.all_categories')</option>
                           {{--   @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name_ar }}</option>
                                @endforeach--}}
                            </select>
                        </div>

                            <div class="form-group">
                            <label>@lang('site.name_ar')</label>
                            <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar') }}">
                        </div>

                            <div class="form-group">
                                <label>@lang('site.description_ar')</label>
                                <textarea name="description_ar" class="form-control ckeditor">{{ old('description_ar') }}</textarea>
                            </div>
                        <div class="form-group">
                            <label>@lang('site.name_en')</label>
                            <input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}">
                        </div>
        
                        

                            <div class="form-group">
                                <label>@lang('site.description_en')</label>
                                <textarea name="description_en" class="form-control ckeditor">{{ old('description_en') }}</textarea>
                            </div>

               

                        <div class="form-group">
                            <label>@lang('site.image')</label>
                            <input type="file" name="image" class="form-control image">
                        </div>

                        <div class="form-group">
                            <img src="{{ asset('uploads/product_images/default.png') }}" style="width: 100px" class="img-thumbnail image-preview" alt="">
                        </div>

                        <div class="form-group">
                            <label>@lang('site.price')</label>
                            <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price') }}">
                        </div>
                        <div class="form-group">
                            <label>@lang('site.pricetext_ar')</label>
                            <input type="text" name="pricetext_ar"  class="form-control" value="{{ old('pricetext_ar') }}">
                        </div>
                         <div class="form-group">
                            <label>@lang('site.pricetext_en')</label>
                            <input type="text" name="pricetext_en"  class="form-control" value="{{ old('pricetext_en') }}">
                        </div>
                        <div class="form-group">
                            <label>@lang('site.availableAmount')</label>
                            <input type="number" name="availableAmount"  class="form-control" value="{{ old('availableAmount') }}">
                        </div>

                        <div class="form-group">
                            <label>@lang('site.availableAmountText_ar')</label>
                            <input type="text" name="availableAmountText_ar" class="form-control" value="{{ old('availableAmountText_ar') }}">
                        </div>
                             <div class="form-group">
                            <label>@lang('site.availableAmountText_en')</label>
                            <input type="text" name="availableAmountText_en" class="form-control" value="{{ old('availableAmountText_en') }}">
                        </div>
                      <div class="form-group">
                            <label>@lang('site.type_ar')</label>
                            <input type="text" name="type_ar"  class="form-control" value="{{ old('type_ar') }}">
                        </div>

                        <div class="form-group">
                            <label>@lang('site.type_en')</label>
                            <input type="text" name="type_en" class="form-control" value="{{ old('type_en') }}">
                        </div> 
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</button>
                        </div>

                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->









<script>
         $(document).ready(function() {
        $('#product_providers_id').on('change', function() {
            var catID = $(this).val();
            if(catID) {
                $.ajax({
                    url: '/dashboard/findproviderWitheID/'+catID,
                    type: "GET",
                    data : {"_token":"{{ csrf_token() }}"},
                    dataType: "json",
                    success:function(data) {

                      if(data){
                      
                        $('#category_id').empty();
                        $('#category_id').focus;
                        $('#category_id').append('<option value="">أختر</option>'); 
                        $.each(data, function(key, value){
                        $.each(value, function(key, valued){
                        $('select[name="category_id"]').append('<option value="'+ valued.id +'">' + valued.name_ar+ '</option>');
                   });  });
                  }else{
                    $('#category_id').empty();
                  }
                  }
                });
            }else{
              $('#category_id').empty();
            }
        });
    });
    </script>

























@endsection
