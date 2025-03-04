@extends('layout')
@section('doc', 'ข้อมูบลรถ')
@section('content')
    <div class="container">
        <div class="input-group mb-3 d-flex align-items-center">

            <div class="col-md-2 me-3">
                <select id="CarFilter" class="form-select">
                    <option value="all">แสดงข้อมูลทั้งหมด</option>
                    <option value="insurance">แสดงข้อมูลที่ พ.ร.บ จะหมดอายุ</option>
                    <option value="tax">แสดงข้อมูลที่ ภาษี จะหมดอายุ</option>
                </select>
            </div>


            <form class="d-flex col-md-2 " role="search">
                <input id="searchInput" class="form-control me-2" type="search" aria-label="Search"
                    placeholder="ค้นหาเลขทะเบียน...">

            </form>

            <span><a href="add" class="btn mx-2" style="background-color:#A4F02A">เพิ่มข้อมูล</a></span>

            <div class="d-flex align-items-center ms-auto">
                <span class="color-box" style="background-color: #f87979;"></span>
                <span class="me-3"> พ.ร.บ./ภาษีจะหมดอายุใน 30 วัน</span>

                <span class="color-box" style="background-color: #FFFF99;"></span>
                <span class="me-3"> พ.ร.บ./ภาษีจะหมดอายุใน 90 วัน</span>

                <span class="color-box" style="background-color: #D3D3D3;"></span>
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
                <th scope="col">วันหมดอายุของภาษี</th>
                <th scope="col">ต่อ พ.ร.บ. / ต่อภาษี</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($list as $item)
                @php
                    $insDaysLeft = (strtotime($item->next_Ins) - time()) / (60 * 60 * 24);
                    $taxDaysLeft = (strtotime($item->renew) - time()) / (60 * 60 * 24);
                @endphp

                <tr class="{{ $item->cls }}" data-ins-days="{{ $insDaysLeft }}" data-tax-days="{{ $taxDaysLeft }}">
                    <td style="background:#FFF!important;">{{ $item->CarNumber }}</td>
                    <td style="background:#FFF!important;">{{ $item->CustomerName }}</td>
                    <td style="background:#FFF!important;">{{ $item->PhoneNumber }}</td>
                    <td>{{ $item->next_Ins }}</td>
                    <td>{{ $item->renew }}</td>
                    <td style="background:#FFF!important;">
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
                    if (filterValue === 'insurance' && !(insDaysLeft <= 90)) {
                        showRow = false;
                    } else if (filterValue === 'tax' && !(taxDaysLeft <= 90)) {
                        showRow = false;
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
