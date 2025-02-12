@extends('layout')
@section('doc', 'Check costs')
@section('content')
    <div class="container my-5">
        <h3 class="text-center mb-4">ตรวจสอบค่าดำเนินการ</h3>

        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                @if ($data)
                    <div class="card p-4 shadow-sm">
                        <div class="mb-3">
                            <p><strong>ประเภท :</strong> {{ $data->InsuranceType }}</p>
                        </div>
                        <div class="mb-3">
                            <p><strong>ประเภท(เลข) :</strong> {{ $data->TypeId }}</p>
                        </div>
                        <div class="mb-3">
                            {{-- @foreach ($settRe as $setting)
                                @if ($data->TypeId == $setting->cartype_id) --}}
                                    <p><strong>ค่า พ.ร.บ. (บาท) :</strong> {{ $data->renew_cost }}</p>
                                {{-- @endif
                            @endforeach --}}
                        </div>

                        <div class="mb-3">
                            <p><strong>ค่าภาษี (บาท) :</strong> {{ $sum }} </p>
                        </div>
                        <div class="mb-3">
                            {{-- @foreach ($settRe as $setting)
                                @if ($data->TypeId == $setting->cartype_id) --}}
                                    <p><strong>ค่าบริการ (บาท) :</strong> {{ $data->fee }}</p>
                                {{-- @endif
                            @endforeach --}}
                        </div>
                        <div class="mb-3">
                            {{-- @foreach ($settRe as $setting)
                                @if ($data->TypeId == $setting->cartype_id) --}}
                                    <p><strong>ค่าจัดส่ง (บาท) :</strong> {{ $data->delivery_cost }}</p>
                                {{-- @endif
                            @endforeach --}}
                        </div>
                        <div class="mb-3">
                            <p><strong>ยอดเงินสุทธิ (บาท) :</strong> {{$sum_cost}} </p>
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
                <a href="/info" class="btn my-3"
                    style="background-color:#A4F02A">ดำเนินการ</a>
            </div>
            
        </div>
    </div>
@endsection
