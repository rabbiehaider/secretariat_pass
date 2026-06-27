<div id="adminUsers" class="container">
    <div class="page-title row align-items-center">
        <div class="col">
            <span class="page-kicker">User Management</span>
            <h1>Visitor Accounts</h1>
            <p>Manage and approve registered visitor user accounts.</p>
        </div>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>NID / Passport</th>
                        <th>Address</th>
                        <th>Registered At</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>
                            <img :src="user.photo_url || defaultPhoto" alt="Profile" class="rounded-circle img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td><strong>{{ user.name }}</strong></td>
                        <td>
                            <div>{{ user.email }}</div>
                            <small class="text-muted">{{ user.phone }}</small>
                        </td>
                        <td>{{ user.nid }}</td>
                        <td><small>{{ user.address }}</small></td>
                        <td><small>{{ user.created_at }}</small></td>
                        <td>
                            <span v-if="user.status == 0" class="badge badge-warning">Pending</span>
                            <span v-else-if="user.status == 1" class="badge badge-success">Approved</span>
                            <span v-else-if="user.status == 2" class="badge badge-danger">Suspended</span>
                        </td>
                        <td>
                            <button v-if="user.status != 1" class="btn btn-sm btn-success mr-1" @click="approveUser(user)">Approve</button>
                            <button v-if="user.status == 1" class="btn btn-sm btn-danger" @click="rejectUser(user)">Suspend</button>
                        </td>
                    </tr>
                    <tr v-if="users.length == 0">
                        <td colspan="8" class="text-center text-muted">No users found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
new Vue({
    el: '#adminUsers',
    data() {
        return {
            users: [],
            defaultPhoto: "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23ccc'><path d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4'/></svg>"
        }
    },
    created() {
        this.getUsers();
    },
    methods: {
        getUsers() {
            axios.get('<?php echo site_url('get_visitor_users'); ?>').then(res => {
                let r = res.data;
                if (r.success) {
                    this.users = r.users;
                } else {
                    alert(r.message);
                }
            });
        },
        approveUser(user) {
            if (!confirm('Approve this visitor user account?')) {
                return;
            }
            let fd = new FormData();
            fd.append('data', JSON.stringify({ id: user.id }));
            axios.post('<?php echo site_url('approve_visitor_user'); ?>', fd).then(res => {
                let r = res.data;
                alert(r.message);
                if (r.success) {
                    this.getUsers();
                }
            });
        },
        rejectUser(user) {
            if (!confirm('Suspend this visitor user account?')) {
                return;
            }
            let fd = new FormData();
            fd.append('data', JSON.stringify({ id: user.id }));
            axios.post('<?php echo site_url('reject_visitor_user'); ?>', fd).then(res => {
                let r = res.data;
                alert(r.message);
                if (r.success) {
                    this.getUsers();
                }
            });
        }
    }
});
</script>
