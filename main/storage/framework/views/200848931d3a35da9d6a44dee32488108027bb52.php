<?php $__env->startSection("title", "Ratings"); ?>

<?php $__env->startSection("content"); ?>
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
                        document.getElementById('edit' + rid).classList.replace('btn-warning', 'btn-danger');
                        document.getElementById('editFlag' + rid).value = '1';
                        document.getElementById('updateRating' + rid).classList.replace('disabled', 'enabled');
                        document.getElementById('updateForm' + rid).classList.replace('disabled', 'enabled');
                    } else {
                        document.getElementById('button' + rid).innerText = "Edit Rating";
                        document.getElementById('edit' + rid).classList.replace('btn-danger', 'btn-warning');
                        document.getElementById('editFlag' + rid).value = '0';
                        document.getElementById('updateRating' + rid).classList.replace('enabled', 'disabled');
                        document.getElementById('updateForm' + rid).classList.replace('enabled', 'disabled');
                    }
                });
            }
        </script>
        <div>
            <h2 style="font-weight: bold">
                Index
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
        <div class="d-flex flex-row" style="margin-bottom: 30px">
            <div>
                <a href="<?php echo e(route('rating.provide')); ?>">
                    <button class="btn btn-primary">Rate a charging station</button>
                </a>
            </div>
            <div style="margin-left: 100px">
                <a href="<?php echo e(route('recommendations.index')); ?>">
                    <button class="btn btn-primary">Get recommendation</button>
                </a>
            </div>
        </div>
        <div>
            <h4 style="font-weight: bold">
                Ratings
            </h4>
            <table class="table">
                <tr>
                    <th>Charging Station</th>
                    <th>Location</th>
                    <th>Rating</th>
                    <th><!-- dummy th for edit--></th>
                </tr>
                <?php $__currentLoopData = $data['ratings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ratings): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($ratings->cs_name); ?></td>
                        <td>
                            <?php echo e($ratings->metropolitan); ?>-<?php echo e($ratings->ward_number); ?>, <?php echo e($ratings->district); ?>, <?php echo e($ratings->province); ?>

                        </td>
                        <td>
                            <div class="rating">
                                <span id="r<?php echo e($ratings->r_id); ?>_s1" class="unselected dim"><i class="fa fa-star star"></i></span>
                                <span id="r<?php echo e($ratings->r_id); ?>_s2" class="unselected dim"><i class="fa fa-star star"></i></span>
                                <span id="r<?php echo e($ratings->r_id); ?>_s3" class="unselected dim"><i class="fa fa-star star"></i></span>
                                <span id="r<?php echo e($ratings->r_id); ?>_s4" class="unselected dim"><i class="fa fa-star star"></i></span>
                                <span id="r<?php echo e($ratings->r_id); ?>_s5" class="unselected dim"><i class="fa fa-star star"></i></span>
                            </div>
                            <script>
                                evaluateRating('r<?php echo e($ratings->r_id); ?>', <?php echo e($ratings->rating); ?>);
                            </script>
                        </td>
                        <td>
                            <div style="display: flex;">
                                <button id="edit<?php echo e($ratings->r_id); ?>" class="btn btn-warning"><span id="button<?php echo e($ratings->r_id); ?>">Edit Rating</span></button>
                                <div style="width: 10px"></div>
                                <input type="hidden" id="editFlag<?php echo e($ratings->r_id); ?>" value="0">
                                <div style="width: 10px"></div>
                                <div class="rating disabled" id="updateRating<?php echo e($ratings->r_id); ?>">
                                    <span id="er<?php echo e($ratings->r_id); ?>_s1" class="unselected dim"><i class="fa fa-star star"></i></span>
                                    <span id="er<?php echo e($ratings->r_id); ?>_s2" class="unselected dim"><i class="fa fa-star star"></i></span>
                                    <span id="er<?php echo e($ratings->r_id); ?>_s3" class="unselected dim"><i class="fa fa-star star"></i></span>
                                    <span id="er<?php echo e($ratings->r_id); ?>_s4" class="unselected dim"><i class="fa fa-star star"></i></span>
                                    <span id="er<?php echo e($ratings->r_id); ?>_s5" class="unselected dim"><i class="fa fa-star star"></i></span>
                                </div>
                                <div style="width: 10px"></div>
                                <form class="disabled" id="updateForm<?php echo e($ratings->r_id); ?>" method="POST" action="<?php echo e(route('rating.edit')); ?>" disabled>
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="rating" id="rating<?php echo e($ratings->r_id); ?>" value="<?php echo e($ratings->rating); ?>">
                                    <input type="hidden" name="charging_station" value="<?php echo e($ratings->r_csid); ?>">
                                    <input type="submit" value="Update rating" class="btn btn-success">
                                </form>
                                <script>
                                    updateToggle(<?php echo e($ratings->r_id); ?>);
                                    evaluateRating('er<?php echo e($ratings->r_id); ?>', <?php echo e($ratings->rating); ?>);
                                    updateStarUI(<?php echo e($ratings->r_id); ?>);
                                </script>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Dell\Desktop\EV project\EVCSRecommendation\resources\views/ratings/index.blade.php ENDPATH**/ ?>