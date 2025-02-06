@extends('layout')
@section('doc', 'Infomationn')
@section('content')
    <div class="container">
        <div class="input-group mb-3">
            <div class="col-md-2">
                <select id="CarBrand" class="form-select">
                    <option selected>แสดงข้อมูล...</option>
                    <option>แสดงข้อมูลทั้งหมด</option>
                    <option>แสดงข้อมูลที่ พ.ร.บ จะหมดอายุ</option>
                    <option>แสดงข้อมูลที่ ภาษี จะหมดอายุ</option>
                </select>
            </div>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" aria-label="Search">
                <button class="btn" style="background-color: #F7CBC7" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <span><a href="add" class="btn mx-3" style="background-color:#A4F02A">เพิ่มข้อมูล</a></span>
        </div>
    </div>
    <hr>
    <body>
        <h1>ผลลัพธ์ที่เลือก</h1>
        <p>คุณเลือกตัวเลือก: {{ $selectedOption }}</p>

        <a href="{{ route('info') }}">กลับไปหน้าเลือก</a>
    </body>
    {{-- <table class="table  table-grid">
        <thead class="text-center" >
            <tr>
                <th scope="col" >เลขทะเบียน</th>
                <th scope="col">ชื่อ</th>
                <th scope="col">เบอร์โทร</th>
                <th scope="col">วันหมดอายุของ พ.ร.บ.</th>
                <th scope="col">วันหมดอายุของภาษี</th>
                <th scope="col" >ต่อ พ.ร.บ. / ต่อภาษี</th>
                <th scope="col" >รายละเอียด</th>
            </tr>
        </thead >
        <tbody class="text-center">
            @foreach ($list as $item)
                <tr class="{{$item->cls}}">
                    <td>{{ $item->CarNumber }}</td>
                    <td>{{ $item->CustomerName }}</td>
                    <td>{{ $item->PhoneNumber }}</td>
                    <td>{{ $item->next_Ins }}</td>
                    <td>{{ $item->renew }}</td>
                    {{-- <td>
                        @if ($item->BookOwner)
                          <a href="/upload/doc/{{ $item->BookOwner }}" target="_blank" > เอกสาร </a>
                        @endif
                    </td> --}}
                    {{-- <td  style="background:#FFF!important;">
                        <button class="btn btn-light btn-sm" style="background-color: #A4F02A;" {{$item->disabled}}>ดำเนินการต่อ</button>
                    </td>
                    <td  style="background:#FFF!important;">
                        <span><a href="add" class="btn btn-light btn-sm" style="background-color:#9fdffa">รายละเอียด</a></span>
                        </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div> --}}
    {{-- <div class="container py-4">
        <h4 class="container text-center">
            ข้อมูลของลูกค้า
        </h4>
        <table class="table table-bordered ">
            <thead class="table-info text-center">
                <tr>
                    <th scope="col">ชื่อและนามสกุล</th>
                    <th scope="col">เลขบัตรประชาชน</th>
                    <th scope="col">เบอร์โทร</th>
                    <th scope="col">ที่อยู่ปัจจุบัน</th>
                    <th scope="col">แก้ไขข้อมูล</th>
                    <th scope="col">ลบข้อมูล</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $item)
                    <tr>
                        <td>{{ $item->CustomerName }}</td>
                        <td>{{ $item->NationalID }}</td>
                        <td>{{ $item->PhoneNumber }}</td>
                        <td>{{ $item->Address }}</td>
                        <td class="text-center"><a href="{{ route('editCus', $item->id) }}"class="btn btn-warning">แก้ไข</a>
                        </td>
                        <td class="text-center"><a href="{{ route('deleteCus', $item->id) }}" class=" btn btn-danger"
                                onclick="return confirm('คุณต้องการลบข้อมูลลูกค้าคุณ {{ $item->CustomerName }} หรือไม่ ?')">
                                ลบ
                            </a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="container py-4">
        <h4 class="container text-center">
            ข้อมูลของยานพาหนะ
        </h4>
        <table class="table table-bordered">
            <thead class="table-info text-center">
                <tr>
                    <th scope="col">ชื่อผู้ถือกรรมสิทธิ์</th>
                    <th scope="col">เลขทะเบียน</th>
                    <th scope="col">จังหวัด</th>
                    <th scope="col">วันที่จดทะเบียน</th>
                    <th scope="col">ประเภท</th>
                    <th scope="col">ยี่ห้อ</th>
                    <th scope="col">รุ่น</th>
                    <th scope="col">สี</th>
                    <th scope="col">ขนาดกำลัง</th>
                    <th scope="col">น้ำหนัก</th>
                    <th scope="col">เลขตัวรถ</th>
                    <th scope="col">แก้ไขข้อมูล</th>
                    <th scope="col">ลบข้อมูล</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($cars as $item)
                    <tr>
                        <td>{{ $item->OwnerName }}</td>
                        <td>{{ $item->CarNumber }}</td>
                        <td>{{ $item->CarCity }}</td>
                        <td>{{ $item->RegistrationDate }}</td>
                        <td>{{ $item->CarType }}</td>
                        <td>{{ $item->CarBrand }}</td>
                        <td>{{ $item->CarCollection }}</td>
                        <td>{{ $item->CarColor }}</td>
                        <td>{{ $item->CarCC }}</td>
                        <td>{{ $item->CarWeight }}</td>
                        <td>{{ $item->VehicleNumber }}</td>
                        <td class="text-center"><a
                                href="{{ route('editCar', $item->id) }}"class="btn btn-warning">แก้ไข</a>
                        </td>
                        <td class="text-center"><a href="{{ route('deleteCar', $item->id) }}" class="btn btn-danger"
                                onclick="return confirm('คุณต้องการลบข้อมูลยานพาหนะทะเบียน {{ $item->CarNumber }} {{ $item->CarCity }} หรือไม่ ?')">
                                ลบ
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div> --}}

@endsection
