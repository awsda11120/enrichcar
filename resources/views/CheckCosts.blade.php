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

                        @if ($sum_renew > 0)
                            <div class="mb-3">
                                <p><strong>ค่า พ.ร.บ. (บาท) :</strong> {{ number_format($sum_renew, 2) }}</p>
                            </div>
                        @endif

                        @if ($sum_tax > 0)
                            <div class="mb-3">
                                <p><strong>ค่าภาษีก่อนลด (บาท) :</strong> {{ number_format($original_tax, 2) }}</p>
                            </div>

                            @if ($carYears >= 6)
                                <div class="mb-3">
                                    <p><strong>ส่วนลดภาษี {{ $discountPercent }}% (บาท) :</strong> {{ number_format($discountAmount, 2) }}</p>
                                </div>
                            @endif

                            <div class="mb-3">
                                <p><strong>ค่าภาษีหลังลด (บาท) :</strong> {{ number_format($sum_tax, 2) }}</p>
                            </div>
                        @endif

                        @if ($sum_fee > 0)
                            <div class="mb-3">
                                <p><strong>ค่าบริการ (บาท) :</strong> {{ number_format($sum_fee, 2) }}</p>
                            </div>
                        @endif

                        @if ($sum_delivery > 0)
                            <div class="mb-3">
                                <p><strong>ค่าจัดส่ง (บาท) :</strong> {{ number_format($sum_delivery, 2) }}</p>
                            </div>
                        @endif

                        <div class="mb-3">
                            <p><strong>ยอดเงินสุทธิ (บาท) :</strong> {{ number_format($sum_cost, 2) }}</p>
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
                <form action="{{ route('storeHistory',['id' => $data->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="car_id" value="{{ $data->id }}">
                    <input type="hidden" name="calculateTax" value="{{ $calculateTax ? 1 : 0 }}">
                    <input type="hidden" name="calculateRenew" value="{{ $calculateRenew ? 1 : 0 }}">
                    <input type="hidden" name="receive_option" value="{{ $data->SelectOption }}">
                    <input type="hidden" name="total_cost" value="{{ $sum_cost }}">
                    <button type="submit" class="btn my-3" style="background-color:#A4F02A">ดำเนินการ</button>
                </form>
            </div>
        </div>
    </div>
@endsection
