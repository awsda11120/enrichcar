@extends('layout')
@section('doc', 'InsView')
@section('content')
    <div class="container">
        <div class="input-group mb-3">
              <!-- ค้นหาและตัวเลือกการแสดงข้อมูล -->
            {{-- <div class="col-md-2">
                <select id="CarBrand" class="form-select">
                    <option selected>แสดงข้อมูล...</option>
                    <option>แสดงข้อมูลทั้งหมด</option>
                    <option>แสดงข้อมูลที่ พ.ร.บ จะหมดอายุ</option>
                    <option>แสดงข้อมูลที่ ภาษี จะหมดอายุ</option>
                </select>
            </div>
            <form action="{{ route('info') }}" method="GET"> --}}
                <select name="status" id="status">
                    <option value="">-- แสดงข้อมูลจาก --</option>
                    {{-- <option value="last" {{ request('status') == 'last' ? 'selected' : '' }}>แสดงข้อมูลจากที่เพิ่มล่าสุด</option> --}}
                    <option href="{{ route('InsView') }}">แสดงข้อมูลจาก พ.ร.บ. ที่กำลังจะหมดอายุ</option>
                    {{-- <option value="tax" {{ rout('Tax')}}>แสดงข้อมูลจากภาษีที่กำลังจะหมดอายุ</option> --}}
                </select>


            {{-- </form> --}}

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
                {{-- <th scope="col">วันหมดอายุของภาษี</th> --}}
                <th scope="col">ต่อ พ.ร.บ. / ต่อภาษี</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($list as $item)
                <tr class="{{ $item->cls }}">
                    <td>{{ $item->CarNumber }}</td>
                    <td>{{ $item->CustomerName }}</td>
                    <td>{{ $item->PhoneNumber }}</td>
                    <td>{{ $item->next_Ins }}</td>
                    {{-- <td>{{ $item->renew }}</td> --}}
                    <td style="background:#FFF!important;">
                        <a href="{{ route('infomation', $item->id) }}" class="btn btn-light btn-sm"
                            style="background-color:#A4F02A">ดำเนินการต่อ</a>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
    </div>

@endsection
