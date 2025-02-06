@extends('layout')
@section('doc', 'ข้อมูลของยานพาหนะ')
@section('content')
    <div class="container py-4">

        <h4 class="container text-center">
            ข้อมูลของยานพาหนะ
        </h4>
        <table class="table table-bordered">
            <thead class="table-info text-center">
                <tr>
                    <th scope="col">วันที่จดทะเบียน</th>
                    <th scope="col">เลขทะเบียน</th>
                    <th scope="col">จังหวัด</th>
                    <th scope="col">ประเภท</th>
                    <th scope="col">ยี่ห้อ</th>
                    <th scope="col">แบบ</th>
                    <th scope="col">สี</th>
                    <th scope="col">ขนาดกำลัง</th>
                    <th scope="col">น้ำหนัก</th>
                    <th scope="col">เลขตัวรถ</th>
                    <th scope="col">อายุ</th>
                    <th scope="col">แก้ไขข้อมูล</th>
                    <th scope="col">ลบข้อมูล</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cars as $item)
                <tr>
                    <td>{{ $item->RegistrationDate }}</td>
                    <td>{{ $item->CarNumber }}</td>
                    <td>{{ $item->CarCity }}</td>
                    <td>{{ $item->CarType }}</td>
                    <td>{{ $item->CarBrand }}</td>
                    <td>{{ $item->CarCollection }}</td>
                    <td>{{ $item->CarColor }}</td>
                    <td>{{ $item->CarCC }}</td>
                    <td>{{ $item->CarWeight }}</td>
                    <td>{{ $item->VehicleNumber }}</td>
                    <td>{{ $item->CarAge }}</td>
                    <td class="text-center"><a href="{{route('editCar',$item->id)}}" class="btn btn-warning">แก้ไขข้อมูล</a></td>
                    <td class="text-center"><a href="{{route('deleteCar',$item->id)}}"
                        class="btn btn-danger" onclick="return confirm('คุณต้องการลบข้อมูลยานพาหนะทะเบียน {{$item->CarNumber}} {{$item->CarCity}} หรือไม่ ?')">
                        ลบ
                    </a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <a href="/carF" class="btn btn-info">เพิ่มข้อมูล</a>
    </div>
@endsection
