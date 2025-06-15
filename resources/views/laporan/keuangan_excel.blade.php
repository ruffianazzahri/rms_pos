<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Omzet</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td>{{ $row->date }}</td>
            <td>{{ $row->omzet }}</td>
        </tr>
        @endforeach
    </tbody>
</table>