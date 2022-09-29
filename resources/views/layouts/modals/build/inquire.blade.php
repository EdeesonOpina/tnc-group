<form action="{{ route('build.inquire') }}" method="post">
  {{ csrf_field() }}
  <div class="modal fade" id="inquire-build" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Inquire Build</h5><br>

          <div class="row">
            <div class="col-md-4">
              <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
            </div>
            <div class="col">
              <div class="row">
                <div class="col">
                  <h6>Name:</h6>
                </div>
                <div class="col">
                  <h6></h6>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Category:
                </div>
                <div class="col">
                  
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Brand:
                </div>
                <div class="col">
                  
                </div>
              </div>
              <hr>
              <label>Firstname</label><br>
              <input type="text" name="firstname" class="form-control" placeholder="Firstname" value="{{ auth()->user()->firstname ?? null }}"><br>

              <label>Lastname</label><br>
              <input type="text" name="lastname" class="form-control" placeholder="Lastname" value="{{ auth()->user()->lastname ?? null }}"><br>

              <label>Phone (optional)</label><br>
              <input type="text" name="phone" class="form-control" placeholder="Phone" value="{{ auth()->user()->phone ?? null }}"><br>

              <label>Mobile</label><br>
              <input type="text" name="mobile" class="form-control" placeholder="Mobile" value="{{ auth()->user()->mobile ?? null }}"><br>

              <label>Email</label><br>
              <input type="email" name="email" class="form-control" placeholder="Email" value="{{ auth()->user()->email ?? null }}">
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