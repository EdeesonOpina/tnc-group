@include('layouts.auth.header')
<form action="{{ route('accounting.liquidations.update') }}" method="post" id="form" enctype="multipart/form-data">
{{ csrf_field() }}
<input type="hidden" name="liquidation_id" value="{{ $liquidation->id }}">
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('accounting.liquidations') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Liquidations</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Liquidation</li>
                </ol>
            </nav>
            <h1 class="m-0">Edit Liquidation</h1>
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
                        <h3>Liquidation</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>BRF #</label>
                                    <input type="text" class="form-control" placeholder="BRF #" value="{{ old('reference_number') ?? $liquidation->budget_request_form->reference_number }}" disabled>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>Transaction Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ old('date') ?? $liquidation->date }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select id="category"  name="category_id" class="custom-select" data-toggle="select">
                                        <option value="{{ $liquidation->category->id }}">{{ $liquidation->category->name }}</option>
                                        @foreach($liquidation_categories as $liquidation_category)
                                            <option value="{{ $liquidation_category->id }}">{{ $liquidation_category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Cost</label>
                                    <input type="text" name="cost" class="form-control" placeholder="0.00" value="{{ old('cost') ?? $liquidation->cost }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Particulars</label>
                                    <input type="name" name="name" class="form-control" placeholder="Particulars" value="{{ old('name') ?? $liquidation->name }}">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" name="description" class="form-control" placeholder="Description" value="{{ old('description') ?? $liquidation->description }}">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div id="semi-spaced-card" class="card card-body">
                <h3>Liquidation Image</h3>
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