<template>
    <div class="row justify-content-center">
        <div class="col-md-8 mx-auto">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Employee Form</h1>
            </div>

            <div class="card">
                <div class="card-header">
                    <router-link
                        :to="{ name: 'employee.index' }"
                        class="float-right btn btn-secondary btn-sm"
                    >
                        <i class="fas fa-arrow-left" aria-hidden="true"></i> Back
                    </router-link>
                </div>

                <div class="card-body">
                    <form @submit.prevent="storeEmployee">
                        <!-- First Name -->
                        <div class="form-group row">
                            <label for="first_name" class="col-md-4 col-form-label text-md-right">
                                First name <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-6">
                                <input
                                    id="first_name"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.first_name }"
                                    name="first_name"
                                    v-model="employeeForm.first_name"
                                    required
                                    autocomplete="given-name"
                                    autofocus
                                >
                                <small class="text-danger" v-if="errors.first_name">
                                    {{ errors.first_name[0] }}
                                </small>
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div class="form-group row">
                            <label for="last_name" class="col-md-4 col-form-label text-md-right">
                                Last name <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-6">
                                <input
                                    id="last_name"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.last_name }"
                                    name="last_name"
                                    v-model="employeeForm.last_name"
                                    required
                                    autocomplete="family-name"
                                >
                                <small class="text-danger" v-if="errors.last_name">
                                    {{ errors.last_name[0] }}
                                </small>
                            </div>
                        </div>

                        <!-- Middle Name -->
                        <div class="form-group row">
                            <label for="middle_name" class="col-md-4 col-form-label text-md-right">
                                Middle Name
                            </label>
                            <div class="col-md-6">
                                <input
                                    id="middle_name"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.middle_name }"
                                    name="middle_name"
                                    v-model="employeeForm.middle_name"
                                    autocomplete="additional-name"
                                >
                                <small class="text-danger" v-if="errors.middle_name">
                                    {{ errors.middle_name[0] }}
                                </small>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-group row">
                            <label for="address" class="col-md-4 col-form-label text-md-right">Address</label>
                            <div class="col-md-6">
                                <input
                                    id="address"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.address }"
                                    name="address"
                                    v-model="employeeForm.address"
                                    autocomplete="street-address"
                                >
                                <small class="text-danger" v-if="errors.address">
                                    {{ errors.address[0] }}
                                </small>
                            </div>
                        </div>

                        <!-- Department -->
                        <div class="form-group row">
                            <label for="department_id" class="col-md-4 col-form-label text-md-right">
                                Department <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-6">
                                <select
                                    id="department_id"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.department_id }"
                                    name="department_id"
                                    v-model="employeeForm.department_id"
                                    required
                                >
                                    <option value="">Select Department</option>
                                    <option
                                        v-for="department in departments"
                                        :key="department.id"
                                        :value="department.id"
                                    >
                                        {{ department.name }}
                                    </option>
                                </select>
                                <small class="text-danger" v-if="errors.department_id">
                                    {{ errors.department_id[0] }}
                                </small>
                            </div>
                        </div>

                        <!-- Country -->
                        <div class="form-group row">
                            <label for="country_id" class="col-md-4 col-form-label text-md-right">
                                Country <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-6">
                                <select
                                    id="country_id"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.country_id }"
                                    name="country_id"
                                    v-model="employeeForm.country_id"
                                    @change="onCountryChange"
                                    required
                                >
                                    <option value="">Select Country</option>
                                    <option
                                        v-for="country in countries"
                                        :key="country.id"
                                        :value="country.id"
                                    >
                                        {{ country.name }}
                                    </option>
                                </select>
                                <small class="text-danger" v-if="errors.country_id">
                                    {{ errors.country_id[0] }}
                                </small>
                            </div>
                        </div>

                        <!-- State (filtered by country) -->
                        <div class="form-group row">
                            <label for="state_id" class="col-md-4 col-form-label text-md-right">
                                State <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-6">
                                <select
                                    id="state_id"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.state_id }"
                                    name="state_id"
                                    v-model="employeeForm.state_id"
                                    @change="onStateChange"
                                    :disabled="!employeeForm.country_id"
                                    required
                                >
                                    <option value="">
                                        {{ employeeForm.country_id ? 'Select State' : 'Select a country first' }}
                                    </option>
                                    <option
                                        v-for="state in states"
                                        :key="state.id"
                                        :value="state.id"
                                    >
                                        {{ state.name }}
                                    </option>
                                </select>
                                <small class="text-danger" v-if="errors.state_id">
                                    {{ errors.state_id[0] }}
                                </small>
                            </div>
                        </div>

                        <!-- City (filtered by state) -->
                        <div class="form-group row">
                            <label for="city_id" class="col-md-4 col-form-label text-md-right">
                                City <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-6">
                                <select
                                    id="city_id"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.city_id }"
                                    name="city_id"
                                    v-model="employeeForm.city_id"
                                    :disabled="!employeeForm.state_id"
                                    required
                                >
                                    <option value="">
                                        {{ employeeForm.state_id ? 'Select City' : 'Select a state first' }}
                                    </option>
                                    <option
                                        v-for="city in cities"
                                        :key="city.id"
                                        :value="city.id"
                                    >
                                        {{ city.name }}
                                    </option>
                                </select>
                                <small class="text-danger" v-if="errors.city_id">
                                    {{ errors.city_id[0] }}
                                </small>
                            </div>
                        </div>

                        <!-- Zip Code -->
                        <div class="form-group row">
                            <label for="zip_code" class="col-md-4 col-form-label text-md-right">
                                Zip Code <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-6">
                                <input
                                    id="zip_code"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.zip_code }"
                                    name="zip_code"
                                    v-model="employeeForm.zip_code"
                                    required
                                    autocomplete="postal-code"
                                >
                                <small class="text-danger" v-if="errors.zip_code">
                                    {{ errors.zip_code[0] }}
                                </small>
                            </div>
                        </div>

                        <!-- Birthdate -->
                        <div class="form-group row">
                            <label for="birthdate" class="col-md-4 col-form-label text-md-right">Birthdate</label>
                            <div class="col-md-6">
                                <datepicker
                                    v-model="employeeForm.birthdate"
                                    input-class="form-control"
                                    :class="{ 'is-invalid': errors.birthdate }"
                                    name="birthdate"
                                    format="yyyy-MM-dd"
                                    :append-to-body="true"
                                />
                                <small class="text-danger" v-if="errors.birthdate">
                                    {{ errors.birthdate[0] }}
                                </small>
                            </div>
                        </div>

                        <!-- Date Hired -->
                        <div class="form-group row">
                            <label for="date_hired" class="col-md-4 col-form-label text-md-right">Date Hired</label>
                            <div class="col-md-6">
                                <datepicker
                                    v-model="employeeForm.date_hired"
                                    input-class="form-control"
                                    :class="{ 'is-invalid': errors.date_hired }"
                                    name="date_hired"
                                    format="yyyy-MM-dd"
                                    :append-to-body="true"
                                />
                                <small class="text-danger" v-if="errors.date_hired">
                                    {{ errors.date_hired[0] }}
                                </small>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    :disabled="loading"
                                >
                                    <span
                                        v-if="loading"
                                        class="spinner-border spinner-border-sm mr-2"
                                        role="status"
                                        aria-hidden="true"
                                    ></span>
                                    {{ loading ? 'Saving…' : 'Add New' }}
                                </button>
                                <router-link
                                    :to="{ name: 'employee.index' }"
                                    class="btn btn-light ml-2"
                                >
                                    Cancel
                                </router-link>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Form from 'vform'
import Datepicker from 'vuejs-datepicker'
import axios from 'axios'
import moment from "moment";
export default {
    components: {
        Datepicker
    },
    data(){
        return{
            employeeForm: new Form({
                last_name: '',
                first_name: '',
                middle_name: '',
                address: '',
                department_id: '',
                country_id: '',
                city_id: '',
                state_id: '',
                zip_code: '',
                birthdate: '',
                date_hired: '',
            }),
            countries: [],
            cities: [],
            states: [],
            departments: [],
            errors: '',
        }
    },
    mounted(){
        this.getCountries();
        this.getCities();
        this.getStates();
        this.getDepartments();
    },
    methods: {
        async getCountries(){
            let res = await axios.get('/api/countries')
            this.countries = res.data.data
        },
        async getCities(){
            let res = await axios.get('/api/cities')
            this.cities = res.data.data
        },
        async getStates(){
            let res = await axios.get('/api/states')
            this.states = res.data.data
        },
        async getDepartments(){
            let res = await axios.get('/api/departments')
            this.departments = res.data.data
        },
        async storeEmployee(){
            this.employeeForm.birthdate = this.formateDate(this.employeeForm.birthdate);
            this.employeeForm.date_hired = this.formateDate(this.employeeForm.date_hired);
            try {
                await axios.post('/api/employees', this.employeeForm)
                await this.$router.push({name: 'employee.index'});
            } catch (e) {
                this.errors = e.response.data.errors
            }


        },
        formateDate(value){
            if(value){
                return moment(String(value)).format("YYYYMMDD");
            }
        }
    }
}
</script>

<style>

</style>
