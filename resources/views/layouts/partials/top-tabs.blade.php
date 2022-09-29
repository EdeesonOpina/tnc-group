<div class="row card-group-row">
        <div class="col-lg-3 col-md-4 card-group-row__col">
            <div class="card card-group-row__card">
                <div class="p-2 d-flex flex-row align-items-center">
                    <div class="avatar avatar-xs mr-2">
                        <span class="avatar-title rounded-circle text-center bg-info">
                            <i class="material-icons text-white icon-18pt">dvr</i>
                        </span>
                    </div>
                    <a href="{{ route('auth.dashboard') }}" class="text-dark">
                        <strong>Dashboard</strong>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 card-group-row__col">
            <div class="card card-group-row__card">
                <div class="p-2 d-flex flex-row align-items-center">
                    <div class="avatar avatar-xs mr-2">
                        <span class="avatar-title rounded-circle text-center bg-primary">
                            <i class="material-icons text-white icon-18pt">add</i>
                        </span>
                    </div>
                    <a href="{{ route('internals.purchase-orders.add') }}" class="text-dark">
                        <strong>Add Purchase Order</strong>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 card-group-row__col">
            <div class="card card-group-row__card">
                <div class="p-2 d-flex flex-row align-items-center">
                    <div class="avatar avatar-xs mr-2">
                        <span class="avatar-title rounded-circle text-center bg-warning">
                            <i class="material-icons text-white icon-18pt">receipt</i>
                        </span>
                    </div>
                    <a href="{{ route('internals.purchase-orders') }}" class="text-dark">
                        <strong>Purchase Orders</strong>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 card-group-row__col">
            <div class="card card-group-row__card">
                <div class="p-2 d-flex flex-row align-items-center">
                    <div class="avatar avatar-xs mr-2">
                        <span class="avatar-title rounded-circle text-center bg-success">
                            <i class="material-icons text-white icon-18pt">assessment</i>
                        </span>
                    </div>
                    <a href="{{ route('admin.reports.sales') }}" class="text-dark">
                        <strong>Reports</strong>
                    </a>
                </div>
            </div>
        </div>
    </div>