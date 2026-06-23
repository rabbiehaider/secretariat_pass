<div id="visitorLogin" class="auth-wrap">
    <div class="panel">
        <div class="panel-kicker">Visitor Account</div>
        <h1>Visitor Login</h1>
        <p class="text-muted">Use your registered email or phone number.</p>

        <form @submit.prevent="loginVisitor">
            <div class="form-group">
                <label>Email or Phone</label>
                <input class="form-control" v-model="login.login" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input class="form-control" type="password" v-model="login.password" required>
            </div>
            <button class="btn btn-primary btn-block" :disabled="submitting" type="submit">
                {{ submitting ? 'Signing In...' : 'Login' }}
            </button>
            <a class="btn btn-outline-primary btn-block mt-2" href="<?php echo site_url('visitor_auth/register'); ?>">Create Visitor Account</a>
        </form>
    </div>
</div>

<script>
new Vue({
    el: '#visitorLogin',
    data() {
        return {
            submitting: false,
            login: {
                login: '',
                password: ''
            }
        }
    },
    methods: {
        loginVisitor() {
            this.submitting = true;
            let fd = new FormData();
            fd.append('data', JSON.stringify(this.login));

            axios.post('<?php echo site_url('visitor_login'); ?>', fd).then(res => {
                let r = res.data;
                alert(r.message);
                if (r.success) {
                    window.location = r.redirect;
                }
            }).catch(() => {
                alert('Login failed');
            }).then(() => {
                this.submitting = false;
            });
        }
    }
});
</script>
