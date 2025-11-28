@extends('Client.Layout.App')
@section('title', 'Tài Liệu Api')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Api Document</h4>
                    <div class="form-group mb-3">
                        <label class="form-label text-bold" for="">Header Mặc Định</label>
                        <div class="input-group">
                            <input class="form-control" type="text" value="Api-token: {{ Auth::user()->api_token }}"
                                readonly="">
                        </div>
                    </div>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading">
                                <button class="accordion-button bg-primary text-white" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false"
                                    aria-controls="collapseOne">
                                    Lấy Thông Tin Tài Khoản
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="heading"
                                data-bs-parent="#accordionExample" style="">
                                <div class="accordion-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="">POST</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text"
                                                value="https://{{ getdomain() }}/api/me" readonly="">
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="">Responsive</label>
                                        <div class="input-group">
                                            <textarea class="form-control" type="text" readonly="" style="min-height: 460px; height: auto;">{
                                                "status": "success",
                                                "message": "Lấy dữ liệu người dùng thành công!",
                                                "data": {
                                                    "id": 1,
                                                    "name": "SUBOLD",
                                                    "email": "subold@gmail.com",
                                                    "username": "subold123",
                                                    "balance": "99999",
                                                    "total_recharge": "50000000",
                                                    "total_deduct": "12176.1",
                                                    "level": "4",
                                                    "status": "active",
                                                    "avatar": "/dist/images/profile/user-1.jpg",
                                                    "last_login": "2024-12-24 11:17:23",
                                                    "created_at": "2024-11-07 19:42:12",
                                                    "order": {
                                                        "total_order": 999
                                                    }
                                                }
                                            }
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading">
                                <button class="accordion-button bg-success text-white" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Lấy Thông Tin Server
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="heading"
                                data-bs-parent="#accordionExample" style="">
                                <div class="accordion-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="">POST</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text"
                                                value="https://{{ getdomain() }}/service/prices" readonly="">
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="">Responsive</label>
                                        <div class="input-group">
                                            <textarea class="form-control" type="text" readonly="" style="min-height: 460px; height: auto;">{
    "status": "success",
    "message": "Lấy dữ liệu thành công!",
    "data": [
        {
            "id": 1,
            "social_name": "facebook",
            "service_name": "Tăng  Theo Dõi",
            "server": "3",
            "price": "23.5",
            "min": "100",
            "max": "1000000",
            "title": "Tăng Theo Dõi Cá Nhân - Ổn Định",
            "description": "Tài Nguyên Page Pro5\r\nHiện Đây Là Gói Chạy Ngon Và Ổn Định Nhất Trong Mùa Bão",
            "type": "default",
            "refund": "1",
            "warranty": "0",
            "status": "Active"
        },
        {
            "id": 2,
            "social_name": "facebook",
            "service_name": "Tăng  Theo Dõi",
            "server": "4",
            "price": "19",
            "min": "50",
            "max": "1000000",
            "title": "Tăng Follow - Chậm",
            "description": "Tài Nguyên Pro5 - Tăng Chậm Khi Quá Tải ( Vì Rẻ Nên Đa Số Là Quá Tải )",
            "type": "default",
            "refund": "1",
            "warranty": "0",
            "status": "Active"
        },
    ]
}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading">
                                <button class="accordion-button bg-danger text-white" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false"
                                    aria-controls="collapseFour">
                                    Đặt Đơn
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="heading"
                                data-bs-parent="#accordionExample" style="">
                                <div class="accordion-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="">POST</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text"
                                                value="https://{{ getdomain() }}/api/service/order/1   (id server lấy được ở trên)"
                                                readonly="">
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="">POST Data</label>
                                        <div class="input-group">
                                            <textarea class="form-control" type="text" readonly="" style="min-height: 200px; height: auto;">{
    "server_service": 1,                                    // server Lấy Được Ở Trên
    "link_order": "https://facebook.com/123123123",         // Link Order
    "reaction": "test",                                     // Gồm Các Giá Trị: like, love, care, haha, wow, sad, angry
    "comment": "test",                                      // Mỗi Bình Luận Xuống Dòng 1 Lần
    "minutes": 15,                                          // Gồm Các Giá Trị: 15, 30, 45, 60, 90, 120, 150, 180, 210, 240, 270, 300
    "time": 3,                                              // Gồm Các Giá Trị: 3, 10, 15
    "quantity": 100,                                        // Đối Với Các Dịch Vụ Bình Luận Thì Không Cần
    "note": "",                                             // Ghi Chú
}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="">Responsive</label>
                                        <div class="input-group">
                                            <textarea class="form-control" type="text" readonly="" style="min-height: 100px; height: auto;">{
    "status": "success",
    "message": "Đặt hàng thành công, vui lòng kiểm tra lịch sử đơn",
    "order_id": 782 
}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading">
                                <button class="accordion-button bg-warning text-white" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo">
                                    Lấy Thông Tin Đơn Hàng
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="heading"
                                data-bs-parent="#accordionExample" style="">
                                <div class="accordion-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="">POST</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text"
                                                value="https://{{ getdomain() }}/api/get/orders" readonly="">
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="">POST Data</label>
                                        <div class="input-group">
                                            <textarea class="form-control" type="text" style="min-height: 90px; height: auto;" readonly="">{
    "order": [1, 2, 3] // AE Lưu Ý Đây Là Mảng!
}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="">Responsive</label>
                                        <div class="input-group">
                                            <textarea class="form-control" type="text" readonly="" style="min-height: 460px; height: auto;">{
    "status": "success",
    "message": "Lấy dữ liệu thành công!",
    "data": [
        {
            "id": 1,
            "username": "subold",
            "order_link": "https://www.facebook.com/profile.php?id=100051349999829",
            "price": "24",
            "quantity": "100",
            "total_payment": "2400",
            "start": "1400",
            "buff": "16",
            "status": "Refunded",
            "created_at": "2024-12-04 00:46:49",
            "updated_at": "2024-12-04 01:03:03"
        },
        {
            "id": 2,
            "username": "subold",
            "order_link": "https://www.facebook.com/profile.php?id=100051349999829",
            "price": "24",
            "quantity": "100",
            "total_payment": "2400",
            "start": "1359",
            "buff": "100",
            "status": "Completed",
            "created_at": "2024-12-04 00:47:52",
            "updated_at": "2024-12-04 01:02:03"
        },
        {
            "id": 3,
            "username": "subold",
            "order_link": "pfbid08rS8HRzL1fd3XgHButW3BRc4AKHKN8g8xJpiGtaixMeNUYrk7WrkN2rWDs3mvGECl",
            "price": "24",
            "quantity": "100",
            "total_payment": "2400",
            "start": "0",
            "buff": "0",
            "status": "Refunded",
            "created_at": "2024-12-04 00:56:32",
            "updated_at": "2024-12-04 01:03:03"
        }
    ]
}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="">Các Trạng Thái Đơn Hàng
                                        </label>
                                        <div class="input-group">
                                            <textarea class="form-control" type="text" style="min-height: 150px; height: auto;" readonly="">WaitingForRefund: Đang huỷ
Error: Lỗi đơn
Pending: Chờ xử lý
Active: Đang hoạt động
Warranty: Bảo hành
Completed: Hoàn thành
Refunded: Hoàn tiền
Cancelled: Đã hủy</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
