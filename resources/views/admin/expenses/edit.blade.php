@include('layouts.auth.header')
<form action="{{ route('accounting.expenses.update') }}" method="post" id="form" enctype="multipart/form-data">
{{ csrf_field() }}
<input type="hidden" name="expense_id" value="{{ $expense->id }}">
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('accounting.expenses') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Expenses</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Expense</li>
                </ol>
            </nav>
            <h1 class="m-0">Edit Expense</h1>
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
                        <h3>Expense</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Transaction Date</label>
                                    <input type="date" name="date" class="form-control" data-toggle="flatpickr" value="{{ old('date') ?? $expense->date }}">
                                </div>
                            </div>

                            <div class="col">
                                &nbsp;
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select id="category"  name="category_id" class="custom-select" data-toggle="select">
                                        <option value="{{ $expense->category->id }}">{{ $expense->category->name }}</option>
                                        @foreach($expense_categories as $expense_category)
                                            <option value="{{ $expense_category->id }}">{{ $expense_category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                @if ($expense->company)
                                    <div class="form-group">
                                        <label>Company</label>
                                        <select id="company" name="company_id" class="custom-select" data-toggle="select">
                                            <option value="{{ $expense->company->id }}">{{ $expense->company->name }}</option>
                                            
                                        </select>
                                    </div>
                                @else
                                    &nbsp;
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" name="price" class="form-control" placeholder="0.00" value="{{ old('price') ?? $expense->price }}">
                                </div>
                            </div>

                            <div class="col">
                                &nbsp;
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" name="description" class="form-control" placeholder="Description" value="{{ old('description') ?? $expense->description }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Note</label>
                                    <input type="text" name="note" class="form-control" placeholder="Note" value="{{ old('note') ?? $expense->note }}">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div id="semi-spaced-card" class="card card-body">
                <h3>Expense Image</h3>
                <br>
                <div class="form-group">
                    <input type="file" name="image">
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
        var url = "{{ route('ajax.companies', [':id']) }}";
        var url_get = url.replace(':id', category_id);

        $.ajax({
            type: "get",
            url: url_get,
            success: function(data)
            {
              $('#company').empty();
              $('#company').append("<option selected></option>");

              $.each(data, function(key, value) {
                  $('#company').append("<option value='" + value.id + "'>" + value.name + "</option>");
              });
            }
        });
    });
</script>