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
                            <button class="btn btn-sm btn-info mr-1" @click="showProfile(user)">Profile</button>
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

    <!-- VISITOR PROFILE MODAL -->
    <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 8px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <div class="modal-header bg-navy text-white">
                    <h5 class="modal-title" id="profileModalLabel" style="font-weight: bold; color: #fff;">Visitor Profile Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" v-if="selectedUser" style="padding: 24px; background: #f8fafc;">
                    <div class="text-center mb-4">
                        <img :src="selectedUser.photo_url || defaultPhoto" alt="Profile" class="rounded img-thumbnail" style="width: 150px; height: 150px; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                        <h4 class="mt-2 font-weight-bold text-dark">{{ selectedUser.name }}</h4>
                        <span v-if="selectedUser.status == 0" class="badge badge-warning">Pending Approval</span>
                        <span v-else-if="selectedUser.status == 1" class="badge badge-success">Approved</span>
                        <span v-else-if="selectedUser.status == 2" class="badge badge-danger">Suspended</span>
                    </div>
                    <table class="table table-bordered bg-white">
                        <tbody>
                            <tr>
                                <th style="width: 35%;" class="bg-light text-muted small text-uppercase">Email</th>
                                <td class="font-weight-bold">{{ selectedUser.email }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted small text-uppercase">Phone</th>
                                <td class="font-weight-bold">{{ selectedUser.phone }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted small text-uppercase">NID / Passport</th>
                                <td class="font-weight-bold">{{ selectedUser.nid }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted small text-uppercase">Address</th>
                                <td>{{ selectedUser.address }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted small text-uppercase">Registered</th>
                                <td class="small">{{ selectedUser.created_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer" v-if="selectedUser" style="background: #edf2f7; justify-content: flex-end;">
                    <button v-if="selectedUser.status != 1" class="btn btn-success" @click="approveUserFromModal(selectedUser)">Approve Account</button>
                    <button v-if="selectedUser.status == 1" class="btn btn-danger" @click="rejectUserFromModal(selectedUser)">Suspend Account</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#adminUsers',
        data() {
            return {
                users: [],
                selectedUser: null,
                defaultPhoto: "/assets/images/noimage.PNG"
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
                fd.append('data', JSON.stringify({
                    id: user.id
                }));
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
                fd.append('data', JSON.stringify({
                    id: user.id
                }));
                axios.post('<?php echo site_url('reject_visitor_user'); ?>', fd).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.getUsers();
                    }
                });
            },
            showProfile(user) {
                this.selectedUser = user;
                $('#profileModal').modal('show');
            },
            approveUserFromModal(user) {
                $('#profileModal').modal('hide');
                setTimeout(() => {
                    this.approveUser(user);
                }, 300);
            },
            rejectUserFromModal(user) {
                $('#profileModal').modal('hide');
                setTimeout(() => {
                    this.rejectUser(user);
                }, 300);
            }
        }
    });
</script>