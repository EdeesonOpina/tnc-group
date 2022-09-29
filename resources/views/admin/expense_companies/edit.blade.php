@include('layouts.auth.header')
<form action="{{ route('accounting.expense-companies.update') }}" method="post" id="form" enctype="multipart/form-data">
{{ csrf_field() }}
<input type="hidden" name="expense_company_id" value="{{ $expense_company->id }}">
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('accounting.expense-companies') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Expense Companies</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Expense Company</li>
                </ol>
            </nav>
            <h1 class="m-0">Edit Expense Company</h1>
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
                        <h3>Expense Company</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select id="category" name="category_id" class="custom-select" data-toggle="select">
                                        <option value="{{ $expense_company->category->id }}">{{ $expense_company->category->name }}</option>
                                        @foreach($expense_categories as $expense_category)
                                            <option value="{{ $expense_category->id }}">{{ $expense_category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                &nbsp;
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') ?? $expense_company->name }}">
                                </div>
                            </div>

                            <div class="col">
                                &nbsp;
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div id="semi-spaced-card" class="card card-body">
                <h3>Expense Company Image</h3>
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