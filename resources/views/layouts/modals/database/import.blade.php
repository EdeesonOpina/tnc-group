<div class="modal fade" id="importCSV" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Import CSV</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6>Table</h6>
        <select name="table" class="form-control" id="test" onchange="showDiv(this)">
          <option value=""></option>
          <option value="categories">categories</option>
          <option value="sub-categories">sub-categories</option>
          <option value="brands">brands</option>
          <option value="items">items</option>
          <option value="item-photos">item-photos</option>
          <option value="suppliers">suppliers</option>
          <option value="supplies">supplies</option>
          <option value="purchase-orders">purchase-orders</option>
          <option value="orders">orders</option>
          <option value="goods-receipts">goods-receipts</option>
          <option value="delivery-receipts">delivery-receipts</option>
          <option value="inventories">inventories</option>
          <option value="item-serial-numbers">item-serial-numbers</option>
          <option value="payments">payments</option>
          <option value="pos-discounts">pos-discounts</option>
          <option value="users">users</option>
        </select>
        <br>
        <div id="categories" style="display:none;">
          <form action="{{ route('admin.database.import.categories') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (categories)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>

        <div id="sub-categories" style="display:none;">
          <form action="{{ route('admin.database.import.sub-categories') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (sub-categories)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>

        <div id="delivery-receipts" style="display:none;">
          <form action="{{ route('admin.database.import.delivery-receipts') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (delivery-receipts)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>

        <div id="users" style="display:none;">
          <form action="{{ route('admin.database.import.users') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (users)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>

        <div id="suppliers" style="display:none;">
          <form action="{{ route('admin.database.import.suppliers') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (suppliers)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>

        <div id="supplies" style="display:none;">
          <form action="{{ route('admin.database.import.supplies') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (supplies)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>

        <div id="items" style="display:none;">
          <form action="{{ route('admin.database.import.items') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (items)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>

        <div id="goods-receipts" style="display:none;">
          <form action="{{ route('admin.database.import.goods-receipts') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (goods-receipts)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>

        <div id="purchase-orders" style="display:none;">
          <form action="{{ route('admin.database.import.purchase-orders') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (purchase-orders)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>

        <div id="orders" style="display:none;">
          <form action="{{ route('admin.database.import.orders') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (orders)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>

        <div id="inventories" style="display:none;">
          <form action="{{ route('admin.database.import.inventories') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (inventories)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>

        <div id="payments" style="display:none;">
          <form action="{{ route('admin.database.import.payments') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (payments)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>

        <div id="item-photos" style="display:none;">
          <form action="{{ route('admin.database.import.item-photos') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (item-photos)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>

        <div id="item-serial-numbers" style="display:none;">
          <form action="{{ route('admin.database.import.item-serial-numbers') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (item-serial-numbers)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>

        <div id="pos-discounts" style="display:none;">
          <form action="{{ route('admin.database.import.pos-discounts') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (pos-discounts)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>

        <div id="brands" style="display:none;">
          <form action="{{ route('admin.database.import.brands') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h6>Upload File (brands)</h6>
            <input type="file" name="csv">
            <br><br>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
function showDiv(select) {
   if (select.value == 'categories') {
    document.getElementById('categories').style.display = "block";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "none";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "none";
   } else if (select.value == 'sub-categories') {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "block";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "none";
   } else if (select.value == 'users') {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "block";
    document.getElementById('items').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "none";
   } else if (select.value == 'items') {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "block";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "none";
   } else if (select.value == 'brands') {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "none";
    document.getElementById('brands').style.display = "block";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "none";
   } else if (select.value == 'item-photos') {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('item-photos').style.display = "block";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "none";
   } else if (select.value == 'item-serial-numbers') {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "block";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "none";
   } else if (select.value == 'pos-discounts') {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('pos-discounts').style.display = "block";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "none";
   } else if (select.value == 'suppliers') {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "block";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "none";
   } else if (select.value == 'supplies') {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "block";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "none";
   } else if (select.value == 'purchase-orders') {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "block";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "none";
   } else if (select.value == 'orders') {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "block";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "none";
   } else if (select.value == 'goods-receipts') {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "block";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "none";
   } else if (select.value == 'inventories') {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "block";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "none";
   } else if (select.value == 'payments') {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "block";
    document.getElementById('delivery-receipts').style.display = "none";
   } else if (select.value == 'delivery-receipts') {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "block";
   } else {
    document.getElementById('categories').style.display = "none";
    document.getElementById('sub-categories').style.display = "none";
    document.getElementById('users').style.display = "none";
    document.getElementById('items').style.display = "none";
    document.getElementById('brands').style.display = "none";
    document.getElementById('item-photos').style.display = "none";
    document.getElementById('item-serial-numbers').style.display = "none";
    document.getElementById('pos-discounts').style.display = "none";
    document.getElementById('suppliers').style.display = "none";
    document.getElementById('supplies').style.display = "none";
    document.getElementById('purchase-orders').style.display = "none";
    document.getElementById('orders').style.display = "none";
    document.getElementById('goods-receipts').style.display = "none";
    document.getElementById('inventories').style.display = "none";
    document.getElementById('payments').style.display = "none";
    document.getElementById('delivery-receipts').style.display = "none";
   }
} 
</script>