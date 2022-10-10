@include('layouts.auth.header')
@php
use App\Models\ProjectDetailStatus;
@endphp
<form action="{{ route('internals.projects.details.update') }}" method="post" id="form" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="project_detail_id" value="{{ $curr_project_detail->id }}">

    <div class="container page__heading-container">
        <div class="page__heading d-flex align-items-center">
            <div class="flex">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('internals.projects') }}"><i class="material-icons icon-20pt">home</i></a></li>
                        <li class="breadcrumb-item">Projects</li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Project Details</li>
                    </ol>
                </nav>
                <h1 class="m-0">Edit Project Details</h1>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </div>

    <div class="container-fluid page__container">
        @include('layouts.partials.alerts')

        <div class="row">
            <div class="col-md-8">
                <div id="spaced-card" class="card card-body">
                    <div class="row">
                        <div class="col">
                            <h3>Edit Project Details</h3>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select id="category" name="category_id" class="custom-select" data-toggle="select">
                                            <option value="{{ $curr_project_detail->category->id }}">{{ $curr_project_detail->category->name }}</option>
                                            @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Sub Category (optional)</label>
                                        <select id="sub_category" name="sub_category_id" class="custom-select" data-toggle="select">
                                            @if ($curr_project_detail->sub_category)
                                            <option value="{{ $curr_project_detail->sub_category->id }}">{{ $curr_project_detail->sub_category->name }}</option>
                                            @else
                                                <option value="0"></option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Particulars</label>
                                        <input type="text" name="name" class="form-control" placeholder="Particulars" value="{{ old('name') ?? $curr_project_detail->name }}">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Qty</label><br>
                                        <input type="text" name="qty" class="form-control" placeholder="Qty" value="{{ old('qty') ?? $curr_project_detail->qty }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Internal CE Price</label>
                                        <input type="text" name="internal_price" class="form-control" placeholder="Internal CE Price" value="{{ old('internal_price') ?? $curr_project_detail->internal_price }}">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="text" name="price" class="form-control" placeholder="Price" value="{{ old('price') ?? $curr_project_detail->price }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="tiny" name="description" placeholder="Enter your description here">{{ old('description') ?? $curr_project_detail->description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div id="semi-spaced-card" class="card card-body">
                    <h3>Project</h3>
                    <br>
                    <div class="row">
                        <div class="col">
                          <strong>Name:</strong>
                      </div>
                      <div class="col">
                          {{ $curr_project_detail->project->name }}
                      </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <strong>Client:</strong>
                  </div>
                  <div class="col">
                      {{ $curr_project_detail->project->client->name }}
                  </div>
              </div>

              <div class="row">
                <div class="col">
                  <strong>Project Duration:</strong>
              </div>
              <div class="col">
                  {{ $curr_project_detail->project->duration_date }}
              </div>
          </div>

          <br><br>

          <div class="table-responsive">
            <table class="table mb-0 thead-border-top-0 table-striped">
                <thead>
                    <tr>
                        <th id="compact-table"></th>
                        <th id="compact-table">Particulars</th>
                        <th id="compact-table">Quantity</th>
                        <th id="compact-table">Description</th>
                        <th id="compact-table">Internal Price</th>
                        <th id="compact-table">Unit Price</th>
                        <th id="compact-table">Total Price</th>
                        <th id="compact-table">Internal Total Price</th>
                        <th id="compact-table">Status</th>
                    </tr>
                </thead>
                <tbody class="list" id="companies">
                    @foreach ($project_details as $project_detail)
                    <tr>
                        <td><strong>{{ $project_detail->category->name }}</strong></td>
                        <td><strong>{{ $project_detail->name }} </strong></td>
                        <td>{{ $project_detail->qty }}</td>
                        <td>{!! $project_detail->description !!}</td>
                        <td>P{{ number_format($project_detail->internal_price, 2) }}</td>
                        <td>P{{ number_format($project_detail->price, 2) }}</td>
                        <td>P{{ number_format($project_detail->total, 2) }}</td>
                        <td>P{{ number_format($project_detail->internal_total, 2) }}</td>
                        <td>
                            @if ($project_detail->status == ProjectDetailStatus::FOR_APPROVAL)
                            <div class="badge badge-warning ml-2">For Approval</div>
                            @elseif ($project_detail->status == ProjectDetailStatus::APPROVED)
                            <div class="badge badge-success ml-2">Approved</div>
                            @elseif ($project_detail->status == ProjectDetailStatus::DISAPPROVED)
                            <div class="badge badge-danger ml-2">Disapproved</div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if (count($project_details) <= 0)
            <div style="padding: 20px">
                <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
</div>

</form>
@include('layouts.auth.footer')
<script type="text/javascript">
    $('#category').on('change', function(e) {
        var category_id = this.value;
        var url = "{{ route('ajax.projects.sub-categories', [':id']) }}";
        var url_get = url.replace(':id', category_id);

        $.ajax({
            type: "get",
            url: url_get,
            success: function(data)
            {
              $('#sub_category').empty();
              $('#sub_category').append("<option selected></option>");

              $.each(data, function(key, value) {
                  $('#sub_category').append("<option value='" + value.id + "'>" + value.name + "</option>");
              });
          }
      });
    });
</script>