@extends('layouts.admin.admin_invoice_layout')

@section('content')
<div class="page-wrapper">
    <span class="page-title">Danh sách đơn xuất hàng</span>

    <div class="page__content-wrapper">
        <div class="row">
            <div class="col col-8 d-flex">
                <div class="me-2">
                    <form class="d-flex" id="form__search" method="GET" action="{{ route('exportinvoice.index') }}">
                        <input class="form-control form-search-input" type="text" name="tenDonHang" placeholder="Nhập tên đơn hàng"/>

                        <select name="loaiHang" class="form-select form-search-select">
                            <option value="">Loại mặt hàng</option>
                            <option value="candle">Nến</option>
                            <option value="scentedWax">Sáp thơm</option>
                            <option value="essentialOil">Tinh dầu</option>
                        </select>

                        <select name="hinhThuc" class="form-select form-search-select">
                            <option value="">Hình thức mua</option>
                            <option value="offline">Trực tiếp tại cửa hàng</option>
                            <option value="online">Online trên website</option>
                        </select>

                        <select name="trangThai" class="form-select form-search-select">
                            <option value="">Trạng thái</option>
                            <option value="completed">Hoàn thành</option>
                            <option value="pending">Đang xử lý</option>
                        </select>

                        <input type="hidden" name="order-type" id="order-type">
                        <input type="hidden" name="order-name" id="order-name">
                        <select id="form_order" class="form-select form-search-select">
                            <option value="">Sắp xếp</option>
                            <option value="tongTien asc">Tổng tiền ít nhất</option>
                            <option value="tongTien desc">Tổng tiền nhiều nhất</option>
                            <option value="created_at desc">Mới nhất</option>
                            <option value="created_at asc">Cũ nhất</option>
                        </select>
                    </form>
                </div>

                <button class="btn btn-outline-success me-2" id="form__search-btn">Search</button>
                <a role="button" class="btn btn-outline-secondary" href="{{ route('exportinvoice.index') }}">Reset</a>
            </div>

            <div class="col col-4 d-flex justify-content-end">
                <a role="button" class="btn btn-outline-primary" href="{{ route('exportinvoice.create') }}">Xuất hàng<i class="fa-solid fa-coins ms-2"></i></a>
            </div>
        </div>
    </div>

    <div class="page__content-wrapper">
        @if (Session::has('message'))
            <h5 class="text-success mb-2 ms-2"><strong>{{ Session::get('message') }}</strong></h5>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Tên đơn hàng</th>
                    <th scope="col">Tên khách hàng</th>
                    <th scope="col">Hình thức mua</th>
                    <th scope="col">Mặt hàng</th>
                    <th scope="col">Tên sản phẩm</th>
                    <th scope="col">Số lượng</th>
                    <th scope="col">Tổng tiền</th>
                    <th scope="col">Ngày xuất hàng</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col" colspan="2">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($exportInvoices as $invoice)
                    <tr>
                        <th scope="row">{{ $invoice->id }}</th>
                        <td>{{ $invoice->tenDonHang }}</td>
                        <td>{{ $invoice->tenKhachHang }}</td>
                        @switch($invoice->hinhThuc)
                            @case('offline')
                                <td>Trực tiếp tại cửa hàng</td>
                                @break
                            @case('online')
                                <td>Online trên website</td>
                                @break
                            @default
                                <td></td>
                        @endswitch
                        @switch($invoice->loaiHang)
                            @case('candle')
                                <td>Nến thơm</td>
                                @break
                            @case('scentedWax')
                                <td>Sáp thơm</td>
                                @break
                            @case('essentialOil')
                                <td>Tinh dầu</td>
                                @break
                            @default
                                <td></td>
                        @endswitch
                        <td>{{ $invoice->tenSanPham }}</td>
                        <td>{{ $invoice->soLuong }}</td>
                        <td>@currency_format($invoice->tongTien)</td>
                        <td>@date_format($invoice->created_at)</td>
                        @switch($invoice->trangThai)
                            @case('completed')
                                <td>Hoàn thành</td>
                                @break
                            @case('pending')
                                <td>Đang xử lý</td>
                                @break
                            @default
                                <td></td>
                        @endswitch
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a role="button" href="{{ route('exportinvoice.show', "$invoice->id") }}" class="btn btn-outline-success btn-sm">Chi tiết</a>
                                <button class="btn btn-outline-danger btn-sm" data-id="{{ $invoice->id }}" data-bs-toggle="modal" data-bs-target="#exampleModal">Xóa</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $exportInvoices->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<!-- Modal -->
<form method="post" id="deleteForm">
    @csrf
    @method('DELETE')
</form>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">
            <strong>
                Warning
            </strong>
            </h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <span>
            Are you sure you want to delete this item?
            </span>
            <br/>
            <span>
            This action cannot be undone!
            </span>
        </div>
        <div class="modal-footer">
            <button type="button" id="deleteBtn" class="btn btn-danger">Delete</button>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    const exampleModal = document.getElementById('exampleModal')
    const btnDelete = document.getElementById('deleteBtn')
    const formDelete = document.getElementById('deleteForm')
    exampleModal.addEventListener('show.bs.modal', event => {
      const button = event.relatedTarget
      const id = button.getAttribute('data-id')
      formDelete.action = `/admin/invoice/exportinvoice/${id}`
  
      btnDelete.onclick = function () {
        formDelete.submit();
      }
    })
  
    const formSearch = document.getElementById('form__search')
    const formSearchBtn = document.getElementById('form__search-btn')
    formSearchBtn.onclick = function() {
        const formOrder = document.getElementById('form_order');
        if (formOrder.value) {
        const orderNameInput = document.getElementById('order-name');
        const orderTypeInput = document.getElementById('order-type');
        const order = formOrder.value.split(' '); 
        const orderName = order[0];
        const orderType = order[1];
        orderNameInput.value = orderName;
        orderTypeInput.value = orderType;
        }
        formSearch.submit();
    }
</script>
@endsection

