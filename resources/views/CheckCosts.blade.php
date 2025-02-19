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
                                <p><strong>ค่า พ.ร.บ. (บาท) :</strong> {{ $sum_renew }}</p>
                            </div>
                        @endif

                        @if ($sum_tax > 0)
                            <div class="mb-3">
                                <p><strong>ค่าภาษี (บาท) :</strong> {{ $sum_tax }}</p>
                            </div>
                        @endif

                        @if ($sum_fee > 0)
                            <div class="mb-3">
                                <p><strong>ค่าบริการ (บาท) :</strong> {{ $sum_fee }}</p>
                            </div>
                        @endif

                        @if ($sum_delivery > 0)
                            <div class="mb-3">
                                <p><strong>ค่าจัดส่ง (บาท) :</strong> {{ $sum_delivery }}</p>
                            </div>
                        @endif

                        <div class="mb-3">
                            <p><strong>ยอดเงินสุทธิ (บาท) :</strong> {{ $sum_cost }}</p>
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
