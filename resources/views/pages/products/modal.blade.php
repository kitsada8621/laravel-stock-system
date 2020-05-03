<!-- Modal -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center bg-light border-0">
                <h4 class="modal-title font-weight-bold pt-4"></h4>
            </div>
            <form id="productForm">
            <div class="modal-body">
                @csrf
                <div class="container-fluid mt-3">
                    <input type="hidden" class="form-control" name="id" id="id" readonly>
                    <div class="form-group row">
                        <label for="p_id" class="col-sm-2 col-form-label text-sm-right">รหัสพัสดุ</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="p_id" id="p_id">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="p_name" class="col-sm-2 col-form-label text-sm-right">ชื่อพัสดุ</label>
                        <div class="col-sm-10">
                            <input type="text" name="p_name" id="p_name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="p_price" class="col-sm-2 col-form-label text-sm-right">ราคา/หน่วย</label>
                        <div class="col-sm-10">
                            <input type="text" name="p_price" id="p_price" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="unit_type_id" class="col-sm-2 col-form-label text-sm-right">หน่วยนับ</label>
                        <div class="col-sm-10">
                            <select class="form-control from-control-lg select2 " name="unit_type_id" id="unit_type_id" style="width: 100%;">
                                @foreach (App\Products\Unit::get() as $item)
                                    <option value="{{$item->unit_type_id}}">{{$item->unit_type_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="p_type_id" class="col-sm-2 col-form-label text-sm-right">ประเภทพัสดุ</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" name="p_type_id" id="p_type_id" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option value="" default hidden>เลือกประเภทพัสดุ</option>
                                @foreach (App\Products\Type::get() as $item)
                                    <option value="{{$item->p_type_id}}">{{$item->p_type_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group text-right mt-4">
                        <button type="submit" id="btnConfirm" class="btn btn-primary font-weight-bold"></button>
                        <button type="button" class="btn btn-dark font-weight-bold" data-dismiss="modal">ยกเลิก</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>