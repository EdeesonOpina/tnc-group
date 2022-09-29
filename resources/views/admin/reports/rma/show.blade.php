@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\ReturnInventory;
    use App\Models\ReturnInventoryStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Reports</a></li>
                    <li class="breadcrumb-item active" aria-current="page">RMA</li>
                </ol>
            </nav>
            <h1 class="m-0">RMA</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.top-tabs')

    <form action="{{ route('admin.reports.rma.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <!-- <div class="col">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" data-toggle="select">
                                @if (old('status'))
                                    @if (old('status') != '*')
                                        @if (old('status') == ReturnInventoryStatus::FOR_WARRANTY)
                                            <option value="{{ old('status') }}">For Warranty</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::WAITING)
                                            <option value="{{ old('status') }}">Waiting</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::FOR_RELEASE)
                                            <option value="{{ old('status') }}">For Release</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::CLEARED)
                                            <option value="{{ old('status') }}">Cleared</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::OUT_OF_WARRANTY)
                                            <option value="{{ old('status') }}">Out of Warranty</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::CANCELLED)
                                            <option value="{{ old('status') }}">Cancelled</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::ON_PROCESS)
                                            <option value="{{ old('status') }}">On Process</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="{{ ReturnInventoryStatus::FOR_WARRANTY }}">For Warranty</option>
                                <option value="{{ ReturnInventoryStatus::WAITING }}">Waiting</option>
                                <option value="{{ ReturnInventoryStatus::FOR_RELEASE }}">For Release</option>
                                <option value="{{ ReturnInventoryStatus::CLEARED }}">Cleared</option>
                                <option value="{{ ReturnInventoryStatus::OUT_OF_WARRANTY }}">Out of Warranty</option>
                                <option value="{{ ReturnInventoryStatus::CANCELLED }}">Cancelled</option>
                                <option value="{{ ReturnInventoryStatus::ON_PROCESS }}">On Process</option>
                            </select>
                        </div>
                    </div> -->
                    <div class="col">
                        <div class="form-group">
                            <label>From</label>
                            <input name="from_date" type="date" class="form-control" data-toggle="flatpickr" max="{{ date('Y-m-d') }}" value="{{ old('from_date') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>To</label>
                            <input name="to_date" type="date" class="form-control" data-toggle="flatpickr" max="{{ date('Y-m-d') }}" value="{{ old('to_date') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label><a href="{{ route('admin.reports.rma') }}" id="no-underline">Clear Filters</a></label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary icon-20pt">search</i></button>
        </div>
    </form>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h4 class="card-header__title flex m-0">Payables</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">

                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Total</th>
                                <th>For Warranty</th>
                                <th>Waiting</th>
                                <th>For Release</th>
                                <th>Cleared</th>
                                <th>Cancelled</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($period as $date)
                            @php
                                $formatted_date = $date->format('Y-m-d');

                                $rma_count = ReturnInventory::whereBetween('created_at', [$formatted_date . ' 00:00:00', $formatted_date  . ' 23:59:59'])
                                                ->where('status', '!=', ReturnInventoryStatus::INACTIVE)
                                                ->where('status', '!=', ReturnInventoryStatus::ON_PROCESS)
                                                ->count();

                                $for_warranty_count = ReturnInventory::whereBetween('created_at', [$formatted_date . ' 00:00:00', $formatted_date  . ' 23:59:59'])
                                                ->where('status', ReturnInventoryStatus::FOR_WARRANTY)
                                                ->where('status', '!=', ReturnInventoryStatus::ON_PROCESS)
                                                ->where('status', '!=', ReturnInventoryStatus::INACTIVE)
                                                ->count();

                                $waiting_count = ReturnInventory::whereBetween('created_at', [$formatted_date . ' 00:00:00', $formatted_date  . ' 23:59:59'])
                                                ->where('status', ReturnInventoryStatus::WAITING)
                                                ->where('status', '!=', ReturnInventoryStatus::ON_PROCESS)
                                                ->where('status', '!=', ReturnInventoryStatus::INACTIVE)
                                                ->count();

                                $for_release_count = ReturnInventory::whereBetween('created_at', [$formatted_date . ' 00:00:00', $formatted_date  . ' 23:59:59'])
                                                ->where('status', ReturnInventoryStatus::FOR_RELEASE)
                                                ->where('status', '!=', ReturnInventoryStatus::ON_PROCESS)
                                                ->where('status', '!=', ReturnInventoryStatus::INACTIVE)
                                                ->count();

                                $cleared_count = ReturnInventory::whereBetween('created_at', [$formatted_date . ' 00:00:00', $formatted_date  . ' 23:59:59'])
                                                ->where('status', ReturnInventoryStatus::CLEARED)
                                                ->where('status', '!=', ReturnInventoryStatus::ON_PROCESS)
                                                ->where('status', '!=', ReturnInventoryStatus::INACTIVE)
                                                ->count();

                                $cancelled_count = ReturnInventory::whereBetween('created_at', [$formatted_date . ' 00:00:00', $formatted_date  . ' 23:59:59'])
                                                ->where('status', ReturnInventoryStatus::CANCELLED)
                                                ->where('status', '!=', ReturnInventoryStatus::ON_PROCESS)
                                                ->where('status', '!=', ReturnInventoryStatus::INACTIVE)
                                                ->count();
                            @endphp
                                <tr>
                                    <td>{{ $date->format('F, d Y') }}</td>
                                    <td>{{ $rma_count }}</td>
                                    <td>{{ $for_warranty_count }}</td>
                                    <td>{{ $waiting_count }}</td>
                                    <td>{{ $for_release_count }}</td>
                                    <td>{{ $cleared_count }}</td>
                                    <td>{{ $cancelled_count }}</td>
                                    <td>
                                        <a href="{{ route('admin.reports.rma.filter', ['*', $formatted_date, $formatted_date]) }}" target="_blank">
                                            <button class="btn btn-sm btn-primary">View</button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@include('layouts.auth.footer')