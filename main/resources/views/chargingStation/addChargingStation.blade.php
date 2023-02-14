@extends("layouts.app")
@section("title", "Add Charging Station")

@section("content")
    <section>
        <h2>
            Add Charging Station
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
            <form method="post" action="{{route('chargingStation.store')}}">
                @csrf
                <table>
                    <tr>
                        <td>
                            <label for="charging-station-name">Name:</label>
                        </td>
                        <td>
                            <input type="text" id="charging-station-name" name="charging_station_name" required/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="province">Province:</label>
                        </td>
                        <td>
                            <select id="province" name="province">
                                <option value="0">-Select Province-</option>
                                @foreach($data['provinces'] as $province)
                                    <option value="{{$province->id}}">{{$province->province_name}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="district">District:</label>
                        </td>
                        <td>
                            <select id="district" name="district">
                                <option value="0">-Select District-</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="metropolitan">Metropolitan:</label>
                        </td>
                        <td>
                            <select id="metropolitan" name="metropolitan">
                                <option value="0">-Select Metropolitan-</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="ward_number">Ward:</label>
                        </td>
                        <td>
                            <input type="number" id="ward_number" name="ward_number" step="1" min="1" max="32" required/>
                            <span id="ward_max"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="ac_ports_fast">Fast Charging AC Ports:</label>
                        </td>
                        <td>
                            <input type="number" id="ac_ports_fast" name="ac_ports_fast" step="1" min="0" required/>
                            <span id="ac_ports_fast"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="dc_ports_fast">Fast Charging DC Ports:</label>
                        </td>
                        <td>
                            <input type="number" id="dc_ports_fast" name="dc_ports_fast" step="1" min="0" required/>
                            <span id="dc_ports_fast"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="ac_ports_regular">Regular AC Ports:</label>
                        </td>
                        <td>
                            <input type="number" id="ac_ports_regular" name="ac_ports_regular" step="1" min="0" required/>
                            <span id="ac_ports_regular"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="dc_ports_regular">Regular DC Ports:</label>
                        </td>
                        <td>
                            <input type="number" id="dc_ports_regular" name="dc_ports_regular" step="1" min="0" required/>
                            <span id="dc_ports_regular"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="nearest_restaurant">Nearest Restaurant:</label>
                        </td>
                        <td>
                            <input type="number" id="nearest_restaurant" name="nearest_restaurant" min="0" required/>
                            <span id="nearest_restaurant"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="nearest_shopping_mall">Nearest Shopping Mall:</label>
                        </td>
                        <td>
                            <input type="number" id="nearest_shopping_mall" name="nearest_shopping_mall" min="0" required/>
                            <span id="nearest_shopping_mall"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="nearest_cinema_hall">Nearest Cinema Hall:</label>
                        </td>
                        <td>
                            <input type="number" id="nearest_cinema_hall" name="nearest_cinema_hall" min="0" required/>
                            <span id="nearest_cinema_hall"></span>
                        </td>
                    </tr>

                    <tr>
                        <td><!-- dummy td--></td>
                        <td>
                            <input type="submit" value="Add Charging Station">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div>
            <a href="{{route('chargingStation.index')}}">
                <button>Return to index</button>
            </a>
        </div>
    </section>

    <script>
        // get districts from province selected
        $('#province').change(function () {
            $.ajax({
               type: 'POST',
                url: '/district/getDistricts',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                   province:  $('#province').val()
                },
                success: function (districts) {
                   let option_all = '<option value="0">-Select District-</option>';
                   for (let i = 0; i < districts.length; i++) {
                        option_all = option_all + '<option value="' + districts[i].id + '">' + districts[i].district_name + '</option>';
                   }
                   $('#district').html(option_all);
                }
            });
        });

        // get metropolitans from districts selected
        $('#district').change(function () {
            $.ajax({
                type: 'POST',
                url: '/metropolitan/getMetropolitans',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    district:  $('#district').val()
                },
                success: function (metropolitans) {
                    let option_all = '<option value="0">-Select Metropolitan-</option>';
                    for (let i = 0; i < metropolitans.length; i++) {
                        option_all = option_all + '<option value="' + metropolitans[i].id + '">' + metropolitans[i].metropolitan_name + '</option>';
                    }
                    $('#metropolitan').html(option_all);
                }
            });
        });

        // get max wards from metropolitan selected
        $('#metropolitan').change(function () {
            $.ajax({
                type: 'POST',
                url: '/metropolitan/getWards',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    metropolitan:  $('#metropolitan').val()
                },
                success: function (wards) {
                    let ward_number;
                    if (wards === undefined) {
                        ward_number = 0;
                    } else {
                        ward_number = wards[0].wards;
                    }
                    document.getElementById('ward_number').value = "";
                    document.getElementById('ward_number').max = ward_number;
                    document.getElementById('ward_max').innerText = 'Max: ' + ward_number;
                }
            });
        });
    </script>
@endsection
