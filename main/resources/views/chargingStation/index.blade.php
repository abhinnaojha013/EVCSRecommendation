@extends("layouts.app")
@section("title", "Charging Stations")

@section("content")
    <section>
        <h2>
            Charging Stations
        </h2>
        <div>
            @if(\Illuminate\Support\Facades\Session::has('success'))
                <p class="alert alert-success" role="alert">
                    {{\Illuminate\Support\Facades\Session::get('success')}}
                </p>
            @endif
            @if(\Illuminate\Support\Facades\Session::has('error'))
                <p class="alert alert-danger" role="alert">
                    {{\Illuminate\Support\Facades\Session::get('error')}}
                </p>
            @endif
        </div>
        <div>
            <div>
                <a href="{{route('chargingStation.create')}}">
                    <button>Add a charging station</button>
                </a>
            </div>
            <div>
                <a href="{{route('metropolitan.create')}}">
                    <button>Add a metropolitan</button>
                </a>
            </div>
        </div>
        <div>
            <table>
                <tr>
                    <th>Charging Station Name</th>
                    <th>Location</th>
                </tr>
                @foreach($data['charging_stations'] as $charging_station)
                    <tr>
                        <td>{{$charging_station->cs_name}}</td>
                        <td>
                            {{$charging_station->metropolitan}}-{{$charging_station->ward_number}}, {{$charging_station->district}}, {{$charging_station->province}}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </section>
@endsection
