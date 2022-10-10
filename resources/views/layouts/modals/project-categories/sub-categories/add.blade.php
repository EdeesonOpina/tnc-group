@foreach ($categories as $category)
<form action="{{ route('admin.project-sub-categories.create') }}" method="post">
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
              <hr>
              <label>Name</label><br>
              <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}"><br>
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