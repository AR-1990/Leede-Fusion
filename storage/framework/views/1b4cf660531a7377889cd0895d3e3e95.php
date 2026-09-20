<?php $__env->startSection('title', 'Users'); ?>
<?php $__env->startSection('page_title', 'Users'); ?>
<?php $__env->startSection('page_subtitle', 'Registered accounts and access roles'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-users-view">
    <div class="admin-action-bar">
        <p>List of all registered customers and administrators in the store database.</p>
    </div>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>ROLE</th>
                    <th>JOINED</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-weight: 700;">#<?php echo e($user->id); ?></td>
                        <td style="font-weight: 600;"><?php echo e($user->name); ?></td>
                        <td>
                            <span style="font-family: ui-monospace, monospace; font-size: 13px;"><?php echo e($user->email); ?></span>
                        </td>
                        <td>
                            <span class="status-badge <?php echo e($user->is_admin ? 'completed' : 'pending'); ?>">
                                <?php echo e($user->is_admin ? 'ADMIN' : 'CUSTOMER'); ?>

                            </span>
                        </td>
                        <td style="color: #64748b; font-size: 13px;">
                            <?php echo e($user->created_at ? $user->created_at->format('M d, Y') : '—'); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colSpan="5" style="text-align: center; padding: 48px; color: #94a3b8;">
                            No registered users found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($users->hasPages()): ?>
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            <?php echo e($users->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mac/Documents/Wahla-Cloth-House/resources/views/admin/users.blade.php ENDPATH**/ ?>