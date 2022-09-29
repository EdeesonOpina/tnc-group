@include('layouts.auth.header')
<form action="{{ route('admin.deductions.update') }}" method="post" id="form" enctype="multipart/form-data">
{{ csrf_field() }}
<input type="hidden" name="deduction_id" value="{{ $deduction->id }}">
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.deductions') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Deductions</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Deduction</li>
                </ol>
            </nav>
            <h1 class="m-0">Edit Deduction</h1>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-8">
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <h3>Deduction</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Type</label>
                                    <input type="text" name="type" class="form-control" placeholder="Type" value="{{ old('type') ?? $deduction->type }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Value</label>
                                    <input type="text" name="value" class="form-control" placeholder="Value" value="{{ old('value') ?? $deduction->value }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</form>
@include('layouts.auth.footer')