@extends('layout')
@section('doc', 'ข้อมูลรถ')
@section('content')
<div class="container">
    <div class="input-group mb-3 d-flex align-items-center">
        <div class="me-3">
            <select id="CarFilter" class="form-select" style="width: 325px;">
                <option value="all">แสดงข้อมูลทั้งหมด</option>
                <option value="insurance">แสดงข้อมูล พ.ร.บ ที่ต้องดำเนินการต่อ</option>
                <option value="tax">แสดงข้อมูลที่ภาษี ที่ต้องดำเนินการต่อ</option>
            </select>
        </div>

        <form class="d-flex col-md-2" role="search">
            <input id="searchInput" class="form-control me-2" type="search" placeholder="ค้นหาเลขทะเบียน...">
        </form>

        <span><a href="add" class="btn mx-2" style="background-color:#A4F02A">เพิ่มข้อมูล</a></span>

        <div class="d-flex align-items-center me-auto mt-3">
            <span class="color-box" style="background-color: red;"></span>
            <span class="me-3"> พ.ร.บ./ภาษี หมดอายุใน 30 วัน</span>
            <span class="color-box" style="background-color: #ffe600;"></span>
            <span class="me-3"> พ.ร.บ./ภาษี หมดอายุใน 90 วัน</span>
            <span class="color-box" style="background-color: gray;"></span>
            <span class="me-3"> พ.ร.บ./ภาษีหมดอายุ</span>
            <span class="color-box" style="background-color: #A4F02A;"></span>
            <span class="me-3"> กำลังดำเนินการ</span>
        </div>
    </div>
</div>

<style>
    .color-box {
        width: 20px;
        height: 20px;
        display: inline-block;
        margin-right: 5px;
        border-radius: 50%;
    }
    .dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-left: 5px;
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
            <th scope="col" class="tax-expiry-column">วันหมดอายุของภาษี</th>
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
                @if ($item->HasRenewIns == 1 && $item->ins_days_left <= 90)
                    <span class="dot" style="background-color: #A4F02A;"></span>
                @elseif ($item->ins_days_left <= 0)
                    <span class="dot" style="background-color: gray;"></span>
                @elseif ($item->ins_days_left <= 30)
                    <span class="dot" style="background-color: red;"></span>
                @elseif ($item->ins_days_left <= 90)
                    <span class="dot" style="background-color: #ffe600;"></span>
                @endif
            </td>
            <td>
                {{ $item->tax_expiry_date }} 
                @if ($item->HasRenewTax == 1 && $item->tax_days_left <= 90)
                    <span class="dot" style="background-color: #A4F02A;"></span>
                @elseif ($item->tax_days_left <= 0)
                    <span class="dot" style="background-color: gray;"></span>
                @elseif ($item->tax_days_left <= 30)
                    <span class="dot" style="background-color: red;"></span>
                @elseif ($item->tax_days_left <= 90)
                    <span class="dot" style="background-color: #ffe600;"></span>
                @endif
            </td>
            <td>
                <a href="{{ route('infomation', $item->id) }}" class="btn btn-light btn-sm" style="background-color:#A4F02A">ดำเนินการต่อ</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        function filterData() {
            let searchValue = $('#searchInput').val().toLowerCase();
            let filterValue = $('#CarFilter').val();

            $('tbody tr').each(function () {
                let carNumber = $(this).find('td:first').text().toLowerCase();
                let insDaysLeft = parseInt($(this).data('ins-days'), 10);
                let taxDaysLeft = parseInt($(this).data('tax-days'), 10);
                let showRow = true;

                if (!carNumber.includes(searchValue)) {
                    showRow = false;
                }

                if (filterValue === 'insurance' && !(insDaysLeft <= 90)) {
                    showRow = false;
                } else if (filterValue === 'tax' && !(taxDaysLeft <= 90)) {
                    showRow = false;
                }

                if (filterValue === 'tax') {
                    $(this).find('td:nth-child(4)').hide();
                    $('th:nth-child(4)').hide();
                } else {
                    $(this).find('td:nth-child(4)').show();
                    $('th:nth-child(4)').show();
                }

                if (filterValue === 'insurance') {
                    $(this).find('td:nth-child(5)').hide();
                    $('th.tax-expiry-column').hide();
                } else {
                    $(this).find('td:nth-child(5)').show();
                    $('th.tax-expiry-column').show();
                }

                $(this).toggle(showRow);
            });
        }

        $('#searchInput').on('keyup', filterData);
        $('#CarFilter').on('change', filterData);
    });
</script>
@endsection
