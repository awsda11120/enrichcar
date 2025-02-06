{{-- @extends('layout')
@section('doc', 'Infomationn')
@section('content')
    <div class="container">
        <div class="input-group mb-3">
            <!-- ค้นหาและตัวเลือกการแสดงข้อมูล -->

            <div class="col-md-2">
                <select id="CarBrand" class="form-select">
                    <option selected>แสดงข้อมูล...</option>
                    <option>แสดงข้อมูลทั้งหมด</option>
                    <option>แสดงข้อมูลที่ พ.ร.บ จะหมดอายุ</option>
                    <option>แสดงข้อมูลที่ ภาษี จะหมดอายุ</option>
                </select>
            </div>
            <form class="d-flex ms-2" role="search">
                <input class="form-control me-2" type="search" aria-label="Search" placeholder="ค้นหาจากเลขทะเบียน...">
                <button class="btn" style="background-color: #F7CBC7" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <span><a href="add" class="btn mx-3" style="background-color:#A4F02A">เพิ่มข้อมูล</a></span>
        </div>
    </div>
    <hr>
    <table class="table  table-grid">
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
                <tr class="{{ $item->cls}}">
                    <td style="background:#FFF!important;">{{ $item->CarNumber }}</td>
                    <td style="background:#FFF!important;">{{ $item->CustomerName }}</td>
                    <td style="background:#FFF!important;">{{ $item->PhoneNumber }}</td>
                    <td>{{ $item->next_Ins }} </td>
                    <td>{{ $item->renew }}</td>
                    <td style="background:#FFF!important;">
                        <a href="{{ route('infomation', $item->id ) }}" class="btn btn-light btn-sm"
                            style="background-color:#A4F02A">ดำเนินการต่อ</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>

@endsection --}}
@extends('layout')
@section('doc', 'ข้อมูบลรถ')
@section('content')
    <div class="container">
        <div class="input-group mb-3">
            <!-- ค้นหาและตัวเลือกการแสดงข้อมูล -->
            <div class="col-md-2">
                <select id="CarFilter" class="form-select">
                    <option value="all" selected>แสดงข้อมูล...</option>
                    <option value="all">แสดงข้อมูลทั้งหมด</option>
                    <option value="ins_expiring">แสดงข้อมูลที่ พ.ร.บ จะหมดอายุ</option>
                    <option value="tax_expiring">แสดงข้อมูลที่ ภาษี จะหมดอายุ</option>
                </select>
            </div>
            <form class="d-flex ms-2" role="search">
                <input id="searchInput" class="form-control me-2" type="search" aria-label="Search" placeholder="ค้นหาจากเลขทะเบียน...">
                <button class="btn" style="background-color: #F7CBC7" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <span><a href="add" class="btn mx-3" style="background-color:#A4F02A">เพิ่มข้อมูล</a></span>
        </div>
    </div>
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
                <tr class="{{ $item->cls }}"
                    data-ins-exp="{{ strtotime($item->next_Ins) < strtotime('+90 days') ? 'yes' : 'no' }}"
                    data-tax-exp="{{ strtotime($item->renew) < strtotime('+90 days') ? 'yes' : 'no' }}">

                    <td style="background:#FFF!important;">{{ $item->CarNumber }}</td>
                    <td style="background:#FFF!important;">{{ $item->CustomerName }}</td>
                    <td style="background:#FFF!important;">{{ $item->PhoneNumber }}</td>
                    <td>{{ $item->next_Ins }}</td>
                    <td>{{ $item->renew }}</td>
                    <td style="background:#FFF!important;">
                        <a href="{{ route('infomation', $item->id ) }}" class="btn btn-light btn-sm"
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
                    $(this).toggle($(this).find('td:first').text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>

@endsection
