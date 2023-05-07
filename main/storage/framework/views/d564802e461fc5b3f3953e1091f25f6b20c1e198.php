<?php $__env->startSection("title", "Add Metropolitan"); ?>

<?php $__env->startSection("content"); ?>
    <section>
        <div>
            <h2 style="font-weight: bold">
                Add Metropolitan
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
            <form method="post" action="<?php echo e(route('metropolitan.store')); ?>">
                <?php echo csrf_field(); ?>
                <table class="table">
                    <tr>
                        <td>
                            <label for="province">Province:</label>
                        </td>
                        <td>
                            <select id="province" name="province" required>
                                <option value="">-Select Province-</option>
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
                            <input type="submit" value="Add Metropolitan" class="btn btn-success">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div>
            <a href="<?php echo e(route('chargingStation.index')); ?>">
                <button class="btn btn-danger" style="font-size: 1rem">Return to main</button>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\EV project\EVCSRecommendation\resources\views/metropolitan/addMetropolitan.blade.php ENDPATH**/ ?>