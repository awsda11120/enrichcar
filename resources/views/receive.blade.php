@extends('layout')
@section('doc', 'receive')
@section('content')
    <div class="input-group mb-3 d-flex align-items-center">
        <div class="me-3">
            ค้นหาเลขทะเบียน
        </div>
        <form class="d-flex col-md-2" role="search">
            <input id="searchInput" class="form-control me-2" type="search" aria-label="Search"
                placeholder="ค้นหาเลขทะเบียน...">
            {{-- <button class="btn" style="background-color: #F7CBC7" type="submit">
                <i class="bi bi-search"></i>
            </button> --}}
        </form>
    </div>
    <table class="table table-bordered">
        <thead class="table-danger">
            <tr>
                <th>ทะเบียนรถ</th>
                <th>ชื่อ</th>
                <th>เบอร์โทร</th>
                <th>สถานะ</th>
                <th>เลขพัสดุ</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>สต5588  นนทบุรี</td>
                <td>อาพร มณีเลิศ</td>
                <td>0615458588</td>
                <td class="text-danger">จัดส่งที่บ้าน</td>
                <td>TH1248564136</td>
                <td>
                    <a href="#" class="btn mx-2" style="background-color:#A4F02A">บันทึก</a>
                    <a href="#" class="btn mx-2" style="background-color:#F0DF2A">แก้ไข</a>
                </td>
            </tr>
            <tr>
                <td>กด2669 ปทุมธานี</td>
                <td>สมศรี จอมทอง</td>
                <td>0615458588</td>
                <td class="text-danger">จัดส่งที่บ้าน</td>
                <td><input type="text" class="form-control" placeholder="กรุณากรอกเลขพัสดุ"></td>
                <td>
                    <a href="#" class="btn mx-2" style="background-color:#A4F02A">บันทึก</a>
                    <a href="#" class="btn mx-2" style="background-color:#F0DF2A">แก้ไข</a>
                </td>
            </tr>
            <tr>
                <td>5กน1458 นครราชสีมา</td>

                <td>ดวงจันทร์ สกุลเด่น</td>
                <td>0615458588</td>
                <td class="text-primary">มารับเอง</td>
                <td><input type="file" class="form-control"></td>
                <td>
                    <a href="#" class="btn mx-2" style="background-color:#A4F02A">บันทึก</a>
                    <a href="#" class="btn mx-2" style="background-color:#F0DF2A">แก้ไข</a>
                </td>
            </tr>
        </tbody>
    </table>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
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
