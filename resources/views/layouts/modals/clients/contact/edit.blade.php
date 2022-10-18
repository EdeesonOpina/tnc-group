@php
  use App\Models\ClientContact;
  use App\Models\ClientContactStatus;
@endphp

@foreach ($clients as $client)
@php
  $client_contacts = ClientContact::where('status', ClientContactStatus::ACTIVE)
                              ->where('client_id', $client->id)
                              ->get();
@endphp

  @foreach ($client_contacts as $client_contact)
  <form action="{{ route('admin.clients.contact.update') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="client_contact_id" value="{{ $client_contact->id }}">
    <div class="modal fade" id="edit-contact-{{ $client_contact->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <h5 class="modal-title" id="exampleModalLabel">Edit Contact</h5><br>

            <div class="row">
              <div class="col-md-4">
                  @if ($client_contact->image)
                      <img src="{{ url($client_contact->image) }}" width="100%">
                  @else
                      <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                  @endif
              </div>
              <div class="col">
                <div class="row">
                    <div class="col">
                      <strong>Client Name</strong>
                    </div>s
                    <div class="col">
                      {{ $client_contact->name }}
                    </div>
                </div>
                <hr>
                <label>Name</label><br>
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') ?? $client_contact->name }}"><br>
                <label>Position</label><br>
                <input type="text" name="position" class="form-control" placeholder="Position" value="{{ old('position') ?? $client_contact->position }}"><br>
                <label>Email Address</label>
                <input type="text" class="form-control" name="email" placeholder="Email Address" value="{{ old('email') ?? $client_contact->email }}"><br>
                <label>Phone <i>(optional)</i></label>
                <input type="text" name="phone" class="form-control" placeholder="Phone" value="{{ old('phone') ?? $client_contact->phone }}"><br>
                <label>Mobile</label>
                <input type="text" name="mobile" class="form-control" placeholder="Mobile" value="{{ old('mobile') ?? $client_contact->mobile }}"><br>
                <label>Line Address 1</label>
                <input type="text" name="line_address_1" class="form-control" placeholder="Line Address 1" value="{{ old('line_address_1') ?? $client_contact->line_address_1 }}"><br>
                <label>Line Address 2</label>
                <input type="text" name="line_address_2" class="form-control" placeholder="Line Address 2" value="{{ old('line_address_2') ?? $client_contact->line_address_2 }}">
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
  @endforeach
@endforeach