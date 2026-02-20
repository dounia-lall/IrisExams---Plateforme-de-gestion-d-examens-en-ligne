<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Statistiques – {{ $exam->title }}</title>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

<h1>Statistiques par question<br>{{ $exam->title }}</h1>

<table>
    <thead>
        <tr>
            <th>Question</th>
            <th>Type</th>
            <th>Réponses</th>
            <th>Réussite</th>
            <th>Moyenne</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stats as $row)
            <tr>
                <td>{{ $row['question'] }}</td>
                <td>{{ $row['type'] }}</td>
                <td>{{ $row['answers'] }}</td>
                <td>{{ $row['success'] }}</td>
                <td>{{ $row['average'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
