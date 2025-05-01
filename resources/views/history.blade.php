@extends('layout')
@section('doc', 'History')
@section('content')


    <div class="row">
        <div class="col-md-2 ">
            <label for="CustomerName" class="form-label fs-5">ประวัติการดำเนินการ</label>
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
                <th scope="col">สำเนาเล่มทะเบียนรถ</th>
                <th scope="col">สถานะ</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($list as $item)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <input type="text" class="form-control datepicker date-renew-input me-2"
                                data-id="{{ $item->history_id }}" value="{{ $item->DateRenew }}" readonly>
                                <button class="btn btn-warning btn-sm edit-btn"
                                data-id="{{ $item->history_id }}"
                                data-date="{{ $item->DateRenew }}"
                                style="background-color: {{ empty($item->DateRenew) ? '#ccc' : '#F9D74E' }}; border: none;"
                                {{ empty($item->DateRenew) ? 'disabled' : '' }}>
                                แก้ไข
                            </button>
                            
                        </div>
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
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        $(document).ready(function() {
            // ทำการโหลดข้อมูลวันที่เมื่อหน้าโหลดใหม่
            $('.date-renew-input').each(function() {
                let row = $(this).closest('tr');
                let status = row.find('.complete-btn').prop("disabled");

                // ถ้าปุ่ม "เสร็จสิ้น" ถูก disable แล้ว แสดงว่าเสร็จสิ้นแล้ว
                if (status) {
                    $(this).prop("readonly", true).css("background-color",
                    "#f0f0f0"); // ทำให้วันที่ไม่สามารถเลือกได้
                }
            });

            // $(".datepicker").datepicker({
            //     format: "yyyy-mm-dd",
            //     autoclose: true,
            //     todayHighlight: true
            // });

            // คลิกปุ่ม "แก้ไข"
            $('.edit-btn').click(function() {
                let historyId = $(this).data('id');
                let dateRenew = $(this).data('date');
                let row = $(this).closest('tr');
                let dateInput = row.find('.date-renew-input');
                let editButton = $(this);

                // ทำให้ช่องวันที่สามารถแก้ไขได้
                dateInput.removeAttr('readonly').css("background-color",
                "#fff"); // เปลี่ยนพื้นหลังให้เป็นปกติ

                dateInput.val(dateRenew);

                // เปลี่ยนสีของปุ่ม "แก้ไข" เมื่อถูกคลิกและทำให้มันทึบ
                editButton.prop("disabled", true).css({
                    "opacity": "0.5",
                    "background-color": "#ccc", // เปลี่ยนสีเป็นเทา
                    "border": "none" // ลบขอบสีเหลืองออก
                });

                // ทำให้ปุ่ม "เสร็จสิ้น" สามารถคลิกได้
                row.find('.complete-btn').css({
                    "background-color": "#A4F02A", // ใช้สีเดียวกับปุ่ม "เสร็จสิ้น"
                    "cursor": "pointer"
                }).prop("disabled", false);
            });

            // คลิกปุ่ม "เสร็จสิ้น"
            $('.complete-btn').click(function() {
                let historyId = $(this).data('id');
                let row = $(this).closest('tr');
                let dateRenew = row.find('.date-renew-input').val();
                let completeButton = $(this);
                let editButton = row.find('.edit-btn');
                let dateInput = row.find('.date-renew-input');

                if (!historyId || !dateRenew) {
                    alert('กรุณากรอกข้อมูลให้ครบ');
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
                            completeButton.css({
                                "background-color": "#ccc", // เปลี่ยนสีของปุ่ม "เสร็จสิ้น" เป็นสีเทา
                                "cursor": "not-allowed"
                            }).prop("disabled", true).text("เสร็จสิ้น");

                            // เปลี่ยนสีของปุ่ม "แก้ไข" กลับมาเป็นสีเหลือง
                            editButton.prop("disabled", false).css({
                                "opacity": "1",
                                "background-color": "#F9D74E", // สีเหลืองที่ต้องการ
                                "border": "none" // เอาขอบออก
                            });

                            // เปลี่ยนวันที่ให้เป็น readonly และสีเทา
                            dateInput.prop("readonly", true).css("background-color", "#f0f0f0");

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
