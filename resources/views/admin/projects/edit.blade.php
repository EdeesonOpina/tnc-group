@include('layouts.auth.header')
<form action="{{ route('internals.projects.update') }}" method="post" id="form" enctype="multipart/form-data">
{{ csrf_field() }}
<input type="hidden" name="project_id" value="{{ $project->id }}">
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.projects') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Projects</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Project</li>
                </ol>
            </nav>
            <h1 class="m-0">Edit Project</h1>
        </div>
        <button type="submit" class="btn btn-success" id="submitButton" onclick="submitForm(this);">Submit</button>
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
                                    <input type="text" name="name" class="form-control" placeholder="Project Name" value="{{ old('name') ?? $project->name }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="company">Company</label><br />
                                    <select id="company" name="company_id" class="custom-select" data-toggle="select">
                                        <option value="{{ $project->company->id }}">{{ $project->company->name }}</option>
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
                                    <label for="client">Client</label><br />
                                    <select id="client" name="client_id" class="custom-select" data-toggle="select">
                                        <option value="{{ $project->client->id }}">{{ $project->client->name }}</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
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
                                    <label for="client">Prepared By</label><br />
                                    <select id="prepared_by_user" name="prepared_by_user_id" class="custom-select" data-toggle="select">
                                        <option value="{{ $project->prepared_by_user->id }}">{{ $project->prepared_by_user->firstname }} {{ $project->prepared_by_user->lastname }}</option>
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
                                        <option value="{{ $project->noted_by_user->id }}">{{ $project->noted_by_user->firstname }} {{ $project->noted_by_user->lastname }}</option>
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
                                        <option value="{{ $project->client_contact->id }}">{{ $project->client_contact->name }}</option>
                                        @foreach($client_contacts as $client_contact)
                                            <option value="{{ $client_contact->id }}">{{ $client_contact->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col">

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description (optional)</label>
                            <textarea id="tiny" name="description" placeholder="Enter your description here">{{ old('description') ?? $project->description }}</textarea>
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
                    <textarea name="proposal_ownership" class="form-control">{{ old('proposal_ownership') ?? $project->proposal_ownership }}</textarea>
                </div>

                <div class="form-group">
                    <label for="confidentiality">Confidentiality</label>
                    <textarea name="confidentiality" class="form-control">{{ old('confidentiality') ?? $project->confidentiality }}</textarea>
                </div>

                <div class="form-group">
                    <label for="project_confirmation">Project Confirmation</label>
                    <textarea name="project_confirmation" class="form-control">{{ old('project_confirmation') ?? $project->project_confirmation }}</textarea>
                </div>

                <div class="form-group">
                    <label for="payment_terms">Payment Terms</label>
                    <textarea name="payment_terms" class="form-control">{{ old('payment_terms') ?? $project->payment_terms }}</textarea>
                </div>

                <div class="form-group">
                    <label for="validity">Validity</label>
                    <textarea name="validity" class="form-control">{{ old('validity') ?? $project->validity }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

</form>
@include('layouts.auth.footer')