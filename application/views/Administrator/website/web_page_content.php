<style>
    #webProfile label {
        font-size: 13px;
        font-weight: 500 !important;
    }

    #webProfile select {
        border-radius: 3px;
    }

    #webProfile .add-button {
        padding: 2.5px;
        width: 100%;
        background-color: #298db4;
        display: block;
        text-align: center;
        color: white;
        cursor: pointer;
        border-radius: 3px;
    }

    #webProfile .add-button:hover {
        background-color: #41add6;
        color: white;
    }

    #webProfile input[type="file"] {
        display: none;
    }

    #webProfile .custom-file-upload {
        border: 1px solid #ccc;
        display: inline-block;
        padding: 5px 12px;
        cursor: pointer;
        margin-top: 5px;
        background-color: #298db4;
        border: none;
        color: white;
    }

    #webProfile .custom-file-upload:hover {
        background-color: #41add6;
    }

    #logoDiv {
        height: 100%;
    }
</style>
<div id="webProfile">
    <form @submit.prevent="updateProfile">
        <div class="row">
            <div class="col-md-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Website Information</legend>
                    <div class="control-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-edit"></i>
                                            Tabs Custom Content Examples
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Home</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-content-above-profile-tab" data-toggle="pill" href="#custom-content-above-profile" role="tab" aria-controls="custom-content-above-profile" aria-selected="false">Profile</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-content-above-messages-tab" data-toggle="pill" href="#custom-content-above-messages" role="tab" aria-controls="custom-content-above-messages" aria-selected="false">Messages</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-content-above-settings-tab" data-toggle="pill" href="#custom-content-above-settings" role="tab" aria-controls="custom-content-above-settings" aria-selected="false">Settings</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="custom-content-above-tabContent">
                                            <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin malesuada lacus ullamcorper dui molestie, sit amet congue quam finibus. Etiam ultricies nunc non magna feugiat commodo. Etiam odio magna, mollis auctor felis vitae, ullamcorper ornare ligula. Proin pellentesque tincidunt nisi, vitae ullamcorper felis aliquam id. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin id orci eu lectus blandit suscipit. Phasellus porta, ante et varius ornare, sem enim sollicitudin eros, at commodo leo est vitae lacus. Etiam ut porta sem. Proin porttitor porta nisl, id tempor risus rhoncus quis. In in quam a nibh cursus pulvinar non consequat neque. Mauris lacus elit, condimentum ac condimentum at, semper vitae lectus. Cras lacinia erat eget sapien porta consectetur.
                                            </div>
                                            <div class="tab-pane fade" id="custom-content-above-profile" role="tabpanel" aria-labelledby="custom-content-above-profile-tab">
                                                Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod pellentesque diam.
                                            </div>
                                            <div class="tab-pane fade" id="custom-content-above-messages" role="tabpanel" aria-labelledby="custom-content-above-messages-tab">
                                                Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna.
                                            </div>
                                            <div class="tab-pane fade" id="custom-content-above-settings" role="tabpanel" aria-labelledby="custom-content-above-settings-tab">
                                                Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet accumsan ex sit amet facilisis.
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card -->
                                </div>
                                <!-- /.card -->
                            </div>

                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </form>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>

<script>
    new Vue({
        el: '#webProfile',
        data() {
            return {
                profile: {
                    Website_SlNo: 0,
                    Website_Name: '',
                    Website_Mobile: '',
                    Website_Email: '',
                    Software_Url: '',
                    Website_Address: '',
                    Opening_Day: '',
                    Website_TagLine: '',
                    Short_Details: '',
                    Facebook_Url: '',
                    Instragram_Url: '',
                    Youtube_Url: '',
                    Developed_By: '',
                    Developer_Url: '',
                },
                headerLogo: '',
                selectedHLogo: null,
                footerLogo: '',
                selectedFtLogo: null,
                mobileLogo: '',
                selectedMLogo: null,
                favLogo: '',
                selectedFLogo: null,
            }
        },
        async created() {
            await this.getWebsiteProfile();
        },
        methods: {
            async getWebsiteProfile() {
                const profile = await axios.get('/get_website_profile').then(res => {
                    return res.data[0];
                });

                let keys = Object.keys(this.profile);
                keys.forEach(key => {
                    this.profile[key] = profile[key];
                })

                if (profile.Header_Logo == null || profile.Header_Logo == '') {
                    this.headerLogo = null;
                } else {
                    this.headerLogo = profile.Header_Logo;
                }

                if (profile.Footer_Logo == null || profile.Footer_Logo == '') {
                    this.footerLogo = null;
                } else {
                    this.footerLogo = profile.Footer_Logo;
                }

                if (profile.Mobile_Logo == null || profile.Mobile_Logo == '') {
                    this.mobileLogo = null;
                } else {
                    this.mobileLogo = profile.Mobile_Logo;
                }

                if (profile.Fav_Logo == null || profile.Fav_Logo == '') {
                    this.favLogo = null;
                } else {
                    this.favLogo = profile.Fav_Logo;
                }

            },
            updateProfile() {
                let fd = new FormData();
                fd.append('hLogo', this.selectedHLogo);
                fd.append('ftLogo', this.selectedFtLogo);
                fd.append('mLogo', this.selectedMLogo);
                fd.append('fLogo', this.selectedFLogo);
                fd.append('profile', JSON.stringify(this.profile));

                axios.post('/update_website_profile', fd, {
                    onUploadProgress: upe => {
                        let progress = Math.round(upe.loaded / upe.total * 100);
                    }
                }).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.getWebsiteProfile();
                    }
                })
            },
            previewHImage() {
                if (event.target.files.length > 0) {
                    this.selectedHLogo = event.target.files[0];
                    this.headerLogo = URL.createObjectURL(this.selectedHLogo);
                } else {
                    this.selectedHLogo = null;
                    this.headerLogo = '';
                }
            },
            previewFtImage() {
                if (event.target.files.length > 0) {
                    this.selectedFtLogo = event.target.files[0];
                    this.footerLogo = URL.createObjectURL(this.selectedFtLogo);
                } else {
                    this.selectedFtLogo = null;
                    this.footerLogo = '';
                }
            },
            previewMImage() {
                if (event.target.files.length > 0) {
                    this.selectedMLogo = event.target.files[0];
                    this.mobileLogo = URL.createObjectURL(this.selectedMLogo);
                } else {
                    this.selectedMLogo = null;
                    this.mobileLogo = '';
                }
            },
            previewFImage() {
                if (event.target.files.length > 0) {
                    this.selectedFLogo = event.target.files[0];
                    this.favLogo = URL.createObjectURL(this.selectedFLogo);
                } else {
                    this.selectedFLogo = null;
                    this.favLogo = '';
                }
            }
        }
    })
</script>