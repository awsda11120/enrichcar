@extends('layout')
@section('doc', 'Show Information')
@section('content')
    <div class="container py-5">
        <h3 class="text-center mb-4 display-6 ">ต่อ พ.ร.บ. / ต่อภาษี</h3>

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card mb-4" style="border-color: #F7CBC7;">
                    <div class="card-header text-center rounded-top" style="background-color: #F7CBC7;">
                        <h5 class="mb-0">ข้อมูลลูกค้า</h5>
                    </div>
                    <div class="card-body">
                        @if ($list)
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="CustomerName" class="form-label fw-bold">ชื่อและนามสกุล:</label>
                                    <p class="form-text">{{ $list->CustomerName }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="NationalID" class="form-label fw-bold">เลขบัตรประชาชน:</label>
                                    <p class="form-text">{{ $list->NationalID }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="PhoneNumber" class="form-label fw-bold">เบอร์โทร:</label>
                                    <p class="form-text">{{ $list->PhoneNumber }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="Address" class="form-label fw-bold">ที่อยู่ปัจจุบัน:</label>
                                    <p class="form-text">{{ $list->Address }}</p>
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
                            @if ($list->BookOwner)
                                <img src="/upload/doc/{{ $list->BookOwner }}" alt="Image"
                                    class="img-fluid rounded shadow-sm" style="max-width: 100%; height: auto;">
                            @else
                                <p class="text-center text-danger fw-bold">ไม่มีข้อมูลรถ</p>
                            @endif
                            {{-- <div class="col-md-6">
                                <label for="total_year" class="form-label fw-bold"> อายุรถ(ปี):</label>
                                <label class="form-label">{{ $total_year }}</label>
                            </div> --}}
                            <div class="col-md-6">
                                <label for="total_year" class="form-label fw-bold">อายุรถ:</label>
                                <label class="form-label">{{ $carYears }} ปี</label>
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
                                <label class="form-label">{{ $list->SelectOption }}</label>
                                {{-- @error('SelectOption')
                                    <div class="my-1">
                                        <span class="text-danger">{{ $message }}</span>
                                    </div>
                                @enderror --}}

                            </div>
                            <div class="col-md-6">
                                <label for="TaxHistoryDate" class="form-label fw-bold"> ต่อภาษีครั้งล่าสุด:</label>
                                <label class="form-label">
                                    {{ \Carbon\Carbon::parse($list->TaxHistoryDate)->format('d/m/Y') }}
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label for="InsHistoryDate" class="form-label fw-bold">ต่อ พรบ. ครั้งล่าสุด:</label>
                                <label class="form-label">
                                    {{ \Carbon\Carbon::parse($list->InsHistoryDate)->format('d/m/Y') }}
                                </label>
                            </div>

                        </div>
                    </div>
                </div>
                <form action="{{ route('CheckCosts', ['id' => $list->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $list->id }}">

                    <div class="container">
                        <div class="row">
                            <div class="form-check form-check col-md-3 offset-md-10">
                                <input type="checkbox" name="renew_prb" value="1"
                                    @if ($list->days_ins > 90) disabled @endif> ต่อ พ.ร.บ.
                            </div>
                            <div class="form-check form-check col-md-3 offset-md-10">
                                <input type="checkbox" name="renew_tax" value="1"
                                    @if ($list->days > 90) disabled @endif> ต่อภาษี
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="javascript:history.back()" class="btn my-3" style="background-color:#9fdffa"> กลับ</a>
                        <a href="{{ route('editInfo', ['id' => $list->id]) }}" class="btn my-3"
                            style="background-color:#F0DF2A">แก้ไข</a>
                        <button type="submit" class="btn my-3" style="background-color:#A4F02A">ดำเนินการ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let daysIns = Number("{{ $list->days_ins ?? 0 }}"); // ถ้าเป็น null จะใช้ 0
            let daysTax = Number("{{ $list->days ?? 0 }}"); // ถ้าเป็น null จะใช้ 0

            let renewPrb = document.querySelector('input[name="renew_prb"]');
            let renewTax = document.querySelector('input[name="renew_tax"]');
            let submitBtn = document.querySelector('button[type="submit"]');

            // ตรวจสอบเงื่อนไขว่าเหลือวันมากกว่า 90 หรือไม่
            if (renewPrb && daysIns <= 90) {
                renewPrb.disabled = false; // ถ้าเหลือวันน้อยกว่าหรือเท่ากับ 90 ให้เปิดใช้งาน
            } else {
                renewPrb.disabled = true; // ถ้าเหลือวันมากกว่า 90 ให้ปิดใช้งาน
            }

            if (renewTax && daysTax <= 90) {
                renewTax.disabled = false; // ถ้าเหลือวันน้อยกว่าหรือเท่ากับ 90 ให้เปิดใช้งาน
            } else {
                renewTax.disabled = true; // ถ้าเหลือวันมากกว่า 90 ให้ปิดใช้งาน
            }

            // ฟังก์ชันในการเช็คสถานะของ checkbox
            function checkCheckboxStatus() {
                if (!renewPrb.checked && !renewTax.checked || renewPrb.disabled && renewTax.disabled) {
                    submitBtn.disabled = true; // ปิดปุ่มเมื่อไม่มีการติ๊ก
                    submitBtn.style.backgroundColor = '#ccc'; // เปลี่ยนสีปุ่มให้ทึบ
                } else {
                    submitBtn.disabled = false; // เปิดปุ่มเมื่อมีการติ๊ก
                    submitBtn.style.backgroundColor = '#A4F02A'; // เปลี่ยนสีปุ่มให้เป็นปกติ
                }
            }

            // ตรวจสอบสถานะเมื่อเริ่มโหลด
            checkCheckboxStatus();

            // เชื่อมโยงฟังก์ชันกับการเปลี่ยนแปลงของ checkbox
            renewPrb.addEventListener('change', checkCheckboxStatus);
            renewTax.addEventListener('change', checkCheckboxStatus);
        });
    </script>
@endsection
