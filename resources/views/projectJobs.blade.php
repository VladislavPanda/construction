<style>
    table{
            border-collapse: collapse;
            table-layout:fixed;
            width: 100%;
        }

    table th, table td { 
            border: 1px solid;
            border-color: black; 
        }   

    th, td{
            height: 100px;
            text-align: center;
        }
</style>

<table>
    <thead>
        <tr>
            <th style="font-size: 16px;">Работа</th>
            <th style="font-size: 16px;">Количество часов</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($jobs as $item)
            <tr>
                <td style="font-size: 16px;">{{ $item['Работа'] }}</td>
                <td style="font-size: 16px;">{{ $item['Количество часов'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>