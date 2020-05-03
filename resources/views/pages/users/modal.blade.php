<!-- Modal -->
<div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="userForm" action="{{route('user.store')}}">
                <div class="modal-body">
                    <div class="text-right">
                        <a href="#" class="text-decoration-none text-black-50" data-dismiss="modal" style="font-size:1.25rem;">x</a>
                    </div>
                    <div class="modal-title mb-5 mt-2 text-center">
                        <h4 class="modal-title-name font-weight-bold"></h4>
                    </div>
                    @csrf
                    <div class="container-fluid">
                        <input type="hidden" name="id" id="id" readonly>
                        <div class="form-group row">
                            <label for="name" class="col-form-label col-sm-2 text-sm-right">ชื่อ-สกุล</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="d_id" class="col-form-label col-sm-2 text-sm-right">หน่วยงาน</label>
                            <div class="col-sm-10">
                                <select name="d_id" id="d_id" class="custom-select select2" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                    @foreach (App\Users\Department::latest()->get() as $item)
                                        <option value="{{$item->d_id}}">{{$item->d_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">ตำแหน่ง</label>
                            <div class="col-sm-10">
                                <select name="role" id="role" class="form-control select2" style="width:100%;">
                                    <option value="" hidden>เลือกข้อมูล</option>
                                    <option value="0">พนักงาน</option>    
                                    <option value="1">แอดมิน</option>    
                                </select>                 
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-form-label col-sm-2 text-sm-right">อีเมลล์</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-form-label col-sm-2 text-sm-right">ชื่อผู้ใช้งาน</label>
                            <div class="col-sm-10">
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-form-label col-sm-2 text-sm-right">รหัสผ่าน</label>
                            <div class="col-sm-10">
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="confirmation_password" class="col-form-label col-sm-2 text-sm-right">ยืนยันรหัสผ่าน</label>
                            <div class="col-sm-10">
                                <input type="password" name="confirmation_password" id="confirmation_password" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">รูปประจำตัว</label>
                            <div class="col-sm-10">
                                <img class="profile-preview shadow-sm mb-2" src="" alt="profile" width="100" height="100" style="border-radius:50%;">
                                <input type="file" name="profile" id="profile" class="form-control-file" style="display:none;"><br>
                                <label for="profile" class="badge badge-secondary font-weight-bold ml-1"><i class="fas fa-upload mr-2"></i>เลือกรูปภาพ</label>
                            </div>
                        </div>
                        <div class="form-group text-right mt-3">
                            <button type="submit" id="btnSubmit" class="btn btn-primary font-weight-bold" style="padding:10px 30px;"></button>
                            <button type="button" class="btn btn-dark font-weight-bold" data-dismiss="modal" style="padding:10px 30px;">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="userDetails" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{-- <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="m-0">รายละเอียดข้อมูล</h5>
                <a href="#" data-dismiss="modal" class="text-secondary"><i class="fas fa-times"></i></a>
            </div> --}}
            <div class="modal-body pb-5">
                <div class="modal-title text-right">
                    <a href="#" class="text-right text-decoration-none text-black-50" data-dismiss="modal">x</a>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                            <img class="shadow" id="imageDetails" src="" alt="profiles" width="200" height="200" style="border-radius:50%;">                    
                        </div>
                        <div class="col-md-8 my-auto">
                            <h5 class="m-0">ชื่อ-สกุล: <span class="details-name"></span></h5>   
                            <p class="mb-0">
                                หน่วยงาน: <span class="detail-department"></span><br>    
                                ตำแหน่ง: <span class="detail-role"></span><br>
                                อีเมลล์: <span class="detail-email"></span><br>
                                ชื่อผู้ใช้งาน: <span class="detail-username"></span><br>
                                <small>วันที่เพิ่ม: <span class="detail-created"></span></small> &nbsp;&nbsp;
                                <small>แก้ไขล่าสุด: <span class="detail-updated"></span></small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>