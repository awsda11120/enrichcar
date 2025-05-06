@extends('layout')
@section('doc', 'Infomationn')
@section('content')
    <div class="container">
        <div class="input-group mb-3">
            <form class="d-flex" method="POST" action="/addcost" id="costForm">
                @csrf
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <label for="">ประเภทรถ</label>
                        <select name="cost[type]" class="form-control" id="carTypeSelect">
                            <option value="" selected>เลือกประเภทรถ...</option>
                            @foreach ($types as $item )
                                <option value="{{$item->id}}"> {{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg col-md-3">
                        <label for="">ค่าต่อพ.ร.บ. (บาท)</label>
                        <input class="form-control text-center" name="cost[renew]" type="number" >
                    </div>
                    <div class="col-lg col-md-3">
                        <label for="">อัตราค่าบริการ (บาท)</label>
                        <input class="form-control text-center" name="cost[service]" type="number" >
                    </div>
                    <div class="col-lg col-md">
                        <label for="">ค่าจัดส่ง (บาท)</label>
                        <input class="form-control text-center" name="cost[deliver]" type="number" >
                    </div>
                    <div class="col-lg col-md">
                        <label for="">&nbsp;</label>
                        <div>
                            <input type="submit" value="บันทึก" class="btn" style="background-color:#A4F02A">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <hr>

    <table class="table table-grid">
        <thead class="text-center">
            <tr>
                <th scope="col">ลำดับ</th>
                <th scope="col">ประเภทรถ</th>
                <th scope="col">ค่าต่อ พ.ร.บ.</th>
                <th scope="col">ค่าบริการ</th>
                <th scope="col">ค่าจัดส่ง</th>
                <th scope="col" width="60">ลบ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($costs as $index => $item)
                <tr class="text-center">
                    <td>{{ $index+1 }}</td>
                    <td class="text-start">{{ $item->name }}</td>
                    <td>{{ $item->renew_cost }}</td>
                    <td>{{ $item->fee }}</td>
                    <td>{{ $item->delivery_cost }}</td>
                    <td>
                        <a href="{{ route('deleteCost', $item->id) }}" class="btn btn-outline-danger"
                            onclick="return confirm('คุณต้องการลบข้อมูลลำดับที่ {{ $index+1 }} {{ $item->name }} หรือไม่ ?')">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('costForm');
            const typeSelect = document.getElementById('carTypeSelect');
    
            form.addEventListener('submit', function (e) {
                const renew = form.querySelector('input[name="cost[renew]"]').value.trim();
                const service = form.querySelector('input[name="cost[service]"]').value.trim();
                const deliver = form.querySelector('input[name="cost[deliver]"]').value.trim();
    
                if (typeSelect.value === '') {
                    e.preventDefault();
                    alert('กรุณาเลือกประเภทรถ');
                    return;
                }
    
                if (renew === '' || service === '' || deliver === '') {
                    e.preventDefault();
                    alert('กรุณากรอกข้อมูลให้ครบถ้วน');
                }
            });
        });
    </script>    
@endsection
