<div class="modal fade" id="addStockMovementModal" tabindex="-1" aria-labelledby="addStockMovementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockMovementModalLabel"><i class='bx bx-plus-medical' ></i> Add Stock Movement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('dashboard.stock-movements.store', $item->id)}}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <select class="form-select" id="type" name="type">
                            <option value="sum">Sumar</option>
                            <option value="sub">Restar</option>
                            <option value="set">Set</option>
                        </select>
                        <label for="type">Type</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity">
                        <label for="quantity">Quantity <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Reason" id="reason" name="reason" style="height: 85px;"></textarea>
                        <label for="reason">Reason</label>
                    </div>
                    <div class="modal-footer p-0 pt-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class='bx bx-plus-medical' ></i> Insert</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
