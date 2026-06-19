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
            <div class="col-md-9">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Website Information</legend>
                    <div class="control-group">
                        <div class="row">
                            <div class="col-md-6 no-padding-right">
                                <div class="form-group clearfix">
                                    <label class="control-label col-md-4">Company Name</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" v-model="profile.Website_Name" required>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="control-label col-md-4">Company Phone</label>
                                    <div class="col-md-7">
                                        <input type="number" class="form-control" v-model="profile.Website_Mobile" required>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="control-label col-md-4">Company Email</label>
                                    <div class="col-md-7">
                                        <input type="email" class="form-control" v-model="profile.Website_Email" required>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="control-label col-md-4">Software Url</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" v-model="profile.Software_Url" required>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="control-label col-md-4">Tag Line</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" v-model="profile.Website_TagLine" required>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="control-label col-md-4">Opening Day</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" v-model="profile.Opening_Day" required>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="control-label col-md-4 no-padding-right" for="about">Company Address</label>
                                    <div class="col-md-7">
                                        <textarea class="form-control" id="about" cols="30" rows="2" v-model="profile.Website_Address" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 no-padding-right">
                                <div class="form-group clearfix">
                                    <label class="control-label col-md-4">Facebook Url</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" v-model="profile.Facebook_Url">
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="control-label col-md-4">Instagram Url</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" v-model="profile.Instragram_Url">
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="control-label col-md-4">Youtube Url</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" v-model="profile.Youtube_Url">
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="control-label col-md-4">Developed By</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" v-model="profile.Developed_By" required>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="control-label col-md-4">Developer Url</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" v-model="profile.Developer_Url" required>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="control-label col-md-4" for="about">Short Details</label>
                                    <div class="col-md-7">
                                        <textarea class="form-control" id="about" cols="30" rows="3" v-model="profile.Short_Details" required></textarea>
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <div class="col-md-7 col-md-offset-4 text-right">
                                        <input type="submit" class="btnSave" value="Update Profile">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-3">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Website Images</legend>
                    <div class="control-group">

                        <div class="col-md-6">
                            <div class="form-group clearfix"
                                style="display: flex;align-items:center;flex-direction:column;">
                                <div style="width: 100px;height:100px;border: 1px solid #ccc;overflow:hidden;">
                                    <img id="logoDiv" v-if="headerLogo == '' || headerLogo == null"
                                        src="/uploads/no_image.jpg">
                                    <img id="logoDiv" v-if="headerLogo != '' && headerLogo != null" v-bind:src="headerLogo">
                                </div>
                                <div style="text-align:center;">
                                    <label class="custom-file-upload">
                                        <input type="file" @change="previewHImage" />
                                        Header Logo
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group clearfix"
                                style="display: flex;align-items:center;flex-direction:column;">
                                <div style="width: 100px;height:100px;border: 1px solid #ccc;overflow:hidden;">
                                    <img id="logoDiv" v-if="footerLogo == '' || footerLogo == null"
                                        src="/uploads/no_image.jpg">
                                    <img id="logoDiv" v-if="footerLogo != '' && footerLogo != null" v-bind:src="footerLogo">
                                </div>
                                <div style="text-align:center;">
                                    <label class="custom-file-upload">
                                        <input type="file" @change="previewFtImage" />
                                        Footer Logo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group clearfix"
                                style="display: flex;align-items:center;flex-direction:column;">
                                <div style="width: 100px;height:100px;border: 1px solid #ccc;overflow:hidden;">
                                    <img id="logoDiv" v-if="mobileLogo == '' || mobileLogo == null"
                                        src="/uploads/no_image.jpg">
                                    <img id="logoDiv" v-if="mobileLogo != '' && mobileLogo != null" v-bind:src="mobileLogo">
                                </div>
                                <div style="text-align:center;">
                                    <label class="custom-file-upload">
                                        <input type="file" @change="previewMImage" />
                                        Mobile Logo
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group clearfix"
                                style="display: flex;align-items:center;flex-direction:column;">
                                <div style="width: 100px;height:100px;border: 1px solid #ccc;overflow:hidden;">
                                    <img id="logoDiv" v-if="favLogo == '' || favLogo == null"
                                        src="/uploads/no_image.jpg">
                                    <img id="logoDiv" v-if="favLogo != '' && favLogo != null" v-bind:src="favLogo">
                                </div>
                                <div style="text-align:center;">
                                    <label class="custom-file-upload">
                                        <input type="file" @change="previewFImage" />
                                        Fav Icon
                                    </label>
                                </div>
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