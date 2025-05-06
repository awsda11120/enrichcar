@extends('layout')
@section('doc', 'Infomationn')
@section('content')
    <div class="container">
        <div class="input-group mb-3">
            <form class="d-flex" method="POST" action="/addgeneral">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <label for="">ประเภทข้อมูล</label>
                        <select name="sett[type]" id="SelectCategory" class="form-control">
                            <option value="">...เลือก...</option>
                            @foreach ($types as $key => $name )
                                <option value="{{$key}}"> {{$name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label for="">ชื่อ</label>
                        <input class="form-control text-center" name="sett[name]" type="text">
                    </div>
                    <div class="col-lg-2">
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
    <table class="table  table-grid">
        <thead class="text-center">
            <tr>
                <th scope="col" width="50">ลำดับ</th>
                <th scope="col">ประเภท</th>
                <th scope="col">ชื่อ</th>
                <th scope="col" width="50">ลบ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $index => $item)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $types[$item->key] }}</td>
                    <td>{{ $item->name }}</td>
                    <td>
                        <a href="{{ route('deleteGeneral', $item->id) }}" class="btn btn-sm btn-outline-danger btn-del"
                           onclick="return confirm('คุณต้องการลบข้อมูลลำดับที่ {{ $index+1 }} {{ $item->name }} หรือไม่ ?')">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        // ตั้งค่าค่าที่เลือกไว้ (ถ้ามี)
        $('#SelectCategory').val('{{$category}}')

        // เปลี่ยนหมวดแล้ว redirect
        $('#SelectCategory').change(function(){
            var category = $(this).val();
            if (category !== '') {
                $(location).attr("href", "/settings/general/" + category);
            }
        });

        // ตรวจสอบก่อน submit
        $('form').on('submit', function(e){
            var selected = $('#SelectCategory').val();
            if (!selected) {
                alert('โปรดเลือกประเภทรถก่อนบันทึก');
                e.preventDefault(); // ยกเลิก submit
            }
        });
    });
</script>

<style>
    table {
        width: 50%!important;
    }
</style>
@endsection
