<?php $__env->startSection("title", "Recommendation"); ?>

<?php $__env->startSection("content"); ?>
    <script>
        navigator.geolocation.getCurrentPosition( function(position) {});
    </script>
    <section>
        <div>
            <h2 style="font-weight: bold">
                Recommendations
            </h2>
            <hr>
        </div>
        <div>
            <?php if(\Illuminate\Support\Facades\Session::has('success')): ?>
                <p class="alert alert-success" role="alert">
                    <?php echo e(\Illuminate\Support\Facades\Session::get('success')); ?>

                </p>
            <?php endif; ?>
            <?php if(\Illuminate\Support\Facades\Session::has('error')): ?>
                <p class="alert alert-danger" role="alert">
                    <?php echo e(\Illuminate\Support\Facades\Session::get('error')); ?>

                </p>
            <?php endif; ?>
        </div>
        <div>

            <div>
                <form method="POST" action="<?php echo e(route('getRecommendation')); ?>">
                    <?php echo csrf_field(); ?>
                    <table class="table">
                        <tr>
                            <td>
                                <label for="province">Province:</label>
                            </td>
                            <td>
                                <select id="province" name="province">
                                    <option value="0">-Select Province-</option>
                                    <?php $__currentLoopData = $data['provinces']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($province->id); ?>"><?php echo e($province->province_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                <select id="ward_number" name="ward_number" disabled>
                                    <option value="0">-Select Ward-</option>
                                </select>
                                <span>
                            <input type="checkbox" id="toggle_ward" checked>
                                    <label for="toggle_ward">Exclude Ward</label>
                            <input type="hidden" id="ward_enabled" name="ward_enabled" value="0">
                            <input type="hidden" id="latitude" name="latitude" value="NULL">
                            <input type="hidden" id="longitude" name="longitude" value="NULL">

                            <script>
                                navigator.geolocation.getCurrentPosition( function(position) {
                                    document.getElementById('latitude').value = position.coords.latitude;
                                    document.getElementById('longitude').value = position.coords.longitude;
                                });
                            </script>
                        </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="width: 120px"></div>
                            </td>
                            <td>
                                <input type="submit" value="Get recommendation" id="submit" class="btn btn-success">
                                <p id="error_submit" class="alert alert-danger" role="alert" style="display:none;"></p>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

            <div>
                <?php if($data['recommendations'] == []): ?>

                <?php elseif($data['recommendations'][0]->isEmpty()): ?>
                    <p>No match found on location</p>
                <?php else: ?>
                    <div class="d-flex flex-column justify-content-center">
                        <table class="table">
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Estimated Rating</th>
                                <th>Distance</th>
                                <th>Duration in car</th>
                                <th></th>
                            </tr>
                            <?php
                            $i = 0;
                            ?>
                            <?php $__currentLoopData = $data['recommendations']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!$rec->isEmpty()): ?>
                                    <tr>
                                        <td><?php echo e($rec[0]->cs_name); ?></td>
                                        <td><?php echo e($rec[0]->metropolitan); ?>-<?php echo e($rec[0]->ward_number); ?>, <?php echo e($rec[0]->district); ?>, <?php echo e($rec[0]->province); ?></td>
                                        <td><?php echo e(round($data['estimated_rating'][$i], 2)); ?></td>
                                        <td><?php echo e(round($data['distances'][$i], 2)); ?> km</td>
                                        <td><?php echo e(round($data['durations'][$i], 2)); ?> minutes</td>
                                        <td>
                                            <a id="a_<?php echo $i ?>" target="_blank">
                                                <script>
                                                    navigator.geolocation.getCurrentPosition( function(position) {
                                                        document.querySelector('#a_<?php echo $i ?>').href = 'https://www.openstreetmap.org/directions?engine=fossgis_osrm_car&route=' + <?php echo e($rec[0]->latitude); ?> + '%2C' + <?php echo e($rec[0]->longitude); ?> + '%3B' + position.coords.latitude + '%2C' + position.coords.longitude;
                                                    });
                                                </script>
                                                <button class="btn btn-info" id="map_view_<?php echo $i ?>" name="map_view_<?php echo $i ?>">
                                                    Get directions
                                                </button>
                                            </a>
                                        </td>
                                        <?php
                                        $i++;
                                        ?>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="d-flex flex-row">
            <div>
                <a href="<?php echo e(route('ratings.index')); ?>">
                    <button class="btn btn-danger" style="font-size: 1rem">Back to Main</button>
                </a>
            </div>
            <div style="margin-left: 25px">
                <a href="<?php echo e(route('rating.provide')); ?>">
                    <button class="btn btn-primary" style="font-size: 1rem">Rate a charging station instead</button>
                </a>
            </div>
        </div>
    </section>
    <script>
        document.getElementById('toggle_ward').addEventListener('change', function () {
            if(document.getElementById('toggle_ward').checked === true) {
                document.getElementById('ward_enabled').value = '0';
                document.getElementById('ward_number').disabled = true;
            } else {
                document.getElementById('ward_enabled').value = '1';
                document.getElementById('ward_number').disabled = false;

            }
        });
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

        $('#submit').click(function () {
            if($('#metropolitan').val() == 0) {
                document.getElementById('error_submit').style.display = 'block';
                $('#error_submit').html("Please select a valid location.");
                return false;
            }
        });

        $('')
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\EV project\EVCSRecommendation\resources\views/recommend/recommend.blade.php ENDPATH**/ ?>