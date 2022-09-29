@foreach ($categories as $category)
<form action="{{ route('admin.sub-categories.create') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="category_id" value="{{ $category->id }}">
  <div class="modal fade" id="add-sub-category-{{ $category->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Add Sub Category</h5><br>

          <div class="row">
            <div class="col-md-4">
                @if ($category->image)
                    <img src="{{ url($category->image) }}" width="100%">
                @else
                    <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                @endif
            </div>
            <div class="col">
              <div class="row">
                <div class="col">
                  <h6>Name:</h6>
                </div>
                <div class="col">
                  {{ $category->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Sort Order:
                </div>
                <div class="col">
                  {{ $category->sort_order }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Is Package:
                </div>
                <div class="col">
                  @if ($category->is_package == 1)
                    Yes
                  @else
                    No
                  @endif
                </div>
              </div>
              <hr>
              <label>Name</label><br>
              <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}"><br>
              <label>Is this a package sub category?</label><br>
              <select name="is_package" class="form-control">
                  @if (old('sort_order'))
                      @if (old('sort_order') == 1)
                          <option value="1">Yes</option>   
                      @else
                          <option value="0">No</option>
                      @endif
                  @endif
                  <option value="0">No</option>
                  <option value="1">Yes</option>         
              </select>
              <br>
              <label>Sort Order #</label><br>
              <input type="number" name="sort_order" class="form-control" placeholder="Sort Order #" value="{{ old('sort_order') ?? 1 }}" min="1">
            </div>
          </div>
          
          <br>

          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</form>
@endforeach