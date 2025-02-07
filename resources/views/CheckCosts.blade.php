@extends('layout')
@section('doc', 'Check costs')
@section('content')
    <div class="container my-5">
        <h3 class="text-center mb-4">ตรวจสอบค่าดำเนินการ</h3>

        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                @if ($car)
                    <div class="card p-4 shadow-sm">
                        <div class="mb-3">
                            <p><strong>ประเภท :</strong> {{ $car->InsuranceType }}</p>
                        </div>
                        <div class="mb-3">
                            <p><strong>ประเภท(เลข) :</strong> {{ $car->TypeId }}</p>
                        </div>
                        <div class="mb-3">
                            @foreach ($settRe as $setting)
                                @if ($car->TypeId == $setting->cartype_id)
                                    <p><strong>ค่า พ.ร.บ. (บาท) :</strong> {{ $setting->renew_cost }}</p>
                                @endif
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <p><strong>ค่าภาษี (บาท) :</strong> {{ $sum }} </p>
                        </div>
                        <div class="mb-3">
                            @foreach ($settRe as $setting)
                                @if ($car->TypeId == $setting->cartype_id)
                                    <p><strong>ค่าบริการ (บาท) :</strong> {{ $setting->fee }}</p>
                                @endif
                            @endforeach
                        </div>
                        <div class="mb-3">
                            @foreach ($settRe as $setting)
                                @if ($car->TypeId == $setting->cartype_id)
                                    <p><strong>ค่าจัดส่ง (บาท) :</strong> {{ $setting->delivery_cost }}</p>
                                @endif
                            @endforeach
                        </div>
                        <div class="mb-3">
                            <p><strong>ยอดเงินสุทธิ (บาท) :</strong> -- </p>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning text-center" role="alert">
                        ไม่มีข้อมูลลูกค้า
                    </div>
                @endif
            </div>
        </div>

        <div class="row d-flex justify-content-center gap-3">
            <div class="col-auto">
                <a href="/info" class="btn my-3" style="background-color:#9fdffa">กลับ</a>
            </div>
            <div class="col-auto">
                <a href="{{ route('history', $customer->id, $car->id) }}" class="btn my-3"
                    style="background-color:#A4F02A">ดำเนินการ</a>
            </div>
            
        </div>
    </div>
@endsection
