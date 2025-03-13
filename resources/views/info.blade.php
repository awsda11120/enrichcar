@extends('layout')
@section('doc', 'ข้อมูบลรถ')
@section('content')
    <div class="container">
        <div class="input-group mb-3 d-flex align-items-center">

            <div class="col-md-2 me-3">
                <select id="CarFilter" class="form-select">
                    <option value="all">แสดงข้อมูลทั้งหมด</option>
                    <option value="insurance">แสดงข้อมูลที่ พ.ร.บ จะหมดอายุ และหมดอายุแล้ว</option>
                    <option value="tax">แสดงข้อมูลที่ภาษี จะหมดอายุ และหมดอายุแล้ว</option>
                </select>
            </div>


            <form class="d-flex col-md-2 " role="search">
                <input id="searchInput" class="form-control me-2" type="search" aria-label="Search"
                    placeholder="ค้นหาเลขทะเบียน...">

            </form>

            <span><a href="add" class="btn mx-2" style="background-color:#A4F02A">เพิ่มข้อมูล</a></span>

            <div class="d-flex align-items-center ms-auto">
                <span class="color-box" style="background-color: red;"></span>
                <span class="me-3"> พ.ร.บ./ภาษีจะหมดอายุใน 30 วัน</span>

                <span class="color-box" style="background-color: #ffe600;"></span>
                <span class="me-3"> พ.ร.บ./ภาษีจะหมดอายุใน 90 วัน</span>

                <span class="color-box" style="background-color: gray;"></span>
                <span> พ.ร.บ./ภาษีหมดอายุแล้ว</span>
            </div>
        </div>
    </div>

    <style>
        .color-box {
            width: 20px;
            height: 20px;
            display: inline-block;
            margin-right: 5px;
            border: 1px solid #000;
        }
    </style>


    <hr>
    <table id="data-table" class="table table-grid">
        <thead class="text-center">
            <tr>
                <th scope="col">เลขทะเบียน</th>
                <th scope="col">ชื่อ</th>
                <th scope="col">เบอร์โทร</th>
                <th scope="col">วันหมดอายุของ พ.ร.บ.</th>
                <th scope="col" class="tax-expiry-column">วันหมดอายุของภาษี</th> <!-- เพิ่มคลาส tax-expiry-column -->
                <th scope="col">ต่อ พ.ร.บ. / ต่อภาษี</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($list as $item)
                <tr data-ins-days="{{ $item->ins_days_left }}" data-tax-days="{{ $item->tax_days_left }}">
                    <td>{{ $item->CarNumber }}</td>
                    <td>{{ $item->CustomerName }}</td>
                    <td>{{ $item->PhoneNumber }}</td>
                    <td>
                        {{ $item->next_Ins }}
                        <span class="dot"
                            style="background-color: 
                            @if ($item->ins_days_left <= 0) gray;
                            @elseif ($item->ins_days_left >= 1 && $item->ins_days_left <= 30)
                                red;
                            @elseif ($item->ins_days_left > 30 && $item->ins_days_left <= 90)
                                #ffe600;
                            @else
                                transparent; @endif
                        "></span>
                    </td>
                    <td>
                        {{ $item->tax_expiry_date }}
                        <span class="dot"
                            style="background-color: 
                            @if ($item->tax_days_left <= 0) gray;
                            @elseif ($item->tax_days_left >= 1 && $item->tax_days_left <= 30)
                                red;
                            @elseif ($item->tax_days_left > 30 && $item->tax_days_left <= 90)
                                #ffe600;
                            @else
                                transparent; @endif
                        "></span>
                    </td>
                    <td>
                        <a href="{{ route('infomation', $item->id) }}" class="btn btn-light btn-sm"
                            style="background-color:#A4F02A">ดำเนินการต่อ</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
    function filterData() {
        let searchValue = $('#searchInput').val().toLowerCase();
        let filterValue = $('#CarFilter').val();

        $('tbody tr').each(function() {
            let carNumber = $(this).find('td:first').text().toLowerCase();
            let insDaysLeft = parseInt($(this).data('ins-days'), 10);
            let taxDaysLeft = parseInt($(this).data('tax-days'), 10);
            let showRow = true;

            // กรองตามเลขทะเบียน
            if (!carNumber.includes(searchValue)) {
                showRow = false;
            }

            // กรองตามตัวเลือกฟิลเตอร์
            if (filterValue === 'insurance' && !(insDaysLeft <= 30)) {
                showRow = false;  // แสดงเฉพาะ พ.ร.บ. ที่เหลือไม่เกิน 30 วัน
            } else if (filterValue === 'tax' && !(taxDaysLeft <= 30)) {
                showRow = false;  // แสดงเฉพาะ ภาษี ที่เหลือไม่เกิน 30 วัน
            }

            // ซ่อนคอลัมน์วันหมดอายุของ พ.ร.บ. และหัวคอลัมน์ถ้าเลือกฟิลเตอร์เป็น "แสดงข้อมูลที่ ภาษีจะหมดอายุ"
            if (filterValue === 'tax') {
                $(this).find('td:nth-child(4)').hide();  // ซ่อนคอลัมน์ "วันหมดอายุของ พ.ร.บ."
                $('th:nth-child(4)').hide();  // ซ่อนหัวคอลัมน์ "วันหมดอายุของ พ.ร.บ."
            } else {
                $(this).find('td:nth-child(4)').show();  // แสดงคอลัมน์ "วันหมดอายุของ พ.ร.บ."
                $('th:nth-child(4)').show();  // แสดงหัวคอลัมน์ "วันหมดอายุของ พ.ร.บ."
            }

            // ซ่อนคอลัมน์วันหมดอายุของภาษีและหัวคอลัมน์ถ้าเลือกฟิลเตอร์เป็น "แสดงข้อมูลที่ พ.ร.บ จะหมดอายุ"
            if (filterValue === 'insurance') {
                $(this).find('td:nth-child(5)').hide();  // ซ่อนคอลัมน์ "วันหมดอายุของภาษี"
                $('th.tax-expiry-column').hide();  // ซ่อนหัวคอลัมน์ "วันหมดอายุของภาษี"
            } else {
                $(this).find('td:nth-child(5)').show();  // แสดงคอลัมน์ "วันหมดอายุของภาษี"
                $('th.tax-expiry-column').show();  // แสดงหัวคอลัมน์ "วันหมดอายุของภาษี"
            }

            $(this).toggle(showRow);
        });
    }

    // ค้นหาตามเลขทะเบียน
    $('#searchInput').on('keyup', filterData);
    // เปลี่ยนตัวเลือกฟิลเตอร์
    $('#CarFilter').on('change', filterData);
});

    </script>



@endsection
