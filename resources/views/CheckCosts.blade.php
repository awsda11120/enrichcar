@extends('layout')
@section('doc', 'Check costs')
@section('content')
    <!-- แสดงข้อความ error หากมี -->
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="container my-5">
        <h3 class="text-center mb-4">ตรวจสอบค่าดำเนินการ</h3>
        <style>
            .disabled-btn {
                background-color: #d3d3d3 !important;
                cursor: not-allowed;
                border: none;
            }

            .disabled-btn:disabled {
                opacity: 0.6;
            }

            .card strong {
                font-weight: 300 !important;
            }

            .card strong.final-amount {
                font-weight: 700 !important;
            }
        </style>

        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                @if ($data)
                    <div class="card p-4 shadow-sm mx-auto" style="max-width: 500px;">
                        {{-- <div class="mb-3 d-flex justify-content-between">
                            <strong class fw-light>ประเภท พ.ร.บ. :</strong> <span
                                class="text-end">{{ $data->InsuranceType }}</span>
                        </div> --}}
                        {{-- <div class="mb-3 d-flex justify-content-be tween">
                            <strong class fw-light>ประเภท พ.ร.บ.(เลข) :</strong> <span
                                class="text-end">{{ $data->TypeId }}</span>
                        </div> --}}
                        {{-- <div class="mb-3 d-flex justify-content-between">
                            <strong class fw-light>ประเภทภาษี :</strong> <span
                                class="text-end">{{ $data->InsuranceType }}</span>
                        </div> --}}
                        {{-- <div class="mb-3 d-flex justify-content-between">
                            <strong class fw-light>ประเภทภาษี(เลข) :</strong> <span
                                class="text-end">{{ $data->TypeId }}</span>
                        </div> --}}

                        @if ($sum_renew > 0)
                            <div class="mb-3 d-flex justify-content-between">
                                <strong class fw-light>ค่า พ.ร.บ. (บาท) :</strong> <span
                                    class="text-end">{{ number_format($sum_renew, 2) }}</span>
                            </div>
                        @endif

                        @if (($InsIncome > 0) || ($TaxIncome > 0))
                            <div class="mb-3 d-flex justify-content-between">
                                <strong class fw-light>ค่าบริการ (บาท) :</strong>
                                <span class="text-end">{{ number_format($InsIncome + $TaxIncome, 2) }}</span>
                            </div>
                        @endif


                        @if ($sum_delivery > 0)
                            <div class="mb-3 d-flex justify-content-between">
                                <strong class fw-light>ค่าจัดส่ง (บาท) :</strong> <span
                                    class="text-end">{{ number_format($sum_delivery, 2) }}</span>
                            </div>
                        @endif

                        @if ($sum_tax > 0)
                            <div class="mb-3 d-flex justify-content-between">
                                <strong class fw-light>ค่าภาษีก่อนลด (บาท) :</strong> <span
                                    class="text-end">{{ number_format($original_tax, 2) }}</span>
                            </div>

                            @if ($carYears >= 6)
                                <div class="mb-3 d-flex justify-content-between">
                                    <strong class fw-light>ส่วนลดภาษี {{ $discountPercent }}% (บาท) :</strong> <span
                                        class="text-end">{{ number_format($discountAmount, 2) }}</span>
                                </div>
                            @endif

                            <div class="mb-3 d-flex justify-content-between">
                                <strong class fw-light>ค่าภาษีหลังลด (บาท) :</strong> <span
                                    class="text-end">{{ number_format($sum_tax, 2) }}</span>
                            </div>
                        @endif

                        <div class="mb-3 d-flex justify-content-between">
                            <strong class="final-amount">ยอดเงินสุทธิ (บาท) :</strong>
                            <span class="text-end">{{ number_format($sum_cost, 2) }}</span>
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
                <a href="javascript:history.back()" class="btn my-3" style="background-color:#9fdffa"> กลับ</a>
            </div>
            <div class="col-auto">
                <form action="{{ route('storeHistory', ['id' => $data->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="car_id" value="{{ $data->id }}">
                    <input type="hidden" name="calculateTax" value="{{ $calculateTax ? 1 : 0 }}">
                    <input type="hidden" name="calculateRenew" value="{{ $calculateRenew ? 1 : 0 }}">
                    <input type="hidden" name="receive_option" value="{{ $data->SelectOption }}">
                    <input type="hidden" name="total_cost" value="{{ $sum_cost }}">
                    <input type="hidden" name="sum_renew" value="{{ $sum_renew }}">
                    <input type="hidden" name="sum_tax" value="{{ $sum_tax }}">
                    <input type="hidden" name="ins_income" value="{{ $InsIncome }}">
                    <input type="hidden" name="tax_income" value="{{ $TaxIncome }}">
                    <input type="hidden" name="sum_delivery" value="{{ $sum_delivery }}">
                    <button type="submit" class="btn my-3 {{ $sum_cost == 0 ? 'disabled-btn' : '' }}"
                        style="background-color:#A4F02A" {{ $sum_cost == 0 ? 'disabled' : '' }}>ดำเนินการ</button>
                </form>
            </div>
        </div>
    </div>
@endsection
