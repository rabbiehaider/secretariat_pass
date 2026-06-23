<div id="visitorRegister" class="container narrow">
    <div class="page-title">
        <div>
            <span class="page-kicker">Visitor Account</span>
            <h1>Create Visitor Account</h1>
            <p>Register once, then apply and track every visit from your dashboard.</p>
        </div>
    </div>

    <form class="panel" @submit.prevent="registerVisitor">
        <div class="form-section-title">Personal Information</div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Full Name</label>
                <input class="form-control" v-model="visitor.name" required>
            </div>
            <div class="form-group col-md-6">
                <label>NID or Passport</label>
                <input class="form-control" v-model="visitor.nid" required>
            </div>
        </div>

        <div class="form-section-title">Login Information</div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Email</label>
                <input class="form-control" type="email" v-model="visitor.email" required>
            </div>
            <div class="form-group col-md-6">
                <label>Phone</label>
                <input class="form-control" v-model="visitor.phone" inputmode="numeric" maxlength="11" pattern="^01[13-9][0-9]{8}$" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Password</label>
                <input class="form-control" type="password" v-model="visitor.password" minlength="6" required>
            </div>
            <div class="form-group col-md-6">
                <label>Confirm Password</label>
                <input class="form-control" type="password" v-model="visitor.confirm_password" minlength="6" required>
            </div>
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea class="form-control" rows="3" v-model="visitor.address" required></textarea>
        </div>

        <button class="btn btn-primary" :disabled="submitting" type="submit">
            {{ submitting ? 'Creating Account...' : 'Create Account and Login' }}
        </button>
        <a class="btn btn-outline-primary ml-2" href="<?php echo site_url('visitor_auth/login'); ?>">Already Registered</a>
    </form>
</div>

<script>
new Vue({
    el: '#visitorRegister',
    data() {
        return {
            submitting: false,
            visitor: {
                name: '',
                email: '',
                phone: '',
                password: '',
                confirm_password: '',
                nid: '',
                address: ''
            }
        }
    },
    methods: {
        registerVisitor() {
            this.submitting = true;
            let fd = new FormData();
            fd.append('data', JSON.stringify(this.visitor));

            axios.post('<?php echo site_url('visitor_register'); ?>', fd).then(res => {
                let r = res.data;
                alert(r.message);
                if (r.success) {
                    window.location = r.redirect;
                }
            }).catch(() => {
                alert('Registration failed');
            }).then(() => {
                this.submitting = false;
            });
        }
    }
});
</script>
