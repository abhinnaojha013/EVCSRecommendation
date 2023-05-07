<?php $__env->startSection("title", "Charging Stations"); ?>

<?php $__env->startSection("content"); ?>
    <section>
        <h2 style="font-weight: bold">
            Charging Stations
        </h2>
        <hr>
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
        <div class="d-flex flex-row" style="margin-bottom: 30px">
            <div>
                <a href="<?php echo e(route('chargingStation.create')); ?>">
                    <button class="btn btn-primary" style="font-size: 1rem">Add a charging station</button>
                </a>
            </div>
            <div style="margin-left: 100px">
                <a href="<?php echo e(route('metropolitan.create')); ?>">
                    <button class="btn btn-primary" style="font-size: 1rem">Add a metropolitan</button>
                </a>
            </div>
        </div>
        <h4 style="font-weight: bold">
            Search
        </h4>
        <div>
            <form method="post" action="<?php echo e(route('rating.add')); ?>">
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
                            <select id="ward_number" name="ward_number">
                                <option value="0">-Select Ward-</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div>
            <table id="cs_data" class="table"></table>
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
                    let max_wards = wards[0].wards;
                    let option_all = '<option value="0">-Select Ward-</option>';
                    for (let i = 1; i <= max_wards; i++) {
                        option_all = option_all + '<option value="' + i + '">' + i + '</option>';
                    }
                    $('#ward_number').html(option_all);
                }
            });

            $.ajax({
                type: 'POST',
                url: '/chargingStation/getChargingStationMetropolitan',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    metropolitan:  $('#metropolitan').val()
                },
                success: function (chargingStations) {
                    if (chargingStations.length === 0) {
                        document.getElementById('cs_data').innerHTML = 'No data available';
                    } else {
                        let cs_list = "<h4 style='font-weight: bold'>List of Charging Stations</h4>" +
                            "<tr>" +
                                "<th>Charging Station Name</th>" +
                                "<th>Location</th>" +
                                "<th></th>" +
                            "</tr>";
                        for (let i = 0; i < chargingStations.length; i++) {
                            cs_list = cs_list +
                                "<tr>" +
                                    "<td>" + chargingStations[i].cs_name + "</td>" +
                                    "<td>" +
                                        chargingStations[i].metropolitan + "-" +
                                        chargingStations[i].ward_number + ", " +
                                        chargingStations[i].district + ", " +
                                        chargingStations[i].province +
                                    "</td>" +
                                    "<td>" +
                                        "<a href = '/Charging-Station/" + chargingStations[i].cs_id + "/edit'>" +
                                            "<button class='btn btn-warning'>Edit</button>" +
                                        "</a>" +
                                    "</td>" +
                                "</tr>"
                        }
                        document.getElementById('cs_data').innerHTML = cs_list;
                    }
                }
            });
        });

        $('#ward_number').change(function () {
            $.ajax({
                type: 'POST',
                url: '/chargingStation/getChargingStationWard',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    metropolitan:  $('#metropolitan').val(),
                    ward_number:  $('#ward_number').val()

                },
                success: function (chargingStations) {
                    if(chargingStations.length === 0) {
                        document.getElementById('cs_data').innerHTML = 'No data available';
                    } else {
                        let cs_list = "<h4 style='font-weight: bold'>List of Charging Stations</h4>" +
                            "<tr>" +
                                "<th>Charging Station Name</th>" +
                                "<th>Location</th>" +
                                "<th></th>" +
                            "</tr>";
                        for (let i = 0; i < chargingStations.length; i++) {
                            cs_list = cs_list +
                                "<tr>" +
                                    "<td>" + chargingStations[i].cs_name + "</td>" +
                                    "<td>" +
                                        chargingStations[i].metropolitan + "-" +
                                        chargingStations[i].ward_number + ", " +
                                        chargingStations[i].district + ", " +
                                        chargingStations[i].province +
                                    "</td>" +
                                    "<td>" +
                                        "<a href = '/Charging-Station/" + chargingStations[i].cs_id + "/edit'>" +
                                            "<button class = 'btn btn-warning'>Edit</button>" +
                                        "</a>" +
                                    "</td>" +
                                "</tr>"
                        }
                        document.getElementById('cs_data').innerHTML = cs_list;
                    }
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\EV project\EVCSRecommendation\resources\views/chargingStation/index.blade.php ENDPATH**/ ?>