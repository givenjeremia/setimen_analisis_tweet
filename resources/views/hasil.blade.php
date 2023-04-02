<div class="row g-0">
    <div class="col-sm-6 col-md-8 p-2 rounded rounded-3" style="background-color:#858F75">
        <table id="hasil-data" class="table table-dark table-striped rounded rounded-3 overflow-hidden table-hover">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Username</th>
                <th scope="col">Ulasan</th>
                <th scope="col">Setimen</th>
                {{-- <th scope="col">Action</th> --}}
        
              </tr>
            </thead>
            <tbody>
                @foreach ($data_with_label as $key => $item)
                <tr>
                    <th scope="row">{{ $key+1 }}</th>
                    <td>{{ $item['high_acc']['tanggal'] }}</td>
                    <td>{{ $item['high_acc']['username'] }}</td>
        
                    <td>{{ $item['high_acc']['text'] }}</td>
                    @if ($item['high_acc']['label'] == '0')
                    <td>Negatif</td>
                    @elseif($item['high_acc']['label'] == '1')
                    <td>Netral</td>
                    @else
                    <td>Positif</td>
                    @endif
                    <td>{{ round($item['high_acc']['acc'],2) * 100 }}% ({{ $item['high_acc']['kernel'] }})</td>
                    {{-- <td>@mdo</td> --}}
                </tr>
                @endforeach
             
             
            </tbody>
            <tfoot>
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Tanggal</th>
                  <th scope="col">Username</th>
                  <th scope="col">Ulasan</th>
                  <th scope="col">Setimen</th>
                  <th scope="col">Acc</th>
                  {{-- <th scope="col">Action</th> --}}
                </tr>
            </tfoot>
        </table>        
    </div>
    {{-- <div class="col"></div> --}}


        <div class="col-6 col-md-4 p-2 " style="background-color:#858F75">
            <div class="rounded rounded-3"  >
                <canvas id="myChart"></canvas>
            </div>
            
        </div>

</div>

<div>
    
</div>


<script>
    new Chart(document.getElementById('myChart'), {
      type: 'pie',
      data: {
        labels: ["Positif", "Netral", "Negatif"],
        datasets: [{
          label: 'Sentimen',
          backgroundColor: ['#00FF00','#CBC7C3','#FF0000'],
          data: [
            {{$positif}},{{$netral}},{{$negatif}},
          ],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
</script>