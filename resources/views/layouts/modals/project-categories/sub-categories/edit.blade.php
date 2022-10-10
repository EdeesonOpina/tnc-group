@php
  use App\Models\ProjectSubCategory;
@endphp

@foreach($categories as $category)
@php
  $sub_categories = ProjectSubCategory::where('category_id', $category->id)->get();
@endphp
  @foreach ($sub_categories as $sub_category)
  <form action="{{ route('admin.project-sub-categories.update') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="sub_category_id" value="{{ $sub_category->id }}">
    <div class="modal fade" id="edit-sub-category-{{ $sub_category->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <h5 class="modal-title" id="exampleModalLabel">Edit Sub Category</h5><br>

            <div class="row">
              <div class="col-md-4">
                  @if ($sub_category->image)
                      <img src="{{ url($sub_category->image) }}" width="100%">
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
                    <h6>{{ $sub_category->name }}</h6>
                  </div>
                </div>
                <hr>
                <label>Name</label><br>
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') ?? $sub_category->name }}"><br>
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
@endforeach