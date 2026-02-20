<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Résultat étudiant</title>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f3f3f3; }
    </style>
</head>
<body>

<h2>📄 Résultat de l’examen</h2>

<p>
    <strong>Examen :</strong> {{ $exam->title }}<br>
    <strong>Étudiant :</strong> {{ $student->name }} ({{ $student->email }})<br>
    <strong>Score automatique :</strong> {{ $autoOn50 }} / 50<br>
    <strong>Note finale :</strong> {{ $finalScore }} / 100
</p>

<table>
    <thead>
        <tr>
            <th>Question</th>
            <th>Type</th>
            <th>Réponse</th>
            <th>Note</th>
            <th>Commentaire</th>
        </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        <tr>
            <td>{{ $row['question'] }}</td>
            <td>{{ $row['type'] }}</td>
            <td>{{ $row['answer'] }}</td>
            <td>{{ $row['manual_score'] ?? '—' }}</td>
            <td>{{ $row['manual_comment'] ?? '—' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>


</body>
</html>
