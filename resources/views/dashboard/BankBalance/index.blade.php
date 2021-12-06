@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.bankbalance')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.bankbalance')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.bankbalance') <small>{{ $bankbalance->total() }}</small></h3>

                    <form action="{{ route('dashboard.bankbalance.index') }}" method="get">

                        <div class="row">

                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{ request()->search }}">
                            </div>

                       

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>
                            
                            </div>

                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($bankbalance->count() > 0)

                        <table class="table table-hover">

                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.name')</th>
                                <th>@lang('site.nationalId')</th>
                                <th>@lang('site.balance')</th>
                                <th>@lang('site.balanceText_ar')</th>
                                <th>@lang('site.balanceText_en')</th>
                      
                                
                                        <th>@lang('site.nationalIdImageFront')</th>
                                <th>@lang('site.nationalIdImageBack')</th>
                                     <th>@lang('site.action')</th>
                              {{--  <th>@lang('site.commercialRegisterImageFront')</th>
                                <th>@lang('site.commercialRegisterImageBack')</th>
                                <th>@lang('site.taxCardImage')</th>
                                <th>@lang('site.activityLicenseImage')</th>
                                  <th>@lang('site.residenceContractImage')</th>
                                <th>@lang('site.businessContractImage')</th>--}}
                                
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($bankbalance as $index=>$region)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $region->Client->name }}</td>
                                    <td>{{ $region->nationalId }}</td>
                                    <td>{{ $region->balance }}</td>
                                    <td>{{ $region->balanceText_ar }}</td>
                                    <td>{{ $region->balanceText_en }}</td>
                           <td><img src="{{ asset('uploads/'.$region->nationalIdImageFront) }}" style="width: 100px"  class="img-thumbnail" alt=""></td>
                            <td><img src="{{ asset('uploads/'.$region->nationalIdImageBack) }}" style="width: 100px"  class="img-thumbnail" alt=""></td>
                              {{--  <td><img src="{{ asset('uploads/'.$region->commercialRegisterImageFront) }}" style="width: 100px"  class="img-thumbnail" alt=""></td>
                              <td><img src="{{ asset('uploads/'.$region->commercialRegisterImageBack) }}" style="width: 100px"  class="img-thumbnail" alt=""></td>
                               <td><img src="{{ asset('uploads/'.$region->taxCardImage) }}" style="width: 100px"  class="img-thumbnail" alt=""></td>
                                   
                                   
                                    <td><img src="{{ asset('uploads/'.$region->activityLicenseImage) }}" style="width: 100px"  class="img-thumbnail" alt=""></td>
                               <td><img src="{{ asset('uploads/'.$region->residenceContractImage) }}" style="width: 100px"  class="img-thumbnail" alt=""></td>
                               
                               <td><img src="{{ asset('uploads/'.$region->businessContractImage) }}" style="width: 100px"  class="img-thumbnail" alt=""></td>
                               --}}
                                    <td>
                                      
                                        @if (auth()->user()->hasPermission('delete_regions'))
                                            <form action="{{ route('dashboard.bankbalance.destroy', $region->id) }}" method="post" style="display: inline-block">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <button type="submit" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                            </form><!-- end of form -->
                                        @else
                                            <button class="btn btn-danger btn-sm disabled"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                        @endif
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>

                        </table><!-- end of table -->

                        {{ $bankbalance->appends(request()->query())->links() }}

                    @else

                        <h2>@lang('site.no_data_found')</h2>

                    @endif

                </div><!-- end of box body -->


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection
