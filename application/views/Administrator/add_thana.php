<style>
	.v-select {
		margin-bottom: 5px;
		background: #fff;
		border-radius: 3px;
	}

	.v-select.open .dropdown-toggle {
		border-bottom: 1px solid #ccc;
	}

	.v-select .dropdown-toggle {
		padding: 0px;
		height: 25px;
		border: none;
	}

	.v-select input[type=search],
	.v-select input[type=search]:focus {
		margin: 0px;
	}

	.v-select .vs__selected-options {
		overflow: hidden;
		flex-wrap: nowrap;
	}

	.v-select .selected-tag {
		margin: 2px 0px;
		white-space: nowrap;
		position: absolute;
		left: 0px;
	}

	.v-select .vs__actions {
		margin-top: -5px;
	}

	.v-select .dropdown-menu {
		width: auto;
		overflow-y: auto;
	}

	#thanaForm label {
		font-size: 13px;
	}

	#thanaForm select {
		border-radius: 3px;
	}

	#thanaForm .add-button {
		padding: 2.5px;
		width: 100%;
		background-color: #298db4;
		display: block;
		text-align: center;
		color: white;
		cursor: pointer;
		border-radius: 3px;
	}

	#thanaForm .add-button:hover {
		background-color: #41add6;
		color: white;
	}
</style>
<div id="thanaForm">
	<div class="row" style="margin: 0;">
		<fieldset class="scheduler-border">
			<legend class="scheduler-border">Thana Entry Form</legend>
			<div class="control-group">
				<div class="col-xs-12 col-md-6 col-md-offset-3">
					<form @submit.prevent="saveThana">
						<div class="form-group clearfix">
							<label class="control-label col-md-4">Thana: <small style="color:red">*</small></label>
							<div class="col-md-7" style="display: flex;align-items:center;margin-bottom:5px;">
								<div style="width: 86%;">
									<v-select v-bind:options="districts" style="margin:0;" v-model="selectedDistrict"
										label="District_Name" placeholder="Select Thana"></v-select>
								</div>
								<div style="width:13%;margin-left:2px;">
									<a href="<?= base_url('thana') ?>" class="add-button" target="_blank" title="Add New Thana"><i class="fa fa-plus" aria-hidden="true"></i></a>
								</div>
							</div>
						</div>

						<div class="form-group clearfix">
							<label class="control-label col-xs-4 col-md-4"> Thana Name:</label>
							<div class="col-xs-7 col-md-7">
								<input type="text" class="form-control" v-model="thana.Thana_Name" required>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="control-label col-md-5"></label>
							<div class="col-md-6 text-right">
								<input type="button" class="btnReset" value="Reset" @click="resetForm">
								<input type="submit" class="btnSave" value="Save">
							</div>
						</div>
					</form>
				</div>
			</div>
		</fieldset>
	</div>

	<div class="row">
		<div class="col-sm-12 form-inline">
			<div class="form-group">
				<label for="filter" class="sr-only">Filter</label>
				<input type="text" class="form-control" v-model="filter" placeholder="Filter">
			</div>
		</div>
		<div class="col-md-12">
			<div class="table-responsive">
				<datatable :columns="columns" :data="thanas" :filter-by="filter" style="margin-bottom: 5px;">
					<template scope="{ row }">
						<tr>
							<td>{{ row.sl }}</td>
							<td>{{ row.Thana_Name }}</td>
							<td>{{ row.District_Name }}</td>
							<td>
								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<i class="btnEdit fa fa-pencil" @click="editThana(row)"></i>
									<i class="btnDelete fa fa-trash" @click="deleteThana(row.Thana_SlNo)"></i>
								<?php } ?>
							</td>
						</tr>
					</template>
				</datatable>
				<datatable-pager v-model="page" type="abbreviated" :per-page="per_page"
					style="margin-bottom: 50px;"></datatable-pager>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#thanaForm',
		data() {
			return {
				thana: {
					Thana_SlNo: 0,
					District_SlNo: 0,
					Thana_Name: '',
				},
				thanas: [],

				districts: [],
				selectedDistrict: null,

				columns: [{
						label: 'Sl',
						field: 'sl',
						align: 'center'
					},
					{
						label: 'Thana Name',
						field: 'Thana_Name',
						align: 'center'
					},
					{
						label: 'District Name',
						field: 'District_Name',
						align: 'center'
					},
					{
						label: 'Action',
						align: 'center',
						filterable: false
					}
				],
				page: 1,
				per_page: 100,
				filter: ''
			}
		},
		created() {
			this.getDistricts();
			this.getThana();
		},
		methods: {
			getDistricts() {
				axios.get('/get_districts').then(res => {
					this.districts = res.data;
				})
			},
			getThana() {
				axios.get('/get_thanas').then(res => {
					this.thanas = res.data.map((item, index) => {
						item.sl = index + 1;
						return item;
					});
				})
			},
			saveThana() {
				if (this.selectedDistrict == null || this.selectedDistrict.District_SlNo == undefined) {
					Swal.fire({
						icon: "error",
						text: "Please select thana",
					});
					return;
				}

				if (this.thana.Thana_Name == '') {
					Swal.fire({
						icon: "error",
						text: "Thana Name is empty!",
					});
					return;
				}

				this.thana.District_SlNo = this.selectedDistrict.District_SlNo;

				let url = '/add_thana';
				if (this.thana.Thana_SlNo != 0) {
					url = '/update_thana';
				}

				axios.post(url, this.thana).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.status) {
						this.resetForm();
						this.getThana();
					}
				})
			},
			editThana(thana) {
				let keys = Object.keys(this.thana);
				keys.forEach(key => {
					this.thana[key] = thana[key];
				})
				this.selectedDistrict = {
					District_SlNo: thana.District_SlNo,
					District_Name: thana.District_Name
				}
			},
			deleteThana(thanaId) {
				if (confirm('Are you sure?')) {
					axios.post('/delete_thana', {
						thanaId: thanaId
					}).then(res => {
						let r = res.data;
						alert(r.message);
						if (r.status) {
							this.getThana();
						}
					})
				}
			},
			resetForm() {
				this.thana = {
					Thana_SlNo: 0,
					District_SlNo: 0,
					Thana_Name: '',
				}
				// this.selectedDistrict = null;
			}
		}
	})
</script>