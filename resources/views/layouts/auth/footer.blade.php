</div>
<!-- // END drawer-layout__content -->

<div class="mdk-drawer  js-mdk-drawer" id="default-drawer" data-align="start">
    <div class="mdk-drawer__content">
        <div class="sidebar sidebar-light sidebar-left simplebar" data-simplebar>
            <div class="d-flex align-items-center sidebar-p-a border-bottom sidebar-account">
                <a href="{{ route('auth.profile') }}" class="flex d-flex align-items-center text-underline-0 text-body">
                    <span class="avatar mr-3">
                        @if (auth()->user()->avatar)
                            <img src="{{ url(auth()->user()->avatar) }}" alt="avatar" class="avatar-img rounded-circle">
                        @else
                            <img src="{{ url(env('APP_ICON')) }}" alt="avatar" class="avatar-img rounded-circle">
                        @endif
                    </span>
                    <span class="flex d-flex flex-column">
                        <strong>{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</strong>
                        <small class="text-muted text-uppercase">{{ auth()->user()->email }}</small>
                    </span>
                </a>
                <div class="dropdown ml-auto">
                    <a href="#" data-toggle="dropdown" data-caret="false" class="text-muted"><i class="material-icons">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-item-text dropdown-item-text--lh">
                            <div><strong>{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</strong></div>
                            <div>{{ auth()->user()->email }}</div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('auth.dashboard') }}">Dashboard</a>
                        <!-- <a class="dropdown-item" href="{{ route('auth.profile') }}">My profile</a> -->
                        <!-- <a class="dropdown-item" href="{{ route('auth.profile.edit') }}">Edit account</a> -->
                        <a class="dropdown-item" href="{{ route('admin.users.edit', [auth()->user()->id]) }}">Edit account</a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#change-password">Change password</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                    </div>
                </div>
            </div>
            <div class="sidebar-heading sidebar-m-t">Menu</div>
            <ul class="sidebar-menu">

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="{{ route('auth.dashboard') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dvr</i>
                        <span class="sidebar-menu-text">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="{{ route('internals.boards.tasks') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">view_week</i>
                        <span class="sidebar-menu-text">Boards</span>
                    </a>
                </li>

                <div class="sidebar-heading">Operations</div>
                <div class="sidebar-block p-0">
                    @if(auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant' || auth()->user()->role == 'Sales' || auth()->user()->role == 'Programs')
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="{{ route('internals.projects') }}">
                                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">beenhere</i>
                                <span class="sidebar-menu-text">Projects</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant' || auth()->user()->role == 'Programs')
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="{{ route('internals.brf') }}">
                                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">bookmark</i>
                                <span class="sidebar-menu-text">BRF</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="{{ route('internals.cv') }}">
                                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">monetization_on</i>
                                <span class="sidebar-menu-text">Check Vouchers</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant' || auth()->user()->role == 'Sales' || auth()->user()->role == 'Programs')
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="{{ route('internals.purchase-orders') }}">
                                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">playlist_add</i>
                                <span class="sidebar-menu-text">Purchase Orders</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant' || auth()->user()->role == 'Programs')
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="{{ route('internals.goods-receipts') }}">
                                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">playlist_add_check</i>
                                <span class="sidebar-menu-text">Goods Receipts</span>
                            </a>
                        </li>
                    @endif

                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button" href="{{ route('internals.inventories') }}">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">assignment</i>
                            <span class="sidebar-menu-text">Inventory</span>
                        </a>
                    </li>
                </div>

                @if(auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant' || auth()->user()->role == 'Sales' || auth()->user()->role == 'RMA')
                    <div class="sidebar-heading">Accounting</div>
                    <div class="sidebar-block p-0">
                        <ul class="sidebar-menu" id="components_menu">
                            @if(auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant')
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-button" href="{{ route('accounting.cash-advances') }}">
                                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">assignment_ind</i>
                                        <span class="sidebar-menu-text">Cash Advances</span>
                                    </a>
                                </li>
                                
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-button" href="{{ route('accounting.expenses') }}">
                                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">attach_money</i>
                                        <span class="sidebar-menu-text">Expenses</span>
                                    </a>
                                </li>

                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-button" href="{{ route('accounting.liquidations') }}">
                                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">invert_colors</i>
                                        <span class="sidebar-menu-text">Liquidations</span>
                                    </a>
                                </li>
                            @endif

                            <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" data-toggle="collapse" href="#apps_menu">
                                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">assessment</i>
                                <span class="sidebar-menu-text">Reports</span>
                                <span class="ml-auto sidebar-menu-toggle-icon"></span>
                            </a>
                            <ul class="sidebar-submenu collapse" id="apps_menu">
                                @if(auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant')
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="{{ route('admin.reports.activity-logs') }}">
                                            <span class="sidebar-menu-text">Activity Logs</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="{{ route('admin.reports.projects') }}">
                                            <span class="sidebar-menu-text">Projects</span>
                                        </a>
                                    </li>
                                @endif

                                @if(auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant')
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="{{ route('admin.reports.expenses') }}">
                                            <span class="sidebar-menu-text">Expenses</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="{{ route('admin.reports.cash-advances') }}">
                                            <span class="sidebar-menu-text">Cash Advances</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        </ul>
                    </div>
                @endif

                <div class="sidebar-heading">HR</div>
                <div class="sidebar-block p-0">
                    <ul class="sidebar-menu" id="components_menu">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="{{ route('hr.payslips') }}">
                                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_balance_wallet</i>
                                <span class="sidebar-menu-text">Payslip</span>
                            </a>
                        </li>
                    </ul>
                </div>

                @if(auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Programs')
                    <div class="sidebar-heading">System</div>
                    <div class="sidebar-block p-0">
                        <ul class="sidebar-menu" id="components_menu">
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="{{ route('admin.accounts') }}">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_balance_wallet</i>
                                    <span class="sidebar-menu-text">Accounts</span>
                                </a>
                            </li>
                            
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="{{ route('admin.companies') }}">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">store_mall_directory</i>
                                    <span class="sidebar-menu-text">Companies</span>
                                </a>
                            </li>
                            
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="{{ route('accounting.expense-companies') }}">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">business</i>
                                    <span class="sidebar-menu-text">Expense Companies</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="{{ route('admin.project-categories') }}">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">beenhere</i>
                                    <span class="sidebar-menu-text">Project Categories</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="{{ route('admin.liquidation-categories') }}">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">invert_colors</i>
                                    <span class="sidebar-menu-text">Liquidation Categories</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif

                @if(auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Programs')
                    <div class="sidebar-heading">Data</div>
                    <div class="sidebar-block p-0">
                        <ul class="sidebar-menu" id="components_menu">
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="{{ route('admin.suppliers') }}">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">directions_car</i>
                                    <span class="sidebar-menu-text">Suppliers</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="{{ route('admin.items') }}">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">add_shopping_cart</i>
                                    <span class="sidebar-menu-text">Items</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="{{ route('admin.brands') }}">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">branding_watermark</i>
                                    <span class="sidebar-menu-text">Brands</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="{{ route('admin.categories') }}">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">art_track</i>
                                    <span class="sidebar-menu-text">Categories</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif

                @if(auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant' || auth()->user()->role == 'Programs')
                    <div class="sidebar-heading">Settings</div>
                    <div class="sidebar-block p-0">
                        <ul class="sidebar-menu" id="components_menu">
                            
                            @if(auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant')
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-button" href="{{ route('admin.users') }}">
                                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_circle</i>
                                        <span class="sidebar-menu-text">Users</span>
                                    </a>
                                </li>
                            @endif

                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="{{ route('admin.clients') }}">
                                    <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">face</i>
                                    <span class="sidebar-menu-text">Clients</span>
                                </a>
                            </li>

                            @if(auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant')
                                @if(auth()->user()->role == 'Super Admin')
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="#" data-toggle="modal" data-target="#importCSV">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">cloud_upload</i>
                                            <span class="sidebar-menu-text">Import</span>
                                        </a>
                                    </li>
                                @endif

                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-button" href="#" data-toggle="modal" data-target="#exportSQL">
                                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">cloud_download</i>
                                        <span class="sidebar-menu-text">Database</span>
                                    </a>
                                </li>
                            @endif
                        </ul>

                        <!-- <div class="sidebar-p-a sidebar-b-y">
                            <div class="d-flex align-items-top mb-2">
                                <div class="sidebar-heading m-0 p-0 flex text-body js-text-body">Progress</div>
                                <div class="font-weight-bold text-success">60%</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div> -->
                    </div>
                @endif

                            <!-- <div class="sidebar-p-a">
                                <a href="https://themeforest.net/item/stack-admin-bootstrap-4-dashboard-template/22959011" class="btn btn-outline-primary btn-block">Purchase Stack &dollar;35</a>
                            </div> -->

                        </div>
                    </div>
                </div>
            </div>
            <!-- // END drawer-layout -->

        </div>
        <!-- // END header-layout__content -->

    </div>
    <!-- // END header-layout -->

    <!-- App Settings FAB -->
    <div id="app-settings">
        <app-settings layout-active="default" :layout-location="{
          'default': 'dashboard-quick-access.html',
          'fixed': 'fixed-dashboard-quick-access.html',
          'fluid': 'fluid-dashboard-quick-access.html',
          'mini': 'mini-dashboard-quick-access.html'
      }"></app-settings>
  </div>

  <!-- jQuery -->
  <script src="{{ url('auth/admin/assets/vendor/jquery.min.js') }}"></script>

  <!-- Bootstrap -->
  <script src="{{ url('auth/admin/assets/vendor/popper.min.js') }}"></script>
  <script src="{{ url('auth/admin/assets/vendor/bootstrap.min.js') }}"></script>

  <!-- Simplebar -->
  <script src="{{ url('auth/admin/assets/vendor/simplebar.min.js') }}"></script>

  <!-- DOM Factory -->
  <script src="{{ url('auth/admin/assets/vendor/dom-factory.js') }}"></script>

  <!-- MDK -->
  <script src="{{ url('auth/admin/assets/vendor/material-design-kit.js') }}"></script>

  <!-- App -->
  <script src="{{ url('auth/admin/assets/js/toggle-check-all.js') }}"></script>
  <script src="{{ url('auth/admin/assets/js/check-selected-row.js') }}"></script>
  <script src="{{ url('auth/admin/assets/js/dropdown.js') }}"></script>
  <script src="{{ url('auth/admin/assets/js/sidebar-mini.js') }}"></script>
  <script src="{{ url('auth/admin/assets/js/app.js') }}"></script>

  <!-- Quill -->
  <script src="{{ url('auth/admin/assets/vendor/quill.min.js') }}"></script>
  <script src="{{ url('auth/admin/assets/js/quill.js') }}"></script>

  <!-- Dropzone -->
  <script src="{{ url('auth/admin/assets/vendor/dropzone.min.js') }}"></script>
  <script src="{{ url('auth/admin/assets/js/dropzone.js') }}"></script>

  <!-- App Settings (safe to remove) -->
  <script src="{{ url('auth/admin/assets/js/app-settings.js') }}"></script>

  <!-- Flatpickr -->
  <script src="{{ url('auth/admin/assets/vendor/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ url('auth/admin/assets/js/flatpickr.js') }}"></script>

  <!-- Select2 -->
  <script src="{{ url('auth/admin/assets/vendor/select2/select2.min.js') }}"></script>
  <script src="{{ url('auth/admin/assets/js/select2.js') }}"></script>

  <!-- Chart.js -->
  <script src="{{ url('auth/admin/assets/vendor/Chart.min.js') }}"></script>

  <!-- App Charts JS -->
  <script src="{{ url('auth/admin/assets/js/charts.js') }}"></script>

  <script>
   function showHiddenOptions(id, elementValue) {
      document.getElementById(id).style.display = elementValue.value == 6 || elementValue.value == 7 || elementValue.value == 8 ? 'block' : 'none'; // checkbox, radio, dropdown
   }
  </script>

  @if (request()->is('admin/suppliers/manage/*'))
    @include('layouts.modals.suppliers.supplies.add')
    @include('layouts.modals.suppliers.supplies.edit-price')
  @endif

  @if (request()->is('admin/goods-receipts') || request()->is('admin/goods-receipts/*'))
  @endif

  @if (request()->is('accounting/payables/*') || request()->is('accounting/payables'))
    @include('layouts.modals.payables.date-released')
  @endif

  @if (request()->is('admin/boards/tasks/*') || request()->is('admin/boards/tasks'))
    @include('layouts.modals.boards.tasks.add')
    @include('layouts.modals.boards.tasks.edit')
    @include('layouts.modals.boards.tasks.graph-activities')
  @endif

  @if (request()->is('admin/purchase-orders/manage/*'))
    @include('layouts.modals.purchase-orders.orders.add')
    @include('layouts.modals.purchase-orders.orders.edit-price')
    @include('layouts.modals.purchase-orders.orders.edit-discount')
    @include('layouts.modals.purchase-orders.orders.edit-qty')
    @include('layouts.modals.purchase-orders.orders.edit-free-qty')
  @endif

  @if (request()->is('admin/purchase-orders/view/*'))
    @include('layouts.modals.purchase-orders.orders.edit-price')
    @include('layouts.modals.purchase-orders.orders.edit-discount')
    @include('layouts.modals.purchase-orders.orders.edit-qty')
    @include('layouts.modals.purchase-orders.orders.edit-free-qty')
  @endif

  @if (request()->is('admin/goods-receipts/manage/*'))
    @include('layouts.modals.goods-receipts.delivery-receipts.add')
    @include('layouts.modals.goods-receipts.orders.receive')
    @include('layouts.modals.goods-receipts.orders.return')
  @endif

  @if (request()->is('admin/return-inventories/manage/*'))
    @include('layouts.modals.return-inventories.inventories.add')
    @include('layouts.modals.return-inventories.inventories.edit-qty')
  @endif

  @if (request()->is('admin/return-inventories/view/*'))
    @include('layouts.modals.return-inventories.inventories.edit-qty')
  @endif

  @if (request()->is('admin/inventories/manage/*'))
    {{-- @include('layouts.modals.inventories.items.set-price') --}}
    {{-- @include('layouts.modals.inventories.items.set-discount') --}}
    @include('layouts.modals.inventories.items.set-barcode')
    {{-- @include('layouts.modals.inventories.items.add-landing-price') --}}
  @endif

  @if (request()->is('admin/inventories/serial-numbers/items/*'))
    @include('layouts.modals.inventories.items.serial-numbers.add-serial-number')
    @include('layouts.modals.inventories.items.serial-numbers.edit-serial-number')
  @endif

  @if (request()->is('pos/customer/*'))
    @include('layouts.modals.pos.credit')

    <!-- if pos is not on service order -->
    @if(!str_contains(url()->current(), '/service-order'))
        @include('layouts.modals.pos.checkout')
        @include('layouts.modals.pos.set-qty')
    @else
        @include('layouts.modals.pos.service-order-checkout')
    @endif
  @endif

  @if (request()->is('operations/service-orders/*'))
    @if (request()->is('operations/service-orders/jo/customer/*'))
        @include('layouts.modals.jo.set-qty')
        @include('layouts.modals.jo.checkout')
    @endif

    @if (request()->is('operations/service-orders/view/*'))
        @include('layouts.modals.jo.set-price')
        @include('layouts.modals.jo.set-qty')
        @include('layouts.modals.jo.action-taken')
        @include('layouts.modals.jo.date-out')
        @include('layouts.modals.jo.mop')
    @else
        @if(!str_contains(url()->current(), '/jo'))
            
        @endif
    @endif
  @endif

  @if (request()->is('accounting/payments') || request()->is('accounting/payments/*'))
    @if (request()->is('accounting/payments/assign/salesperson/*'))

    @else
        @include('layouts.modals.payments.assign-bir-number')
        @include('layouts.modals.payments.assign-invoice-number')
        @include('layouts.modals.payments.credit')
        @include('layouts.modals.payments.upload-proof')
    @endif
  @endif

  @if (request()->is('accounting/payment-credits') || request()->is('accounting/payment-credits/*'))
    @if (request()->is('accounting/payment-credits/view/*'))

    @else
        @include('layouts.modals.payment-credits.pay')
        @include('layouts.modals.payment-credits.assign-bir-number')
        @include('layouts.modals.payment-credits.assign-invoice-number')
        @include('layouts.modals.payment-credits.upload-proof')
    @endif
  @endif

  @if (request()->is('accounting/cash-advances') || request()->is('accounting/cash-advances/*'))
    @if (request()->is('accounting/cash-advances/view/*'))
        @include('layouts.modals.cash-advances.pay')
    @else

    @endif
  @endif

  @if (request()->is('admin/categories') || request()->is('admin/categories/*'))
    @if(str_contains(url()->current(), '/add') || str_contains(url()->current(), '/edit'))

    @else
        @include('layouts.modals.categories.sub-categories.add')
        @include('layouts.modals.categories.sub-categories.edit')
    @endif
  @endif

  @if (request()->is('admin/project-categories') || request()->is('admin/project-categories/*'))
    @if(str_contains(url()->current(), '/add') || str_contains(url()->current(), '/edit'))

    @else
        @include('layouts.modals.project-categories.sub-categories.add')
        @include('layouts.modals.project-categories.sub-categories.edit')
    @endif
  @endif

  @if (request()->is('admin/projects/manage/*'))
    @if(str_contains(url()->current(), '/tasks'))
        @include('layouts.modals.projects.tasks.add')
        @include('layouts.modals.projects.tasks.edit')
        @include('layouts.modals.projects.tasks.graph-activities')
    @else
        @include('layouts.modals.projects.details.add')
        @include('layouts.modals.projects.details.edit')
        @include('layouts.modals.projects.start-date')
        @include('layouts.modals.projects.end-date')
        @include('layouts.modals.projects.duration-date')
        @include('layouts.modals.projects.terms')
        @include('layouts.modals.projects.asf')
        @include('layouts.modals.projects.vat')
        @include('layouts.modals.projects.margin')
        @include('layouts.modals.projects.vat-rate')
        @include('layouts.modals.projects.usd-rate')
        @include('layouts.modals.projects.has-usd')
        @include('layouts.modals.projects.disapprove')
    @endif
  @endif

  @if (request()->is('admin/cv/*') || request()->is('admin/cv'))
    @include('layouts.modals.cv.mark-as-released')
  @endif

  @if (request()->is('admin/cv/custom/manage/*'))
    @include('layouts.modals.cv.details.add')
    @include('layouts.modals.cv.details.edit')
  @endif

  @if (request()->is('admin/projects/*') || request()->is('admin/projects'))
    @if(str_contains(url()->current(), '/manage') || str_contains(url()->current(), '/tasks') || str_contains(url()->current(), '/edit')|| str_contains(url()->current(), '/view'))
        @include('layouts.modals.projects.signed-ce')
    @elseif (str_contains(url()->current(), '/add'))
        
    @else
        @include('layouts.modals.projects.disapprove-from-show')
    @endif
  @endif

  @if (request()->is('admin/projects/view/*'))
    @include('layouts.modals.projects.link')
  @endif

  @if (request()->is('admin/brf') || request()->is('admin/brf/*'))
    @if(str_contains(url()->current(), '/edit') || str_contains(url()->current(), '/add') || str_contains(url()->current(), '/view'))
        @if(str_contains(url()->current(), '/users') || str_contains(url()->current(), '/suppliers'))

        @else
            @include('layouts.modals.brf.release-file')
        @endif
    @else
        {{-- @include('layouts.modals.brf.add-from-project-list') --}}

        @if(str_contains(url()->current(), '/manage'))
            @include('layouts.modals.brf.edit')
            @include('layouts.modals.brf.disapprove')
        @else
            @include('layouts.modals.brf.disapprove-from-show')
            @include('layouts.modals.brf.cv')

            @include('layouts.modals.brf.mark-as-released')
        @endif

    @endif 
  @endif

  @if (request()->is('admin/clients') || request()->is('admin/clients/*'))
    @if(str_contains(url()->current(), '/edit') || str_contains(url()->current(), '/add') || str_contains(url()->current(), '/view'))

    @else
        @include('layouts.modals.clients.contact.add')
        @include('layouts.modals.clients.contact.edit')
    @endif
  @endif

  @if (request()->is('admin/brf/manage/*'))
    @include('layouts.modals.brf.add')
  @endif

  @if (request()->is('admin/items/photos/*'))
    @include('layouts.modals.inventories.items.photos.add')
    @include('layouts.modals.inventories.items.photos.edit')
  @endif

  @if (request()->is('accounting/payments/view/*'))
    @include('layouts.modals.payments.assign-mop')
    @include('layouts.modals.payments.edit-discount')
    @include('layouts.modals.payments.edit-vat')
    @include('layouts.modals.payments.add-serial-number')
    @include('layouts.modals.payments.return')
  @endif

    @if (request()->is('admin/rma/manage/*') || request()->is('admin/rma/review/*'))
        {{-- @include('layouts.modals.rma.return') --}}
        {{-- @include('layouts.modals.rma.edit-qty') --}}
        @include('layouts.modals.rma.add-serial-number')
        @include('layouts.modals.rma.delivery-receipt')

        <script type="text/javascript">
            const el = document.getElementById('return_type');

            const immediate_replacement = document.getElementById('advanced_replacement');
            const for_warranty = document.getElementById('for_warranty');

            el.addEventListener('change', function handleChange(event) {
              if (event.target.value === 'advanced_replacement') {
                immediate_replacement.style.display = 'block';
                for_warranty.style.display = 'none';
              } else if (event.target.value === 'for_warranty') {
                immediate_replacement.style.display = 'none';
                for_warranty.style.display = 'block';
              } else {
                immediate_replacement.style.display = 'none';
                for_warranty.style.display = 'none';
              }
            });
        </script>
    @endif

    @if (request()->is('admin/rma/view/*'))
        @include('layouts.modals.rma.action-taken')
    @endif

  @if (request()->is('admin/inventories/serial-numbers/items/*'))
    @include('layouts.modals.item-serial-numbers.assign-so')
  @endif

  @if (request()->is('admin/users') || request()->is('admin/users/*'))
    @if (request()->is('admin/users/view/*') || request()->is('admin/users/edit/*') || request()->is('admin/users/add') || request()->is('admin/users/corporate/view/*') || request()->is('admin/users/corporate/edit/*') || request()->is('admin/users/corporate/add'))

    @else
        @include('layouts.modals.users.set-password')
    @endif
  @endif

  @if (request()->is('admin/reports/customers') || request()->is('admin/reports/customers/*'))
    @include('layouts.modals.reports.customers.view')
  @endif

  @if (request()->is('admin/reports/cash-advances') || request()->is('admin/reports/cash-advances/*'))
    @include('layouts.modals.reports.cash-advances.view')
  @endif

  @include('layouts.modals.confirm-action')
  @include('layouts.modals.change-password')
  @include('layouts.modals.database.import')
  @include('layouts.modals.database.password')

    <script type="text/javascript">
        $('#confirm-action').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });
    </script>

    <script>
        $(document).ready(function(){
          $("#add-choice-field").click(function(){
            $("#choice-field").append("<div class='row' id='bottom-spaced-field'><div class='col-md-10'><input type='text' name='choices[]' class='form-control' placeholder='Enter choice here...'></div><div class='col'><i class='material-icons icon-16pt mr-1 text-danger' data-toggle='tooltip' data-placement='top' title='Delete' onclick='remove(this)'>delete</i></div></div>");
          });
        });

        function remove(elem){
            $(elem).closest('.row').remove();
        }
    </script>

    <!-- DISABLE SUBMIT BUTTON ONCE CLICKED -->
    <script>
        function submitForm(btn) {
            // disable the button
            btn.disabled = true;
            // submit the form    
            btn.form.submit();
        }
    </script>

</body>

</html>