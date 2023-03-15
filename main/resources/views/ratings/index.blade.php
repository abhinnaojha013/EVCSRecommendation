@extends("layouts.app")
@section("title", "Ratings")

@section("content")
    <section>
        <div>
            <h2>
                Ratings
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
            <div>
                <a href="{{route('rating.provide')}}">
                    <button>Rate a charging station</button>
                </a>
            </div>
            <div>
                <a href="{{route('recommendations.index')}}">
                    <button>Get recommendation</button>
                </a>
            </div>
        </div>
        <div>
            <table>
                <tr>
                    <th>Charging Station</th>
                    <th>Location</th>
                    <th>Rating</th>
                    <th><!-- dummy th for edit--></th>
                </tr>
                @foreach($data['ratings'] as $ratings)
                    <tr>
                        <td>{{$ratings->cs_name}}</td>
                        <td>
                            {{$ratings->metropolitan}}-{{$ratings->ward_number}}, {{$ratings->district}}, {{$ratings->province}}
                        </td>
                        <td>{{$ratings->rating}}</td>
                        <td>
                            <a href="">
                                <button>Edit Rating</button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </section>
@endsection
