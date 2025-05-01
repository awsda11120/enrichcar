<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('doc') | EINS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sidebars/">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    




    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&family=Roboto:wght@400;700&display=swap');

        body {
            font-family: 'Prompt', 'Roboto', sans-serif;
            size: 16px;
        }

        table>thead>tr>th {
            font-weight: 500 !important;
        }

        .sidebar {
            margin-top: 10px;
            margin-left: 10px;
            padding: 25px 10px;
            width: 100%;
            background-color: #FEE8EC;
            /* position: fixed; */
            height: 100%;
            overflow: auto;
            -webkit-box-shadow: 10px 10px 7px -8px rgba(0, 0, 0, 0.53);
            -moz-box-shadow: 10px 10px 7px -8px rgba(0, 0, 0, 0.53);
            box-shadow: 10px 10px 7px -8px rgba(0, 0, 0, 0.53);
            border-radius: 15px;
        }

        .sidebar a {
            display: block;
            color: black;
            padding: 8px 15px;
            text-decoration: none;
            background: #FFF;
            margin: 10px 0px;
            border-radius: 25px;
        }

        .sidebar a.active {
            background-color: #F7CBC7;
            color: #000;
        }

        .sidebar a:hover:not(.active) {
            background-color: #F7CBC7;
            color: white;
        }

        div.content {
            margin-left: 200px;
            padding: 1px 16px;
            height: 1000px;
        }

        .relative {
            position: relative;
        }

        .sidebar a.relative span {
            display: inline-block;
            color: #FFF;
            background: #B33134;
            border-radius: 50%;
            text-align: center;
            font-size: 13px;
            width: 25px;
            height: 25px;
            padding: 3px;
            position: absolute;
            top: 7px;
            right: 5px;
        }

        .test-bdr {
            border: #EC0000 1px solid;
        }

        #addinfo label {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
            font-weight: 400;
        }

        #editInfo label {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
            font-weight: 400;
        }


        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            background: transparent;
            background-image: url("data:image/svg+xml;utf8,<svg fill='black' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/><path d='M0 0h24v24H0z' fill='none'/></svg>");
            background-repeat: no-repeat;
            background-position-x: 100%;
            background-position-y: 5px;
            border: 1px solid #dfdfdf;
            border-radius: 2px;
            margin-right: 2rem;
            padding: 1rem;
            padding-right: 2rem;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }




        /* end add info form */
        .errMsg ul li {
            list-style: none;
            color: #EC0000;
        }

        .bg_danger td {
            background: #f87979 !important;
            color: ##000;
        }

        .bg_warning td {
            background: #FFFF99 !important;
            color: #000;
        }

        .bg_expire td {
            background: #D3D3D3 !important;
            color: #000;
        }

        .dot {
            display: inline-block;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            margin-left: 5px;
        }




        table.table-grid,
        table.table-grid thead tr th,
        table.table-grid tbody tr td {
            border: #000 1px solid !important;
        }

        table.table-grid thead tr th {
            background: #F7CBC7 !important;
        }

        /* //table.table-grid thead tr th{    height: 55px;  } */



        @media screen and (max-width: 700px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .sidebar a {
                float: left;
            }

            div.content {
                margin-left: 0;
            }
        }

        @media screen and (max-width: 400px) {
            .sidebar a {
                text-align: center;
                float: none;
            }
        }




    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".datepicker").forEach(input => {
                if (!input._flatpickr) { // ตรวจสอบว่า Flatpickr ถูกใช้กับอินพุตนี้ไปแล้วหรือยัง
                    flatpickr(input, {
                        dateFormat: "Y-m-d",
                        maxDate: "today"
                    });
                }
            });
        });
        
    </script>

</head>

<body style="background-color: #FDF5F4;">
    <nav class="navbar navbar-expand-lg" style="background-color: #F7CBC7;">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">EINS system</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="row">
        <div class="col-2 p-3 ">
            <div class="sidebar">
                <a href="/info" class="{{ request()->is('info') ? 'active' : '' }}"><i class="bi bi-car-front"></i>
                    ข้อมูล</a>
                <a href="/ShowHis" class="{{ request()->is('ShowHis') ? 'active' : '' }}"><i
                        class="bi bi-clock-history"></i> ประวัติทำรายการ</a>
                <a href="/receive" class="{{ request()->is('receive') ? 'active' : '' }}"><i class="bi bi-box-seam"></i>
                    การรับเอกสาร</a>
                <a href="/sum" class="{{ request()->is('sum') ? 'active' : '' }}"><i
                        class="bi bi-bar-chart-line-fill"></i> สรุปข้อมูล</a>
                <a href="/settings/general" class="{{ request()->is('settings/general') ? 'active' : '' }}"><i
                        class="bi bi-gear-wide-connected"></i> ตั้งค่าทั่วไป</a>
                <a href="/settings/cost" class="{{ request()->is('settings/cost') ? 'active' : '' }}"><i
                        class="bi bi-cash-coin"></i> กำหนดค่าบริการ</a>
            </div>
        </div>
        <div class="col-10 ">
            <div class="container py-2">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('script')
    <div class="b-example-divider"></div>
</body>

</html>
