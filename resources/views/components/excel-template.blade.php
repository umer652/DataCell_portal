<style>
.template-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.template-table th,
.template-table td {
    border: 1px solid #ddd;
    padding: 6px;
    font-size: 13px;
    text-align: left;
}

.template-box {
    margin-top: 20px;
    padding: 15px;
    background: #f9f9f9;
    border-radius: 8px;
}
</style>

<div class="template-box">

    <h4>Excel Format (Do NOT change headers)</h4>

    <table class="template-table">

        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach($sampleRows as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>

    </table>

</div>
