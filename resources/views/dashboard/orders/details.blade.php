




   <form action="{{ route('dashboard.updatenotes') }}" method="post" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('post') }}

                     <input type="hidden" name="id" value="{{$post_id}}" />

                        <div class="form-group">
                            <label>@lang('site.notes_ar')</label> 
                            <input type="text" name="notes_ar" placeholder="@lang('site.notes_ar')"class="form-control" value="{{ old('notes_ar') }}">
                        </div>
  <div class="form-group">
                            <label>@lang('site.notes_en')</label> 
                            <input type="text" name="notes_en" placeholder="@lang('site.notes_en')"class="form-control" value="{{ old('notes_en') }}">
                        </div>

                    


                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</button>
                        </div>

                    </form>
                    