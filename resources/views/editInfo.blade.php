@extends('layout')
@section('doc', 'Edit')
@section('content')
    <h3 class="text-center">
        แก้ไขข้อมูล
    </h3>
    <div class="container py-4 ">
        {{-- <form method="POST" action="{{route('updateInfo', $car->id, $customer->id,$List->id) }}" id="editInfo" enctype="multipart/form-data" > --}}
            <form method="POST" action="{{route('updateInfo', $car->id, $customer->id) }}" id="editInfo" enctype="multipart/form-data" >
            @csrf
            <div class="row ms-auto justify-content-center">
                <div class="col-md-9">
                    <h5 class="container">
                        ข้อมูลลูกค้า
                    </h5>
                    <div class="row">
                        <div class="col-md-6 ">
                            <label for="CustomerName" class="form-label">ชื่อและนามสกุล</label>
                            <input type="text" class="form-control" name="CustomerName" class="form-control"
                                value="{{ $customer->CustomerName }}">
                            @error('CustomerName')
                                <div class="my-1">
                                    <span class="text-danger">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="NationalID" class="form-label">เลขบัตรประชาชน</label>
                            <input type="text" class="form-control" name="NationalID" class="form-control"
                                value="{{ $customer->NationalID }}">
                            @error('NationalID')
                                <div class="my-1">
                                    <span class="text-danger">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="PhoneNumber" class="form-label">เบอร์โทร</label>
                            <input type="number" class="form-control" name="PhoneNumber" class="form-control"
                                value="{{ $customer->PhoneNumber }}">
                            @error('PhoneNumber')
                                <div class="my-1">
                                    <span class="text-danger">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <label for="Address" class="form-label">ที่อยู่จัดส่ง</label>
                            <input type="text" class="form-control" name="Address" class="form-control"
                                value="{{ $customer->Address }}">
                            @error('Address')
                                <div class="my-1">
                                    <span class="text-danger">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="row ms-auto form-group justify-content-center">
                    <div class="col-md-9">
                        <hr>
                        <h5 class="container">
                            ข้อมูลรถ
                        </h5>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="CarNumber" class="form-label">เลขทะเบียน</label>
                                <input type="text" class="form-control" name="CarNumber" class="form-control"
                                    value="{{ $car->CarNumber }}">
                                @error('CarNumber')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="CarCity" class="form-label">จังหวัด</label>
                                <select name="CarCity" class="form-select">
                                    <option selected>{{ $car->CarCity}} </option>
                                    @foreach ($prov as $item)
                                        <option value="{{ $item->name }}"> {{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('CarCity')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="RegistrationDate" class="form-label">วันที่จดทะเบียน</label>
                                <input type="text" class="form-control datepicker" name="RegistrationDate" readonly
                                    class="form-control" value="{{ $car->RegistrationDate }}">
                                @error('RegistrationDate')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="TaxHistoryDate" class="form-label">วันที่ต่อภาษีครั้งล่าสุด</label>
                                <input type="text" class="form-control datepicker" name="TaxHistoryDate" readonly
                                    class="form-control" value="{{ $car->TaxHistoryDate }}">
                                @error('TaxHistoryDate')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="InsHistoryDate" class="form-label">วันที่ต่อ พ.ร.บ. ครั้งล่าสุด</label>
                                <input type="text" class="form-control datepicker" name="InsHistoryDate" readonly
                                    class="form-control" value="{{ $car->InsHistoryDate }}">
                                @error('InsHistoryDate')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="InsuranceType" class="form-label">ประเภท พ.ร.บ.</label>
                                <select name="InsuranceType" class="form-select">
                                    <option selected> {{ $car->InsuranceType }}</option>
                                    @foreach ($sett["type"] as $item )
                                        <option value="{{$item->name}}"> {{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('InsuranceType')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label for="TaxType" class="form-label">ประเภทภาษี</label>
                                <select name="TaxType" class="form-select">
                                    <option selected>{{ $car->TaxType }}</option>
                                    @foreach ($tax as $item )
                                        <option value="{{$item->name}}"> {{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('TaxType')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label for="CarCC" class="form-label">ขนาดกำลัง(CC)</label>
                                <input type="text" class="form-control" name="CarCC" class="form-control"
                                    value="{{ $car->CarCC }}">
                                @error('CarCC')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label for="CarWeight" class="form-label">น้ำหนัก(ก.ก.)</label>
                                <input type="text" class="form-control" name="CarWeight" class="form-control"
                                    value="{{ $car->CarWeight }}">
                                @error('CarWeight')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label for="SelectOption" class="form-label">การรับเอกสาร</label>
                                <select name="SelectOption" class="form-select">
                                    <option selected>{{ $car->SelectOption }} </option>
                                        <option>มารับเอง</option>
                                        <option>จัดส่งตามที่อยู่</option>
                                </select>
                                {{-- <label for="SelectOption" class="form-label">การรับเอกสาร</label>
                                <input type="text" class="form-control" name="SelectOption">--}}
                                @error('SelectOption')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="singleFile" class="form-label">สำเนาเล่มทะเบียน</label>
                                <input type="file" class="form-control" name="singleFile"
                                value="{{ $car->BookOwner }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="errMsg">
                    <ul>
                        @foreach ($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="container col-2 mx-auto">
                    <input type="submit" value="บันทึก" class="btn my-3" style="background-color:#A4F02A">
                    <a href="{{route('infomation',$car->id,$customer->id)}}" class="btn my-3" style="background-color:#f87979">ยกเลิก</a>
                </div>
            </div>
        </form>
    </div>
@endsection
