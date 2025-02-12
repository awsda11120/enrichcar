@extends('layout')
@section('doc', 'ข้อมูบลรถ')
@section('content')
    <div class="container">
        <div class="input-group mb-3 d-flex align-items-center">
            <!-- ตัวเลือกการแสดงข้อมูล -->
            <div class="col-md-2">
                <select id="CarFilter" class="form-select">
                    <option value="all" selected>แสดงข้อมูล...</option>
                    <option value="all">แสดงข้อมูลทั้งหมด</option>
                    <option value="ins_expiring">แสดงข้อมูลที่ พ.ร.บ จะหมดอายุ</option>
                    <option value="tax_expiring">แสดงข้อมูลที่ ภาษี จะหมดอายุ</option>
                </select>
            </div>

            <!-- ค้นหา -->
            <form class="d-flex col-md-2 " role="search">
                <input id="searchInput" class="form-control me-2" type="search" aria-label="Search"
                    placeholder="ค้นหาเลขทะเบียน...">
                <button class="btn" style="background-color: #F7CBC7" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <!-- ปุ่มเพิ่มข้อมูล -->
            <span><a href="add" class="btn mx-2" style="background-color:#A4F02A">เพิ่มข้อมูล</a></span>

            <!-- ตัวบ่งชี้สี -->
            <div class="d-flex align-items-center ms-auto">
                <span class="color-box" style="background-color: #FFCCCC;"></span>
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
                    // คำนวณจำนวนวันคงเหลือ
                    $insDaysLeft = (strtotime($item->next_Ins) - time()) / (60 * 60 * 24);
                    $taxDaysLeft = (strtotime($item->renew) - time()) / (60 * 60 * 24);

                    // กำหนดค่าเริ่มต้น
                    $insColor = '';
                    $taxColor = '';

                    // เงื่อนไขการกำหนดสี พ.ร.บ.
                    if ($insDaysLeft < 0) {
                        $insColor = 'background-color: #D3D3D3 !important;'; // สีเทา
                    } elseif ($insDaysLeft <= 30) {
                        $insColor = 'background-color: #FFCCCC !important;'; // สีแดง
                    } elseif ($insDaysLeft <= 90) {
                        $insColor = 'background-color: #FFFF99 !important;'; // สีเหลือง
                    }

                    // เงื่อนไขการกำหนดสีภาษี
                    if ($taxDaysLeft < 0) {
                        $taxColor = 'background-color: #D3D3D3 !important;'; // สีเทา
                    } elseif ($taxDaysLeft <= 30) {
                        $taxColor = 'background-color: #FFCCCC !important;'; // สีแดง
                    } elseif ($taxDaysLeft <= 90) {
                        $taxColor = 'background-color: #FFFF99 !important;'; // สีเหลือง
                    }
                @endphp



                <tr>
                    <td>{{ $item->CarNumber }}</td>
                    <td>{{ $item->CustomerName }}</td>
                    <td>{{ $item->PhoneNumber }}</td>
                    <td style="{{ $insColor ?? '' }}" data-ins-exp="{{ $insDaysLeft < 90 ? 'yes' : 'no' }}">
                        {{ $item->next_Ins }}
                    </td>
                    <td style="{{ $taxColor ?? '' }}" data-tax-exp="{{ $taxDaysLeft < 90 ? 'yes' : 'no' }}">
                        {{ $item->renew }}
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
            // ฟังก์ชันกรองข้อมูล
            $('#CarFilter').change(function() {
                let filter = $(this).val();
                $('tbody tr').show();

                if (filter === 'ins_expiring') {
                    $('tbody tr').filter(function() {
                        return $(this).attr('data-ins-exp') !== 'yes';
                    }).hide();
                } else if (filter === 'tax_expiring') {
                    $('tbody tr').filter(function() {
                        return $(this).attr('data-tax-exp') !== 'yes';
                    }).hide();
                }
            });

            // ฟังก์ชันค้นหาจากเลขทะเบียน
            $('#searchInput').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $('tbody tr').filter(function() {
                    $(this).toggle($(this).find('td:first').text().toLowerCase().indexOf(value) > -
                        1);
                });
            });
        });
    </script>

@endsection
