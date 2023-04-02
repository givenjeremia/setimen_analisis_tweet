<div class="container-fluid p-3 rounded" style="background-color:#858F75">
    <h1>Hasil</h1>
    <h3>Text : {{ $hasil[0]['high_acc']['text'] }}</h3>
    <h3> Sentimen : 
        @if ($hasil[0]['high_acc']['label'] == '0')
        <td>Negatif</td>
        @elseif($hasil[0]['high_acc']['label'] == '1')
        <td>Netral</td>
        @else
        <td>Positif</td>
        @endif
    </h3>
    <h3>Accuracy : {{ round($hasil[0]['high_acc']['acc'],2) * 100 }}% ({{ $hasil[0]['high_acc']['kernel'] }})</h3>
</div>