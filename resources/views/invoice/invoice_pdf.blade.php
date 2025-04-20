<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice</title>
    <style>
        @page {
            size: A4 potrait;
            margin: 3% 5%;
            width: 100%;
        }

        body {
            font-size: 10px;
            width: auto; /* A4 size */
            height: auto; /* A4 size */
            margin: auto;
        }

        .page-break {
            page-break-after: always;
        }

        table, tr, th, td {
            border-collapse: collapse;
            width: 100%;
            height: auto;
            border: 1px solid black;
            padding: 0px 5px;
            text-align: left;
        }

        table.top-row, table.top-row tr, table.top-row th, table.top-row td {
            border: none;
            border-collapse: collapse;
            vertical-align: middle;
        }

        table.project-list, table.project-list tr, table.project-list th, table.project-list td {
            border-collapse: collapse;
            vertical-align: middle;
            padding: 5px;
        }
    </style>
</head>
<body>

    <table class="top-row">
        <tr>
            <td style="width: 50%; text-align: left; vertical-align: middle;" rowspan="2">
                <h1>Invoice</h1>
            </td>
            <td style="width: 20%; vertical-align: bottom;">
                Invoice Date
            </td>
            <td style="width: 30%; vertical-align: bottom; border-bottom: 1px solid black;">
                {{ date('d-m-Y', strtotime($invoice->created_at)) }}
            </td>
        </tr>
        <tr>
            <td style="width: 20%; vertical-align: bottom;">
                Invoice Number
            </td>
            <td style="width: 30%; vertical-align: bottom; border-bottom: 1px solid black;">
                {{ $invoice->invoice_number }}
            </td>
        </tr>
    </table>

    <br>

    <table class="top-row">
        <tr>
            <td>
                Bill To:
            </td>
        </tr>
        <tr>
            <td style="padding-top: 5px;">
                <strong>{{ $invoice->client?->name ?? '' }}</strong>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 5px;">
                {!! nl2br($invoice->client?->address ?? '') !!}
            </td>
        </tr>
    </table>

    <br>
    <br>

    <table class="project-list">
        <tr>
            <th style="width: 20%;">Project</th>
            <th style="width: 40%;">Description</th>
            <th style="width: 17%;">Rate / Hour</th>
            <th style="width: 8%;">Hours</th>
            <th style="width: 15%;">Total</th>
        </tr>

        @php
            $total = 0;
        @endphp

        @foreach($invoice_projects as $item)
            @php
                $total += $item->rate * $item->duration;
            @endphp

            <tr>
                <td style="width: 20%;">
                    {{ $item->project?->name ?? '' }}
                </td>
                <td style="width: 40%;">
                    {!! nl2br($item->project?->description ?? '') !!}
                </td>
                <td style="width: 15%;">
                    {{ number_format($item->rate, 2) }}
                </td>
                <td style="width: 8%;">
                    {{ $item->duration }}
                </td>

                <td style="width: 17%;">
                    {{ number_format($item->rate * $item->duration ?? 0, 2) }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4" style="text-align: right;">
                <strong>Total</strong>
            </td>
            <td style="width: 17%;">
                {{ number_format($total, 2) }}
            </td>
        </tr>
    </table>

</body>
</html>
