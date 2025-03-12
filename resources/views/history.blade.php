@extends('layout')
@section('doc', 'History')
@section('content')

    <div class="row">
        <div class="col-md-2 ">
            <label for="CustomerName" class="form-label fs-5">ต้นหาเลขทะเบียนรถ</label>
        </div>
        <form class="d-flex col-md-2 " role="search">
            <input id="searchInput" class="form-control me-2" type="search" aria-label="Search"
                placeholder="ค้นหาเลขทะเบียน...">

        </form>

    </div>
    <hr>
    <table class="table table-grid">
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
                            data-id="{{ $item->history_id }}" value="{{ $item->DateRenew }}" readonly>
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
                    <td>
                        @if (!empty($item->BookOwner))
                            <a href="{{ asset('upload\doc/' . $item->BookOwner) }}" download class="btn btn-sm"
                                style="background-color: #A2D7FF;">
                                ดาวน์โหลด
                            </a>
                        @else
                            <span class="text-muted">ไม่มีไฟล์</span>
                        @endif
                    </td>
                    <td style="background:#FFF!important;">
                        <button class="btn btn-light btn-sm complete-btn" data-id="{{ $item->history_id }}"
                            style="background-color: {{ $item->status == 1 ? '#ccc' : '#A4F02A' }}"
                            {{ $item->status == 1 ? 'disabled' : '' }}>
                            {{ $item->status == 1 ? 'เสร็จสิ้น' : 'เสร็จสิ้น' }}
                        </button>

                    </td>
                    <td>
                        <!-- เพิ่มปุ่ม "แก้ไข" -->
                        <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $item->history_id }}"
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
                let historyId = $(this).data('id'); // ดึงค่า id ที่เป็น history_id
                let dateRenew = $(this).data('date');
                let row = $(this).closest('tr');

                // เปิดให้เลือกวันที่ใหม่
                let dateInput = row.find('.date-renew-input');
                dateInput.removeAttr('readonly');
                dateInput.val(dateRenew); // กำหนดวันที่เดิมให้แสดงในช่อง

                // เปลี่ยนสีของปุ่ม "เสร็จสิ้น" เป็นสีเขียว และเปิดให้คลิกได้
                row.find('.complete-btn').css({
                    "background-color": "#A4F02A",
                    "cursor": "pointer"
                }).prop("disabled", false);
            });

            // คลิกปุ่ม "เสร็จสิ้น"
            $('.complete-btn').click(function() {
                let historyId = $(this).data('id'); // ใช้ data-id เพื่อรับค่า history_id จากปุ่ม
                let dateRenew = $(this).closest('tr').find('.date-renew-input').val();
                let button = $(this);

                // ตรวจสอบค่าก่อนส่ง
                if (!historyId || !dateRenew) {
                    alert('กรุณากรอกข้อมูลให้ครบ');
                    return;
                }

                $.ajax({
                    url: "{{ route('updateDateRenew') }}", // ใช้ route ที่เหมาะสม
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}", // ส่ง CSRF Token
                        history_id: historyId, // ส่ง history_id ที่ถูกต้อง
                        date_renew: dateRenew // ส่งค่า date_renew ที่เลือกจากช่อง input
                    },
                    success: function(response) {
                        if (response.success) {
                            button.css({
                                "background-color": "#ccc",
                                "cursor": "not-allowed"
                            }).prop("disabled", true).text("เสร็จสิ้น");

                            alert('วันที่ถูกบันทึกเรียบร้อยแล้ว');
                        } else {
                            alert(response.message || "เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("เกิดข้อผิดพลาดในการส่งข้อมูลไปยังเซิร์ฟเวอร์");
                    }
                });
            });
            $(document).ready(function() {
                $("#searchInput").on("keyup", function() {
                    let value = $(this).val().toLowerCase();
                    $("table tbody tr").filter(function() {
                        $(this).toggle($(this).find("td:nth-child(2)").text().toLowerCase()
                            .indexOf(value) > -1);
                    });
                });
            });
        });
    </script>
@endsection
