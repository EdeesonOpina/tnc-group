@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\ProjectDetailStatus;
    use App\Models\CheckVoucherStatus;
    use App\Models\CheckVoucherDetailStatus;
@endphp

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.cv') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Check Voucher</li>
                    <li class="breadcrumb-item active" aria-current="page">Manage Check Voucher</li>
                </ol>
            </nav>
            <h1 class="m-0">Manage Check Voucher</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-12">

            <div id="spaced-card" class="card card-body">

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>CV #</strong>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            {{ $cv->reference_number ?? null }}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <strong>Date</strong>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {{ $cv->created_at->format('M d Y') ?? null }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>Pay To</strong>
                        </div>
                    </div>
                    <div class="col">
                        @if ($cv->pay_to)
                            {{ $cv->pay_to }}
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>In Payment For</strong>
                        </div>
                    </div>
                    <div class="col">
                        {{ $cv->name }}
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col">
                        <h4 class="card-header__title flex m-0">CV Details</h4>
                    </div>

                    <div class="col-md-2">
                        <a href="#" data-toggle="modal" data-target="#add-cv-details-{{ $cv->id }}">
                            <button type="button" class="btn btn-success form-control" id="table-letter-margin"><i class="material-icons icon-16pt mr-1 text-white">add</i> Add Details</button>
                        </a>
                    </div>
                </div>
                <br>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">Particulars</th>
                                <th id="compact-table">Description</th>
                                <th id="compact-table">Project</th>
                                <th id="compact-table">A. File</th>
                                <th id="compact-table">Quantity</th>
                                <th id="compact-table">Unit Price</th>
                                <th id="compact-table">Total Price</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach ($details as $detail)
                                <tr>
                                    <td>
                                        <strong>{{ $detail->name }}</strong>
                                        <div class="d-flex">
                                            @if ($detail->status == CheckVoucherDetailStatus::FOR_APPROVAL)
                                                <a href="#" data-href="{{ route('internals.cv.details.approve', [$detail->id]) }}" data-toggle="modal" data-target="#confirm-action" id="margin-right">Approve</a> | 

                                                <a href="#" data-href="{{ route('internals.cv.details.disapprove', [$detail->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Disapprove</a>
                                            @endif

                                            @if ($detail->status != CheckVoucherDetailStatus::INACTIVE)
                                                <a href="#" data-toggle="modal" data-target="#edit-cv-detail-{{ $detail->id }}" id="margin-right">Edit</a>| 

                                                <a href="#" data-href="{{ route('internals.cv.details.delete', [$detail->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Delete</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $detail->description }}</td>
                                    <td>{{ $detail->project->name }}</td>
                                    <td id="compact-table">
                                        @if ($detail->file)
                                            <a href="{{ url($detail->file) }}" download>
                                                <button class="btn btn-sm btn-primary">Download File</button>
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $detail->qty }}</td>
                                    <td>P{{ number_format($detail->price, 2) }}</td>
                                    <td>P{{ number_format($detail->total, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr> 
                                <td colspan="5">&nbsp;</td>
                                <td id="compact-table"><strong>Total Cost</strong></th>
                                <td id="compact-table">P{{ number_format($details_total, 2) }}</th>
                            </tr>
                        </tbody>
                    </table>

                    @if (count($details) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>

                <br><br>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <strong>Prepared By</strong><br>
                            @if ($cv->prepared_by_user)
                              @if ($cv->prepared_by_user->signature)
                                  <img src="{{ url($cv->prepared_by_user->signature) }}" width="80px"><br>
                              @else
                                <br><br><br>
                              @endif
                          @endif
                          <strong>
                            @if ($cv->prepared_by_user)
                                {{ $cv->prepared_by_user->firstname }} {{ $cv->prepared_by_user->lastname }}
                            @endif
                          </strong><br>
                          @if ($cv->prepared_by_user)
                              @if ($cv->prepared_by_user->role)
                                {{ $cv->prepared_by_user->role }}<br>
                              @endif

                              @if ($cv->prepared_by_user->position)
                                {{ $cv->prepared_by_user->position }}<br>
                              @endif

                              @if ($cv->prepared_by_user->company)
                                {{ $cv->prepared_by_user->company->name }}
                              @endif
                          @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <strong>Checked By</strong>
                            
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <strong>Noted By</strong>
                            
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('layouts.auth.footer')