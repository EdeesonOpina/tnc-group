@php
  use App\Models\ProjectCategory;
  use App\Models\ProjectCategoryStatus;

  $categories = ProjectCategory::where('status', ProjectCategoryStatus::ACTIVE)
                        ->orderBy('name', 'asc')
                        ->get();
@endphp

@foreach ($project_details as $project_detail)
  <form action="{{ route('internals.projects.details.update') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="project_detail_id" value="{{ $project_detail->id }}">
    <div class="modal fade" id="edit-project-detail-{{ $project_detail->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <h5 class="modal-title" id="exampleModalLabel">Edit Project Details</h5><br>

            <div class="row">
              <div class="col-md-4">
                  @if ($project_detail->project->image)
                      <img src="{{ url($project_detail->project->image) }}" width="100%">
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
                    {{ $project_detail->project->name }}
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <h6>Client:</h6>
                  </div>
                  <div class="col">
                    {{ $project_detail->project->client->name }}
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <h6>Project Duration:</h6>
                  </div>
                  <div class="col">
                    {{ $project_detail->project->end_date }}
                  </div>
                </div>
                <hr>
                <label>Category</label><br>
                <select id="category" name="category_id" class="custom-select" data-toggle="select">
                  <option value="{{ $project_detail->category->id }}">{{ $project_detail->category->name }}</option>
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
                </select>
                <br><br>
                <label>Sub Category</label>
                <select id="sub_category" name="sub_category_id" class="custom-select" data-toggle="select">
                    <option value=""></option>
                    @if ($project_detail->sub_category)
                      <option value="{{ $project_detail->sub_category->name }}">{{ $project_detail->sub_category->name }}</option>
                    @endif
                </select>
                <br><br>
                <label>Name</label><br>
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') ?? $project_detail->name }}"><br>
                <label>Qty</label><br>
                <input type="text" name="qty" class="form-control" placeholder="Qty" value="{{ old('qty') ?? $project_detail->qty }}"><br>
                <label>Internal CE Price</label><br>
                <input type="text" name="internal_price" class="form-control" placeholder="Internal CE Price" value="{{ old('internal_price') ?? $project_detail->internal_price }}"><br>
                <label>Price</label><br>
                <input type="text" name="price" class="form-control" placeholder="Price" value="{{ old('price') ?? $project_detail->price }}"><br>
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