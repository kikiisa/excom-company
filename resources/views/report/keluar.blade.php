<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>lAPORAN KELUAR</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>NO.</th>
                <th>KATEGORI</th>
                <th>NOMINAL</th>
                <th>KETERANGAN</th>
                <th>TANGGAL</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($credit as $hasil)
                <tr>
                    <th>{{ $no }}</th>
                    <td>{{ $hasil->name }}</td>
                    <td>{{ rupiah($hasil->nominal) }}</td>
                    <td>{{ $hasil->description }}</td>
                    <td>{{ $hasil->debit_date }}</td>
                </tr>
                @php
                    $no++;
                @endphp
            @endforeach
        </tbody>
    </table>
</body>
</html>
