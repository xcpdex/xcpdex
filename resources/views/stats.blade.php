<table>
<tr>
  <td>Date</td>
  @foreach($tx_stats[0]['data'] as $data)
    <td>{{ \Carbon\Carbon::createFromTimestamp($data[0] / 1000)->toDateTimeString() }}</td>
  @endforeach
</tr>
@foreach($tx_stats as $tx_stat)
  <tr>
    <td>{{ $tx_stat['name'] }}</td>
    @foreach($tx_stat['data'] as $data)
      <td>{{ $data[1] }}</td>
    @endforeach
  </tr>
@endforeach
</table>