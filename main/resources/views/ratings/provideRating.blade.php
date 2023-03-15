@extends("layouts.app")
@section("title", "Ratings")

@section("content")
    <section>
        <h2>
            Rate a charging station
        </h2>
        <p>
            Please select the location details and the charging station that you wish to provide rating.
        </p>
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
        <form method="post" action="{{route('rating.add')}}">
            @csrf
            <table>
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
                        <label for="ward">Ward:</label>
                    </td>
                    <td>
                        <select id="ward_number" name="ward_number">
                            <option value="0">-Select Ward-</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="charging_station">Charging Station:</label>
                    </td>
                    <td>
                        <select id="charging_station" name="charging_station">
                            <option value="0">-Select Charging Station-</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="rating">Rating:</label>
                    </td>
                    <td>
                        <div class="rating">
                            <span id="s1" class="selected dim"><i class="fa fa-star star"></i></span>
                            <span id="s2" class="unselected dim"><i class="fa fa-star star"></i></span>
                            <span id="s3" class="unselected dim"><i class="fa fa-star star"></i></span>
                            <span id="s4" class="unselected dim"><i class="fa fa-star star"></i></span>
                            <span id="s5" class="unselected dim"><i class="fa fa-star star"></i></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        {{--                    dummy--}}
                        <input type="hidden" value="1" name="rating" id="rating">
                    </td>
                    <td>
                        <input type="submit" value="Submit rating" id="submit">
                        <p id="error_submit" class="alert alert-danger" role="alert" style="display: none"></p>
                    </td>
                </tr>
            </table>
        </form>
        <div>
            <div>
                <a href="{{route('recommendations.index')}}">
                    <button>Get recommendation</button>
                </a>
            </div>
            <div>
                <a href="{{route('rating.provide')}}">
                    <button>Rate a charging station</button>
                </a>
            </div>
        </div>
    </section>
    <style>
        .rating {
            width: fit-content;
            padding: 5px 5px 3px 5px;
            background-color: lightgray;
            cursor: pointer;
        }
        .dim {
            color: yellow;
        }
        .selected {
            color: orange;
        }
        .light {
            color: darkorange;
        }
        .star {
            font-size: x-large;
        }

    </style>
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
                    let max_wards = wards[0].wards;
                    let option_all = '<option value="0">-Select Ward-</option>';
                    for (let i = 1; i <= max_wards; i++) {
                        option_all = option_all + '<option value="' + i + '">' + i + '</option>';
                    }
                    $('#ward_number').html(option_all);
                }
            });
        });

        $('#ward_number').change(function () {
            $.ajax({
                type: 'POST',
                url: '/chargingStation/getChargingStations',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    metropolitan:  $('#metropolitan').val(),
                    ward_number:  $('#ward_number').val()
                },
                success: function (chargingStations) {
                    let option_all = '<option value="0">-Select Charging Station-</option>';
                    for (let i = 0; i < chargingStations.length; i++) {
                        console.log(i);
                        option_all = option_all + '<option value="' + chargingStations[i].cs_id + '">' + chargingStations[i].cs_name + '</option>';
                    }
                    $('#charging_station').html(option_all);
                }
            });
        });

        for (let i = 1; i <= 5; i++) {
            let id = "s" + i;
            document.getElementById(id).addEventListener('mouseover', function () {
                for (let j = 1; j <= i; j++) {
                    let tid = "s" + j;
                    document.getElementById(tid).classList.add('light');
                }
            });
            document.getElementById(id).addEventListener('mouseout', function () {
                for (let j = 1; j <= i; j++) {
                    let tid = "s" + j;
                    document.getElementById(tid).classList.remove('light');
                }
            });
        }
        for (let i = 1; i <= 5; i++) {
            let id = "s" + i;
            document.getElementById(id).addEventListener('click', function () {
                for (let j = 1; j <= i; j++) {
                    for (let sel = 1; sel <= 5; sel++) {
                        let tid = "s" + sel;
                        document.getElementById(tid).classList.replace('selected', 'unselected');
                    }
                    for (let sel = 1; sel <= j; sel++) {
                        let tid1 = 's' + sel;
                        document.getElementById(tid1).classList.replace('unselected', 'selected');
                    }
                }
                document.getElementById('rating').value = i;
            });
        }

        $('#submit').click(function () {

            if($('#charging_station').val() == 0) {
                document.getElementById('error_submit').style.display = 'block';
                $('#error_submit').html("Please select a charging station.");
                return false;
            }
        });
    </script>
@endsection
