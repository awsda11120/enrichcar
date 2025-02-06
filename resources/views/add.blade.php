@extends('layout')
@section('doc', 'Add')
@section('content')
    <h3 class="text-center">
        เพิ่มข้อมูล
    </h3>
    <div class="container py-4 ">
        <form method="POST" action="/insertInfo" id="addinfo" enctype="multipart/form-data">
            @csrf
            <div class="row ms-auto justify-content-center">
                <div class="col-md-9">
                    <h5 class="container">
                        ข้อมูลลูกค้า
                    </h5>
                    <div class="row">
                        <div class="col-md-6 ">
                            <label for="CustomerName" class="form-label">ชื่อและนามสกุล</label>
                            <input type="text" class="form-control" name="CustomerName">
                            @error('CustomerName')
                                <div class="my-1">
                                    <span class="text-danger">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="NationalID" class="form-label">เลขบัตรประชาชน</label>
                            <input type="text" class="form-control" name="NationalID">

                            {{--
                            <input type="checkbox" name="NationalID"> sdadasd

                            <label for="A1">
                                <input type="radio" id="A1" value="AAA" name="NationalIDx"> AAAA
                            </label>
                            <input type="radio"  value="BBB" name="NationalIDx"> BB --}}


                            @error('NationalID')
                                <div class="my-1">
                                    <span class="text-danger">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="PhoneNumber" class="form-label">เบอร์โทร</label>
                            <input type="number" class="form-control" name="PhoneNumber">
                            @error('PhoneNumber')
                                <div class="my-1">
                                    <span class="text-danger">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <label for="Address" class="form-label">ที่อยู่ปัจจุบัน</label>
                            <input type="text" class="form-control" name="Address">
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
                                <input type="text" class="form-control" name="CarNumber">
                                @error('CarNumber')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="CarCity" class="form-label">จังหวัด</label>
                                <select name="CarCity" class="form-select">
                                    <option selected>เลือก...</option>
                                    @foreach ($prov as $item )
                                        <option value="{{$item->name}}"> {{$item->name}}</option>
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
                                <input type="text" class="form-control datepicker" name="RegistrationDate" readonly>
                                @error('RegistrationDate')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="TaxHistoryDate" class="form-label">วันที่ต่อภาษีครั้งล่าสุด</label>
                                <input type="text" class="form-control datepicker" name="TaxHistoryDate" readonly>
                                @error('TaxHistoryDate')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="InsHistoryDate" class="form-label">วันที่ต่อ พ.ร.บ. ครั้งล่าสุด</label>
                                <input type="text" class="form-control datepicker" name="InsHistoryDate" readonly>
                                @error('InsHistoryDate')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="InsuranceType" class="form-label">ประเภท พ.ร.บ.</label>
                                <select name="InsuranceType" class="form-select">
                                    <option selected>เลือก...</option>
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
                            <div class="col-md-3">
                                <label for="TaxType" class="form-label">ประเภทภาษี</label>
                                <select name="TaxType" class="form-select">
                                    <option selected>เลือก...</option>
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

                            
                            {{-- <div class="col-md-3">
                                <label for="CarBrand" class="form-label">ยี่ห้อ</label>
                                <select name="CarBrand" class="form-select">
                                    <option selected>เลือก...</option>
                                    @foreach ($sett["brand"] as $item )
                                        <option value="{{$item->name}}"> {{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('CarBrand')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div> --}}
                            {{-- <div class="col-md-3">
                                <label for="CarCollection" class="form-label">รุ่น</label>
                                <input type="text" class="form-control" name="CarCollection">
                                @error('CarCollection')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label for="CarColor" class="form-label">สี</label>
                                <input type="text" class="form-control" name="CarColor">
                                @error('CarColor')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div> --}}
                            <div class="col-md-2">
                                <label for="CarCC" class="form-label">ขนาดกำลัง(CC)</label>
                                <input type="text" class="form-control" name="CarCC">
                                @error('CarCC')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label for="CarWeight" class="form-label">น้ำหนัก(ก.ก.)</label>
                                <input type="text" class="form-control" name="CarWeight">
                                @error('CarWeight')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label for="SelectOption" class="form-label">การรับเอกสาร</label>
                                <select name="SelectOption" class="form-select">
                                    <option selected>เลือก...</option>
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
                            {{-- <div class="col-md-3">
                                <label for="VehicleNumber" class="form-label">เลขตัวรถ</label>
                                <input type="text" class="form-control" name="VehicleNumber">
                                @error('VehicleNumber')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div> --}}
                            <div class="col-md-12">
                                <label for="singleFile" class="form-label">สำเนาเล่มทะเบียน</label>
                                <input type="file" class="form-control" name="singleFile">
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-md-3">
                            <label for="ImageCopyBook" class="form-label">สำเนาเล่มทะเบียน</label>
                            <input class="form-control" type="file" id="ImageCopyBook">
                    </div> --}}
                </div>
                <div class="errMsg">
                    <ul>
                        @foreach ($errors as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="container col-2 mx-auto">
                    <input type="submit" value="บันทึก" class="btn my-3" style="background-color:#A4F02A">
                    <a href="/" class="btn btn-warning my-3" style="background-color:#F0DF2A">กลับ</a>
                </div>
            </div>
        </form>
    </div>
@endsection
