@include('layouts.auth.header')
<form action="{{ route('internals.projects.create') }}" method="post" id="form" enctype="multipart/form-data">
{{ csrf_field() }}

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.projects') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Projects</li>
                    <li class="breadcrumb-item active" aria-current="page">Add Project</li>
                </ol>
            </nav>
            <h1 class="m-0">Add Project</h1>
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
                        <h3>Project</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Project Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Project Name" value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="company">Company</label><br />
                                    <select id="company" name="company_id" class="custom-select" data-toggle="select">
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Has USD</label>
                                    <select name="has_usd" class="form-control">
                                        <option value=""></option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>USD Rate</label>
                                    <input type="text" name="usd_rate" class="form-control" placeholder="USD Rate" value="{{ old('usd_rate') ?? $usd }}" min="1">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="client">Client</label><br />
                                    <select id="client" name="client_id" class="custom-select" data-toggle="select">
                                        <option value=""></option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Duration Date (optional)</label>
                                    <input type="date" name="duration_date" class="form-control" value="{{ old('duration_date') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Start Date (optional)</label>
                                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') ?? date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>End Date (optional)</label>
                                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="client">Prepared By</label><br />
                                    <select id="prepared_by_user" name="prepared_by_user_id" class="custom-select" data-toggle="select">
                                        <option value=""></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="client">Noted By</label><br />
                                    <select id="noted_by_user" name="noted_by_user_id" class="custom-select" data-toggle="select">
                                        <option value=""></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="conforme">Conforme</label><br />
                                    <select id="conforme" name="client_contact_id" class="custom-select" data-toggle="select">
                                        <option value=""></option>
                                        @foreach($client_contacts as $client_contact)
                                            <option value="{{ $client_contact->id }}">{{ $client_contact->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col">

                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>ASF (%)</label>
                                    <input type="text" name="margin" class="form-control" placeholder="ASF (%)" value="{{ old('margin') ?? '1' }}" min="1">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>VAT (%)</label>
                                    <input type="text" name="vat_rate" class="form-control" placeholder="VAT (%)" value="{{ old('vat_rate') ?? '1' }}" min="1">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description (optional)</label>
                            <textarea id="tiny" name="description" placeholder="Enter your description here">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div id="semi-spaced-card" class="card card-body">
                <h3>Project Image</h3>
                <br>
                <div class="form-group">
                    <input type="file" name="image">
                </div>
            </div>

            <br>

            <div id="semi-spaced-card" class="card card-body">
                <h3>Terms</h3>
                <br>
                <div class="form-group">
                    <label for="proposal_ownership">Proposal Ownership</label>
                    <textarea name="proposal_ownership" class="form-control">{{ old('proposal_ownership') ?? 'The project cost estimate is the property of Digimart, Inc. and is made exclusively to the recipient of this cost estimate.' }}</textarea>
                </div>

                <div class="form-group">
                    <label for="confidentiality">Confidentiality</label>
                    <textarea name="confidentiality" class="form-control">{{ old('confidentiality') ?? 'This document shall be treated confidential all times and is limited to internal use only.' }}</textarea>
                </div>

                <div class="form-group">
                    <label for="project_confirmation">Project Confirmation</label>
                    <textarea name="project_confirmation" class="form-control">{{ old('project_confirmation') ?? 'Upon signing this document, the client confirms the project.' }}</textarea>
                </div>

                <div class="form-group">
                    <label for="payment_terms">Payment Terms</label>
                    <textarea name="payment_terms" class="form-control">{{ old('payment_terms') ?? '50% down payment upon project confirmation. 50% balance to be settled 15 days after project completion. All check payments shall be made payable to TheNet.Com, Inc.' }}</textarea>
                </div>

                <div class="form-group">
                    <label for="validity">Validity</label>
                    <textarea name="validity" class="form-control">{{ old('validity') ?? 'This project cost estimate is only valid until September 15, 2022.' }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

</form>
@include('layouts.auth.footer')