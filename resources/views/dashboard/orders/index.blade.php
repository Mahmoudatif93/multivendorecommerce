@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.orders')
                <small>{{ $orders->total() }} @lang('site.orders')</small>
            </h1>

            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.orders')</li>
            </ol>
        </section>

        <section class="content">

            <div class="row">

                <div class="col-md-8">

                    <div class="box box-primary">

                        <div class="box-header">

                            <h3 class="box-title" style="margin-bottom: 10px">@lang('site.orders')</h3>

                            <form action="{{ route('dashboard.orders.index') }}" method="get">

                                <div class="row">

                                    <div class="col-md-8">
                                        <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{ request()->search }}">
                                    </div>

                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>
                                    </div>

                                </div><!-- end of row -->

                            </form><!-- end of form -->

                        </div><!-- end of box header -->

                        @if ($orders->count() > 0)

                            <div class="box-body table-responsive">

                                <table class="table table-hover">
                                    <tr>
                                        <th>@lang('site.client_name')</th>
                                        <th>@lang('site.price')</th>
  <th>@lang('site.status')</th>
  
                                        <th>@lang('site.created_at')</th>
                                        <th>@lang('site.notes')</th>
                                        <th>@lang('site.action')</th>
                                    </tr>

                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->client->name }}</td>
                                            <td>{{ number_format($order->cost, 2) }}</td>
                                            <td>
                                              <select onchange="changestatus({{$order->id}})" id="statues{{$order->id}}" class="form-select" aria-label="Default select example">
                                                 @if(Session::get('locale') == 'en')
                                                 
                                                  <option disabled readonly selected>{{$order->statusText_en}}</option>
                                                  @else
                                                  
                                                  <option disabled readonly selected>{{$order->statusText_ar}}</option>
                                                  @endif
                                                    @if(Session::get('locale') == 'en')
                                                  <option value="1">pending delivery</option>
                                                   @else
                                                         <option value="1">قيد التوصيل</option>
                                                     @endif
                                                     
                                                       @if(Session::get('locale') == 'en')
                                                  <option value="2">Delivered</option>
                                                   @else
                                                         <option value="2">تم التوصيل</option>
                                                     @endif
                                                     
                                                      @if(Session::get('locale') == 'en')
                                                  <option value="3">Cancelled</option>
                                                   @else
                                                         <option value="3">تم الالغاء</option>
                                                     @endif
                                                
                                              
                                                </select>
                                            </td>
                                            <td>{{ $order->created_at->toFormattedDateString() }}</td>
                                            <td>
                                               <button type="button" class="btn btn-primary "
														onclick="addnotes({{ $order->id }})"
														data-toggle="modal" data-target="#postdetails"> @lang('site.addnotes')
												</button>
                                            
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btn-sm order-products"
                                                        data-url="{{ route('dashboard.orders.products', $order->id) }}"
                                                        data-method="get"
                                                >
                                                    <i class="fa fa-list"></i>
                                                    @lang('site.show')
                                                </button>
                                               {{-- @if (auth()->user()->hasPermission('update_orders'))
                                                    <a href="{{ route('dashboard.clients.orders.edit', ['client' => $order->client->id, 'order' => $order->id]) }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i> @lang('site.edit')</a>
                                                @else
                                                    <a href="#" disabled class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                                @endif
--}}
                                                @if (auth()->user()->hasPermission('delete_orders'))
                                                    <form action="{{ route('dashboard.orders.destroy', $order->id) }}" method="post" style="display: inline-block;">
                                                        {{ csrf_field() }}
                                                        {{ method_field('delete') }}
                                                        <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                                    </form>

                                                @else
                                                    <a href="#" class="btn btn-danger btn-sm" disabled><i class="fa fa-trash"></i> @lang('site.delete')</a>
                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </table><!-- end of table -->

                                {{ $orders->appends(request()->query())->links() }}

                            </div>

                        @else

                            <div class="box-body">
                                <h3>@lang('site.no_records')</h3>
                            </div>

                        @endif

                    </div><!-- end of box -->

                </div><!-- end of col -->

                <div class="col-md-4">

                    <div class="box box-primary">

                        <div class="box-header">
                            <h3 class="box-title" style="margin-bottom: 10px">@lang('site.show_products')</h3>
                        </div><!-- end of box header -->

                        <div class="box-body">

                            <div style="display: none; flex-direction: column; align-items: center;" id="loading">
                                <div class="loader"></div>
                                <p style="margin-top: 10px">@lang('site.loading')</p>
                            </div>

                            <div id="order-product-list">

                            </div><!-- end of order product list -->

                        </div><!-- end of box body -->

                    </div><!-- end of box -->

                </div><!-- end of col -->

            </div><!-- end of row -->

        </section><!-- end of content section -->

    </div><!-- end of content wrapper -->



  <div id="postdetails" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-body">
							<div class="text-center mt-2 mb-4">
								<a class="text-success"><span>
										
										
									</span>
								</a>
							</div>
                            <div class="details" id="details">
								
							</div>

						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div>

    </div><!-- end of content wrapper -->







<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
function changestatus(id){
    
    var status =$('#statues'+id).val();
  
    
         data = {
               id: id,
               status:status,
               _token: "{{csrf_token()}}",
           };
           $.ajax({
               url: '{{URL::to("dashboard/changestatus") }}',
               type: 'get',
               dataType: 'html',
               data: data,
               success: function(response) {
                  swal("تم تعديل حاله الطلب", "", "success");
                        setTimeout(function() {
                            window.location.reload(1);
                        }, 2000);
               },
               error: function(response) {
                  
               }
           });
}


    function addnotes(id){
           

            data = {
                id: id,
                _token: "{{csrf_token()}}",
            };
            $.ajax({
                url: '{{URL::to("dashboard/addnotes") }}',
                type: 'get',
                dataType: 'html',
                data: data,
                success: function(response) {
                    $('#details').html(response);
                },
                error: function(response) {
                    // alert(response);
                }
            });


        }
        

</script>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/firebase/8.7.0/firebase.js" integrity="sha512-OgLn108dqAbLIF2owPaxydFdSzzq+B2l6lInPybTE1mb19azQlmsvmypZBxz7i+FgOXIBPUKXrQD3kUl/8wRWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('dashboard_files/js/custom/firebase.js')}}"></script>



@endpush
@endsection
