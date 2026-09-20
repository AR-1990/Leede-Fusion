<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page_title', 'Dashboard'); ?>
<?php $__env->startSection('page_subtitle', 'Revenue, orders, and live store activity'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-view">
    <!-- 4 Stat Cards: Natural Case, Normal Weights, Clean Human Styling -->
    <div class="stats-grid">
        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="stat-card">
                <div class="stat-card-top">
                    <span class="stat-label">
                        <?php if($stat['label'] === 'TOTAL REVENUE'): ?>
                            Total revenue
                        <?php elseif($stat['label'] === 'TOTAL ORDERS'): ?>
                            Total orders
                        <?php elseif($stat['label'] === 'TOTAL CUSTOMERS'): ?>
                            Customers
                        <?php elseif($stat['label'] === 'AVG ORDER VALUE'): ?>
                            Average order value
                        <?php else: ?>
                            <?php echo e(ucwords(strtolower($stat['label']))); ?>

                        <?php endif; ?>
                    </span>
                    <div class="stat-icon-box">
                        <?php if($index === 0): ?>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        <?php elseif($index === 1): ?>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                        <?php elseif($index === 2): ?>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <?php else: ?>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="stat-value"><?php echo e($stat['value']); ?></div>
                <div class="stat-sub">
                    <?php if($index === 0): ?>
                        All-time gross sales
                    <?php elseif($index === 1): ?>
                        Orders placed across all channels
                    <?php elseif($index === 2): ?>
                        Registered customer accounts
                    <?php else: ?>
                        Calculated average checkout
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Live Orders Section -->
    <div class="admin-surface">
        <div class="admin-action-bar">
            <div>
                <h2 class="section-title-small">Recent orders</h2>
                <p class="section-subtitle-small">Live transactions from your storefront.</p>
            </div>
            <a href="<?php echo e(route('admin.orders')); ?>" class="btn-table-action">
                <span>View all orders</span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <span style="font-family: ui-monospace, SFMono-Regular, monospace; font-size: 12px; font-weight: 600; color: #0f172a;">
                                    #ORD-<?php echo e(str_pad($order->id, 4, '0', STR_PAD_LEFT)); ?>

                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 500; color: #0f172a;">
                                    <?php echo e($order->user->name ?? $order->customer_name ?? 'Guest customer'); ?>

                                </div>
                                <div style="font-size: 12px; color: #64748b;">
                                    <?php echo e($order->user->email ?? $order->customer_email ?? 'Direct checkout'); ?>

                                </div>
                            </td>
                            <td style="color: #64748b; font-size: 13px;">
                                <?php echo e($order->created_at->format('M d, Y')); ?>

                            </td>
                            <td style="font-weight: 500; color: #0f172a;">
                                Rs. <?php echo e(number_format((float) $order->total_amount)); ?>

                            </td>
                            <td>
                                <span class="status-badge <?php echo e($order->status); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $order->status))); ?>

                                </span>
                            </td>
                            <td style="text-align: right;">
                                <a href="<?php echo e(route('admin.orders')); ?>?manage=<?php echo e($order->id); ?>" class="btn-table-action">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 48px; color: #64748b; font-size: 13px;">
                                No customer orders recorded yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mac/Documents/Wahla-Cloth-House/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>