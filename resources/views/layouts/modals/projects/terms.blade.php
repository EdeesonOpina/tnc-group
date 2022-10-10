<form action="{{ route('internals.projects.update.terms') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="project_id" value="{{ $project->id }}">
  <div class="modal fade" id="terms-{{ $project->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Terms and Conditions</h5><br>

          <div class="row">
            <div class="col-md-4">
                @if ($project->image)
                    <img src="{{ url($project->image) }}" width="100%">
                @else
                    <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                @endif
            </div>
            <div class="col">
              <div class="row">
                <div class="col">
                  <h6>Name:</h6>
                </div>
                <div class="col">
                  {{ $project->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <h6>Client:</h6>
                </div>
                <div class="col">
                  {{ $project->client->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <h6>Project Duration:</h6>
                </div>
                <div class="col">
                  {{ $project->end_date }}
                </div>
              </div>
              <hr>
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
          
          <br>

          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</form>