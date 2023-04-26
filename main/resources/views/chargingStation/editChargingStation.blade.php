@extends("layouts.app")
@section("title", "Edit Charging Station")

@section("content")
    <section>
        <div>
            <h2 style="font-weight: bold">
                Edit Charging Station
            </h2>
            <hr>
        </div>
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
            <form method="post" action="{{route('chargingStation.update')}}">
                @csrf
                <input type="hidden" id="charging-station-id" name="charging_station_id" value="{{ $data['charging_station'][0]->cs_id}}" required/>
                <table class="table">
                    <tr>
                        <td>
                            <label for="charging-station-name">Name:</label>
                        </td>
                        <td>
                            <input type="text" id="charging-station-name" name="charging_station_name" value="{{ $data['charging_station'][0]->cs_name}}" required/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="province">Province:</label>
                        </td>
                        <td>
                            <select id="province" name="province" required>
                                <option value="">-Select Province-</option>
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
                            <select id="district" name="district" required>
                                <option value="">-Select District-</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="metropolitan">Metropolitan:</label>
                        </td>
                        <td>
                            <select id="metropolitan" name="metropolitan" required>
                                <option value="">-Select Metropolitan-</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="ward_number">Ward:</label>
                        </td>
                        <td>
                            <input type="number" id="ward_number" name="ward_number" step="1" min="1" max="1" value="{{ $data['charging_station'][0]->ward_number}}" required/>
                            <span id="ward_max"></span>
                            <input type="hidden" id="max_wards" name="max_wards" value="0">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="ac_ports_fast">Fast Charging AC Ports:</label>
                        </td>
                        <td>
                            <input type="number" id="ac_ports_fast" name="ac_ports_fast" step="1" min="0" value="{{ $data['charging_station'][0]->ac_fast}}" required/>
                            <span id="ac_ports_fast"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="dc_ports_fast">Fast Charging DC Ports:</label>
                        </td>
                        <td>
                            <input type="number" id="dc_ports_fast" name="dc_ports_fast" step="1" min="0" value="{{ $data['charging_station'][0]->dc_fast}}" required/>
                            <span id="dc_ports_fast"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="ac_ports_regular">Regular AC Ports:</label>
                        </td>
                        <td>
                            <input type="number" id="ac_ports_regular" name="ac_ports_regular" step="1" min="0" value="{{ $data['charging_station'][0]->ac_reg}}" required/>
                            <span id="ac_ports_regular"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="dc_ports_regular">Regular DC Ports:</label>
                        </td>
                        <td>
                            <input type="number" id="dc_ports_regular" name="dc_ports_regular" step="1" min="0" value="{{ $data['charging_station'][0]->dc_reg}}" required/>
                            <span id="dc_ports_regular"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="nearest_restaurant">Nearest Restaurant:</label>
                        </td>
                        <td>
                            <input type="number" id="nearest_restaurant" name="nearest_restaurant" min="0" value="{{ $data['charging_station'][0]->restaurant}}" required/>
                            <span id="nearest_restaurant"> metres</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="nearest_shopping_mall">Nearest Shopping Mall:</label>
                        </td>
                        <td>
                            <input type="number" id="nearest_shopping_mall" name="nearest_shopping_mall" min="0" value="{{ $data['charging_station'][0]->mall}}" required/>
                            <span id="nearest_shopping_mall"> metres</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="nearest_cinema_hall">Nearest Cinema Hall:</label>
                        </td>
                        <td>
                            <input type="number" id="nearest_cinema_hall" name="nearest_cinema_hall" min="0" value="{{ $data['charging_station'][0]->cinema}}" required/>
                            <span id="nearest_cinema_hall"> metres</span>
                        </td>
                    </tr>

                    <tr>
                        <td><!-- dummy td--></td>
                        <td>
                            <input type="submit" value="Update Charging Station" class="btn btn-success">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div>
            <a href="{{route('chargingStation.index')}}">
                <button class="btn btn-danger">Return to main</button>
            </a>
        </div>
    </section>

    <script>
        function select_district() {
             $.ajax({
                type: 'POST',
                url: '/district/getDistricts',
                async: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    province:  $('#province').val()
                },
                success: function (districts) {
                    let option_all = '<option value="">-Select District-</option>';
                    for (let i = 0; i < districts.length; i++) {
                        option_all = option_all + '<option value="' + districts[i].id + '">' + districts[i].district_name + '</option>';
                    }
                    $('#district').html(option_all);
                }
            });
        }

        function select_metropolitan() {
            $.ajax({
                type: 'POST',
                url: '/metropolitan/getMetropolitans',
                async: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    district:  $('#district').val()
                },
                success: function (metropolitans) {
                    let option_all = '<option value="">-Select Metropolitan-</option>';
                    for (let i = 0; i < metropolitans.length; i++) {
                        option_all = option_all + '<option value="' + metropolitans[i].id + '">' + metropolitans[i].metropolitan_name + '</option>';
                    }
                    $('#metropolitan').html(option_all);
                }
            });
        }

        function select_wards() {
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
                    document.getElementById('ward_number').max = ward_number;
                    document.getElementById('ward_max').innerText = 'Max: ' + ward_number;

                    document.getElementById('max_wards').value = ward_number;
                }
            });
        }
    </script>

    <script>
        // script for dynamic location selection during startup
        let province_option = document.getElementById('province').options;
        for (let i = 0; i < province_option.length; i++) {
            if (province_option[i].value == {{$data['charging_station'][0]->province}}) {
                province_option[i].selected = true;
            }
        }

        select_district();
        let district_option = document.getElementById('district').options;
        for (let i = 0; i < district_option.length; i++) {
            if (district_option[i].value == {{$data['charging_station'][0]->district}}) {
                district_option[i].selected = true;
            }
        }

        select_metropolitan();
        let metropolitan_option = document.getElementById('metropolitan').options;
        for (let i = 0; i < metropolitan_option.length; i++) {
            if (metropolitan_option[i].value == {{$data['charging_station'][0]->metropolitan}}) {
                metropolitan_option[i].selected = true;
            }
        }
        select_wards();

        // get districts from province selected
        $('#province').change(function () {
            select_district();
        });

        // get metropolitans from districts selected
        $('#district').change(function () {
            select_metropolitan();
        });

        // get max wards from metropolitan selected
        $('#metropolitan').change(function () {
            select_wards();
            document.getElementById('ward_number').value = "";
        });
    </script>
@endsection
