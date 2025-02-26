@extends('layout')
@section('doc', 'History')
@section('content')

    <div class="row">
        <div class="col-md-2 ">
            <label for="CustomerName" class="form-label fs-5">ประวัติการดำเนินการ</label>
        </div>
        <div class="col-md-1 ">
            <label for="CustomerName" class="form-label fs-5">จาก</label>
        </div>
        <div class="col-md-3 ">
            <input type="text" class="form-control datepicker" name="RegistrationDate" readonly>
        </div>
        <div class="col-md-1 ">
            <label for="CustomerName" class="form-label fs-5">ถึง</label>
        </div>
        <div class="col-md-3 ">
            <input type="text" class="form-control datepicker" name="RegistrationDate" readonly>
        </div>
        <div class="col-md-2 ">
            <span><a href="#" class="btn mx-2" style="background-color:#F7CBC7">ค้นหา</a></span>
        </div>
    </div>
    <hr>
    <table class="table  table-grid">
        <thead class="text-center">
            <tr>
                <th scope="col">วันที่ดำเนินการต่อ</th>
                <th scope="col">ทะเบียนรถ</th>
                <th scope="col">พ.ร.บ.</th>
                <th scope="col">ภาษี</th>
                <th scope="col">การรับเอกสาร</th>
                <th scope="col">ดาวน์โหลด</th>
                <th scope="col">สถานะ</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($list as $item)
                <tr>
                    <td><input type="text" class="form-control datepicker col-md-2" name="RegistrationDate" readonly>
                    </td>
                    <td>{{ $item->CarNumber }}</td>
                    <td>
                        @if ($item->TypeRenewIns == 1)
                            <span class="text-green-500">✔</span>
                        @else
                            <span class="text-red-500">✘</span>
                        @endif
                    </td>
                    <td>
                        @if ($item->TypeRenewTax == 1)
                            <span class="text-green-500">✔</span>
                        @else
                            <span class="text-red-500">✘</span>
                        @endif
                    </td>
                    <td>{{ $item->Receive }}</td>
                    <td>{{ $item->CarNumber }}</td>
                    <td style="background:#FFF!important;">
                        <a href="#" class="btn btn-light btn-sm" style="background-color:#A4F02A">เสร็จสิ้น</a>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
