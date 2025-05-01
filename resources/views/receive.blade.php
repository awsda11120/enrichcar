@extends('layout')
@section('doc', 'receive')
@section('content')

    <div class="input-group mb-3 d-flex align-items-center">
        <div class="me-3">
            ค้นหาเลขทะเบียน
        </div>
        <form class="d-flex col-md-2" role="search">
            <input id="searchInput" class="form-control me-2" type="search" aria-label="Search"
                placeholder="ค้นหาเลขทะเบียน...">
        </form>
    </div>
    <table class="table table-grid">
        <thead class="text-center">
            <tr>
                <th>ทะเบียนรถ</th>
                <th>ชื่อ</th>
                <th>เบอร์โทร</th>
                <th>สถานะ</th>
                <th>หลักฐาน</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($list as $item)
                <tr>
                    <td>{{ $item->CarNumber }}</td>
                    <td>{{ $item->CustomerName }}</td>
                    <td>{{ $item->PhoneNumber }}</td>
                    <td
                        style="color: {{ $item->SelectOption == 'จัดส่งตามที่อยู่' ? '#FF0000' : ($item->SelectOption == 'มารับเอง' ? '#424ddf' : '') }}">
                        {{ $item->SelectOption }}
                    </td>
                    <form action="{{ route('history.update', $item->history_id) }}" method="POST"
                        enctype="multipart/form-data" id="form-{{ $item->history_id }}">
                        @csrf
                        <td>
                            @if ($item->SelectOption == 'จัดส่งตามที่อยู่')
                                <input type="text" class="form-control" name="ProofOfReceive"
                                    value="{{ old('ProofOfReceive', $item->ProofOfReceive) }}"
                                    placeholder="กรุณากรอกเลขพัสดุ" id="proof-{{ $item->history_id }}"
                                    {{ !empty($item->ProofOfReceive) ? 'disabled' : '' }}>
                            @elseif ($item->SelectOption == 'มารับเอง')
                                @if (!empty($item->ProofOfReceive))
                                    <!-- ช่องเลือกไฟล์ -->
                                    <input type="file" class="form-control mt-2" name="ProofOfReceive"
                                        id="proof-{{ $item->history_id }}"
                                        {{ !empty($item->ProofOfReceive) ? 'disabled' : '' }}>
                                    <!-- ปุ่มดาวน์โหลดไฟล์จะอยู่หลังช่องเลือกไฟล์ -->
                                    <a href="{{ asset('public/proofs/' . $item->ProofOfReceive) }}"
                                        class="btn btn-info btn-sm mt-2" style="background-color: #A2D7FF; border: none;"
                                        download>
                                        ดาวน์โหลด
                                    </a>
                                    <span class="mt-2">{{ $item->ProofOfReceive }}</span>
                                @else
                                    <!-- ช่องเลือกไฟล์ -->
                                    <input type="file" class="form-control" name="ProofOfReceive"
                                        id="proof-{{ $item->history_id }}"
                                        {{ !empty($item->ProofOfReceive) ? 'disabled' : '' }}>
                                @endif
                            @endif
                        </td>
                        <td>
                            <button type="submit" class="btn btn-light btn-sm save-btn"
                                id="submit-btn-{{ $item->history_id }}"
                                style="background-color: {{ !empty($item->ProofOfReceive) ? '#ccc' : '#A4F02A' }}"
                                {{ !empty($item->ProofOfReceive) ? 'disabled' : '' }}>
                                บันทึก
                            </button>
                            <button type="button" class="btn btn-warning btn-sm edit-btn"
                                data-id="{{ $item->history_id }}"
                                style="background-color: {{ !empty($item->ProofOfReceive) ? '#F9D74E' : '#ccc' }}; border: none;"
                                {{ empty($item->ProofOfReceive) ? 'disabled' : '' }}>
                                แก้ไข
                            </button>
                        </td>
                    </form>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // เมื่อกดปุ่ม "บันทึก" จะเปลี่ยนเป็นสีเทา และปิดใช้งาน
            $('form').submit(function(event) {
                var form = $(this);
                var proofInput = form.find('input[name="ProofOfReceive"]');
                var submitButton = form.find('.save-btn');
                var proofValue = proofInput.val().trim();

                // หากไม่มีการกรอกข้อมูลให้แสดงข้อความเตือน
                if (!proofValue) {
                    alert('กรุณากรอกข้อมูลหลักฐานก่อน');
                    event.preventDefault();
                    return false;
                }

                // ปิดใช้งานปุ่มบันทึก และเปลี่ยนเป็นสีเทา
                submitButton.prop('disabled', true).css('background-color', '#ccc');

                // ปิดใช้งานช่องกรอกข้อมูล
                proofInput.prop('disabled', true).css('background-color', '#f0f0f0');

                // เปลี่ยนปุ่ม "แก้ไข" ให้เป็นทึบ
                var editButton = form.find('.edit-btn');
                editButton.prop('disabled', true).css('background-color', '#ccc');
            });

            // เมื่อกดปุ่ม "แก้ไข" ให้เปิดใช้งานปุ่ม "บันทึก" และช่องกรอกข้อมูลอีกครั้ง
            $('.edit-btn').click(function() {
                var row = $(this).closest('tr'); // ค้นหาแถวที่ปุ่มถูกกด
                var submitButton = row.find('.save-btn'); // ค้นหาปุ่มบันทึกในแถวเดียวกัน
                var proofInput = row.find(
                'input[name="ProofOfReceive"]'); // ค้นหาช่องกรอกข้อมูลในแถวเดียวกัน
                var editButton = row.find('.edit-btn'); // ค้นหาปุ่มแก้ไขในแถวเดียวกัน

                // เปิดใช้งานปุ่มบันทึก และเปลี่ยนเป็นสีเขียว
                submitButton.prop('disabled', false).css('background-color', '#A4F02A');

                // เปิดใช้งานช่องกรอกข้อมูล
                proofInput.prop('disabled', false).css('background-color', '#ffffff');

                // เปลี่ยนปุ่ม "แก้ไข" ให้กลับมาเป็นสีเดิม
                editButton.prop('disabled', false).css('background-color', '#F9D74E');
            });
        });
    </script>

@endsection
