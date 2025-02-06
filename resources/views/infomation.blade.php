@extends('layout')
@section('doc', 'Show Information')
@section('content')
    <div class="container py-5">
        <h3 class="text-center mb-4 display-6 ">ต่อ พรบ./ต่อภาษี</h3>

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card mb-4" style="border-color: #F7CBC7;">
                    <div class="card-header text-center rounded-top" style="background-color: #F7CBC7;">
                        <h5 class="mb-0">ข้อมูลลูกค้า</h5>
                    </div>
                    <div class="card-body">
                        @if ($customer)
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="CustomerName" class="form-label fw-bold">ชื่อและนามสกุล:</label>
                                    <p class="form-text">{{ $customer->CustomerName }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="NationalID" class="form-label fw-bold">เลขบัตรประชาชน:</label>
                                    <p class="form-text">{{ $customer->NationalID }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="PhoneNumber" class="form-label fw-bold">เบอร์โทร:</label>
                                    <p class="form-text">{{ $customer->PhoneNumber }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="Address" class="form-label fw-bold">ที่อยู่ปัจจุบัน:</label>
                                    <p class="form-text">{{ $customer->Address }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-center text-danger fw-bold">ไม่มีข้อมูลลูกค้า</p>
                        @endif
                    </div>
                </div>


                <div class="card mb-4" style="border-color: #F7CBC7;">
                    <div class="card-header text-center rounded-top" style="background-color: #F7CBC7;">
                        <h5 class="mb-0">ข้อมูลรถ</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            @if ($car->BookOwner)
                                <img src="/upload/doc/{{ $car->BookOwner }}" alt="Image"
                                    class="img-fluid rounded shadow-sm" style="max-width: 100%; height: auto;">
                            @else
                                <p class="text-center text-danger fw-bold">ไม่มีข้อมูลรถ</p>
                            @endif
                            <div class="col-md-6">
                                <label for="total_year" class="form-label fw-bold"> อายุรถ(ปี):</label>
                                <label class="form-label">{{ $total_year }}</label>
                            </div>
                            
                            <div class="col-md-6">
                                {{-- <label for="SelectOption" class="form-label fw-bold">การรับเอกสาร:</label> --}}
                                {{-- <label for="SelectOption" class="form-label">การรับเอกสาร</label> --}}
                                {{-- <select name="SelectOption" class="form-select">
                                    <option selected>เลือก...</option>
                                    <option>มารับเอง</option>
                                    <option>จัดส่งตามที่อยู่</option>
                                </select> --}}
                                <label for="SelectOption" class="form-label fw-bold">การรับเอกสาร :</label>
                                <label class="form-label">{{ $car->SelectOption }}</label>
                                {{-- @error('SelectOption')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror --}}

                            </div>
                            <div class="col-md-6">
                                <label for="TaxHistoryDate" class="form-label fw-bold"> ต่อภาษีครั้งล่าสุด:</label>
                                <label class="form-label">
                                    {{ \Carbon\Carbon::parse($car->TaxHistoryDate)->format('d/m/Y') }}
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label for="InsHistoryDate" class="form-label fw-bold">ต่อ พรบ. ครั้งล่าสุด:</label>
                                <label class="form-label">
                                    {{ \Carbon\Carbon::parse($car->InsHistoryDate)->format('d/m/Y') }}
                                </label>
                            </div>

                            <div class="container">
                                <div class="row">
                                    <div class="form-check form-check col-md-3 offset-md-10">
                                        <input class="form-check-input  " type="checkbox" id="inlineCheckbox1"
                                            value="ต่อ พรบ.">
                                        <label class="form-check-label  " for="inlineCheckbox1">ต่อ พรบ.</label>
                                    </div>
                                    <div class="form-check form-check  col-md-3 offset-md-10">
                                        <input class="form-check-input  " type="checkbox" id="inlineCheckbox2"
                                            value="ต่อภาษี">
                                        <label class="form-check-label  " for="inlineCheckbox2">ต่อภาษี</label>
                                    </div>
                                    
                                    {{-- <div class="form-check form-check-reverse ">
                                    <input class="form-check-input" type="checkbox" value="ต่อ พรบ." id="reverseCheck1">
                                    <label class="form-check-label" for="reverseCheck1">ต่อ พรบ.</label>
                                </div>
                                <div class="form-check form-check-reverse ">
                                    <input class="form-check-input" type="checkbox" value="ต่อ พรบ." id="reverseCheck1">
                                    <label class="form-check-label" for="reverseCheck1">ต่อภาษี</label>
                                </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="d-flex justify-content-between align-items-center">
                    <a href="/info" class="btn my-3" style="background-color:#9fdffa"> กลับ</a>
                    <a href="{{ route('editInfo', $customer->id, $car->id) }}" class="btn my-3"
                        style="background-color:#F0DF2A">แก้ไข</a>
                    <a href="{{ route('CheckCosts', $customer->id, $car->id) }}" class="btn my-3"
                        style="background-color:#A4F02A">ดำเนินการ</a>
                </div>
            </div>
        </div>
    </div>
@endsection
