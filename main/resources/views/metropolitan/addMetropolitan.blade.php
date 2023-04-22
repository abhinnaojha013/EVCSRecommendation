@extends("layouts.app")
@section("title", "Add Metropolitan")

@section("content")
    <section>
        <div>
            <h2>
                Add Metropolitan
            </h2>
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
            <form method="post" action="{{route('metropolitan.store')}}">
                @csrf
                <table>
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
                            <label for="metropolitan">Metropolitan name:</label>
                        </td>
                        <td>
                            <input type="text" id="metropolitan" name="metropolitan" required/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="max_wards">Number of wards:</label>
                        </td>
                        <td>
                            <input type="number" id="wards" name="wards" min="1" max="32" step="1" required/>
                        </td>
                    </tr>
                    <tr>
                        <td><!-- dummy td--></td>
                        <td>
                            <input type="submit" value="Add Metropolitan">
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
                    let option_all = '<option value="">-Select District-</option>';
                    for (let i = 0; i < districts.length; i++) {
                        option_all = option_all + '<option value="' + districts[i].id + '">' + districts[i].district_name + '</option>';
                    }
                    $('#district').html(option_all);
                }
            });
        });
    </script>
@endsection
