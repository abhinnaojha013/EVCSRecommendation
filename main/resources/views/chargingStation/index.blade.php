@extends("layouts.app")
@section("title", "Charging Stations")

@section("content")
    <section>
        <h2>
            Charging Stations
        </h2>
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
