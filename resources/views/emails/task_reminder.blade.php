<!DOCTYPE html>
<html>
<head>
    <title>Task Reminder</title>
</head>
<body>
    <h1>Hello {{ $user->name }},</h1>

    <p>You have the following tasks due tomorrow:</p>

    <ul>
        @foreach($tasks as $task)
            <li><strong>{{ $task->title }}</strong> - Due: {{ $task->due_date->format('Y-m-d') }}</li>
        @endforeach
    </ul>

    <p>Please complete them on time.</p>

    <p>Best regards,<br>Task Manager</p>
</body>
</html>