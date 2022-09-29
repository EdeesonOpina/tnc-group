@include('layouts.auth.header')
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.rma') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">RMA</li>
                    <li class="breadcrumb-item active" aria-current="page">Add RMA</li>
                </ol>
            </nav>
            <h1 class="m-0">Add RMA</h1>
        </div>
        
    </div>
</div>

<div class="container-fluid page__container">
    <div class="row">
        <div class="col-md-8">
            @include('layouts.partials.alerts')
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('internals.rma.find') }}" method="post" id="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <h3>Add RMA</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>BFG SO#</label><br />
                                    <input type="text" name="so_number" class="form-control" placeholder="Enter BFG SO number here">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>Supplier DR#</label><br />
                                    <input type="text" name="delivery_receipt_number" class="form-control" placeholder="Enter Supplier DR number here">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            &nbsp;
        </div>
    </div>
</div>
@include('layouts.auth.footer')