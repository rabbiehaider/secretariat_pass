<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Visitor</th>
                <th>Phone</th>
                <th>Visit</th>
                <th>Department</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $row): ?>
                <tr>
                    <td><?php echo (int) $row->id; ?></td>
                    <td><?php echo html_escape($row->name); ?></td>
                    <td><?php echo html_escape($row->phone); ?></td>
                    <td><?php echo html_escape($row->visit_date); ?></td>
                    <td><?php echo html_escape($row->department_name); ?></td>
                    <td><span class="badge badge-status badge-<?php echo html_escape($row->status); ?>"><?php echo html_escape($row->status); ?></span></td>
                    <td>
                        <?php if ($row->status === 'pending'): ?>
                            <a class="btn btn-sm btn-success" href="<?php echo site_url('admin/approve/' . $row->id); ?>">Approve</a>
                            <form class="inline-form" method="post" action="<?php echo site_url('admin/reject/' . $row->id); ?>">
                                <input type="hidden" name="rejected_reason" value="Rejected by admin">
                                <button class="btn btn-sm btn-danger" type="submit">Reject</button>
                            </form>
                        <?php elseif ($row->status === 'approved'): ?>
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo site_url('visitor/card/' . $row->qr_token); ?>">Card</a>
                        <?php else: ?>
                            <?php echo html_escape($row->rejected_reason); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($applications)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">No records found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>