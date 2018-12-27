<style>
    td{
        padding: 10px;
        text-align: center;
    }
</style>

<table>

    @foreach($questions as $question )
        <th>{{ $question->text }}</th>
    @endforeach
    @foreach($interviewers as $interviewer )
        <tr>
            @foreach ((array)json_decode($interviewer->answers) as $answer)
                <td>{{ $answer }}</td>
            @endforeach
        </tr>
    @endforeach
</table>