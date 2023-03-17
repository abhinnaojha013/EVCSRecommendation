@extends("layouts.app")
@section("title", "Ratings")

@section("content")
    <section>
        <script>
            function evaluateRating(rid, rating) {
                for(let i = 1; i <= rating; i++) {
                    let rateID = rid + '_s' + i;
                    document.getElementById(rateID).classList.replace('unselected', 'selected');
                }
            }

            function updateStarUI(rid) {
                for (let i = 1; i <= 5; i++) {
                    let id = 'er' + rid + "_s" + i;
                    document.getElementById(id).addEventListener('mouseover', function () {
                        for (let j = 1; j <= i; j++) {
                            let tid = 'er' + rid + "_s" + j;
                            document.getElementById(tid).classList.add('light');
                        }
                    });
                    document.getElementById(id).addEventListener('mouseout', function () {
                        for (let j = 1; j <= i; j++) {
                            let tid = 'er' + rid + "_s" + j;
                            document.getElementById(tid).classList.remove('light');
                        }
                    });
                }
                for (let i = 1; i <= 5; i++) {
                    let id = 'er' + rid + "_s" + i;
                    document.getElementById(id).addEventListener('click', function () {
                        for (let j = 1; j <= i; j++) {
                            for (let sel = 1; sel <= 5; sel++) {
                                let tid = 'er' + rid + "_s" + sel;
                                document.getElementById(tid).classList.replace('selected', 'unselected');
                            }
                            for (let sel = 1; sel <= j; sel++) {
                                let tid1 = 'er' + rid + '_s' + sel;
                                document.getElementById(tid1).classList.replace('unselected', 'selected');
                            }
                        }
                        document.getElementById('rating' + rid).value = i;
                    });
                }
            }

            function updateToggle(rid) {
                document.getElementById('edit' + rid).addEventListener('click', () => {
                    let flag = document.getElementById('editFlag' + rid).value;
                    if(flag == 0) {
                        document.getElementById('button' + rid).innerText = "Cancel Edit";
                        document.getElementById('editFlag' + rid).value = '1';
                        document.getElementById('updateRating' + rid).classList.replace('disabled', 'enabled');
                        document.getElementById('updateForm' + rid).classList.replace('disabled', 'enabled');
                    } else {
                        document.getElementById('button' + rid).innerText = "Edit Rating";
                        document.getElementById('editFlag' + rid).value = '0';
                        document.getElementById('updateRating' + rid).classList.replace('enabled', 'disabled');
                        document.getElementById('updateForm' + rid).classList.replace('enabled', 'disabled');
                    }
                });
            }
        </script>
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
                        <td>
                            <div class="rating">
                                <span id="r{{$ratings->r_id}}_s1" class="unselected dim"><i class="fa fa-star star"></i></span>
                                <span id="r{{$ratings->r_id}}_s2" class="unselected dim"><i class="fa fa-star star"></i></span>
                                <span id="r{{$ratings->r_id}}_s3" class="unselected dim"><i class="fa fa-star star"></i></span>
                                <span id="r{{$ratings->r_id}}_s4" class="unselected dim"><i class="fa fa-star star"></i></span>
                                <span id="r{{$ratings->r_id}}_s5" class="unselected dim"><i class="fa fa-star star"></i></span>
                            </div>
                            <script>
                                evaluateRating('r{{$ratings->r_id}}', {{$ratings->rating}});
                            </script>
                        </td>
                        <td>
                            <div style="display: flex">
                                <button id="edit{{$ratings->r_id}}"><span id="button{{$ratings->r_id}}">Edit Rating</span></button>
                                <input type="hidden" id="editFlag{{$ratings->r_id}}" value="0">
                                <div class="rating disabled" id="updateRating{{$ratings->r_id}}">
                                    <span id="er{{$ratings->r_id}}_s1" class="unselected dim"><i class="fa fa-star star"></i></span>
                                    <span id="er{{$ratings->r_id}}_s2" class="unselected dim"><i class="fa fa-star star"></i></span>
                                    <span id="er{{$ratings->r_id}}_s3" class="unselected dim"><i class="fa fa-star star"></i></span>
                                    <span id="er{{$ratings->r_id}}_s4" class="unselected dim"><i class="fa fa-star star"></i></span>
                                    <span id="er{{$ratings->r_id}}_s5" class="unselected dim"><i class="fa fa-star star"></i></span>
                                </div>
                                <form class="disabled" id="updateForm{{$ratings->r_id}}" method="POST" action="{{route('rating.edit')}}" disabled>
                                    @csrf
                                    <input type="hidden" name="rating" id="rating{{$ratings->r_id}}" value="{{$ratings->rating}}">
                                    <input type="hidden" name="charging_station" value="{{$ratings->r_csid}}">
                                    <input type="submit" value="Update rating">
                                </form>
                                <script>
                                    updateToggle({{$ratings->r_id}});
                                    evaluateRating('er{{$ratings->r_id}}', {{$ratings->rating}});
                                    updateStarUI({{$ratings->r_id}});
                                </script>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
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
        .disabled {
            display: none;
        }
        .enabled {
            display: block;
        }
    </style>
    <script>

    </script>
@endsection
