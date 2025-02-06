@extends('layout')
@section('doc', 'infomation')
@section('content')
    <div class="container">
        <div class="input-group mb-3">
            <div class="col-md-2">
                <select id="CarBrand" class="form-select">
                    <option selected>ค้นหาจาก...</option>
                    <option>เลขทะเบียน</option>
                    <option>ชื่อลูกค้า</option>
                </select>
            </div>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" aria-label="Search">
                <button class="btn" style="background-color: #F7CBC7" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>
    <hr>
    <table class="table table-bordered">
        <thead class="table text-center" style="background-color:#F7CBC7">
            <tr>
                <th scope="col">เลขทะเบียน</th>
                <th scope="col">จังหวัด</th>
                <th scope="col">ชื่อ</th>
                <th scope="col">เบอร์โทร</th>
                <th scope="col">ต่อ พ.ร.บ.</th>
                <th scope="col">ข้อมูลลูกค้า</th>
                <th scope="col">ข้อมูลยานพาหนะ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cars as $item)
                <tr>
                    <td>{{ $item->CarNumber}}</td>
                    <td>{{$item->CarCity}}</td>
                    {{-- <td class="text-center"><a href="{{ route('editCus', $item->id) }}"
                            class="btn btn-warning">แก้ไขข้อมูล</a></td>
                    <td class="text-center"><a href="{{ route('deleteCus', $item->id) }}" class=" btn btn-danger"
                            onclick="return confirm('คุณต้องการลบข้อมูลลูกค้าคุณ {{ $item->CustomerName }} หรือไม่ ?')">
                            ลบ
                        </a></td> --}}
                </tr>
            @endforeach
            @foreach ($customers as $item)
                <tr>
                    <td>{{ $item->CustomerName }}</td>
                    <td>{{ $item->PhoneNumber }}</td>
                    <td class="text-center"><a href="#" class="btn" style="background-color:#A4F02A">ต่อ พ.ร.บ.</a></td>
                    <td class="text-center"><a href="#" class="btn" style="background-color:#A2D7FF">ข้อมูลลูกค้า</a></td>
                    <td class="text-center"><a href="#" class="btn" style="background-color:#A2D7FF">ข้อมูลยานพาหนะ</a></td>
                </tr>

            @endforeach
        </tbody>
    </table>

    <div class="container col-2 mx-auto ">
        <a href="add" class="btn" style="background-color:#A4F02A">เพิ่มข้อมูล</a>
    </div>
    </div>

@endsection
