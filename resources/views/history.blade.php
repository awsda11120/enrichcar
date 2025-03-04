@extends('layout')
@section('doc', 'History')
@section('content')

    <div class="row">
        <div class="col-md-2 ">
            <label for="CustomerName" class="form-label fs-5">ประวัติการดำเนินการ</label>
        </div>
        <div class="col-md-1 ">
            <label for="CustomerName" class="form-label fs-5">จาก</label>
        </div>
        <div class="col-md-3 ">
            <input type="text" class="form-control datepicker" name="RegistrationDate" readonly>
        </div>
        <div class="col-md-1 ">
            <label for="CustomerName" class="form-label fs-5">ถึง</label>
        </div>
        <div class="col-md-3 ">
            <input type="text" class="form-control datepicker" name="RegistrationDate" readonly>
        </div>
        <div class="col-md-2 ">
            <span><a href="#" class="btn mx-2" style="background-color:#F7CBC7">ค้นหา</a></span>
        </div>
    </div>
    <hr>
    <table class="table  table-grid">
        <thead class="text-center">
            <tr>
                <th scope="col">วันที่ดำเนินการต่อ</th>
                <th scope="col">ทะเบียนรถ</th>
                <th scope="col">พ.ร.บ.</th>
                <th scope="col">ภาษี</th>
                <th scope="col">การรับเอกสาร</th>
                <th scope="col">ดาวน์โหลด</th>
                <th scope="col">สถานะ</th>
                <th scope="col">การดำเนินการ</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($list as $item)
                <tr>
                    <td>
                        <input type="text" class="form-control datepicker col-md-2 date-renew-input"
                            data-id="{{ $item->id }}" value="{{ $item->DateRenew }}" readonly>
                    </td>
                    <td>{{ $item->CarNumber }}</td>
                    <td>
                        @if ($item->TypeRenewIns == 1)
                            <span class="text-green-500">✔</span>
                        @else
                            <span class="text-red-500">✘</span>
                        @endif
                    </td>
                    <td>
                        @if ($item->TypeRenewTax == 1)
                            <span class="text-green-500">✔</span>
                        @else
                            <span class="text-red-500">✘</span>
                        @endif
                    </td>
                    <td>{{ $item->Receive }}</td>
                    <td>{{ $item->id }}</td>
                    <td style="background:#FFF!important;">
                        <button class="btn btn-light btn-sm complete-btn" data-id="{{ $item->id }}"
                            style="background-color: {{ $item->status == 1 ? '#ccc' : '#A4F02A' }}"
                            {{ $item->status == 1 ? 'disabled' : '' }}>
                            {{ $item->status == 1 ? 'เสร็จสิ้นแล้ว' : 'เสร็จสิ้น' }}
                        </button>
                    </td>
                    <td>
                        <!-- เพิ่มปุ่ม "แก้ไข" -->
                        <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $item->id }}"
                            data-date="{{ $item->DateRenew }}">
                            แก้ไข
                        </button>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
    <script>
        $(document).ready(function() {
            $(".datepicker").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true
            });
    
            // คลิกปุ่ม "แก้ไข"
            $('.edit-btn').click(function() {
                let historyId = $(this).data('id');
                let dateRenew = $(this).data('date');
                let row = $(this).closest('tr');
    
                // เปิดให้เลือกวันที่ใหม่
                let dateInput = row.find('.date-renew-input');
                dateInput.removeAttr('readonly');
                dateInput.val(dateRenew); // กำหนดวันที่เดิมให้แสดงในช่อง
    
                // เปลี่ยนสีของปุ่ม "เสร็จสิ้น" เป็นสีเขียว
                row.find('.complete-btn').css("background-color", "#A4F02A").prop("disabled", false);
            });
    
            // คลิกปุ่ม "เสร็จสิ้น"
            $('.complete-btn').click(function() {
                let historyId = $(this).data('id');
                let dateRenew = $(this).closest('tr').find('.date-renew-input').val();
                let button = $(this);
    
                // ตรวจสอบว่าได้เลือกวันที่หรือไม่
                if (!dateRenew) {
                    alert('กรุณาเลือกวันที่ก่อนกดปุ่มเสร็จสิ้น');
                    return;
                }
    
                $.ajax({
                    url: "{{ route('updateDateRenew') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        history_id: historyId,
                        date_renew: dateRenew
                    },
                    success: function(response) {
                        if (response.success) {
                            // เปลี่ยนปุ่ม "เสร็จสิ้น" เป็นสีทึบ (#ccc) และไม่ให้คลิกได้
                            button.css({
                                "background-color": "#ccc",
                                "cursor": "not-allowed"
                            }).prop("disabled", true).text("เสร็จสิ้นแล้ว");
    
                            alert('วันที่ถูกบันทึกเรียบร้อยแล้ว');
                        } else {
                            alert("เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง");
                        }
                    },
                    error: function() {
                        alert("เกิดข้อผิดพลาดในการส่งข้อมูลไปยังเซิร์ฟเวอร์");
                    }
                });
            });
        });
    </script>    
@endsection
